<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    /**
     * The database table associated with this model.
     */
    protected $table = 'system_settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'system_name',
        'support_email',
        'support_phone',
        'company_website',
        'default_bot',
        'theme_color',
        'logo_url',
        'max_accounts',
        'max_api_keys',
        'session_timeout',
        'enable_2fa',
        'maintenance_mode',
        'enable_trading',
        'enable_withdrawals',
        'trading_hours_start',
        'trading_hours_end',
        'max_daily_trades',
        'min_trade_size',
        'max_trade_size',
        'max_loss_limit',
        'notification_email',
        'email_alerts',
        'sms_alerts',
        'in_app_alerts',
        'daily_summary',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'enable_2fa' => 'boolean',
        'maintenance_mode' => 'boolean',
        'enable_trading' => 'boolean',
        'enable_withdrawals' => 'boolean',
        'email_alerts' => 'boolean',
        'sms_alerts' => 'boolean',
        'in_app_alerts' => 'boolean',
        'daily_summary' => 'boolean',
        'min_trade_size' => 'decimal:2',
        'max_trade_size' => 'decimal:2',
        'max_loss_limit' => 'decimal:2',
    ];

    /**
     * Relationship with EaBot
     */
    public function defaultBot()
    {
        return $this->belongsTo(EaBot::class, 'default_bot');
    }

    /**
     * Get the first (and typically only) system setting.
     * This ensures we always have a singleton-like behavior.
     */
    public static function instance()
    {
        return self::first() ?? self::create([
            'system_name' => 'Trading Bot',
            'support_email' => 'support@example.com',
        ]);
    }
}
