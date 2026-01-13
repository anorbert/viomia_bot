<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappSignal;
use Illuminate\Support\Facades\Log;


class WhatsappSignalController extends Controller
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
        // 🟢 LOG: Incoming request (RAW PAYLOAD)
        Log::info('📥 WhatsApp Signal API hit', [
            'ip'      => $request->ip(),
            'headers' => $request->headers->all(),
            'payload' => $request->all()
        ]);

        try {
            // 🔐 LOG: Authenticated bot
            $bot = $request->user();

            Log::info('🤖 Authenticated bot', [
                'bot_id' => $bot?->id,
                'bot_name' => $bot?->name
            ]);

            // ✅ Validate payload
            $validated = $request->validate([
                'symbol'    => 'required|string|max:10',
                'type'      => 'required|in:BUY,SELL',
                'entry'     => 'required|numeric',
                'sl'        => 'required|numeric',
                'tp'        => 'required|array|min:1',
                'tp.*'      => 'numeric',
                'raw_text'  => 'nullable|string',
                'group'     => 'required|string|max:50',
                'sender'    => 'required|string|max:50',
                'timestamp' => 'required|date'
            ]);

            Log::info('✅ Payload validated', $validated);

            // 🛑 LOG: Check duplicate signal
            $exists = whatsappSignal::where([
                'symbol'      => $validated['symbol'],
                'type'        => $validated['type'],
                'entry'       => $validated['entry'],
                'received_at' => $validated['timestamp']
            ])->exists();

            if ($exists) {
                Log::warning('⚠️ Duplicate signal ignored', [
                    'symbol' => $validated['symbol'],
                    'entry'  => $validated['entry'],
                    'time'   => $validated['timestamp']
                ]);

                return response()->json([
                    'status' => 'duplicate'
                ], 200);
            }

            // 💾 Save signal
            $signal = whatsappSignal::create([
                'symbol'       => $validated['symbol'],
                'type'         => $validated['type'],
                'entry'        => $validated['entry'],
                'stop_loss'    => $validated['sl'],
                'take_profit'  => $validated['tp'],
                'raw_text'     => $validated['raw_text'] ?? null,
                'group_id'     => $validated['group'],
                'sender'       => $validated['sender'],
                'received_at'  => $validated['timestamp'],
                'source'       => 'whatsapp',
                'bot_id'       => $bot?->id
            ]);

            Log::info('💾 Signal stored successfully', [
                'signal_id' => $signal->id,
                'symbol'    => $signal->symbol,
                'type'      => $signal->type
            ]);

            return response()->json([
                'status' => 'success',
                'signal_id' => $signal->id
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            // ❌ LOG: Validation failed
            Log::error('❌ Validation error while receiving signal', [
                'errors'  => $e->errors(),
                'payload' => $request->all()
            ]);

            throw $e; // Laravel will return proper 422

        } catch (\Throwable $e) {

            // ❌ LOG: Any unexpected error
            Log::critical('🔥 Unexpected error in WhatsApp Signal API', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
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

    public function whatsapp_signal(Request $request)
    {
        //check whatsapp signal incoming request

    }

    public function latestForEA(Request $request)
    {
        // Fetch latest pending signal
        $signal = whatsappSignal::where('status', 'pending')
            ->orderBy('received_at', 'desc')
            ->first();

        if (!$signal) {
            Log::info('📡 EA requested latest signal, but none found');
            return response()->json([
                'status' => 'success',
                'signals' => null
            ]);
        }

        // Determine TP for EA
        $tpArray = is_array($signal->take_profit) ? $signal->take_profit : [$signal->take_profit];
        $tp = null;

        if (strtoupper($signal->type) === 'BUY') {
            $tp = max($tpArray);
        } elseif (strtoupper($signal->type) === 'SELL') {
            $tp = min($tpArray);
        }

        // Log the EA request safely
        Log::info('📡 EA requested latest signal', [
            'signal_id' => $signal->id,
            'symbol' => $signal->symbol,
            'type' => $signal->type,
            'tp_selected' => $tp,
            'bot_id' => $request->user()?->id
        ]);

        // Return payload matching your desired format
        return response()->json([
                'id'        => $signal->id,
                'symbol'    => $signal->symbol,
                'type'      => $signal->type,
                'entry'     => (float) $signal->entry,
                'sl'        => (float) $signal->stop_loss,
                'tp'        => (float) $tp,
                'timeframe' => $signal->timeframe ?? 'M5',
                'source'    => strtoupper($signal->source),
                'created_at'=> $signal->created_at->toDateTimeString()
        ]);
    }

    public function markAsReceived(Request $request, $id)
    {
        // 1️⃣ Read raw MT5 payload
        $raw = $request->getContent();
        \Log::info('Incoming Response Status payload:', ['raw' => $raw]);

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
        // Find the signal by ID
        $signal = whatsappSignal::find($id);

        if (!$signal) {
            Log::warning('⚠️ EA attempted to mark non-existent signal as received', [
                'signal_id' => $id,
                'status' => $data['status'],
                'bot_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Signal not found'
            ], 404);
        }

        // Update status to 'received'
        $signal->status =  $data['status'];
        $signal->save();

        Log::info('✅ Signal marked as received by EA', [
            'signal_id' => $signal->id,
            'bot_id' => $request->user()?->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Signal marked as received'
        ]);
    }
}

