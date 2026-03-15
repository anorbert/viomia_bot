<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EaBot;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $bots = EaBot::all();
        $settings = SystemSetting::instance();
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
            'theme_color' => 'nullable|string|size:7',
            'logo_url' => 'nullable|string',
            'max_accounts' => 'required|integer|min:1|max:1000',
            'max_api_keys' => 'required|integer|min:1|max:100',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'enable_2fa' => 'sometimes|boolean',
            'maintenance_mode' => 'sometimes|boolean',
            'enable_trading' => 'sometimes|boolean',
            'enable_withdrawals' => 'sometimes|boolean',
            'trading_hours_start' => 'nullable|date_format:H:i',
            'trading_hours_end' => 'nullable|date_format:H:i',
            'max_daily_trades' => 'nullable|integer|min:1',
            'min_trade_size' => 'nullable|numeric|min:0.01',
            'max_trade_size' => 'nullable|numeric|min:0.01',
            'max_loss_limit' => 'nullable|numeric|min:0',
            'notification_email' => 'nullable|email',
            'email_alerts' => 'sometimes|boolean',
            'sms_alerts' => 'sometimes|boolean',
            'in_app_alerts' => 'sometimes|boolean',
            'daily_summary' => 'sometimes|boolean',
        ]);

        try {
            // Get or create default setting
            $settings = SystemSetting::first();
            if (!$settings) {
                $settings = new SystemSetting();
            }

            // Convert checkboxes to boolean
            $settings->system_name = $validated['system_name'];
            $settings->support_email = $validated['support_email'];
            $settings->support_phone = $validated['support_phone'] ?? '';
            $settings->company_website = $validated['company_website'] ?? '';
            $settings->default_bot = $validated['default_bot'] ?? null;
            $settings->theme_color = $validated['theme_color'] ?? '#1ABB9C';
            $settings->logo_url = $validated['logo_url'] ?? '';
            $settings->max_accounts = (int)$validated['max_accounts'];
            $settings->max_api_keys = (int)$validated['max_api_keys'];
            $settings->session_timeout = (int)$validated['session_timeout'];
            $settings->enable_2fa = (bool)$request->has('enable_2fa');
            $settings->maintenance_mode = (bool)$request->has('maintenance_mode');
            $settings->enable_trading = (bool)$request->has('enable_trading');
            $settings->enable_withdrawals = (bool)$request->has('enable_withdrawals');
            $settings->trading_hours_start = $validated['trading_hours_start'] ?? '09:00';
            $settings->trading_hours_end = $validated['trading_hours_end'] ?? '17:00';
            $settings->max_daily_trades = $validated['max_daily_trades'] ?? null;
            $settings->min_trade_size = $validated['min_trade_size'] ?? 0.01;
            $settings->max_trade_size = $validated['max_trade_size'] ?? 100.00;
            $settings->max_loss_limit = $validated['max_loss_limit'] ?? null;
            $settings->notification_email = $validated['notification_email'] ?? '';
            $settings->email_alerts = (bool)$request->has('email_alerts');
            $settings->sms_alerts = (bool)$request->has('sms_alerts');
            $settings->in_app_alerts = (bool)$request->has('in_app_alerts');
            $settings->daily_summary = (bool)$request->has('daily_summary');

            // Save to database
            $settings->save();

            // Also cache the settings for faster access (24 hours)
            Cache::put('app_settings', $settings, now()->addHours(24));
            foreach ($settings->getAttributes() as $key => $value) {
                Cache::put('setting_' . $key, $value, now()->addHours(24));
            }

            return back()->with('success', 'Settings saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }
}

