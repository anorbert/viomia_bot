<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Models\ViomiaSignalPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Signal Pattern Storage Controller
 * 
 * Saves signal pattern information when signals are generated
 * (not just when trades close)
 * 
 * P0-2b: Pattern tracking at signal generation time
 */
class SignalPatternController extends Controller
{
    /**
     * Store signal pattern when signal is generated
     * 
     * Called by AI system after decision_engine analyzes signal
     * Saves which technical patterns were present at signal time
     * 
     * POST /api/bot/signal/pattern
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'symbol'         => 'required|string|max:20',
            'pattern_name'   => 'required|string|max:100',  // Unique pattern ID
            'decision'       => 'required|string|in:BUY,SELL,NO_TRADE',
            'confidence'     => 'required|numeric|between:0,1',
            'account_id'     => 'nullable|string|max:20',
            
            // Pattern flags
            'with_bos'       => 'boolean',
            'with_equal_levels' => 'boolean',
            'liquidity_sweep' => 'boolean',
            'volume_spike'   => 'boolean',
            
            // Context
            'pattern_quality' => 'nullable|numeric|between:0,100',
            'market_regime'   => 'nullable|string|max:50',
            'pattern_count'   => 'nullable|integer|between:0,5',
            'web_sentiment'   => 'nullable|string|max:20',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                // ✅ NEW: Save signal pattern at generation time
                $pattern = ViomiaSignalPattern::create([
                    'pattern_name'      => $validated['pattern_name'],
                    'with_bos'          => (bool)($validated['with_bos'] ?? false),
                    'with_equal_levels' => (bool)($validated['with_equal_levels'] ?? false),
                    'web_sentiment'     => $validated['web_sentiment'] ?? null,
                    'market_regime'     => $validated['market_regime'] ?? null,
                    'decision'          => $validated['decision'],
                    'result'            => null,  // Will be updated when trade closes
                    'profit'            => null,  // Will be updated when trade closes
                ]);

                Log::info('✅ Signal pattern saved', [
                    'pattern_id'    => $pattern->id,
                    'pattern_name'  => $validated['pattern_name'],
                    'decision'      => $validated['decision'],
                    'bos'           => $validated['with_bos'] ?? false,
                    'confidence'    => $validated['confidence'],
                    'account_id'    => $validated['account_id'] ?? 'global',
                ]);

                return response()->json([
                    'success' => true,
                    'pattern_id' => $pattern->id,
                    'message' => 'Signal pattern recorded',
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Failed to save signal pattern: ' . $e->getMessage(), [
                'pattern_name' => $validated['pattern_name'] ?? 'unknown',
                'decision' => $validated['decision'] ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to save pattern',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get pattern analysis statistics
     * 
     * GET /api/bot/signal/pattern/analysis
     */
    public function getAnalysis(Request $request)
    {
        $accountId = $request->query('account_id');
        
        $query = ViomiaSignalPattern::query();
        
        if ($accountId) {
            // If account_id filtering is added to model
            // $query->where('account_id', $accountId);
        }

        // Pattern performance stats
        $patterns = $query
            ->selectRaw('
                pattern_name,
                COUNT(*) as total_signals,
                SUM(CASE WHEN result="WIN" THEN 1 ELSE 0 END) as wins,
                SUM(CASE WHEN result="LOSS" THEN 1 ELSE 0 END) as losses,
                ROUND(SUM(CASE WHEN result="WIN" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as win_rate,
                ROUND(AVG(profit), 2) as avg_profit,
                SUM(with_bos) as bos_count,
                SUM(with_equal_levels) as equal_levels_count
            ')
            ->groupBy('pattern_name')
            ->orderBy('total_signals', 'DESC')
            ->get();

        // BOS impact analysis
        $bosImpact = DB::table('viomia_signal_patterns')
            ->selectRaw('
                with_bos,
                COUNT(*) as total,
                SUM(CASE WHEN result="WIN" THEN 1 ELSE 0 END) as wins,
                ROUND(SUM(CASE WHEN result="WIN" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as win_rate,
                ROUND(AVG(profit), 2) as avg_profit
            ')
            ->groupBy('with_bos')
            ->get();

        return response()->json([
            'patterns' => $patterns,
            'bos_impact' => $bosImpact,
            'summary' => [
                'total_patterns_tracked' => $patterns->sum('total_signals'),
                'avg_win_rate' => $patterns->avg('win_rate'),
            ]
        ]);
    }

    /**
     * Link pattern to trade outcome
     * 
     * When a trade closes, update the pattern record with result
     * 
     * PUT /api/bot/signal/pattern/{patternId}/outcome
     */
    public function linkOutcome(Request $request, $patternId)
    {
        $validated = $request->validate([
            'result'    => 'required|string|in:WIN,LOSS',
            'profit'    => 'required|numeric',
            'ticket'    => 'required|integer',
        ]);

        try {
            $pattern = ViomiaSignalPattern::findOrFail($patternId);
            
            $pattern->update([
                'result' => $validated['result'],
                'profit' => $validated['profit'],
            ]);

            Log::info('✅ Pattern outcome linked', [
                'pattern_id' => $patternId,
                'result' => $validated['result'],
                'profit' => $validated['profit'],
                'ticket' => $validated['ticket'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pattern outcome recorded',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to link pattern outcome: ' . $e->getMessage());
            return response()->json([
                'error' => 'Pattern not found or update failed',
            ], 500);
        }
    }

    /**
     * Get pattern history for a symbol
     * 
     * GET /api/bot/signal/pattern/history/{symbol}
     */
    public function getHistory($symbol)
    {
        $patterns = ViomiaSignalPattern::where('pattern_name', 'like', "$symbol%")
            ->orderBy('created_at', 'DESC')
            ->limit(100)
            ->get();

        return response()->json([
            'symbol' => $symbol,
            'patterns' => $patterns,
            'total' => $patterns->count(),
        ]);
    }
}
