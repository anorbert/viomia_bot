<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        $userId = auth()->id();
        
        $subscription = UserSubscription::query()
            ->with('plan')
            ->where('user_id', $userId)
            ->orderByRaw("FIELD(status,'active','pending','expired','cancelled')")
            ->orderByDesc('ends_at')
            ->orderByDesc('id')
            ->first();

        if ($subscription && $subscription->status === 'active' && $subscription->ends_at && $subscription->ends_at->lt($now)) {
            $subscription->update(['status' => 'expired']);
            $subscription->refresh();
        }

        // Check if subscription is chosen but not paid
        $isSubscriptionUnpaid = false;
        if ($subscription && $subscription->plan && $subscription->plan->price > 0) {
            $hasPayment = PaymentTransaction::where('user_id', $userId)
                ->where('subscription_plan_id', $subscription->subscription_plan_id)
                ->whereIn('status', ['paid', 'success'])
                ->exists();
            
            if (!$hasPayment) {
                $isSubscriptionUnpaid = true;
            }
        }

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $noSubscriptionChosen = $subscription === null;

        return view('users.subscriptions.index', compact('subscription', 'plans', 'isSubscriptionUnpaid', 'noSubscriptionChosen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Process subscription payment
     */
    public function payment(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:user_subscriptions,id',
            'payment_method' => 'required|in:momo,binance,visa,paypal',
            'momo_phone' => 'nullable|required_if:payment_method,momo|string',
            'momo_name' => 'nullable|required_if:payment_method,momo|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $subscription = UserSubscription::with('plan')->findOrFail($request->subscription_id);

        // Verify this subscription belongs to the authenticated user
        if ($subscription->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if payment already exists
        $existingPayment = PaymentTransaction::where('user_id', auth()->id())
            ->where('subscription_plan_id', $subscription->subscription_plan_id)
            ->whereIn('status', ['paid', 'success', 'pending'])
            ->exists();

        if ($existingPayment) {
            return response()->json(['message' => 'Payment already exists for this subscription'], 400);
        }

        // Create payment transaction record
        $payment = PaymentTransaction::create([
            'user_id' => auth()->id(),
            'subscription_plan_id' => $subscription->subscription_plan_id,
            'reference' => 'SUB-' . strtoupper(uniqid()) . '-' . time(),
            'provider' => $request->payment_method,
            'currency' => $subscription->plan->currency ?? 'RWF',
            'amount' => $request->amount,
            'status' => 'pending',
            'provider_txn_id' => null,
            'checkout_url' => null,
            'payload' => [
                'momo_phone' => $request->momo_phone,
                'momo_name' => $request->momo_name,
                'initiated_at' => now()->toDateTimeString(),
            ],
            'paid_at' => null,
        ]);

        // Handle MOMO payment
        if ($request->payment_method === 'momo') {
            $momoService = app(\App\Services\MomoService::class);
            
            // Check if MOMO is configured
            if (!$momoService->isConfigured()) {
                // In test/development, just mark as pending and return success
                // In production, return error if MOMO is not configured
                if (app()->environment('production')) {
                    $payment->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment method is not configured. Please try again later.'
                    ], 503);
                }
                
                // Development mode - simulate success
                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated (development mode)',
                    'reference' => $payment->reference,
                    'payment_id' => $payment->id,
                    'redirect_url' => route('user.subscriptions.payment-pending', $payment->reference),
                ]);
            }

            // Initiate payment with MOMO service
            $result = $momoService->requestPayment($payment, $request->momo_phone);

            if (!$result['success']) {
                // Payment initiation failed
                $payment->update(['status' => 'failed']);
                
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to initiate payment',
                    'error_code' => $result['error_code'] ?? 'UNKNOWN',
                ], 400);
            }

            // Payment initiated successfully
            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Payment request sent to your phone',
                'reference' => $payment->reference,
                'transaction_id' => $result['transaction_id'] ?? null,
                'payment_id' => $payment->id,
                'redirect_url' => route('user.subscriptions.payment-pending', $payment->reference),
            ]);
        }

        // Other payment methods
        return response()->json([
            'success' => false,
            'message' => 'This payment method is coming soon. Only MOMO is available now.'
        ], 400);
    }

    /**
     * Show payment pending page
     */
    public function paymentPending($reference)
    {
        $payment = PaymentTransaction::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->with('plan')
            ->firstOrFail();

        return view('users.subscriptions.payment-pending', compact('payment'));
    }

    /**
     * Check payment status (AJAX)
     */
    public function paymentStatus($reference)
    {
        $payment = PaymentTransaction::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // If payment is already paid/success, activate subscription
        if (in_array($payment->status, ['paid', 'success'])) {
            // Check if subscription is already active
            $subscription = UserSubscription::where('user_id', auth()->id())
                ->where('subscription_plan_id', $payment->subscription_plan_id)
                ->where('status', 'active')
                ->first();

            if (!$subscription) {
                // Activate subscription
                $startsAt = Carbon::now();
                $endsAt = null;

                $plan = $payment->plan;
                if ($plan) {
                    if (!empty($plan->duration_days)) {
                        $endsAt = (clone $startsAt)->addDays((int)$plan->duration_days);
                    } else {
                        $endsAt = match($plan->billing_interval) {
                            'daily' => (clone $startsAt)->addDay(),
                            'weekly' => (clone $startsAt)->addWeek(),
                            'yearly' => (clone $startsAt)->addYear(),
                            default => (clone $startsAt)->addMonth(),
                        };
                    }
                }

                UserSubscription::create([
                    'user_id' => auth()->id(),
                    'subscription_plan_id' => $payment->subscription_plan_id,
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'auto_renew' => false,
                    'reference' => $payment->reference,
                    'amount' => $payment->amount,
                ]);
            }
        }

        return response()->json([
            'status' => $payment->status,
            'paid_at' => optional($payment->paid_at)->toDateTimeString(),
            'amount' => $payment->amount,
            'currency' => $payment->currency,
        ]);
    }
}
