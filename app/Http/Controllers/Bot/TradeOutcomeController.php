<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViomiaTradeOutcome;
use App\Models\Account;
use App\Traits\ApiResponseFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TradeOutcomeController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store trade outcome (when trade closes)
     * 
     * Called by EA AiOutcome.mqh when trade closes
     * Saves complete trade outcomes to database for AI learning and reporting
     * 
     * P0-1: "Data Coming from EA Not Saved"
     */
    public function store(Request $request)
    {
        // Read raw JSON
        $raw = $request->getContent();
        Log::info('Trade outcome received from EA: ' . substr($raw, 0, 200));

        // Clean null bytes
        $clean = preg_replace('/\x00/', '', $raw);
        
        // Decode JSON
        $data = json_decode($clean, true);
        
        if (!$data) {
            Log::error('Invalid JSON in trade outcome', ['raw' => substr($raw, 0, 500)]);
            return $this->errorResponse('Invalid JSON format', ['raw' => $raw], 400);
        }

        // P0-1a: CRITICAL - Verify account_id is present EARLY
        if (empty($data['account_id'])) {
            Log::error('❌ CRITICAL: Missing account_id in trade outcome', [
                'ticket' => $data['ticket'] ?? 'unknown',
                'symbol' => $data['symbol'] ?? 'unknown',
                'data_keys' => array_keys($data),
            ]);
            return $this->errorResponse(
                'CRITICAL: account_id is required and cannot be empty. EA must send account_id in payload.',
                ['missing_field' => 'account_id'],
                422
            );
        }

        // Validate all outcome fields
        try {
            $validated = validator($data, [
                // Trade identification
                'ticket' => 'required|numeric|unique:viomia_trade_outcomes,ticket',
                'account_id' => 'required|string|min:1|max:20',  // P0-1a: Strict validation - account_id CANNOT be empty
                'symbol' => 'required|string|max:20',
                'decision' => 'required|string|in:BUY,SELL',
                
                // Price levels
                'entry' => 'required|numeric',
                'sl' => 'required|numeric',
                'tp' => 'required|numeric',
                'close_price' => 'required|numeric',
                'profit' => 'required|numeric',
                
                // Trade details
                'close_reason' => 'nullable|string|max:100',
                'duration_mins' => 'nullable|integer',
                'result' => 'required|string|in:WIN,LOSS',
                
                // Technical indicators at close
                'rsi' => 'nullable|numeric|between:0,100',
                'atr' => 'nullable|numeric',
                'trend' => 'nullable|integer|in:-1,0,1',
                'session' => 'nullable|integer|in:0,1,2,3',
                
                // Pattern signals at close
                'bos' => 'nullable|boolean|integer:0,1',
                'liquidity_sweep' => 'nullable|boolean|integer:0,1',
                'equal_highs' => 'nullable|boolean|integer:0,1',
                'equal_lows' => 'nullable|boolean|integer:0,1',
                'volume_spike' => 'nullable|boolean|integer:0,1',
                
                // Market context
                'dxy_trend' => 'nullable|integer|in:-1,0,1',
                'risk_off' => 'nullable|integer|in:0,1',
            ])->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for trade outcome', ['errors' => $e->errors()]);
            return $this->errorResponse('Validation failed', $e->errors(), 422);
        }

        // Save to database in a transaction
        try {
            return DB::transaction(function () use ($validated) {
                // Create outcome record
                $outcome = ViomiaTradeOutcome::create([
                    'ticket' => (int)$validated['ticket'],
                    'account_id' => $validated['account_id'],
                    'symbol' => $validated['symbol'],
                    'decision' => $validated['decision'],
                    
                    // Prices
                    'entry' => (float)$validated['entry'],
                    'sl' => (float)$validated['sl'],
                    'tp' => (float)$validated['tp'],
                    'close_price' => (float)$validated['close_price'],
                    'profit' => (float)$validated['profit'],
                    
                    // Details
                    'close_reason' => $validated['close_reason'] ?? null,
                    'duration_mins' => isset($validated['duration_mins']) ? (int)$validated['duration_mins'] : null,
                    'result' => $validated['result'],
                    
                    // Technical
                    'rsi' => isset($validated['rsi']) ? (float)$validated['rsi'] : null,
                    'atr' => isset($validated['atr']) ? (float)$validated['atr'] : null,
                    'trend' => isset($validated['trend']) ? (int)$validated['trend'] : null,
                    'session' => isset($validated['session']) ? (int)$validated['session'] : null,
                    
                    // Patterns
                    'bos' => (bool)($validated['bos'] ?? false),
                    'liquidity_sweep' => (bool)($validated['liquidity_sweep'] ?? false),
                    'equal_highs' => (bool)($validated['equal_highs'] ?? false),
                    'equal_lows' => (bool)($validated['equal_lows'] ?? false),
                    'volume_spike' => (bool)($validated['volume_spike'] ?? false),
                    
                    // Market context
                    'dxy_trend' => isset($validated['dxy_trend']) ? (int)$validated['dxy_trend'] : 0,
                    'risk_off' => isset($validated['risk_off']) ? (int)$validated['risk_off'] : 0,
                    
                    // Timestamp
                    'recorded_at' => now(),
                ]);

                Log::info('✅ Trade outcome saved successfully', [
                    'ticket' => $outcome->ticket,
                    'account_id' => $outcome->account_id,
                    'symbol' => $outcome->symbol,
                    'profit' => $outcome->profit,
                    'result' => $outcome->result,
                ]);

                return $this->successResponse(
                    'Trade outcome recorded successfully',
                    [
                        'id' => $outcome->id,
                        'ticket' => $outcome->ticket,
                        'symbol' => $outcome->symbol,
                        'profit' => $outcome->profit,
                        'result' => $outcome->result,
                    ],
                    201
                );
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors (unique constraint, etc)
            Log::error('Database error saving outcome: ' . $e->getMessage(), [
                'code' => $e->getCode(),
                'ticket' => $validated['ticket'] ?? 'unknown'
            ]);
            
            // Check if duplicate ticket
            if ($e->getCode() == 23505 || strpos($e->getMessage(), 'unique') !== false) {
                return $this->errorResponse(
                    'Trade outcome already recorded',
                    ['ticket' => $validated['ticket'] ?? null],
                    409
                );
            }
            
            return $this->errorResponse('Database error', ['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Error saving trade outcome: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return $this->errorResponse(
                'Failed to record trade outcome',
                ['error' => $e->getMessage()],
                500
            );
        }
    }

    /**
     * Get outcome by ticket
     */
    public function getByTicket($ticket)
    {
        $outcome = ViomiaTradeOutcome::where('ticket', $ticket)->first();
        
        if (!$outcome) {
            return $this->errorResponse('Trade outcome not found', ['ticket' => $ticket], 404);
        }
        
        return $this->successResponse('Trade outcome retrieved', $outcome->toArray(), 200);
    }

    /**
     * Get performance statistics
     */
    public function getStats(Request $request)
    {
        $account_id = $request->query('account_id');
        $symbol = $request->query('symbol');
        $days = $request->query('days', 7);
        
        $query = ViomiaTradeOutcome::query();
        
        if ($account_id) {
            $query->where('account_id', $account_id);
        }
        
        if ($symbol) {
            $query->where('symbol', $symbol);
        }
        
        if ($days) {
            $query->where('recorded_at', '>=', now()->subDays($days));
        }
        
        $stats = [
            'total_trades' => $query->count(),
            'wins' => $query->where('result', 'WIN')->count(),
            'losses' => $query->where('result', 'LOSS')->count(),
            'total_profit' => $query->sum('profit'),
            'avg_profit' => $query->avg('profit'),
            'win_rate' => 0,
        ];
        
        if ($stats['total_trades'] > 0) {
            $stats['win_rate'] = round(($stats['wins'] / $stats['total_trades']) * 100, 2);
        }
        
        return $this->successResponse('Performance statistics', $stats, 200);
    }

    /**
     * Get pattern analysis
     */
    public function getPatternAnalysis(Request $request)
    {
        $pattern = $request->query('pattern'); // bos, liquidity_sweep, equal_highs, etc
        
        if (!$pattern) {
            return $this->errorResponse('Pattern type required', null, 400);
        }
        
        $query = ViomiaTradeOutcome::query();
        
        // Filter by pattern
        if ($pattern === 'bos') {
            $query->where('bos', true);
        } elseif ($pattern === 'liquidity_sweep') {
            $query->where('liquidity_sweep', true);
        } elseif ($pattern === 'equal_highs') {
            $query->where('equal_highs', true);
        } elseif ($pattern === 'equal_lows') {
            $query->where('equal_lows', true);
        } elseif ($pattern === 'volume_spike') {
            $query->where('volume_spike', true);
        } else {
            return $this->errorResponse('Invalid pattern type', null, 400);
        }
        
        $analysis = [
            'pattern' => $pattern,
            'total' => $query->count(),
            'wins' => $query->where('result', 'WIN')->count(),
            'losses' => $query->where('result', 'LOSS')->count(),
            'avg_profit' => $query->avg('profit'),
            'win_rate' => 0,
        ];
        
        if ($analysis['total'] > 0) {
            $analysis['win_rate'] = round(($analysis['wins'] / $analysis['total']) * 100, 2);
        }
        
        return $this->successResponse('Pattern analysis', $analysis, 200);
    }
}
