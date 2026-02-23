# ⚡ Settings System - Quick Reference

## Get a Setting
```php
setting('system_name')                    // Get value
setting('max_accounts')                   // Returns: 100
setting('max_accounts', 50)               // Set value
```

## Check if Enabled
```php
if (is_setting_enabled('enable_trading')) {
    // Trading is enabled
}
```

## Get All Settings
```php
$all = setting();  // Returns associative array
```

## In Blade Template
```blade
{{ setting('system_name') }}

@if (is_setting_enabled('enable_withdrawals'))
    <button>Withdraw</button>
@endif
```

## Using Service Directly
```php
use App\Services\SettingsService;

SettingsService::get('key', 'default')
SettingsService::set('key', value)
SettingsService::isEnabled('key')
SettingsService::all()
SettingsService::clearCache()
```

## Available Settings

### General
- `system_name`
- `support_email`
- `support_phone`
- `company_website`
- `default_bot`
- `theme_color`
- `logo_url`

### Access Control
- `max_accounts` (int)
- `max_api_keys` (int)
- `session_timeout` (int)
- `enable_2fa` (bool)
- `maintenance_mode` (bool)
- `notification_email`

### Trading
- `enable_trading` (bool)
- `enable_withdrawals` (bool)
- `trading_hours_start` (HH:MM)
- `trading_hours_end` (HH:MM)
- `max_daily_trades` (int)
- `min_trade_size` (float)
- `max_trade_size` (float)
- `max_loss_limit` (float)

## Admin URL
```
/admin/settings
```

## Cache Info
- **Driver:** File (default)
- **TTL:** 24 hours
- **Keys:** `setting_*` and `app_settings`

## Real-World Examples

### 1. Validate Trade Size
```php
$size = $request->input('size');
$min = setting('min_trade_size');
$max = setting('max_trade_size');

if ($size < $min || $size > $max) {
    throw new \Exception("Invalid size");
}
```

### 2. Check Account Limit
```php
if ($user->accounts()->count() >= setting('max_accounts')) {
    return response()->json(['error' => 'Limit reached'], 422);
}
```

### 3. Enforce Trading Hours
```php
$now = now()->format('H:i');
$start = setting('trading_hours_start');
$end = setting('trading_hours_end');

if ($now < $start || $now > $end) {
    return response()->json(['error' => 'Market closed'], 422);
}
```

### 4. Check Daily Loss Limit
```php
$dailyLoss = getUserDailyLoss($user->id);
$limit = setting('max_loss_limit');

if ($dailyLoss >= $limit) {
    return response()->json(['error' => 'Daily loss limit reached'], 422);
}
```

### 5. Maintenance Mode Middleware
```php
if (is_setting_enabled('maintenance_mode') && !auth()->user()->isAdmin()) {
    return response()->view('errors.maintenance', [], 503);
}
```

### 6. Get Company Info
```php
$company = [
    'name' => setting('system_name'),
    'email' => setting('support_email'),
    'phone' => setting('support_phone'),
    'website' => setting('company_website'),
];
```

## Error Handling
```php
try {
    $value = setting('some_setting');
} catch (\Exception $e) {
    Log::error('Settings error: ' . $e->getMessage());
}
```

## Clear Cache
```php
// When you need to force refresh
SettingsService::clearCache();
```

---

**Pro Tip:** Settings are cached for 24 hours. For instant updates, clear cache:
```php
SettingsService::clearCache();
```

**Another Tip:** All settings validation happens in `SettingController@save()`. Modify validation rules there as needed.

---

For detailed documentation, see: `SETTINGS_SYSTEM.md`
