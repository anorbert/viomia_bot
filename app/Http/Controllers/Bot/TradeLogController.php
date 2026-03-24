<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeLog;
use App\Models\Signal;
use App\Models\Account;

class TradeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get query builder
        $query = TradeLog::query();

        // Search by symbol
        if ($request->has('search') && !empty($request->search)) {
            $query->where('symbol', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Paginate results (15 per page)
        $trades = $query->latest()->paginate(15);

        // Calculate summary statistics for stats cards
        $allTrades = TradeLog::all();
        $totalTrades = $allTrades->count();
        $buyTrades = $allTrades->where('type', 'buy')->count();
        $sellTrades = $allTrades->where('type', 'sell')->count();
        $totalProfit = $allTrades->sum('profit');
        
        return view('admin.trades.index', compact('trades', 'totalTrades', 'buyTrades', 'sellTrades', 'totalProfit'));
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
        $raw = $request->getContent();
        \Log::info('Incoming MT5 payload:', ['raw' => $raw]);

        $clean = preg_replace('/\x00/', '', $raw);

        $data = json_decode($clean, true);
        if (!$data) {
            return response()->json([
                'error' => 'Invalid JSON format',
                'raw'   => $raw
            ], 400);
        }

        // ✅ Normalize type to uppercase (if present)
        if (isset($data['type'])) {
            $data['type'] = strtoupper($data['type']);
        }

        // ✅ Normalize direction to lowercase (if present, for signal endpoint)
        if (isset($data['direction'])) {
            $data['direction'] = strtolower($data['direction']);
        }

        // Resolve account login to account_id FIRST
        if (!isset($data['account'])) {
            return response()->json(['error' => 'Account field required'], 400);
        }
        $account = Account::where('login', $data['account'])->first();
        if (!$account) {
            return response()->json([
                'error' => 'Account not found',
                'account_login' => $data['account']
            ], 400);
        }
        $accountId = $account->id;

        // ✅ Check if trade exists by ticket FIRST
        $existingTradeLog = TradeLog::where('ticket', $data['ticket'] ?? null)->first();

        if ($existingTradeLog) {
            // CLOSING existing trade - only require closing fields
            $validated = validator($data, [
                'account'     => 'required|numeric',
                'ticket'      => 'required|string',
                'close_price' => 'required|numeric|gt:0',
                'profit'      => 'required|numeric',
                'closed_lots' => 'required|numeric|gt:0',
                'reason'      => 'required|string|in:TP,SL,manual',
            ])->validate();

            \Log::info('Closing existing trade:', ['ticket' => $validated['ticket']]);

            return \DB::transaction(function () use ($existingTradeLog, $validated, $accountId) {
                $incomingProfit     = (float)($validated['profit'] ?? 0);
                $incomingClosedLots = (float)($validated['closed_lots'] ?? 0);

                $currentProfit = (float)($existingTradeLog->profit ?? 0);
                $currentClosed = (float)($existingTradeLog->closed_lots ?? 0);

                $newClosed = $currentClosed + $incomingClosedLots;
                $lotsOpen = (float)$existingTradeLog->lots;
                $epsilon  = 0.0000001;

                $status = ($newClosed + $epsilon >= $lotsOpen) ? 'closed' : 'partial_closed';

                $updates = [
                    'profit'      => $currentProfit + $incomingProfit,
                    'closed_lots' => $newClosed,
                    'status'      => $status,
                    'close_price' => $validated['close_price'],
                    'close_reason' => $validated['reason'],
                    'closed_at'    => now(),
                ];

                $existingTradeLog->update($updates);
                $existingTradeLog->refresh();

                \Log::info('Trade closed successfully', [
                    'ticket'       => $validated['ticket'],
                    'added_profit' => $incomingProfit,
                    'total_profit' => $existingTradeLog->profit,
                    'status'       => $existingTradeLog->status,
                ]);

                return response()->json([
                    'success'    => true,
                    'message'    => 'Trade closed successfully',
                    'ticket'     => $existingTradeLog->ticket,
                    'status'     => $existingTradeLog->status,
                    'profit'     => $existingTradeLog->profit,
                    'closed_at'  => $existingTradeLog->closed_at,
                ], 200);
            });

        } else {
            // OPENING new trade - require all opening fields
            $validated = validator($data, [
                'account'     => 'required|numeric',
                'ticket'      => 'required|string',
                'symbol'      => 'required|string',
                'type'        => 'required|in:BUY,SELL',
                'lots'        => 'required|numeric|gt:0',
                'open_price'  => 'required|numeric|gt:0',
                'sl'          => 'required|numeric|gte:0',
                'tp'          => 'required|numeric|gte:0',
            ])->validate();

            \Log::info('Opening new trade:', ['ticket' => $validated['ticket']]);

            return \DB::transaction(function () use ($validated, $accountId) {
                $tradeLog = TradeLog::create([
                    'account_id'   => $accountId,
                    'ticket'       => $validated['ticket'],
                    'symbol'       => $validated['symbol'],
                    'type'         => $validated['type'],
                    'lots'         => $validated['lots'],
                    'open_price'   => $validated['open_price'],
                    'sl'           => $validated['sl'],
                    'tp'           => $validated['tp'],
                    'status'       => 'open',
                    'opened_at'    => now(),
                ]);

                \Log::info('Trade opened successfully', [
                    'ticket' => $validated['ticket'],
                    'symbol' => $validated['symbol'],
                ]);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Trade opened successfully',
                    'ticket'    => $tradeLog->ticket,
                    'status'    => $tradeLog->status,
                    'opened_at' => $tradeLog->opened_at,
                ], 201);
            });
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trade = TradeLog::with(['account'])->findOrFail($id);

        // Map TradeLog fields to template expectations
        $trade->direction = $trade->type;
        $trade->entry_price = $trade->open_price;
        $trade->stop_loss = $trade->sl;
        $trade->take_profit = $trade->tp;
        $trade->lot_size = $trade->lots;
        $trade->exit_price = $trade->close_price;
        $trade->current_price = $trade->close_price ?? $trade->open_price;
        $trade->pnl = $trade->profit ?? 0;

        return view('admin.trades.show', compact('trade'));
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
