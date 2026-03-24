# 🔧 Trade Closure API Fix - Complete

**Date**: March 24, 2026  
**Status**: ✅ IMPLEMENTED & READY FOR TESTING  
**Issue**: Laravel "symbol is required and cannot be empty" error when EA closes trades  
**Root Cause**: EA sending incomplete payload to `/trade/log` endpoint

---

## Problem Analysis

### Error Flow
```
MT5 Trade Closes
    ↓
OnTradeTransaction() trigger
    ↓
SendClosedTrade() called
    ↓
POST /trade/log with incomplete JSON
    ↓
TradeLogController validates & creates TradeLog
    ↓
❌ Missing required fields: symbol, type, lots, open_price, sl, tp
    ↓
Exception: "TradeLog symbol is required and cannot be empty"
```

### Why It Happened
1. **SignalController** (first endpoint `/signal`): Creates TradeLog with full data when signal received
2. **TradeLogController** (second endpoint `/trade/log`): Receives trade closure events
3. **Mismatch**: SendClosedTrade() only sent 6 fields (account, ticket, close_price, closed_lots, profit, reason)
4. **Laravel Expected**: All 12 fields for both opening and closing scenarios

---

## Solutions Implemented

### 1️⃣ Enhanced SendClosedTrade() Function
**File**: `connection.mqh` (line 308)  
**Changes**:
- Added deal history search logic to retrieve: symbol, type, lots, open_price
- First searches for closing deal (DEAL_ENTRY_OUT)
- Falls back to opening deal (DEAL_ENTRY_IN) if close deal not found
- Includes fallback to current Symbol() if neither found
- Now sends complete 12-field JSON payload:

```json
{
  "account": 1234567890,
  "ticket": "1642553608",
  "symbol": "XAUUSD",
  "type": "BUY",
  "lots": 0.10,
  "open_price": 2411.50,
  "sl": 2403.80,
  "tp": 2419.20,
  "close_price": 2410.95,
  "closed_lots": 0.10,
  "profit": -45.30,
  "reason": "TP|SL|manual"
}
```

### 2️⃣ Updated TradeLogController Validation
**File**: `app/Http/Controllers/Bot/TradeLogController.php` (line 57-71)  
**Changes**:

#### Before
```php
$validated = validator($data, [
    'account'     => 'required|numeric',
    'ticket'      => 'required',
    'close_price' => 'nullable|numeric',
    'profit'      => 'nullable|numeric',
    'closed_lots' => 'nullable|numeric|min:0',
    'reason'      => 'nullable|string',
])->validate();
```

#### After
```php
$validated = validator($data, [
    'account'     => 'required|numeric',
    'ticket'      => 'required|string',
    'symbol'      => 'required|string',
    'type'        => 'required|regex:/^(BUY|SELL|buy|sell)$/',
    'lots'        => 'required|numeric|gt:0',
    'open_price'  => 'required|numeric|gt:0',
    'sl'          => 'required|numeric|gte:0',
    'tp'          => 'required|numeric|gte:0',
    'close_price' => 'nullable|numeric|gt:0',
    'profit'      => 'nullable|numeric',
    'closed_lots' => 'nullable|numeric|gte:0',
    'reason'      => 'nullable|string',
])->validate();

// Normalize type to uppercase (BUY or SELL)
$validated['type'] = strtoupper($validated['type']);
```

### 3️⃣ Fixed TradeLog Creation Logic
**File**: `TradeLogController.php` (line 91-103)  
**Changes**:

#### Before
```php
'lots' => $validated['closed_lots'] ?? 0,  // ❌ WRONG!
'status' => 'closed',
```

#### After
```php
'lots' => $validated['lots'],  // ✅ CORRECT
'status' => 'open',  // Start as open, update when closed
```

---

## Database Impact

### TradeLog Table Updates
When trade closes, the TradeLog record is updated with:
```
status: 'open' → 'closed'
close_price: <closePrice>
profit: <profit>
closed_lots: <closed_lots>
close_reason: 'TP' | 'SL' | 'manual'
closed_at: <now()>
```

---

## Data Flow After Fix

