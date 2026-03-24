# 🔧 Trade Closure API Fix - UPDATED v2

**Date**: March 24, 2026  
**Status**: ✅ IMPLEMENTATION COMPLETE - PARAMETER PASSING FIXED
**Issue**: "type field is required" and "open_price field is required" validation errors  
**Root Cause**: SendClosedTrade() not receiving required parameters from caller

---

## What Was Wrong

### Original Flow (Broken)
```
OnTradeTransaction triggers
    ↓
No deal data passed to SendClosedTrade()
    ↓
SendClosedTrade tries to lookup history (unreliable)
    ↓
Variables may be uninitialized
    ↓
JSON missing type and open_price
    ↓
❌ Laravel validation fails
```

### New Flow (Fixed)
```
OnTradeTransaction triggers
    ↓
Extract symbol, type, lots from closing deal
    ↓
Search history for opening deal to get open_price
    ↓
Pass ALL parameters to SendClosedTrade()
    ↓
SendClosedTrade() builds complete 12-field JSON
    ↓
✅ Laravel validates successfully
```

---

## Changes Made

### 1️⃣ Updated SendClosedTrade() Function Signature
**File**: `connection.mqh` (line 308)

**Before**:
```mql5
bool SendClosedTrade(ulong ticket, double close_price, double closed_lots, double profit, string close_reason)
```

**After**:
```mql5
bool SendClosedTrade(ulong ticket, string symbol, string type, double lots, double open_price, double close_price, double closed_lots, double profit, string close_reason)
```

**Logic**:
- Function now accepts 9 parameters instead of 5
- All trading details passed directly, no need for history lookup
- Safer: relies on caller (OnTradeTransaction) which has deal access

### 2️⃣ Enhanced OnTradeTransaction Handler
**File**: `Viomia.mq5` (line 252-288)

**New Logic**:
```mql5
// Extract from closing deal
ulong positionId = (ulong)HistoryDealGetInteger(dealTicket, DEAL_POSITION_ID);
string symbol    = HistoryDealGetString(dealTicket, DEAL_SYMBOL);
string type      = ((long)HistoryDealGetInteger(dealTicket, DEAL_TYPE) == DEAL_TYPE_BUY) ? "BUY" : "SELL";
double lots      = HistoryDealGetDouble(dealTicket, DEAL_VOLUME);
double closePrice = HistoryDealGetDouble(dealTicket, DEAL_PRICE);

// Find opening deal for opening price
double openPrice = 0;
for(int i = dealsCount - 1; i >= 0; i--)
{
   if((ulong)HistoryDealGetInteger(openDealTicket, DEAL_POSITION_ID) == positionId &&
      (long)HistoryDealGetInteger(openDealTicket, DEAL_ENTRY) == DEAL_ENTRY_IN)
   {
      openPrice = HistoryDealGetDouble(openDealTicket, DEAL_PRICE);
      break;
   }
}

// Call with all required parameters
SendClosedTrade(positionId, symbol, type, lots, openPrice, closePrice, closedVol, dealProfit, closeReason);
```

### 3️⃣ Simplified SendClosedTrade() JSON Building
**File**: `connection.mqh` (line 321-346)

Now uses **direct parameters** instead of unreliable history lookup:
```mql5
string json = StringFormat(
  "{\"account\":%I64u,"
  "\"ticket\":\"%I64u\","
  "\"symbol\":\"%s\","
  "\"type\":\"%s\","
  "\"lots\":%.2f,"
  "\"open_price\":%.5f,"
  "\"sl\":%.5f,"
  "\"tp\":%.5f,"
  "\"close_price\":%.5f,"
  "\"closed_lots\":%.2f,"
  "\"profit\":%.2f,"
  "\"reason\":\"%s\""
  "}",
  accountNumber,      // From account info
  ticket,             // From parameter
  symbol,             // From parameter ✅
  type,               // From parameter ✅
  lots,               // From parameter
  open_price,         // From parameter ✅
  sl,                 // 0 (hardcoded)
  tp,                 // 0 (hardcoded)
  close_price,        // From parameter
  closed_lots,        // From parameter
  profit,             // From parameter
  SafeJsonString(close_reason)
);
```

---

## Complete JSON Payload

Trade closure now sends **all 12 required fields**:

```json
{
  "account": 1234567890,
  "ticket": "1642553608",
  "symbol": "XAUUSD",
  "type": "BUY",
  "lots": 0.10,
  "open_price": 2411.50000,
  "sl": 0.00000,
  "tp": 0.00000,
  "close_price": 2410.95000,
  "closed_lots": 0.10,
  "profit": -45.30,
  "reason": "TP"
}
```

