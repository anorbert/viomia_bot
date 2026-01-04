# Viomia Bot API Integration Guide

## Overview
This document outlines the complete integration between your MQL5 trading bot and the Laravel backend API for accurate data syncing.

---

## ✅ What's Been Created

### 1. **Database Migrations** (8 new tables)
- `trade_events` - Record of all trades opened
- `daily_summaries` - Daily P/L and trading statistics
- `position_updates` - Real-time unrealized P/L tracking
- `loss_limit_alerts` - Daily loss limit breaches
- `filter_blocks` - Session/filter blocks that prevented trades
- `technical_signals` - Technical indicator snapshots
- `ea_status_changes` - EA state changes (running, paused, etc)
- `error_logs` - Bot errors and exceptions

### 2. **Models** (8 Eloquent models)
All models support:
- Mass assignment via `$fillable`
- Automatic timestamp tracking
- Relationship to `Account` model
- Proper casting of datetime fields

### 3. **Controllers** (8 API endpoints)
Each controller:
- Validates incoming JSON data
- Handles raw MQL5 JSON parsing
- Uses trait for consistent response formatting
- Includes comprehensive error logging

### 4. **API Routes** (13 endpoints total)
All routes are prefixed with `/api/bot` and protected by `CheckApiKey` middleware:

```
POST   /api/bot/trade/opened           - Record new trade
POST   /api/bot/trade/log              - Record closed trade (already existed)
POST   /api/bot/trading/daily-summary  - Store daily P/L summary
POST   /api/bot/position/update        - Update unrealized P/L
POST   /api/bot/alert/daily-loss-limit - Alert on loss limit hit
POST   /api/bot/filter/blocked         - Log trade filters
POST   /api/bot/signal/technical       - Store technical indicators
POST   /api/bot/ea/status-change       - Log EA status changes
POST   /api/bot/error/log              - Log errors
```

### 5. **Security**
- Implemented `CheckApiKey` middleware
- Validates `X-API-KEY` header on all endpoints
- Checks API key exists and is `active` status in database

---

## 🚀 Setup Instructions

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Create API Key
```bash
php artisan tinker
```

Then in Tinker:
```php
App\Models\ApiKey::create([
    'key' => 'TEST_API_KEY_123',
    'description' => 'Development Bot Key',
    'status' => 'active'
]);
```

### Step 3: Update MQL5 Bot
Replace your bot's API module with the optimized version provided in `OPTIMIZED_BOT_MODULE.mq5`

Key improvements:
- ✅ Retry logic with exponential backoff
- ✅ Safe JSON escaping for special characters
- ✅ Connection timeout handling
- ✅ Fallback to CSV for backtesting
- ✅ Comprehensive logging

### Step 4: Configuration
In your MQL5 bot, update:
```cpp
string API_KEY = "TEST_API_KEY_123";
string API_BASE_URL = "http://127.0.0.1:8000/api/bot";
bool ENABLE_SYNC = true;
bool ENABLE_LOGGING = true;
```

---

## 📊 API Request/Response Format

### Request Headers (All POST/GET)
```
Content-Type: application/json
X-API-KEY: TEST_API_KEY_123
Accept: application/json
User-Agent: ViomiaBot/1.0
```

### Example: Send Trade Opened
**Request:**
```json
POST /api/bot/trade/opened
{
    "ticket": 123456789,
    "direction": "BUY",
    "entry_price": 1.23456,
    "sl_price": 1.23000,
    "tp_price": 1.24000,
    "lot_size": 1.5,
    "signal_source": "EURUSD_SIGNAL",
    "opened_at": "2025-01-03 14:30:45"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Trade event recorded successfully",
    "data": {
        "id": 45,
        "ticket": "123456789"
    }
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Invalid JSON format",
    "details": null
}
```

---

## 📋 All Endpoints Reference

### 1. Trade Events
**POST** `/api/bot/trade/opened`
```json
{
    "ticket": "string|unique",
    "direction": "BUY|SELL",
    "entry_price": "numeric",
    "sl_price": "numeric",
    "tp_price": "numeric",
    "lot_size": "numeric",
    "signal_source": "string|nullable",
    "opened_at": "Y-m-d H:i:s"
}
```

### 2. Daily Summary
**POST** `/api/bot/trading/daily-summary`
```json
{
    "daily_pl": "numeric",
    "trades_count": "integer",
    "winning_trades": "integer",
    "losing_trades": "integer",
    "win_rate_percent": "numeric",
    "balance": "numeric",
    "equity": "numeric",
    "summary_date": "Y-m-d",
    "captured_at": "Y-m-d H:i:s"
}
```

### 3. Position Update
**POST** `/api/bot/position/update`
```json
{
    "ticket": "string",
    "entry_price": "numeric",
    "current_price": "numeric",
    "unrealized_pl": "numeric",
    "unrealized_pl_percent": "numeric",
    "lot_size": "numeric",
    "updated_at": "Y-m-d H:i:s"
}
```

### 4. Loss Limit Alert
**POST** `/api/bot/alert/daily-loss-limit`
```json
{
    "daily_loss": "numeric",
    "daily_loss_limit": "numeric",
    "limit_type": "USD|PERCENT",
    "balance": "numeric",
    "equity": "numeric",
    "alert_at": "Y-m-d H:i:s"
}
```

### 5. Filter Block
**POST** `/api/bot/filter/blocked`
```json
{
    "filter_type": "ASIA|LONDON_DISABLED|NEWS|CORRELATION",
    "block_reason": "string",
    "blocked_at": "Y-m-d H:i:s"
}
```

