# 🔬 DEEP ANALYSIS: Signal Payload Structure & Data Flow

## 1. WHAT THE EA IS SENDING NOW

### Signal Endpoint: `/signal` (POST)

**Current JSON Payload** (from BuildSignalJson in OrderSend.mqh):

```json
{
  "account": 102734606,
  "symbol": "XAUUSD",
  "direction": "sell",
  "ticket": "1642553608",
  "lots": 0.06,
  "entry": 4409.87,
  "sl": 4417.57,
  "tp": 4386.77,
  "timeframe": "M15",
  "active": true,
  "created_at": "2026.03.24 14:06:08"
}
```

---

## 2. WHAT LARAVEL EXPECTS

### SignalController.php - store() method validation rules:

```php
$validated = validator($data, [
    'account'   => 'required|numeric',       // ✅ Present, numeric (int)
    'ticket'    => 'required|string',        // ✅ Present, string
    'symbol'    => 'required|string|max:10', // ✅ Present, string
    'direction' => 'required|in:buy,sell',   // ✅ Present, lowercase buy/sell
    'entry'     => 'required|numeric',       // ✅ Present, numeric (float)
    'sl'        => 'required|numeric',       // ✅ Present, numeric (float)
    'tp'        => 'required|numeric',       // ✅ Present, numeric (float)
    'timeframe' => 'required|string|max:10', // ✅ Present, string
    'lots'      => 'required|numeric',       // ✅ Present, numeric (float)
])->validate();
```

**Extra fields NOT validated**:
- `active` (not required, will be ignored)
- `created_at` (not required, will be ignored)

---

## 3. DATA TYPE ANALYSIS

### Field-by-Field Breakdown:

| Field | EA Sends | Type | Laravel Expects | Status | Notes |
|-------|----------|------|-----------------|--------|-------|
| **account** | 102734606 | int | numeric | ✅ | Account login number |
| **ticket** | "1642553608" | string | string | ✅ | MT5 order ticket number |
| **symbol** | "XAUUSD" | string | string | ✅ | Trading symbol |
| **direction** | "sell" | string | buy\|sell | ✅ | Lowercase matches validation |
| **entry** | 4409.87 | decimal | numeric | ✅ | Entry price |
| **sl** | 4417.57 | decimal | numeric | ✅ | Stop loss price |
| **tp** | 4386.77 | decimal | numeric | ✅ | Take profit price |
| **timeframe** | "M15" | string | string | ✅ | Timeframe (M15, M5, etc) |
| **lots** | 0.06 | decimal | numeric | ✅ | Position size |
| **active** | true | boolean | (ignored) | ✅ | Laravel doesn't validate this |
| **created_at** | "2026.03.24 14:06:08" | string | (ignored) | ✅ | Laravel doesn't validate this |

---

## 4. VALIDATION STATUS: ✅ ALL FIELDS PASS

**Conclusion**: The current payload structure perfectly matches Laravel validation rules.

```
HTTP Response Expected: ✅ 201 Created
{
    "success": true,
    "message": "Signal & trade log created successfully",
    "signal_id": 123,
    "ticket": "1642553608"
}
```

---

