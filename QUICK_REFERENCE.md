# Trading Bot - Quick Reference

## 🚀 Most Common Commands

### Check System Health (Start Here)
```bash
php health_check.php
```
**Output**: ✓ SYSTEM HEALTHY (or issues to fix)

### Create Test Data (Production-Ready)
```bash
php create_quality_signals.php
```
**Output**: 5 signals × 5 accounts = 25 assignments

### View Current State
```bash
php check_trades.php
```
**Output**: Complete database snapshot

### Analyze Trading Bot Decisions
```bash
php check_trading_bot.php
```
**Output**: Bot logic analysis (acceptance/rejection)

### Get Summary Report
```bash
php trading_summary.php
```
**Output**: High-level stats and performance

---

## 📊 Signal Quality Requirements

All signals MUST meet:
- ✓ Risk/Reward Ratio ≥ 2.0 (mandatory)
- ✓ Valid stop loss (below entry for buy, above for sell)
- ✓ Valid take profit (above entry for buy, below for sell)  
- ✓ Unique ticket number
- ✓ Valid symbol

## 🎯 Understanding RR Calculation

**For BUY signals:**
```
Risk = Entry - StopLoss
Reward = TakeProfit - Entry
RR = Reward / Risk

Example: Entry 1.0845, SL 1.0800, TP 1.0925
  Risk = 1.0845 - 1.0800 = 45 pips
  Reward = 1.0925 - 1.0845 = 80 pips
  RR = 80/45 = 1:1.78 ✗ REJECTED (< 2.0)
```

**For SELL signals:**
```
Risk = StopLoss - Entry  
Reward = Entry - TakeProfit
RR = Reward / Risk

Example: Entry 1.2765, SL 1.2815, TP 1.2665
  Risk = 1.2815 - 1.2765 = 50 pips
  Reward = 1.2765 - 1.2665 = 100 pips
  RR = 100/50 = 1:2.00 ✓ ACCEPTED (≥ 2.0)
```

## 🔍 Key Metrics

| Metric | Expected | Current |
|--------|----------|---------|
| Active Accounts | ≥ 1 | 5 |
| Created Signals | - | 5 |
| Distribution Rate | 100% | 100% |
| Acceptance Rate | 80%+ | Depends on RR |
| Database Tables | 6 | ✓ All |

## 🚨 Troubleshooting

**Problem: No accounts**
→ Create accounts in admin panel
→ Verify "active" field is checked

**Problem: Low signal quality**  
→ Increase TP spreads (for buy signals)
→ Decrease TP spreads (for sell signals)
→ Target RR ≥ 2.0

**Problem: Signal not distributed**
→ Run `health_check.php`
→ Verify accounts are "active" = 1
→ Check foreign key constraints

**Problem: Database errors**
→ Verify MySQL connection
→ Check table existence
→ Review error logs

## 📚 Documentation Files

- **TRADING_BOT_SETUP_GUIDE.md** - Complete setup guide
- **trading_bot_testing_guide.md** - Testing details
- **health_check.php** - Run for diagnostics

## 🔗 API Endpoints

- `POST /api/bot/signal` - Create signal
- `GET /api/bot/signal` - Get active signal
- `POST /api/bot/trade/log` - Log trade
- `POST /api/bot/trade/opened` - Mark opened

## 💾 Database Tables

| Table | Purpose | Key Fields |
|-------|---------|-----------|
| `accounts` | Trading accounts | login, active |
| `signals` | Trade signals | ticket, symbol, entry, sl, tp |
| `signal_accounts` | Assignments | signal_id, account_id, status |
| `trade_logs` | Trade records | ticket, symbol, lots, profit |
| `trade_events` | Executions | ticket, entry_price, opened_at |
| `users` | User accounts | email, name, role_id |

## ⚡ Execution Flow

```
1. Signal Created
   └─ Validated (RR, symbol, ticket)
   └─ Saved to database
   
2. Signal Distributed  
   └─ SignalAccount records created
   └─ One per active account
   
3. Bot Analysis (per account)
   └─ Calculate RR ratio
   └─ If RR ≥ 2.0: ACCEPT
   └─ If RR < 2.0: REJECT
   
4. Execution (if accepted)
   └─ Create TradeEvent
   └─ Update SignalAccount status
   └─ Track in TradeLog
```

---

**Last Updated**: March 20, 2026  
**Version**: 1.0  
**Status**: ✓ Production Ready
