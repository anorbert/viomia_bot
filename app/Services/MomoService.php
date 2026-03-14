<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MomoService
{
    private $apiKey;
    private $apiUser;
    private $apiSecret;
    private $endpoint;
    private $currency;

    public function __construct()
    {
        $config = config('momo.momo');
        $this->apiKey = $config['api_key'] ?? '';
        $this->apiUser = $config['api_user'] ?? '';
        $this->apiSecret = $config['api_secret'] ?? '';
        $this->endpoint = $config['primary_endpoint'] ?? 'https://sandbox.momoapi.mtn.com';
        $this->currency = $config['currency'] ?? 'RWF';
    }

    /**
     * Request payment from MOMO (STK Push / Collection)
     * 
     * @param PaymentTransaction $transaction
     * @param string $phoneNumber - Customer MOMO phone number
     * @return array - Response with status and data
     */
    public function requestPayment(PaymentTransaction $transaction, string $phoneNumber): array
    {
        try {
            // Validate phone number format
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            if (!$phoneNumber) {
                return [
                    'success' => false,
                    'message' => 'Invalid phone number format',
                    'error_code' => 'INVALID_PHONE'
                ];
            }

            // Prepare payment request payload
            $payload = [
                'amount' => number_format($transaction->amount, 2, '.', ''),
                'currency' => $transaction->currency ?? $this->currency,
                'externalId' => $transaction->reference,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $phoneNumber,
                ],
                'payerMessage' => 'Subscription payment for trading signals',
                'payeeNote' => 'Trading subscription - ' . $transaction->reference,
            ];

            // Make API request to MOMO
            $response = Http::withBasicAuth($this->apiUser, $this->apiSecret)
                ->withHeaders([
                    'X-Reference-Id' => $transaction->reference,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->timeout(30)
                ->post($this->endpoint . '/v1_0/requesttopay', $payload);

            if ($response->failed()) {
                Log::error('MOMO payment request failed', [
                    'reference' => $transaction->reference,
                    'status_code' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to initiate payment. Please try again.',
                    'error_code' => 'MOMO_API_ERROR',
                    'status_code' => $response->status(),
                ];
            }

            // Extract transaction ID from response headers
            $transactionId = $response->header('X-Transaction-Id');

            // Update payment transaction with MOMO details
            $transaction->update([
                'provider_txn_id' => $transactionId,
                'status' => 'pending',
                'payload' => array_merge($transaction->payload ?? [], [
                    'momo_phone' => $phoneNumber,
                    'api_response_time' => now()->toDateTimeString(),
                    'transaction_id' => $transactionId,
                ])
            ]);

            Log::info('MOMO payment request initiated', [
                'reference' => $transaction->reference,
                'transaction_id' => $transactionId,
                'amount' => $transaction->amount,
            ]);

            return [
                'success' => true,
                'message' => 'Payment request sent to your phone. Please enter your PIN to confirm.',
                'reference' => $transaction->reference,
                'transaction_id' => $transactionId,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency ?? $this->currency,
            ];

        } catch (Exception $e) {
            Log::error('MOMO service error', [
                'error' => $e->getMessage(),
                'reference' => $transaction->reference ?? 'unknown',
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error_code' => 'SERVICE_ERROR',
            ];
        }
    }

    /**
     * Get payment status from MOMO
     * 
     * @param string $reference
     * @return array
     */
    public function getPaymentStatus(string $reference): array
    {
        try {
            $response = Http::withBasicAuth($this->apiUser, $this->apiSecret)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->timeout(30)
                ->get($this->endpoint . '/v1_0/requesttopay/' . $reference);

            if ($response->failed()) {
                Log::error('MOMO status check failed', [
                    'reference' => $reference,
                    'status_code' => $response->status(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Unable to check payment status',
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'status' => $data['status'] ?? 'UNKNOWN',
                'data' => $data,
            ];

        } catch (Exception $e) {
            Log::error('MOMO status check error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error checking status',
            ];
        }
    }

    /**
     * Format and validate phone number
     * Accepts formats: +250xxxxxxxxx, 0xxxxxxxxx, 250xxxxxxxxx
     * Returns: 250xxxxxxxxx or false if invalid
     * 
     * @param string $phone
     * @return string|false
     */
    private function formatPhoneNumber(string $phone): string|false
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/\D/', '', $phone);

        // Handle Rwanda numbers (should be 12 digits starting with 250)
        if (strlen($cleaned) === 12 && str_starts_with($cleaned, '250')) {
            return $cleaned;
        }

        // Handle format: 0xxxxxxxxx (10 digits)
        if (strlen($cleaned) === 10 && str_starts_with($cleaned, '0')) {
            return '250' . substr($cleaned, 1);
        }

        // Handle format: 250xxxxxxxxx but wrong length
        if (str_starts_with($cleaned, '250') && strlen($cleaned) === 12) {
            return $cleaned;
        }

        return false;
    }

    /**
     * Validate API credentials are configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->apiUser) && !empty($this->apiSecret);
    }
}
