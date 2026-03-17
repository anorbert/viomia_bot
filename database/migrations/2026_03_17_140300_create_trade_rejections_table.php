<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create table to log trade rejections/skipped trades
     * Related to P1-4 but helps with overall data quality
     * 
     * When EA decides NOT to trade, log why:
     * - Insufficient margin
     * - Cooldown active
     * - Outside trading hours
     * - Risk per trade exceeds limit
     * 
     * This gives AI FULL decision history (100 signals = 80 trades + 20 rejections)
     * Without this, AI thinks it recommended 80 when it actually evaluated 100
     */
    public function up(): void
    {
        Schema::create('trade_rejections', function (Blueprint $table) {
            $table->id();
            $table->string('account_id', 50)->index();
            $table->string('symbol', 20)->index();
            $table->string('intended_direction', 10);  // BUY or SELL
            
            // Why was it rejected?
            $table->string('rejection_reason', 100);  // "insufficient_margin", "cooldown_active", etc
            $table->text('rejection_details')->nullable();  // Extra info
            
            // Technical context at time of rejection
            $table->decimal('proposed_entry', 18, 5)->nullable();
            $table->decimal('proposed_sl', 18, 5)->nullable();
            $table->decimal('proposed_tp', 18, 5)->nullable();
            $table->decimal('proposed_lot_size', 10, 4)->nullable();
            
            // Signal reference (if from AI)
            $table->unsignedBigInteger('signal_id')->nullable();
            $table->string('signal_correlation_id', 36)->nullable();
            
            // Account state at rejection
            $table->decimal('account_equity', 12, 2)->nullable();
            $table->decimal('account_balance', 12, 2)->nullable();
            $table->decimal('available_margin', 12, 2)->nullable();
            
            $table->timestamp('rejected_at')->useCurrent()->index();
            $table->timestamps();
            
            // Indices
            $table->index(['account_id', 'rejected_at']);
            $table->index(['symbol', 'rejected_at']);
            $table->index(['rejection_reason']);
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
        Schema::dropIfExists('trade_rejections');
    }
};
