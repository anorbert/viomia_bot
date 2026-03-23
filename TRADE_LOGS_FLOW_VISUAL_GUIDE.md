# Trade Logs Data Flow & Troubleshooting Guide
## Visual Guide to Why Trades Aren't Being Saved

---

## 🔄 Complete Data Flow (Correct Path)

```
 MT5 EA            Laravel           Database
 ──────────────────────────────────────────────
 
 Trade Opens
   │
   ├─→ SendSignalToLaravel()
   │    │
   │    └─→ POST /api/bot/signal
   │         │
   │         │ JSON: {account, ticket, symbol, direction, entry, sl, tp, lots}
   │         │
   │         ▼
   │    SignalController::store()
   │         │
   │         ├─→ Create Signal record
   │         │
   │         └─→ Create TradeLog record ✅ (CRITICAL!)
   │              │
   │              └─→ trade_logs table
   │                   · ticket
   │                   · symbol
   │                   · type (buy/sell)
   │                   · lots
   │                   · open_price
   │                   · status = "open"
   │
   ├─→ SendTradeOpened()
   │    └─→ POST /api/bot/trade/opened
   │         (Records in trade_events, not trade_logs)
   │
   ... Trade is open ...
   │
   Trade Closes
   │
   └─→ SendClosedTrade()
        │
        └─→ POST /api/bot/trade/log
             │
             │ JSON: {account, ticket, close_price, closed_lots, profit, reason}
             │
             ▼
        TradeLogController::store()
             │
             ├─→ Find trade_logs by ticket
             │    ├─→ If found: ✅ Update record
             │    │             · profit
             │    │             · close_price
             │    │             · closed_lots
             │    │             · status = "closed"
             │    │
             │    └─→ If NOT found: ❌ ERROR 404 (FAILURE!)
             │
             └─→ Database Updated
                  trade_logs table
                  · profit ← populated
                  · close_price ← populated
                  · closed_lots ← populated
                  · status ← "closed"
                  · close_reason ← "TP" "SL" etc
```

---

## 🚨 Common Failure Points

### Failure Point 1: Signal Never Sent (MOST COMMON - 80% of cases)

```
 MT5 EA                Laravel          Database
 ──────────────────────────────────────────────────
 
 Trade Opens
   │
   ├─→ SendSignalToLaravel()  ❌ NOT CALLED!
   │   (Code missing or returns early)
   │
   └─→ SendTradeOpened()  (Creates trade_events, not trade_logs)
   
   ... Trade closes ...
   
   └─→ SendClosedTrade()
        │
        └─→ POST /api/bot/trade/log
             │
             ▼
        TradeLogController::store()
             │
             ├─→ Query: SELECT * FROM trade_logs WHERE ticket = ?
             │    │
             │    └─→ ❌ NOTHING FOUND! (Record was never created)
             │
             └─→ RETURNS: 404 NOT FOUND
                 message: "Trade log not found"
                 
   RESULT: Trade is lost, not saved ❌
```

**Fix**: Ensure EA calls SendSignalToLaravel() when placing trade

---

### Failure Point 2: Account Not Found

```
 SignalController::store()
   │
   ├─→ Validate JSON fields
   │    ✅ account: 102734606
   │    ✅ ticket: "1234567"
   │    ✅ symbol: "EURUSD"
   │    ✅ All fields valid
   │
   ├─→ Query: SELECT * FROM accounts WHERE login = 102734606
   │    │
   │    └─→ ❌ NO RECORD FOUND!
   │
   └─→ RETURNS: 400 BAD REQUEST
       message: "Account not found"
       
   RESULT: Signal not created, TradeLog not created, trade lost ❌
```

**Fix**: Create account in database

```bash
php artisan tinker
>>> Account::create(['login' => 102734606, 'name' => 'Trading Account', 'active' => true])
```

---

### Failure Point 3: Validation Error in Signal Payload

```
 POST /api/bot/signal
   │
   └─→ JSON received: {"account": 102734606, "ticket": "123", "symbol": null}
        (Missing 'symbol' field!)
        │
        ├─→ Validation runs
        │    │
        │    ├─→ ✅ account: valid
        │    ├─→ ✅ ticket: valid
        │    ├─→ ❌ symbol: MISSING (required!)
        │    ├─→ ❌ direction: MISSING
        │    └─→ ❌ lots: MISSING
        │
        └─→ RETURNS: 422 UNPROCESSABLE ENTITY
            message: "Validation failed"
            errors: {
              "symbol": ["The symbol field is required"],
              "direction": ["The direction field is required"],
              "lots": ["The lots field is required"]
            }
            
   RESULT: Signal not created, trade lost ❌
```

**Fix**: Ensure EA sends all required fields:
- account (numeric)
- ticket (string)
- symbol (string)
- direction (buy/sell)
- entry (numeric)
- sl (numeric)
- tp (numeric)
- timeframe (string)
- lots (numeric)

---

### Failure Point 4: TradeLogController Gets 404

