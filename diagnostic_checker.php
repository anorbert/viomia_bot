<?php
/**
 * DIAGNOSTIC CHECKER - VIOMIA TRADING BOT
 * Run this to verify your system is ready for live trading
 * 
 * Usage: php diagnostic_checker.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Account;
use App\Models\Signal;
use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║       VIOMIA BOT - DIAGNOSTIC CHECKER (v2.0)                 ║\n";
echo "║                March 18, 2026                                ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$checks = [];
$passed = 0;
$failed = 0;

// ============================================================
// CHECK 1: Active Accounts
// ============================================================
echo "📋 CHECK 1: Active Accounts\n";
$activeAccounts = Account::where('active', true)->count();
$totalAccounts = Account::count();

if ($activeAccounts > 0) {
    echo "  ✅ PASS: {$activeAccounts}/{$totalAccounts} accounts active\n";
    $passed++;
    $checks[] = ['Active Accounts', 'PASS'];
} else {
    echo "  ❌ FAIL: No active accounts! (0/{$totalAccounts})\n";
    echo "  🔧 FIX: UPDATE accounts SET active = 1 WHERE login IN (YOUR_ACCOUNT_NOS);\n";
    $failed++;
    $checks[] = ['Active Accounts', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 2: P0-1 Signal Linking Implemented
// ============================================================
echo "📋 CHECK 2: P0-1 Signal Linking (signal_id column)\n";
$hasSignalId = DB::select("
    SELECT COUNT(*) as count 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'viomia_trade_outcomes' 
    AND COLUMN_NAME = 'signal_id'
")[0];

if ($hasSignalId->count > 0) {
    echo "  ✅ PASS: signal_id column exists on viomia_trade_outcomes\n";
    $passed++;
    $checks[] = ['P0-1 Signal Linking', 'PASS'];
} else {
    echo "  ❌ FAIL: signal_id column missing!\n";
    echo "  🔧 FIX: php artisan migrate\n";
    $failed++;
    $checks[] = ['P0-1 Signal Linking', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 3: P0-3 Outcome Retry Queue Implemented
// ============================================================
echo "📋 CHECK 3: P0-3 Retry Queue (outcome_failures table)\n";
$hasOutcomeFailures = DB::select("
    SELECT COUNT(*) as count 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_NAME = 'outcome_failures' 
    AND TABLE_SCHEMA = DATABASE()
")[0];

if ($hasOutcomeFailures->count > 0) {
    echo "  ✅ PASS: outcome_failures table exists\n";
    
    // Check queue size
    $queueSize = DB::table('outcome_failures')
        ->where('retry_count', '<', 6)
        ->count();
    $permanentFailures = DB::table('outcome_failures')
        ->where('retry_count', '>=', 6)
        ->count();
    
    echo "  ℹ️  Queue items to retry: {$queueSize}\n";
    echo "  ℹ️  Permanent failures (max retries exceeded): {$permanentFailures}\n";
    
    $passed++;
    $checks[] = ['P0-3 Retry Queue', 'PASS'];
} else {
    echo "  ❌ FAIL: outcome_failures table missing!\n";
    echo "  🔧 FIX: php artisan migrate\n";
    $failed++;
    $checks[] = ['P0-3 Retry Queue', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 4: P0-4 Entry Context Implemented
// ============================================================
echo "📋 CHECK 4: P0-4 Entry Context (trade_entry_context table)\n";
$hasEntryContext = DB::select("
    SELECT COUNT(*) as count 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_NAME = 'trade_entry_context' 
    AND TABLE_SCHEMA = DATABASE()
")[0];

if ($hasEntryContext->count > 0) {
    echo "  ✅ PASS: trade_entry_context table exists\n";
    $passed++;
    $checks[] = ['P0-4 Entry Context', 'PASS'];
} else {
    echo "  ❌ FAIL: trade_entry_context table missing!\n";
    echo "  🔧 FIX: php artisan migrate\n";
    $failed++;
    $checks[] = ['P0-4 Entry Context', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 5: Recent Signals Created
// ============================================================
echo "📋 CHECK 5: Recent Signal Activity\n";
$recentSignals = Signal::where('created_at', '>', now()->subHours(24))->count();
$allSignals = Signal::count();

if ($recentSignals > 0) {
    echo "  ✅ PASS: {$recentSignals} signals created in last 24 hours\n";
    echo "  ℹ️  Total signals all time: {$allSignals}\n";
    $passed++;
    $checks[] = ['Recent Signals', 'PASS'];
} else {
    echo "  ⚠️  WARNING: No signals in last 24 hours\n";
    echo "  ℹ️  Total signals all time: {$allSignals}\n";
    
    if ($allSignals == 0) {
        echo "  🔧 FIX: AI backend not sending signals. Check:\n";
        echo "         1. Is Python FastAPI running on port 8001?\n";
        echo "         2. Is EA connected to correct API URL?\n";
        $failed++;
        $checks[] = ['Recent Signals', 'FAIL'];
    } else {
        echo "  ℹ️  System has worked before, just no recent activity\n";
        $checks[] = ['Recent Signals', 'WARN'];
    }
}
echo "\n";

// ============================================================
// CHECK 6: Signal Outcomes Linked
// ============================================================
echo "📋 CHECK 6: Signal-Outcome Linking Quality\n";
$totalOutcomes = DB::table('viomia_trade_outcomes')->count();
$linkedOutcomes = DB::table('viomia_trade_outcomes')
    ->whereNotNull('signal_id')
    ->count();

if ($totalOutcomes > 0) {
    $linkPercentage = round(($linkedOutcomes / $totalOutcomes) * 100, 1);
    echo "  ℹ️  Total outcomes: {$totalOutcomes}\n";
    echo "  ℹ️  Linked to signals: {$linkedOutcomes} ({$linkPercentage}%)\n";
    
    if ($linkPercentage >= 80) {
        echo "  ✅ PASS: Strong signal-outcome linking\n";
        $passed++;
        $checks[] = ['Signal-Outcome Linking', 'PASS'];
    } else if ($linkPercentage >= 50) {
        echo "  ⚠️  WARNING: Partial linking (aim for >80%)\n";
        $checks[] = ['Signal-Outcome Linking', 'WARN'];
    } else {
        echo "  ❌ FAIL: Weak linking (should be >80%)\n";
        $failed++;
        $checks[] = ['Signal-Outcome Linking', 'FAIL'];
    }
} else {
    echo "  ℹ️  No outcomes yet (system just started)\n";
    $checks[] = ['Signal-Outcome Linking', 'N/A'];
}
echo "\n";

// ============================================================
// CHECK 7: Signal Expiry Implemented
// ============================================================
echo "📋 CHECK 7: Signal Expiry (expires_at column)\n";
$hasExpiry = DB::select("
    SELECT COUNT(*) as count 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'signals' 
    AND COLUMN_NAME = 'expires_at'
")[0];

if ($hasExpiry->count > 0) {
    echo "  ✅ PASS: expires_at column exists on signals table\n";
    $passed++;
    $checks[] = ['Signal Expiry', 'PASS'];
} else {
    echo "  ❌ FAIL: expires_at column missing!\n";
    echo "  🔧 FIX: php artisan tinker\n";
    echo "         >>> Schema::table('signals', function(\$t) { \$t->timestamp('expires_at')->nullable(); });\n";
    $failed++;
    $checks[] = ['Signal Expiry', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 8: Migration Status
// ============================================================
echo "📋 CHECK 8: P0 Migrations Status\n";
$p0Migrations = DB::table('migrations')
    ->whereIn('migration', [
        '2026_03_17_140000_add_signal_linking_to_outcomes',
        '2026_03_17_140100_create_outcome_failures_table',
        '2026_03_17_140200_create_trade_entry_context_table',
        '2026_03_17_150000_add_race_condition_protections'
    ])
    ->count();

if ($p0Migrations >= 4) {
    echo "  ✅ PASS: All {$p0Migrations}/4 P0 migrations applied\n";
    $passed++;
    $checks[] = ['P0 Migrations', 'PASS'];
} else {
    echo "  ❌ FAIL: Only {$p0Migrations}/4 P0 migrations applied\n";
    echo "  🔧 FIX: php artisan migrate\n";
    $failed++;
    $checks[] = ['P0 Migrations', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 9: Validation Controller Available
// ============================================================
echo "📋 CHECK 9: Signal Validation Controller\n";
$validationFile = file_exists(
    app_path('Http/Controllers/Bot/SignalValidatorController.php')
);

if ($validationFile) {
    echo "  ✅ PASS: SignalValidatorController exists\n";
    $passed++;
    $checks[] = ['Validation Controller', 'PASS'];
} else {
    echo "  ❌ FAIL: SignalValidatorController missing!\n";
    $failed++;
    $checks[] = ['Validation Controller', 'FAIL'];
}
echo "\n";

// ============================================================
// CHECK 10: Database Connection
// ============================================================
echo "📋 CHECK 10: Database Connection\n";
try {
    DB::connection()->getPdo();
    echo "  ✅ PASS: MySQL connection OK\n";
    $passed++;
    $checks[] = ['Database Connection', 'PASS'];
} catch (\Exception $e) {
    echo "  ❌ FAIL: MySQL connection failed: {$e->getMessage()}\n";
    $failed++;
    $checks[] = ['Database Connection', 'FAIL'];
}
echo "\n";

// ============================================================
// SUMMARY
// ============================================================
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                     DIAGNOSTIC SUMMARY                        ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "┌───────────────────────────────────────────────────────────────┐\n";
echo "│ Test Results                                                  │\n";
echo "├───────────────────────────────────────────────────────────────┤\n";

foreach ($checks as $check) {
    $name = $check[0];
    $status = $check[1];
    $icon = $status === 'PASS' ? '✅' : ($status === 'WARN' ? '⚠️' : '❌');
    printf("│ %-50s %s %s \n", $name, $icon, $status);
}

echo "└───────────────────────────────────────────────────────────────┘\n\n";

$total = $passed + $failed;
$percentage = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

echo "Overall Status: {$passed}/{$total} passed ({$percentage}%)\n\n";

if ($failed === 0) {
    echo "🎉 ALL CHECKS PASSED! Your system is ready.\n\n";
    echo "Next steps:\n";
    echo "  1. Verify FastAPI is running: netstat -ano | findstr :8001\n";
    echo "  2. Verify EA can connect: Test /ai/analyze endpoint\n";
    echo "  3. Monitor health_check.php for ongoing status\n";
} else if ($failed <= 2) {
    echo "⚠️  SOME ISSUES FOUND - Fix them then re-run this check.\n\n";
} else {
    echo "🔴 CRITICAL ISSUES - System not ready for live trading.\n";
    echo "   Follow the 🔧 FIX suggestions above.\n\n";
}

echo "Full Analysis: See DEEP_ANALYSIS_TRADING_LOSSES.md\n";
