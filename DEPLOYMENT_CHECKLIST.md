# Viomia Bot Integration Checklist

## Pre-Deployment Phase

### Database Setup
- [ ] Verify Laravel database connection in `.env`
- [ ] Run `php artisan migrate`
- [ ] Verify all 8 new tables created:
  - [ ] `trade_events`
  - [ ] `daily_summaries`
  - [ ] `position_updates`
  - [ ] `loss_limit_alerts`
  - [ ] `filter_blocks`
  - [ ] `technical_signals`
  - [ ] `ea_status_changes`
  - [ ] `error_logs`
- [ ] Run `php artisan tinker` to verify tables exist

### API Key Setup
- [ ] Create API key in database via Tinker:
  ```php
  App\Models\ApiKey::create([
      'key' => 'your_secure_key_here',
      'description' => 'Production Bot Key',
      'status' => 'active'
  ]);
  ```
- [ ] Verify API key was created
- [ ] Save API key securely (password manager)

### File Creation Verification
- [ ] Verify 8 models created in `app/Models/`:
  - [ ] TradeEvent.php
  - [ ] DailySummary.php
  - [ ] PositionUpdate.php
  - [ ] LossLimitAlert.php
  - [ ] FilterBlock.php
  - [ ] TechnicalSignal.php
  - [ ] EaStatusChange.php
  - [ ] ErrorLog.php

- [ ] Verify 8 controllers created in `app/Http/Controllers/Bot/`:
  - [ ] TradeEventController.php
  - [ ] DailySummaryController.php
  - [ ] PositionUpdateController.php
  - [ ] LossLimitAlertController.php
  - [ ] FilterBlockController.php
  - [ ] TechnicalSignalController.php
  - [ ] EaStatusChangeController.php
  - [ ] ErrorLogController.php

- [ ] Verify middleware updated:
  - [ ] CheckApiKey.php (fully implemented)

- [ ] Verify trait created:
  - [ ] ApiResponseFormatter.php

- [ ] Verify routes updated:
  - [ ] routes/api.php (includes all new routes)

- [ ] Verify bot module created:
  - [ ] OPTIMIZED_BOT_MODULE.mq5

### Documentation Verification
- [ ] API_INTEGRATION_GUIDE.md created
- [ ] IMPLEMENTATION_SUMMARY.md created
- [ ] QUICK_REFERENCE.md created
- [ ] ARCHITECTURE_DIAGRAM.md created

---

## Development Phase

### Laravel API Testing

#### Test Each Endpoint
- [ ] **Trade Opened**
  ```bash
  curl -X POST http://127.0.0.1:8000/api/bot/trade/opened \
    -H "X-API-KEY: your_key" \
    -H "Content-Type: application/json" \
    -d '{...}'
  ```
  - [ ] Returns 201 Created
  - [ ] Data saved to `trade_events` table

- [ ] **Daily Summary**
  ```bash
  curl -X POST http://127.0.0.1:8000/api/bot/trading/daily-summary \
    -H "X-API-KEY: your_key" \
    -H "Content-Type: application/json" \
    -d '{...}'
  ```
  - [ ] Returns 201 Created
  - [ ] Updates existing record if already exists

- [ ] **Position Update**
  ```bash
  curl -X POST http://127.0.0.1:8000/api/bot/position/update \
    -H "X-API-KEY: your_key" \
    -H "Content-Type: application/json" \
    -d '{...}'
  ```
  - [ ] Returns 200 OK
  - [ ] Uses updateOrCreate (no duplicates)

- [ ] **Loss Limit Alert**
  - [ ] Returns 201 Created
  - [ ] Data saved correctly

- [ ] **Filter Block**
  - [ ] Returns 201 Created
  - [ ] Data saved correctly

- [ ] **Technical Signal**
  - [ ] Returns 201 Created
  - [ ] Data saved correctly

- [ ] **EA Status Change**
  - [ ] Returns 201 Created
  - [ ] Data saved correctly

- [ ] **Error Log**
  - [ ] Returns 201 Created
  - [ ] Data saved correctly

#### Test Error Handling
- [ ] **Missing API Key**
  - [ ] Returns 401 Unauthorized
  - [ ] Message: "API key is required"

- [ ] **Invalid API Key**
  - [ ] Returns 401 Unauthorized
  - [ ] Message: "Invalid or inactive API key"

