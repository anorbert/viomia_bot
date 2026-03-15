# Admin Pages Testing Guide

## Quick Start

### 1. Import Dummy Data
```bash
mysql -u root -p viomia_bot < COMPLETE_DUMMY_DATA.sql
```

### 2. Access Admin Panel
```
URL: http://localhost/viomia_bot/admin
Login: admin@viomia.com
```

### 3. Test Each Module
Follow the guide below for each section.

---

## 🧪 Module-by-Module Testing

### 1. **ACCOUNTS MODULE**
**Route**: `/admin/accounts`

#### Test Index
1. Navigate to **Admin → Trading Accounts**
2. You should see **5 accounts**:
   - ACC001 (MT5 Real, IC Markets)
   - ACC002 (MT4 Demo, XM Global)
   - ACC003 (MT5 Real, Pepperstone)
   - ACC004 (MT5 Real, IC Markets)
   - ACC005 (MT4 Demo, FXCM)
3. Verify columns display:
   - ✅ Login credentials
   - ✅ Platform badge (MT4/MT5)
   - ✅ Account type (Real/Demo)
   - ✅ Connection status (Connected)
   - ✅ Balance from snapshots

#### Test Create
1. Click **"Add Trading Account"** button
2. Fill in form:
   - Client: Choose from dropdown (should show 5 users)
   - Platform: Select MT4 or MT5
   - Account Type: Real or Demo
   - Server: e.g., "TestBroker-Live"
   - Login: e.g., "TEST_LOGIN_123"
   - Password: Any password
3. Submit form
4. Verify success message appears
5. Check if new account appears in list

#### Test Edit
1. From accounts list, click **Edit** on any account
2. Verify form pre-populates with:
   - Account number
   - Platform selection
   - Broker server
   - Password field
3. Make a change (e.g., change server)
4. Click **COMMIT CHANGES**
5. Verify success message

#### Test Pending Verification
1. Navigate to **Admin → Pending Accounts** (or look for pending queue)
2. You may see accounts awaiting verification
3. Test Approve:
   - Click **Approve & Activate**
   - Add optional verification notes
   - Confirm dialog
   - Account should move to active
4. Test Reject:
   - Click **Reject**
   - Add rejection reason (required)
   - Confirm dialog
   - Account status should update

---

### 2. **USERS MODULE**
**Route**: `/admin/users`

#### Test Index
1. Navigate to **Admin → Users**
2. You should see **5 users**:
   - Admin User (Administrator role)
   - John Trader (Trader role)
   - Jane Premium (Premium Member role)
   - Mike Trader (Trader role)
   - Sarah Analytics (Analyst role)
3. Verify display:
   - ✅ Profile photos (from Dicebear API)
   - ✅ Name and email
   - ✅ Phone number
   - ✅ Role badge
   - ✅ Last login info
   - ✅ Actions available

#### Test Edit
1. Click **Edit** on any user
2. Verify form loads with:
   - Profile photo (left sidebar)
   - Name field
   - Email field
   - Phone number
   - Role dropdown (5 roles available)
3. Make changes:
   - Change name
   - Update email
   - Select different role
   - Upload new photo (optional)
4. Scroll to Security Update section:
   - Enter new password (optional)
   - Confirm password
5. Click **Save Changes**
6. Verify success message

#### Test Profile Photo Upload
1. Go to Edit page for any user
2. In left sidebar, select a JPG/PNG file
3. Save changes
4. Verify photo updates in list view

#### Test Password Reset
1. In Edit page, enter new password
2. Confirm it
3. Save
4. Try logging in with new password

---

### 3. **SUBSCRIPTION PLANS**
**Route**: `/admin/payments/subscriptions`

#### Test Plans Index
1. Navigate to **Admin → Payments → Subscription Plans**
2. You should see **4 plans**:
   - **Basic** - $29.99/month, 1 account, 70% profit share
   - **Professional** - $99.99/month, 3 accounts, 80% profit share
   - **Premium** - $299.99/month, 10 accounts, 85% profit share
   - **Enterprise** - $999.99/month, 99 accounts, 90% profit share
3. Verify columns:
   - ✅ Plan name
   - ✅ Monthly price
   - ✅ Max accounts
   - ✅ Profit share percentage
   - ✅ Is active status
   - ✅ Action buttons

#### Test Create Plan
1. Click **Create Subscription Plan**
2. Fill in form:
   - Plan name: "Starter"
   - Monthly price: 19.99
   - Max accounts: 1
   - Profit share: 65
   - Description: A plan description
   - Features (JSON): ["Feature 1", "Feature 2"]