✅ All 12 fields present → Laravel validation passes → No "field is required" errors

---

## Why This Fix Works

| Issue | Root Cause | Solution |
|-------|-----------|----------|
| Missing `type` | Not retrieved from deal | Passed as parameter from OnTradeTransaction |
| Missing `open_price` | History lookup unreliable | Explicitly searched and passed as parameter |
| Missing `symbol` | Lookup fallback not guaranteed | Extracted directly from deal ticket |
| Uninitialized values | Defaults without validation | All values come from actual trade data |

---

## Parameter Flow

### OnTradeTransaction → SendClosedTrade()
```
When deal closes:
├─ dealTicket = DEAL_OUT (closing deal)
├─ Extract: symbol, type, lots (from dealTicket)
├─ Extract: closePrice (from dealTicket)
├─ Search: openDealTicket (DEAL_IN with same POSITION_ID)
├─ Extract: openPrice (from openDealTicket)
└─ Call: SendClosedTrade(positionId, symbol, type, lots, openPrice, closePrice, closedVol, dealProfit, closeReason)
    ↓
    SendClosedTrade builds JSON with all parameters
    ↓
    POST /api/bot/trade/log
    ↓
    Laravel validates & updates TradeLog
```

---

## Testing Checklist

### Quick Verification
- [ ] Verify both files compile without errors (MetaTrader 5 terminal)
- [ ] Check no syntax errors in DebugMode output

### Integration Test
- [ ] Run mini-backtest (5 trades on XAUUSD M3)
- [ ] Verify EA log shows both opening and closing trades
- [ ] Check Laravel log for successful TradeLog updates
- [ ] Verify NO "field is required" errors in Laravel log

### Validation
- [ ] TradeLog records show: symbol, type, lots, open_price
- [ ] Closing records show: close_price, closed_lots, profit, reason
- [ ] Dashboard reflects complete trade lifecycle
- [ ] Performance metrics match between EA and DB

---

## Files Modified

1. ✅ **[connection.mqh](c:\Users\User\AppData\Roaming\MetaQuotes\Terminal\F762D69EEEA9B4430D7F17C82167C844\MQL5\Experts\VIOMIA\web\connection.mqh#L308)**
   - Updated SendClosedTrade() signature (line 308)
   - Removed unreliable history lookup logic
   - Updated JSON building (line 321-346)

2. ✅ **[Viomia.mq5](c:\Users\User\AppData\Roaming\MetaQuotes\Terminal\F762D69EEEA9B4430D7F17C82167C844\MQL5\Experts\VIOMIA\Viomia.mq5#L252)**
   - Enhanced OnTradeTransaction (line 252-288)
   - Added symbol, type, lots extraction
   - Added opening deal lookup for openPrice
   - Updated SendClosedTrade() call with all parameters

---

## Expected Behavior

### Before Fix
```
❌ Error: {  "errors": {    "type": ["The type field is required."],
    "open_price": ["The open price field is required."]
  }
}
```

### After Fix
```
✅ Success: {
  "message": "Trade logged successfully",
  "trade": {
    "id": 123,
    "account_id": 456,
    "ticket": "1642553608",
    "symbol": "XAUUSD",
    "type": "BUY",
    "status": "closed",
    "profit": -45.30,
    "created_at": "2026-03-24T15:30:45Z",
    "updated_at": "2026-03-24T15:31:02Z"
  }
}
```

---

## Rollback Instructions

If needed, revert to original simpler version:

**In Viomia.mq5**:
```mql5
// Revert call to original 5-parameter version
SendClosedTrade(closePrice, closedVol, dealProfit, closeReason);
```

**In connection.mqh**:
```mql5
// Revert function signature
bool SendClosedTrade(ulong ticket, double close_price, double closed_lots, double profit, string close_reason)
{
   // Old implementation
}
```

---

## Success Criteria

✅ **ALL of the following MUST be true:**
- [ ] MQL5 compilation succeeds (no errors)
- [ ] Laravel log shows HTTP 201 on closure
- [ ] No "field is required" validation errors
- [ ] TradeLog records include symbol, type, open_price
- [ ] Dashboard shows complete open→close trade flow
- [ ] Profit calculations match EA
- [ ] No null/empty values in database records
- [ ] 100% of closing trades logged successfully

---

**Last Updated**: 2026-03-24 15:35 UTC  
**Status**: Ready for next test run
