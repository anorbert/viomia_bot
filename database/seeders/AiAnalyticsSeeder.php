<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // 1. CANDLE LOGS (Market Data)
        // ============================================
        $candleLogs = [
            // EURUSD Data
            [
                'symbol' => 'EURUSD', 'price' => 1.08450, 'rsi' => 65.23, 'atr' => 0.00145, 'trend' => 1,
                'resistance' => 1.08650, 'support' => 1.08200, 'session' => 'LONDON', 'account_id' => 'ACC001',
                'candles_json' => '{"o":1.0840,"h":1.0850,"l":1.0835,"c":1.0845}',
                'received_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'EURUSD', 'price' => 1.08520, 'rsi' => 68.45, 'atr' => 0.00152, 'trend' => 1,
                'resistance' => 1.08700, 'support' => 1.08300, 'session' => 'LONDON', 'account_id' => 'ACC001',
                'candles_json' => '{"o":1.0845,"h":1.0855,"l":1.0840,"c":1.0852}',
                'received_at' => now()->subHour(), 'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
            // GBPUSD Data
            [
                'symbol' => 'GBPUSD', 'price' => 1.27650, 'rsi' => 72.34, 'atr' => 0.00165, 'trend' => 1,
                'resistance' => 1.27900, 'support' => 1.27400, 'session' => 'LONDON', 'account_id' => 'ACC001',
                'candles_json' => '{"o":1.2760,"h":1.2775,"l":1.2748,"c":1.2765}',
                'received_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'GBPUSD', 'price' => 1.27520, 'rsi' => 55.67, 'atr' => 0.00172, 'trend' => 0,
                'resistance' => 1.27800, 'support' => 1.27200, 'session' => 'LONDON', 'account_id' => 'ACC001',
                'candles_json' => '{"o":1.2765,"h":1.2780,"l":1.2740,"c":1.2752}',
                'received_at' => now()->subHour(), 'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
            // USDJPY Data
            [
                'symbol' => 'USDJPY', 'price' => 149.850, 'rsi' => 61.23, 'atr' => 0.85, 'trend' => 1,
                'resistance' => 150.500, 'support' => 149.200, 'session' => 'TOKYO', 'account_id' => 'ACC001',
                'candles_json' => '{"o":149.800,"h":149.950,"l":149.750,"c":149.850}',
                'received_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            // AUDUSD Data
            [
                'symbol' => 'AUDUSD', 'price' => 0.67350, 'rsi' => 64.12, 'atr' => 0.00125, 'trend' => 1,
                'resistance' => 0.67600, 'support' => 0.67100, 'session' => 'SYDNEY', 'account_id' => 'ACC001',
                'candles_json' => '{"o":0.6730,"h":0.6740,"l":0.6720,"c":0.6735}',
                'received_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('viomia_candle_logs')->insert($candleLogs);

        // ============================================
        // 2. AI DECISIONS (Trading Decisions)
        // ============================================
        $decisions = [
            [
                'symbol' => 'EURUSD', 'decision' => 'BUY', 'confidence' => 0.8234, 'score' => 85,
                'reasons' => 'Strong uptrend, RSI oversold recovery, Support bounce', 'entry' => 1.08450,
                'stop_loss' => 1.08200, 'take_profit' => 1.08750, 'rr_ratio' => 2.15,
                'web_intel' => null, 'web_sentiment' => 'BULLISH', 'web_score_adj' => 5, 'account_id' => 'ACC001',
                'decided_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'GBPUSD', 'decision' => 'BUY', 'confidence' => 0.8945, 'score' => 89,
                'reasons' => 'Golden cross, Strong momentum, News positive', 'entry' => 1.27650,
                'stop_loss' => 1.27400, 'take_profit' => 1.28200, 'rr_ratio' => 2.35,
                'web_intel' => null, 'web_sentiment' => 'VERY_BULLISH', 'web_score_adj' => 8, 'account_id' => 'ACC001',
                'decided_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'USDJPY', 'decision' => 'SELL', 'confidence' => 0.6754, 'score' => 68,
                'reasons' => 'Overbought condition, Top formation, Resistance ahead', 'entry' => 149.850,
                'stop_loss' => 150.300, 'take_profit' => 149.200, 'rr_ratio' => 1.78,
                'web_intel' => null, 'web_sentiment' => 'BEARISH', 'web_score_adj' => -4, 'account_id' => 'ACC001',
                'decided_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'AUDUSD', 'decision' => 'BUY', 'confidence' => 0.8156, 'score' => 82,
                'reasons' => 'Double bottom, Strong close, Momentum building', 'entry' => 0.67350,
                'stop_loss' => 0.67100, 'take_profit' => 0.67850, 'rr_ratio' => 1.95,
                'web_intel' => null, 'web_sentiment' => 'BULLISH', 'web_score_adj' => 5, 'account_id' => 'ACC001',
                'decided_at' => now()->subHour(), 'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
        ];

        DB::table('viomia_decisions')->insert($decisions);

        // ============================================
        // 3. SIGNAL LOGS (Signals Sent)
        // ============================================
        $signalLogs = [
            [
                'symbol' => 'EURUSD', 'decision' => 'BUY', 'entry' => 1.08450, 'push_status' => 'SUCCESS',
                'laravel_resp' => json_encode(['status' => 'signal_received', 'signal_id' => 'SIG001']),
                'pushed_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'GBPUSD', 'decision' => 'BUY', 'entry' => 1.27650, 'push_status' => 'SUCCESS',
                'laravel_resp' => json_encode(['status' => 'signal_received', 'signal_id' => 'SIG002']),
                'pushed_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'USDJPY', 'decision' => 'SELL', 'entry' => 149.850, 'push_status' => 'SUCCESS',
                'laravel_resp' => json_encode(['status' => 'signal_received', 'signal_id' => 'SIG003']),
                'pushed_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'symbol' => 'AUDUSD', 'decision' => 'BUY', 'entry' => 0.67350, 'push_status' => 'SUCCESS',
                'laravel_resp' => json_encode(['status' => 'signal_received', 'signal_id' => 'SIG004']),
                'pushed_at' => now()->subHour(), 'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
        ];

        DB::table('viomia_signal_logs')->insert($signalLogs);

        // ============================================
        // 4. TRADE EXECUTIONS
        // ============================================
        $tradeExecutions = [
            [
                'account_id' => 'ACC001', 'ticket' => 1001, 'symbol' => 'EURUSD', 'decision' => 'BUY',
                'ml_confidence' => 0.8234, 'signal_combo' => 'RSI_MA_BOUNCEOFF', 'regime_type' => 'UPTREND',
                'entry_price' => 1.08450, 'profit_loss' => 125.50, 'result' => 'WIN', 'session_name' => 'LONDON',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'account_id' => 'ACC001', 'ticket' => 1002, 'symbol' => 'GBPUSD', 'decision' => 'BUY',
                'ml_confidence' => 0.8945, 'signal_combo' => 'GOLDEN_CROSS_MOMENTUM', 'regime_type' => 'UPTREND',
                'entry_price' => 1.27650, 'profit_loss' => 275.80, 'result' => 'WIN', 'session_name' => 'LONDON',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'account_id' => 'ACC001', 'ticket' => 1003, 'symbol' => 'USDJPY', 'decision' => 'SELL',
                'ml_confidence' => 0.6754, 'signal_combo' => 'OVERBOUGHT_RESISTANCE', 'regime_type' => 'DOWNTREND',
                'entry_price' => 149.850, 'profit_loss' => 145.20, 'result' => 'WIN', 'session_name' => 'TOKYO',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'account_id' => 'ACC001', 'ticket' => 1004, 'symbol' => 'AUDUSD', 'decision' => 'BUY',
                'ml_confidence' => 0.8156, 'signal_combo' => 'DOUBLE_BOTTOM_MOMENTUM', 'regime_type' => 'UPTREND',
                'entry_price' => 0.67350, 'profit_loss' => 189.75, 'result' => 'WIN', 'session_name' => 'SYDNEY',
                'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
            [
                'account_id' => 'ACC001', 'ticket' => 1005, 'symbol' => 'EURUSD', 'decision' => 'BUY',
                'ml_confidence' => 0.7234, 'signal_combo' => 'MA_CROSSOVER', 'regime_type' => 'UPTREND',
                'entry_price' => 1.08380, 'profit_loss' => -95.50, 'result' => 'LOSS', 'session_name' => 'LONDON',
                'created_at' => now()->subHours(4), 'updated_at' => now()->subHours(4),
            ],
        ];

        DB::table('viomia_trade_executions')->insert($tradeExecutions);

        // ============================================
        // 5. TRADE OUTCOMES (Results)
        // ============================================
        $tradeOutcomes = [
            [
                'ticket' => 1001, 'account_id' => 'ACC001', 'symbol' => 'EURUSD', 'profit' => 125.50,
                'result' => 'WIN', 'recorded_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'ticket' => 1002, 'account_id' => 'ACC001', 'symbol' => 'GBPUSD', 'profit' => 275.80,
                'result' => 'WIN', 'recorded_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'ticket' => 1003, 'account_id' => 'ACC001', 'symbol' => 'USDJPY', 'profit' => 145.20,
                'result' => 'WIN', 'recorded_at' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'ticket' => 1004, 'account_id' => 'ACC001', 'symbol' => 'AUDUSD', 'profit' => 189.75,
                'result' => 'WIN', 'recorded_at' => now()->subHour(), 'created_at' => now()->subHour(), 'updated_at' => now()->subHour(),
            ],
            [
                'ticket' => 1005, 'account_id' => 'ACC001', 'symbol' => 'EURUSD', 'profit' => -95.50,
                'result' => 'LOSS', 'recorded_at' => now()->subHours(4), 'created_at' => now()->subHours(4), 'updated_at' => now()->subHours(4),
            ],
        ];

        DB::table('viomia_trade_outcomes')->insert($tradeOutcomes);
    }
}
