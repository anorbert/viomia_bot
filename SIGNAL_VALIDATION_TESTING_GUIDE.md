# Signal Validation Testing Guide

## Test Scenario 1: Invalid Symbol

**Setup**: Send signal with undefined symbol
```json
{
    "account_id": "ACC_001",
    "symbol": "INVALID_SYMBOL",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,
    "take_profit": 1.0650,
    "lot_size": 0.1,
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["Invalid symbol: INVALID_SYMBOL"],
    "warnings": [],
    "checks_performed": 1,
    "checks_passed": 0
}
```

**Script to Test**:
```bash
curl -X POST http://localhost/api/bot/validate-signal \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: your-api-key" \
  -d '{...json above...}'
```

---

## Test Scenario 2: Unrealistic Entry Price

**Setup**: Entry price outside realistic range
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 99999,        // WAY too high
    "stop_loss": 99950,
    "take_profit": 100050,
    "lot_size": 0.1,
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["Entry price 99999 outside realistic range (0.90-1.20)"],
    "warnings": [],
    "checks_performed": 2,
    "checks_passed": 1
}
```

---

## Test Scenario 3: Poor Risk-Reward Ratio

**Setup**: TP too close to Entry (RR < 0.5:1)
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,         // 50 pips loss
    "take_profit": 1.0555,       // 5 pips gain (0.1:1 ratio)
    "lot_size": 0.1,
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["RR ratio too poor: 0.1:1 (minimum 0.8:1)"],
    "warnings": [],
    "checks_performed": 3,
    "checks_passed": 2
}
```

---

## Test Scenario 4: Stop Loss Too Tight

**Setup**: SL distance < minimum for symbol
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0549,         // Only 1 pip (too tight)
    "take_profit": 1.0650,
    "lot_size": 0.1,
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["Stop Loss too tight: 0.0001 (minimum 0.0010)"],
    "warnings": [],
    "checks_performed": 4,
    "checks_passed": 3
}
```

---

## Test Scenario 5: Excessive Lot Size

**Setup**: Lot size exceeds 2% account risk limit
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,         // 50 pips
    "take_profit": 1.0650,
    "lot_size": 5.0,             // Way too large (5 lots)
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["Lot size 5.0 exceeds max allowed 0.4 for 2% risk"],
    "warnings": [],
    "checks_performed": 5,
    "checks_passed": 4
}
```

---

## Test Scenario 6: Insufficient Margin

**Setup**: Position requires more margin than available
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,
    "take_profit": 1.0650,
    "lot_size": 10.0,            // Huge lot size
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 100      // Not enough
}
```

**Expected Response (HTTP 422)**:
```json
{
    "valid": false,
    "errors": ["Insufficient margin: need 200, have 100"],
    "warnings": [],
    "checks_performed": 6,
    "checks_passed": 5
}
```

---

## Test Scenario 7: Valid Signal (All Checks Pass)

**Setup**: Well-formed, realistic signal
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,         // 50 pips loss
    "take_profit": 1.0750,       // 200 pips gain (4:1 ratio)
    "lot_size": 0.1,             // 0.5% risk
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 200)**:
```json
{
    "valid": true,
    "errors": [],
    "warnings": [],
    "checks_performed": 6,
    "checks_passed": 6
}
```

---

## Test Scenario 8: Warning - RR Below 1:1

**Setup**: Valid signal but with unfavorable RR ratio
```json
{
    "account_id": "ACC_001",
    "symbol": "EURUSD",
    "entry_price": 1.0550,
    "stop_loss": 1.0500,         // 50 pips loss
    "take_profit": 1.0600,       // 50 pips gain (1:1 ratio)
    "lot_size": 0.1,
    "account_balance": 10000,
    "account_equity": 10500,
    "available_margin": 8000
}
```

**Expected Response (HTTP 200 with warning)**:
```json
{
    "valid": true,
    "errors": [],
    "warnings": ["Warning: RR ratio 1:1 or less (1.0:1)"],
    "checks_performed": 6,
    "checks_passed": 6
}
```

---

## Batch Testing Script (PHP/Laravel)

```php
// Create artisan test command
php artisan make:test SignalValidationTest

// In tests/Feature/SignalValidationTest.php:

