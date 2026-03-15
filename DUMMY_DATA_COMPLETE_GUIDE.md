# Complete Dummy Data Guide - All 44 Tables

## Quick Start

### Import All Dummy Data
```bash
# Via MySQL command line
mysql -u root -p viomia_bot < COMPLETE_DUMMY_DATA.sql

# Or copy the entire file content into MySQL Workbench / PHPMyAdmin SQL editor
```

---

## What's Included

### **44 Database Tables with Comprehensive Dummy Data**

#### 1. **Core System Tables** (6 tables)
- ✅ **roles** - 5 roles (Admin, Trader, Premium, Support, Analyst)
- ✅ **users** - 5 users with profile data (Admin, 2 Traders, Premium Member, Analyst)
- ✅ **password_reset_tokens** - System table (auto-managed)
- ✅ **sessions** - System table (auto-managed)
- ✅ **cache** - System table (auto-managed)
- ✅ **cache_locks** - System table (auto-managed)

#### 2. **Banking & Payments** (6 tables)
- ✅ **banks** - 3 payment processors (Momo, Binance, Bank Transfer)
- ✅ **subscription_plans** - 4 subscription tiers (Basic, Professional, Premium, Enterprise)
- ✅ **user_subscriptions** - 4 active subscriptions
- ✅ **payment_transactions** - 4 payment records (3 completed, 1 pending)
- ✅ **weekly_payments** - 4 weekly payment records (3 paid, 1 pending)
- ✅ **payment_audit_logs** - 2 audit trail entries

#### 3. **Trading Accounts & Core Data** (3 tables)
- ✅ **accounts** - 5 trading accounts (MT4/MT5, Real/Demo)
- ✅ **account_snapshots** - 5 account balance snapshots with equity/margin
- ✅ **api_keys** - 3 API keys for integration

#### 4. **Trade Events & Logs** (4 tables)
- ✅ **trade_events** - 5 trade events (open/in-progress)
- ✅ **trade_logs** - 5 trade logs (mix of open/closed/partial)
- ✅ **position_updates** - 3 live position updates
- ✅ **daily_summaries** - 7 daily P&L summaries with statistics

#### 5. **Trading Signals & Execution** (3 tables)
- ✅ **signals** - 5 signals (mix of active/inactive)
- ✅ **signal_accounts** - 5 signal executions across accounts
- ✅ **whatsapp_signals** - 3 WhatsApp signal notifications
- ✅ **ea_whatsapp_excutions** - 3 EA executions from WhatsApp

#### 6. **Technical Analysis & Indicators** (4 tables)
- ✅ **technical_signals** - 3 technical signal records with RSI/ATR/EMA
- ✅ **filter_blocks** - 2 trading filter blocks (news, London)
- ✅ **loss_limit_alerts** - 2 daily loss limit alerts
- ✅ **ea_status_changes** - 3 EA status history records

#### 7. **News & Market Data** (1 table)
- ✅ **news_events** - 4 upcoming economic calendar events

#### 8. **Bot Configuration & Status** (3 tables)
- ✅ **bot_statuses** - 2 bot performance snapshots
- ✅ **bot_settings** - 2 bot configuration sets (enabled/disabled)
- ✅ **ea_bots** - 3 bot definitions (2 active, 1 inactive)

#### 9. **VIOMIA AI Trading System** (8 tables)
- ✅ **viomia_candle_logs** - 5 OHLC candle records with indicators
- ✅ **viomia_decisions** - 4 AI trading decisions with confidence/score
- ✅ **viomia_signal_logs** - 4 signal execution logs
- ✅ **viomia_trade_outcomes** - 5 trade results (4 wins, 1 loss)
- ✅ **viomia_model_versions** - 3 model training versions with accuracy
- ✅ **viomia_error_logs** - 2 AI error records
- ✅ **viomia_signal_patterns** - 4 pattern analysis records
- ✅ **viomia_trade_executions** - 4 ML-based trade executions

#### 10. **Support System** (1 table)
- ✅ **support_tickets** - 4 support tickets (mix of statuses)

