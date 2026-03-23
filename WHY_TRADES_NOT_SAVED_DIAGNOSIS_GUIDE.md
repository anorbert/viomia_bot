# Why Trades Are Not Being Saved in trade_logs - Diagnosis & Solutions
## Complete Root Cause Analysis & Fix Guide
## Date: March 24, 2026

---

## 🔴 THE CORE PROBLEM

Trade outcomes are **NOT being saved in the `trade_logs` table**. This means:
- ❌ Closed trades don't get recorded
- ❌ Trade history is incomplete
- ❌ Profitability calculations are wrong
- ❌ Performance metrics are inaccurate

---

## 🔍 ROOT CAUSE: Two-Stage Process Broken

The trade_logs system requires **TWO separate API calls**:

### Stage 1: Trade OPENS (Required!)
```
EA → POST /signal
    ↓
SignalController::store()
    ↓
Creates BOTH:
  - signals record (for AI)
  - trade_logs record (for tracking)
```

### Stage 2: Trade CLOSES (Tries to Update)
```
EA → POST /trade/log
    ↓
TradeLogController::store()
    ↓
Tries to UPDATE existing trade_logs record
    ↓
🔴 ERROR 404 if record doesn't exist!
```

---

## 🚨 Why This Fails

### Failure Point: Signal Never Sent
**Most Common Cause** (90% of the time)

The initial signal is **NEVER being sent to /signal endpoint**, so:
1. ❌ No Signal record created
2. ❌ No TradeLog record created
3. ❌ When trade closes, SendClosedTrade() tries to UPDATE non-existent record
4. ❌ Returns 404: "Trade log not found"
5. ❌ Trade is lost

**Evidence**:
- Laravel logs don't show "Signal created" messages
- trade_logs table is empty
- No signal/trade_logs records with today's date

---

## 🔧 HOW TO DIAGNOSE

### Check 1: Are Signals Being Created?

```bash
php artisan tinker
>>> Signal::latest()->first()
# Should show recent signal records

>>> TradeLog::latest()->first()
# Should show recent trade_logs records
```

**If empty**: Signals are NOT being sent from EA

---

### Check 2: Watch Laravel Logs in Real Time

```bash
tail -f storage/logs/laravel.log | grep -E "Signal|trade_logs|Signal created|Trade log"
```

**Expected Recent Entries**:
```
[2026-03-24 10:30:15] local.INFO: Incoming raw request: {"account":102734606,"ticket":"1234567",...}
[2026-03-24 10:30:15] local.INFO: Signal & trade log created successfully
[2026-03-24 10:30:45] local.INFO: Trade log updated
```

**Missing Entries**: Check #3 below

---

### Check 3: Is EA Sending Signal to Laravel?

The EA should call SendSignalToLaravel() in the OrderSend.mqh file. Check:

```bash
# Check the EA code
grep -n "SendSignalToLaravel\|POST.*signal" Trade/OrderSend.mqh
```

**Should see**: A call to SendSignalToLaravel() or similar

**If missing**: That's the problem!

---

### Check 4: Can You Send Test Signal Manually?

```bash
curl -X POST http://94.72.112.148:8011/api/bot/signal \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -d '{
    "account": 102734606,
    "ticket": "9999999",
    "symbol": "EURUSD",
    "direction": "buy",
    "entry": 1.0850,
    "sl": 1.0825,
    "tp": 1.0900,
    "timeframe": "M15",
    "lots": 0.1
  }'

# Expected Response: 201 Created
# {
#   "success": true,
#   "message": "Signal & trade log created successfully",
#   "signal_id": 123,
#   "ticket": "9999999"
# }
```

**200 (Duplicate)**: Signal already exists (good - try different ticket)

**201 (Created)**: ✅ API is working, problem is in EA

**400/422**: Missing or invalid field

**500**: Database error

---

### Check 5: Are Trade Closes Being Sent?

```bash
grep "✅ Trade closed:" storage/logs/laravel.log | tail -5
```

**Expected Output**:
```
[2026-03-24 10:30:45] local.INFO: ✅ Trade closed: Account=102734606 | Ticket=9999999 | Profit=50.00
[2026-03-24 10:30:46] local.INFO: Trade log updated
```

**Missing Despite Trades Closing**: Problem is in Eagle Stage 1 (Signal not sent)

---

## 💡 THE 3 MOST LIKELY CAUSES & FIXES

### Cause #1: Initial Signal Never Sent (MOST LIKELY)

**Symptom**: 
- No Signal records in database
- No TradeLog records
- EA shows trades opening and closing but nothing in database

**Root Cause**:
- EA doesn't call SendSignalToLaravel() when placing trade
- OR SendSignalToLaravel() is failing