```
 Trade Closes
   │
   └─→ SendClosedTrade()
        │
        └─→ POST /api/bot/trade/log {ticket: "1234567", profit: 50.00}
             │
             ▼
        TradeLogController::store()
             │
             ├─→ Query: SELECT * FROM trade_logs WHERE ticket = '1234567' FOR UPDATE
             │    (Look for record to update)
             │    │
             │    └─→ ❌ NO RECORD FOUND!
             │        (Why? Signal was never sent!)
             │
             └─→ RETURNS: 404 NOT FOUND
                 message: "Trade log not found"
                 
   RESULT: Trade close not recorded, profit not saved ❌
```

**Fix**: Make sure signal was sent BEFORE trade closes
- Always send signal to /signal when trade opens
- Only then can trade_logs be updated when trade closes

---

## 🔧 How to Check Each Failure Point

### Check #1: Is Signal in Database?

```bash
php artisan tinker

# Check for recent signals
>>> Signal::latest()->limit(10)->get()

# If empty → Failure Point 1 (Signal never sent)
# If populated → Go to Check #2
```

---

### Check #2: Is TradeLog Created?

```bash
php artisan tinker

# Check for recent trade logs
>>> TradeLog::latest()->limit(10)->get()

# If empty → Signal sent but TradeLog not created
#           Check for error in SignalController logs

# If populated → Go to Check #3
```

---

### Check #3: Are Trades Being Closed?

```bash
php artisan tinker

# Check for closed trades
>>> TradeLog::where('status', 'closed')->count()

# If 0 → Trades open but SendClosedTrade() not being called

# If > 0 → Trades are being saved properly ✅
```

---

### Check #4: Are There Errors in Logs?

```bash
# Watch logs in real time
tail -f storage/logs/laravel.log

# When EA places a trade, should see:
# [2026-03-24 10:30:15] local.INFO: Incoming raw request: {"account":102734606,...}
# [2026-03-24 10:30:15] local.INFO: Signal & trade log created successfully

# When trade closes, should see:
# [2026-03-24 10:35:45] local.INFO: ✅ Trade closed: Account=102734606 | Ticket=1234567 | Profit=50.00
# [2026-03-24 10:35:45] local.INFO: Trade log updated
```

---

## 🎯 Step-by-Step Verification

### Before Anything Else

```bash
# 1. Check account exists
php artisan tinker
>>> Account::where('login', 102734606)->first()
# Should return Account object

# If null:
>>> Account::create(['login' => 102734606, 'name' => 'Account', 'active' => true])
```

### Test Signal Endpoint

```bash
# 2. Send test signal
curl -X POST http://94.72.112.148:8011/api/bot/signal \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -d '{
    "account": 102734606,
    "ticket": "TEST12345",
    "symbol": "EURUSD",
    "direction": "buy",
    "entry": 1.0850,
    "sl": 1.0825,
    "tp": 1.0900,
    "timeframe": "M15",
    "lots": 0.1
  }'

# Expected: 201 Created
```

### Verify in Database

```bash
# 3. Check both records created
php artisan tinker
>>> Signal::where('ticket', 'TEST12345')->first()
# Should show signal

>>> TradeLog::where('ticket', 'TEST12345')->first()
# Should show trade log with status='open'
```

### Test Trade Close

```bash
# 4. Send trade close
curl -X POST http://94.72.112.148:8011/api/bot/trade/log \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -d '{
    "account": 102734606,
    "ticket": "TEST12345",
    "close_price": 1.0875,
    "closed_lots": 0.1,
    "profit": 25.00,
    "reason": "TP"
  }'

# Expected: 200 OK with message "Trade log updated successfully"
```

### Verify Update in Database

```bash
# 5. Check trade closed
php artisan tinker
>>> $log = TradeLog::where('ticket', 'TEST12345')->first()
>>> $log->status
# Should show: "closed"

>>> $log->profit
# Should show: 25.00

>>> $log->close_price
# Should show: 1.0875
```

---

## 📋 Quick Checklist

- [ ] Account exists: `Account::where('login', YOUR_ACCOUNT)->first()`
- [ ] Recent signals: `Signal::latest()->first()` shows records
- [ ] Recent trade logs: `TradeLog::latest()->first()` shows records
- [ ] Closed trades: `TradeLog::where('status', 'closed')->count() > 0`
- [ ] API key valid: Response not 401/403
- [ ] JSON format valid: curl test works
- [ ] EA debug logs show: ✅ Signal successfully sent, ✅ Trade closed
- [ ] Database updated: TradeLog has profit, close_price, status='closed'

---

## 🆘 If Still Not Working

Run monitoring script:
```bash
php debug_trade_logs.php
```

This will identify exactly which stage is failing:
1. Accounts configured?
2. Signals being created?
3. TradeLog records created?
4. Trades being closed?
5. TradeLog being updated?

Then refer to the specific failure point above for that section.

---

**Last Updated**: March 24, 2026
**See Also**: WHY_TRADES_NOT_SAVED_DIAGNOSIS_GUIDE.md