## 5. COMPLETE DATA FLOW (13 STEPS)

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 1: Technical Signal Detection (M3+M5 Dual Confirm)       │
├─────────────────────────────────────────────────────────────────┤
│ OnTick → BuildEntrySignal(M3, M5)                              │
│ └─ Output: sig = -1 (SELL signal)                              │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 2: AI Gate Validation                                     │
├─────────────────────────────────────────────────────────────────┤
│ AI API call → /ai/signal (Python Viomia_AI server)            │
│ └─ Output: confidence=0.62, decision="SELL"                    │
│ └─ Result: ✅ APPROVED (passes 3 checks)                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 3: Risk Management Validation                             │
├─────────────────────────────────────────────────────────────────┤
│ • Check SL distance: 7.70 points < 1000 points ✅             │
│ • Check correlation: Not correlated ✅                          │
│ • Check position limit: 0 open < MaxPositions ✅              │
│ • Check cooldown: Not in cooldown ✅                           │
│ • Check spread: <= MaxSpread ✅                                │
│ └─ Result: ✅ ALL PASS                                         │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 4: Order Placemen (MT5)                                  │
├─────────────────────────────────────────────────────────────────┤
│ PlaceMarketOrder(buy=false, lots=0.06, sl=4417.57, tp=4386.77)│
│ ├─ CTrade.Sell() executed                                      │
│ ├─ Order accepted by MT5 broker                                │
│ └─ Result: ticket=1642553608 ✅                               │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 5: Build Signal JSON                                      │
├─────────────────────────────────────────────────────────────────┤
│ BuildSignalJson(                                               │
│   symbol="XAUUSD",                                             │
│   buy=false,                                                   │
│   ticket=1642553608,                                           │
│   lots=0.06,                                                   │
│   entry=4409.87,                                               │
│   sl=4417.57,                                                  │
│   tp=4386.77,                                                  │
│   timeframe="M15"                                              │
│ )                                                              │
│                                                                 │
│ Output JSON:                                                   │
│ {                                                              │
│   "account": 102734606,          ← Account login (numeric)    │
│   "symbol": "XAUUSD",            ← Symbol                     │
│   "direction": "sell",           ← Lowercase (✅ FIXED)       │
│   "ticket": "1642553608",        ← String ticket             │
│   "lots": 0.06,                  ← Position size             │
│   "entry": 4409.87,              ← Entry price               │
│   "sl": 4417.57,                 ← Stop loss                 │
│   "tp": 4386.77,                 ← Take profit               │
│   "timeframe": "M15",            ← Timeframe                 │
│   "active": true,                ← (Not used by Laravel)     │
│   "created_at": "2026.03.24 14:06:08"  ← (Not used by Laravel)
│ }                                                              │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 6: Convert to uchar array                                │
├─────────────────────────────────────────────────────────────────┤
│ StringToCharArray(json, data, 0, WHOLE_ARRAY, CP_UTF8)        │
│ └─ Converts JSON string to UTF-8 byte array                    │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 7: Send HTTP POST Request                                │
├─────────────────────────────────────────────────────────────────┤
│ WebRequest(                                                     │
│   method="POST",                                               │
│   url="http://94.72.112.148:8011/api/bot/signal",            │
│   headers={                                                    │
│     "Content-Type: application/json",                          │
│     "X-API-KEY: TEST_API_KEY_123",                            │
│     "Accept: application/json"                                │
│   },                                                           │
│   timeout=10000,                                              │
│   post_data=json_bytes,                                       │
│   response=result,                                            │
│   headers=response_headers                                    │
│ )                                                              │
│                                                                 │
│ └─ HTTP 200-299: ✅ Success                                   │
│ └─ HTTP 400+: ❌ Laravel validation error                     │
│ └─ HTTP -1: ❌ Network error (no connection)                  │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 8: Laravel Receives Request                              │
├─────────────────────────────────────────────────────────────────┤
│ SignalController::store()                                       │
│ ├─ Read raw JSON from request body                             │
│ ├─ Clean null bytes (preg_replace)                            │
│ ├─ Decode JSON to PHP array                                    │
│ └─ Log: 'Incoming raw request: {json}'                        │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 9: Laravel Validates Payload                             │
├─────────────────────────────────────────────────────────────────┤
│ Validator::make($data, rules)->validate()                     │
│                                                                 │
│ Checks:                                                        │
│ ✅ account (required, numeric)                                │
│ ✅ ticket (required, string)                                  │
│ ✅ symbol (required, string, max:10)                          │
│ ✅ direction (required, in:buy,sell)                          │
│ ✅ entry (required, numeric)                                  │
│ ✅ sl (required, numeric)                                     │
│ ✅ tp (required, numeric)                                     │
│ ✅ timeframe (required, string, max:10)                       │
│ ✅ lots (required, numeric)                                   │
│                                                                 │
│ Result: ✅ ALL PASS (or ❌ 422 Validation Error)              │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 10: Account Lookup                                        │
├─────────────────────────────────────────────────────────────────┤
│ $account = Account::where('login', 102734606)->first()        │
│                                                                 │
│ If exists: ✅ Get account record from database                │
│ If missing: ❌ 400 error "Account number does not exist"      │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 11: Store Data in Database (Transaction)                │
├─────────────────────────────────────────────────────────────────┤
│ DB::beginTransaction()                                          │
│                                                                 │
│ A) Create Signal record:                                       │
│    Signal::create([                                            │
│      'ticket' => 1642553608,                                   │
│      'symbol' => 'XAUUSD',                                     │
│      'direction' => 'sell',                                    │
│      'entry' => 4409.87,                                       │
│      'sl' => 4417.57,                                          │
│      'tp' => 4386.77,                                          │
│      'timeframe' => 'M15',                                     │
│      'active' => true                                          │
│    ])                                                          │
│                                                                 │
│ B) Deactivate previous signals for XAUUSD                      │
│                                                                 │
│ C) Create TradeLog record:                                     │
│    TradeLog::create([                                          │
│      'account_id' => 1,          ← FK to accounts table       │
│      'ticket' => 1642553608,                                   │
│      'symbol' => 'XAUUSD',                                     │
│      'type' => 'sell',                                         │
│      'lots' => 0.06,                                           │
│      'open_price' => 4409.87,                                  │
│      'sl' => 4417.57,                                          │
│      'tp' => 4386.77                                           │
│    ])                                                          │
│                                                                 │
│ D) Distribute signal to all active accounts:                   │
│    for each Account where active=true:                         │
│      SignalAccount::create([signal_id, account_id, ticket])   │
│                                                                 │
│ DB::commit()  ← All or nothing (atomic transaction)            │
│ └─ Result: ✅ All records created, or ❌ rollback on error   │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 12: Laravel Returns Response                             │
├─────────────────────────────────────────────────────────────────┤
│ HTTP 201 Created                                               │
│ {                                                              │
│   "success": true,                                             │
│   "message": "Signal & trade log created successfully",        │
│   "signal_id": 123,                                            │
│   "ticket": "1642553608"                                       │
│ }                                                              │
│                                                                 │
│ OR on validation error:                                        │
│ HTTP 422 Unprocessable Entity                                  │
│ {                                                              │
│   "message": "The given data was invalid",                     │
│   "errors": { "account": [...] }                              │
│ }                                                              │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ STEP 13: EA Processes Response                                │
├─────────────────────────────────────────────────────────────────┤
│ SendSignalToLaravel(json) receives response                   │
│                                                                 │
│ if (status >= 200 && status < 300):                           │
│   ✅ Print("Signal successfully sent to Database → response") │
│   ✅ Log added to EA journal                                  │
│ else:                                                          │
│   ❌ Print("Signal send returned non-2xx → check logs")       │
│   ❌ No retry by default                                      │
│                                                                 │
│ Also sends:                                                    │
│   SendTradeOpened(ticket, buy, price, sl, tp, lots, reason)  │
│   └─ Logs trade opening event to Laravel separately           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 6. JSON PAYLOAD FIELD REFERENCE

