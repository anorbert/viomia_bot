<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create table to store entry-time technical data for each trade
     * Solves P0-4: "Patterns Detected at Close, Not Entry"
     * 
     * When EA opens a trade:
     * 1. Capture RSI, ATR, patterns, trend AT ENTRY TIME
     * 2. Store in this table with correlation_id
     * 3. When trade closes, retrieve from this table
     * 4. Include entry data + close data in outcome
     * 
     * This ensures AI can see the ACTUAL entry conditions that triggered the trade
     */
    public function up(): void
    {
        Schema::create('trade_entry_context', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket')->unique()->index();
            $table->string('account_id', 50)->index();
            $table->string('correlation_id', 36)->unique()->index();
            $table->string('symbol', 20)->index();
            
            // Entry time technical data
            $table->decimal('entry_rsi', 8, 4)->nullable();
            $table->decimal('entry_atr', 18, 5)->nullable();
            $table->integer('entry_trend')->nullable();  // -1, 0, 1
            $table->integer('entry_session')->nullable();
            
            // Patterns detected at entry
            $table->boolean('entry_bos')->default(false);
            $table->boolean('entry_liquidity_sweep')->default(false);
            $table->boolean('entry_equal_highs')->default(false);
            $table->boolean('entry_equal_lows')->default(false);
            $table->boolean('entry_volume_spike')->default(false);
            
            // Market regime at entry
            $table->integer('entry_dxy_trend')->default(0);
            $table->integer('entry_risk_off')->default(0);
            
            // Entry prices
            $table->decimal('entry_price', 18, 5);
            $table->decimal('stop_loss', 18, 5);
            $table->decimal('take_profit', 18, 5);
            
            // Candle information
            $table->integer('entry_candle_period')->nullable();  // In seconds: 3600=1H, 86400=1D
            $table->timestamp('entry_candle_close_time')->nullable();
            
            // Signal reference (if from AI signal)
            $table->unsignedBigInteger('signal_id')->nullable();
            $table->string('signal_correlation_id', 36)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indices
            $table->index(['account_id', 'created_at']);
            $table->index(['symbol', 'created_at']);
            $table->foreign('signal_id')
                ->references('id')
                ->on('viomia_decisions')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_entry_context');
    }
};
