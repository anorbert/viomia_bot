<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add signal_id foreign key and correlation tracking to viomia_trade_outcomes
     * Enables linking: Signal → Trade Entry → Trade Close → Outcome
     * 
     * This solves: P0-1 "No Signal ↔ Outcome Linking"
     */
    public function up(): void
    {
        Schema::table('viomia_trade_outcomes', function (Blueprint $table) {
            // Add signal reference
            $table->unsignedBigInteger('signal_id')->nullable()->after('account_id');
            $table->string('signal_correlation_id', 36)->nullable()->after('signal_id');
            $table->timestamp('signal_created_at')->nullable()->after('signal_correlation_id');
            
            // Add indices for fast lookups
            $table->index(['signal_id']);
            $table->index(['signal_correlation_id']);
            $table->index(['account_id', 'signal_id']);
            
            // Foreign key to viomia_decisions table
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
        Schema::table('viomia_trade_outcomes', function (Blueprint $table) {
            $table->dropForeign(['signal_id']);
            $table->dropIndex(['signal_id']);
            $table->dropIndex(['signal_correlation_id']);
            $table->dropIndex(['account_id', 'signal_id']);
            $table->dropColumn(['signal_id', 'signal_correlation_id', 'signal_created_at']);
        });
    }
};
