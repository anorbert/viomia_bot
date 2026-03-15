<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViomiaTradeExecution;
use Illuminate\Support\Facades\DB;

class TradeExecutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ViomiaTradeExecution::query();

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
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $executions = $query->paginate(20);

        // Get unique symbols for filter
        $symbols = ViomiaTradeExecution::select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        // Get statistics
        $totalExecutions = ViomiaTradeExecution::count();
        $totalProfit = ViomiaTradeExecution::sum('profit_loss');
        $winCount = ViomiaTradeExecution::where('result', 'WIN')->count();
        $lossCount = ViomiaTradeExecution::where('result', 'LOSS')->count();

        return view('admin.ai.executions.index', [
            'executions' => $executions,
            'symbols' => $symbols,
            'totalExecutions' => $totalExecutions,
            'totalProfit' => $totalProfit,
            'winCount' => $winCount,
            'lossCount' => $lossCount,
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
