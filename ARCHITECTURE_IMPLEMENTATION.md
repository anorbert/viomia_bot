# Login & Dashboard Architecture

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         CLIENT (Browser)                                │
│                                                                          │
│  [Login Form] ────► [Phone + PIN] ────► [Submit]                       │
└────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                         ROUTING LAYER                                     │
│                                                                            │
│  routes/web.php                                                           │
│  ├─ GET  /login              → LoginController@showLoginForm             │
│  ├─ POST /login              → LoginController@login                     │
│  ├─ POST /logout             → LoginController@logout                    │
│  ├─ GET  /admin/dashboard    → AdminController@index  [auth, role:admin]│
│  └─ GET  /admin/bots/*       → BotController@*        [auth, role:admin]│
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                    MIDDLEWARE STACK (Global)                              │
│                                                                            │
│  1. CSRF Protection              ← All form submissions                   │
│  2. Session Management           ← Auth state                             │
│  3. CheckUserActive (Custom)     ← Account status check                   │
│  4. CheckRole (Custom)           ← Role-based access                      │
│  5. API Key Validation           ← Bot API endpoints                      │
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                         CONTROLLER LAYER                                   │
│                                                                            │
│  ┌─ LoginController                                                       │
│  │  ├─ showLoginForm()          → Display login view                      │
│  │  ├─ login()                  → Validate & authenticate                 │
│  │  │  ├─ Check phone number                                              │
│  │  │  ├─ Validate PIN                                                    │
│  │  │  ├─ Check is_active                                                 │
│  │  │  ├─ Update last_login_at                                            │
│  │  │  └─ Redirect by role                                                │
│  │  ├─ logout()                 → Terminate session                       │
│  │  └─ redirectBasedOnRole()    → Smart redirection                       │
│  │                                                                         │
│  ├─ AdminController                                                       │
│  │  └─ index()                 → Generate dashboard with:                 │
│  │     ├─ User statistics                                                 │
│  │     ├─ Trading metrics                                                 │
│  │     ├─ Bot health status                                               │
│  │     ├─ Recent errors                                                   │
│  │     ├─ Revenue chart data                                              │
│  │     └─ Active signals                                                  │
│  │                                                                         │
│  └─ BotController (Admin)                                                 │
│     ├─ index()                 → List all bots (paginated)                │
│     ├─ create()                → Bot creation form                         │
│     ├─ store()                 → Save new bot                              │
│     ├─ show($bot)              → Bot details & stats                       │
│     ├─ edit($bot)              → Edit form                                 │
│     ├─ update($bot)            → Save changes                              │
│     ├─ destroy($bot)           → Delete bot                                │
│     ├─ logs()                  → View bot logs                             │
│     ├─ settings()              → Bot settings                              │
│     └─ getStatus($botId)       → JSON status endpoint                      │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                        SERVICE LAYER                                       │
│                                                                            │
│  BotManagementService                                                     │
│  │                                                                         │
│  ├─ getBotStatus($bot)                                                    │
│  │  └─ Returns: id, name, balance, equity, pl, positions, health         │
│  │                                                                         │
│  ├─ getBotPerformance($bot, $days)                                        │
│  │  └─ Returns: total_trades, wins, losses, win_rate, profit              │
│  │                                                                         │
│  ├─ isBotHealthy($bot)                                                    │
│  │  └─ Checks: last_ping < 5 minutes                                      │
│  │                                                                         │
│  ├─ isBotActive($bot)                                                     │
│  │  └─ Checks: account.status = 'active'                                  │
│  │                                                                         │
│  ├─ getBotErrors($bot, $limit)                                            │
│  │  └─ Returns: recent error logs                                         │
│  │                                                                         │
│  ├─ getBotStatusChanges($bot, $limit)                                     │
│  │  └─ Returns: status change history                                     │
│  │                                                                         │
│  ├─ calculateUptimePercentage($bot, $days)                                │
│  │  └─ Returns: % time bot was running                                    │
│  │                                                                         │
│  ├─ getAllBotsSummary()                                                   │
│  │  └─ Returns: fleet stats (total, healthy, balance, equity, pl)         │
│  │                                                                         │
│  ├─ restartBot($bot)       → Log status change                            │
│  ├─ stopBot($bot)          → Log status change                            │
│  ├─ enableBot($bot)        → Update & log                                 │
│  └─ disableBot($bot)       → Update & log                                 │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                        MODEL LAYER                                         │
│                                                                            │
│  ┌─ User (Eloquent Model)                                                 │
│  │  ├─ id, name, email, phone_number, password                            │
│  │  ├─ role_id (FK → Role)                                                │
│  │  ├─ is_active (NEW)         ← Track account status                     │
│  │  ├─ last_login_at (NEW)     ← Track login time                         │
│  │  ├─ last_login_ip (NEW)     ← Track login IP                           │
│  │  └─ Methods: role()                                                    │
│  │                                                                         │
│  ├─ BotStatus (Eloquent Model)                                            │
│  │  ├─ id, account_id (FK)                                                │
│  │  ├─ name, strategy, description (NEW)                                  │
│  │  ├─ balance, equity, daily_pl, open_positions, max_dd                  │
│  │  ├─ enabled (NEW)                                                      │
│  │  ├─ max_daily_loss (NEW)                                               │
│  │  ├─ max_concurrent_positions (NEW)                                     │
│  │  ├─ trading_hours_start (NEW)                                          │
│  │  ├─ trading_hours_end (NEW)                                            │
│  │  ├─ last_ping (NEW)         ← Health check timestamp                   │
│  │  └─ Methods: account()                                                 │
│  │                                                                         │
│  ├─ TradeLog (Eloquent Model)                                             │
│  │  ├─ bot_status_id (FK)                                                 │
│  │  ├─ symbol, entry_price, exit_price, quantity                          │
│  │  ├─ profit_loss                                                        │
│  │  └─ Methods: bot()                                                     │
│  │                                                                         │
│  ├─ ErrorLog (Eloquent Model)                                             │
│  │  ├─ bot_status_id (FK)                                                 │
│  │  ├─ message, type                                                      │
│  │  └─ timestamp                                                          │
│  │                                                                         │
│  ├─ EaStatusChange (Eloquent Model)                                       │
│  │  ├─ bot_status_id (FK)                                                 │
│  │  ├─ old_status, new_status                                             │
│  │  ├─ reason                                                             │
│  │  └─ timestamp                                                          │
│  │                                                                         │
│  └─ DailySummary, SignalAccount, etc.                                     │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
┌─────────────────────────────────────────────────────────────────────────┴─┐
│                     DATABASE LAYER                                         │
│                                                                            │
│  ┌─ users                                                                  │
│  │  ├─ id, name, email, phone_number, password                            │
│  │  ├─ role_id (FK)                                                       │
│  │  ├─ is_active           ← NEW: Account status                          │
│  │  ├─ last_login_at       ← NEW: Audit trail                             │
│  │  ├─ last_login_ip       ← NEW: Security tracking                       │
│  │  └─ Indexes: phone_number, role_id, is_active                          │
│  │                                                                         │
│  ├─ bot_statuses                                                          │
│  │  ├─ id, account_id (FK)                                                │
│  │  ├─ balance, equity, daily_pl, open_positions, max_dd                  │
│  │  ├─ name, strategy, description                ← NEW: Config          │
│  │  ├─ enabled                                    ← NEW: Control          │
│  │  ├─ max_daily_loss                             ← NEW: Risk limit       │
│  │  ├─ max_concurrent_positions                   ← NEW: Risk limit       │
│  │  ├─ trading_hours_start/end                    ← NEW: Schedule         │
│  │  ├─ last_ping                                  ← NEW: Health           │
│  │  └─ Indexes: account_id, enabled, last_ping                            │
│  │                                                                         │
│  ├─ trade_logs                                                            │
│  │  ├─ id, bot_status_id (FK)                                             │
│  │  ├─ symbol, entry_price, exit_price, quantity, profit_loss            │
│  │  ├─ status (open, closed)                                              │
│  │  └─ Indexes: bot_status_id, created_at                                 │
│  │                                                                         │
│  ├─ error_logs                                                            │
│  │  ├─ id, bot_status_id (FK)                                             │
│  │  ├─ message, type                                                      │
│  │  └─ Indexes: bot_status_id, created_at                                 │
│  │                                                                         │
│  ├─ ea_status_changes                                                     │
│  │  ├─ id, bot_status_id (FK)                                             │
│  │  ├─ old_status, new_status, reason                                     │
│  │  └─ Indexes: bot_status_id, created_at                                 │
│  │                                                                         │
│  └─ audit_logs (NEW - OPTIONAL)                                           │
│     ├─ id, user_id (FK)                                                   │
│     ├─ action, model_type, model_id                                       │
│     ├─ changes (JSON)                                                     │
│     ├─ ip_address, user_agent                                             │
│     └─ Indexes: user_id, model_type, created_at                           │
│                                                                            │
└─────────────────────────────────────────────────────────────────────────┬─┘
                                                                          │
                                                                          │
                                   VIEW LAYER
                                   (Blade Templates)
                                        │
            ┌───────────────────────────┼───────────────────────────┐
            │                           │                           │
      login.blade.php          dashboard.blade.php          admin/bots/*.blade.php
      ├─ Phone input            ├─ Statistics cards          ├─ index (list)
      ├─ PIN input              ├─ Chart container          ├─ create (form)
      ├─ Remember checkbox      ├─ Recent errors            ├─ show (details)
      ├─ Submit button          ├─ Bot health               ├─ edit (form)
      └─ Error messages         ├─ Active signals           ├─ logs (table)
                                └─ Navigation               └─ settings (form)
```

## Data Flow Diagram

```
1. USER LOGIN FLOW
   ┌──────────────┐
   │ User at /login │
   └────────┬─────┘
            │
            ▼
   ┌──────────────────────────────┐
   │ Enter: Phone + PIN            │
   └────────┬─────────────────────┘
            │
            ▼
   ┌──────────────────────────────┐
   │ POST /login                   │
   │ ↓ LoginController@login()     │
   └────────┬─────────────────────┘
            │
    ┌───────┴────────┐
    │                │
    ▼                ▼
 SUCCESS          FAILURE
 (Valid)          (Invalid)
    │                │
    ▼                ▼
Check        ← Login Failed
is_active    ← Redirect to /login
    │         with error message
    │
 ┌──┴──┐
 │     │
YES   NO
 │     └─→ Auto Logout
 │         ↓
 │      Redirect to /login
 │
 ▼
Update last_login_at
    │
    ▼
redirectBasedOnRole()
    │
    ├─→ role_id = 1 → /admin/dashboard
    ├─→ role_id = 2 → /editor/dashboard
    └─→ role_id = 3 → /user/dashboard


2. ADMIN DASHBOARD FLOW
   ┌────────────────────────────────┐
   │ GET /admin/dashboard            │
   │ ↓ AdminController@index()       │
   └────────┬───────────────────────┘
            │
    ┌───────┴────────────────────┐
    │                            │
    ▼                            ▼
Check Auth            Check Role
(is logged in?)        (role_id = 1?)
    │                            │
   YES                          YES
    │                            │
    └────────────┬───────────────┘
                 │
                 ▼
    ┌──────────────────────────────┐
    │ AdminController Logic:        │
    │ ├─ Calculate user stats      │
    │ ├─ Calculate trade metrics   │
    │ ├─ Check bot health          │
    │ ├─ Get recent errors         │
    │ ├─ Generate chart data       │
    │ └─ Fetch active signals      │
    └────────┬───────────────────┘
             │
             ▼
    ┌──────────────────────────────┐
    │ Pass data to view:           │
    │ $stats                       │
    │ $revenueData                 │
    │ $recentErrors                │
    │ $botStatus                   │
    │ $activeSignals               │
    └────────┬───────────────────┘
             │
             ▼
    ┌──────────────────────────────┐
    │ Return dashboard.blade.php    │
    │ (Display with charts, cards) │
    └──────────────────────────────┘


3. BOT MANAGEMENT FLOW
   ┌────────────────────────────────┐
   │ GET /admin/bots                 │
   │ ↓ BotController@index()         │
   └────────┬───────────────────────┘
            │
            ▼
    ┌─────────────────────────┐
    │ Verify Auth & Role      │
    └────────┬────────────────┘
             │
             ▼
    ┌────────────────────────────────┐
    │ Query all bots with pagination │
    │ Load relationships: account     │
    └────────┬───────────────────────┘
             │
             ▼
    ┌────────────────────────────────┐
    │ For each bot, calculate:        │
    │ ├─ Health status               │
    │ ├─ Performance metrics          │
    │ ├─ Recent errors                │
    │ └─ Status change history        │
    └────────┬───────────────────────┘
             │
             ▼
    ┌────────────────────────────────┐
    │ Return bots.index.blade.php     │
    │ (Display bot list with status) │
    └──────────────────────────────┘


4. SERVICE LAYER PROCESSING
   BotManagementService
            │
    ┌───────┴──────────┐
    │                  │
    ▼                  ▼
Health Check      Performance Calc
├─ Check         ├─ Sum trades
│ last_ping      ├─ Count wins
├─ < 5 min?      ├─ Calculate %
└─ Return bool   └─ Return metrics
    │
    └─────→ Used by Controllers & Dashboard
```

## Security Flow Diagram

```
REQUEST LIFECYCLE
    │
    ▼
┌──────────────────────┐
│ Route Matching       │
│ (routes/web.php)     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Middleware Stack     │
│                      │
│ 1. CSRF Token Check  │─→ Validate token
│    (Framework)       │
│                      │
│ 2. Session Handler   │─→ Load session
│    (Framework)       │
│                      │
│ 3. CheckUserActive   │─→ Check is_active
│    (Custom)          │
│                      │
│ 4. CheckRole         │─→ Check role_id
│    (Custom)          │
│                      │
│ 5. API Key Check     │─→ Validate X-API-KEY
│    (Custom)          │
└──────────┬───────────┘
           │
    ┌──────┴──────┐
    │             │
   PASS          FAIL
    │             │
    ▼             ▼
Controller      Abort/Redirect
Logic           ├─ 401 Unauthorized
    │           ├─ 403 Forbidden
    │           └─ Error message
    ▼
Business
Logic
    │
    ▼
Database
Query
    │
    ▼
Response
(View/JSON)
```

## Component Interaction Diagram

```
                    ┌─────────────────┐
                    │   User Browser  │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
          Login          Dashboard       Bots
          Form           Page            Page
              │              │              │
              └──────────────┼──────────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
        LoginController  AdminController  BotController
              │              │              │
              └──────────────┼──────────────┘
                             │
                    ┌────────┴────────┐
                    │                 │
                    ▼                 ▼
              BotManagementService   Models
              ├─ getBotStatus()      ├─ User
              ├─ getBotPerformance() ├─ BotStatus
              ├─ isBotHealthy()      ├─ TradeLog
              ├─ getAllBotsSummary() ├─ ErrorLog
              └─ ...others           └─ ...others
                    │
                    ▼
              Database
              ├─ users
              ├─ bot_statuses
              ├─ trade_logs
              ├─ error_logs
              └─ ...tables
```

---

## Response Status Codes

```
200 OK             → Successful GET request
201 Created        → Successful POST (create)
204 No Content     → Successful DELETE
301 Redirect       → Login → Dashboard (by role)
302 Found          → Post-Redirect-Get pattern
304 Not Modified   → Cache hit
401 Unauthorized   → Invalid auth/API key
403 Forbidden      → Valid auth, wrong role
404 Not Found      → Resource doesn't exist
422 Unprocessable  → Validation failed
500 Server Error   → Unexpected error (logged)
503 Service Error  → Maintenance mode
```

---

**Architecture Created:** January 3, 2026
**Framework:** Laravel 11
**Status:** Production Ready