### `account` (integer)
- **Source**: `AccountInfoInteger(ACCOUNT_LOGIN)`
- **Example**: `102734606`
- **Purpose**: Links signal to account record in database
- **Validation**: `required|numeric`

### `ticket` (string)
- **Source**: MT5 order ticket number from trade.ResultOrder()
- **Example**: `"1642553608"`
- **Purpose**: Unique identifier for the trade
- **Validation**: `required|string`
- **Note**: Must be unique (prevents duplicate signals)

### `symbol` (string)
- **Source**: `_Symbol` (trading symbol)
- **Example**: `"XAUUSD"`
- **Purpose**: Identifies which instrument was traded
- **Validation**: `required|string|max:10`

### `direction` (string, lowercase)
- **Source**: `buy ? "buy" : "sell"`
- **Example**: `"sell"`
- **Purpose**: Trading direction
- **Validation**: `required|in:buy,sell`
- **Note**: Must be lowercase! (✅ Fixed in our edit)

### `entry` (float)
- **Source**: Executed price from MT5
- **Example**: `4409.87`
- **Purpose**: Actual entry price (bid for SELL, ask for BUY)
- **Validation**: `required|numeric`

### `sl` (float)
- **Source**: Stop loss level from risk calculation
- **Example**: `4417.57`
- **Purpose**: Stop loss price level
- **Validation**: `required|numeric`
- **Precision**: 2 decimal places for XAUUSD

### `tp` (float)
- **Source**: Take profit level from risk calculation
- **Example**: `4386.77`
- **Purpose**: Take profit price level
- **Validation**: `required|numeric`
- **Precision**: 2 decimal places for XAUUSD

### `timeframe` (string)
- **Source**: Hardcoded as `"M15"` in PlaceMarketOrder call
- **Example**: `"M15"`
- **Purpose**: Identifies which timeframe signal was generated on
- **Validation**: `required|string|max:10`

### `lots` (float)
- **Source**: Risk-based position sizing (GetLotByEquity)
- **Example**: `0.06`
- **Purpose**: Position size in lots
- **Validation**: `required|numeric`
- **Note**: Already normalized using NormalizeVolume()

### `active` (boolean) — NOT VALIDATED
- **Source**: Hardcoded as `true`
- **Example**: `true`
- **Purpose**: Marks signal as active
- **Laravel**: Ignores this field, doesn't validate

### `created_at` (string) — NOT VALIDATED
- **Source**: `TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)`
- **Example**: `"2026.03.24 14:06:08"`
- **Purpose**: Timestamp when signal was sent
- **Laravel**: Ignores this field, Laravel uses database timestamp

---