#### **Job Queue Tables** (3 tables)
- ✅ **jobs** - System table (auto-managed)
- ✅ **job_batches** - System table (auto-managed)
- ✅ **failed_jobs** - System table (auto-managed)

---

## Sample Data Details

### Users Created
```
1. Admin User (admin@viomia.com) - Administrator access
2. John Trader (john.trader@example.com) - Professional Trader
3. Jane Premium (jane.premium@example.com) - Premium Member
4. Mike Trader (mike@example.com) - Active Trader
5. Sarah Analytics (sarah@example.com) - Data Analyst
```

### Accounts Created
```
ACC001 - User: John Trader, Platform: MT5, Type: Real, Status: Connected ✅
ACC002 - User: John Trader, Platform: MT4, Type: Demo, Status: Connected ✅
ACC003 - User: Jane Premium, Platform: MT5, Type: Real, Status: Connected ✅
ACC004 - User: Mike Trader, Platform: MT5, Type: Real, Status: Connected ✅
ACC005 - User: Sarah Analytics, Platform: MT4, Type: Demo, Status: Connected ✅
```

### Trade Symbols with Data
```
✅ EURUSD - 2 candle logs, BUY decision, 2 trades (1 open, 1 closed)
✅ GBPUSD - 2 candle logs, BUY decision, 1 trade closed with +125 profit
✅ USDJPY - 1 candle log, SELL decision, 1 trade partial closed
✅ AUDUSD - 1 candle log, BUY decision, 1 trade closed with -225 loss
```

### Account Statistics
```
Total Account Balance: ~$126,266.50 USD
Total Account Equity: ~$127,649.00 USD
Open Positions: 3 trades
Closed Positions: 2 trades (Win rate: 100%)
Daily P&L (Today): +$2,686.50 USD
Max Drawdown: -3.45%
```

### Subscription Plans Purchased
```
John Trader - Professional Plan ($99.99/month) ✅ Active
Jane Premium - Premium Plan ($299.99/month) ✅ Active
Mike Trader - Basic Plan ($29.99/month) ✅ Active
Sarah Analytics - Professional Plan ($99.99/month) ✅ Active
```

### Payment Processing
```
Completed: 3 payments ($429.97)
Pending: 1 payment ($29.99)
Total Revenue: $459.96 USD
```

---

## Verification Commands

### Check Total Records
```sql
-- View total users
SELECT COUNT(*) as total_users FROM users;
-- Expected: 5

-- View total accounts
SELECT COUNT(*) as total_accounts FROM accounts;
-- Expected: 5

-- View total trades
SELECT COUNT(*) as total_trades FROM trade_logs;
-- Expected: 5

-- View AI decisions
SELECT COUNT(*) as total_decisions FROM viomia_decisions;
-- Expected: 4

-- View support tickets
SELECT COUNT(*) as total_support_tickets FROM support_tickets;
-- Expected: 4

-- View all records summary
SELECT 
    (SELECT COUNT(*) FROM users) as users,
    (SELECT COUNT(*) FROM accounts) as accounts,
    (SELECT COUNT(*) FROM payment_transactions) as payments,
    (SELECT COUNT(*) FROM trade_logs) as trades,
    (SELECT COUNT(*) FROM viomia_decisions) as ai_decisions,
    (SELECT COUNT(*) FROM support_tickets) as support_tickets;
```

### Verify User Relationships
```sql
-- View users with their subscriptions
SELECT 
    u.name,
    u.email,
    sp.name as plan,
    us.status,
    us.starts_at,
    us.ends_at
FROM users u
LEFT JOIN user_subscriptions us ON u.id = us.user_id
LEFT JOIN subscription_plans sp ON us.subscription_plan_id = sp.id
WHERE u.id > 1;
```

### Verify Account Data
```sql
-- View accounts with balance and equity
SELECT 
    a.id,
    a.login,
    a.platform,
    a.account_type,
    ans.balance,
    ans.equity,
    ans.drawdown
FROM accounts a
LEFT JOIN account_snapshots ans ON a.id = ans.account_id;
```

