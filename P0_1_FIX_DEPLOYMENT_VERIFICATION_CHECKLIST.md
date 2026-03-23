# P0-1 Fix Deployment Verification Checklist
## Trade Outcome Data Persistence - Pre-Launch Verification
## Date: March 23, 2026

---

## PHASE 1: Code Deployment Verification

### ✅ Check 1.1: New Controller Created
```bash
# Verify file exists
ls -la app/Http/Controllers/Bot/TradeOutcomeController.php

# Expected: File should exist and be ~250 lines
# Success: "total 250" or similar file size
```

**Verification Command (in Laravel root)**:
```php
php artisan tinker
>>> class_exists('App\Http\Controllers\Bot\TradeOutcomeController')
```

**Expected Output**: `true`

---

### ✅ Check 1.2: Routes Updated
```bash
# Verify routes file contains TradeOutcomeController
grep -n "TradeOutcomeController" routes/api.php

# Expected output:
# 1: use App\Http\Controllers\Bot\TradeOutcomeController;
# 50: Route::post('/bot/trade/outcome', [TradeOutcomeController::class, 'store']);
# 51: Route::get('/bot/trade/outcome/{ticket}', [TradeOutcomeController::class, 'getByTicket']);
# 52: Route::get('/bot/trade/outcome/stats', [TradeOutcomeController::class, 'getStats']);
# 53: Route::get('/bot/trade/outcome/pattern-analysis', [TradeOutcomeController::class, 'getPatternAnalysis']);
```

**Verification Command (in Laravel root)**:
```php
php artisan route:list | grep outcome
```

**Expected Output** (4 routes):
```
POST      /api/bot/trade/outcome
GET       /api/bot/trade/outcome/{ticket}
GET       /api/bot/trade/outcome/stats
GET       /api/bot/trade/outcome/pattern-analysis
```

---

### ✅ Check 1.3: EA Code Updated
```bash
# Verify AiOutcome.mqh has Laravel URL
grep "LARAVEL_OUTCOME_URL" web/AiOutcome.mqh

# Expected:
# string LARAVEL_OUTCOME_URL = "http://94.72.112.148:8011/api/bot/trade/outcome";
```

**Verification Command (in MQL5 folder)**:
```bash
grep -A5 "SendOutcomeToVIOMIA" web/AiOutcome.mqh | head -20
```

**Expected**: Should see dual sends to both `LARAVEL_OUTCOME_URL` and `VIOMIA_OUTCOME_URL`

---

## PHASE 2: Database Verification

### ✅ Check 2.1: Table Structure
```bash
# Run in Laravel root
php artisan tinker
```

```php
>>> Schema::hasTable('viomia_trade_outcomes')
# Expected: true

>>> Schema::getColumns('viomia_trade_outcomes')
# Expected: array of 25+ columns
```

**Key Columns to Verify**:
- ✅ ticket (nullable: false - UNIQUE KEY)
- ✅ account_id (string)
- ✅ symbol (string)
- ✅ decision (string: BUY/SELL)
- ✅ entry (decimal 10,5)
- ✅ sl (decimal 10,5)
- ✅ tp (decimal 10,5)
- ✅ close_price (decimal 10,5)
- ✅ profit (decimal 12,2)
- ✅ result (string: WIN/LOSS)
- ✅ rsi (decimal 6,2)
- ✅ atr (decimal 8,5)
- ✅ bos, liquidity_sweep, equal_highs, equal_lows, volume_spike (tiny int - boolean flags)
- ✅ dxy_trend, risk_off (tiny int - boolean flags)

---

### ✅ Check 2.2: Model Configuration
```bash
# Run in Laravel root
php artisan tinker
```

```php
>>> $model = new App\Models\ViomiaTradeOutcome()

# Check fillable array
>>> $model->getFillable()
# Expected: 23-field array including all outcome fields

# Check casts
>>> $model->getCasts()
# Expected: decimal casts for prices/indicators, bool casts for patterns
```

---

### ✅ Check 2.3: API Key Configuration
```bash
# Verify API key exists
php artisan tinker
```

```php
>>> App\Models\ApiKey::where('key', 'TEST_API_KEY_123')->first()
# Expected: ApiKey object with is_active = true

# If missing, create it:
>>> App\Models\ApiKey::create(['key' => 'TEST_API_KEY_123', 'is_active' => true])
```

