# Payment System Security Implementation Guide

## Overview
This document outlines all security measures implemented in the VioMia payment system, particularly for MOMO payment gateway integration.

## Security Layers Implemented

### 1. MOMO Webhook Signature Validation ✅

**File**: `app/Services/MomoWebhookService.php`

**How it works**:
- MOMO sends `X-Momo-Signature` header with each webhook
- Signature is calculated as: `HMAC-SHA256(api_key, json_payload)`
- Server validates signature before processing payment
- Uses constant-time comparison to prevent timing attacks

**Implementation**:
```php
$isValid = $this->momoService->validateSignature($rawPayload, $signature);
if (!$isValid) {
    return response()->json(['error' => 'Invalid signature'], 401);
}
```

**Setup Required**:
Add to `.env`:
```
MOMO_API_KEY=your-actual-api-key-from-momo
```

---

### 2. IP Whitelist Validation ✅

**File**: `app/Http/Middleware/EnsurePaymentSecure.php`

**How it works**:
- Only allows webhooks from MOMO IP addresses
- Configurable whitelist stored in config
- Prevents spoofed webhook requests

**Setup Required**:
Add to `.env`:
```
MOMO_IP_WHITELIST=203.0.113.1,203.0.113.2
```
(Replace with actual MOMO server IPs provided by MOMO)

**Local Development**:
- Automatically allows `127.0.0.1` and `::1` in local environment
- Use Postman with IP spoofing or MOMO test webhook tool

---

### 3. Rate Limiting ✅

**File**: `app/Http/Controllers/User/PaymentController.php` (lines ~60-70)

**How it works**:
- Limits webhook requests: 100 per minute per IP
- Prevents DDoS and webhook spam
- Configurable thresholds

**Setup Required**:
Add to `.env`:
```
MOMO_RATE_LIMIT_REQUESTS=100
MOMO_RATE_LIMIT_MINUTES=1
```

**Response** (when limit exceeded):
```json
{
    "error": "Rate limit exceeded",
    "retry_after": 45
}
```

---

### 4. Payload Timestamp Validation ✅

**File**: `app/Services/MomoWebhookService.php::validatePayloadStructure()`

**How it works**:
- Validates webhook timestamp is within 5 minutes of server time
- Prevents replay attacks with old webhooks
- Prevents clock-skew exploitation

**Validation**:
- Webhook must include `timestamp` field
- Must be in format recognized by `strtotime()`
- Cannot be older than 5 minutes

---

### 5. Payment Amount & Currency Verification ✅

**File**: `app/Services/MomoWebhookService.php::validatePaymentMatch()`

**How it works**:
- Verifies webhook amount matches stored payment transaction
- Verifies currency matches
- Prevents attacker from confirming payment with wrong amount
- Uses floating-point tolerance (±0.01) for decimal comparison

**Validation**:
```php
if (abs($payment->amount - $amount) > 0.01) {
    // Invalid webhook
}
```

---

### 6. Idempotency Protection ✅

**File**: `app/Services/MomoWebhookService.php::validatePaymentMatch()`

**How it works**:
- If same payment_id webhook is received twice, returns success without double-processing
- Prevents duplicate subscription charges or account reactivations
- Logs as "duplicate" for audit trail

**Scenario**:
- First webhook: `paid=false` → `paid=true` (processed)
- Second webhook (duplicate): Returns success but doesn't change status

---

### 7. HTTPS Enforcement ✅

**File**: `app/Http/Middleware/EnsurePaymentSecure.php`

**How it works**:
- All payment endpoints require HTTPS
- Rejects non-HTTPS requests with 403 error
- Disabled in local environment for testing

**Configuration**:
```php
if (!$request->secure() && !app()->isLocal()) {
    return response()->json(['error' => 'HTTPS required'], 403);
}
```

---

### 8. Audit Logging ✅

**Files**: 
- `app/Models/PaymentAuditLog.php`
- `database/migrations/2026_03_13_000001_create_payment_audit_logs_table.php`

**What's Logged**:
```
- Action: webhook_received, manual_confirmation, signature_validation_failed
- User ID and Payment Transaction ID
- Status transition: old_status → new_status
- IP address of requester
- User agent
- Metadata (transaction ID, signature details, etc.)
- Timestamp
```

**View Audit Log**:
```
GET /api/payments/{paymentId}/audit-log
```

**Usage Example**:
```bash
curl -H "Authorization: Bearer token" \
  https://yoursite.com/api/payments/123/audit-log
```

---

### 9. User Ownership Verification ✅

**File**: `app/Http/Controllers/User/PaymentController.php`

**How it works**:
- All payment endpoints verify user owns the payment
- Prevents users from confirming other users' payments
- Also allows admins to manually confirm payments

**Code Pattern**:
```php
if ($payment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
    return response()->json(['error' => 'Unauthorized'], 403);
}
```

---

### 10. Encryption of Sensitive Data ✅

**File**: `app/Services/SensitiveDataEncryptionService.php`

**What's Encrypted**:
- Phone numbers
- MOMO account names
- Other PII stored in payment payload

**Methods Provided**:
```php
// Encrypt data
$encrypted = $encryptionService->encrypt($data);

// Decrypt data
$decrypted = $encryptionService->decrypt($encrypted);

// Hash for comparison (one-way)
$hash = $encryptionService->hash($data);

// Mask for display
$masked = $encryptionService->maskPhoneNumber('+250', '123456789');
// Returns: +250 123 ****789
```

**Note**: Implement encryption of phone numbers and MOMO account names in PaymentTransaction payload

---

## Environment Setup

