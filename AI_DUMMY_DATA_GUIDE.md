# AI Analytics Dummy Data Guide

## Overview
This guide explains the dummy data included for testing the AI Analytics system. The data represents realistic trading scenarios across 4 currency pairs.

## Quick Start

### Import the Data
```bash
mysql -u root -p viomia_bot < AI_DUMMY_DATA.sql
```

Or run the SQL directly in your database client (PHPMyAdmin, MySQL Workbench, etc.)

---

## Data Structure

### 1. CANDLE LOGS (Market Data)
**Table**: `viomia_candle_logs`

Represents OHLC (Open, High, Low, Close) candle data with technical indicators.

#### Sample Records:
```
Symbol: EURUSD
- Price: 1.08450
- RSI: 65.23 (Slightly overbought)
- ATR: 0.00145 (Average True Range - Volatility)
- Trend: 1 (Uptrend, 0=Downtrend)
- Support: 1.08200
- Resistance: 1.08650
```

#### What to verify:
- ✅ Market data displays with proper formatting
- ✅ RSI values color-coded (Red >70, Green <30)
- ✅ Trend indicators show correct direction
- ✅ Support/Resistance levels visible
- ✅ JSON candle data properly stored

---

### 2. AI DECISIONS (Trading Decisions)
**Table**: `viomia_decisions`

Contains AI algorithm decisions before execution.

#### Sample Decision:
```
Symbol: EURUSD
- Decision: BUY
- Confidence: 0.8234 (82.34%)
- Score: 85/100
- Reasons: "Strong uptrend, RSI oversold recovery, Support bounce"
- Entry: 1.08450
- Stop Loss: 1.08200
- Take Profit: 1.08750
- R:R Ratio: 2.15 (For every 1 unit risk, potential 2.15 gain)
```

#### Decision Reasons Included:
- RSI oversold recovery
- Golden cross
- Bearish divergence
- Support bounce
- Trend continuation
- Double bottom patterns

#### What to verify:
- ✅ Confidence percentages display correctly
- ✅ R:R ratios calculated properly
- ✅ Entry/Stop/TP levels visible
- ✅ Web sentiment shows (BULLISH/BEARISH)
- ✅ Score translations are accurate

---

### 3. SIGNAL LOGS (Signals Sent)
**Table**: `viomia_signal_logs`

Records when signals are pushed to trading system.

#### Sample Signal:
```
Symbol: EURUSD
- Decision: BUY
- Entry: 1.08450
- Push Status: SUCCESS
- Response: {"status":"signal_received","signal_id":"SIG001"}
- Pushed At: 2026-03-15 current time
```

#### Push Status Values:
- **SUCCESS** - Signal successfully pushed to broker/API
- **FAILED** - Signal failed to push (network/API error)
- **PENDING** - Signal queued, awaiting push

#### What to verify:
- ✅ Signal history displays in correct order
- ✅ Status badges color correctly
- ✅ Push timestamps are accurate
- ✅ Signal IDs trackable
- ✅ API responses logged properly

---

### 4. TRADE EXECUTIONS
**Table**: `viomia_trade_executions`

Records actual executed trades.

#### Sample Execution:
```
Ticket: 1001
- Symbol: EURUSD
- Decision: BUY
- Account: ACC001
- Entry Price: 1.08450
- ML Confidence: 0.8234 (82.34%)
- Signal Combo: "RSI_MA_BOUNCEOFF"
- Regime: UPTREND
- Profit/Loss: 125.50
- Result: WIN
- Session: LONDON
```

#### Regime Types:
- **UPTREND** - Market in upward trend
- **DOWNTREND** - Market in downward trend
- **RANGING** - Market moving sideways
- **CONSOLIDATION** - Price consolidating

#### Signal Combos (Examples):
- RSI_MA_BOUNCEOFF - RSI reversal + Moving Average confirmation
- GOLDEN_CROSS_MOMENTUM - crossover + momentum
- RESISTANCE_BEARISH_DIV - Price resistance + divergence
- OVERBOUGHT_RESISTANCE - Overbought condition near resistance
- SUPPORT_BOUNCE_TREND - Support bounce in trend direction

#### What to verify:
- ✅ Total executions count correctly
- ✅ Win/Loss ratio calculated (7 wins, 1 loss in sample)
- ✅ Total profit sums correctly (~1106 pips profit)
- ✅ Profit/Loss column shows signed values
- ✅ Result badges color properly

---

### 5. TRADE OUTCOMES (Results)
**Table**: `viomia_trade_outcomes`

Final results of executed trades.

#### Sample Outcome:
```
Ticket: 1001
- Symbol: EURUSD
- Account: ACC001
- Profit: 125.50
- Result: WIN
- Recorded: 2026-03-15 current time
```

#### Data Matching:
- ⚠️ **IMPORTANT**: Each trade execution ticket (1001-1009) has a corresponding outcome
- Profit values in outcomes match execution P&L values
- Result (WIN/LOSS) matches execution result

#### What to verify:
- ✅ Win rate calculated: 7/8 = 87.5%
- ✅ Total profit: ~1106 (sum of all profits)
- ✅ Average profit per trade: ~138 pips
- ✅ Largest win: 275.80 (GBPUSD)
- ✅ Largest loss: -95.50 (EURUSD)

---

## Symbols Included

### 1. EURUSD (Euro/US Dollar)
- **Characteristics**: Highly liquid, trending well
- **Trades**: 3 executions (2 wins, 1 loss)
- **Total P&L**: +145.25

### 2. GBPUSD (British Pound/US Dollar)
- **Characteristics**: Strong bullish bias in sample
- **Trades**: 3 executions (3 wins)
- **Total P&L**: +642.60

