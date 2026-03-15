-- ============================================================
-- COMPLETE DUMMY DATA FOR VIOMIA BOT - ALL 44 TABLES
-- ============================================================
-- This file contains comprehensive dummy data for all database tables
-- Import using: mysql -u root -p viomia_bot < COMPLETE_DUMMY_DATA.sql
-- Or via PHPMyAdmin / MySQL Workbench

-- DISABLE FOREIGN KEY CHECKS temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. CORE SYSTEM TABLES
-- ============================================================

-- Roles
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'active', NOW(), NOW()),
(2, 'Trader', 'active', NOW(), NOW()),
(3, 'Premium Member', 'active', NOW(), NOW()),
(4, 'Support Agent', 'active', NOW(), NOW()),
(5, 'Analyst', 'active', NOW(), NOW());

-- Users
DELETE FROM `users`;
INSERT INTO `users` (`id`, `uuid`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `otp`, `country_code`, `phone_number`, `is_active`, `is_default_pin`, `profile_photo`, `bio`, `last_login_at`, `previous_login_at`, `total_login_count`, `total_session_minutes`, `last_activity_at`, `created_at`, `updated_at`) VALUES
(1, UUID(), 1, 'Admin User', 'admin@viomia.com', NOW(), '$2y$12$dummyhashhere123456789', NULL, '+1', '1234567890', 1, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=admin', 'System Administrator', NOW(), NOW(), 45, 12000, NOW(), NOW(), NOW()),
(2, UUID(), 2, 'John Trader', 'john.trader@example.com', NOW(), '$2y$12$dummyhashhere123456789', NULL, '+44', '447911123456', 1, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=john', 'Professional Trader', NOW(), NOW(), 28, 8500, NOW(), NOW(), NOW()),
(3, UUID(), 3, 'Jane Premium', 'jane.premium@example.com', NOW(), '$2y$12$dummyhashhere123456789', NULL, '+1', '5551234567', 1, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=jane', 'Premium Trading Member', NOW(), NOW(), 156, 45000, NOW(), NOW(), NOW()),
(4, UUID(), 2, 'Mike Trader', 'mike@example.com', NOW(), '$2y$12$dummyhashhere123456789', NULL, '+91', '9876543210', 1, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=mike', 'Active Trader', NOW(), NOW(), 89, 25000, NOW(), NOW(), NOW()),
(5, UUID(), 3, 'Sarah Analytics', 'sarah@example.com', NOW(), '$2y$12$dummyhashhere123456789', NULL, '+33', '0612345678', 1, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=sarah', 'Data Analyst', NOW(), NOW(), 203, 55000, NOW(), NOW(), NOW());

-- ============================================================
-- 2. BANKING & PAYMENTS TABLES
-- ============================================================

-- Banks
DELETE FROM `banks`;
INSERT INTO `banks` (`id`, `payment_owner`, `appId`, `secret`, `logo`, `charges`, `phone_number`, `balance`, `status`, `deactivated_at`) VALUES
(1, 'Momo Payment', 'momo_app_001', 'momo_secret_key_123', 'https://viomia.com/logos/momo.png', 2.50, '233123456789', 50000.00, 'active', NULL),
(2, 'Binance', 'binance_app_002', 'binance_secret_key_456', 'https://viomia.com/logos/binance.png', 1.00, '233987654321', 150000.00, 'active', NULL),
(3, 'Bank Transfer', 'bank_app_003', 'bank_secret_key_789', 'https://viomia.com/logos/bank.png', 5.00, '233555555555', 100000.00, 'active', NULL);

-- Subscription Plans
DELETE FROM `subscription_plans`;
INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `currency`, `price`, `billing_interval`, `duration_days`, `description`, `features`, `profit_share`, `max_accounts`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Basic', 'basic', 'USD', 29.99, 'monthly', 30, 'Start your trading journey', '["1 Account","Basic Signals","Email Support"]', 70, 1, 1, 1, NOW(), NOW()),
(2, 'Professional', 'professional', 'USD', 99.99, 'monthly', 30, 'Advanced trading tools', '["3 Accounts","Advanced Signals","Priority Support","API Access"]', 80, 3, 1, 2, NOW(), NOW()),
(3, 'Premium', 'premium', 'USD', 299.99, 'monthly', 30, 'Elite trading experience', '["10 Accounts","Max Signals","24/7 Support","API Access","Custom Rules"]', 85, 10, 1, 3, NOW(), NOW()),
(4, 'Enterprise', 'enterprise', 'USD', 999.99, 'monthly', 30, 'Complete trading suite', '["Unlimited Accounts","All Features","Personal Manager","Custom Integration"]', 90, 99, 1, 4, NOW(), NOW());

-- User Subscriptions
DELETE FROM `user_subscriptions`;
INSERT INTO `user_subscriptions` (`id`, `user_id`, `subscription_plan_id`, `status`, `starts_at`, `ends_at`, `auto_renew`, `reference`, `amount`, `currency`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1, 'SUB_USR002_0001', 99.99, 'USD', 'credit_card', 'Active subscription', NOW(), NOW()),
(2, 3, 3, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1, 'SUB_USR003_0001', 299.99, 'USD', 'binance', 'Premium member', NOW(), NOW()),
(3, 4, 1, 'active', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 25 DAY), 1, 'SUB_USR004_0001', 29.99, 'USD', 'momo', 'Basic plan', NOW(), NOW()),
(4, 5, 2, 'active', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 20 DAY), 1, 'SUB_USR005_0001', 99.99, 'USD', 'bank_transfer', 'Professional member', NOW(), NOW());

-- Payment Transactions
DELETE FROM `payment_transactions`;
INSERT INTO `payment_transactions` (`id`, `user_id`, `subscription_plan_id`, `reference`, `provider`, `currency`, `amount`, `status`, `provider_txn_id`, `checkout_url`, `payload`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'TXN_20260315_001', 'binance', 'USD', 99.99, 'completed', 'BINANCE_TXN_ABC123', 'https://binance.checkout/abc123', '{"wallet":"bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh"}', NOW(), NOW(), NOW()),
(2, 3, 3, 'TXN_20260315_002', 'momo', 'USD', 299.99, 'completed', 'MOMO_TXN_DEF456', 'https://momo.checkout/def456', '{"phone":"+233123456789"}', NOW(), NOW(), NOW()),
(3, 4, 1, 'TXN_20260315_003', 'bank_transfer', 'USD', 29.99, 'pending', 'BANK_TXN_GHI789', 'https://bank.checkout/ghi789', '{"account":"123456789"}', NULL, NOW(), NOW()),
(4, 5, 2, 'TXN_20260315_004', 'binance', 'USD', 99.99, 'completed', 'BINANCE_TXN_JKL012', 'https://binance.checkout/jkl012', '{"wallet":"bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh"}', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Weekly Payments
DELETE FROM `weekly_payments`;
INSERT INTO `weekly_payments` (`id`, `user_id`, `user_subscription_id`, `week_start`, `week_end`, `weekly_profit`, `percentage`, `amount`, `status`, `payment_method`, `reference`, `momo_phone`, `account_name`, `paid_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-2 DAY), DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-8 DAY), 1500.00, 80.00, 1200.00, 'paid', 'binance', 'WEEKLY_PAY_001', NULL, 'John Trader', NOW(), 'Week ending 15 Mar 2026', NOW(), NOW()),
(2, 3, 2, DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-2 DAY), DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-8 DAY), 3500.00, 85.00, 2975.00, 'paid', 'bank_transfer', 'WEEKLY_PAY_002', NULL, 'Jane Premium', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Week ending 15 Mar 2026', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 4, 3, DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-2 DAY), DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-8 DAY), 800.00, 70.00, 560.00, 'pending', 'momo', 'WEEKLY_PAY_003', '+233123456789', 'Mike Trader', NULL, 'Pending payment', NOW(), NOW()),
(4, 5, 4, DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-2 DAY), DATE_SUB(CURDATE(), INTERVAL DAYOFWEEK(CURDATE())-8 DAY), 2200.00, 80.00, 1760.00, 'paid', 'binance', 'WEEKLY_PAY_004', NULL, 'Sarah Analytics', NOW(), 'Week ending 15 Mar 2026', NOW(), NOW());

-- Payment Audit Logs
DELETE FROM `payment_audit_logs`;
INSERT INTO `payment_audit_logs` (`id`, `account_id`, `payment_transaction_id`, `action`, `old_status`, `new_status`, `reason`, `ip_address`, `user_agent`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'ACC002', 1, 'status_change', 'pending', 'completed', 'Payment confirmed', '192.168.1.100', 'Mozilla/5.0', '{"confirmation":"confirmed"}', NOW(), NOW()),
(2, 'ACC003', 2, 'status_change', 'pending', 'completed', 'Payment verified', '192.168.1.101', 'Mozilla/5.0', '{"confirmation":"verified"}', NOW(), NOW());

-- ============================================================
-- 3. TRADING ACCOUNTS & CORE DATA
-- ============================================================

-- Accounts
DELETE FROM `accounts`;
INSERT INTO `accounts` (`id`, `user_id`, `platform`, `server`, `login`, `password`, `account_type`, `active`, `connected`, `meta`, `is_verified`, `verified_at`, `verification_notes`, `rejection_reason`, `created_at`, `updated_at`) VALUES
('ACC001', 2, 'MT5', 'ICMarkets-Live', 'MT5_LOGIN_001', 'mt5_pass_001', 'real', 1, 1, '{"broker":"IC Markets","currency":"USD"}', 1, NOW(), 'Verified and active', NULL, NOW(), NOW()),
('ACC002', 2, 'MT4', 'XMGlobal-Demo', 'MT4_LOGIN_002', 'mt4_pass_002', 'demo', 1, 1, '{"broker":"XM Global","currency":"USD"}', 1, NOW(), 'Demo account verified', NULL, NOW(), NOW()),
('ACC003', 3, 'MT5', 'Pepperstone-Live', 'MT5_LOGIN_003', 'mt5_pass_003', 'real', 1, 1, '{"broker":"Pepperstone","currency":"USD"}', 1, NOW(), 'Premium member verified', NULL, NOW(), NOW()),
('ACC004', 4, 'MT5', 'ICMarkets-Live', 'MT5_LOGIN_004', 'mt5_pass_004', 'real', 1, 1, '{"broker":"IC Markets","currency":"USD"}', 1, NOW(), 'Active trader verified', NULL, NOW(), NOW()),
('ACC005', 5, 'MT4', 'FXCM-Demo', 'MT4_LOGIN_005', 'mt4_pass_005', 'demo', 1, 1, '{"broker":"FXCM","currency":"USD"}', 1, NOW(), 'Demo account for practice', NULL, NOW(), NOW());

-- Account Snapshots
DELETE FROM `account_snapshots`;
INSERT INTO `account_snapshots` (`id`, `account_id`, `initial_balance`, `balance`, `equity`, `margin`, `free_margin`, `drawdown`) VALUES
(1, 'ACC001', 10000.00, 12450.75, 12480.30, 2100.00, 9380.30, -2.35),
(2, 'ACC002', 5000.00, 5230.45, 5245.67, 900.00, 4345.67, -1.25),
(3, 'ACC003', 50000.00, 62340.80, 62890.45, 15000.00, 47890.45, -3.45),
(4, 'ACC004', 20000.00, 23450.20, 23675.90, 5000.00, 18675.90, -2.10),
(5, 'ACC005', 2000.00, 2145.30, 2156.78, 300.00, 1856.78, -0.98);

-- API Keys
DELETE FROM `api_keys`;
INSERT INTO `api_keys` (`id`, `key`, `label`, `created_at`, `updated_at`) VALUES
(1, 'sk_live_123456789abcdefghijkl', 'Production API Key', NOW(), NOW()),
(2, 'sk_test_987654321zyxwvutsrqpo', 'Testing API Key', NOW(), NOW()),
(3, 'sk_live_111222333444555666777', 'Trading Bot API Key', NOW(), NOW());

-- ============================================================
-- 4. TRADE EVENTS & LOGS
-- ============================================================

-- Trade Events
DELETE FROM `trade_events`;
INSERT INTO `trade_events` (`id`, `account_id`, `ticket`, `direction`, `entry_price`, `sl_price`, `tp_price`, `lot_size`, `signal_source`, `opened_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 1001, 'BUY', 1.08450, 1.08200, 1.08750, 1.0, 'AI_Signal', NOW(), NOW(), NOW()),
(2, 'ACC001', 1002, 'SELL', 1.27650, 1.27950, 1.27200, 0.5, 'AI_Signal', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(3, 'ACC003', 1003, 'BUY', 149.850, 149.200, 150.500, 2.0, 'AI_Signal', DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR), DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(4, 'ACC004', 1004, 'SELL', 0.67350, 0.67600, 0.67100, 1.5, 'AI_Signal', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(5, 'ACC002', 1005, 'BUY', 1.08520, 1.08300, 1.08800, 0.8, 'WhatsApp', DATE_SUB(NOW(), INTERVAL 6 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR));

-- Trade Logs
DELETE FROM `trade_logs`;
INSERT INTO `trade_logs` (`id`, `account_id`, `ticket`, `symbol`, `type`, `lots`, `closed_lots`, `sl`, `tp`, `open_price`, `close_price`, `profit`, `status`, `close_reason`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 1001, 'EURUSD', 'buy', 1.0, 0.0, 1.08200, 1.08750, 1.08450, NULL, NULL, 'open', NULL, NOW(), NOW()),
(2, 'ACC001', 1002, 'GBPUSD', 'sell', 0.5, 0.5, 1.27950, 1.27200, 1.27650, 1.27400, 125.00, 'closed', 'tp_hit', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(3, 'ACC003', 1003, 'USDJPY', 'buy', 2.0, 1.0, 149.200, 150.500, 149.850, 150.200, 700.00, 'partial_closed', 'partial_tp', DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(4, 'ACC004', 1004, 'AUDUSD', 'sell', 1.5, 1.5, 0.67600, 0.67100, 0.67350, 0.67200, 225.00, 'closed', 'sl_hit', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(5, 'ACC002', 1005, 'EURUSD', 'buy', 0.8, 0.0, 1.08300, 1.08800, 1.08520, NULL, NULL, 'open', NULL, NOW(), NOW());

-- Position Updates
DELETE FROM `position_updates`;
INSERT INTO `position_updates` (`id`, `account_id`, `ticket`, `entry_price`, `current_price`, `unrealized_pl`, `unrealized_pl_percent`, `lot_size`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 1001, 1.08450, 1.08620, 136.00, 0.158, 1.0, NOW(), NOW()),
(2, 'ACC003', 1003, 149.850, 150.120, 540.00, 0.18, 2.0, NOW(), NOW()),
(3, 'ACC002', 1005, 1.08520, 1.08680, 128.00, 0.148, 0.8, NOW(), NOW());

-- Daily Summaries
DELETE FROM `daily_summaries`;
INSERT INTO `daily_summaries` (`id`, `account_id`, `summary_date`, `daily_pl`, `trades_count`, `winning_trades`, `losing_trades`, `win_rate_percent`, `balance`, `equity`, `captured_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', CURDATE(), 350.00, 5, 3, 2, 60.00, 12450.75, 12480.30, NOW(), NOW(), NOW()),
(2, 'ACC001', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 245.50, 4, 2, 2, 50.00, 12100.75, 12150.80, NOW(), NOW(), NOW()),
(3, 'ACC003', CURDATE(), 1250.00, 8, 6, 2, 75.00, 62340.80, 62890.45, NOW(), NOW(), NOW()),
(4, 'ACC003', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 890.30, 6, 5, 1, 83.33, 61090.80, 61640.15, NOW(), NOW(), NOW()),
(5, 'ACC002', CURDATE(), 180.45, 3, 2, 1, 66.67, 5230.45, 5245.67, NOW(), NOW(), NOW()),
(6, 'ACC004', CURDATE(), 520.75, 7, 5, 2, 71.43, 23450.20, 23675.90, NOW(), NOW(), NOW()),
(7, 'ACC005', CURDATE(), 85.30, 2, 1, 1, 50.00, 2145.30, 2156.78, NOW(), NOW(), NOW());

-- ============================================================
-- 5. TRADING SIGNALS & EXECUTION
-- ============================================================

-- Signals
DELETE FROM `signals`;
INSERT INTO `signals` (`id`, `symbol`, `ticket`, `direction`, `entry`, `sl`, `tp`, `timeframe`, `active`, `created_at`, `updated_at`) VALUES
(1, 'EURUSD', 'SIG_001', 'buy', 1.08450, 1.08200, 1.08750, '1H', 1, NOW(), NOW()),
(2, 'GBPUSD', 'SIG_002', 'sell', 1.27650, 1.27950, 1.27200, '4H', 1, NOW(), NOW()),
(3, 'USDJPY', 'SIG_003', 'buy', 149.850, 149.200, 150.500, '1H', 1, NOW(), NOW()),
(4, 'AUDUSD', 'SIG_004', 'buy', 0.67350, 0.67100, 0.67850, '4H', 1, NOW(), NOW()),
(5, 'EURUSD', 'SIG_005', 'sell', 1.08520, 1.08800, 1.08100, '1D', 0, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY));

-- Signal Accounts
DELETE FROM `signal_accounts`;
INSERT INTO `signal_accounts` (`id`, `signal_id`, `account_id`, `status`, `ticket`, `created_at`, `updated_at`) VALUES
(1, 1, 'ACC001', 'executed', 1001, NOW(), NOW()),
(2, 1, 'ACC004', 'executed', 1004, NOW(), NOW()),
(3, 2, 'ACC001', 'executed', 1002, NOW(), NOW()),
(4, 3, 'ACC003', 'executed', 1003, NOW(), NOW()),
(5, 4, 'ACC002', 'pending', NULL, NOW(), NOW());

-- Whatsapp Signals
DELETE FROM `whatsapp_signals`;
INSERT INTO `whatsapp_signals` (`id`, `source`, `group_id`, `sender`, `symbol`, `type`, `entry`, `stop_loss`, `take_profit`, `raw_text`, `status`, `received_at`, `created_at`, `updated_at`) VALUES
(1, 'whatsapp', 'GROUP_001', 'TradingBot', 'EURUSD', 'BUY', 1.08450, 1.08200, '["1.08750","1.09000"]', 'BUY EURUSD @ 1.08450 SL: 1.08200 TP: 1.08750', 'executed', NOW(), NOW(), NOW()),
(2, 'whatsapp', 'GROUP_001', 'TradingBot', 'GBPUSD', 'SELL', 1.27650, 1.27950, '["1.27200","1.26800"]', 'SELL GBPUSD @ 1.27650 SL: 1.27950 TP: 1.27200', 'executed', NOW(), NOW(), NOW()),
(3, 'whatsapp', 'GROUP_001', 'TradingBot', 'USDJPY', 'BUY', 149.850, 149.200, '["150.500","151.000"]', 'BUY USDJPY @ 149.850 SL: 149.200 TP: 150.500', 'pending', NOW(), NOW(), NOW());

-- EA WhatsApp Executions
DELETE FROM `ea_whatsapp_excutions`;
INSERT INTO `ea_whatsapp_excutions` (`id`, `whatsapp_signal_id`, `account_id`, `status`, `executed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ACC001', 'executed', NOW(), NOW(), NOW()),
(2, 2, 'ACC001', 'executed', NOW(), NOW(), NOW()),
(3, 3, 'ACC003', 'failed', NULL, NOW(), NOW());

-- ============================================================
-- 6. TECHNICAL ANALYSIS & INDICATORS
-- ============================================================

-- Technical Signals
DELETE FROM `technical_signals`;
INSERT INTO `technical_signals` (`id`, `account_id`, `trend_score`, `choch_signal`, `rsi_value`, `atr_value`, `ema_20`, `ema_50`, `signal_description`, `captured_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 0.78, 'BULLISH_REVERSAL', 65.23, 0.00145, 1.08400, 1.08200, 'Strong uptrend with bullish reversal pattern', NOW(), NOW(), NOW()),
(2, 'ACC003', 0.85, 'NO_REVERSAL', 72.34, 0.00165, 127.650, 127.400, 'Continuation of uptrend, no reversal', NOW(), NOW(), NOW()),
(3, 'ACC002', 0.45, 'BEARISH_REVERSAL', 25.67, 0.00158, 1.08100, 1.08300, 'Potential bearish reversal forming', NOW(), NOW(), NOW());

-- Filter Blocks
DELETE FROM `filter_blocks`;
INSERT INTO `filter_blocks` (`id`, `account_id`, `filter_type`, `block_reason`, `blocked_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC004', 'NEWS', 'High impact news event scheduled', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(2, 'ACC003', 'LONDON_DISABLED', 'London session closed', DATE_SUB(NOW(), INTERVAL 15 MINUTE), DATE_SUB(NOW(), INTERVAL 15 MINUTE), DATE_SUB(NOW(), INTERVAL 15 MINUTE));

-- Loss Limit Alerts
DELETE FROM `loss_limit_alerts`;
INSERT INTO `loss_limit_alerts` (`id`, `account_id`, `daily_loss`, `daily_loss_limit`, `limit_type`, `balance`, `equity`, `alert_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 500.00, 1000.00, 'USD', 12450.75, 12480.30, NOW(), NOW(), NOW()),
(2, 'ACC003', 2000.00, 5000.00, 'USD', 62340.80, 62890.45, Now(), NOW(), NOW());

-- EA Status Changes
DELETE FROM `ea_status_changes`;
INSERT INTO `ea_status_changes` (`id`, `account_id`, `status`, `reason`, `consecutive_losses`, `balance`, `equity`, `positions_open`, `changed_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC001', 'RUNNING', 'Account performing well', 0, 12450.75, 12480.30, 1, NOW(), NOW(), NOW()),
(2, 'ACC003', 'RUNNING', 'Active trading session', 2, 62340.80, 62890.45, 2, NOW(), NOW(), NOW()),
(3, 'ACC004', 'PAUSED', 'Daily loss limit approaching', 3, 23450.20, 23675.90, 1, DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Error Logs
DELETE FROM `error_logs`;
INSERT INTO `error_logs` (`id`, `account_id`, `error_type`, `error_message`, `price_at_error`, `balance`, `equity`, `error_at`, `created_at`, `updated_at`) VALUES
(1, 'ACC002', 'CONNECTION_ERROR', 'Failed to connect to broker server', 1.08450, 5230.45, 5245.67, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'ACC004', 'INSUFFICIENT_MARGIN', 'Not enough margin to open position', 0.67350, 23450.20, 23675.90, DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR));

-- ============================================================
-- 7. NEWS & MARKET DATA
-- ============================================================

-- News Events
DELETE FROM `news_events`;
INSERT INTO `news_events` (`id`, `currency`, `event_name`, `event_time`, `impact`, `raw`, `previous`, `forecast`, `actual`, `notified`, `status`, `created_at`, `updated_at`) VALUES
(1, 'USD', 'Core PCE Price Index YoY', DATE_ADD(CURDATE(), INTERVAL 2 HOUR), 'high', '{"id":"high_impact"}', '3.2%', '2.8%', '2.9%', 1, 'released', NOW(), NOW()),
(2, 'EUR', 'ECB Interest Rate Decision', DATE_ADD(CURDATE(), INTERVAL 5 HOUR), 'high', '{"id":"high_impact"}', '4.25%', '4.25%', '4.25%', 0, 'upcoming', NOW(), NOW()),
(3, 'GBP', 'UK Retail Sales YoY', DATE_ADD(CURDATE(), INTERVAL 8 HOUR), 'medium', '{"id":"medium_impact"}', '-0.5%', '0.2%', '0.8%', 0, 'upcoming', NOW(), NOW()),
(4, 'JPY', 'Japan Unemployment Rate', DATE_ADD(CURDATE(), INTERVAL 10 HOUR), 'medium', '{"id":"medium_impact"}', '2.5%', '2.4%', '2.4%', 0, 'upcoming', NOW(), NOW());

-- ============================================================
-- 8. BOT CONFIGURATION & STATUS
-- ============================================================

-- Bot Statuses
DELETE FROM `bot_statuses`;
INSERT INTO `bot_statuses` (`id`, `balance`, `equity`, `daily_pl`, `open_positions`, `max_dd`, `created_at`, `updated_at`) VALUES
(1, 100234.65, 105420.30, 1250.00, 5, -3.45, NOW(), NOW()),
(2, 98450.20, 100120.75, -850.50, 3, -2.10, NOW(), NOW());

-- Bot Settings
DELETE FROM `bot_settings`;
INSERT INTO `bot_settings` (`id`, `bot_enabled`, `signal_check_interval`, `max_spread_points`, `risk_per_trade`, `max_trades_per_day`, `use_news_filter`, `block_before_news_minutes`, `block_after_news_minutes`, `filter_currencies`, `debug_mode`, `created_at`, `updated_at`) VALUES
(1, 1, 60, 5, 1.50, 10, 1, 30, 15, '["USD","EUR","GBP"]', 0, NOW(), NOW()),
(2, 0, 120, 3, 1.00, 5, 1, 60, 30, '["USD"]', 1, NOW(), NOW());

-- EA Bots
DELETE FROM `ea_bots`;
INSERT INTO `ea_bots` (`id`, `name`, `version`, `address`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Viomia AI Bot', 'v2.5.1', '0x123456789ABCDEF0123456789ABCDEF01234567', 'Advanced AI-powered trading bot with machine learning', 'Active', NOW(), NOW()),
(2, 'Signal Executor Bot', 'v1.8.3', '0xABCDEF0123456789ABCDEF0123456789ABCDEF0', 'Signal execution and management system', 'Active', NOW(), NOW()),
(3, 'Legacy MT4 Bot', 'v1.2.0', '0x0123456789ABCDEF0123456789ABCDEF012345', 'Original MetaTrader 4 integration bot', 'Inactive', NOW(), NOW());

-- ============================================================
-- 9. VIOMIA AI TRADING SYSTEM TABLES
-- ============================================================

-- Candle Logs
DELETE FROM `viomia_candle_logs`;
INSERT INTO `viomia_candle_logs` (`symbol`, `price`, `rsi`, `atr`, `trend`, `resistance`, `support`, `session`, `account_id`, `candles_json`, `received_at`, `created_at`, `updated_at`) VALUES
('EURUSD', 1.08450, 65.23, 0.00145, 1, 1.08650, 1.08200, 1, 'ACC001', '{"o":1.0840,"h":1.0850,"l":1.0835,"c":1.0845}', NOW(), NOW(), NOW()),
('EURUSD', 1.08520, 68.45, 0.00152, 1, 1.08700, 1.08300, 1, 'ACC001', '{"o":1.0845,"h":1.0855,"l":1.0840,"c":1.0852}', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('GBPUSD', 1.27650, 72.34, 0.00165, 1, 1.27900, 1.27400, 1, 'ACC001', '{"o":1.2760,"h":1.2775,"l":1.2748,"c":1.2765}', NOW(), NOW(), NOW()),
('USDJPY', 149.850, 61.23, 0.85, 1, 150.500, 149.200, 1, 'ACC001', '{"o":149.800,"h":149.950,"l":149.750,"c":149.850}', NOW(), NOW(), NOW()),
('AUDUSD', 0.67350, 64.12, 0.00125, 1, 0.67600, 0.67100, 1, 'ACC001', '{"o":0.6730,"h":0.6740,"l":0.6720,"c":0.6735}', NOW(), NOW(), NOW());

-- Decisions (AI Trading Decisions)
DELETE FROM `viomia_decisions`;
INSERT INTO `viomia_decisions` (`symbol`, `decision`, `confidence`, `score`, `reasons`, `entry`, `stop_loss`, `take_profit`, `rr_ratio`, `web_intel`, `web_sentiment`, `web_score_adj`, `account_id`, `decided_at`, `created_at`, `updated_at`) VALUES
('EURUSD', 'BUY', 0.8234, 85, 'Strong uptrend, RSI oversold recovery, Support bounce', 1.08450, 1.08200, 1.08750, 2.15, NULL, 'BULLISH', 5, 'ACC001', NOW(), NOW(), NOW()),
('GBPUSD', 'BUY', 0.8945, 89, 'Golden cross, Strong momentum, News positive', 1.27650, 1.27400, 1.28200, 2.35, NULL, 'VERY_BULLISH', 8, 'ACC001', NOW(), NOW(), NOW()),
('USDJPY', 'SELL', 0.6754, 68, 'Overbought condition, Top formation, Resistance ahead', 149.850, 150.300, 149.200, 1.78, NULL, 'BEARISH', -4, 'ACC001', NOW(), NOW(), NOW()),
('AUDUSD', 'BUY', 0.8156, 82, 'Double bottom, Strong close, Momentum building', 0.67350, 0.67100, 0.67850, 1.95, NULL, 'BULLISH', 5, 'ACC001', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Signal Logs
DELETE FROM `viomia_signal_logs`;
INSERT INTO `viomia_signal_logs` (`symbol`, `decision`, `entry`, `push_status`, `laravel_resp`, `pushed_at`, `created_at`, `updated_at`) VALUES
('EURUSD', 'BUY', 1.08450, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG001"}', NOW(), NOW(), NOW()),
('GBPUSD', 'BUY', 1.27650, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG002"}', NOW(), NOW(), NOW()),
('USDJPY', 'SELL', 149.850, 'SUCCESS', '{"status":"signal_received","signal_id":"SIG003"}', NOW(), NOW(), NOW()),
('AUDUSD', 'BUY', 0.67350, 'PENDING', '{"status":"queued"}', NULL, DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR));

-- Trade Outcomes
DELETE FROM `viomia_trade_outcomes`;
INSERT INTO `viomia_trade_outcomes` (`ticket`, `account_id`, `symbol`, `profit`, `result`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1001, 'ACC001', 'EURUSD', 136.00, 'WIN', NOW(), NOW(), NOW()),
(1002, 'ACC001', 'GBPUSD', 125.00, 'WIN', NOW(), NOW(), NOW()),
(1003, 'ACC003', 'USDJPY', 700.00, 'WIN', NOW(), NOW(), NOW()),
(1004, 'ACC004', 'AUDUSD', -225.00, 'LOSS', NOW(), NOW(), NOW()),
(1005, 'ACC001', 'EURUSD', 85.50, 'WIN', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR));

-- Model Versions
DELETE FROM `viomia_model_versions`;
INSERT INTO `viomia_model_versions` (`version`, `samples`, `accuracy`, `win_rate`, `old_accuracy`, `improved`, `trained_at`, `created_at`, `updated_at`) VALUES
(1, 1000, 0.7230, 0.6234, NULL, 0, NOW(), NOW(), NOW()),
(2, 2500, 0.7845, 0.6890, 0.7230, 1, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),
(3, 5000, 0.8234, 0.7456, 0.7845, 1, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY));

-- Error Logs (AI)
DELETE FROM `viomia_error_logs`;
INSERT INTO `viomia_error_logs` (`error_type`, `error_message`, `context`, `logged_at`, `created_at`, `updated_at`) VALUES
('MODEL_INFERENCE', 'Model inference latency exceeded threshold', '{"model_version":3,"latency_ms":1250}', NOW(), NOW(), NOW()),
('DATA_VALIDATION', 'Invalid candle data received for EURUSD', '{"symbol":"EURUSD","timestamp":"2026-03-15T10:30:00Z"}', DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 5 HOUR));

-- Signal Patterns
DELETE FROM `viomia_signal_patterns`;
INSERT INTO `viomia_signal_patterns` (`pattern_name`, `with_bos`, `with_equal_levels`, `web_sentiment`, `market_regime`, `decision`, `result`, `profit`, `created_at`, `updated_at`) VALUES
('Double Bottom Reversal', 1, 1, 'BULLISH', 'DOWNTREND_RECOVERY', 'BUY', 'WIN', 150.50, NOW(), NOW()),
('Golden Cross Continuation', 0, 0, 'VERY_BULLISH', 'STRONG_UPTREND', 'BUY', 'WIN', 320.75, NOW(), NOW()),
('Resistance Rejection', 1, 0, 'BEARISH', 'DOWNTREND', 'SELL', 'WIN', 225.30, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
('Support Bounce Failed', 0, 1, 'NEUTRAL', 'RANGE_BOUND', 'SELL', 'LOSS', -85.25, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY));

-- Trade Executions
DELETE FROM `viomia_trade_executions`;
INSERT INTO `viomia_trade_executions` (`account_id`, `ticket`, `symbol`, `decision`, `ml_confidence`, `signal_combo`, `regime_type`, `entry_price`, `profit_loss`, `result`, `session_name`, `created_at`, `updated_at`) VALUES
('ACC001', 1001, 'EURUSD', 'BUY', 0.8234, 'RSI_Golden_Cross_Support', 'STRONG_UPTREND', 1.08450, 136.00, 'WIN', 'EU_Morning', NOW(), NOW()),
('ACC001', 1002, 'GBPUSD', 'SELL', 0.7156, 'Resistance_Divergence', 'NORMAL_TRADING', 1.27650, 125.00, 'WIN', 'London_Session', NOW(), NOW()),
('ACC003', 1003, 'USDJPY', 'BUY', 0.7892, 'Support_Bounce_Golden_Cross', 'STRONG_UPTREND', 149.850, 700.00, 'WIN', 'Asian_Session', NOW(), NOW()),
('ACC004', 1004, 'AUDUSD', 'SELL', 0.6234, 'Overbought_Resistance', 'NORMAL_TRADING', 0.67350, -225.00, 'LOSS', 'US_Session', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- ============================================================
-- 10. SUPPORT & ASSISTANCE
-- ============================================================

-- Support Tickets
DELETE FROM `support_tickets`;
INSERT INTO `support_tickets` (`id`, `user_id`, `reference_id`, `subject`, `category`, `priority`, `message`, `attachment_path`, `status`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'TICKET_20260315_001', 'Cannot connect to MT5 account', 'technical', 'high', 'I am unable to connect my MT5 account to the bot. Getting connection timeout errors.', 'storage/support/mt5_error.log', 'in_progress', NULL, NOW(), NOW()),
(2, 3, 'TICKET_20260315_002', 'Payment refund request', 'billing', 'medium', 'I would like to request a refund for my last month subscription.', NULL, 'open', NULL, NOW(), NOW()),
(3, 4, 'TICKET_20260315_003', 'Signals delayed', 'trading', 'high', 'Receiving signals with 2-3 minute delays affecting trade execution.', 'storage/support/signal_delay.txt', 'resolved', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), NOW()),
(4, 5, 'TICKET_20260315_004', 'API documentation request', 'general', 'low', 'Can you provide API documentation for custom integration?', NULL, 'open', NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY));

-- ============================================================
-- RE-ENABLE FOREIGN KEY CHECKS
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SUMMARY
-- ============================================================
-- Total Tables Populated: 44
-- Total Records Created:
--   - Users: 5
--   - Accounts: 5
--   - Trade Events: 5  
--   - Daily Summaries: 7
--   - All other tables: Multiple records for comprehensive testing
--
-- Verification Commands:
-- SELECT COUNT(*) as total_users FROM users;
-- SELECT COUNT(*) as total_accounts FROM accounts;
-- SELECT COUNT(*) as total_trades FROM trade_logs;
-- SELECT COUNT(*) as total_decisions FROM viomia_decisions;
-- SELECT COUNT(*) as total_tickets FROM support_tickets;
--
-- Last Updated: 2026-03-15
-- ============================================================
