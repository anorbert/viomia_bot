# Quick Reference: Viomia Bot API Commands

## Database Commands

### Run Migrations
```bash
php artisan migrate
```

### Rollback Last Migration
```bash
php artisan migrate:rollback
```

### Reset All Migrations
```bash
php artisan migrate:reset
```

---

## API Key Management

### Create API Key (in Tinker)
```bash
php artisan tinker
```

```php
App\Models\ApiKey::create([
    'key' => 'your_unique_key_here',
    'description' => 'Production Bot Key',
    'status' => 'active'
]);

// List all keys
App\Models\ApiKey::all();

// Disable a key
App\Models\ApiKey::where('key', 'old_key')->update(['status' => 'inactive']);
```

---

## Testing Endpoints with curl

### 1. Trade Opened
```bash
curl -X POST http://127.0.0.1:8000/api/bot/trade/opened \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "ticket": "123456789",
    "direction": "BUY",
    "entry_price": 1.23456,
    "sl_price": 1.23000,
    "tp_price": 1.24000,
    "lot_size": 1.5,
    "signal_source": "EA_SIGNAL",
    "opened_at": "2025-01-03 14:30:45"
  }'
```

### 2. Trade Closed (Log)
```bash
curl -X POST http://127.0.0.1:8000/api/bot/trade/log \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "ticket": 123456789,
    "profit": 150.50,
    "reason": "TP_HIT"
  }'
```

### 3. Daily Summary
```bash
curl -X POST http://127.0.0.1:8000/api/bot/trading/daily-summary \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "daily_pl": 250.00,
    "trades_count": 5,
    "winning_trades": 3,
    "losing_trades": 2,
    "win_rate_percent": 60.0,
    "balance": 10250.00,
    "equity": 10250.00,
    "summary_date": "2025-01-03",
    "captured_at": "2025-01-03 17:00:00"
  }'
```

### 4. Position Update
```bash
curl -X POST http://127.0.0.1:8000/api/bot/position/update \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "ticket": "123456789",
    "entry_price": 1.23456,
    "current_price": 1.23556,
    "unrealized_pl": 50.00,
    "unrealized_pl_percent": 0.81,
    "lot_size": 1.5,
    "updated_at": "2025-01-03 14:35:00"
  }'
```

### 5. Loss Limit Alert
```bash
curl -X POST http://127.0.0.1:8000/api/bot/alert/daily-loss-limit \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "daily_loss": -500.00,
    "daily_loss_limit": -500.00,
    "limit_type": "USD",
    "balance": 9500.00,
    "equity": 9500.00,
    "alert_at": "2025-01-03 14:45:00"
  }'
```

### 6. Filter Block
```bash
curl -X POST http://127.0.0.1:8000/api/bot/filter/blocked \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "filter_type": "NEWS",
    "block_reason": "High impact news event within 1 hour",
    "blocked_at": "2025-01-03 14:50:00"
  }'
```

### 7. Technical Signal
```bash
curl -X POST http://127.0.0.1:8000/api/bot/signal/technical \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "trend_score": 0.75,
    "choch_signal": "BULLISH_REVERSAL",
    "rsi_value": 65.5,
    "atr_value": 0.00125,
    "ema_20": 1.23500,
    "ema_50": 1.23400,
    "signal_description": "Strong bullish momentum with RSI above 60",
    "captured_at": "2025-01-03 15:00:00"
  }'
```

### 8. EA Status Change
```bash
curl -X POST http://127.0.0.1:8000/api/bot/ea/status-change \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "PAUSED",
    "reason": "Daily loss limit reached",
    "consecutive_losses": 3,
    "balance": 9500.00,
    "equity": 9500.00,
    "positions_open": 0,
    "changed_at": "2025-01-03 14:45:00"
  }'
```

### 9. Error Log
```bash
curl -X POST http://127.0.0.1:8000/api/bot/error/log \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "error_type": "INVALID_ORDER",
    "error_message": "OrderSend failed: insufficient margin",
    "price_at_error": 1.23456,
    "balance": 5000.00,
    "equity": 4999.50,
    "error_at": "2025-01-03 15:05:00"
  }'
```

### 10. Account Snapshot
```bash
curl -X POST http://127.0.0.1:8000/api/bot/account/snapshot \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "account": 2155555,
    "balance": 10000.00,
    "equity": 10150.00,
    "margin": 1500.00,
    "free_margin": 8650.00,
    "positions": 1,
    "reason": "Scheduled snapshot",
    "captured_at": "2025-01-03 15:30:00"
  }'
```