### 3. USDJPY (US Dollar/Japanese Yen)
- **Characteristics**: Risk-on sentiment
- **Trades**: 2 executions (2 wins)
- **Total P&L**: +243.70

### 4. AUDUSD (Australian Dollar/US Dollar)
- **Characteristics**: Supporting commodity trade
- **Trades**: 1 execution (1 win)
- **Total P&L**: +189.75

### Overall Statistics:
- **Total Trades**: 8
- **Win Rate**: 87.5% (7/8)
- **Total Profit**: ~1106 pips
- **Avg Profit**: 138.25 pips/trade
- **Best Trade**: GBPUSD #1003 (+275.80)
- **Worst Trade**: EURUSD #1008 (-95.50)

---

## Testing Scenarios

### Scenario 1: Market Data View
1. Navigate to: Admin → AI Analytics → Market Data
2. **Verify**:
   - All 10 candle records display
   - Filtering by symbol works
   - RSI color coding (>70=red, <30=green)
   - Trend displays correctly

### Scenario 2: AI Decisions View
1. Navigate to: Admin → AI Analytics → AI Decisions
2. **Verify**:
   - 5 decisions visible
   - Confidence shown as percentages (82.34%)
   - R:R ratios calculated (2.15:1, etc)
   - BUY/SELL badges color correctly
   - Date filtering works

### Scenario 3: Signals View
1. Navigate to: Admin → AI Analytics → Signals Sent
2. **Verify**:
   - 7 signal records display
   - All show SUCCESS status
   - Signal IDs trackable
   - Chronological order correct
   - Symbol filtering works

### Scenario 4: Trade Executions
1. Navigate to: Admin → AI Analytics → Trade Executions
2. **Verify Statistics Cards**:
   - Total Executions: 8
   - Wins: 7
   - Losses: 1
   - Total Profit: 1106.30
3. **Verify Table**:
   - All 8 trades with correct data
   - Profit_loss shows decimals (125.50, etc)
   - Confidence shown as % (82.34%)
   - WIN badges green, LOSS badges red

### Scenario 5: Trade Outcomes
1. Navigate to: Admin → AI Analytics → Trade Outcomes
2. **Verify Statistics**:
   - Total Trades: 8
   - Wins: 7
   - Losses: 1
   - Win Rate: 87.50%
3. **Verify Table**:
   - Profit/Loss column shows signed values
   - Positive (green) and negative (red) colors work
   - Result badges correct

### Scenario 6: Performance Analytics
1. Navigate to: Admin → AI Analytics → AI Performance
2. **Verify**:
   - Key metrics cards display
   - Win rate: 87.5%
   - Total profit: 1106.30
   - Charts render (if Chart.js included)
   - Symbol performance table shows rankings:
     - GBPUSD: Best (642.60 profit)
     - USDJPY: Second (243.70)
     - EURUSD: Third (145.25)
     - AUDUSD: Fourth (189.75)

---

## Adding More Data

### To add more trades:
```sql
INSERT INTO `viomia_trade_executions` VALUES (
  NULL,              -- id (auto)
  'ACC001',          -- account_id
  1010,              -- ticket (unique)
  'EURUSD',          -- symbol
  'BUY',             -- decision
  0.8234,            -- ml_confidence
  'RSI_MA_BOUNCEOFF',-- signal_combo
  'UPTREND',         -- regime_type
  1.08450,           -- entry_price
  125.50,            -- profit_loss
  'WIN',             -- result
  'LONDON',          -- session_name
  NOW(),             -- created_at
  NOW()              -- updated_at
);
```

### To add historical data:
Replace `NOW()` with past dates:
```sql
DATE_SUB(NOW(), INTERVAL 30 DAY)  -- 30 days ago
DATE_SUB(NOW(), INTERVAL 7 DAY)   -- 1 week ago
DATE_SUB(NOW(), INTERVAL 1 DAY)   -- Yesterday
```

---

## Payment Data Example

If you need more historical data, here's a template:

```sql
-- Last 7 days of data
INSERT INTO viomia_trade_outcomes (ticket, account_id, symbol, profit, result, recorded_at, created_at, updated_at)
VALUES
(2001, 'ACC001', 'EURUSD', 156.75, 'WIN', DATE_SUB(NOW(), INTERVAL 6 DAY), NOW(), NOW()),
(2002, 'ACC001', 'GBPUSD', -112.30, 'LOSS', DATE_SUB(NOW(), INTERVAL 6 DAY), NOW(), NOW()),
(2003, 'ACC001', 'USDJPY', 234.50, 'WIN', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW(), NOW()),
(2004, 'ACC001', 'EURUSD', 189.20, 'WIN', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW(), NOW()),
(2005, 'ACC001', 'AUDUSD', -78.45, 'LOSS', DATE_SUB(NOW(), INTERVAL 4 DAY), NOW(), NOW());
```

---

## Troubleshooting

### If no data appears:
1. Check if data inserted correctly: `SELECT COUNT(*) FROM viomia_trade_executions;`
2. Verify account_id matches: `ACC001`
3. Check date filters aren't excluding data
4. Clear browser cache

### If statistics are wrong:
1. Verify SQL executed without errors
2. Check for duplicate key errors (unique violations)
3. Run: `SELECT * FROM viomia_trade_executions;` to inspect

### If charts don't display:
1. Verify Chart.js is loaded
2. Check browser console for JS errors
3. Ensure data arrays have matching lengths

---

## Notes
- All timestamps use NOW() to stay current
- Account ID is 'ACC001' for all records
- Ticket numbers (1001-1009) are unique and sequential
- All currency pairs are major FX pairs
- Session names: LONDON, TOKYO, SYDNEY (trading sessions)
- Confidence scores range 0.6-0.9 (realistic ML confidence)

Happy Testing! 🎯