---

## PHASE 3: Application Configuration

### ✅ Check 3.1: Cache Cleared
```bash
# Clear all caches in Laravel root
php artisan config:clear
php artisan route:cache
php artisan view:clear

# Expected: All commands execute without errors
```

---

### ✅ Check 3.2: Environment Variables
```bash
# Check .env file has correct URLs
grep -E "LARAVEL_URL|VITE_API_URL" .env

# Expected:
# LARAVEL_URL=http://94.72.112.148:8011
```

---

## PHASE 4: Functional Testing

### ✅ Check 4.1: Run Test Script
```bash
# In Laravel root
php check_trade_outcome_persistence.php

# Expected output pattern:
# ✅ PASS: TradeOutcomeController found
# ✅ PASS: Route structure verified
# ✅ PASS: API key 'TEST_API_KEY_123' exists
# ✅ PASS: viomia_trade_outcomes table exists
# ✅ PASS: Can query viomia_trade_outcomes table
# ✅ PASS: Model loaded successfully
#
# 🎉 ALL TESTS PASSED!
```

---

### ✅ Check 4.2: Manual API Test
```bash
# Test creating a trade outcome
curl -X POST http://94.72.112.148:8011/api/bot/trade/outcome \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -d '{
    "ticket": 9999999,
    "account_id": "102734606",
    "symbol": "EURUSD",
    "decision": "BUY",
    "entry": 1.0850,
    "sl": 1.0825,
    "tp": 1.0900,
    "close_price": 1.0875,
    "profit": 50.00,
    "close_reason": "TP_HIT",
    "duration_mins": 60,
    "result": "WIN",
    "rsi": 75.5,
    "atr": 0.0045,
    "trend": 1,
    "session": 2,
    "bos": 1,
    "liquidity_sweep": 0,
    "equal_highs": 1,
    "equal_lows": 0,
    "volume_spike": 1,
    "dxy_trend": 0,
    "risk_off": 0
  }'

# Expected Response (201 Created):
# {
#   "success": true,
#   "message": "Trade outcome recorded successfully",
#   "data": {
#     "id": 1,
#     "ticket": 9999999,
#     "symbol": "EURUSD",
#     "profit": 50.00,
#     "result": "WIN"
#   }
# }
```

---

### ✅ Check 4.3: Verify Data Persisted
```bash
php artisan tinker
>>> App\Models\ViomiaTradeOutcome::where('ticket', 9999999)->first()

# Expected: Complete ViomiaTradeOutcome record with all 23 fields
# Should show:
# - ticket: 9999999
# - symbol: EURUSD
# - profit: 50.00
# - result: WIN
# - bos: 1
# - rsi: 75.5
# - etc.
```

---

### ✅ Check 4.4: Test Get by Ticket
```bash
curl -X GET http://94.72.112.148:8011/api/bot/trade/outcome/9999999 \
  -H "X-API-KEY: TEST_API_KEY_123"

# Expected Response (200 OK):
# {
#   "success": true,
#   "message": "Trade outcome retrieved successfully",
#   "data": {
#     "id": 1,
#     "ticket": 9999999,
#     "symbol": "EURUSD",
#     "... all 23 fields ...
#   }
# }
```

---

### ✅ Check 4.5: Test Stats Endpoint
```bash
curl -X GET "http://94.72.112.148:8011/api/bot/trade/outcome/stats?days=1" \
  -H "X-API-KEY: TEST_API_KEY_123"

# Expected Response:
# {
#   "success": true,
#   "message": "Performance statistics retrieved successfully",
#   "data": {
#     "total_trades": 1,
#     "wins": 1,
#     "losses": 0,
#     "win_rate": 100.0,
#     "total_profit": 50.00,
#     "avg_profit_per_trade": 50.00
#   }
# }
```

---

### ✅ Check 4.6: Test Pattern Analysis
```bash
curl -X GET "http://94.72.112.148:8011/api/bot/trade/outcome/pattern-analysis?pattern=bos" \
  -H "X-API-KEY: TEST_API_KEY_123"

# Expected Response:
# {
#   "success": true,
#   "message": "Pattern analysis retrieved successfully",
#   "data": {
#     "pattern": "bos",
#     "total": 1,
#     "wins": 1,
#     "losses": 0,
#     "win_rate": 100.0,
#     "avg_profit": 50.00
#   }
# }
```

