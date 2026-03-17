# Laravel EA Route Handler Validation Report
## Date: March 17, 2026

---

## SUMMARY OF ISSUES FOUND

**Critical Issues**: 8  
**Validation Mismatches**: 3  
**Missing Account Mapping**: 8/12  

---

## ISSUES IDENTIFIED

### 🔴 CRITICAL: Missing Account ID Validation & Mapping

All controllers below receive `"account"` from the bot but don't validate or map it to `account_id`:

#### 1. TradeLogController.store() - `/trade/log`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- Model needs: `account_id` (foreign key)
- **RESULT**: Data stored without account association! ❌

**Current Validation**:
```php
$validated = validator($data, [
    'ticket'      => 'required',
    'close_price' => 'nullable|numeric',
    'profit'      => 'nullable|numeric',
    'closed_lots' => 'nullable|numeric|min:0',
    'reason'      => 'nullable|string',
])->validate();
```

**Should Be**:
```php
$validated = validator($data, [
    'account'     => 'required|numeric',
    'ticket'      => 'required',
    'close_price' => 'nullable|numeric',
    'profit'      => 'nullable|numeric',
    'closed_lots' => 'nullable|numeric|min:0',
    'reason'      => 'nullable|string',
])->validate();

// Resolve account to account_id
$account = Account::where('login', $validated['account'])->first();
if (!$account) {
    return response()->json(['error' => 'Account not found'], 400);
}
$validated['account_id'] = $account->id;
unset($validated['account']);
```

---

#### 2. TradeEventController.store() - `/trade/opened`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- **RESULT**: Data stored without account association! ❌

**Current Validation**:
```php
$validated = validator($data, [
    'ticket'        => 'required|string|unique:trade_events',
    'direction'     => 'required|in:BUY,SELL',
    'entry_price'   => 'required|numeric',
    'sl_price'      => 'required|numeric',
    'tp_price'      => 'required|numeric',
    'lot_size'      => 'required|numeric',
    'signal_source' => 'nullable|string',
    'opened_at'     => 'required|date_format:Y-m-d H:i:s',
])->validate();
```

**Missing**: Account validation and mapping

---

#### 3. DailySummaryController.store() - `/trading/daily-summary`
**Issue 1 - Missing Account ID**:
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation

**Issue 2 - Wrong Validation Rules**:
- Bot sends: `"winning_trades": nullable, "losing_trades": nullable`
- Controller requires: ✅ Required
- **Mismatch!** - Bot may send null values

**Current Validation**:
```php
$validated = validator($data, [
    'daily_pl'          => 'required|numeric',
    'trades_count'      => 'required|integer',
    'winning_trades'    => 'required|integer',  // ❌ Should be nullable
    'losing_trades'     => 'required|integer',  // ❌ Should be nullable
    'win_rate_percent'  => 'required|numeric',
    'balance'           => 'required|numeric',
    'equity'            => 'required|numeric',
    'summary_date'      => 'required|date_format:Y-m-d',
    'captured_at'       => 'required|date_format:Y-m-d H:i:s',
])->validate();
```

---

#### 4. PositionUpdateController.store() - `/position/update`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- **RESULT**: Data stored without account association! ❌

**Missing**: Account validation and mapping

---

#### 5. LossLimitAlertController.store() - `/alert/daily-loss-limit`
**Issue 1 - Missing Account ID**:
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation

**Issue 2 - Wrong Validation Rules**:
- Bot sends: `"limit_type": "HARD_STOP"` or `"SOFT_STOP"`
- Controller validates: `'limit_type' => 'required|in:USD,PERCENT'`
- **Mismatch!** - These are completely different values!

**Current Validation**:
```php
$validated = validator($data, [
    'daily_loss'        => 'required|numeric',
    'daily_loss_limit'  => 'required|numeric',
    'limit_type'        => 'required|in:USD,PERCENT',  // ❌ WRONG VALUES!
    'balance'           => 'required|numeric',
    'equity'            => 'required|numeric',
    'alert_at'          => 'required|date_format:Y-m-d H:i:s',
])->validate();
```

**Should Accept**:
```php
'limit_type' => 'required|in:HARD_STOP,SOFT_STOP,WARNING'  // ✅ Correct values
```

---

#### 6. FilterBlockController.store() - `/filter/blocked`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- **RESULT**: Data stored without account association! ❌

**Missing**: Account validation and mapping

---

#### 7. TechnicalSignalController.store() - `/signal/technical`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- **RESULT**: Data stored without account association! ❌

**Missing**: Account validation and mapping

---

#### 8. ErrorLogController.store() - `/error/log`
**Issue**: 
- Bot sends: `"account": 123456`
- Controller validates: ❌ No account validation
- **RESULT**: Data stored without account association! ❌

**Missing**: Account validation and mapping

---

## FIXES REQUIRED

### Fix #1: TradeLogController
Add account validation and mapping

### Fix #2: TradeEventController
Add account validation and mapping

### Fix #3: DailySummaryController
- Add account validation and mapping
- Change `winning_trades` and `losing_trades` to nullable

### Fix #4: PositionUpdateController
Add account validation and mapping

### Fix #5: LossLimitAlertController
- Add account validation and mapping
- Fix limit_type validation from `'in:USD,PERCENT'` to `'in:HARD_STOP,SOFT_STOP,WARNING'`

### Fix #6: FilterBlockController
Add account validation and mapping

### Fix #7: TechnicalSignalController
Add account validation and mapping

### Fix #8: ErrorLogController
Add account validation and mapping

---

## IMPACT ASSESSMENT

**Without These Fixes**:
- ❌ Trades not associated with correct account
- ❌ Data integrity issues (orphaned records)
- ❌ Queries fail because account_id is NULL
- ❌ Admin cannot filter/view data by account
- ❌ Loss limit alerts fail validation (USD/PERCENT vs HARD_STOP)
- ⚠️ Daily summary values lost (nullable fields)

**After Fixes**:
- ✅ All data properly associated with accounts
- ✅ Account verification on every request
- ✅ Validation rules match bot payloads
- ✅ Data integrity maintained
- ✅ Proper error responses for invalid accounts

---

## SUCCESS CRITERIA

After fixes, verify:
- ✅ Bot can send trade close events (account validation passes)
- ✅ All records are tied to correct account
- ✅ Loss limit alerts with HARD_STOP type accepted
- ✅ Daily summary works with nil winning/losing trades
- ✅ All controllers return 201/200 with proper data
- ✅ Invalid accounts rejected with 400 error

