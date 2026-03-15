<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserSettings;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Create default settings for the user
     */
    public function created(User $user): void
    {
        UserSettings::create([
            'user_id' => $user->id,
            'theme' => 'light',
            'language' => 'en',
            'email_trade_alerts' => true,
            'email_weekly_report' => true,
            'email_payment_reminders' => true,
            'email_system_updates' => true,
            'push_trade_alerts' => true,
            'push_payment_reminders' => true,
            'notification_frequency' => 'immediate',
            'profile_visibility' => 'public',
            'two_factor_enabled' => false,
            'chart_type' => 'candlestick',
            'timeframe' => '1h',
            'email_marketing' => true,
            'email_newsletter' => true,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Cascade delete user settings
        $user->settings()->delete();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        // Force delete user settings
        $user->settings()->forceDelete();
    }
}

