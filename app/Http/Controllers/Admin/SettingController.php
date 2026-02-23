<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EaBot;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $bots = EaBot::all();
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings', 'bots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Save settings
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:100',
            'support_email' => 'required|email',
            'support_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url',
            'default_bot' => 'nullable|exists:ea_bots,id',
            'max_accounts' => 'required|integer|min:1|max:1000',
            'max_api_keys' => 'required|integer|min:1|max:100',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'enable_2fa' => 'boolean',
            'enable_trading' => 'boolean',
            'enable_withdrawals' => 'boolean',
            'maintenance_mode' => 'boolean',
            'trading_hours_start' => 'nullable|date_format:H:i',
            'trading_hours_end' => 'nullable|date_format:H:i',
            'max_daily_trades' => 'nullable|integer|min:1',
            'min_trade_size' => 'nullable|numeric|min:0.01',
            'max_trade_size' => 'nullable|numeric|min:0.01',
            'max_loss_limit' => 'nullable|numeric|min:0',
            'notification_email' => 'nullable|email',
            'logo_url' => 'nullable|string',
            'theme_color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6})$/',
        ]);

        try {
            // Convert checkboxes to boolean
            $settings = [
                'system_name' => $validated['system_name'],
                'support_email' => $validated['support_email'],
                'support_phone' => $validated['support_phone'] ?? '',
                'company_website' => $validated['company_website'] ?? '',
                'default_bot' => $validated['default_bot'] ?? null,
                'max_accounts' => (int)$validated['max_accounts'],
                'max_api_keys' => (int)$validated['max_api_keys'],
                'session_timeout' => (int)$validated['session_timeout'],
                'enable_2fa' => (bool)$request->has('enable_2fa'),
                'enable_trading' => (bool)$request->has('enable_trading'),
                'enable_withdrawals' => (bool)$request->has('enable_withdrawals'),
                'maintenance_mode' => (bool)$request->has('maintenance_mode'),
                'trading_hours_start' => $validated['trading_hours_start'] ?? '09:00',
                'trading_hours_end' => $validated['trading_hours_end'] ?? '17:00',
                'max_daily_trades' => $validated['max_daily_trades'] ?? null,
                'min_trade_size' => $validated['min_trade_size'] ?? 0.01,
                'max_trade_size' => $validated['max_trade_size'] ?? 100,
                'max_loss_limit' => $validated['max_loss_limit'] ?? null,
                'notification_email' => $validated['notification_email'] ?? '',
                'logo_url' => $validated['logo_url'] ?? '',
                'theme_color' => $validated['theme_color'] ?? '#3b82f6',
            ];

            // Store all settings in cache (24 hours)
            foreach ($settings as $key => $value) {
                Cache::put('setting_' . $key, $value, now()->addHours(24));
            }

            // Also store as a single JSON object in cache
            Cache::put('app_settings', $settings, now()->addHours(24));

            return back()->with('success', 'Settings saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }

    /**
     * Get all settings with caching
     */
    private function getSettings()
    {
        // Try to get from cache first
        $cachedSettings = Cache::get('app_settings');
        
        if ($cachedSettings) {
            return (object) $cachedSettings;
        }

        // Default settings if not cached
        return (object) [
            'system_name' => Cache::get('setting_system_name', 'Trading Bot'),
            'support_email' => Cache::get('setting_support_email', 'support@example.com'),
            'support_phone' => Cache::get('setting_support_phone', ''),
            'company_website' => Cache::get('setting_company_website', ''),
            'default_bot' => Cache::get('setting_default_bot', null),
            'max_accounts' => Cache::get('setting_max_accounts', 100),
            'max_api_keys' => Cache::get('setting_max_api_keys', 10),
            'session_timeout' => Cache::get('setting_session_timeout', 60),
            'enable_2fa' => Cache::get('setting_enable_2fa', true),
            'enable_trading' => Cache::get('setting_enable_trading', true),
            'enable_withdrawals' => Cache::get('setting_enable_withdrawals', true),
            'maintenance_mode' => Cache::get('setting_maintenance_mode', false),
            'trading_hours_start' => Cache::get('setting_trading_hours_start', '09:00'),
            'trading_hours_end' => Cache::get('setting_trading_hours_end', '17:00'),
            'max_daily_trades' => Cache::get('setting_max_daily_trades', null),
            'min_trade_size' => Cache::get('setting_min_trade_size', 0.01),
            'max_trade_size' => Cache::get('setting_max_trade_size', 100),
            'max_loss_limit' => Cache::get('setting_max_loss_limit', null),
            'notification_email' => Cache::get('setting_notification_email', ''),
            'logo_url' => Cache::get('setting_logo_url', ''),
            'theme_color' => Cache::get('setting_theme_color', '#3b82f6'),
        ];
    }
}