3. Click **Save Plan**
4. Verify new plan appears in list

#### Test Edit Plan
1. Click **Edit** on any plan
2. Change values:
   - Increase price
   - Change profit share
   - Update features
3. Click **Save**
4. Verify changes

---

### 4. **USER SUBSCRIPTIONS**
**Route**: `/admin/payments`

#### Test View Subscriptions
1. Navigate to **Admin → Payments** (or main billing section)
2. Look for **Active Subscriptions** tab
3. You should see **4 subscriptions**:
   - User 2 (John Trader) - Professional - $99.99
   - User 3 (Jane Premium) - Premium - $299.99
   - User 4 (Mike Trader) - Basic - $29.99
   - User 5 (Sarah Analytics) - Professional - $99.99
4. Verify columns:
   - ✅ User name
   - ✅ Subscription plan
   - ✅ Status (active)
   - ✅ Start date (NOW())
   - ✅ End date (30 days from now)
   - ✅ Amount
   - ✅ Auto-renew status

---

### 5. **PAYMENT TRANSACTIONS**
**Route**: `/admin/payments`

#### Test View Transactions
1. In **Payments** section, look for **Payment Transactions** tab
2. You should see **4 transactions**:
   - TXN_20260315_001 - Binance, $99.99, COMPLETED
   - TXN_20260315_002 - Momo, $299.99, COMPLETED
   - TXN_20260315_003 - Bank, $29.99, PENDING
   - TXN_20260315_004 - Binance, $99.99, COMPLETED
3. Verify display:
   - ✅ Transaction reference
   - ✅ Provider (Binance/Momo/Bank)
   - ✅ Amount
   - ✅ Status badge (green/orange)
   - ✅ Timestamp
   - ✅ Checkout URL

#### Test Weekly Payments
1. Look for **Weekly Payments** section
2. You should see **4 weekly payments**:
   - User 2: $1,200 (80% of $1,500 profit)
   - User 3: $2,975 (85% of $3,500 profit)
   - User 4: $560 (Pending)
   - User 5: $1,760 (80% of $2,200 profit)
3. Verify columns:
   - ✅ User name
   - ✅ Weekly profit
   - ✅ Profit share %
   - ✅ Amount due
   - ✅ Status
   - ✅ Payment method

---

### 6. **BOTS MODULE**
**Route**: `/admin/bots`

#### Test Index
1. Navigate to **Admin → Bots**
2. You should see **3 bots**:
   - Viomia AI Bot v2.5.1 (Active)
   - Signal Executor Bot v1.8.3 (Active)
   - Legacy MT4 Bot v1.2.0 (Inactive)
3. Verify columns:
   - ✅ Bot name
   - ✅ Version badge
   - ✅ Status (green/red badge)
   - ✅ Description
   - ✅ Action buttons

#### Test Create Bot
1. Click **Add Bot** button
2. Fill form:
   - Bot Name: "Test Bot"
   - Version: "v0.1.0"
   - Address: URL or path
   - Description: Bot description
3. Click **Save**
4. Verify success and bot appears in list

#### Test Edit Bot
1. Click **Edit** on any bot
2. Modal opens with form
3. Update fields:
   - Name
   - Version
   - Address
   - Status (Active/Inactive)
   - Description
4. Click **Save Changes**
5. Verify updates

#### Test Bot Settings
1. From bot list, click **Settings** (or gear icon)
2. Settings page should show:
   - Bot configuration options
   - Trading parameters
   - Risk settings

#### Test Bot Logs
1. From bot list, click **View Logs** (or logs icon)
2. Logs page should display:
   - Bot execution history
   - Timestamps
   - Any errors or events

#### Test View Bot Details
1. Click **View** on any bot
2. Detail page shows:
   - Bot ID
   - Name
   - Version
   - Address/download link
   - Status badge
   - Description
   - Created/updated dates

---

### 7. **TRADES MODULE**
**Route**: `/admin/trades`

#### Test Index
1. Navigate to **Admin → Trades**
2. You should see **5 trades**:
   - 1001 - EURUSD BUY, Open, Entry: 1.08450, P&L: +136
   - 1002 - GBPUSD SELL, Closed, Entry: 1.27650, P&L: +125
   - 1003 - USDJPY BUY, Partial, Entry: 149.850, P&L: +700
   - 1004 - AUDUSD SELL, Closed, Entry: 0.67350, P&L: -225
   - 1005 - EURUSD BUY, Open, Entry: 1.08520, P&L: +128