---

## PHASE 5: EA Integration Testing

### ✅ Check 5.1: EA Compilation
```bash
# In MetaEditor, open: VIOMIA.mq5
# Press F7 to compile
# Expected: No errors, only warnings about unused includes are OK
```

---

### ✅ Check 5.2: EA Debug Output
```bash
# After recompiling EA and running live/demo:
# Open MT5 → Expert tab
# When trade closes, should see:
# ✅ Laravel outcome saved | TICKET_ID | HTTP 201
# Then:
# VIOMIA Outcome sent to Python AI
```

**If you see this**: ✅ Everything is working!

---

### ✅ Check 5.3: Monitor Trade Closure
```bash
# Watch Laravel logs in real-time:
tail -f storage/logs/laravel.log | grep -i "outcome"

# Expected within 5 seconds of trade close:
# [2026-03-23 10:30:45] local.INFO: Trade outcome received from EA: ticket=9999999, symbol=EURUSD
# [2026-03-23 10:30:45] local.INFO: ✅ Trade outcome saved successfully
```

---

## PHASE 6: Production Readiness

### Final Checklist

- [ ] All Phase 1 checks passed (Code Deployment)
- [ ] All Phase 2 checks passed (Database)
- [ ] All Phase 3 checks passed (Configuration)
- [ ] All Phase 4 checks passed (Functional Testing)
- [ ] All Phase 5 checks passed (EA Integration)

### Go/No-Go Decision

**GO LIVE IF**:
- ✅ All 6 phases have passed checks
- ✅ Test trade was recorded to database
- ✅ Stats and pattern endpoints return data
- ✅ EA logs show "✅ Laravel outcome saved" messages

**DO NOT GO LIVE IF**:
- ❌ Any test failed
- ❌ Controller not found
- ❌ Routes not registered
- ❌ Database queries fail
- ❌ API returns errors on test request

---

## Troubleshooting Reference

### Issue: "Controller not found"
**Solution**: Run `php artisan config:clear && php artisan route:cache`

### Issue: "Routes not found"
**Solution**: Check `routes/api.php` has `TradeOutcomeController` imported

### Issue: "API returns 403 Unauthorized"
**Solution**: Verify API key exists: `php artisan tinker >>> ApiKey::where('key', 'TEST_API_KEY_123')->first()`

### Issue: "API returns 422 Validation Failed"
**Solution**: Check request JSON is valid and matches controller validation rules

### Issue: "Database table not found"
**Solution**: Run `php artisan migrate`

### Issue: "EA not sending outcomes"
**Solution**: Recompile EA with F7 in MetaEditor, ensure LARAVEL_OUTCOME_URL is constants

---

## Success Indicators

You'll know the fix is working when:

1. ✅ **Database Recording**: New rows appear in `viomia_trade_outcomes` after each trade close
2. ✅ **Complete Data**: All 23 columns (prices, technical indicators, patterns) are populated
3. ✅ **EA Logging**: See "✅ Laravel outcome saved" in EA tab logs
4. ✅ **API Endpoints**: All 4 endpoints return data without errors
5. ✅ **Stats Accuracy**: Win rate, profit, and trade count calculations are correct
6. ✅ **Pattern Analysis**: Can query individual pattern performance (BOS, liquidity_sweep, etc)

---

## Current Status: ✅ READY TO DEPLOY

- ✅ Code files created/updated
- ✅ Database schema compatible
- ✅ Routes registered
- ✅ Models configured
- ✅ EA code updated

**Next Step**: Run Phase 4 functional tests above, then monitor first trade closure for successful outcome recording.

---

## Support & Reference

- **Implementation Guide**: [P0_1_TRADE_OUTCOME_FIX_IMPLEMENTATION.md](P0_1_TRADE_OUTCOME_FIX_IMPLEMENTATION.md)
- **Testing Script**: `php check_trade_outcome_persistence.php`
- **API Script**: `bash test_trade_outcome_api.sh`
- **Controller Source**: `app/Http/Controllers/Bot/TradeOutcomeController.php`
- **Routes**: `routes/api.php`
- **EA Module**: `web/AiOutcome.mqh`

---

**Deployed**: March 23, 2026  
**Status**: ✅ Ready for Production Testing  
**Expected Impact**: Complete EA-to-Database data persistence for pattern analysis and AI learning
