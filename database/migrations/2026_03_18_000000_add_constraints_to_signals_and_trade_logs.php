<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cleans up NULL/empty symbol values in signals and trade_logs tables
     * caused by the SignalController bug (Feb 2026) where exists() returned
     * boolean instead of Account object, resulting in NULL account_id inserts.
     */
    public function up(): void
    {
        // Clean signals table
        if (Schema::hasTable('signals')) {
            DB::statement('UPDATE signals SET symbol = "UNKNOWN" WHERE symbol IS NULL OR symbol = ""');
            DB::statement('UPDATE signals SET ticket = CONCAT("NULL_", id) WHERE ticket IS NULL OR ticket = ""');
        }

        // Clean trade_logs table
        if (Schema::hasTable('trade_logs')) {
            DB::statement('UPDATE trade_logs SET symbol = "UNKNOWN" WHERE symbol IS NULL OR symbol = ""');
            DB::statement('UPDATE trade_logs SET ticket = CONCAT("NULL_", id) WHERE ticket IS NULL OR ticket = ""');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migration can be rolled back if needed
        // Note: This will NOT restore original NULL values
    }
};
