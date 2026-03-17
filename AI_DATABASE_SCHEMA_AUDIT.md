# AI Database Schema Audit Report
## Date: March 17, 2026

---

## SUMMARY

**Status**: ⚠️ **CRITICAL ISSUES FOUND**

- **Total AI Tables**: 8
- **Tables with Issues**: 5
- **Missing Columns**: 19+
- **Endpoint Mismatches**: 1
- **Risk Level**: 🔴 HIGH - AI cannot save critical trading data

---

## CRITICAL ISSUES

### 🔴 CRITICAL #1: viomia_decisions Table - Missing 6 Columns

**AI Code Inserts** (payload_logger.py line 30):
```python
INSERT INTO viomia_decisions (
    symbol, decision, confidence, score, reasons,
    entry, stop_loss, take_profit, rr_ratio,
    rsi, atr, trend, session,
    dxy_trend, risk_off, account_id,
    web_intel, web_sentiment, web_score_adj
)
```

**Migration Defines** (2026_03_14_175209_create_viomia_decisions_table.php):
```php
symbol, decision, confidence, score, reasons,
entry, stop_loss, take_profit, rr_ratio,
web_intel, web_sentiment, web_score_adj,
account_id
```

**Missing Columns**:
- ❌ `rsi` - AI inserts it, table doesn't have it
- ❌ `atr` - AI inserts it, table doesn't have it
- ❌ `trend` - AI inserts it, table doesn't have it
- ❌ `session` - AI inserts it, table doesn't have it
- ❌ `dxy_trend` - AI inserts it, table doesn't have it
- ❌ `risk_off` - AI inserts it, table doesn't have it

**Impact**: ✅ INSERT will succeed but columns are silently ignored - **DATA LOSS**

**Fix Needed**:
```sql
ALTER TABLE viomia_decisions ADD COLUMN (
    rsi DECIMAL(8,4),
    atr DECIMAL(18,5),
    trend TINYINT,
    session TINYINT,
    dxy_trend INT DEFAULT 0,
    risk_off INT DEFAULT 0
);
```

---

### 🔴 CRITICAL #2: viomia_signal_logs Table - Missing 5 Columns

**AI Code Inserts** (payload_logger.py line 65):
```python
INSERT INTO viomia_signal_logs (
    symbol, decision, entry, stop_loss, take_profit,
    confidence, score, account_id, push_status, laravel_resp
)
```

**Migration Defines** (2026_03_14_175211_create_viomia_signal_logs_table.php):
```php
symbol, decision, entry, push_status, laravel_resp
```

**Missing Columns**:
- ❌ `stop_loss` - AI inserts it
- ❌ `take_profit` - AI inserts it
- ❌ `confidence` - AI inserts it
- ❌ `score` - AI inserts it
- ❌ `account_id` - AI inserts it, needed for multi-account filtering

**Impact**: ✅ INSERT will succeed but critical signal data is lost - **DATA LOSS**

**Fix Needed**:
```sql
ALTER TABLE viomia_signal_logs ADD COLUMN (
    stop_loss DECIMAL(18,5),
    take_profit DECIMAL(18,5),
    confidence DECIMAL(6,4),
    score INT,
    account_id VARCHAR(50)
);
```

---

### 🔴 CRITICAL #3: viomia_trade_outcomes Table - Missing 17 Columns

**AI Code Inserts** (outcome_receiver.py line 17):
```python
INSERT INTO viomia_trade_outcomes (
    ticket, symbol, decision,
    entry, sl, tp, close_price, profit,
    close_reason, duration_mins,
    rsi, atr, trend, session,
    bos, liquidity_sweep, equal_highs,
    equal_lows, volume_spike,
    dxy_trend, risk_off, result, account_id
)
```

**Migration Defines** (2026_03_14_175213_create_viomia_trade_outcomes_table.php):
```php
ticket, account_id, symbol, profit, result
```

