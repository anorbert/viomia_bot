<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: This migration completely recreates viomia_trade_outcomes with all required columns.
     * The previous migration only had 5 columns but AI needs 23 columns.
     */
    public function up(): void
    {
        // Drop existing incomplete table
        Schema::dropIfExists('viomia_trade_outcomes');
        
        // Recreate with all required columns
        Schema::create('viomia_trade_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket')->unique()->index();
            $table->string('account_id', 50)->default('0')->index();
            $table->string('symbol', 20)->index();
            $table->string('decision', 10);
            
            // Price levels
            $table->decimal('entry', 18, 5);
            $table->decimal('sl', 18, 5);
            $table->decimal('tp', 18, 5);
            $table->decimal('close_price', 18, 5)->nullable();
            $table->decimal('profit', 12, 4)->index();
            
            // Trade details
            $table->string('close_reason', 100)->nullable();
            $table->integer('duration_mins')->nullable();
            $table->string('result', 10)->index(); // WIN/LOSS
            
            // Technical indicators at entry
            $table->decimal('rsi', 8, 4)->nullable();
            $table->decimal('atr', 18, 5)->nullable();
            $table->tinyInteger('trend')->nullable();
            $table->tinyInteger('session')->nullable();
            
            // Pattern signals
            $table->boolean('bos')->default(false);
            $table->boolean('liquidity_sweep')->default(false);
            $table->boolean('equal_highs')->default(false);
            $table->boolean('equal_lows')->default(false);
            $table->boolean('volume_spike')->default(false);
            
            // Market context
            $table->integer('dxy_trend')->default(0);
            $table->integer('risk_off')->default(0);
            
            // Timestamps
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viomia_trade_outcomes');
    }
};
