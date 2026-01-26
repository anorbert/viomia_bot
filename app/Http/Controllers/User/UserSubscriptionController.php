<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
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

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('users.subscriptions.index', compact('subscription','plans'));
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
}