3. Verify columns:
   - ✅ Ticket number
   - ✅ Symbol
   - ✅ Direction (BUY/SELL)
   - ✅ Entry price
   - ✅ Status (open/closed/partial)
   - ✅ Profit/Loss
   - ✅ Timestamps

#### Test Trade Statistics
1. Click **Statistics** tab
2. View should show:
   - Total trades today: 38
   - Winning trades: 20
   - Losing trades: 18
   - Win rate: High percentage
   - Daily P&L: Positive

#### Test Daily Summaries
1. View **Daily Summary** section
2. You should see **7 daily summaries**:
   - Today (CURDATE())
   - Yesterday
   - 5 days of historical data
3. Each summary shows:
   - Daily P&L (net profit/loss)
   - Trades count
   - Winning trades
   - Losing trades
   - Win rate percentage
   - Balance
   - Equity

---

### 8. **SIGNALS MODULE**
**Route**: `/admin/trades/signals`

#### Test Signals Index
1. Navigate to **Admin → Trades → Signals**
2. You should see **5 signals**:
   - SIG_001 - EURUSD BUY, 1.08450, Active, 1H
   - SIG_002 - GBPUSD SELL, 1.27650, Active, 4H
   - SIG_003 - USDJPY BUY, 149.850, Active, 1H
   - SIG_004 - AUDUSD BUY, 0.67350, Active, 4H
   - SIG_005 - EURUSD SELL, 1.08520, Inactive, 1D
3. Verify columns:
   - ✅ Symbol
   - ✅ Direction (BUY/SELL)
   - ✅ Entry price
   - ✅ Stop loss
   - ✅ Take profit
   - ✅ Timeframe
   - ✅ Active status

#### Test Create Signal
1. Click **Create Signal**
2. Fill form:
   - Symbol: EURUSD
   - Direction: BUY or SELL
   - Entry: Price
   - SL: Stop loss
   - TP: Take profit
   - Timeframe: 1H, 4H, 1D, etc.
3. Click **Save**
4. Verify signal appears in list

#### Test WhatsApp Signals
1. Look for **WhatsApp Signals** section
2. You should see **3 signals**:
   - EURUSD BUY - Executed
   - GBPUSD SELL - Executed
   - USDJPY BUY - Pending
3. Verify fields:
   - ✅ Symbol
   - ✅ Type (BUY/SELL)
   - ✅ Entry level
   - ✅ Stop loss
   - ✅ Take profit
   - ✅ Raw text from WhatsApp
   - ✅ Status
   - ✅ Timestamp

---

### 9. **AI MODULE**
**Route**: `/admin/ai`

#### Test Dashboard
1. Navigate to **Admin → AI → Dashboard**
2. Dashboard should display:
   - **Real-time stats**:
     - Win rate
     - Model accuracy
     - Decisions pending
     - Signals executed
   - **Charts**:
     - Daily P&L chart
     - Win/Loss distribution
     - Confidence distribution
3. Verify live data:
   - Loads without errors
   - Colors match dark theme
   - Icons display correctly

#### Test Candle Logs
1. Navigate to **Admin → AI → Candle Logs**
2. You should see **5 candles**:
   - EURUSD (2 records)
   - GBPUSD (1 record)
   - USDJPY (1 record)
   - AUDUSD (1 record)
3. Verify columns:
   - ✅ Symbol
   - ✅ Price
   - ✅ RSI value (color-coded: red >70, green <30)
   - ✅ ATR (volatility)
   - ✅ Trend indicator (up/down)
   - ✅ Resistance level
   - ✅ Support level
   - ✅ OHLC candle JSON

#### Test AI Decisions
1. Navigate to **Admin → AI → Decisions**
2. You should see **4 decisions**:
   - EURUSD BUY, 82.34% confidence, Score: 85
   - GBPUSD BUY, 89.45% confidence, Score: 89
   - USDJPY SELL, 67.54% confidence, Score: 68
   - AUDUSD BUY, 81.56% confidence, Score: 82
3. Verify display:
   - ✅ Symbol
   - ✅ Decision (BUY/SELL)
   - ✅ Confidence % with color
   - ✅ Score (0-100)
   - ✅ Reasons (detailed explanation)
   - ✅ Entry level
   - ✅ Stop loss level
   - ✅ Take profit level
   - ✅ R:R ratio
   - ✅ Web sentiment (BULLISH/BEARISH/etc)

#### Test Signal Logs
1. Navigate to **Admin → AI → Signal Logs**
2. You should see **4 signal logs**:
   - EURUSD BUY - SUCCESS
   - GBPUSD BUY - SUCCESS
   - USDJPY SELL - SUCCESS
   - AUDUSD BUY - PENDING