**Fix**:
1. Verify OrderSend.mqh has SendSignalToLaravel() call
2. Check EA logs for error messages
3. Verify API key is correct
4. Test manually with curl command above

**Code Check** (in Viomia.mq5 or OrderSend.mqh):
```mql5
// Should have this after placing trade:
SendSignalToLaravel(json);  // ← This MUST be called!

// If not there, add it:
string response = SendSignalToLaravel(json);
if(response != "")
    Print("✅ Signal sent:", response);
else
    Print("❌ Signal send failed - check logs");
```

---

### Cause #2: Account Lookup Failure

**Symptom**:
- Laravel logs show: "Account not found" errors
- Error response: {"error": "Account not found", "account_login": 102734606}

**Root Cause**:
- Account doesn't exist in `accounts` table
- Account login number is wrong

**Fix**:
```bash
# 1. Check if account exists
php artisan tinker
>>> Account::where('login', 102734606)->first()
# If null, account doesn't exist

# 2. List all accounts
>>> Account::all()
# Should show your account

# 3. If missing, create it:
>>> Account::create(['login' => 102734606, 'name' => 'Trading Account', 'active' => true])
```

**In EA**: Get correct account number
```mql5
ulong accountNumber = (ulong)AccountInfoInteger(ACCOUNT_LOGIN);
Print("My account number:", accountNumber);  // Check this matches DB
```

---

### Cause #3: Missing Required Fields in Payload

**Symptom**:
- Laravel logs show validation errors
- Error: {"errors": {"symbol": ["The symbol field is required"]}}

**Root Cause**:
- JSON payload from EA missing required fields
- Fields are null, empty, or wrong type

**Required Fields for Signal POST**:
```php
'account'   => 'required|numeric',
'ticket'    => 'required|string',
'symbol'    => 'required|string|max:10',
'direction' => 'required|in:buy,sell',
'entry'     => 'required|numeric',
'sl'        => 'required|numeric',
'tp'        => 'required|numeric',
'timeframe' => 'required|string|max:10',
'lots'      => 'required|numeric',
```

**Fix**: 
1. Check EA logs for signal payload being sent
2. Verify JSON is valid (use tool like JSONLint.com)
3. Ensure all fields are populated before sending

---

## 🛠️ COMPLETE FIX CHECKLIST

### Step 1: Verify Signal Flow ✅
```bash
# A. Check EA code sends signal
grep -n "SendSignalToLaravel" Trade/OrderSend.mqh

# B. Test API manually
curl -X POST http://94.72.112.148:8011/api/bot/signal \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -d '{"account": 102734606, "ticket": "TEST123", "symbol": "EURUSD", "direction": "buy", "entry": 1.0850, "sl": 1.0825, "tp": 1.0900, "timeframe": "M15", "lots": 0.1}'

# C. Verify in database
php artisan tinker
>>> Signal::where('ticket', 'TEST123')->first()
>>> TradeLog::where('ticket', 'TEST123')->first()
# Both should exist after signal sent
```

### Step 2: Verify Account Exists ✅
```bash
php artisan tinker
>>> Account::where('login', 102734606)->first()
# Should return Account object, not null

# If not found, create:
>>> Account::create(['login' => 102734606, 'name' => 'Live Account', 'active' => true])
```

### Step 3: Enable EA Debug Logging ✅
```
In MT5 EA input parameters:
- Set DebugMode = true
- Set ENABLE_LOGGING = true
```

Watch logs for:
```
✅ Signal successfully sent
✅ Trade closed: Account=... | Ticket=... | Profit=...
✅ Trade log updated
```

### Step 4: Test Complete Flow ✅
```bash
# Terminal 1: Watch logs
tail -f storage/logs/laravel.log | grep -E "Signal|Trade"

# Terminal 2: In MT5, do ONE test trade
# Open and close a demo trade

# Terminal 3: Check database
php artisan tinker
>>> Signal::latest()->first()
>>> TradeLog::latest()->first()
# Both should show your test trade
```

### Step 5: Monitor First Live Trade ✅
1. Close a live trade in MT5
2. Check logs within 5 seconds
3. Verify trade_logs record was updated
4. Check profit is correct

---

## 📊 Debugging Checklist

Use this table to identify which stage is failing:

| Check | Pass? | Status |
|-------|-------|--------|
| Signal created in DB? | Yes/No | ✅ Stage 1 working / ❌ Stage 1 failed |
| TradeLog created when signal sent? | Yes/No | ✅ SignalController working / ❌ Issue in creation |
| Account lookup working? | Yes/No | ✅ Account configured / ❌ Add account to DB |
| API key valid? |Yes/No | ✅ Auth working / ❌ Check API key |
| Trade closed logged? | Yes/No | ✅ Stage 2 working / ❌ Stage 2 failed |
| TradeLog updated on close? | Yes/No | ✅ All working / ❌ Find error in logs |

