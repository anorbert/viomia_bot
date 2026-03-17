# Signal Validation Integration Guide

## Overview
This document explains how to integrate the Signal Validation layer into the MySMC_EA strategy.

**Problem Solved (P0-6):** No Signal Validation Before Execution
- Before this fix, the EA would execute any signal from the AI without validation
- Corrupted signals (wrong entry, invalid symbol, etc) would be executed blindly
- This led to catastrophic losses from unrealistic take profit levels or invalid symbols

## Validation Rules Implemented

The `SignalValidatorController` now validates:

1. **Symbol Validity** - Checks symbol exists in approved trading list
2. **Entry Price Realism** - Ensures entry is within realistic range (±1-5% of current price)
3. **SL/TP Ratio** - Validates risk:reward ratio (0.8:1 to 10:1)
4. **SL Distance** - Checks stop loss isn't too tight or too wide
5. **Lot Size Risk** - Ensures trade doesn't exceed 2% account risk
6. **Margin Requirements** - Validates sufficient margin available

## Integration Points

### 1. Include the Validation Library

In your EA's main strategy file (e.g., `MySMC_EA.mq5`), add:

```mql5
#include "web/SignalValidationGate.mqh"
```

### 2. Validate Before OrderSend

**BEFORE:**
```mql5
// OLD - No validation
trade.Buy(lot_size, symbol, entry_price, sl_price, tp_price);
```

**AFTER:**
```mql5
// NEW - Validate signal first
if (!ValidateSignalPreExecution(
        symbol,
        entry_price,
        sl_price,
        tp_price,
        lot_size,
        AccountInfoDouble(ACCOUNT_BALANCE),
        AccountInfoDouble(ACCOUNT_EQUITY),
        AccountInfoDouble(ACCOUNT_MARGIN_FREE)
    ))
{
    Print("Signal failed validation, skipping trade");
    return;  // Do not execute
}

// Signal passed validation, safe to execute
trade.Buy(lot_size, symbol, entry_price, sl_price, tp_price);
```

### 3. Log Validation Rejections

Rejected signals should be logged for analysis:

```mql5
struct SignalRejection
{
    datetime rejected_at;
    string symbol;
    double entry_price;
    double stop_loss;
    double take_profit;
    string rejection_reason;
};

SignalRejection rejection;
if (!ValidateSignalPreExecution(...))
{
    rejection.rejected_at = TimeCurrent();
    rejection.symbol = symbol;
    rejection.entry_price = entry_price;
    rejection.stop_loss = sl_price;
    rejection.take_profit = tp_price;
    rejection.rejection_reason = "Validation failed";
    
    // Send to Laravel for logging
    SendRejectionLog(rejection);
}
```

## Validation Response Format

### Success (HTTP 200)
```json
{
    "valid": true,
    "errors": [],
    "warnings": [],
    "checks_performed": 6,
    "checks_passed": 6
}
```
→ Signal is safe to execute

### Failure (HTTP 422)
```json
{
    "valid": false,
    "errors": [
        "Entry price 99999 outside realistic range (1.0500-1.2000)",
        "RR ratio too poor: 0.5:1 (minimum 0.8:1)"
    ],
    "warnings": [],
    "checks_performed": 6,
    "checks_passed": 2
}
```
→ DO NOT execute the signal

## Error Handling

### Timeout Scenarios
If validation takes > 400ms, the EA has two options:

**Option A: Fail-Safe (Conservative)**
```mql5
signal.valid = false;  // Assume invalid on timeout
// Signal is rejected, does not execute
```

**Option B: Failsafe-Allow (Aggressive)**
```mql5
signal.valid = true;   // Assume valid on timeout
// Signal executes even if validation timed out
// Risk: Might execute corrupted signal
```

The library currently uses **Option A** (fail-safe conservative).

## Linked Fixes

This fixes several related issues:

- **P0-2**: Now validates TP/SL ratio prevents unrealistic takes
- **P0-5**: Entry context logging now has validated entry price
- **P0-4**: Patterns are checked before entry (via validation)

## Configuration

Update your EA inputs:

```mql5
input string InpAccountId = "ACC_001";           // Used in validation
input string InpApiKey = "your-api-key-here";   // API authentication
input string InpServerUrl = "http://your-server"; // Laravel server
```

## Testing Checklist

- [ ] Test with invalid symbol (should reject)
- [ ] Test with unrealistic entry price (should reject)
- [ ] Test with SL/TP ratio < 0.8:1 (should reject)
- [ ] Test with lot size > 2% risk (should reject)
- [ ] Test with low margin (should reject)  
- [ ] Test with valid signal (should accept)
- [ ] Monitor validation latency (should be < 400ms)
- [ ] Monitor rejection logging (should appear in trade_rejections table)

## Monitoring

Monitor validation performance with:

```sql
-- Check validation pass rate
SELECT 
    DATE(rejected_at) as date,
    COUNT(*) as rejections,
    ROUND(COUNT(*) * 100.0 / 
        (SELECT COUNT(*) FROM viomia_trade_outcomes WHERE DATE(created_at) = DATE(rejected_at)),
    2) as rejection_rate_percent
FROM trade_rejections
GROUP BY DATE(rejected_at)
ORDER BY date DESC;

-- Check failure reasons
SELECT 
    rejection_reason,
    COUNT(*) as count
FROM trade_rejections
GROUP BY rejection_reason
ORDER BY count DESC;
```

## Performance Impact

- **Validation latency**: 50-400ms (depends on server response time)
- **Network overhead**: ~100 bytes per validation request
- **Server CPU**: Negligible (<1% per validation)
- **Recommended**: Run on every signal before trade to prevent catastrophic errors

## Rollback

If validation causes issues:

1. Comment out the `ValidateSignalPreExecution` call
2. EA will execute signals without validation (old behavior)
3. Review the validation response codes to understand why signals are failing

## Next Steps

1. **Integrate into EA** - Add the validation check before OrderSend
2. **Test thoroughly** - Run on demo account for 100+ trades
3. **Monitor rejections** - Track which signals are being rejected and why
4. **Tune thresholds** - Adjust validation limits based on your trading pattern
5. **Production deployment** - Roll out to live accounts after validation