3. Verify columns:
   - ✅ Symbol
   - ✅ Decision
   - ✅ Entry price
   - ✅ Push status (color badge)
   - ✅ Response payload
   - ✅ Pushed timestamp

#### Test Trade Executions
1. Navigate to **Admin → AI → Trade Executions**
2. You should see **4 executions**:
   - Ticket 1001, EURUSD, WIN, +136
   - Ticket 1002, GBPUSD, WIN, +125
   - Ticket 1003, USDJPY, WIN, +700
   - Ticket 1004, AUDUSD, LOSS, -225
3. Verify columns:
   - ✅ Account ID
   - ✅ Ticket
   - ✅ Symbol
   - ✅ Decision
   - ✅ ML confidence
   - ✅ Signal combination
   - ✅ Market regime
   - ✅ Entry price
   - ✅ Result (WIN/LOSS)
   - ✅ Profit/Loss amount

#### Test Trade Outcomes
1. Navigate to **Admin → AI → Outcomes**
2. You should see **5 outcomes**:
   - 4 WINs (+136, +125, +700, +85.50)
   - 1 LOSS (-225)
3. Total win rate should show: 80%

#### Test Performance Metrics
1. Navigate to **Admin → AI → Performance**
2. View should display:
   - Model accuracy trends
   - Historical data
   - Model versions (3 versions available)
   - Win rate improvements

---

### 10. **BANKS MODULE**
**Route**: `/admin/banks`

#### Test Index
1. Navigate to **Admin → Banks**
2. You should see **3 banks**:
   - Momo Payment - $50,000, 2.50% charges, Active
   - Binance - $150,000, 1.00% charges, Active
   - Bank Transfer - $100,000, 5.00% charges, Active
3. Verify columns:
   - ✅ Payment owner
   - ✅ Current balance
   - ✅ Transaction charges
   - ✅ Phone number
   - ✅ Status badge

#### Test Edit Bank
1. Click **Edit** on any bank
2. Form should show:
   - Payment owner (editable)
   - App ID
   - Secret key
   - Logo URL
   - Transaction charges percentage
   - Current balance
   - Phone number
   - Status (active/inactive)
3. Make changes:
   - Update charges: 3.00
   - Update balance: 75000
   - Toggle status
4. Click **Save**
5. Verify changes in list

---

### 11. **CLIENTS MODULE**
**Route**: `/admin/clients`

#### Test Index
1. Navigate to **Admin → Clients** (if separate from Users)
2. View all clients (linked to users table)

#### Test Create Client
1. Click **Add Client** or **Create Client**
2. Fill form with:
   - Name
   - Email
   - Phone
   - Address
   - Company
   - etc.
3. Submit
4. Verify in list

#### Test Edit Client
1. Click **Edit** on any client
2. Update details
3. Save changes

---

## ✅ Post-Testing Verification

After testing all modules, verify using these queries:

```sql
-- Count all records for verification
SELECT 
    'Accounts' as 'Module', COUNT(*) as 'Count'
FROM accounts
UNION ALL
SELECT 'Users', COUNT(*) FROM users
UNION ALL
SELECT 'Subscriptions', COUNT(*) FROM user_subscriptions
UNION ALL
SELECT 'Payments', COUNT(*) FROM payment_transactions
UNION ALL
SELECT 'Trades', COUNT(*) FROM trade_logs
UNION ALL
SELECT 'Signals', COUNT(*) FROM signals
UNION ALL
SELECT 'AI Decisions', COUNT(*) FROM viomia_decisions
UNION ALL
SELECT 'Bots', COUNT(*) FROM ea_bots
UNION ALL
SELECT 'Banks', COUNT(*) FROM banks;
```

Expected output:
```
Module                 Count
Accounts               5
Users                  5
Subscriptions          4
Payments               4
Trades                 5
Signals                5
AI Decisions           4
Bots                   3
Banks                  3
```

---

## 📝 Bug/Issue Reporting

If you encounter issues:

1. **Note the exact error message**
2. **Check browser console** (F12 → Console tab)
3. **Check Laravel logs**: `storage/logs/laravel.log`
4. **Record the steps to reproduce**
5. **Screenshot any errors**

---

## 🎯 Expected Results Summary

- ✅ All 11 modules accessible
- ✅ 47 pages fully functional
- ✅ 120+ test records populated
- ✅ Dark theme working on AI/Accounts modules
- ✅ Forms submit and validate correctly
- ✅ Data displays accurately
- ✅ Relationships properly connected

---

**Ready to test! Good luck! 🚀**