**Missing Columns** (17 critical columns):
- ❌ `decision` - BUY/SELL decision made
- ❌ `entry` - Entry price
- ❌ `sl` - Stop loss price
- ❌ `tp` - Take profit price
- ❌ `close_price` - Actual close price
- ❌ `close_reason` - Why trade closed
- ❌ `duration_mins` - How long trade was open
- ❌ `rsi` - RSI value at entry
- ❌ `atr` - ATR value at entry
- ❌ `trend` - Market trend
- ❌ `session` - Trading session
- ❌ `bos` - Break of Structure signal
- ❌ `liquidity_sweep` - Liquidity sweep occurred
- ❌ `equal_highs` - Equal highs pattern
- ❌ `equal_lows` - Equal lows pattern
- ❌ `volume_spike` - Volume spike signal
- ❌ `dxy_trend` - DXY trend indicator
- ❌ `risk_off` - Risk-off sentiment

**Impact**: 🔴 **CATASTROPHIC** - Will fail to insert due to column mismatch!

**Error Will Be**:
```
INSERT statement has more values than columns in table viomia_trade_outcomes
```

**Fix Needed**:
```sql
ALTER TABLE viomia_trade_outcomes ADD COLUMN (
    decision VARCHAR(10),
    entry DECIMAL(18,5),
    sl DECIMAL(18,5),
    tp DECIMAL(18,5),
    close_price DECIMAL(18,5),
    close_reason VARCHAR(100),
    duration_mins INT,
    rsi DECIMAL(8,4),
    atr DECIMAL(18,5),
    trend TINYINT,
    session TINYINT,
    bos BOOLEAN,
    liquidity_sweep BOOLEAN,
    equal_highs BOOLEAN,
    equal_lows BOOLEAN,
    volume_spike BOOLEAN,
    dxy_trend INT,
    risk_off INT
);
```

---

### 🟠 ISSUE #4: viomia_error_logs Table - Missing account_id

**AI Code Inserts** (payload_logger.py line 77):
```python
INSERT INTO viomia_error_logs (
    error_type, error_message, context
)
```

**Migration Defines** (2026_03_14_175217_create_viomia_error_logs_table.php):
```php
error_type, error_message, context
```

**Issue**: 
- ✅ No missing columns
- ⚠️ But should add `account_id` for multi-account filtering

**Current Status**: Works but incomplete for multi-account

**Recommendation**:
```sql
ALTER TABLE viomia_error_logs ADD COLUMN account_id VARCHAR(50);
```

---

### 🟠 ISSUE #5: Signal Push Endpoint Mismatch

**AI Code** (signal_pusher.py line 45):
```python
r = requests.post(
    f"{LARAVEL_API_BASE}/signals",  # ← Posts to /api/bot/signals
    ...
)
```

**Laravel Routes** (routes/api.php):
- ✅ POST `/signal` exists
- ❌ POST `/signals` does NOT exist

**Issue**: AI pushes to wrong endpoint!

**Current Endpoint**: POST `/api/bot/signals` ❌ DOESN'T EXIST  
**Should Be**: POST `/api/bot/signal`  
**Could Be Used For**: Signal broadcast endpoint (not implemented)

**Impact**: Signal push fails silently or returns 404

---

## TABLE-BY-TABLE COMPARISON

| Table | AI Inserts | Migration Columns | Status | Action |
|-------|-----------|------------------|--------|--------|
| viomia_candle_logs | 10 fields | 10 fields | ✅ OK | None |
| viomia_decisions | 19 fields | 13 fields | ❌ 6 MISSING | ADD COLUMNS |
| viomia_signal_logs | 10 fields | 5 fields | ❌ 5 MISSING | ADD COLUMNS |
| viomia_trade_outcomes | 23 fields | 5 fields | ❌ 18 MISSING | ADD COLUMNS |
| viomia_error_logs | 3 fields | 3 fields | ⚠️ INCOMPLETE | Add account_id |
| viomia_model_versions | N/A | 8 fields | ✅ OK | None |
| viomia_signal_patterns | N/A | 9 fields | ✅ OK | None |
| viomia_trade_executions | N/A | 11 fields | ✅ OK | None |

---

## DETAILED FIELD MAPPING

### viomia_candle_logs ✅ MATCH

