<?php

if (!function_exists('setting')) {
    /**
     * Get or set settings
     * 
     * Usage:
     * setting('system_name') - Get a setting
     * setting('system_name', 'New Name') - Set a setting
     * setting() - Get all settings
     */
    function setting($key = null, $value = null)
    {
        $settings = app('App\Services\SettingsService');
        
        if ($key === null) {
            return $settings::all();
        }
        
        if ($value !== null) {
            return $settings::set($key, $value);
        }
        
        return $settings::get($key);
    }
}

if (!function_exists('is_setting_enabled')) {
    /**
     * Check if a boolean setting is enabled
     * 
     * Usage:
     * is_setting_enabled('enable_trading')
     */
    function is_setting_enabled($key)
    {
        return app('App\Services\SettingsService')::isEnabled($key);
    }
}