```
┌─────────────────────────────────────────────────────┐
│ MT5 PlaceMarketOrder                               │
│ (EA places trade)                                   │
└──────────────────┬──────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────────────────┐
│ POST /api/bot/signal                               │
│ Signal sent to Laravel (from AI validation)         │
│ TradeLog created with:                              │
│ - account_id, ticket, symbol, type, lots           │
│ - open_price, sl, tp, opened_at                    │
│ - status='open'                                     │
└──────────────────┬──────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────────────────┐
│ MT5 OnTradeTransaction                             │
│ (Deal closes - SL/TP hit or manual)                │
│ SendClosedTrade() called                            │
└──────────────────┬──────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────────────────┐
│ POST /api/bot/trade/log                            │
│ Trade closure sent with ALL fields:                │
│ - account, ticket, symbol, type, lots              │
│ - open_price, sl, tp                               │
│ - close_price, closed_lots (partial close support) │
│ - profit, reason                                   │
└──────────────────┬──────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────────────────┐
│ TradeLogController.store()                          │
│ 1. Validate all 12 fields                          │
│ 2. Check if TradeLog exists by ticket              │
│ 3. If NOT found: Create new record                 │
│ 4. If found: Update with closure data              │
│ 5. Return HTTP 201                                 │
└──────────────────┬──────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────────────────┐
│ TradeLog Record Updated                            │
│ status:       'open' → 'closed'                    │
│ close_price:  2410.95                              │
│ profit:       -45.30                               │
│ closed_lots:  0.10                                 │
│ closed_at:    2026-03-24 15:30:45                  │
└─────────────────────────────────────────────────────┘
```

---

## Key Changes Summary

| Component | Before | After | Status |
|-----------|--------|-------|--------|
| **SendClosedTrade()** | 6 fields | 12 fields | ✅ FIXED |
| **Validation Rules** | 5 rules | 11 rules | ✅ FIXED |
| **Type Handling** | `in:buy,sell` | `regex:/^(BUY\|SELL\|buy\|sell)$/` | ✅ FIXED |
| **Type Normalization** | None | strtoupper() | ✅ FIXED |
| **Create Logic** | `lots=$closed_lots` | `lots=$lots` | ✅ FIXED |
| **Initial Status** | 'closed' | 'open' | ✅ FIXED |

---

## Testing Checklist

### Unit Tests
- [ ] SendClosedTrade() correctly retrieves symbol from deal history
- [ ] SendClosedTrade() correctly retrieves type (BUY/SELL) from deal history
- [ ] JSON payload includes all 12 required fields
- [ ] TradeLogController validates type (both BUY and buy accepted)
- [ ] TradeLogController normalizes type to uppercase

### Integration Tests
- [ ] Backtest: Trade opens → Signal sent → TradeLog created (status='open')
- [ ] Backtest: Trade closes → Closure event sent → TradeLog updated (status='closed')
- [ ] Laravel: HTTP 201 response received on closure
- [ ] Database: TradeLog records show correct open and close data

### Live Testing
- [ ] Real XAUUSD trade opens without API error
- [ ] Trade closes cleanly without "symbol required" error
- [ ] Dashboard shows open/closed status correctly
- [ ] Profit calculation matches EA execution

---

## Dependencies

### MQL5 Files
- `connection.mqh`: SendClosedTrade() (UPDATED)
- `Viomia.mq5`: OnTradeTransaction() calls SendClosedTrade() (NO CHANGES)

### Laravel Files
- `app/Http/Controllers/Bot/TradeLogController.php` (UPDATED)
- `app/Models/TradeLog.php` (NO CHANGES - validation works correctly)

---

## Rollback Plan

If issues occur:

### Revert SendClosedTrade()
```mql5
// Restore original 6-field payload
"account": %account
"ticket": %ticket
"close_price": %close_price
"closed_lots": %closed_lots
"profit": %profit
"reason": %reason
```

### Revert Validation
Back to accepting only: account, ticket, close_price, closed_lots, profit, reason

---

## Next Steps

1. ✅ **Code Review**: Verify all 3 files changed correctly
2. ⏭️ **Compile**: Test MQL5 compilation (no errors expected)
3. ⏭️ **Backtest**: Run mini-backtest on XAUUSD M3 (5 trades)
4. ⏭️ **Monitor**: Watch for HTTP responses in EA logs
5. ⏭️ **Verify**: Check Laravel logs for TradeLog creation/updates
6. ⏭️ **Live**: Deploy to demo account first, then live

---

## Success Criteria

✅ **ALL of the following must be true:**
- [ ] EA compiles without errors
- [ ] No HTTP 422 errors on trade close
- [ ] TradeLog records created with status='open' on signal
- [ ] TradeLog records updated with status='closed' on close
- [ ] Dashboard shows complete trade lifecycle (open → close)
- [ ] Profit calculations match between EA and database
- [ ] No crashes or exceptions in Laravel logs
- [ ] No "symbol is required" validation errors

---

## Files Modified

1. ✅ [connection.mqh](c:\Users\User\AppData\Roaming\MetaQuotes\Terminal\F762D69EEEA9B4430D7F17C82167C844\MQL5\Experts\VIOMIA\web\connection.mqh#L308-L395)
   - Enhanced SendClosedTrade() with deal history lookup
   
2. ✅ [TradeLogController.php](d:\MQL 5 PROJECTS\viomia_bot\app\Http\Controllers\Bot\TradeLogController.php#L57-L103)
   - Updated validation rules
   - Added type normalization
   - Fixed create logic

---

**Created**: 2026-03-24 15:25 UTC  
**Author**: AI Code Assistant  
**Status**: Ready for implementation
