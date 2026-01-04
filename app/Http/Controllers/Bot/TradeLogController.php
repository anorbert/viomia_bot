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
        // 1️⃣ Read raw MT5 payload
        $raw = $request->getContent();
        \Log::info('Incoming MT5 close payload:', ['raw' => $raw]);

        // 2️⃣ Clean null bytes
        $clean = preg_replace('/\x00/', '', $raw);

        // 3️⃣ Decode JSON
        $data = json_decode($clean, true);
        if (!$data) {
            return response()->json([
                'error' => 'Invalid JSON format',
                'raw'   => $raw
            ], 400);
        }

        // 4️⃣ Validate (NO unique rule here)
        $validated = validator($data, [
            'ticket'      => 'required|string',
            'close_price' => 'required|numeric',
            'profit'      => 'nullable|numeric',
        ])->validate();

        // 5️⃣ Find trade log
        $tradeLog = TradeLog::where('ticket', $validated['ticket'])->first();
        if (!$tradeLog) {
            return response()->json([
                'error' => 'Trade log not found',
                'ticket' => $validated['ticket']
            ], 404);
        }

        // 6️⃣ Update close data
        $tradeLog->update([
            'close_price' => $validated['close_price'],
            'profit'      => $validated['profit'] ?? 0,
        ]);

        \Log::info('Trade log closed', [
            'ticket' => $validated['ticket'],
            'profit' => $validated['profit']
        ]);

        //Update Signal status to closed if linked to a signal
        $signal=Signal::where('ticket',$validated['ticket'])->first();
        if ($signal) {
            $signal->update(['active' => false]);
            \Log::info('Linked signal closed', [
                'ticket' => $validated['ticket'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trade log updated successfully',
            'ticket'  => $validated['ticket']
        ], 200);
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
