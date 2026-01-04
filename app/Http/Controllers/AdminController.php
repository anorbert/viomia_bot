<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BotStatus;
use App\Models\TradeLog;
use App\Models\DailySummary;
use App\Models\TradeEvent;
use App\Models\ErrorLog;
use App\Models\SignalAccount;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    $today = now()->startOfDay();
    $week = now()->subDays(7);

    /** ================= USERS ================= */
    $totalClients = User::count();
    $newClients = User::where('created_at', '>=', $week)->count();

    /** ================= ACCOUNTS ================= */
    $connectedAccounts = Account::where('active', true)->count();

    /** ================= ACTIVE BOTS ================= */
    $activeBots = DB::table('ea_status_changes as e')
        ->whereIn('e.id', function ($q) {
            $q->selectRaw('MAX(id)')
              ->from('ea_status_changes')
              ->groupBy('account_id');
        })
        ->where('status', 'RUNNING')
        ->count();

    /** ================= TODAY PERFORMANCE ================= */
    $todayTrades = TradeLog::where('created_at', '>=', $today);

    $todaysProfit = $todayTrades->sum('profit');
    $todaysTrades = $todayTrades->count();
    $todaysWins   = $todayTrades->where('profit', '>', 0)->count();
    $todaysLosses = $todayTrades->where('profit', '<', 0)->count();

    /** ================= AVG TRADE DURATION ================= */
    $avgTradeDuration = TradeLog::whereNotNull('close_price')
        ->where('created_at', '>=', $today)
        ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, updated_at)')) ?? 0;

    /** ================= ALERTS ================= */
    $alertsCount = ErrorLog::whereDate('error_at', today())->count();

    /** ================= SERVER HEALTH ================= */
    $lastPing = DB::table('ea_status_changes')
        ->latest('changed_at')
        ->value('changed_at');

    $serverHealth = $lastPing && now()->diffInMinutes($lastPing) < 5
        ? 'OK'
        : 'DEGRADED';

    /** ================= MONTHLY PROFIT ================= */
    $months = [];
    $monthlyProfit = [];

    // Pre-fill last 12 months with 0
    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $key = $month->format('Y-m');
        $months[$key] = $month->format('M Y');
        $monthlyProfit[$key] = 0;
    }

    // Aggregate profit by month from closed trades
    $profits = TradeLog::selectRaw("
            DATE_FORMAT(updated_at, '%Y-%m') as ym,
            SUM(COALESCE(profit,0)) as total_profit
        ")
        ->whereNotNull('close_price')
        ->where('updated_at', '>=', now()->subMonths(12))
        ->groupBy('ym')
        ->pluck('total_profit', 'ym');

    // Merge DB results into pre-filled array
    foreach ($profits as $ym => $value) {
        if (isset($monthlyProfit[$ym])) {
            $monthlyProfit[$ym] = round($value, 2);
        }
    }

    // Prepare arrays for Chart.js
    $months = array_values($months);
    $monthlyProfit = array_values($monthlyProfit);


    /** ================= SYMBOL DISTRIBUTION ================= */
    $symbolData = TradeLog::selectRaw('symbol, COUNT(*) as total')
        ->where('created_at', '>=', now()->subMonth())
        ->groupBy('symbol')
        ->get();

    return view('dashboard', compact(
        'totalClients',
        'newClients',
        'connectedAccounts',
        'activeBots',
        'todaysProfit',
        'todaysTrades',
        'todaysWins',
        'todaysLosses',
        'avgTradeDuration',
        'alertsCount',
        'serverHealth',
        'months',
        'monthlyProfit',
        'symbolData'
    ));
}



    /**
     * Calculate win rate
     */
    private function calculateWinRate($fromDate, $toDate)
    {
        $totalTrades = TradeLog::whereBetween('created_at', [$fromDate, $toDate])->count();
        if ($totalTrades === 0) {
            return 0;
        }

        $winningTrades = TradeLog::where('status', 'closed')
            ->where('profit_loss', '>', 0)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        return round(($winningTrades / $totalTrades) * 100, 2);
    }

    /**
     * Calculate total revenue
     */
    private function calculateTotalRevenue($fromDate, $toDate)
    {
        return TradeLog::whereBetween('created_at', [$fromDate, $toDate])
            ->sum('profit_loss') ?? 0;
    }

    /**
     * Get daily revenue data for charts
     */
    private function getDailyRevenueData($days)
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = TradeLog::whereDate('created_at', $date)->sum('profit_loss');
            $data[$date->format('M d')] = $revenue ?? 0;
        }
        return $data;
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

    public function metrics()
    {
        $today = now()->startOfDay();

        return response()->json([
            'profit' => TradeLog::where('created_at', '>=', $today)->sum('profit'),
            'trades' => TradeLog::where('created_at', '>=', $today)->count(),
            'wins'   => TradeLog::where('created_at', '>=', $today)->where('profit', '>', 0)->count(),
            'losses' => TradeLog::where('created_at', '>=', $today)->where('profit', '<', 0)->count(),
        ]);
    }

}
