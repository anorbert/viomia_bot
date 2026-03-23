# Database Pattern Reach Analysis
## Date: March 23, 2026
## Status: 🔴 CRITICAL - Patterns Cannot Be Reached Due to Eloquent Model Mismatches

---

## EXECUTIVE SUMMARY

Your AI cannot retrieve patterns from the database because **Eloquent models don't match the actual database schema**. The migrations were fixed (data saves correctly), BUT the Laravel models used for querying have outdated `$fillable` arrays.

**Result**: ✅ AI can WRITE to database, but ❌ **CANNOT READ/QUERY** patterns effectively

---

## PROBLEM #1: ViomiaTradeOutcome Model Missing 17 Columns

**File**: `app/Models/ViomiaTradeOutcome.php`

### Current $fillable Array (INCOMPLETE):
```php
protected $fillable = [
    'ticket',
    'account_id',
    'symbol',
    'profit',
    'result',
    'recorded_at',
];  // ❌ Only 6 columns!
```

### Database Actually Has (23 columns):
```
id, ticket, account_id, symbol, decision,
entry, sl, tp, close_price, profit,
close_reason, duration_mins, result,
rsi, atr, trend, session,
bos, liquidity_sweep, equal_highs, equal_lows, volume_spike,
dxy_trend, risk_off,
recorded_at, created_at, updated_at
```

### Missing 17 Critical Columns:
- ❌ `decision` - BUY/SELL decision
- ❌ `entry` - Entry price
- ❌ `sl` - Stop loss
- ❌ `tp` - Take profit
- ❌ `close_price` - Closing price
- ❌ `close_reason` - Why trade closed
- ❌ `duration_mins` - Trade duration
- ❌ `rsi` - RSI at entry (AI pattern indicator)
- ❌ `atr` - ATR at entry (AI pattern indicator)
- ❌ `trend` - Market trend (AI pattern feature)
- ❌ `session` - Trading session (AI pattern feature)
- ❌ `bos` - Break of Structure signal
- ❌ `liquidity_sweep` - Liquidity pattern
- ❌ `equal_highs` - Pattern indicator
- ❌ `equal_lows` - Pattern indicator
- ❌ `volume_spike` - Volume pattern
- ❌ `dxy_trend` - Market context
- ❌ `risk_off` - Risk sentiment

### Impact:
When your code queries patterns with these indicators:
```php
// Example: Trying to query trades by pattern
$trades = ViomiaTradeOutcome::where('bos', true)
    ->where('trend', 1)
    ->where('rsi', '>', 70)
    ->get();

// ❌ FAILS silently - these columns are not in $fillable so they're ignored!
```

---

## PROBLEM #2: ViomiaDecision Model Missing 6 Columns

**File**: `app/Models/ViomiaDecision.php`

### Current $fillable Array:
```php
protected $fillable = [
    'symbol',
    'decision',
    'confidence',
    'score',
    'reasons',
    'entry',
    'stop_loss',
    'take_profit',
    'rr_ratio',
    'web_intel',
    'web_sentiment',
    'web_score_adj',
    'account_id',
    'decided_at',
];  // ❌ Missing 6 technical columns!
```

### Missing 6 Columns (Added in Migration 2026_03_17_120000):
- ❌ `rsi` - Relative Strength Index
- ❌ `atr` - Average True Range
- ❌ `trend` - Market trend indicator
- ❌ `session` - Trading session
- ❌ `dxy_trend` - US Dollar Index trend
- ❌ `risk_off` - Risk-off sentiment indicator

### Impact:
Your AI is saving these values (payload_logger.py inserts them), but queries cannot retrieve them:
```php
// AI stored these, but can't retrieve them:
$decision = ViomiaDecision::where('rsi', '>', 70)->first();  // ❌ IGNORED

// Only gets: symbol, decision, confidence, score, reasons, entry, stop_loss, take_profit, rr_ratio, web_intel, web_sentiment, web_score_adj, account_id, decided_at
// Missing: rsi, atr, trend, session, dxy_trend, risk_off
```

---

## PROBLEM #3: ViomiaSignalPattern Model Incomplete

**File**: `app/Models/ViomiaSignalPattern.php`