### 6. Technical Signal
**POST** `/api/bot/signal/technical`
```json
{
    "trend_score": "numeric",
    "choch_signal": "BULLISH_REVERSAL|BEARISH_REVERSAL|NO_REVERSAL",
    "rsi_value": "numeric",
    "atr_value": "numeric",
    "ema_20": "numeric",
    "ema_50": "numeric",
    "signal_description": "string",
    "captured_at": "Y-m-d H:i:s"
}
```

### 7. EA Status Change
**POST** `/api/bot/ea/status-change`
```json
{
    "status": "RUNNING|PAUSED|ERROR_STOP|DAILY_LOSS_HIT",
    "reason": "string",
    "consecutive_losses": "integer",
    "balance": "numeric",
    "equity": "numeric",
    "positions_open": "integer",
    "changed_at": "Y-m-d H:i:s"
}
```

### 8. Error Log
**POST** `/api/bot/error/log`
```json
{
    "error_type": "string",
    "error_message": "string",
    "price_at_error": "numeric|null",
    "balance": "numeric",
    "equity": "numeric",
    "error_at": "Y-m-d H:i:s"
}
```

### 9. Account Snapshot (Existing)
**POST** `/api/bot/account/snapshot`
```json
{
    "account": "integer",
    "balance": "numeric",
    "equity": "numeric",
    "margin": "numeric",
    "free_margin": "numeric",
    "positions": "integer",
    "reason": "string",
    "captured_at": "Y-m-d H:i:s"
}
```

### 10. Trade Log (Existing)
**POST** `/api/bot/trade/log`
```json
{
    "ticket": "integer|unique",
    "profit": "numeric",
    "reason": "string"
}
```

---

## 🔧 Data Sync Best Practices

### 1. **Connection Reliability**
- Bot has 3 retry attempts with 1000ms delay
- Timeout set to 5 seconds
- Falls back to CSV logging if API fails
- Always logs API failures for debugging

### 2. **Data Accuracy**
- Use unique constraint on `ticket` field (prevents duplicates)
- Timestamps always use `Y-m-d H:i:s` format
- All numeric fields use proper precision (15,5 for prices)
- Account snapshots use `updateOrCreate` to prevent duplicates

### 3. **Frequency Guidelines**
```
Trade Opened     → Send immediately when trade opens
Trade Closed     → Send immediately when trade closes
Position Update  → Send every 5-10 seconds (configurable)
Account Snapshot → Send every 30 minutes or on demand
Daily Summary    → Send once per day at end of session
Technical Signal → Send with each signal generation
EA Status Change → Send on status changes only
Error Events     → Send on errors (not frequent)
Filter Blocks    → Send when filter triggers
Loss Limit Alert → Send when limit hit (critical)
```

### 4. **Error Handling**
- All endpoints return `success: true/false` flag
- Error responses include detailed messages
- Validation errors include field-level details
- All errors are logged on both sides

---

## 🧪 Testing the Integration

### Test Trade Opened
```bash
curl -X POST http://127.0.0.1:8000/api/bot/trade/opened \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "ticket": "123456",
    "direction": "BUY",
    "entry_price": 1.2345,
    "sl_price": 1.2300,
    "tp_price": 1.2400,
    "lot_size": 1.0,
    "signal_source": "MANUAL",
    "opened_at": "2025-01-03 14:30:00"
  }'
```

### Test Daily Summary
```bash
curl -X POST http://127.0.0.1:8000/api/bot/trading/daily-summary \
  -H "X-API-KEY: TEST_API_KEY_123" \
  -H "Content-Type: application/json" \
  -d '{
    "daily_pl": 150.00,
    "trades_count": 5,
    "winning_trades": 3,
    "losing_trades": 2,
    "win_rate_percent": 60.0,
    "balance": 10150.00,
    "equity": 10150.00,
    "summary_date": "2025-01-03",
    "captured_at": "2025-01-03 17:00:00"
  }'
```

---

## 📦 Database Schema Summary

```
trade_events
├── id (PK)
├── account_id (FK)
├── ticket (unique)
├── direction (enum: BUY, SELL)
├── entry_price
├── sl_price
├── tp_price
├── lot_size
├── signal_source
├── opened_at
└── timestamps

daily_summaries
├── id (PK)
├── account_id (FK)
├── summary_date (unique with account_id)
├── daily_pl
├── trades_count
├── winning_trades
├── losing_trades
├── win_rate_percent
├── balance
├── equity
├── captured_at
└── timestamps

[... and 6 more tables with similar structure]
```

---

## 🎯 Next Steps

1. ✅ Run migrations
2. ✅ Create API key in database
3. ✅ Update bot with optimized module
4. ✅ Test endpoints with curl
5. ⏳ Monitor logs during trading
6. ⏳ Create dashboard to visualize data
7. ⏳ Set up alerts for critical events
8. ⏳ Add user notifications
9. ⏳ Implement data cleanup jobs

---

## 🐛 Debugging

Enable logging in bot:
```cpp
bool ENABLE_LOGGING = true;  // Set to true for debugging
```

Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

Check API requests:
```bash
SELECT * FROM error_logs ORDER BY created_at DESC LIMIT 10;
```

---

## 📞 Support

All endpoints are RESTful and follow Laravel conventions.
All controllers use consistent error handling and response formatting.
All models include proper relationships and casting.