- [ ] **Invalid JSON**
  - [ ] Returns 400 Bad Request
  - [ ] Message: "Invalid JSON format"

- [ ] **Missing Required Fields**
  - [ ] Returns 422 Unprocessable Entity
  - [ ] Shows field-level errors

- [ ] **Duplicate Ticket**
  - [ ] Returns appropriate response
  - [ ] Prevents duplicate insertion

### Database Verification
- [ ] Verify data persists after API calls
  ```php
  // In Tinker:
  App\Models\TradeEvent::latest()->first();
  App\Models\DailySummary::all();
  // etc.
  ```

- [ ] Verify timestamps are correct
- [ ] Verify relationships work:
  ```php
  App\Models\TradeEvent::with('account')->first();
  ```

- [ ] Check indexes on frequently queried columns
- [ ] Verify unique constraints working:
  - [ ] Duplicate ticket rejected
  - [ ] Duplicate (account_id, summary_date) updated

### Performance Testing
- [ ] Test response time for single request: < 200ms
- [ ] Test response time for batch requests: < 100ms each
- [ ] Load test with 100 requests/min: No errors
- [ ] Load test with 1000 requests/min: Acceptable response time

---

## Integration Phase

### MQL5 Bot Configuration

#### Update Bot Code
- [ ] Copy `OPTIMIZED_BOT_MODULE.mq5` content to bot
- [ ] Update configuration variables:
  ```cpp
  string API_KEY = "your_actual_key";
  string API_BASE_URL = "http://127.0.0.1:8000/api/bot";  // or production URL
  bool ENABLE_SYNC = true;
  bool ENABLE_LOGGING = true;
  ```

- [ ] Verify all function calls exist:
  - [ ] SendTradeOpened()
  - [ ] SendClosedTrade()
  - [ ] SendAccountSnapshot()
  - [ ] SendDailySummary()
  - [ ] SendPositionUpdate()
  - [ ] SendDailyLossLimitHit()
  - [ ] SendSessionFilterBlock()
  - [ ] SendTechnicalSignals()
  - [ ] SendEAStatusChange()
  - [ ] SendErrorEvent()

#### Test Bot in Backtesting Mode
- [ ] Compile bot without errors
- [ ] Run backtest with ENABLE_SYNC = false
- [ ] Verify bot logic unchanged by new code
- [ ] Check execution speed not degraded

#### Test Bot in Demo Mode
- [ ] Verify `#ifdef __TESTER__` bypasses API calls
- [ ] Place demo trade manually
- [ ] Monitor MetaTrader logs for errors
- [ ] Check if any API calls made (should be none in demo)

### Live API Integration Testing

#### In Safe Environment (Demo Account)
- [ ] Set ENABLE_SYNC = true
- [ ] Set ENABLE_LOGGING = true
- [ ] Check API URL points to development server
- [ ] Monitor logs for API calls
- [ ] Place test trade
- [ ] Verify POST /api/bot/trade/opened received
- [ ] Verify data in database

#### Retry Logic Testing
- [ ] Simulate API timeout:
  - [ ] Start bot with API down
  - [ ] Bot should retry 3 times
  - [ ] Logs should show retry attempts
  - [ ] Bot should continue after failures

- [ ] Test recovery:
  - [ ] Start API again
  - [ ] Bot should successfully send on next trade

#### Error Scenario Testing
- [ ] Test with invalid API key:
  - [ ] Should receive 401
  - [ ] Bot should log error
  - [ ] Bot should retry

- [ ] Test with malformed JSON:
  - [ ] Bot escaping should handle special characters
  - [ ] API should accept and parse correctly

---

## Production Deployment Phase

### Pre-Production Setup
- [ ] Review and update all configuration
- [ ] Generate new secure API key for production
- [ ] Update bot code with production URL
- [ ] Update bot code with production API key
- [ ] Set ENABLE_LOGGING = false (reduce overhead)
- [ ] Set up log rotation for Laravel logs
- [ ] Configure automated backups for database

### Security Hardening
- [ ] [ ] Verify HTTPS enabled on production (if internet-exposed)
- [ ] [ ] Rate limiting configured:
  ```php
  // Add to middleware stack
  'api' => [
      \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
  ]
  ```

- [ ] [ ] CORS configured appropriately
- [ ] [ ] API keys rotated monthly
- [ ] [ ] Sensitive data not logged (passwords, account details)
- [ ] [ ] Database credentials in .env, not hardcoded
- [ ] [ ] API endpoints protected from enumeration

