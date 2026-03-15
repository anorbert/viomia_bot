<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Display Preferences
            $table->string('theme')->default('light'); // light, dark, auto
            $table->string('language')->default('en'); // en, fr, es, de
            
            // Notification Preferences - Email
            $table->boolean('email_trade_alerts')->default(true);
            $table->boolean('email_weekly_report')->default(true);
            $table->boolean('email_payment_reminders')->default(true);
            $table->boolean('email_system_updates')->default(true);
            
            // Notification Preferences - Push
            $table->boolean('push_trade_alerts')->default(true);
            $table->boolean('push_payment_reminders')->default(true);
            
            // Notification Frequency
            $table->string('notification_frequency')->default('immediate'); // immediate, daily, weekly
            
            // Privacy & Visibility
            $table->string('profile_visibility')->default('public'); // public, private, friends
            
            // Security Features
            $table->boolean('two_factor_enabled')->default(false);
            
            // Trading Preferences
            $table->string('chart_type')->default('candlestick'); // candlestick, line, bar
            $table->string('timeframe')->default('1h'); // 1m, 5m, 15m, 1h, 4h, 1d
            
            // Email Preferences
            $table->boolean('email_marketing')->default(true);
            $table->boolean('email_newsletter')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