| Field | AI Sends | Migration | Status |
|-------|----------|-----------|--------|
| symbol | ✓ | ✓ | ✅ |
| price | ✓ | ✓ | ✅ |
| rsi | ✓ | ✓ | ✅ |
| atr | ✓ | ✓ | ✅ |
| trend | ✓ | ✓ | ✅ |
| resistance | ✓ | ✓ | ✅ |
| support | ✓ | ✓ | ✅ |
| session | ✓ | ✓ | ✅ |
| account_id | ✓ | ✓ | ✅ |
| candles_json | ✓ | ✓ | ✅ |

---

### viomia_decisions ⚠️ 6 MISSING COLUMNS

| Field | AI Sends | Migration | Status |
|-------|----------|-----------|--------|
| symbol | ✓ | ✓ | ✅ |
| decision | ✓ | ✓ | ✅ |
| confidence | ✓ | ✓ | ✅ |
| score | ✓ | ✓ | ✅ |
| reasons | ✓ | ✓ | ✅ |
| entry | ✓ | ✓ | ✅ |
| stop_loss | ✓ | ✓ | ✅ |
| take_profit | ✓ | ✓ | ✅ |
| rr_ratio | ✓ | ✓ | ✅ |
| rsi | ✓ | ❌ | 🔴 MISSING |
| atr | ✓ | ❌ | 🔴 MISSING |
| trend | ✓ | ❌ | 🔴 MISSING |
| session | ✓ | ❌ | 🔴 MISSING |
| dxy_trend | ✓ | ❌ | 🔴 MISSING |
| risk_off | ✓ | ❌ | 🔴 MISSING |
| account_id | ✓ | ✓ | ✅ |
| web_intel | ✓ | ✓ | ✅ |
| web_sentiment | ✓ | ✓ | ✅ |
| web_score_adj | ✓ | ✓ | ✅ |

---

### viomia_signal_logs ⚠️ 5 MISSING COLUMNS

| Field | AI Sends | Migration | Status |
|-------|----------|-----------|--------|
| symbol | ✓ | ✓ | ✅ |
| decision | ✓ | ✓ | ✅ |
| entry | ✓ | ✓ | ✅ |
| stop_loss | ✓ | ❌ | 🔴 MISSING |
| take_profit | ✓ | ❌ | 🔴 MISSING |
| confidence | ✓ | ❌ | 🔴 MISSING |
| score | ✓ | ❌ | 🔴 MISSING |
| account_id | ✓ | ❌ | 🔴 MISSING |
| push_status | ✓ | ✓ | ✅ |
| laravel_resp | ✓ | ✓ | ✅ |

---

### viomia_trade_outcomes 🔴 18 MISSING COLUMNS

| Field | AI Sends | Migration | Status |
|-------|----------|-----------|--------|
| ticket | ✓ | ✓ | ✅ |
| symbol | ✓ | ✓ | ✅ |
| decision | ✓ | ❌ | 🔴 MISSING |
| entry | ✓ | ❌ | 🔴 MISSING |
| sl | ✓ | ❌ | 🔴 MISSING |
| tp | ✓ | ❌ | 🔴 MISSING |
| close_price | ✓ | ❌ | 🔴 MISSING |
| profit | ✓ | ✓ | ✅ |
| close_reason | ✓ | ❌ | 🔴 MISSING |
| duration_mins | ✓ | ❌ | 🔴 MISSING |
| rsi | ✓ | ❌ | 🔴 MISSING |
| atr | ✓ | ❌ | 🔴 MISSING |
| trend | ✓ | ❌ | 🔴 MISSING |
| session | ✓ | ❌ | 🔴 MISSING |
| bos | ✓ | ❌ | 🔴 MISSING |
| liquidity_sweep | ✓ | ❌ | 🔴 MISSING |
| equal_highs | ✓ | ❌ | 🔴 MISSING |
| equal_lows | ✓ | ❌ | 🔴 MISSING |
| volume_spike | ✓ | ❌ | 🔴 MISSING |
| dxy_trend | ✓ | ❌ | 🔴 MISSING |
| risk_off | ✓ | ❌ | 🔴 MISSING |
| result | ✓ | ✓ | ✅ |
| account_id | ✓ | ✓ | ✅ |

---

## FIXES REQUIRED

### Migration #1: Add Missing Columns to viomia_decisions

