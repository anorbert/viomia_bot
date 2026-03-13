<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MomoWebhookService
{
    /**
     * Validate MOMO webhook signature
     * 
     * MOMO uses HMAC-SHA256 for signature validation
     * Signature is calculated as: HMAC-SHA256(api_key, json_payload)
     */
    public function validateSignature($payload, $signature, $apiKey = null)
    {
        try {
            $apiKey = $apiKey ?? config('services.momo.api_key');

            if (!$apiKey) {
                Log::error('MOMO API key not configured');
                return false;
            }

            // Calculate expected signature
            $payloadJson = is_string($payload) ? $payload : json_encode($payload);
            $expectedSignature = hash_hmac('sha256', $payloadJson, $apiKey, true);
            $expectedSignatureHex = bin2hex($expectedSignature);

            // Compare signatures (constant-time comparison to prevent timing attacks)
            return hash_equals($expectedSignatureHex, $signature);
        } catch (\Exception $e) {
            Log::error('Signature validation error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Validate webhook payload structure
     */
    public function validatePayloadStructure($payload)
    {
        $required = ['payment_id', 'status', 'transaction_id', 'amount', 'timestamp'];
        
        foreach ($required as $field) {
            if (!isset($payload[$field]) || empty($payload[$field])) {
                return false;
            }
        }

        // Validate timestamp is recent (within 5 minutes)
        $webhookTime = strtotime($payload['timestamp']);
        $currentTime = time();
        $timeDiff = abs($currentTime - $webhookTime);

        if ($timeDiff > 300) { // 5 minutes
            Log::warning('Webhook timestamp too old', [
                'webhook_time' => $payload['timestamp'],
                'current_time' => now(),
                'diff' => $timeDiff
            ]);
            return false;
        }

        return true;
    }

    /**
     * Validate payment exists and matches webhook amount
     */
    public function validatePaymentMatch($paymentId, $amount, $currency)
    {
        $payment = \App\Models\PaymentTransaction::find($paymentId);

        if (!$payment) {
            Log::warning('Payment not found in webhook', ['payment_id' => $paymentId]);
            return false;
        }

        // Verify amount matches (with floating point tolerance)
        if (abs((float)$payment->amount - (float)$amount) > 0.01) {
            Log::warning('Webhook amount mismatch', [
                'payment_id' => $paymentId,
                'expected' => $payment->amount,
                'received' => $amount
            ]);
            return false;
        }

        // Verify currency matches
        if ($payment->currency !== $currency) {
            Log::warning('Webhook currency mismatch', [
                'payment_id' => $paymentId,
                'expected' => $payment->currency,
                'received' => $currency
            ]);
            return false;
        }

        // Check if already processed (idempotency)
        if ($payment->status === 'paid' && $payment->paid_at) {
            Log::info('Webhook for already paid payment (idempotency)', ['payment_id' => $paymentId]);
            return 'duplicate';
        }

        return true;
    }
}
