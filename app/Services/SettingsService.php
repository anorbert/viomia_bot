<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a specific setting
     */
    public static function get($key, $default = null)
    {
        return Cache::get('setting_' . $key, $default);
    }

    /**
     * Set a specific setting
     */
    public static function set($key, $value)
    {
        Cache::put('setting_' . $key, $value, now()->addHours(24));
    }

    /**
     * Get all settings
     */
    public static function all()
    {
        $cached = Cache::get('app_settings');
        
        if ($cached) {
            return $cached;
        }

        return [
            'system_name' => self::get('system_name', 'Trading Bot'),
            'support_email' => self::get('support_email', 'support@example.com'),
            'support_phone' => self::get('support_phone', ''),
            'company_website' => self::get('company_website', ''),
            'default_bot' => self::get('default_bot', null),
            'max_accounts' => self::get('max_accounts', 100),
            'max_api_keys' => self::get('max_api_keys', 10),
            'session_timeout' => self::get('session_timeout', 60),
            'enable_2fa' => self::get('enable_2fa', true),
            'enable_trading' => self::get('enable_trading', true),
            'enable_withdrawals' => self::get('enable_withdrawals', true),
            'maintenance_mode' => self::get('maintenance_mode', false),
            'trading_hours_start' => self::get('trading_hours_start', '09:00'),
            'trading_hours_end' => self::get('trading_hours_end', '17:00'),
            'max_daily_trades' => self::get('max_daily_trades', null),
            'min_trade_size' => self::get('min_trade_size', 0.01),
            'max_trade_size' => self::get('max_trade_size', 100),
            'max_loss_limit' => self::get('max_loss_limit', null),
            'notification_email' => self::get('notification_email', ''),
            'logo_url' => self::get('logo_url', ''),
            'theme_color' => self::get('theme_color', '#3b82f6'),
        ];
    }

    /**
     * Check if a boolean setting is enabled
     */
    public static function isEnabled($key)
    {
        return (bool) self::get($key, false);
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('app_settings');
        $keys = [
            'system_name', 'support_email', 'support_phone', 'company_website',
            'default_bot', 'max_accounts', 'max_api_keys', 'session_timeout',
            'enable_2fa', 'enable_trading', 'enable_withdrawals', 'maintenance_mode',
            'trading_hours_start', 'trading_hours_end', 'max_daily_trades',
            'min_trade_size', 'max_trade_size', 'max_loss_limit',
            'notification_email', 'logo_url', 'theme_color',
        ];
        
        foreach ($keys as $key) {
            Cache::forget('setting_' . $key);
        }
    }
}