**File**: `database/migrations/2026_03_14_175209_CREATE_viomia_decisions_ALTER.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viomia_decisions', function (Blueprint $table) {
            $table->decimal('rsi', 8, 4)->nullable()->after('web_score_adj');
            $table->decimal('atr', 18, 5)->nullable()->after('rsi');
            $table->tinyInteger('trend')->nullable()->after('atr');
            $table->tinyInteger('session')->nullable()->after('trend');
            $table->integer('dxy_trend')->default(0)->after('session');
            $table->integer('risk_off')->default(0)->after('dxy_trend');
        });
    }

    public function down(): void
    {
        Schema::table('viomia_decisions', function (Blueprint $table) {
            $table->dropColumn(['rsi', 'atr', 'trend', 'session', 'dxy_trend', 'risk_off']);
        });
    }
};
```

---

### Migration #2: Add Missing Columns to viomia_signal_logs

**File**: `database/migrations/2026_03_14_175211_CREATE_viomia_signal_logs_ALTER.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viomia_signal_logs', function (Blueprint $table) {
            $table->decimal('stop_loss', 18, 5)->nullable()->after('entry');
            $table->decimal('take_profit', 18, 5)->nullable()->after('stop_loss');
            $table->decimal('confidence', 6, 4)->nullable()->after('take_profit');
            $table->integer('score')->nullable()->after('confidence');
            $table->string('account_id', 50)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('viomia_signal_logs', function (Blueprint $table) {
            $table->dropColumn(['stop_loss', 'take_profit', 'confidence', 'score', 'account_id']);
        });
    }
};
```

---

### Migration #3: Completely Redo viomia_trade_outcomes

**Issue**: Current migration only has 5 columns, but AI needs 23 columns. Migration must be completely replaced.

**File**: `database/migrations/2026_03_14_175213_REDO_viomia_trade_outcomes_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing table
        Schema::dropIfExists('viomia_trade_outcomes');
        
        // Recreate with all required columns
        Schema::create('viomia_trade_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket')->unique()->index();
            $table->string('account_id', 50)->default('0')->index();
            $table->string('symbol', 20)->index();
            $table->string('decision', 10);
            $table->decimal('entry', 18, 5);
            $table->decimal('sl', 18, 5);
            $table->decimal('tp', 18, 5);
            $table->decimal('close_price', 18, 5)->nullable();
            $table->decimal('profit', 12, 4)->index();
            $table->string('close_reason', 100)->nullable();
            $table->integer('duration_mins')->nullable();
            $table->decimal('rsi', 8, 4)->nullable();
            $table->decimal('atr', 18, 5)->nullable();
            $table->tinyInteger('trend')->nullable();
            $table->tinyInteger('session')->nullable();
            $table->boolean('bos')->default(false);
            $table->boolean('liquidity_sweep')->default(false);
            $table->boolean('equal_highs')->default(false);
            $table->boolean('equal_lows')->default(false);
            $table->boolean('volume_spike')->default(false);
            $table->integer('dxy_trend')->default(0);
            $table->integer('risk_off')->default(0);
            $table->string('result', 10)->index(); // WIN/LOSS
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viomia_trade_outcomes');
    }
};
```

---

## EXECUTION CHECKLIST

- [ ] Create migration file for viomia_decisions ALTER
- [ ] Create migration file for viomia_signal_logs ALTER
- [ ] Create migration file for viomia_trade_outcomes REDO
- [ ] Create migration file for viomia_error_logs (add account_id)
- [ ] Fix signal_pusher.py endpoint: /signals → /signal
- [ ] Run `php artisan migrate`
- [ ] Verify all AI inserts work
- [ ] Test AI → Laravel data flow
- [ ] Verify all columns save properly
- [ ] Check no data is lost on insert
- [ ] Monitor error logs for INSERT failures

---

## CONCLUSION

⚠️ **Status: NOT READY FOR PRODUCTION**

### Critical Blockers:
1. 🔴 viomia_trade_outcomes will FAIL with column mismatch error
2. 🔴 viomia_decisions will lose 6 columns of critical data
3. 🔴 viomia_signal_logs will lose 5 columns of critical data
4. 🔴 Signal push uses wrong endpoint

### Action Required:
Create and run the 4 migration files above BEFORE deploying AI to production.

