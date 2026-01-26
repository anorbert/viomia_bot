<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /* =======================
       1) PLANS
    ======================= */
    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('users.plans.index', compact('plans'));
    }

    public function showPlan(SubscriptionPlan $plan)
    {
        abort_unless($plan->is_active, 404);
        return view('users.plans.show', compact('plan'));
    }

    /* =======================
       2) START CHECKOUT
    ======================= */
    public function start(Request $request, SubscriptionPlan $plan)
    {
        abort_unless($plan->is_active, 404);

        $request->validate([
            'provider' => 'required|in:momo,binance',
            'phone'    => 'nullable|string|max:30', // used for momo
        ]);

        $provider = $request->provider;

        // Create a unique reference (internal)
        $reference = 'SUB-' . strtoupper(Str::random(10)) . '-' . time();

        $txn = PaymentTransaction::create([
            'user_id' => auth()->id(),
            'subscription_plan_id' => $plan->id,
            'reference' => $reference,
            'provider'  => $provider,
            'currency'  => $plan->currency ?? 'RWF',
            'amount'    => $plan->price,
            'status'    => 'pending',
            'payload'   => [
                'plan_slug' => $plan->slug,
            ],
        ]);

        if ($provider === 'momo') {
            // Initiate MoMo payment (STK push / collect)
            $phone = $request->phone ?? auth()->user()->phone_number ?? null;

            // TODO: call your MoMo gateway here (FDI or your provider)
            // Example (pseudo):
            // $resp = app(\App\Services\MomoService::class)->requestPayment($txn, $phone);

            // For now, store phone in payload
            $txn->update([
                'payload' => array_merge($txn->payload ?? [], [
                    'momo_phone' => $phone,
                    'note' => 'Initiated momo request (connect gateway in MomoService).'
                ])
            ]);

            return redirect()->route('user.checkout.pending', $txn->reference);
        }

        if ($provider === 'binance') {
            // Create Binance Pay order & get checkout URL
            // $resp = app(\App\Services\BinancePayService::class)->createOrder($txn);
            // $txn->update(['checkout_url' => $resp['checkoutUrl'], 'provider_txn_id' => $resp['prepayId']]);

            // Placeholder (until you connect API):
            $fakeUrl = url('/user/checkout/pending/' . $txn->reference);
            $txn->update([
                'checkout_url' => $fakeUrl,
                'payload' => array_merge($txn->payload ?? [], ['note' => 'Connect Binance Pay API to generate real checkout url'])
            ]);

            return redirect()->away($txn->checkout_url);
        }

        abort(400, 'Invalid provider');
    }

    /* =======================
       3) PENDING PAGE + POLL
    ======================= */
    public function pending(string $reference)
    {
        $txn = PaymentTransaction::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('users.checkout.pending', compact('txn'));
    }

    public function status(string $reference)
    {
        $txn = PaymentTransaction::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'status' => $txn->status,
            'paid_at' => optional($txn->paid_at)->toDateTimeString(),
        ]);
    }

    /* =======================
       4) CALLBACKS / WEBHOOKS
       IMPORTANT: Make idempotent
    ======================= */

    public function momoCallback(Request $request)
    {
        // Validate signature if your provider supports it (recommended)
        $data = $request->all();

        // You must map your gateway callback fields to:
        // - reference (our internal)
        // - provider_txn_id
        // - status success/failed
        $reference = $data['reference'] ?? $data['externalId'] ?? null;

        if (!$reference) return response()->json(['message'=>'Missing reference'], 400);

        return $this->finalizePaymentFromCallback(
            provider: 'momo',
            reference: $reference,
            providerTxnId: $data['transactionId'] ?? $data['txn_id'] ?? null,
            isSuccess: ($data['status'] ?? '') === 'SUCCESS' || ($data['success'] ?? false) === true,
            payload: $data
        );
    }

    public function binanceWebhook(Request $request)
    {
        $data = $request->all();

        // Binance Pay webhook normally includes merchantTradeNo or similar
        $reference = $data['merchantTradeNo'] ?? $data['reference'] ?? null;

        if (!$reference) return response()->json(['message'=>'Missing reference'], 400);

        // Binance success mapping depends on actual payload (e.g. status=PAID)
        $statusValue = strtoupper($data['status'] ?? '');
        $isSuccess = in_array($statusValue, ['PAID','SUCCESS','COMPLETED'], true);

        return $this->finalizePaymentFromCallback(
            provider: 'binance',
            reference: $reference,
            providerTxnId: $data['prepayId'] ?? $data['transactionId'] ?? null,
            isSuccess: $isSuccess,
            payload: $data
        );
    }

    /* =======================
       5) FINALIZER
    ======================= */
    private function finalizePaymentFromCallback(string $provider, string $reference, ?string $providerTxnId, bool $isSuccess, array $payload)
    {
        DB::beginTransaction();

        try {
            /** @var PaymentTransaction $txn */
            $txn = PaymentTransaction::lockForUpdate()->where('reference', $reference)->first();

            if (!$txn) {
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Idempotent: if already success, ignore duplicates
            if ($txn->status === 'success') {
                DB::commit();
                return response()->json(['message'=>'Already processed'], 200);
            }

            // Update txn
            $txn->provider = $provider; // ensure stored
            $txn->provider_txn_id = $providerTxnId ?? $txn->provider_txn_id;
            $txn->payload = $payload;
            $txn->status = $isSuccess ? 'success' : 'failed';
            $txn->paid_at = $isSuccess ? Carbon::now() : null;
            $txn->save();

            if ($isSuccess) {
                $this->activateSubscription($txn);
            }

            DB::commit();
            return response()->json(['message'=>'OK'], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message'=>'Error', 'error'=>$e->getMessage()], 500);
        }
    }

    private function activateSubscription(PaymentTransaction $txn): void
    {
        $plan = $txn->plan;
        if (!$plan) return;

        // Optional: expire any existing active subs for this user
        UserSubscription::where('user_id', $txn->user_id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $startsAt = Carbon::now();

        // Determine endsAt
        $endsAt = null;
        if (!empty($plan->duration_days)) {
            $endsAt = (clone $startsAt)->addDays((int)$plan->duration_days);
        } else {
            // default based on interval
            $endsAt = match($plan->billing_interval) {
                'daily' => (clone $startsAt)->addDay(),
                'weekly' => (clone $startsAt)->addWeek(),
                'yearly' => (clone $startsAt)->addYear(),
                default => (clone $startsAt)->addMonth(),
            };
        }

        UserSubscription::create([
            'user_id' => $txn->user_id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'auto_renew' => false,
            'reference' => $txn->reference,
            'amount' => $txn->amount,
        ]);
    }
}