### Verify Trading Performance
```sql
-- View daily summaries with P&L
SELECT 
    account_id,
    summary_date,
    daily_pl,
    trades_count,
    winning_trades,
    losing_trades,
    win_rate_percent
FROM daily_summaries
ORDER BY summary_date DESC;
```

### Verify AI Decisions
```sql
-- View all decisions with confidence
SELECT 
    symbol,
    decision,
    confidence * 100 as confidence_pct,
    score,
    rr_ratio,
    web_sentiment
FROM viomia_decisions
ORDER BY decided_at DESC;
```

### Verify Trade Executions
```sql
-- View trade outcomes
SELECT 
    ticket,
    symbol,
    profit,
    result,
    recorded_at
FROM viomia_trade_outcomes
ORDER BY recorded_at DESC;
```

---

## Database Statistics

### Size Estimation
- **Total Tables**: 44
- **Total Records**: ~120+ base records
- **Database Size**: ~2-3 MB (after indexing)
- **Foreign Keys**: 31 relationships

### Data Coverage
- ✅ All core system tables populated
- ✅ Complete payment workflow (plans → transactions → payments)
- ✅ Full trading lifecycle (accounts → trades → outcomes)
- ✅ AI system integration (candles → decisions → executions → outcomes)
- ✅ Support system with ticket tracking
- ✅ Technical indicators and trading filters
- ✅ News calendar integration

---

## Important Notes

### Foreign Key Constraints
- All data respects foreign key relationships
- User IDs referenced correctly throughout
- Account IDs properly linked to trades and signals
- Subscription links properly established

### Timestamps
- All `created_at` and `updated_at` timestamps are set to `NOW()`
- Some historical records use `DATE_SUB(NOW(), INTERVAL X DAY/HOUR)` for realistic data
- `resolved_at` fields populated only for resolved tickets

### Password Hashing
- All user passwords use placeholder hash: `$2y$12$dummyhashhere123456789`
- **In production**, always use proper bcrypt hashing: `bcrypt('password')`
- For testing, you can manually update: `UPDATE users SET password = bcrypt('password') WHERE id = X`

### Account Credentials
- Account passwords are dummy values for testing only
- Update with real trading account credentials in production

### Data Relationships
```
users (5) 
├── user_subscriptions (4) 
│   └── subscription_plans (4 plans available)
├── accounts (5 accounts)
│   ├── account_snapshots (5)
│   ├── trade_events (5)
│   ├── trade_logs (5)
│   ├── daily_summaries (7)
│   └── technical_signals (3)
├── payment_transactions (4)
└── support_tickets (4)
```

---

## Clearing Data

If you need to reset and reimport:

```bash
# Drop all data (WARNING: Destructive!)
mysql -u root -p viomia_bot < delete_all_dummy_data.sql

# Then reimport fresh data
mysql -u root -p viomia_bot < COMPLETE_DUMMY_DATA.sql
```

Or directly in MySQL:

```sql
-- Disable checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Delete all dummy data
DELETE FROM users;
DELETE FROM accounts;
-- ... continue for all tables

-- Re-enable checks
SET FOREIGN_KEY_CHECKS = 1;

-- Reimport the SQL file
SOURCE /path/to/COMPLETE_DUMMY_DATA.sql;
```

---

## Last Updated
**Date**: March 15, 2026  
**Version**: 1.0 (Complete Coverage)  
**Status**: ✅ Ready for Production Testing

---

## Support

If you encounter issues:

1. **Foreign Key Errors**: Ensure ALL dependent tables are cleared before reimporting
2. **Constraint Violations**: Check that role_ids, user_ids, and account_ids match expected ranges
3. **Missing Tables**: Ensure all migrations have been run: `php artisan migrate`
4. **Duplicate Entries**: Run the delete statements first to clear existing data

```bash
# Verify migrations are applied
php artisan migrate:status

# Reset and re-run migrations
php artisan migrate:reset
php artisan migrate
```

Then import the dummy data:

```bash
mysql -u root -p viomia_bot < COMPLETE_DUMMY_DATA.sql
```
