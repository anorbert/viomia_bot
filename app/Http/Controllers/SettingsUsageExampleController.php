<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;

/**
 * Example Settings Usage Controller
 * 
 * This controller demonstrates how to use the Settings system
 * throughout your application.
 */
class SettingsUsageExampleController extends Controller
{
    /**
     * Example 1: Using SettingsService directly
     */
    public function exampleOne()
    {
        // Get a specific setting
        $systemName = SettingsService::get('system_name', 'Trading Bot');
        
        // Get a numeric setting
        $maxAccounts = SettingsService::get('max_accounts', 100);
        
        // Check if a boolean setting is enabled
        if (SettingsService::isEnabled('enable_trading')) {
            echo "Trading is enabled";
        }
        
        // Get all settings
        $allSettings = SettingsService::all();
        
        return response()->json([
            'system_name' => $systemName,
            'max_accounts' => $maxAccounts,
            'all_settings' => $allSettings,
        ]);
    }

    /**
     * Example 2: Using helper functions (recommended)
     */
    public function exampleTwo()
    {
        // Get setting using helper
        $supportEmail = setting('support_email');
        
        // Check if setting is enabled
        if (is_setting_enabled('maintenance_mode')) {
            return response()->json(['message' => 'System is in maintenance mode'], 503);
        }
        
        // Get trading hours
        $tradingStart = setting('trading_hours_start');
        $tradingEnd = setting('trading_hours_end');
        
        return response()->json([
            'support_email' => $supportEmail,
            'trading_hours' => "$tradingStart - $tradingEnd",
        ]);
    }

    /**
     * Example 3: Enforcing trade size limits
     */
    public function validateTradeSize($requestedSize)
    {
        $minSize = setting('min_trade_size', 0.01);
        $maxSize = setting('max_trade_size', 100);
        
        if ($requestedSize < $minSize) {
            return response()->json([
                'error' => "Minimum trade size is $minSize"
            ], 422);
        }
        
        if ($requestedSize > $maxSize) {
            return response()->json([
                'error' => "Maximum trade size is $maxSize"
            ], 422);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Example 4: Checking user account limits
     */
    public function checkAccountLimit($userId)
    {
        $user = auth()->user();
        $maxAccounts = setting('max_accounts', 100);
        $currentAccounts = $user->accounts()->count();
        
        if ($currentAccounts >= $maxAccounts) {
            return response()->json([
                'error' => 'You have reached the maximum number of accounts',
                'limit' => $maxAccounts,
                'current' => $currentAccounts,
            ], 422);
        }
        
        return response()->json([
            'can_create' => true,
            'remaining' => $maxAccounts - $currentAccounts,
        ]);
    }

    /**
     * Example 5: Daily trading stats
     */
    public function getDailyStats()
    {
        $maxDailyTrades = setting('max_daily_trades');
        $dailyLossLimit = setting('max_loss_limit');
        $minTradeSize = setting('min_trade_size');
        $maxTradeSize = setting('max_trade_size');
        
        return response()->json([
            'max_daily_trades' => $maxDailyTrades,
            'daily_loss_limit' => $dailyLossLimit,
            'min_trade_size' => $minTradeSize,
            'max_trade_size' => $maxTradeSize,
        ]);
    }

    /**
     * Example 6: Check security settings
     */
    public function getSecuritySettings()
    {
        return response()->json([
            'two_factor_enabled' => is_setting_enabled('enable_2fa'),
            'session_timeout' => setting('session_timeout') . ' minutes',
            'max_api_keys' => setting('max_api_keys'),
            'notification_email' => setting('notification_email'),
        ]);
    }

    /**
     * Example 7: Get company info
     */
    public function getCompanyInfo()
    {
        return response()->json([
            'name' => setting('system_name'),
            'email' => setting('support_email'),
            'phone' => setting('support_phone'),
            'website' => setting('company_website'),
            'theme_color' => setting('theme_color'),
            'logo_url' => setting('logo_url'),
        ]);
    }

    /**
     * Example 8: Clear settings cache (admin only)
     */
    public function clearSettingsCache()
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        SettingsService::clearCache();
        
        return response()->json(['message' => 'Settings cache cleared']);
    }
}