---

## Database Queries

### View Recent Trade Events
```bash
php artisan tinker
```

```php
App\Models\TradeEvent::latest()->limit(10)->get();
```

### View Daily Summary
```php
App\Models\DailySummary::where('summary_date', '2025-01-03')->get();
```

### View Position Updates
```php
App\Models\PositionUpdate::latest()->limit(5)->get();
```

### View Errors
```php
App\Models\ErrorLog::latest()->limit(10)->get();
```

### View Loss Limit Alerts
```php
App\Models\LossLimitAlert::latest()->limit(5)->get();
```

### View EA Status Changes
```php
App\Models\EaStatusChange::latest()->limit(10)->get();
```

### Count Trades by Day
```php
App\Models\TradeEvent::whereDate('opened_at', '2025-01-03')->count();
```

### Calculate Daily P/L
```php
App\Models\DailySummary::where('summary_date', '2025-01-03')->sum('daily_pl');
```

---

## Logs and Debugging

### Tail Laravel Log
```bash
tail -f storage/logs/laravel.log
```

### Filter API Errors Only
```bash
grep "error\|Error\|ERROR" storage/logs/laravel.log | tail -50
```

### Check Database Errors
```bash
php artisan logs:tail
```

### Monitor Real-time Traffic
```bash
tail -f storage/logs/laravel.log | grep "bot"
```

---

## MQL5 Bot Configuration

### In your EA file:
```cpp
// Configuration
string API_KEY = "TEST_API_KEY_123";
string API_BASE_URL = "http://127.0.0.1:8000/api/bot";
bool ENABLE_SYNC = true;          // Enable/disable all API calls
bool ENABLE_LOGGING = true;       // Enable/disable detailed logging

// Include the optimized module
#include "OPTIMIZED_BOT_MODULE.mq5"

// Usage examples:
SendTradeOpened(ticket, true, 1.234, 1.23, 1.235, 1.0, "SIGNAL_NAME");
SendAccountSnapshot("status_update");
SendDailySummary(150.50, 5, 3, 2, 60.0, "2025-01-03");
SendEAStatusChange("RUNNING", "Bot started", 0);
```

---

## Performance Monitoring

### Check API Response Times
```bash
time curl -X GET http://127.0.0.1:8000/api/bot/news/next \
  -H "X-API-KEY: TEST_API_KEY_123"
```

### Database Query Logging
```php
// In config/logging.php, enable query logging:
DB::listen(function ($query) {
    Log::info($query->sql);
});
```

### Monitor Database Connections
```bash
mysql -u root -p
SELECT * FROM information_schema.PROCESSLIST;
```

---

## Maintenance Commands

### Clean Old Logs
```bash
php artisan logs:clear
```

### Optimize Database
```bash
php artisan optimize
php artisan optimize:clear
```

### Cache Config
```bash
php artisan config:cache
```

### Reset Cache
```bash
php artisan cache:clear
```

---

## Deployment Checklist

- [ ] Update `API_KEY` in bot code
- [ ] Update `API_BASE_URL` to production URL
- [ ] Set `ENABLE_SYNC = true`
- [ ] Run `php artisan migrate` on server
- [ ] Create API key in production database
- [ ] Test at least 3 endpoints with curl
- [ ] Monitor logs for 1 hour
- [ ] Verify data in database
- [ ] Set up backup automation
- [ ] Configure error notifications

---

## Troubleshooting

### "Invalid API Key" Error
```php
// Check if key exists and is active
App\Models\ApiKey::where('status', 'active')->get();

// Activate key if needed
App\Models\ApiKey::where('key', 'your_key')->update(['status' => 'active']);
```

### "Connection Refused" in Bot
```cpp
// Check if Laravel is running
// Check API_BASE_URL is correct
// Check firewall allows port 8000
// Check X-API-KEY header is being sent
```

### "Validation Failed" Error
```bash
# Review error response details:
curl ... | jq '.details'
# Check all required fields are present
# Check field types match specification
```

### "Duplicate Entry" Error
```php
// Check if ticket already exists
App\Models\TradeEvent::where('ticket', '123456')->exists();
// Bot should use updateOrCreate for updates
```

---

## Support Resources

- Laravel Documentation: https://laravel.com/docs
- REST API Best Practices: https://restfulapi.net/
- MQL5 WebRequest: https://www.mql5.com/en/docs/network_functions/webrequest
- cURL Manual: https://curl.se/docs/
