<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'email_trade_alerts',
        'email_weekly_report',
        'email_payment_reminders',
        'email_system_updates',
        'push_trade_alerts',
        'push_payment_reminders',
        'notification_frequency',
        'profile_visibility',
        'two_factor_enabled',
        'chart_type',
        'timeframe',
        'email_marketing',
        'email_newsletter',
    ];

    protected $casts = [
        'email_trade_alerts' => 'boolean',
        'email_weekly_report' => 'boolean',
        'email_payment_reminders' => 'boolean',
        'email_system_updates' => 'boolean',
        'push_trade_alerts' => 'boolean',
        'push_payment_reminders' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'email_marketing' => 'boolean',
        'email_newsletter' => 'boolean',
    ];

    /**
     * Get the user that owns the settings
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
