<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeLog;
use App\Models\Account;
use App\Models\EaWhatsappExcution;
use App\Models\SubscriptionPlan;
use App\Models\WeeklyPayment;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserSignalController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim($request->get('q', ''));
        $status = $request->get('status');
        $symbol = $request->get('symbol');
        
        // Get current user's account IDs
        $userAccountIds = auth()->user()->accounts()->pluck('id');

        $trades = TradeLog::query()
            ->whereIn('account_id', $userAccountIds)
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('symbol', 'like', "%{$q}%")
                      ->orWhere('ticket', 'like', "%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when($symbol, fn($qry) => $qry->where('symbol', $symbol))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $symbols = TradeLog::query()
            ->whereIn('account_id', $userAccountIds)
            ->select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        // Calculate statistics
        $baseQuery = TradeLog::whereIn('account_id', $userAccountIds)->where('status', 'closed');
        
        // Daily (Today)
        $dailyData = $baseQuery->clone()
            ->whereDate('updated_at', Carbon::today())
            ->selectRaw('SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END) as profit_sum, SUM(CASE WHEN profit < 0 THEN profit ELSE 0 END) as loss_sum, SUM(profit) as net_profit')
            ->first();
        $dailyProfit = $dailyData->profit_sum ?? 0;
        $dailyLoss = abs($dailyData->loss_sum ?? 0);
        $dailyNet = $dailyData->net_profit ?? 0;
        
        // Weekly
        $weeklyData = $baseQuery->clone()
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END) as profit_sum, SUM(CASE WHEN profit < 0 THEN profit ELSE 0 END) as loss_sum, SUM(profit) as net_profit')
            ->first();
        $weeklyProfit = $weeklyData->profit_sum ?? 0;
        $weeklyLoss = abs($weeklyData->loss_sum ?? 0);
        $weeklyNet = $weeklyData->net_profit ?? 0;
        
        // Monthly
        $monthlyData = $baseQuery->clone()
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->selectRaw('SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END) as profit_sum, SUM(CASE WHEN profit < 0 THEN profit ELSE 0 END) as loss_sum, SUM(profit) as net_profit')
            ->first();
        $monthlyProfit = $monthlyData->profit_sum ?? 0;
        $monthlyLoss = abs($monthlyData->loss_sum ?? 0);
        $monthlyNet = $monthlyData->net_profit ?? 0;

        return view('users.signals.index', compact(
            'trades','symbols','q','status','symbol',
            'dailyProfit','dailyLoss','dailyNet',
            'weeklyProfit','weeklyLoss','weeklyNet',
            'monthlyProfit','monthlyLoss','monthlyNet'
        ));
    }

    public function executions(Request $request)
    {
        $status = $request->get('status');
        $account = trim($request->get('account', ''));

        $executions = EaWhatsappExcution::query()
            ->with(['signal']) // relation
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when($account, fn($qry) => $qry->where('account_id','like',"%{$account}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('users.executions.index', compact('executions','status','account'));
    }

    public function weeklyReport()
    {
        // Get current user's account IDs
        $userAccounts = auth()->user()->accounts()->get();
        $userAccountIds = $userAccounts->pluck('id');
        
        // Get user's active subscription
        $userSubscription = auth()->user()->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->latest()
            ->first();
        
        // Get real accounts count
        $realAccountsCount = auth()->user()->accounts()
            ->where('account_type', 'real')
            ->count();
        
        // Get current week's data
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        
        $currentWeekData = TradeLog::whereIn('account_id', $userAccountIds)
            ->where('status', 'closed')
            ->whereBetween('updated_at', [$weekStart, $weekEnd])
            ->selectRaw('SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END) as profit_sum, SUM(CASE WHEN profit < 0 THEN profit ELSE 0 END) as loss_sum, SUM(profit) as net_profit')
            ->first();
        
        $currentProfit = $currentWeekData->profit_sum ?? 0;
        $currentLoss = abs($currentWeekData->loss_sum ?? 0);
        $currentNetProfit = $currentWeekData->net_profit ?? 0;
        
        // Calculate payment amount for Entry Plan (30% of weekly profit)
        $paymentAmount = 0;
        $isEntryPlan = false;
        $showSubscriptionAlert = false;
        
        if ($userSubscription && $userSubscription->plan) {
            // Check if subscription requires payment (not free)
            $isPaidPlan = $userSubscription->plan->price > 0;
            
            if ($isPaidPlan) {
                // Check if subscription has expired
                if ($userSubscription->ends_at && $userSubscription->ends_at < Carbon::now()) {
                    // Subscription expired - deactivate accounts and show alert
                    Account::whereIn('id', $userAccountIds)->update(['active' => 0]);
                    $showSubscriptionAlert = true;
                } else {
                    // Check if payment was made for this subscription
                    $hasPayment = PaymentTransaction::where('user_id', auth()->user()->id)
                        ->where('subscription_plan_id', $userSubscription->subscription_plan_id)
                        ->whereIn('status', ['paid', 'success'])
                        ->exists();
                    
                    if (!$hasPayment) {
                        // No payment found - deactivate accounts and show alert
                        Account::whereIn('id', $userAccountIds)->update(['active' => 0]);
                        $showSubscriptionAlert = true;
                    } else {
                        // Payment found - check if it's Entry Plan
                        if ($userSubscription->plan->name === 'Entry Plan') {
                            $isEntryPlan = true;
                            if ($currentNetProfit > 0) {
                                $paymentAmount = $currentNetProfit * 0.30;
                            }
                        }
                    }
                }
            } else {
                // Free plan - set Entry Plan if applicable
                if ($userSubscription->plan->name === 'Entry Plan') {
                    $isEntryPlan = true;
                    if ($currentNetProfit > 0) {
                        $paymentAmount = $currentNetProfit * 0.30;
                    }
                }
            }
        }
        
        // Calculate weekly payment (old method for reference)
        $weeklyPayment = 0;
        if ($userSubscription && $userSubscription->plan && $realAccountsCount > 0 && $userSubscription->plan->name === 'Entry Plan') {
            if ($currentNetProfit > 0) {
                $weeklyPayment = ($userSubscription->plan->price * 0.30) / 4;
            }
        }
        
        // Get all weeks' summaries (last 12 weeks)
        $weeklySummaries = [];
        for ($i = 0; $i < 12; $i++) {
            $start = $weekStart->copy()->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $end = $start->copy()->endOfWeek(Carbon::SUNDAY);
            
            $weekData = TradeLog::whereIn('account_id', $userAccountIds)
                ->where('status', 'closed')
                ->whereBetween('updated_at', [$start, $end])
                ->selectRaw('SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END) as profit_sum, SUM(CASE WHEN profit < 0 THEN profit ELSE 0 END) as loss_sum, SUM(profit) as net_profit, COUNT(*) as total_trades, SUM(CASE WHEN profit > 0 THEN 1 ELSE 0 END) as winning_trades')
                ->first();
            
            $profit = $weekData->profit_sum ?? 0;
            $loss = abs($weekData->loss_sum ?? 0);
            $netProfit = $weekData->net_profit ?? 0;
            $totalTrades = $weekData->total_trades ?? 0;
            $winningTrades = $weekData->winning_trades ?? 0;
            $winRate = $totalTrades > 0 ? ($winningTrades / $totalTrades) * 100 : 0;
            
            if ($totalTrades > 0) {
                $weeklySummaries[] = [
                    'week' => $start->format('d M') . ' - ' . $end->format('d M'),
                    'startDate' => $start,
                    'endDate' => $end,
                    'netProfit' => $netProfit,
                    'profit' => $profit,
                    'loss' => $loss,
                    'totalTrades' => $totalTrades,
                    'winningTrades' => $winningTrades,
                    'winRate' => $winRate
                ];
            }
        }

        // Check for unpaid weekly payments from past weeks
        $unpaidWeeklyPayments = WeeklyPayment::where('user_id', auth()->user()->id)
            ->where('status', 'pending')
            ->where('week_end', '<', Carbon::now())
            ->get();
        
        $showUnpaidWeeklyAlert = $unpaidWeeklyPayments->count() > 0;

        return view('users.weekly-report.index', compact('weeklySummaries', 'userSubscription', 'realAccountsCount', 'weeklyPayment', 'userAccounts', 'paymentAmount', 'isEntryPlan', 'showSubscriptionAlert', 'unpaidWeeklyPayments', 'showUnpaidWeeklyAlert'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:momo,binance,visa,paypal',
            'amount' => 'required|numeric|min:0.01',
            'momo_phone' => 'nullable|required_if:payment_method,momo|string',
            'momo_name' => 'nullable|required_if:payment_method,momo|string',
        ]);

        $user = auth()->user();
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Get active subscription
        $userSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$userSubscription) {
            return response()->json(['message' => 'No active subscription found'], 404);
        }

        // Check if payment already exists for this week
        $existingPayment = WeeklyPayment::where('user_id', $user->id)
            ->where('week_start', $weekStart)
            ->where('week_end', $weekEnd)
            ->first();

        if ($existingPayment) {
            return response()->json(['message' => 'Payment already exists for this week'], 400);
        }

        // Create weekly payment record
        $payment = WeeklyPayment::create([
            'user_id' => $user->id,
            'user_subscription_id' => $userSubscription->id,
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'weekly_profit' => $request->amount / 0.30, // Calculate actual profit from 30%
            'percentage' => 30,
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'momo_phone' => $request->momo_phone,
            'account_name' => $request->momo_name,
        ]);

        return response()->json([
            'message' => 'Payment initiated successfully',
            'payment_id' => $payment->id,
            'status' => 'pending'
        ]);
    }
}