---

## 🚨 Common Error Messages & Solutions

### Error: "Signal already exists"
**Meaning**: Signal with that ticket already in database
**Solution**: This is actually OK (status 200). Try closing existing trade first or use different ticket.

---

### Error: "Account not found"
**Meaning**: Account login doesn't exist in `accounts` table
**Solution**: 
```bash
php artisan tinker
>>> Account::create(['login' => 102734606, 'name' => 'Account Name', 'active' => true])
```

---

### Error: "Validation failed"
**Meaning**: Required field missing or wrong type
**Check logs**:
```bash
grep "Validation" storage/logs/laravel.log
# Shows exactly which field is wrong
```

**Fix**: 
- Ensure all required fields are in EA payload
- Check JSON format is valid
- Verify field types match expectations

---

### Error: "Trade log not found"
**Meaning**: SendClosedTrade() trying to update non-existent trade_logs
**Solution**: Make sure Stage 1 completes before trade closes
- Signal MUST be sent when trade opens
- TradeLog record MUST exist before trade can close

---

## 📝 Data Flow Map

```
EA Places Trade
    ↓
SendSignalToLaravel()
    ↓
POST /api/bot/signal
    ↓
SignalController::store()
    ✅ Creates Signal
    ✅ Creates TradeLog (status = "open")
    ↓
✅ TradeLog record exists in database
    ↓
EA trade closes
    ↓
SendClosedTrade()
    ↓
POST /api/bot/trade/log
    ↓
TradeLogController::store()
    ✅ Updates TradeLog (status = "closed", profit, etc)
    ↓
✅ Trade saved with all closing details
```

---

## 🔗 Related Endpoints

| Endpoint | Purpose | When Called | Creates/Updates |
|----------|---------|-------------|-----------------|
| POST /signal | Create signal | When trade placed | Creates Signal + TradeLog |
| POST /trade/opened | Log trade opened | When trade placed | Updates TradeEvent (not trade_logs) |
| POST /trade/log | Log trade closed | When trade closes | Updates TradeLog |
| POST /trade/outcome | Save outcome details | When trade closes | Creates ViomiaTradeOutcome |

**Key**: The trade_logs is created by `/signal`, updated by `/trade/log`

---

## 🆘 Still Not Seeing Trades Saved?

### Comprehensive Debugging Steps

1. **Check Last 20 Logs**:
```bash
tail -20 storage/logs/laravel.log
# Look for any errors related to signals or trades
```

2. **Enable Verbose Logging**:
```bash
# In EA: Set DebugMode = true
# Watch EA tab in MT5
# You'll see: ✅ Signal successfully sent
```

3. **Check Network Connection**:
```bash
curl -I http://94.72.112.148:8011/api/bot/signal
# Should return 200 or 405 (not connection refused)
```

4. **Verify JSON Format Being Sent**:
```bash
# Add this in EA to print payload:
Print("Sending: ", payload);
# Copy the output and validate with https://jsonlint.com/
```

5. **Test with Smallest Possible Trade**:
```bash
# Place 0.01 lot trade
# Close immediately
# Check database
```

6. **Check Account Status**:
```bash
php artisan tinker
>>> Account::find(1)->toArray()
# Check: 'active' => true, 'login' => correct_number
```

---

## ✅ Success Indicators

You'll know it's working when:

1. ✅ **Signal Created**: `Signal::latest()->first()` shows records with today's date
2. ✅ **TradeLog Created**: `TradeLog::latest()->first()` shows records
3. ✅ **Trade Logged on Close**: Logs show "Trade log updated" when trade closes
4. ✅ **Profit Recorded**: `TradeLog::latest()->first()->profit` shows correct value
5. ✅ **Status Updated**: `TradeLog::latest()->first()->status` shows "closed" after trade closes

---

## 📞 Support

If trades still aren't saving after these checks:

1. Share the Laravel logs: `tail -50 storage/logs/laravel.log`
2. Share the EA logs (MT5 Expert tab output)
3. Confirm: Signal exists in database: `Signal::latest()->first()`
4. Confirm: Account exists: `Account::where('login', YOUR_ACCOUNT)->first()`

---

**Last Updated**: March 24, 2026  
**Related**: P0_1_TRADE_OUTCOME_FIX_IMPLEMENTATION.md, P0_1A_ACCOUNT_ID_SECURITY_FIX.md
