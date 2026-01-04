<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeLog;
use App\Models\Signal;
use App\Models\Account;
use App\Models\SignalAccount;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class SignalController extends Controller
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
        // Step 1: Read and clean raw JSON
        $raw = $request->getContent();
        Log::info('Incoming raw request: ' . $raw);

        $clean = preg_replace('/\x00/', '', $raw);
        Log::info('Cleaned raw request: ' . $clean);

        $data = json_decode($clean, true);
        if (!$data) {
            return response()->json([
                'error' => 'Invalid JSON format',
                'raw'   => $raw
            ], 400);
        }
        Log::info('Parsed JSON:', $data);

        // Step 2: Validate incoming data
        $validated = validator($data, [
            'ticket'    => 'required|string',
            'symbol'    => 'required|string|max:10',
            'direction' => 'required|in:buy,sell',
            'entry'     => 'required|numeric',
            'sl'        => 'required|numeric',
            'tp'        => 'required|numeric',
            'timeframe' => 'required|string|max:10',
            'lots'      => 'required|numeric',
        ])->validate();

        // Step 3: Prevent duplicate tickets early
        if (Signal::where('ticket', $validated['ticket'])->exists()) {
            return response()->json([
                'message' => 'Signal already exists',
                'ticket'  => $validated['ticket']
            ], 200);
        }

        // Step 4: Use a DB transaction for atomicity
        DB::beginTransaction();
        try {
            // Create Signal (ticket MUST be included)
            $signal = Signal::create([
                'ticket'    => $validated['ticket'],  // ✅ Include ticket
                'symbol'    => $validated['symbol'],
                'direction' => $validated['direction'],
                'entry'     => $validated['entry'],
                'sl'        => $validated['sl'],
                'tp'        => $validated['tp'],
                'timeframe' => $validated['timeframe'],
                'active'    => true,
            ]);

            // Optionally deactivate previous active signals for the same symbol
            Signal::where('symbol', $validated['symbol'])
                ->where('id', '!=', $signal->id)
                ->where('active', true)
                ->update(['active' => false]);

            // Create TradeLog
            $tradeLog = TradeLog::create([
                'ticket'      => $validated['ticket'],
                'symbol'      => $validated['symbol'],
                'type'        => $validated['direction'],
                'lots'        => $validated['lots'],
                'open_price'  => $validated['entry'],
                'sl'          => $validated['sl'],
                'tp'          => $validated['tp'],
            ]);

            // Distribute Signal to all active accounts
            $accounts = Account::where('active', true)->get();
            foreach ($accounts as $account) {
                Log::info("Distributing signal {$signal->id} to account {$account->id}");

                $signalAccount = SignalAccount::create([
                    'signal_id'  => $signal->id,
                    'account_id' => $account->id,
                    'status'     => 'pending',
                    'ticket'     => $validated['ticket'],
                ]);

                if (!$signalAccount) {
                    Log::error("Failed to distribute signal {$signal->id} to account {$account->id}");
                    throw new \Exception("Signal distribution failed for account {$account->id}");
                }
            }

            DB::commit(); // Everything succeeded
            return response()->json([
                'success'   => true,
                'message'   => 'Signal & trade log created successfully',
                'signal_id' => $signal->id,
                'ticket'    => $validated['ticket']
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Signal creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Signal creation failed',
                'details' => $e->getMessage()
            ], 500);
        }
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

    public function getActive()
    {
        return response()->json(
            Signal::where('active', true)->latest()->first()
        );
    }

    
}
