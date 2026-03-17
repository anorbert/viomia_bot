# Trading Bot Testing & Setup Complete Guide

## 📋 Overview

The VIOMIA trading bot system consists of:
- **Signal Generation**: Creating trade signals with specific entry/exit points
- **Signal Distribution**: Distributing signals to all active trading accounts  
- **Risk Management**: Evaluating signals using Risk/Reward ratio (must be ≥ 2.0)
- **Trade Execution**: Executing accepted trades across multiple accounts

## 🚀 Quick Start (5 minutes)

### 1. **Verify System Health**
```bash
php health_check.php
```
Expected output: ✓ SYSTEM HEALTHY (or specific issues if any)

### 2. **Create Test Data**
```bash
php create_quality_signals.php
```
Creates 5 high-quality signals distributed to all 5 active accounts.

### 3. **View Results**
```bash
php trading_summary.php
```
Shows signal quality, distribution, and account assignments.

## 📊 Database Schema

### Accounts Table
- **Purpose**: Trading accounts that will receive and execute signals
- **Key Fields**: `login`, `active`, `user_id`
- **Current State**: 5 active accounts ready to trade

### Signals Table
- **Purpose**: Individual trading signals with price targets
- **Key Fields**: `ticket`, `symbol`, `direction`, `entry`, `sl`, `tp`
- **Validation**: Both ticket and symbol required, cannot be empty

### SignalAccount Table
- **Purpose**: Junction table linking signals to accounts
- **Key Fields**: `signal_id`, `account_id`, `status`, `ticket`
- **Status Values**: `pending`, `executed`, `rejected`
- **Distribution**: Each signal automatically sent to ALL active accounts

### TradeLog Table
- **Purpose**: Log of trades placed on each account
- **Key Fields**: `account_id`, `ticket`, `symbol`, `type`, `lots`, `open_price`, `close_price`, `profit`
- **Purpose**: Created when signal is received

### TradeEvent Table
- **Purpose**: Record actual trade executions with timestamps
- **Key Fields**: `ticket`, `account_id`, `direction`, `entry_price`, `lot_size`, `opened_at`
- **Purpose**: Created when trade is actually executed

## 📝 Test Scripts Reference

### `send_signals.php`
**Purpose**: Create basic test signals and distribute to accounts

**What it does**:
1. Clears previous test data
2. Creates 3 example signals (EURUSD, GBPUSD, USDJPY)
3. Distributes each to all 5 active accounts
4. Shows distribution stats

**Output**: 3 signals × 5 accounts = 15 SignalAccount records

```bash
php send_signals.php
```

---

### `check_trades.php`
**Purpose**: Verify complete database state and relationships

**Shows**:
- All accounts and their active status
- All signals with tickets and pricing
- All signal-account assignments
- All trade logs created
- Trade events (if any)
- Diagnostic warnings

**Output Format**:
```
═══ DATABASE STATE ═══
ACCOUNTS: [count]
SIGNALS: [count]  
SIGNAL_ACCOUNTS: [count]
TRADE_LOGS: [count]
TRADE_EVENTS: [count]
═══ KEY ISSUES FOUND ═══
```

```bash
php check_trades.php
```

---

### `check_trading_bot.php`
**Purpose**: Simulate trading bot decision logic

**Bot Logic**:
1. For each pending signal assignment:
   - Calculate Risk/Reward ratio
   - If RR ≥ 2.0 → **ACCEPT** (mark as executed)
   - If RR < 2.0 → **REJECT** (mark as rejected)
   - If accepted: Create TradeEvent record

**Risk/Reward Calculation**:

For **BUY** signals:
```
Risk = Entry - StopLoss
Reward = TakeProfit - Entry  
RR = Reward / Risk
```

For **SELL** signals:
```
Risk = StopLoss - Entry
Reward = Entry - TakeProfit
RR = Reward / Risk
```

**Output**: Shows per-account analysis with acceptance/rejection reasons

```bash
php check_trading_bot.php
```

---

### `create_quality_signals.php`
**Purpose**: Create signals designed to pass the RR filter

**Signals Created** (all with RR ≥ 2.0):
- GOOD_EURUSD_001: Buy @ 1.0845, RR = 1:2.00
- GOOD_GBPUSD_001: Sell @ 1.2765, RR = 1:2.00  
- GOOD_USDJPY_001: Buy @ 149.85, RR = 1:2.00
- GOOD_AUDUSD_001: Buy @ 0.6735, RR = 1:2.00
- GOOD_NZDUSD_001: Sell @ 0.6125, RR = 1:2.51