## 7. COMPARISON: BEFORE vs AFTER FIX

### Before (❌ Failed with 422)
```json
{
  "account_id": 102734606,     ← WRONG field name
  "symbol": "XAUUSD",
  "direction": "sell",
  // ...
}
```

**Laravel Error**:
```
HTTP 422
{
  "message": "The given data was invalid",
  "errors": {
    "account": ["The account field is required."]
  }
}
```

### After (✅ Pass with 200-299)
```json
{
  "account": 102734606,        ← CORRECT field name
  "symbol": "XAUUSD",
  "direction": "sell",
  // ...
}
```

**Laravel Success**:
```
HTTP 201
{
  "success": true,
  "message": "Signal & trade log created successfully",
  "signal_id": 123,
  "ticket": "1642553608"
}
```

---

## 8. DATABASE RECORDS CREATED

After successful signal creation, these records are written:

### signals table
```sql
INSERT INTO signals (
  ticket, symbol, direction, entry, sl, tp, timeframe, active, created_at
) VALUES (
  '1642553608', 'XAUUSD', 'sell', 4409.87, 4417.57, 4386.77, 'M15', true, NOW()
);
```

### trade_logs table (per account)
```sql
INSERT INTO trade_logs (
  account_id, ticket, symbol, type, lots, open_price, sl, tp, created_at
) VALUES (
  1, '1642553608', 'XAUUSD', 'sell', 0.06, 4409.87, 4417.57, 4386.77, NOW()
);
```

### signal_accounts table (distribute to all active accounts)
```sql
INSERT INTO signal_accounts (
  signal_id, account_id, status, ticket, created_at
) VALUES (
  123, 1, 'pending', '1642553608', NOW()
),
(
  123, 2, 'pending', '1642553608', NOW()
),
(
  123, 3, 'pending', '1642553608', NOW()
);
```

---

## 9. POTENTIAL ISSUES & VALIDATION

### Issue 1: Account Doesn't Exist in Database
```
Error: HTTP 400
{
  "error": "Account number does not exist",
  "account": 102734606
}
```

**Fix**: Ensure account 102734606 is in the accounts table with `login=102734606`

### Issue 2: Duplicate Ticket
```
Success: HTTP 200
{
  "message": "Signal already exists",
  "ticket": "1642553608"
}
```

**Reason**: Signal with this ticket already exists. This is idempotent (safe to retry).

### Issue 3: Invalid JSON Format
```
Error: HTTP 400
{
  "error": "Invalid JSON format",
  "raw": "< broken json >"
}
```

**Fix**: Ensure StringToCharArray UTF-8 encoding works (usually not an issue)

### Issue 4: Direction Not Lowercase
```
Error: HTTP 422
{
  "message": "The given data was invalid",
  "errors": {
    "direction": ["The selected direction is invalid."]
  }
}
```

**Status**: ✅ Fixed (direction is now set to lowercase "buy"/"sell")

---

## 10. SUMMARY TABLE

| Aspect | Status | Notes |
|--------|--------|-------|
| **Account field name** | ✅ Fixed | Changed from `account_id` to `account` |
| **Field types** | ✅ Correct | All numeric/string types match validation |
| **Direction value** | ✅ Correct | Lowercase "buy" or "sell" |
| **JSON structure** | ✅ Valid | Properly formatted, UTF-8 encoded |
| **HTTP endpoint** | ✅ Correct | `/api/bot/signal` on port 8011 |
| **Database storage** | ✅ Working | Creates Signal, TradeLog, SignalAccount records |
| **Response handling** | ✅ Correct | Checks for HTTP 200-299 |
| **Error handling** | ⚠️ Basic | No retry logic, just logs |
| **Account validation** | ⚠️ Required | Account must exist in database |

---

## 11. NEXT STEPS TO VERIFY

1. **Ensure account 102734606 exists in database**:
   ```sql
   SELECT id, login, name FROM accounts WHERE login = 102734606;
   ```

2. **Run backtest and check logs for**:
   ```
   ✅ Order success: ticket=...
   ✅ Laravel Response: HTTP Status: 201 (or 200-299)
   Signal successfully sent to Database
   ```

3. **Verify in Laravel database**:
   ```sql
   SELECT * FROM signals WHERE ticket = '1642553608';
   SELECT * FROM trade_logs WHERE ticket = '1642553608';
   SELECT * FROM signal_accounts WHERE ticket = '1642553608';
   ```

4. **Monitor Python learning system**:
   ```
   🧠 VIOMIA learning | 1642553608 | LOSS | Profit=... | ...
   ```
   This shows Python captured the outcome for retraining.

