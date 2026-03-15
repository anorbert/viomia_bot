<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViomiaTradeOutcome;
use Illuminate\Support\Facades\DB;

class TradeOutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ViomiaTradeOutcome::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('symbol', 'like', "%{$search}%")
                  ->orWhere('ticket', 'like', "%{$search}%");
        }

        // Filter by symbol
        if ($request->filled('symbol')) {
            $query->where('symbol', $request->input('symbol'));
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('result', $request->input('result'));
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('recorded_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('recorded_at', '<=', $request->input('to_date'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'recorded_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $outcomes = $query->paginate(20);

        // Get unique symbols for filter
        $symbols = ViomiaTradeOutcome::select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        // Get statistics
        $totalOutcomes = ViomiaTradeOutcome::count();
        $totalProfit = ViomiaTradeOutcome::sum('profit');
        $winCount = ViomiaTradeOutcome::where('result', 'WIN')->count();
        $lossCount = ViomiaTradeOutcome::where('result', 'LOSS')->count();
        $winRate = $totalOutcomes > 0 ? round(($winCount / $totalOutcomes) * 100, 2) : 0;

        return view('admin.ai.outcomes.index', [
            'outcomes' => $outcomes,
            'symbols' => $symbols,
            'totalOutcomes' => $totalOutcomes,
            'totalProfit' => $totalProfit,
            'winCount' => $winCount,
            'lossCount' => $lossCount,
            'winRate' => $winRate,
        ]);
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
