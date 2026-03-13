<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentAuditLog;
use App\Models\UserSubscription;
use App\Models\WeeklyPayment;
use App\Models\Account;
use App\Services\MomoWebhookService;
use App\Services\SensitiveDataEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PaymentController extends Controller
{
    protected $momoService;
    protected $encryptionService;

    public function __construct(
        MomoWebhookService $momoService,
        SensitiveDataEncryptionService $encryptionService
    ) {
        $this->momoService = $momoService;
        $this->encryptionService = $encryptionService;
    }

    /**
     * Webhook endpoint to confirm MOMO payments
     * Called by MOMO payment gateway after successful payment
     * 
     * Security measures:
     * - HMAC-SHA256 signature validation
     * - IP whitelist check
     * - Rate limiting
     * - Timestamp validation
     * - Payload structure validation
     * - Audit logging
     */
    public function momoWebhook(Request $request)
    {
        $ipAddress = $request->ip();
        
        try {
            // 1. Rate limiting check
            $rateLimitKey = 'momo-webhook:' . $ipAddress;
            $maxRequests = config('momo.momo.rate_limit.max_requests', 100);
            $perMinutes = config('momo.momo.rate_limit.per_minutes', 1);
            
            if (RateLimiter::tooManyAttempts($rateLimitKey, $maxRequests)) {
                $retryAfter = RateLimiter::availableIn($rateLimitKey);
                Log::warning('Rate limit exceeded for webhook', [
                    'ip' => $ipAddress,
                    'retry_after' => $retryAfter
                ]);
                return response()->json([
                    'error' => 'Rate limit exceeded',
                    'retry_after' => $retryAfter
                ], 429);
            }
            RateLimiter::hit($rateLimitKey, $perMinutes * 60);

            // 2. Get raw payload for signature validation
            $rawPayload = $request->getContent();
            $payload = $request->all();
            $signature = $request->header('X-Momo-Signature');

            // Log webhook received
            Log::info('MOMO Webhook received', [
                'payment_id' => $payload['payment_id'] ?? null,
                'ip' => $ipAddress
            ]);

            // 3. Validate signature
            if (!$signature) {
                $this->logAudit(null, null, 'webhook_failed', null, null, 'Missing signature header', $ipAddress, $request->userAgent(), $payload);
                return response()->json(['error' => 'Missing signature'], 401);
            }

            if (!$this->momoService->validateSignature($rawPayload, $signature)) {
                $this->logAudit(null, null, 'signature_validation_failed', null, null, 'Invalid HMAC signature', $ipAddress, $request->userAgent(), $payload);
                Log::error('MOMO Webhook signature validation failed', ['ip' => $ipAddress]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // 4. Validate payload structure
            if (!$this->momoService->validatePayloadStructure($payload)) {
                $this->logAudit(null, null, 'webhook_failed', null, null, 'Invalid payload structure', $ipAddress, $request->userAgent(), $payload);
                return response()->json(['error' => 'Invalid payload structure'], 400);
            }

            $paymentId = $payload['payment_id'];
            $status = $payload['status']; // 'success', 'failed', 'pending'
            $transactionId = $payload['transaction_id'];
            $amount = $payload['amount'];
            $currency = $payload['currency'] ?? config('momo.momo.currency', 'RWF');

            // 5. Validate payment exists and matches
            $paymentValidation = $this->momoService->validatePaymentMatch($paymentId, $amount, $currency);
            if ($paymentValidation === false) {
                $this->logAudit(null, null, 'webhook_failed', null, null, 'Payment validation failed', $ipAddress, $request->userAgent(), $payload);
                return response()->json(['error' => 'Payment validation failed'], 400);
            }
            if ($paymentValidation === 'duplicate') {
                // Idempotent response for duplicate webhooks
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment already processed',
                    'idempotent' => true
                ], 200);
            }

            $payment = PaymentTransaction::find($paymentId);

            // 6. Process payment based on status
            if (in_array($status, ['success', 'completed'])) {
                $oldStatus = $payment->status;
                
                $payment->update([
                    'status' => 'paid',
                    'provider_txn_id' => $transactionId,
                    'paid_at' => now(),
                ]);

                // Activate subscription if this was a subscription payment
                if ($payment->subscription_plan_id) {
                    $subscription = UserSubscription::where('user_id', $payment->user_id)
                        ->where('subscription_plan_id', $payment->subscription_plan_id)
                        ->first();

                    if ($subscription) {
                        $subscription->update(['status' => 'active']);
                    }
                }

                // Reactivate accounts
                Account::where('user_id', $payment->user_id)->update(['active' => true]);

                // Log audit trail
                $this->logAudit(
                    $payment->user_id,
                    $paymentId,
                    'webhook_received',
                    $oldStatus,
                    'paid',
                    'Payment confirmed via MOMO webhook',
                    $ipAddress,
                    $request->userAgent(),
                    ['transaction_id' => $transactionId, 'amount' => $amount]
                );

                Log::info('Payment confirmed successfully via webhook', [
                    'payment_id' => $paymentId,
                    'user_id' => $payment->user_id
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment confirmed',
                    'payment_id' => $paymentId
                ], 200);
            } elseif ($status === 'failed') {
                $oldStatus = $payment->status;
                
                $payment->update(['status' => 'failed']);

                $this->logAudit(
                    $payment->user_id,
                    $paymentId,
                    'webhook_received',
                    $oldStatus,
                    'failed',
                    'Payment failed via MOMO webhook',
                    $ipAddress,
                    $request->userAgent(),
                    ['transaction_id' => $transactionId]
                );

                Log::warning('Payment failed via webhook', [
                    'payment_id' => $paymentId,
                    'user_id' => $payment->user_id
                ]);

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment failed'
                ], 400);
            }

            // Still pending
            return response()->json([
                'status' => 'pending',
                'message' => 'Payment pending confirmation'
            ], 200);

        } catch (\Exception $e) {
            Log::error('MOMO Webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $ipAddress
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Manual payment confirmation endpoint (admin or user-initiated)
     * Requires HTTPS and authentication
     */
    public function confirmPayment(Request $request, $paymentId)
    {
        try {
            $payment = PaymentTransaction::findOrFail($paymentId);

            // Verify ownership or admin
            if ($payment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                $this->logAudit(
                    Auth::id(),
                    $paymentId,
                    'manual_confirmation',
                    null, null,
                    'Unauthorized confirmation attempt',
                    $request->ip(),
                    $request->userAgent()
                );
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $oldStatus = $payment->status;

            // Confirm the payment
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Activate subscription
            if ($payment->subscription_plan_id) {
                $subscription = UserSubscription::where('user_id', $payment->user_id)
                    ->where('subscription_plan_id', $payment->subscription_plan_id)
                    ->first();

                if ($subscription) {
                    $subscription->update(['status' => 'active']);
                }
            }

            // Confirm weekly payment if provided
            if ($request->has('weekly_payment_id')) {
                $weeklyPayment = WeeklyPayment::find($request->weekly_payment_id);
                if ($weeklyPayment) {
                    $weeklyPayment->update(['status' => 'paid']);
                }
            }

            // Reactivate accounts
            Account::where('user_id', $payment->user_id)->update(['active' => true]);

            // Log audit trail
            $this->logAudit(
                $payment->user_id,
                $paymentId,
                'manual_confirmation',
                $oldStatus,
                'paid',
                'Payment manually confirmed by ' . (Auth::user()->isAdmin() ? 'admin' : 'user'),
                $request->ip(),
                $request->userAgent()
            );

            Log::info('Payment manually confirmed', [
                'payment_id' => $paymentId,
                'confirmed_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed successfully',
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            Log::error('Payment confirmation error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Resend payment link
     */
    public function resendPaymentLink($paymentId)
    {
        try {
            $payment = PaymentTransaction::findOrFail($paymentId);

            // Verify ownership
            if ($payment->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Only resend if payment is still pending
            if ($payment->status !== 'pending') {
                return response()->json([
                    'error' => 'Cannot resend link for completed or failed payments'
                ], 400);
            }

            // Generate new checkout URL (integrate with MOMO API)
            $checkout_url = $this->generateMomoCheckoutUrl($payment);

            $payment->update(['checkout_url' => $checkout_url]);

            Log::info('Payment link resent', ['payment_id' => $paymentId]);

            return response()->json([
                'status' => 'success',
                'checkout_url' => $checkout_url,
                'message' => 'Payment link regenerated'
            ]);
        } catch (\Exception $e) {
            Log::error('Resend payment link error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get payment history with audit trail
     */
    public function history()
    {
        $payments = PaymentTransaction::where('user_id', Auth::id())
            ->with(['plan' => function ($query) {
                $query->select('id', 'name', 'price', 'currency');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.payments.history', compact('payments'));
    }

    /**
     * Get audit log for a payment
     */
    public function auditLog($paymentId)
    {
        $payment = PaymentTransaction::findOrFail($paymentId);

        // Verify ownership or admin
        if ($payment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logs = PaymentAuditLog::where('payment_transaction_id', $paymentId)
            ->with(['confirmedByUser', 'confirmedByAdmin'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'payment_id' => $paymentId,
            'logs' => $logs
        ]);
    }

    /**
     * Generate MOMO checkout URL (stub - implement with actual MOMO API)
     */
    private function generateMomoCheckoutUrl($payment)
    {
        // TODO: Implement actual MOMO API integration
        // For now, return placeholder URL
        return route('payments.checkout', ['payment_id' => $payment->id]);
    }

    /**
     * Log payment action to audit trail
     */
    private function logAudit(
        $userId,
        $paymentTransactionId,
        $action,
        $oldStatus = null,
        $newStatus = null,
        $reason = null,
        $ipAddress = null,
        $userAgent = null,
        $metadata = null
    ) {
        try {
            PaymentAuditLog::create([
                'user_id' => $userId,
                'payment_transaction_id' => $paymentTransactionId,
                'action' => $action,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'confirmed_by_user_id' => Auth::check() ? Auth::id() : null,
                'confirmed_by_admin_id' => Auth::check() && Auth::user()->isAdmin() ? Auth::id() : null,
                'reason' => $reason,
                'ip_address' => $ipAddress ?? request()->ip(),
                'user_agent' => $userAgent ?? request()->userAgent(),
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log error', ['error' => $e->getMessage()]);
        }
    }
}
