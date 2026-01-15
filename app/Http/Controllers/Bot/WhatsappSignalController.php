<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappSignal;
use App\Models\EaWhatsappExcution;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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

            // 1) Expire all pending signals (global)
            WhatsappSignal::where('status', 'pending')
                ->update([
                    'status'     => 'expired'
                ]);

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
        // Accept JSON OR form-data
        $raw = $request->getContent();
        $data = $request->all();

        // If body exists and request->all() empty, try decode JSON
        if (empty($data) && !empty($raw)) {
            $clean = preg_replace('/\x00/', '', $raw);
            $decoded = json_decode($clean, true);

            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        Log::info('📡 Incoming MT5 payload (latestForEA)', [
            'raw'  => $raw,
            'data' => $data
        ]);

        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid JSON format', 'raw' => $raw], 400);
        }

        $accountId = $data['account_id'] ?? $request->header('X-ACCOUNT-ID');
        if (!$accountId) {
            return response()->json(['error' => 'account_id is required'], 422);
        }
        $accountId = (string)$accountId;
        $signal = DB::transaction(function () use ($accountId) {
            $signal = WhatsappSignal::where('status', 'pending')
                ->whereNotExists(function ($q) use ($accountId) {
                    $q->select(DB::raw(1))
                        ->from('ea_whatsapp_excutions')
                        ->whereColumn('ea_whatsapp_excutions.whatsapp_signal_id', 'whatsapp_signals.id')
                        ->where('ea_whatsapp_excutions.account_id', $accountId);
                })
                ->orderBy('received_at', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$signal) {
                return null;
            }

            EaWhatsappExcution::firstOrCreate([
                'whatsapp_signal_id' => $signal->id,
                'account_id'         => $accountId,
            ], [
                'status'             => 'received',
            ]);

            return $signal;
        });

        if (!$signal) {
            return response()->json(['status' => 'success', 'signal' => null]);
        }

        $tpArray = is_array($signal->take_profit) ? $signal->take_profit : [(float)$signal->take_profit];
        $tp = strtoupper($signal->type) === 'BUY' ? max($tpArray) : min($tpArray);

        return response()->json([
            'id'         => $signal->id,
            'symbol'     => $signal->symbol,
            'type'       => $signal->type,
            'entry'      => (float)$signal->entry,
            'sl'         => (float)$signal->stop_loss,
            'tp'         => (float)$tp,
            'timeframe'  => $signal->timeframe ?? 'M5',
            'source'     => strtoupper($signal->source),
            'created_at' => optional($signal->created_at)->toDateTimeString(),
        ]);
    }

    public function markAsReceived(Request $request, $id)
    {
        // Accept JSON OR form-data
        $raw = $request->getContent();
        $data = $request->all();

        if (empty($data) && !empty($raw)) {
            $clean = preg_replace('/\x00/', '', $raw);
            $decoded = json_decode($clean, true);

            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        Log::info('📥 Incoming EA status payload', [
            'raw'  => $raw,
            'data' => $data
        ]);

        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid JSON format', 'raw' => $raw], 400);
        }

        $accountId = (string)($data['account_id'] ?? $request->header('X-ACCOUNT-ID'));
        $status    = $data['status'] ?? null;

        if (!$accountId || !$status) {
            return response()->json(['error' => 'account_id and status are required'], 422);
        }

        $allowed = ['received', 'executed', 'failed'];
        if (!in_array($status, $allowed, true)) {
            return response()->json(['error' => 'Invalid status value'], 422);
        }

        $row = EaWhatsappExcution::where('whatsapp_signal_id', $id)
            ->where('account_id', $accountId)
            ->first();

        if (!$row) {
            return response()->json(['status' => 'error', 'message' => 'Signal not found for this account'], 404);
        }

        $row->status = $status;
        if ($status === 'executed') {
            $row->executed_at = now();
        }
        $row->save();

        return response()->json(['status' => 'success', 'message' => 'Signal updated']);
    }
}