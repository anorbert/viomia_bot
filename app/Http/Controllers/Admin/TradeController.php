<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeLog;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.trades.index');
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

public function statistic1(){
    // return 'David BIKORIMANA';
    // return 'here we are';
        $trades = TradeLog::all();
        
        // Calculate basic statistics
        $totalTrades = $trades->count();
        $winningTrades = $trades->where('profit', '>', 0)->count();
        $losingTrades = $trades->where('profit', '<', 0)->count();
        $totalProfit = $trades->sum('profit');
        $totalLots = $trades->sum('lots');
        
        $winRate = $totalTrades > 0 ? round(($winningTrades / $totalTrades) * 100, 2) : 0;
        $avgProfit = $totalTrades > 0 ? round($totalProfit / $totalTrades, 2) : 0;
        $avgWin = $winningTrades > 0 ? round($trades->where('profit', '>', 0)->sum('profit') / $winningTrades, 2) : 0;
        $avgLoss = $losingTrades > 0 ? round($trades->where('profit', '<', 0)->sum('profit') / $losingTrades, 2) : 0;
        
        // Profit by symbol
        $profitBySymbol = $trades->groupBy('symbol')->map(function ($group) {
            return [
                'symbol' => $group[0]->symbol,
                'profit' => round($group->sum('profit'), 2),
                'count' => $group->count(),
                'wins' => $group->where('profit', '>', 0)->count(),
            ];
        })->sortByDesc('profit')->values();
        
        // Profit by type (Buy/Sell)
        $profitByType = $trades->groupBy('type')->map(function ($group) {
            return [
                'type' => $group[0]->type,
                'profit' => round($group->sum('profit'), 2),
                'count' => $group->count(),
            ];
        })->values();
        
        // Daily profit
        $dailyProfit = $trades->groupBy(function ($trade) {
            return $trade->created_at->format('Y-m-d');
        })->map(function ($group) {
            return [
                'date' => $group[0]->created_at->format('M d'),
                'profit' => round($group->sum('profit'), 2),
                'count' => $group->count(),
            ];
        })->sortBy('date')->take(30)->values();
        
        // Profit factor
        $profitFactor = $avgLoss != 0 ? round(abs($avgWin / $avgLoss), 2) : 0;
        
        // Symbol statistics - group by symbol with detailed metrics
        $symbolStats = TradeLog::selectRaw(
            'symbol, 
            COUNT(*) as trade_count, 
            SUM(CASE WHEN profit > 0 THEN 1 ELSE 0 END) as winning,
            SUM(profit) as total_profit'
        )
        ->groupBy('symbol')
        ->orderByDesc('total_profit')
        ->get()
        ->map(function ($stat) {
            $stat->win_rate = $stat->trade_count > 0 ? round(($stat->winning / $stat->trade_count) * 100, 1) : 0;
            return $stat;
        });
        
        return view('admin.trades.statistics', compact(
            'totalTrades', 'winningTrades', 'losingTrades', 'totalProfit', 
            'totalLots', 'winRate', 'avgProfit', 'avgWin', 'avgLoss', 'profitFactor',
            'profitBySymbol', 'profitByType', 'dailyProfit', 'symbolStats'
        ));
    }
    public function Check(){
        
    }


    public function symbols()
    {
        return view('admin.trades.symbols.index');
    }
}
