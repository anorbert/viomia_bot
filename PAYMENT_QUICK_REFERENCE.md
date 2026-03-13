# Payment System - Developer Quick Reference

## Webhook Security Quick Checklist

```
┌─────────────────────────────────────────────────────────┐
│ MOMO Webhook Flow (Secure)                              │
├─────────────────────────────────────────────────────────┤
│ 1. ✅ POST /webhooks/momo                               │
│ 2. ✅ Validate HTTPS (EnsurePaymentSecure middleware)   │
│ 3. ✅ Validate IP (whitelist in config/momo.php)        │
│ 4. ✅ Rate limit check (100/minute)                     │
│ 5. ✅ HMAC-SHA256 signature validation                  │
│ 6. ✅ Payload structure validation                      │
│ 7. ✅ Timestamp validation (< 5 min old)                │
│ 8. ✅ Payment amount match verification                 │
│ 9. ✅ Idempotency check (duplicate detection)           │
│ 10. ✅ Audit log entry created                          │
│ 11. ✅ Payment status: pending → paid                   │
│ 12. ✅ Subscription status: pending → active            │
│ 13. ✅ Accounts reactivated: active = 1                 │
└─────────────────────────────────────────────────────────┘
```

## Environment Setup

```bash
# 1. Add to .env (get credentials from MOMO dashboard)
MOMO_API_KEY=your-api-key
MOMO_API_SECRET=your-api-secret
MOMO_IP_WHITELIST=203.0.113.1,203.0.113.2

# 2. Run migrations
php artisan migrate

# 3. Add routes to routes/api.php (see PAYMENT_ROUTES_REFERENCE.php)

# 4. Test webhook with curl
curl -X POST https://localhost/webhooks/momo \
  -H "Content-Type: application/json" \
  -H "X-Momo-Signature: $(echo -n 'payload' | openssl dgst -sha256 -hex -mac HMAC -macopt key:API_KEY)" \
  -d '{"payment_id":"123","status":"success","transaction_id":"TXN","amount":1000,"currency":"RWF","timestamp":"2026-03-13T10:00:00Z"}'
```

## Common Code Patterns

### Validate Signature (in PaymentController)
```php
if (!$this->momoService->validateSignature($rawPayload, $signature)) {
    return response()->json(['error' => 'Invalid signature'], 401);
}
```

### Check IP Whitelist (in Middleware)
```php
if (!in_array($ip, config('services.momo.ip_whitelist', []))) {
    return response()->json(['error' => 'IP not whitelisted'], 403);
}
```

### Validate Amount Match
```php
if (abs($payment->amount - $amount) > 0.01) {
    // Amounts don't match - REJECT
}
```

### Log to Audit Trail
```php
PaymentAuditLog::create([
    'user_id' => $payment->user_id,
    'payment_transaction_id' => $payment->id,
    'action' => 'webhook_received',
    'old_status' => 'pending',
    'new_status' => 'paid',
    'ip_address' => request()->ip(),
    'metadata' => ['txn_id' => $transactionId],
]);
```

## TODO Items

### HIGH PRIORITY (Do Now)
```php
// 1. Encrypt sensitive data in PaymentTransaction.payload
// In PaymentController.php momoWebhook():
$payment->payload = $this->encryptionService->encrypt($payload);

// 2. Send confirmation email
// In PaymentController.php momoWebhook() after payment paid:
Mail::to($payment->user->email)->send(new PaymentConfirmationMail($payment));

// 3. Configure MOMO dashboard webhook URL
// Settings → Webhooks → https://yoursite.com/webhooks/momo
```

### MEDIUM PRIORITY (This Week)
```php
// 1. Build admin payment confirmation dashboard
// Route: GET /admin/payments (show pending, let admin confirm)
// POST /admin/payments/{id}/confirm (manual confirmation)

// 2. Create daily MOMO reconciliation job
// php artisan schedule:work
// Check: for paid payments without webhook

// 3. Add payment notification emails
// PaymentConfirmedMail
// PaymentFailedMail
// PaymentPendingReminderMail (2+ hours)
```

### LOW PRIORITY (Next Sprint)
```php
// 1. Refund request workflow
// 2. Payment dispute resolution
// 3. MOMO transaction history sync
// 4. Payment analytics dashboard
```

## Database Queries

### View pending payments
```sql
SELECT pays.id, pays.amount, pays.currency, pays.created_at 
FROM payment_transactions pays
WHERE pays.status = 'pending'
AND pays.created_at < NOW() - INTERVAL 2 HOUR;
```

### View audit trail for payment
```sql
SELECT * FROM payment_audit_logs
WHERE payment_transaction_id = ?
ORDER BY created_at DESC;
```

### Find suspicious activity
```sql
SELECT ip_address, COUNT(*) as attempts
FROM payment_audit_logs
WHERE action = 'signature_validation_failed'
AND created_at > NOW() - INTERVAL 1 HOUR
GROUP BY ip_address
HAVING attempts > 5;
```

## Security Reminders

- Never log API keys or secrets ❌
- Never expose payment_id in URLs (use POST) ⚠️
- Always use HTTPS for payment endpoints ✅
- Always verify user ownership before processing ✅
- Always validate webhook signature ✅
- Always check IP whitelist ✅
- Always create audit log entry ✅

## Files Location Reference

| Purpose | File |
|---------|------|
| Webhook Handler | `app/Http/Controllers/User/PaymentController.php` |
| Signature Validation | `app/Services/MomoWebhookService.php` |
| IP & HTTPS Validation | `app/Http/Middleware/EnsurePaymentSecure.php` |
| Audit Logging | `app/Models/PaymentAuditLog.php` |
| Config | `config/momo.php` |
| Migration | `database/migrations/2026_03_13_000001_create_payment_audit_logs_table.php` |
| History View | `resources/views/user/payments/history.blade.php` |
| Routes | `routes/PAYMENT_ROUTES_REFERENCE.php` |
| Docs | `PAYMENT_SECURITY_GUIDE.md` |

## Testing Commands

```bash
# Run all tests
php artisan test

# Test only payment-related tests
php artisan test --filter=PaymentController

# Run Laravel Tinker to test manually
php artisan tinker

# View all routes
php artisan route:list | grep payment

# Check if middleware is registered
php artisan route:list | grep payment.secure
```

## Error Responses

| Error | Status | Meaning |
|-------|--------|---------|
| Invalid signature | 401 | Wrong HMAC-SHA256 signature |
| IP not whitelisted | 403 | Request from unknown/spoofed IP |
| Rate limit exceeded | 429 | Too many requests from this IP |
| Invalid payload | 400 | Missing required fields in webhook |
| Payment not found | 404 | payment_id doesn't exist |
| Timestamp too old | 400 | Webhook older than 5 minutes |
| Amount mismatch | 400 | Webhook amount ≠ stored amount |
| Unauthorized | 403 | User doesn't own this payment |
| HTTPS required | 403 | Non-HTTPS request in production |

## Production Checklist

- [ ] MOMO_API_KEY set to production key
- [ ] MOMO_API_SECRET set to production secret
- [ ] MOMO_IP_WHITELIST set to real MOMO IPs (not localhost)
- [ ] HTTPS enabled and enforced
- [ ] Webhook URL configured in MOMO dashboard
- [ ] Database migrations run
- [ ] Audit logging tested
- [ ] Email notifications working
- [ ] Error alerts set up
- [ ] Rate limiting tested at scale
- [ ] Payment history view accessible
- [ ] Audit log viewer functional
