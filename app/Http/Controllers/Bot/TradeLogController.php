<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeLog;
use App\Models\Signal;

class TradeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $trades=TradeLog::latest()->paginate(50);
        return view('admin.logs.trading',compact('trades'));
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
        \Log::info('Incoming MT5 close payload:', ['raw' => $raw]);

        $clean = preg_replace('/\x00/', '', $raw);

        $data = json_decode($clean, true);
        if (!$data) {
            return response()->json([
                'error' => 'Invalid JSON format',
                'raw'   => $raw
            ], 400);
        }

        $validated = validator($data, [
            'ticket'      => 'required',   // POSITION ID
            'close_price' => 'nullable|numeric',
            'profit'      => 'nullable|numeric',
            'closed_lots' => 'nullable|numeric|min:0',
            'reason'      => 'nullable|string',
        ])->validate();

        return \DB::transaction(function () use ($validated) {

            $tradeLog = TradeLog::where('ticket', $validated['ticket'])
                ->lockForUpdate()
                ->first();

            if (!$tradeLog) {
                return response()->json([
                    'error'  => 'Trade log not found',
                    'ticket' => $validated['ticket']
                ], 404);
            }

            $incomingProfit     = (float)($validated['profit'] ?? 0);
            $incomingClosedLots = (float)($validated['closed_lots'] ?? 0);

            $currentProfit = (float)($tradeLog->profit ?? 0);
            $currentClosed = (float)($tradeLog->closed_lots ?? 0);

            $newClosed = $currentClosed + $incomingClosedLots;

            $lotsOpen = (float)$tradeLog->lots;
            $epsilon  = 0.0000001;

            $status = ($newClosed + $epsilon >= $lotsOpen) ? 'closed' : 'partial_closed';

            $updates = [
                'profit'      => $currentProfit + $incomingProfit,
                'closed_lots' => $newClosed,
                'status'      => $status,
            ];

            if (array_key_exists('close_price', $validated) && $validated['close_price'] !== null) {
                $updates['close_price'] = $validated['close_price'];
            }

            if (!empty($validated['reason'])) {
                $updates['close_reason'] = $validated['reason'];
            }

            $tradeLog->update($updates);
            $tradeLog->refresh();

            \Log::info('Trade log updated', [
                'ticket'       => $validated['ticket'],
                'added_profit' => $incomingProfit,
                'profit_total' => $tradeLog->profit,
                'added_lots'   => $incomingClosedLots,
                'closed_lots'  => $tradeLog->closed_lots,
                'open_lots'    => $tradeLog->lots,
                'status'       => $tradeLog->status,
                'close_price'  => $tradeLog->close_price,
                'reason'       => $tradeLog->close_reason,
            ]);

            if ($status === 'closed') {
                $signal = Signal::where('ticket', $validated['ticket'])->first();
                if ($signal) {
                    $signal->update(['active' => false]);
                    \Log::info('Linked signal closed', ['ticket' => $validated['ticket']]);
                }
            }

            return response()->json([
                'success'       => true,
                'message'       => 'Trade log updated successfully',
                'ticket'        => $validated['ticket'],
                'status'        => $status,
                'closed_lots'   => $tradeLog->closed_lots,
                'profit_total'  => $tradeLog->profit,
            ], 200);
        });
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