### Current $fillable Array:
```php
protected $fillable = [
    'symbol',
    'pattern_type',
    'confidence',
    'detected_at',
];  // ❌ Wrong field names!
```

### Database Actually Has (Different Column Names!):
```
id, pattern_name, with_bos, with_equal_levels,
web_sentiment, market_regime, decision, result, profit,
created_at, updated_at
```

### Issues:
1. ❌ Field name mismatch: `pattern_type` in model → `pattern_name` in database
2. ❌ Field name mismatch: `detected_at` in model → absent in database (uses `created_at`)
3. ❌ Missing columns:
   - `with_bos` - Break of Structure pattern
   - `with_equal_levels` - Equal highs/lows pattern
   - `web_sentiment` - Sentiment analysis results
   - `market_regime` - Market regime context
   - `decision` - Pattern decision (BUY/SELL)
   - `result` - Pattern success/failure
   - `profit` - Profit from pattern trades

### Impact:
```php
// Your code tries to query patterns by name:
$pattern = ViomiaSignalPattern::where('pattern_name', 'SMC')
    ->where('with_bos', true)
    ->get();

// ❌ FAILS - 'pattern_type' field exists but 'pattern_name' doesn't in fillable
// ❌ FAILS - 'with_bos' not in fillable array at all
```

---

## WHY PATTERNS AREN'T "REACHING"

**Chain of Failure**:

1. **AI Writes Data** ✅ (Python → Database succeeds, migrations are correct)
2. **Data Saves** ✅ (All 23 columns in trade_outcomes table)
3. **Code Queries Patterns** ❌ (Laravel can't retrieve because models don't match schema)
4. **Pattern Analysis Fails** ❌ (No pattern data available for AI learning)
5. **AI Doesn't Improve** ❌ (Can't learn which patterns are successful)

**Example Flow**:
```
Trade closes → AI saves outcome with bos=1, trend=1, rsi=75, profit=50
  ✅ Data in database: viomia_trade_outcomes (23 columns)
  
Laravel backend queries: "Give me profitable patterns with BOS"
  ❌ Query fails: ViomiaTradeOutcome model has no 'bos' in $fillable
  ❌ Returns empty results
  
Pattern learning fails → AI can't improve
```

---

## DATABASE SCHEMA vs MODEL MISMATCH TABLE

| Table | DB Columns | Model Fillable | Match? | Issue |
|-------|-----------|----------------|--------|-------|
| viomia_trade_outcomes | 23 | 6 | ❌ NO | 17 columns missing from model |
| viomia_decisions | 19 | 14 | ❌ NO | 6 technical columns missing |
| viomia_signal_patterns | 9 | 4 | ❌ NO | Wrong field names + 5 missing |
| viomia_signal_logs | 10 | 5 | ❌ NO | 5 columns missing (after migration) |

---

## HOW TO FIX

You need to update all Eloquent model `$fillable` arrays to match the database schema.

### Fix 1: ViomiaTradeOutcome.php
**Add all 23 columns to $fillable**

### Fix 2: ViomiaDecision.php
**Add 6 missing technical columns**

### Fix 3: ViomiaSignalPattern.php
**Fix field names and add missing columns**

### Fix 4: ViomiaSignalLog.php
**Add 5 columns from migration**

---

## ROOT CAUSE

**When you fixed the database migrations**, you correctly added:
- ✅ Migration migrations created (2026_03_17_120000, 2026_03_17_120100, etc.)
- ✅ Database columns added
- ✅ Data saves successfully

**But you missed**:
- ❌ Updating Eloquent model `$fillable` arrays
- ❌ Updating model `$casts` for proper type casting
- ❌ Creating database relationships for pattern learning

Eloquent models control what columns Laravel can work with. If a column isn't in `$fillable`, it's treated as "protected" and ignored in queries.

---

## NEXT STEPS

1. ✅ Fix all 4 Eloquent models to match database schema
2. ✅ Add proper type casting in models (`$casts` array)
3. ✅ Test pattern queries work
4. ✅ Verify AI can learn from stored patterns
5. ✅ Rebuild pattern analysis cache

**This explains why your AI is not reaching patterns — the data is in the database, but Laravel can't retrieve it to analyze patterns!**