**Output**: Distribution stats + bot analysis

```bash
php create_quality_signals.php
```

---

### `health_check.php`
**Purpose**: 8-point comprehensive system diagnostic

**Checks**:
1. ✓ Database connectivity
2. ✓ Table existence and integrity
3. ✓ User setup
4. ✓ Account configuration
5. ✓ Signal distribution completeness
6. ✓ Trade log creation
7. ✓ Signal quality (RR analysis)
8. ✓ API endpoint configuration

**Output Format**:
```
[1/8] Database Connectivity...
[2/8] Table Integrity...
[3/8] User Setup...
...
═══ SYSTEM HEALTH SUMMARY ═══
Critical Issues: ✓ NONE
Warnings: ⚠️ 1 FOUND
```

```bash
php health_check.php
```

---

### `trading_summary.php`
**Purpose**: High-level trading results summary

**Shows**:
- Signal pipeline stats
- Signal status breakdown
- Execution results
- Signal quality analysis
- Account performance per account

```bash
php trading_summary.php
```

## 🔄 Typical Workflow

```
1. Setup Phase
   └─ Verify system health
   └─ Confirm accounts exist and are active
   └─ Check database connectivity

2. Testing Phase
   └─ Create test signals
   └─ Verify signal distribution
   └─ Check database state

3. Analysis Phase
   └─ Run trading bot analyzer
   └─ Review bot decisions
   └─ Check account performance

4. Monitoring Phase
   └─ Health check summary
   └─ Trading results review
   └─ Performance metrics
```

## 📈 Quality Standards

### Signal Quality Requirements
- **Risk/Reward Ratio**: ≥ 2.0 (required for acceptance)
- **Stop Loss**: Must be below entry (buy) or above entry (sell)
- **Take Profit**: Must be above entry (buy) or below entry (sell)
- **Ticket**: Must be unique and non-empty
- **Symbol**: Must be non-empty

### Distribution Assurance
- Signals automatically distributed to **ALL active accounts**
- Duplicate signals prevented by ticket validation
- Foreign key integrity maintained
- Atomic transactions ensure database consistency

## 🐛 Troubleshooting

### Issue: Database Errors
**Solution**: Run `health_check.php` to identify specific problems

### Issue: No Signals Created
**Solutions**:
- Check if active accounts exist: `php trading_summary.php`
- Verify database connectivity: `php health_check.php`
- Create test accounts via admin panel

### Issue: No SignalAccount Records
**Solutions**:
- Check if accounts are "active" (active = 1)
- Verify SignalController.store() logic
- Review database foreign key constraints

### Issue: Low RR Ratios
**Solutions**:
- Adjust TP/SL spreads in signal creation
- Target minimum RR = 2.0
- Use `create_quality_signals.php` as example

## 🔧 API Endpoints (Reference)

**Create Signal**
```
POST /api/bot/signal
Content-Type: application/json

{
  "account": 105338607,
  "ticket": "SIGNAL_123",
  "symbol": "EURUSD",
  "direction": "buy",
  "entry": 1.0845,
  "sl": 1.0800,
  "tp": 1.0925,
  "timeframe": "H1",
  "lots": 1.0
}
```

**Get Active Signal**
```
GET /api/bot/signal
```

**Log Trade**
```
POST /api/bot/trade/log
```

**Mark Trade Opened**
```
POST /api/bot/trade/opened
```

## 📊 Performance Metrics

After running `php trading_summary.php`, you'll see:
- **Signal Quality**: Percentage of signals with RR ≥ 2.0
- **Distribution Rate**: (Total Assigned / Expected) × 100
- **Acceptance Rate**: (Executed / Assigned) × 100 per account
- **Safety Margin**: All trades must have RR ≥ 2.0

## 🎯 Next Steps

1. **For Production Use**:
   - Replace test data with real signals
   - Configure API authentication
   - Monitor trade execution in real-time
   - Set up alerting for rejections

2. **For Development**:
   - Modify signal creation parameters
   - Adjust RR threshold if needed
   - Add custom validation rules
   - Implement additional risk checks

3. **For Debugging**:
   - Run `health_check.php` first
   - Use `check_trades.php` to verify data
   - Review log files for errors
   - Check database directly as backup

## 📚 Related Documentation

- `trading_bot_testing_guide.md` - Detailed testing guide
- `payment_security_implementation.md` - Payment security details
- `user_settings_implementation.md` - User configuration

---

**Last Updated**: March 20, 2026
**Status**: ✓ All scripts tested and verified