### Monitoring & Alerting
- [ ] [ ] Log monitoring set up
- [ ] [ ] Database connection monitoring
- [ ] [ ] API response time monitoring
- [ ] [ ] Error rate threshold alerts configured
- [ ] [ ] Disk space monitoring for logs
- [ ] [ ] CPU/Memory usage monitoring

### Backup & Recovery
- [ ] [ ] Daily database backups automated
- [ ] [ ] Weekly full system backups
- [ ] [ ] Tested restore procedure
- [ ] [ ] Off-site backup storage
- [ ] [ ] Recovery time objective (RTO) defined
- [ ] [ ] Recovery point objective (RPO) defined

---

## Post-Deployment Phase

### Initial Monitoring (First 24 hours)
- [ ] Monitor API logs every 30 minutes
- [ ] Check database growth (transactions/hour)
- [ ] Monitor bot logs for errors
- [ ] Verify all endpoint types working:
  - [ ] Trade opened
  - [ ] Trade closed
  - [ ] Position updates
  - [ ] Daily summary
  - [ ] Technical signals
  - [ ] EA status changes
  - [ ] Error logs

- [ ] Check response times remain acceptable
- [ ] Verify no validation errors in logs
- [ ] Check API key usage logs

### Week 1 Monitoring
- [ ] Run weekly queries to verify data integrity:
  ```php
  // Check for orphaned records
  App\Models\TradeEvent::whereNull('account_id')->count();
  
  // Check data consistency
  App\Models\DailySummary::where('win_rate_percent', '>', 100)->count();
  
  // Check for errors
  App\Models\ErrorLog::where('created_at', '>', now()->subWeek())->count();
  ```

- [ ] Review error logs for patterns
- [ ] Check database performance
- [ ] Verify backup integrity
- [ ] Monitor API key usage

### Monthly Maintenance
- [ ] Archive old records (if needed)
- [ ] Rotate API keys
- [ ] Review and optimize slow queries
- [ ] Clean up logs older than 30 days
- [ ] Review security logs
- [ ] Test disaster recovery procedure

---

## Rollback Plan

If issues occur, execute in this order:

### Immediate Rollback (< 1 hour of trading lost)
1. [ ] Stop bot immediately (disable in MetaTrader)
2. [ ] Set ENABLE_SYNC = false in bot code
3. [ ] Restart bot to resume trading without API
4. [ ] Investigate error in logs
5. [ ] Fix issue or rollback code
6. [ ] Create new API key if security issue
7. [ ] Resume with API enabled

### Database Rollback
```bash
# Restore from last backup
mysql -u root -p database < backup_YYYY-MM-DD.sql
```

### Code Rollback
```bash
git revert <commit_hash>
php artisan migrate:rollback --step=8
```

---

## Success Criteria

Bot integration is successful when:

- ✅ All 8 new endpoints responding with 200/201 status
- ✅ All data persisting to database correctly
- ✅ No validation errors in logs
- ✅ API response time < 500ms
- ✅ Retry logic working (tested with network outage)
- ✅ Duplicate prevention working
- ✅ Error logging comprehensive
- ✅ Bot continues trading if API unavailable
- ✅ No data loss in any scenario
- ✅ All stakeholders satisfied with performance

---

## Troubleshooting Guide

| Issue | Symptoms | Solution |
|-------|----------|----------|
| 401 Unauthorized | API returns 401 | Check X-API-KEY header, verify key in database |
| 422 Validation | Missing field errors | Check JSON format, verify all required fields |
| Connection Timeout | No response | Check API URL, verify firewall, check server load |
| Duplicate Entry | Unique constraint errors | Use updateOrCreate, check for duplicate submissions |
| Slow Response | > 1s response time | Check database queries, optimize indexes, check server load |
| Data Not Persisting | No records in DB | Check controller logic, verify database connection |
| Bot Crashes | MetaTrader hangs | Check JSON escaping, verify memory usage |
| High Memory Usage | RAM increasing | Check for memory leaks in bot code, reduce logging |

---

## Sign-Off

When all items are complete:

- [ ] Project Manager Approval
- [ ] QA Lead Sign-off
- [ ] DevOps Approval
- [ ] Security Review Complete
- [ ] Production Deployment Authorized

**Deployment Date:** ____________

**Deployed By:** ____________

**Verified By:** ____________
