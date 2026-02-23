# Settings System Documentation

## Overview
The Settings system allows admins to configure system-wide settings through an advanced web interface with full validation and caching support.

## Features

### ✅ What's Implemented:

1. **Advanced Settings Page** (`/admin/settings`)
   - 3 organized sections: General, Account & Access Control, Trading Settings
   - 20+ configurable options
   - Beautiful UI with modern toggle switches
   - Real-time validation with error messages

2. **Robust Backend**
   - `SettingController` with full validation
   - `SettingsService` for programmatic access
   - Helper functions for easy usage throughout app
   - Cache-based storage (24-hour expiration)

3. **Settings Sections:**

   **General Settings:**
   - System Name
   - Support Email & Phone
   - Company Website
   - Default Bot Selection
   - Theme Color (with color picker)

   **Account & Access Control:**
   - Max Accounts per User (default: 100)
   - Max API Keys per User (default: 10)
   - Session Timeout in minutes (default: 60)
   - Enable Two-Factor Authentication (toggle)
   - Maintenance Mode (toggle)
   - Notification Email

   **Trading Settings:**
   - Enable Trading (toggle)
   - Enable Withdrawals (toggle)
   - Trading Hours (Start & End times)
   - Max Daily Trades
   - Minimum Trade Size
   - Maximum Trade Size
   - Daily Loss Limit

## Usage

### 1. In Controllers:
```php
use App\Services\SettingsService;

public function myMethod()
{
    // Get specific setting
    $maxAccounts = SettingsService::get('max_accounts', 100);
    
    // Check if enabled
    if (SettingsService::isEnabled('enable_trading')) {
        // Allow trading
    }
    
    // Get all settings
    $allSettings = SettingsService::all();
}
```

### 2. Using Helper Functions (Recommended):
```php
// Get a setting
$systemName = setting('system_name');

// Set a setting
setting('system_name', 'My New Bot');

// Check if enabled
if (is_setting_enabled('enable_trading')) {
    // Allow trading
}

// Get all settings
$settings = setting();
```

### 3. In Blade Templates:
```blade
<h1>{{ setting('system_name') }}</h1>

@if (is_setting_enabled('maintenance_mode'))
    <div class="alert">System is in maintenance mode</div>
@endif

<!-- Display trading hours -->
<p>Trading: {{ setting('trading_hours_start') }} - {{ setting('trading_hours_end') }}</p>
```

### 4. In Middleware:
```php
use App\Services\SettingsService;

public function handle($request, Closure $next)
{
    if (SettingsService::isEnabled('maintenance_mode')) {
        return response()->view('errors.maintenance', [], 503);
    }
    
    return $next($request);
}
```

## Routes

**View Settings:**
```
GET /admin/settings
Name: admin.settings.index
```

**Save Settings:**
```
POST /admin/settings/save
Name: admin.settings.save
```

## Validation Rules

All settings are validated before saving:

- `system_name`: Required, max 100 chars
- `support_email`: Required email
- `support_phone`: Optional, max 20 chars
- `company_website`: Optional, valid URL
- `default_bot`: Optional, must exist in ea_bots table
- `max_accounts`: Required, 1-1000
- `max_api_keys`: Required, 1-100
- `session_timeout`: Required, 5-1440 minutes
- `enable_*`: Boolean toggles
- `trading_hours_*`: Optional, HH:MM format
- `max_daily_trades`: Optional, min 1
- `min_trade_size`: Optional, min 0.01
- `max_trade_size`: Optional, min 0.01
- `max_loss_limit`: Optional, min 0
- `notification_email`: Optional email
- `logo_url`: Optional string
- `theme_color`: Optional hex color (#RRGGBB)

## Caching

Settings are cached for 24 hours for performance:

```php
// Manually clear cache
use App\Services\SettingsService;
SettingsService::clearCache();
```

Cache keys:
- Individual: `setting_{key}`
- All: `app_settings`

## Implementation Examples

### Example 1: Check Trading Status
```php
public function executeOrder()
{
    if (!is_setting_enabled('enable_trading')) {
        return response()->json(['error' => 'Trading is disabled'], 403);
    }
    
    // Process trade
}
```

### Example 2: Enforce Trade Size Limits
```php
public function validateTradeSize($size)
{
    $min = setting('min_trade_size');
    $max = setting('max_trade_size');
    
    if ($size < $min || $size > $max) {
        throw new \Exception("Trade size must be between $min and $max");
    }
}
```

### Example 3: Check Account Limits
```php
public function createAccount(User $user)
{
    $maxAccounts = setting('max_accounts');
    $userAccounts = $user->accounts()->count();
    
    if ($userAccounts >= $maxAccounts) {
        throw new \Exception("Account limit reached");
    }
    
    // Create account
}
```

### Example 4: In Middleware for Session Timeout
```php
public function handle($request, Closure $next)
{
    config(['session.lifetime' => setting('session_timeout')]);
    return $next($request);
}
```

## Files Modified/Created

### New Files:
- `app/Services/SettingsService.php` - Settings service class
- `app/Helpers/SettingHelper.php` - Helper functions
- `app/Http/Middleware/CheckMaintenanceMode.php` - Maintenance mode middleware

### Modified Files:
- `app/Http/Controllers/Admin/SettingController.php` - Full implementation
- `resources/views/admin/settings/index.blade.php` - Advanced UI
- `composer.json` - Added helper file to autoload

### Existing Routes:
- `routes/web.php` - Routes already in place

## Next Steps

1. Register `CheckMaintenanceMode` middleware in `app/Http/Kernel.php` if needed
2. Run `composer dump-autoload` to update helper function autoloading
3. Visit `/admin/settings` to configure your system
4. Use `setting()` helper throughout your app

## Troubleshooting

**Settings not saving?**
- Check if cache is writable
- Verify form submission to `/admin/settings/save`
- Check validation errors in browser

**Changes not taking effect?**
- Clear cache: `SettingsService::clearCache()`
- Check cache driver in `.env` (default: file)

**Helper functions not available?**
- Run: `composer dump-autoload`
- Restart your server

## Performance Notes

- Settings are cached for 24 hours
- Each setting access uses cache (very fast)
- No database queries required
- Perfect for read-heavy workloads
