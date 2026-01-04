# Viomia Bot Architecture Diagram

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      MQL5 Trading Bot                            │
│              (Running in MetaTrader 5 Platform)                  │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │        OPTIMIZED_BOT_MODULE.mq5                          │   │
│  │  • Trade Event Generation                                │   │
│  │  • Position Monitoring                                   │   │
│  │  • Performance Tracking                                  │   │
│  │  • Error Detection                                       │   │
│  │  • Status Management                                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│                            ▼                                      │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │        SendApiRequest() with Retry Logic                │   │
│  │  • 3 Retry Attempts                                      │   │
│  │  • 1000ms Delay Between Retries                          │   │
│  │  • 5000ms Connection Timeout                             │   │
│  │  • Safe JSON Escaping                                    │   │
│  │  • Fallback to CSV Logging                               │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
└────────────────────────────┼──────────────────────────────────────┘
                             │
                HTTP Request │ (X-API-KEY Header)
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Laravel Application                           │
│                  (Running on Port 8000)                          │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           API Routes (/api/bot/*)                        │   │
│  │  • /trade/opened                                         │   │
│  │  • /trade/log                                            │   │
│  │  • /trading/daily-summary                                │   │
│  │  • /position/update                                      │   │
│  │  • /alert/daily-loss-limit                               │   │
│  │  • /filter/blocked                                       │   │
│  │  • /signal/technical                                     │   │
│  │  • /ea/status-change                                     │   │
│  │  • /error/log                                            │   │
│  │  • /account/snapshot                                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│  ┌────────────────────────┴─────────────────────────────────┐   │
│  ▼                                                            │   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    CheckApiKey Middleware                                │   │
│  │  • Validates X-API-KEY Header                            │   │
│  │  • Checks API Key Status (active)                        │   │
│  │  • Returns 401 if Invalid                                │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│  ┌────────────────────────┴─────────────────────────────────┐   │
│  ▼                                                            │   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    8 Specialized Controllers                              │   │
│  │  • TradeEventController                                  │   │
│  │  • DailySummaryController                                │   │
│  │  • PositionUpdateController                              │   │
│  │  • LossLimitAlertController                              │   │
│  │  • FilterBlockController                                 │   │
│  │  • TechnicalSignalController                             │   │
│  │  • EaStatusChangeController                              │   │
│  │  • ErrorLogController                                    │   │
│  │  • TradeLogController (existing)                         │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│  ┌────────────────────────┴─────────────────────────────────┐   │
│  ▼                                                            │   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    ApiResponseFormatter Trait                            │   │
│  │  • parseRawJson()                                        │   │
│  │  • successResponse()                                     │   │
│  │  • errorResponse()                                       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│  ┌────────────────────────┴─────────────────────────────────┐   │
│  ▼                                                            │   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    8 Eloquent Models                                     │   │
│  │  • TradeEvent                                            │   │
│  │  • DailySummary                                          │   │
│  │  • PositionUpdate                                        │   │
│  │  • LossLimitAlert                                        │   │
│  │  • FilterBlock                                           │   │
│  │  • TechnicalSignal                                       │   │
│  │  • EaStatusChange                                        │   │
│  │  • ErrorLog                                              │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            │                                      │
│  ┌────────────────────────┴─────────────────────────────────┐   │
│  ▼                                                            │   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │    Database Layer (MySQL/PostgreSQL)                     │   │
│  │  • 8 New Tables                                          │   │
│  │  • 13 Total API-Related Tables                           │   │
│  │  • Unique Constraints                                    │   │
│  │  • Foreign Keys                                          │   │
│  │  • Indexes for Performance                               │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Diagram

```
┌────────────────────┐
│ Trade Execution    │
│ in MetaTrader      │
└────────────┬───────┘
             │
             ▼
┌────────────────────────────────────────┐
│ SendTradeOpened(ticket, direction...) │
│ Generates JSON Payload                 │
└────────────┬───────────────────────────┘
             │
             ▼
┌────────────────────────────────────────┐
│ SendApiRequest("POST",                 │
│   "/trade/opened", json)               │
└────────────┬───────────────────────────┘
             │
     ┌───────┴────────┐
     │                │
     ▼                ▼
┌─────────────┐   ┌──────────────┐
│ Request     │   │ 3 Retries    │
│ Succeeds    │   │ with backoff  │
└──────┬──────┘   └──────┬───────┘
       │                 │
       ▼                 ▼
┌─────────────────────────────┐
│ POST /api/bot/trade/opened  │
│ Headers: X-API-KEY          │
│ Body: JSON                  │
└──────┬──────────────────────┘
       │
       ▼
┌──────────────────────────────────┐
│ CheckApiKey Middleware           │
│ Validates API Key                │
└──────┬───────────────────────────┘
       │
       ▼
┌──────────────────────────────────┐
│ TradeEventController::store()     │
│ • Parse JSON                     │
│ • Validate Data                  │
│ • Check Duplicates               │
└──────┬───────────────────────────┘
       │
       ▼
┌──────────────────────────────────┐
│ TradeEvent Model                 │
│ • Create Record                  │
│ • Assign Timestamps              │
└──────┬───────────────────────────┘
       │
       ▼
┌──────────────────────────────────┐
│ MySQL Database                   │
│ INSERT INTO trade_events         │
└──────┬───────────────────────────┘
       │
       ▼
┌──────────────────────────────────┐
│ Response to Bot                  │
│ {                                │
│   "success": true,               │
│   "data": {"id": 45, ...}        │
│ }                                │
└──────────────────────────────────┘
```

---

## Database Schema Relationships

```
┌─────────────────────┐
│   accounts          │
├─────────────────────┤
│ id (PK)             │
│ user_id (FK)        │◄──┐
│ platform            │   │
│ server              │   │
│ login               │   │
│ password            │   │
│ active              │   │
│ connected           │   │
│ timestamps          │   │
└─────────────────────┘   │
     ▲   ▲   ▲   ▲   ▲    │
     │   │   │   │   └────┼──────────────────┐
     │   │   │   │        │                  │
  1──┼───┼───┼───┼────────┼──────────────┐   │
     │   │   │   │        │              │   │
┌────┴───┴───┴───┴────┐   │        ┌─────┴───┴──────┐
│ trade_events        │   │        │ daily_summaries │
├─────────────────────┤   │        ├─────────────────┤
│ id                  │   │        │ id              │
│ account_id (FK)     │───┼────────│ account_id (FK) │───┐
│ ticket (unique)     │   │        │ summary_date    │   │
│ direction           │   │        │ daily_pl        │   │
│ entry_price         │   │        │ trades_count    │   │
│ sl_price            │   │        │ win_rate_percent│   │
│ tp_price            │   │        │ timestamps      │   │
│ lot_size            │   │        └─────────────────┘   │
│ opened_at           │   │                              │
│ timestamps          │   │                              │
└─────────────────────┘   │                              │
                          │                              │
                          │ ┌──────────────────────────┐ │
                          │ │ position_updates         │ │
                          │ ├──────────────────────────┤ │
                          │ │ id                       │ │
                          │ │ account_id (FK)          │───┐
                          │ │ ticket                   │ │ │
                          │ │ entry_price              │ │ │
                          │ │ current_price            │ │ │
                          │ │ unrealized_pl            │ │ │
                          │ │ unrealized_pl_percent    │ │ │
                          │ │ lot_size                 │ │ │
                          │ │ timestamps               │ │ │
                          │ └──────────────────────────┘ │ │
                          │                              │ │
                          └──────────────────────────────┘ │
                                                           │
                          ┌──────────────────────────────┐ │
                          │ loss_limit_alerts            │ │
                          ├──────────────────────────────┤ │
                          │ id                           │ │
                          │ account_id (FK)              │───┐
                          │ daily_loss                   │ │ │
                          │ daily_loss_limit             │ │ │
                          │ limit_type                   │ │ │
                          │ balance                      │ │ │
                          │ equity                       │ │ │
                          │ timestamps                   │ │ │
                          └──────────────────────────────┘ │ │
                                                           │ │
                          ┌──────────────────────────────┐ │ │
                          │ filter_blocks                │ │ │
                          ├──────────────────────────────┤ │ │
                          │ id                           │ │ │
                          │ account_id (FK)              │───┤
                          │ filter_type                  │ │ │
                          │ block_reason                 │ │ │
                          │ blocked_at                   │ │ │
                          │ timestamps                   │ │ │
                          └──────────────────────────────┘ │ │
                                                           │ │
                          ┌──────────────────────────────┐ │ │
                          │ technical_signals            │ │ │
                          ├──────────────────────────────┤ │ │
                          │ id                           │ │ │
                          │ account_id (FK)              │───┤
                          │ trend_score                  │ │ │
                          │ choch_signal                 │ │ │
                          │ rsi_value                    │ │ │
                          │ atr_value                    │ │ │
                          │ ema_20, ema_50               │ │ │
                          │ timestamps                   │ │ │
                          └──────────────────────────────┘ │ │
                                                           │ │
                          ┌──────────────────────────────┐ │ │
                          │ ea_status_changes            │ │ │
                          ├──────────────────────────────┤ │ │
                          │ id                           │ │ │
                          │ account_id (FK)              │───┤
                          │ status                       │ │ │
                          │ reason                       │ │ │
                          │ consecutive_losses           │ │ │
                          │ balance, equity              │ │ │
                          │ positions_open               │ │ │
                          │ changed_at                   │ │ │
                          │ timestamps                   │ │ │
                          └──────────────────────────────┘ │ │
                                                           │ │
                          ┌──────────────────────────────┐ │ │
                          │ error_logs                   │ │ │
                          ├──────────────────────────────┤ │ │
                          │ id                           │ │ │
                          │ account_id (FK)              │───┘
                          │ error_type                   │
                          │ error_message                │
                          │ price_at_error               │
                          │ balance, equity              │
                          │ error_at                     │
                          │ timestamps                   │
                          └──────────────────────────────┘
```

---

## Request/Response Flow

### Success Flow
```
Bot                          Laravel API
│                            │
├─ POST /trade/opened ──────►│
│  Headers:                  │
│  X-API-KEY: key            │
│  Content-Type: json        │
│                            │
│                           ├─ CheckApiKey
│                           │  (Validate key)
│                           │
│                           ├─ TradeEventController
│                           │  ├─ parseRawJson()
│                           │  ├─ validator()
│                           │  └─ TradeEvent::create()
│                           │
│  ◄────────────────────────┤ {success: true,
│  200 OK                    │  message: "...",
│  {success: true,           │  data: {...}}
│   data: {...}}             │
│
```

### Error Flow (Retry)
```
Bot                          Laravel API
│                            │
├─ POST /trade/opened ──────►│ ❌ Connection Failed
│                            │
│ [Wait 1000ms]
│
├─ POST /trade/opened ──────►│ ❌ Request Timeout
│  (Retry 1/3)               │
│                            │
│ [Wait 1000ms]
│
├─ POST /trade/opened ──────►│ ✅ Success
│  (Retry 2/3)               │
│                           ├─ Process & Store
│                           │
│  ◄────────────────────────┤ {success: true}
│  201 Created               │
│
```

---

## API Endpoint Categories

```
┌─────────────────────────────────────────────────────────────┐
│                    /api/bot/* Endpoints                      │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ TRADE EXECUTION (Immediate)                            │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ POST /trade/opened    - Record new trade              │ │
│  │ POST /trade/log       - Record closed trade           │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ PERFORMANCE TRACKING (Periodic)                        │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ POST /position/update      - Live unrealized P/L      │ │
│  │ POST /trading/daily-summary - End of day summary      │ │
│  │ POST /account/snapshot      - Account state snapshot  │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ ALERTS & WARNINGS (Event-driven)                       │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ POST /alert/daily-loss-limit - Daily loss hit        │ │
│  │ POST /ea/status-change       - EA state changes      │ │
│  │ POST /error/log              - Error events          │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ TECHNICAL DATA (Analysis)                             │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ POST /signal/technical   - Indicator values          │ │
│  │ POST /filter/blocked     - Trade filter logs         │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ EXISTING ENDPOINTS (Already implemented)              │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ GET  /signal          - Get active signals           │ │
│  │ POST /signal          - Create new signal            │ │
│  │ GET  /bot/status      - Get latest bot status        │ │
│  │ POST /bot/status      - Update bot status            │ │
│  │ GET  /news/list       - Get all news events          │ │
│  │ POST /news/store      - Create news event            │ │
│  │ GET  /news/next       - Get next news event          │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## Retry & Fallback Strategy

```
┌──────────────────────────────────────┐
│ SendApiRequest()                     │
├──────────────────────────────────────┤
│                                      │
│ For attempt = 0 to 2:                │
│  ├─ Build Request                   │
│  ├─ Send WebRequest                 │
│  │                                  │
│  ├─ IF Success (return != -1)        │
│  │  └─ Return Response ✅            │
│  │                                  │
│  ├─ IF Failed (return == -1)         │
│  │  ├─ Log Error                    │
│  │  ├─ ResetLastError()              │
│  │  └─ IF not last attempt           │
│  │     └─ Sleep(1000ms)              │
│  │                                  │
│ └─ Continue to next attempt          │
│                                      │
│ IF all attempts failed:              │
│  ├─ Log Critical Failure             │
│  └─ Return -1                        │
│                                      │
│ Fallback: Save to CSV               │
│  └─ ResumeFromCSV on reconnect      │
│                                      │
└──────────────────────────────────────┘
```

---

## Summary

This architecture ensures:
- ✅ **Reliable Delivery** - Retry logic with exponential backoff
- ✅ **Data Integrity** - Unique constraints prevent duplicates
- ✅ **Performance** - Optimized queries with proper indexing
- ✅ **Security** - API key authentication on all endpoints
- ✅ **Scalability** - Foreign keys maintain referential integrity
- ✅ **Observability** - Comprehensive logging and error tracking
- ✅ **Resilience** - CSV fallback if API unavailable
