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
        $subscription = UserSubscription::query()
            ->with('plan')
            ->where('user_id', auth()->id())
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
            $hasPayment = PaymentTransaction::where('user_id', auth()->id())
                ->where('subscription_plan_id', $subscription->subscription_plan_id)
                ->where('status', 'paid')
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
            ->where('status', 'paid')
            ->exists();

        if ($existingPayment) {
            return response()->json(['message' => 'Payment already completed for this subscription'], 400);
        }

        // Create payment transaction record
        $payment = PaymentTransaction::create([
            'user_id' => auth()->id(),
            'subscription_plan_id' => $subscription->subscription_plan_id,
            'reference' => 'SUB-' . strtoupper(uniqid()),
            'provider' => $request->payment_method,
            'currency' => $subscription->plan->currency ?? 'USD',
            'amount' => $request->amount,
            'status' => 'pending',
            'provider_txn_id' => null,
            'checkout_url' => null,
            'payload' => [
                'momo_phone' => $request->momo_phone,
                'momo_name' => $request->momo_name,
            ],
            'paid_at' => null,
        ]);

        return response()->json([
            'message' => 'Payment initiated successfully',
            'payment_id' => $payment->id,
            'status' => 'pending',
        ]);
    }
}
