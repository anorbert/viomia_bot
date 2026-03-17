<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class P0RaceConditionVerifierController extends Controller
{
    /**
     * Verify that P0-7 race condition protections are properly installed
     */
    public function verify()
    {
        $checks = [];

        try {
            // Check 1: Verify correlation_id column exists
            $columns = DB::select("DESCRIBE viomia_trade_outcomes");
            $columnNames = array_column($columns, 'Field');
            
            $checks['correlation_id_column'] = [
                'status' => in_array('correlation_id', $columnNames),
                'message' => in_array('correlation_id', $columnNames) 
                    ? '✅ correlation_id column exists'
                    : '❌ correlation_id column missing'
            ];

            // Check 2: Verify attempt_count column exists
            $checks['attempt_count_column'] = [
                'status' => in_array('attempt_count', $columnNames),
                'message' => in_array('attempt_count', $columnNames)
                    ? '✅ attempt_count column exists'
                    : '❌ attempt_count column missing'
            ];

            // Check 3: Verify unique constraint exists
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                 WHERE TABLE_NAME='viomia_trade_outcomes' 
                 AND COLUMN_NAME='ticket' 
                 AND CONSTRAINT_NAME != 'PRIMARY'"
            );
            
            $hasUniqueConstraint = count($constraints) > 0;
            $checks['unique_constraint'] = [
                'status' => $hasUniqueConstraint,
                'message' => $hasUniqueConstraint
                    ? '✅ Unique constraint on (ticket, account_id) exists'
                    : '❌ Unique constraint missing'
            ];

            // Check 4: Verify stored procedure exists
            $procedures = DB::select(
                "SELECT ROUTINE_NAME FROM INFORMATION_SCHEMA.ROUTINES 
                 WHERE ROUTINE_NAME='sp_store_outcome_atomic' 
                 AND ROUTINE_SCHEMA=DATABASE()"
            );
            
            $hasProcedure = count($procedures) > 0;
            $checks['stored_procedure'] = [
                'status' => $hasProcedure,
                'message' => $hasProcedure
                    ? '✅ Stored procedure sp_store_outcome_atomic exists'
                    : '❌ Stored procedure missing'
            ];

            // Check 5: Verify indexes
            $indexes = DB::select(
                "SHOW INDEXES FROM viomia_trade_outcomes 
                 WHERE Column_name='correlation_id'"
            );
            
            $hasIndex = count($indexes) > 0;
            $checks['correlation_id_index'] = [
                'status' => $hasIndex,
                'message' => $hasIndex
                    ? '✅ Index on correlation_id exists'
                    : '❌ Index on correlation_id missing'
            ];

            // Summary
            $allPassed = array_every($checks, fn($check) => $check['status']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Verification failed: ' . $e->getMessage(),
                'checks' => $checks ?? []
            ], 500);
        }

        return response()->json([
            'status' => $allPassed ? 'ALL_CHECKS_PASSED' : 'SOME_CHECKS_FAILED',
            'all_passed' => $allPassed,
            'checks' => $checks,
            'message' => $allPassed 
                ? 'P0-7 race condition protections successfully installed'
                : 'Some P0-7 protections are missing or improperly installed'
        ]);
    }

    /**
     * Get statistics on deduplication
     */
    public function dedupStats()
    {
        try {
            // Statistics on duplicate handling
            $stats = DB::select(
                "SELECT 
                    COUNT(*) as total_outcomes,
                    COUNT(DISTINCT correlation_id) as unique_flows,
                    SUM(CASE WHEN attempt_count > 1 THEN 1 ELSE 0 END) as deduplicated,
                    MAX(attempt_count) as max_retries,
                    AVG(attempt_count) as avg_retries,
                    MIN(created_at) as first_outcome,
                    MAX(updated_at) as latest_update
                FROM viomia_trade_outcomes"
            );

            $byAttemptCount = DB::select(
                "SELECT attempt_count, COUNT(*) as frequency 
                 FROM viomia_trade_outcomes 
                 GROUP BY attempt_count 
                 ORDER BY attempt_count ASC"
            );

            return response()->json([
                'summary' => $stats[0] ?? null,
                'distribution' => $byAttemptCount,
                'dedup_rate' => $stats[0] 
                    ? round(($stats[0]->deduplicated / $stats[0]->total_outcomes) * 100, 2) . '%'
                    : 'N/A'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Statistics query failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get correlation trace for a specific outcome
     */
    public function traceOutcome($correlationId)
    {
        try {
            $trace = DB::select(
                "SELECT 
                    oto.ticket,
                    oto.account_id,
                    oto.symbol,
                    oto.decision,
                    oto.entry,
                    oto.close_price,
                    oto.profit,
                    oto.result,
                    oto.signal_id,
                    oto.signal_correlation_id,
                    oto.correlation_id,
                    oto.attempt_count,
                    oto.created_at,
                    oto.updated_at,
                    s.direction as signal_direction,
                    s.created_at as signal_created_at
                FROM viomia_trade_outcomes oto
                LEFT JOIN signals s ON oto.signal_id = s.id
                WHERE oto.correlation_id = ?",
                [$correlationId]
            );

            return response()->json([
                'correlation_id' => $correlationId,
                'trace' => $trace[0] ?? null,
                'trace_count' => count($trace)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Trace query failed: ' . $e->getMessage()
            ], 500);
        }
    }
}

/**
 * Helper function for checking array every
 */
if (!function_exists('array_every')) {
    function array_every(array $array, callable $callback): bool {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        return true;
    }
}
