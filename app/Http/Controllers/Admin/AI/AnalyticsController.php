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
