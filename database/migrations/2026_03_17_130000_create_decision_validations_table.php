<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create table to store all AI decision validations for:
     * - Audit trail
     * - Confidence calibration verification
     * - Learning system feedback
     */
    public function up(): void
    {
        Schema::create('decision_validations', function (Blueprint $table) {
            $table->id();
            $table->string('account_id', 50)->index();
            $table->string('symbol', 20)->index();
            $table->string('direction', 10);  // BUY or SELL
            $table->decimal('confidence', 6, 4)->index();  // 0.0000 to 1.0000
            $table->json('patterns');  // ['BOS', 'LIQSWP', 'OBBLOCK']
            $table->decimal('rsi', 8, 4)->nullable();
            $table->integer('trend')->nullable();  // -1, 0, 1
            $table->decimal('rr_ratio', 8, 4)->nullable();
            $table->json('scores');  // Breakdown: pattern, rr, trend, rsi, regime, model
            $table->text('reasoning');  // Human-readable explanation
            $table->timestamp('created_at')->index();
            
            // Indices for common queries
            $table->index(['account_id', 'created_at']);
            $table->index(['symbol', 'direction']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_validations');
    }
};
