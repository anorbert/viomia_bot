<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {

        /* ================= TODAY METRICS ================= */

        $today = Carbon::today();

        $signalsToday = DB::table('viomia_signal_logs')
            ->whereDate('pushed_at', $today)
            ->count();

        $executionsToday = DB::table('viomia_trade_executions')
            ->whereDate('created_at', $today)
            ->count();

        $winsToday = DB::table('viomia_trade_outcomes')
            ->whereDate('recorded_at', $today)
            ->where('result','WIN')
            ->count();

        $lossToday = DB::table('viomia_trade_outcomes')
            ->whereDate('recorded_at', $today)
            ->where('result','LOSS')
            ->count();

        $winRate = $executionsToday > 0
            ? round(($winsToday / $executionsToday) * 100,2)
            : 0;


        /* ================= PROFIT ================= */

        $profitToday = DB::table('viomia_trade_outcomes')
            ->whereDate('recorded_at',$today)
            ->sum('profit');


        /* ================= AI DECISION QUALITY ================= */

        $avgConfidence = DB::table('viomia_trade_executions')
            ->avg('ml_confidence');

        $avgRR = DB::table('viomia_decisions')
            ->avg('rr_ratio');


        /* ================= SYMBOL PERFORMANCE ================= */

        $symbolPerformance = DB::table('viomia_trade_outcomes')
            ->select(
                'symbol',
                DB::raw('COUNT(*) as trades'),
                DB::raw('SUM(profit) as pnl')
            )
            ->groupBy('symbol')
            ->orderByDesc('pnl')
            ->limit(10)
            ->get();


        /* ================= SESSION PERFORMANCE ================= */

        $sessionStats = DB::table('viomia_trade_executions')
            ->select(
                'session_name',
                DB::raw('COUNT(*) as trades')
            )
            ->groupBy('session_name')
            ->get();


        /* ================= SIGNAL TYPES ================= */

        $signalBreakdown = DB::table('viomia_decisions')
            ->select(
                'decision',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('decision')
            ->get();


        /* ================= MONTHLY AI PROFIT ================= */

        $months = [];
        $monthlyProfit = [];

        for ($i=11; $i>=0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $pnl = DB::table('viomia_trade_outcomes')
                ->whereMonth('recorded_at',$date->month)
                ->whereYear('recorded_at',$date->year)
                ->sum('profit');

            $monthlyProfit[] = round($pnl,2);
        }


        /* ================= LAST AI SIGNAL ================= */

        $lastSignal = DB::table('viomia_signal_logs')
            ->latest('pushed_at')
            ->first();


        /* ================= LAST EXECUTION ================= */

        $lastExecution = DB::table('viomia_trade_executions')
            ->latest()
            ->first();


        return view('admin.ai.dashboard',[
            'signalsToday' => $signalsToday,
            'executionsToday' => $executionsToday,
            'winsToday' => $winsToday,
            'lossToday' => $lossToday,
            'winRate' => $winRate,
            'profitToday' => $profitToday,
            'avgConfidence' => round($avgConfidence,2),
            'avgRR' => round($avgRR,2),
            'symbolPerformance' => $symbolPerformance,
            'sessionStats' => $sessionStats,
            'signalBreakdown' => $signalBreakdown,
            'months' => $months,
            'monthlyProfit' => $monthlyProfit,
            'lastSignal' => $lastSignal,
            'lastExecution' => $lastExecution
        ]);
    }
    /**
     * Show AI Performance Analytics
     */
    public function performance(Request $request)
    {
        // Time period filter
        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days);

        // Overall performance metrics
        $totalTrades = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->count();

        $totalProfit = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->sum('profit');

        $winCount = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->where('result', 'WIN')
            ->count();

        $lossCount = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->where('result', 'LOSS')
            ->count();

        $winRate = $totalTrades > 0 ? round(($winCount / $totalTrades) * 100, 2) : 0;

        // Average profit per trade
        $avgProfit = $totalTrades > 0 ? round($totalProfit / $totalTrades, 2) : 0;

        // Symbol performance
        $symbolPerformance = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->select(
                'symbol',
                DB::raw('COUNT(*) as trades'),
                DB::raw('SUM(profit) as pnl'),
                DB::raw('SUM(CASE WHEN result = "WIN" THEN 1 ELSE 0 END) as wins'),
                DB::raw('ROUND((SUM(CASE WHEN result = "WIN" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as win_rate')
            )
            ->groupBy('symbol')
            ->orderByDesc('pnl')
            ->get();

        // Daily performance chart
        $dailyData = [];
        $dailyProfit = [];
        $dailyWins = [];
        $dailyLosses = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - $i - 1);
            $dateStr = $date->format('Y-m-d');
            $dailyData[] = $date->format('M d');

            $profit = DB::table('viomia_trade_outcomes')
                ->whereDate('recorded_at', $dateStr)
                ->sum('profit');
            
            $wins = DB::table('viomia_trade_outcomes')
                ->whereDate('recorded_at', $dateStr)
                ->where('result', 'WIN')
                ->count();

            $losses = DB::table('viomia_trade_outcomes')
                ->whereDate('recorded_at', $dateStr)
                ->where('result', 'LOSS')
                ->count();

            $dailyProfit[] = round($profit, 2);
            $dailyWins[] = $wins;
            $dailyLosses[] = $losses;
        }

        // Best and worst trades
        $bestTrade = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->orderByDesc('profit')
            ->first();

        $worstTrade = DB::table('viomia_trade_outcomes')
            ->where('recorded_at', '>=', $startDate)
            ->orderBy('profit')
            ->first();

        return view('admin.ai.performance', [
            'totalTrades' => $totalTrades,
            'totalProfit' => $totalProfit,
            'winCount' => $winCount,
            'lossCount' => $lossCount,
            'winRate' => $winRate,
            'avgProfit' => $avgProfit,
            'symbolPerformance' => $symbolPerformance,
            'dailyData' => json_encode($dailyData),
            'dailyProfit' => json_encode($dailyProfit),
            'dailyWins' => json_encode($dailyWins),
            'dailyLosses' => json_encode($dailyLosses),
            'bestTrade' => $bestTrade,
            'worstTrade' => $worstTrade,
            'days' => $days,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
