<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Models\TradeEntryContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradeEntryContextController extends Controller
{
    /**
     * Store entry-time technical context for a trade
     * 
     * Called by EA when trade opens (OnTradeTransaction DEAL_ENTRY)
     * Solves P0-4: "Patterns Detected at Wrong Time"
     * 
     * Captures technical state at entry for proper AI training data
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Trade identification
            'account_id' => 'required|string',
            'ticket' => 'required|integer',
            'symbol' => 'required|string',
            'direction' => 'required|in:BUY,SELL',
            'entry_price' => 'required|numeric',
            'entry_time' => 'required|date_format:Y-m-d H:i:s',
            
            // Technical indicators AT ENTRY
            'entry_rsi' => 'nullable|numeric|between:0,100',
            'entry_atr' => 'nullable|numeric',
            'entry_rsi_level' => 'nullable|in:oversold,neutral,overbought',  // RSI interpretation
            
            // Trend at entry
            'entry_trend' => 'nullable|in:UP,DOWN,RANGE',
            'trend_strength' => 'nullable|numeric|between:0,1',  // 0-1 confidence
            
            // Patterns AT ENTRY (not at close)
            'entry_pattern_type' => 'nullable|in:BOS,LIQSWP,OBBLOCK,FVG,NONE',
            'pattern_quality' => 'nullable|numeric|between:0,100',
            
            // Additional context
            'entry_atr_multiplier' => 'nullable|numeric',  // SL = entry +/- 1.5*ATR
            'entry_spread' => 'nullable|numeric',
            'entry_bid' => 'nullable|numeric',
            'entry_ask' => 'nullable|numeric',
            
            // DXY context (macro context)
            'dxy_trend' => 'nullable|in:UP,DOWN,RANGE',
            'dxy_level' => 'nullable|string',
            
            // Risk-off indicator
            'risk_off' => 'nullable|boolean',  // VIX high, news event, etc
            
            // Account state at entry
            'account_balance_at_entry' => 'nullable|numeric',
            'account_equity_at_entry' => 'nullable|numeric',
            'margin_used_percent' => 'nullable|numeric|between:0,100',
            
            // Signal reference (optional)
            'signal_id' => 'nullable|string',
            'signal_correlation_id' => 'nullable|string',
        ]);

        try {
            // Store entry context
            $context = TradeEntryContext::create([
                'account_id' => $validated['account_id'],
                'ticket' => $validated['ticket'],
                'symbol' => $validated['symbol'],
                'direction' => $validated['direction'],
                'entry_price' => $validated['entry_price'],
                'entry_time' => $validated['entry_time'],
                
                // Technical data
                'entry_rsi' => $validated['entry_rsi'] ?? null,
                'entry_atr' => $validated['entry_atr'] ?? null,
                'entry_rsi_level' => $validated['entry_rsi_level'] ?? null,
                
                // Trend
                'entry_trend' => $validated['entry_trend'] ?? null,
                'trend_strength' => $validated['trend_strength'] ?? null,
                
                // Patterns
                'entry_pattern_type' => $validated['entry_pattern_type'] ?? null,
                'pattern_quality' => $validated['pattern_quality'] ?? null,
                
                // Context
                'entry_atr_multiplier' => $validated['entry_atr_multiplier'] ?? null,
                'entry_spread' => $validated['entry_spread'] ?? null,
                'entry_bid' => $validated['entry_bid'] ?? null,
                'entry_ask' => $validated['entry_ask'] ?? null,
                
                // Macro
                'dxy_trend' => $validated['dxy_trend'] ?? null,
                'dxy_level' => $validated['dxy_level'] ?? null,
                'risk_off' => $validated['risk_off'] ?? false,
                
                // Account
                'account_balance_at_entry' => $validated['account_balance_at_entry'] ?? null,
                'account_equity_at_entry' => $validated['account_equity_at_entry'] ?? null,
                'margin_used_percent' => $validated['margin_used_percent'] ?? null,
                
                // Signal reference
                'signal_id' => $validated['signal_id'] ?? null,
                'signal_correlation_id' => $validated['signal_correlation_id'] ?? null,
            ]);

            Log::info('Entry context stored', [
                'ticket' => $validated['ticket'],
                'symbol' => $validated['symbol'],
                'pattern' => $validated['entry_pattern_type'] ?? 'NONE',
                'entry_rsi' => $validated['entry_rsi'] ?? 'N/A',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entry context recorded',
                'context_id' => $context->id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to store entry context', [
                'error' => $e->getMessage(),
                'ticket' => $validated['ticket'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to store entry context: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get entry context for a specific trade/outcome
     * Used for AI analysis and verification
     */
    public function getByTicket($ticket)
    {
        $context = TradeEntryContext::where('ticket', $ticket)->first();

        if (!$context) {
            return response()->json([
                'error' => "No entry context found for ticket {$ticket}",
            ], 404);
        }

        return response()->json($context);
    }

    /**
     * Get entry context linked to outcomes for AI training
     * Returns entry context + outcome data for machine learning
     */
    public function getTrainingData(Request $request)
    {
        $limit = $request->get('limit', 100);
        $symbol = $request->get('symbol', null);

        $query = DB::table('trade_entry_context as ec')
            ->join('viomia_trade_outcomes as oto', 'ec.ticket', '=', 'oto.ticket')
            ->select([
                'ec.*',
                'oto.profit',
                'oto.result',
                'oto.closed_at',
                'oto.pattern_at_close',
            ])
            ->orderBy('ec.entry_time', 'DESC')
            ->limit($limit);

        if ($symbol) {
            $query->where('ec.symbol', $symbol);
        }

        $data = $query->get();

        return response()->json([
            'count' => $data->count(),
            'training_samples' => $data,
        ]);
    }

    /**
     * Analytics: Pattern effectiveness at entry vs close
     */
    public function patternAnalytics()
    {
        $analytics = DB::table('trade_entry_context as ec')
            ->join('viomia_trade_outcomes as oto', 'ec.ticket', '=', 'oto.ticket')
            ->where('ec.entry_pattern_type', '<>', 'NONE')
            ->select([
                'ec.entry_pattern_type as pattern',
                'ec.entry_rsi_level',
                DB::raw('COUNT(*) as trades'),
                DB::raw('SUM(CASE WHEN oto.result = "WIN" THEN 1 ELSE 0 END) as wins'),
                DB::raw('ROUND(SUM(CASE WHEN oto.result = "WIN" THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as win_rate'),
                DB::raw('ROUND(AVG(oto.profit), 2) as avg_profit'),
                DB::raw('MIN(oto.profit) as min_profit'),
                DB::raw('MAX(oto.profit) as max_profit'),
            ])
            ->groupBy('ec.entry_pattern_type', 'ec.entry_rsi_level')
            ->orderBy('trades', 'DESC')
            ->get();

        return response()->json([
            'pattern_analytics' => $analytics,
        ]);
    }
}