### 1. Copy MOMO Configuration
```bash
cp .env.momo.example .env
```

### 2. Add to Your .env File
```env
# MOMO API Credentials (from MOMO dashboard)
MOMO_API_KEY=your-api-key
MOMO_API_USER=your-api-user
MOMO_API_SECRET=your-api-secret

# MOMO Server IPs (provided by MOMO support)
MOMO_IP_WHITELIST=203.0.113.1,203.0.113.2

# Webhook Settings
MOMO_WEBHOOK_TIMEOUT=30
MOMO_WEBHOOK_RETRY=3

# Rate Limiting
MOMO_RATE_LIMIT_REQUESTS=100
MOMO_RATE_LIMIT_MINUTES=1

# Currency
MOMO_CURRENCY=RWF
```

### 3. Run Migrations
```bash
php artisan migrate
```

This creates the `payment_audit_logs` table.

---

## Routes Configuration

Add to `routes/api.php` or `routes/web.php`:

```php
use App\Http\Controllers\User\PaymentController;

Route::middleware(['auth:sanctum'])->group(function () {
    // User payment history
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::get('/payments/{paymentId}/audit-log', [PaymentController::class, 'auditLog'])->name('payments.audit-log');
    
    // Payment management (requires HTTPS)
    Route::middleware(['payment.secure'])->group(function () {
        Route::post('/payments/{paymentId}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        Route::post('/payments/{paymentId}/resend-link', [PaymentController::class, 'resendPaymentLink'])->name('payments.resend-link');
    });
});

// Public webhook endpoint (protected by signature validation)
Route::middleware(['payment.secure'])->post('/webhooks/momo', [PaymentController::class, 'momoWebhook'])->name('payments.momo-webhook');
```

---

## Webhook URL Configuration

**In MOMO Dashboard**, set webhook URL to:
```
https://yoursite.com/webhooks/momo
```

**Expected Webhook Format**:
```json
{
    "payment_id": "123",
    "status": "success",
    "transaction_id": "TXN-12345",
    "amount": 1000.00,
    "currency": "RWF",
    "timestamp": "2026-03-13T10:30:00Z"
}
```

**Response** (expected from server):
```json
{
    "status": "success",
    "message": "Payment confirmed",
    "payment_id": "123"
}
```

---

## Testing

### Unit Tests
```bash
php artisan test tests/Unit/Services/MomoWebhookServiceTest.php
```

### Manual Webhook Testing
Use Postman or curl:

```bash
curl -X POST https://yoursite.com/webhooks/momo \
  -H "Content-Type: application/json" \
  -H "X-Momo-Signature: $(openssl dgst -sha256 -hex -mac HMAC -macopt key:YOUR_API_KEY payload.json)" \
  -d '{
    "payment_id": "test-123",
    "status": "success",
    "transaction_id": "TXN-test",
    "amount": 1000,
    "currency": "RWF",
    "timestamp": "2026-03-13T10:30:00Z"
  }'
```

---

## Security Checklist

- [ ] MOMO_API_KEY set in .env
- [ ] MOMO_IP_WHITELIST configured with actual MOMO IPs
- [ ] HTTPS enabled on production (enforced by middleware)
- [ ] Database migrations run (payment_audit_logs table created)
- [ ] Webhook URL configured in MOMO dashboard
- [ ] Rate limiting tested
- [ ] Audit logs reviewed in production
- [ ] Phone numbers encrypted in payment payload
- [ ] MOMO account names masked for display

---

## Monitoring & Alerts

### Monitor These Metrics
```sql
-- Failed signatures
SELECT COUNT(*) FROM payment_audit_logs 
WHERE action = 'signature_validation_failed' 
AND created_at > NOW() - INTERVAL 1 HOUR;

-- Rate limit hits
SELECT COUNT(*) FROM payment_audit_logs 
WHERE action = 'webhook_failed' 
AND reason LIKE '%Rate limit%' 
AND created_at > NOW() - INTERVAL 1 HOUR;

-- Pending payments older than 1 hour
SELECT COUNT(*) FROM payment_transactions 
WHERE status = 'pending' 
AND created_at < NOW() - INTERVAL 1 HOUR;

-- User accounts still disabled after payment
SELECT COUNT(*) FROM accounts 
WHERE active = 0 
AND user_id IN (
    SELECT user_id FROM payment_transactions 
    WHERE status = 'paid' AND paid_at < NOW() - INTERVAL 1 HOUR
);
```

### Set Up Alerts
Create alerts for:
- Multiple failed signatures from same IP (potential attack)
- Rate limit exceeded
- Payments pending > 2 hours
- Mismatched amounts/currencies

---

## Common Issues & Solutions

### Issue: "Invalid signature"
**Cause**: API key mismatch  
**Fix**: Verify MOMO_API_KEY in .env matches MOMO dashboard

### Issue: "IP not whitelisted"
**Cause**: Webhook coming from unlisted IP  
**Fix**: Add IP to MOMO_IP_WHITELIST in .env

### Issue: "Timestamp too old"
**Cause**: Server clock not synced  
**Fix**: Run `ntpdate -u pool.ntp.org` on server

### Issue: "Payment validation failed"
**Cause**: Amount doesn't match webhook  
**Fix**: Check payment creation logic, verify currency conversion

---

## Next Steps

1. ✅ Signature validation implemented
2. ✅ IP whitelist implemented
3. ✅ Rate limiting implemented
4. ✅ Audit logging implemented
5. ⏳ **NEXT**: Encrypt phone numbers/account names in payload
6. ⏳ Implement payment status email notifications
7. ⏳ Build admin payment management dashboard
8. ⏳ Set up monitoring alerts