public function test_invalid_symbol_rejected()
{
    $response = $this->postJson('/api/bot/validate-signal', [
        'account_id' => 'ACC_001',
        'symbol' => 'INVALID',
        'entry_price' => 1.0550,
        'stop_loss' => 1.0500,
        'take_profit' => 1.0650,
        'lot_size' => 0.1,
        'account_balance' => 10000,
        'account_equity' => 10500,
        'available_margin' => 8000,
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('valid', false)
        ->assertJsonPath('errors.0', 'Invalid symbol: INVALID');
}

public function test_valid_signal_accepted()
{
    $response = $this->postJson('/api/bot/validate-signal', [
        'account_id' => 'ACC_001',
        'symbol' => 'EURUSD',
        'entry_price' => 1.0550,
        'stop_loss' => 1.0500,
        'take_profit' => 1.0750,
        'lot_size' => 0.1,
        'account_balance' => 10000,
        'account_equity' => 10500,
        'available_margin' => 8000,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('valid', true);
}

public function test_excessive_lot_rejected()
{
    $response = $this->postJson('/api/bot/validate-signal', [
        'account_id' => 'ACC_001',
        'symbol' => 'EURUSD',
        'entry_price' => 1.0550,
        'stop_loss' => 1.0500,
        'take_profit' => 1.0650,
        'lot_size' => 10.0,  // Too large
        'account_balance' => 10000,
        'account_equity' => 10500,
        'available_margin' => 8000,
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('valid', false)
        ->assertJsonPath('errors.0', fn($str) => str_contains($str, 'exceeds max allowed'));
}
```

---

## Load Testing Validation Performance

```bash
# Using Apache Bench
ab -n 1000 -c 10 -p payload.json \
   -H "X-API-KEY: key" \
   http://localhost/api/bot/validate-signal

# Expected:
# - Response time: 50-150ms average
# - Throughput: 100+ requests/second
# - Error rate: 0%
```

---

## Integration Test: EA → Validation → Trade

```mql5
// In MySMC_EA strategy OnTick():

// 1. Detect signal internally
if (BOS_DETECTED) {
    entry = Ask;
    sl = entry - 50 * Point;
    tp = entry + 200 * Point;
    lot = 0.1;
    
    // 2. Validate before trading
    if (!ValidateSignalPreExecution(
            Symbol(),
            entry,
            sl,
            tp,
            lot,
            AccountInfoDouble(ACCOUNT_BALANCE),
            AccountInfoDouble(ACCOUNT_EQUITY),
            AccountInfoDouble(ACCOUNT_MARGIN_FREE)
        ))
    {
        Print("Validation failed, skipping trade");
        return;  // DO NOT TRADE
    }
    
    // 3. Only proceed if valid
    trade.Buy(lot, Symbol(), entry, sl, tp);
}
```

---

## Monitoring Validation Effectiveness

```sql
-- Daily validation stats
SELECT 
    DATE(rejected_at) as date,
    COUNT(*) as rejected_trades,
    ROUND(COUNT(*) * 100.0 / (
        (SELECT COUNT(*) FROM viomia_trade_outcomes 
         WHERE DATE(created_at) = DATE(rejected_at)) +
        COUNT(*)
    ), 2) as rejection_rate_percent
FROM trade_rejections
GROUP BY DATE(rejected_at)
ORDER BY date DESC;

-- Most common rejection reasons
SELECT 
    rejection_reason,
    COUNT(*) as frequency,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM trade_rejections), 2) as percentage
FROM trade_rejections
GROUP BY rejection_reason
ORDER BY frequency DESC;

-- Validation drift: check if rejected signals would have been profitable
SELECT 
    rejection_reason,
    AVG(ABS(CAST(JSON_EXTRACT(proposed_entry, '$.entry_price') AS DECIMAL(10,5)) -
            CAST(JSON_EXTRACT(proposed_entry, '$.tp_price') AS DECIMAL(10,5)))) as avg_potential_gain
FROM trade_rejections
GROUP BY rejection_reason;
```

---

## Rollout Strategy

### Phase 1: Monitor (0-10 trades)
- Log all validations, don't reject yet
- Understand validation patterns
- Adjust symbol/price ranges if needed

### Phase 2: Demo Trading (10-100 trades)
- Enable validation with rejections
- Monitor rejection rate (should be < 5%)
- Verify rejected signals would have been bad

### Phase 3: Live Trading (100+ trades)
- Deploy to live accounts
- Monitor daily rejection stats
- Alert if rejection rate > 10%
