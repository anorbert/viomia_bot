<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();

            // General Settings
            $table->string('system_name')->default('Trading Bot');
            $table->string('support_email')->default('support@example.com');
            $table->string('support_phone')->nullable();
            $table->string('company_website')->nullable();
            $table->foreignId('default_bot')->nullable()->constrained('ea_bots')->onDelete('set null');
            $table->string('theme_color')->default('#1ABB9C');
            $table->string('logo_url')->nullable();

            // Access Control
            $table->integer('max_accounts')->default(100);
            $table->integer('max_api_keys')->default(10);
            $table->integer('session_timeout')->default(60); // minutes
            $table->boolean('enable_2fa')->default(true);
            $table->boolean('maintenance_mode')->default(false);

            // Trading Settings
            $table->boolean('enable_trading')->default(true);
            $table->boolean('enable_withdrawals')->default(true);
            $table->string('trading_hours_start')->default('09:00');
            $table->string('trading_hours_end')->default('17:00');
            $table->integer('max_daily_trades')->nullable();
            $table->decimal('min_trade_size', 10, 2)->default(0.01);
            $table->decimal('max_trade_size', 10, 2)->default(100.00);
            $table->decimal('max_loss_limit', 12, 2)->nullable();

            // Notification Settings
            $table->string('notification_email')->nullable();
            $table->boolean('email_alerts')->default(true);
            $table->boolean('sms_alerts')->default(false);
            $table->boolean('in_app_alerts')->default(true);
            $table->boolean('daily_summary')->default(true);

            $table->timestamps();
        });

        // Create default settings record
        DB::table('system_settings')->insert([
            'system_name' => 'Trading Bot',
            'support_email' => 'support@example.com',
            'support_phone' => null,
            'company_website' => null,
            'default_bot' => null,
            'theme_color' => '#1ABB9C',
            'logo_url' => null,
            'max_accounts' => 100,
            'max_api_keys' => 10,
            'session_timeout' => 60,
            'enable_2fa' => true,
            'maintenance_mode' => false,
            'enable_trading' => true,
            'enable_withdrawals' => true,
            'trading_hours_start' => '09:00',
            'trading_hours_end' => '17:00',
            'max_daily_trades' => null,
            'min_trade_size' => 0.01,
            'max_trade_size' => 100.00,
            'max_loss_limit' => null,
            'notification_email' => null,
            'email_alerts' => true,
            'sms_alerts' => false,
            'in_app_alerts' => true,
            'daily_summary' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
