-- ============================================
-- DUMMY DATA FOR AI ANALYTICS TABLES
-- ============================================
-- Use this data to test and verify the AI Analytics features

-- ============================================
-- 1. CANDLE LOGS (Market Data)
-- ============================================
INSERT INTO `viomia_candle_logs` (`symbol`, `price`, `rsi`, `atr`, `trend`, `resistance`, `support`, `session`, `account_id`, `candles_json`, `received_at`, `created_at`, `updated_at`) VALUES

-- EURUSD Data
('EURUSD', 1.08450, 65.23, 0.00145, 1, 1.08650, 1.08200, 1, 'ACC001', '{"o":1.0840,"h":1.0850,"l":1.0835,"c":1.0845}', NOW(), NOW(), NOW()),
('EURUSD', 1.08520, 68.45, 0.00152, 1, 1.08700, 1.08300, 1, 'ACC001', '{"o":1.0845,"h":1.0855,"l":1.0840,"c":1.0852}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('EURUSD', 1.08380, 48.92, 0.00158, 0, 1.08600, 1.08100, 1, 'ACC001', '{"o":1.0852,"h":1.0860,"l":1.0835,"c":1.0838}', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

-- GBPUSD Data
('GBPUSD', 1.27650, 72.34, 0.00165, 1, 1.27900, 1.27400, 1, 'ACC001', '{"o":1.2760,"h":1.2775,"l":1.2748,"c":1.2765}', NOW(), NOW(), NOW()),
('GBPUSD', 1.27520, 55.67, 0.00172, 0, 1.27800, 1.27200, 1, 'ACC001', '{"o":1.2765,"h":1.2780,"l":1.2740,"c":1.2752}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('GBPUSD', 1.27890, 78.45, 0.00168, 1, 1.28100, 1.27600, 1, 'ACC001', '{"o":1.2752,"h":1.2795,"l":1.2750,"c":1.2789}', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

-- USDJPY Data
('USDJPY', 149.850, 61.23, 0.85, 1, 150.500, 149.200, 1, 'ACC001', '{"o":149.800,"h":149.950,"l":149.750,"c":149.850}', NOW(), NOW(), NOW()),
('USDJPY', 149.670, 52.45, 0.78, 0, 150.300, 249.000, 1, 'ACC001', '{"o":149.850,"h":149.900,"l":149.600,"c":149.670}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),

-- AUDUSD Data
('AUDUSD', 0.67350, 64.12, 0.00125, 1, 0.67600, 0.67100, 1, 'ACC001', '{"o":0.6730,"h":0.6740,"l":0.6720,"c":0.6735}', NOW(), NOW(), NOW()),
('AUDUSD', 0.67120, 45.78, 0.00118, 0, 0.67400, 0.66900, 1, 'ACC001', '{"o":0.6735,"h":0.6750,"l":0.6710,"c":0.6712}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- ============================================
-- 2. AI DECISIONS (Trading Decisions)
-- ============================================
INSERT INTO `viomia_decisions` (`symbol`, `decision`, `confidence`, `score`, `reasons`, `entry`, `stop_loss`, `take_profit`, `rr_ratio`, `web_intel`, `web_sentiment`, `web_score_adj`, `account_id`, `decided_at`, `created_at`, `updated_at`) VALUES

('EURUSD', 'BUY', 0.8234, 85, 'Strong uptrend, RSI oversold recovery, Support bounce', 1.08450, 1.08200, 1.08750, 2.15, NULL, 'BULLISH', 5, 'ACC001', NOW(), NOW(), NOW()),
('EURUSD', 'SELL', 0.7156, 72, 'Resistance rejection, Bearish divergence, Volume spike', 1.08520, 1.08800, 1.08100, 1.87, NULL, 'BEARISH', -3, 'ACC001', DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR)),

('GBPUSD', 'BUY', 0.8945, 89, 'Golden cross, Strong momentum, News positive', 1.27650, 1.27400, 1.28200, 2.35, NULL, 'VERY_BULLISH', 8, 'ACC001', NOW(), NOW(), NOW()),
('GBPUSD', 'BUY', 0.7823, 78, 'Support hold, Bounce setup, Trend continuation', 1.27520, 1.27200, 1.27950, 1.92, NULL, 'BULLISH', 4, 'ACC001', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),

('USDJPY', 'SELL', 0.6754, 68, 'Overbought condition, Top formation, Resistance ahead', 149.850, 150.300, 149.200, 1.78, NULL, 'BEARISH', -4, 'ACC001', NOW(), NOW(), NOW()),
('USDJPY', 'BUY', 0.7892, 79, 'Support reclaimed, Downtrend reversal, Bullish candle', 149.670, 149.200, 150.400, 2.12, NULL, 'BULLISH', 3, 'ACC001', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

('AUDUSD', 'BUY', 0.8156, 82, 'Double bottom, Strong close, Momentum building', 0.67350, 0.67100, 0.67850, 1.95, NULL, 'BULLISH', 5, 'ACC001', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- ============================================
-- 3. SIGNAL LOGS (Signals Sent)
-- ============================================
INSERT INTO `viomia_signal_logs` (`symbol`, `decision`, `entry`, `push_status`, `laravel_resp`, `pushed_at`, `created_at`, `updated_at`) VALUES

('EURUSD', 'BUY', 1.08450, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG001"}', NOW(), NOW(), NOW()),
('EURUSD', 'SELL', 1.08520, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG002"}', DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR)),

('GBPUSD', 'BUY', 1.27650, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG003"}', NOW(), NOW(), NOW()),
('GBPUSD', 'BUY', 1.27520, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG004"}', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),

('USDJPY', 'SELL', 149.850, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG005"}', NOW(), NOW(), NOW()),
('USDJPY', 'BUY', 149.670, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG006"}', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

('AUDUSD', 'BUY', 0.67350, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG007"}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- ============================================
-- 4. TRADE EXECUTIONS
-- ============================================
INSERT INTO `viomia_trade_executions` (`account_id`, `ticket`, `symbol`, `decision`, `ml_confidence`, `signal_combo`, `regime_type`, `entry_price`, `profit_loss`, `result`, `session_name`, `created_at`, `updated_at`) VALUES

('ACC001', 1001, 'EURUSD', 'BUY', 0.8234, 'RSI_MA_BOUNCEOFF', 'UPTREND', 1.08450, 125.50, 'WIN', 'LONDON', NOW(), NOW()),
('ACC001', 1002, 'EURUSD', 'SELL', 0.7156, 'RESISTANCE_BEARISH_DIV', 'DOWNTREND', 1.08520, -85.25, 'LOSS', 'LONDON', DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR)),

('ACC001', 1003, 'GBPUSD', 'BUY', 0.8945, 'GOLDEN_CROSS_MOMENTUM', 'UPTREND', 1.27650, 275.80, 'WIN', 'LONDON', NOW(), NOW()),
('ACC001', 1004, 'GBPUSD', 'BUY', 0.7823, 'SUPPORT_BOUNCE_TREND', 'UPTREND', 1.27520, 156.35, 'WIN', 'LONDON', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),

('ACC001', 1005, 'USDJPY', 'SELL', 0.6754, 'OVERBOUGHT_RESISTANCE', 'DOWNTREND', 149.850, 145.20, 'WIN', 'TOKYO', NOW(), NOW()),
('ACC001', 1006, 'USDJPY', 'BUY', 0.7892, 'SUPPORT_REVERSAL', 'UPTREND', 149.670, 98.50, 'WIN', 'TOKYO', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

('ACC001', 1007, 'AUDUSD', 'BUY', 0.8156, 'DOUBLE_BOTTOM_MOMENTUM', 'UPTREND', 0.67350, 189.75, 'WIN', 'SYDNEY', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),

-- Additional trades for more data
('ACC001', 1008, 'EURUSD', 'BUY', 0.7234, 'MA_CROSSOVER', 'UPTREND', 1.08380, -95.50, 'LOSS', 'LONDON', DATE_SUB(NOW(), INTERVAL 6 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR)),
('ACC001', 1009, 'GBPUSD', 'SELL', 0.8123, 'RESISTANCE_TEST', 'DOWNTREND', 1.27890, 210.45, 'WIN', 'LONDON', DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR));

-- ============================================
-- 5. TRADE OUTCOMES (Results)
-- ============================================
INSERT INTO `viomia_trade_outcomes` (`ticket`, `account_id`, `symbol`, `profit`, `result`, `recorded_at`, `created_at`, `updated_at`) VALUES

(1001, 'ACC001', 'EURUSD', 125.50, 'WIN', NOW(), NOW(), NOW()),
(1002, 'ACC001', 'EURUSD', -85.25, 'LOSS', DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR)),

(1003, 'ACC001', 'GBPUSD', 275.80, 'WIN', NOW(), NOW(), NOW()),
(1004, 'ACC001', 'GBPUSD', 156.35, 'WIN', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),

(1005, 'ACC001', 'USDJPY', 145.20, 'WIN', NOW(), NOW(), NOW()),
(1006, 'ACC001', 'USDJPY', 98.50, 'WIN', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),

(1007, 'ACC001', 'AUDUSD', 189.75, 'WIN', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),

(1008, 'ACC001', 'EURUSD', -95.50, 'LOSS', DATE_SUB(NOW(), INTERVAL 6 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(1009, 'ACC001', 'GBPUSD', 210.45, 'WIN', DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR));
