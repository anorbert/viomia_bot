<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Solves P0-7: "Race Conditions in Data Recording"
     * 
     * Adds:
     * 1. Unique constraint on (ticket, account_id) to prevent duplicates
     * 2. correlation_id for tracking signal→trade→outcome flow
     * 3. Deduplication tracking with attempt_count
     * 4. Index for fast correlation lookups
     */
    public function up(): void
    {
        Schema::table('viomia_trade_outcomes', function (Blueprint $table) {
            // Add correlation ID for tracking atomicity
            $table->string('correlation_id', 36)->nullable()->unique()->after('signal_correlation_id');
            
            // Track deduplication attempts
            $table->integer('attempt_count')->default(1)->after('correlation_id');
            
            // Add index for fast correlation lookups
            $table->index('correlation_id');
            
            // Unique constraint on (ticket, account_id) to prevent duplicates
            // Note: Using raw to ensure correct constraint
            $table->unique(['ticket', 'account_id'], 'unique_ticket_account');
        });

        // Add stored procedure for atomic transaction handling
        DB::statement(<<<'SQL'
            CREATE PROCEDURE IF NOT EXISTS sp_store_outcome_atomic(
                IN p_ticket INT,
                IN p_account_id VARCHAR(50),
                IN p_symbol VARCHAR(20),
                IN p_decision VARCHAR(10),
                IN p_entry DECIMAL(15,5),
                IN p_sl DECIMAL(15,5),
                IN p_tp DECIMAL(15,5),
                IN p_close_price DECIMAL(15,5),
                IN p_profit DECIMAL(15,2),
                IN p_close_reason VARCHAR(100),
                IN p_duration_mins INT,
                IN p_rsi FLOAT,
                IN p_atr FLOAT,
                IN p_trend VARCHAR(20),
                IN p_session VARCHAR(50),
                IN p_bos TINYINT,
                IN p_liquidity_sweep TINYINT,
                IN p_equal_highs TINYINT,
                IN p_equal_lows TINYINT,
                IN p_volume_spike TINYINT,
                IN p_dxy_trend VARCHAR(50),
                IN p_risk_off TINYINT,
                IN p_result VARCHAR(10),
                IN p_signal_id BIGINT,
                IN p_signal_correlation_id VARCHAR(36),
                IN p_correlation_id VARCHAR(36)
            )
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                -- Check if already exists (deduplication)
                IF EXISTS (
                    SELECT 1 FROM viomia_trade_outcomes 
                    WHERE ticket = p_ticket AND account_id = p_account_id
                ) THEN
                    -- Update existing record instead of failing
                    UPDATE viomia_trade_outcomes
                    SET 
                        close_price = p_close_price,
                        profit = p_profit,
                        result = p_result,
                        close_reason = p_close_reason,
                        rsi = p_rsi,
                        atr = p_atr,
                        attempt_count = attempt_count + 1,
                        recorded_at = NOW(),
                        updated_at = NOW()
                    WHERE ticket = p_ticket AND account_id = p_account_id;
                ELSE
                    -- Insert new record
                    INSERT INTO viomia_trade_outcomes (
                        ticket, account_id, symbol, decision,
                        entry, sl, tp, close_price, profit,
                        close_reason, duration_mins,
                        rsi, atr, trend, session,
                        bos, liquidity_sweep, equal_highs,
                        equal_lows, volume_spike,
                        dxy_trend, risk_off, result,
                        signal_id, signal_correlation_id,
                        correlation_id, attempt_count,
                        recorded_at, created_at, updated_at
                    ) VALUES (
                        p_ticket, p_account_id, p_symbol, p_decision,
                        p_entry, p_sl, p_tp, p_close_price, p_profit,
                        p_close_reason, p_duration_mins,
                        p_rsi, p_atr, p_trend, p_session,
                        p_bos, p_liquidity_sweep, p_equal_highs,
                        p_equal_lows, p_volume_spike,
                        p_dxy_trend, p_risk_off, p_result,
                        p_signal_id, p_signal_correlation_id,
                        p_correlation_id, 1,
                        NOW(), NOW(), NOW()
                    );
                END IF;

                COMMIT;
            END;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS sp_store_outcome_atomic');

        Schema::table('viomia_trade_outcomes', function (Blueprint $table) {
            $table->dropUnique('unique_ticket_account');
            $table->dropIndex('viomia_trade_outcomes_correlation_id_index');
            $table->dropColumn(['correlation_id', 'attempt_count']);
        });
    }
};
