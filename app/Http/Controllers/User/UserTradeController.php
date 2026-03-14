<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Trade;
use App\Models\TradeLog;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserTradeController extends Controller
{
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

    public function open(Request $request)
    {
        $symbol = $request->get('symbol');
        $type   = $request->get('type');

        // Get current user's account IDs
        $accountIds = Account::where('user_id', auth()->id())->pluck('id');

        // Get open positions (where status is not 'closed')
        $query = TradeLog::whereIn('account_id', $accountIds)
            ->where('status', '!=', 'closed');

        // Filter by symbol
        if (!empty($symbol)) {
            $query->where('symbol', $symbol);
        }

        // Filter by type (BUY/SELL)
        if (!empty($type)) {
            $query->where('type', $type);
        }

        $positions = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Get unique symbols for filter dropdown
        $symbols = TradeLog::whereIn('account_id', $accountIds)
            ->where('status', '!=', 'closed')
            ->select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        // Calculate statistics
        $totalProfit = TradeLog::whereIn('account_id', $accountIds)
            ->where('status', '!=', 'closed')
            ->sum('profit') ?? 0;

        $buyCount = TradeLog::whereIn('account_id', $accountIds)
            ->where('status', '!=', 'closed')
            ->where('type', 'BUY')
            ->count();

        $sellCount = TradeLog::whereIn('account_id', $accountIds)
            ->where('status', '!=', 'closed')
            ->where('type', 'SELL')
            ->count();

        return view('users.trades.open', compact('positions', 'symbols', 'symbol', 'type', 'totalProfit', 'buyCount', 'sellCount'));
    }

    /**
     * TRADE HISTORY
     * Load from your trades table with full filters and pagination.
     */
    public function history(Request $request)
    {
        $q      = trim($request->get('q', ''));
        $symbol = $request->get('symbol');
        $type   = $request->get('type');
        $from   = $request->get('from');
        $to     = $request->get('to');

        // Get current user's account IDs
        $accountIds = Account::where('user_id', auth()->id())->pluck('id');

        // Build the query
        $query = TradeLog::whereIn('account_id', $accountIds);

        // Filter by search term (q) - search in ticket, symbol, or status
        if (!empty($q)) {
            $query->where(function ($q_query) use ($q) {
                $q_query->where('ticket', 'LIKE', "%{$q}%")
                    ->orWhere('symbol', 'LIKE', "%{$q}%")
                    ->orWhere('status', 'LIKE', "%{$q}%");
            });
        }

        // Filter by symbol
        if (!empty($symbol)) {
            $query->where('symbol', $symbol);
        }

        // Filter by type (BUY/SELL)
        if (!empty($type)) {
            $query->where('type', $type);
        }

        // Filter by from date
        if (!empty($from)) {
            $query->whereDate('closed_at', '>=', $from);
        }

        // Filter by to date
        if (!empty($to)) {
            $query->whereDate('closed_at', '<=', $to);
        }

        // Get paginated results
        $trades = $query->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page')); // Preserve filters in pagination links

        return view('users.trades.history', compact('trades','q','symbol','type','from','to'));
    }
}
