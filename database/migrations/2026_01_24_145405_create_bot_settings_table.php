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
        Schema::create('bot_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('bot_enabled')->default(true);

            // polling / fetching
            $table->integer('signal_check_interval')->default(5); // seconds
            $table->integer('max_spread_points')->default(500);

            // risk / trading limits
            $table->decimal('risk_per_trade', 6, 2)->default(1.00); // %
            $table->integer('max_trades_per_day')->default(10);

            // news filter
            $table->boolean('use_news_filter')->default(true);
            $table->integer('block_before_news_minutes')->default(15);
            $table->integer('block_after_news_minutes')->default(15);
            $table->string('filter_currencies')->default('USD,EUR,GBP');

            // misc
            $table->boolean('debug_mode')->default(false);
            $table->timestamps();
        });

        // create one default row
        DB::table('bot_settings')->insert([
            'bot_enabled' => true,
            'signal_check_interval' => 5,
            'max_spread_points' => 500,
            'risk_per_trade' => 1.00,
            'max_trades_per_day' => 10,
            'use_news_filter' => true,
            'block_before_news_minutes' => 15,
            'block_after_news_minutes' => 15,
            'filter_currencies' => 'USD,EUR,GBP',
            'debug_mode' => false,            
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_settings');
    }
};
