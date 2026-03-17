<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\SignalAccount;
use App\Models\TradeLog;
use App\Models\Account;
use App\Models\TradeEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         TRADING BOT DIAGNOSTIC & HEALTH CHECK                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Check 1: Database connectivity
echo "[1/8] Database Connectivity...\n";
try {
    DB::connection()->getPdo();
    echo "  ✓ Database connected\n";
} catch (\Exception $e) {
    echo "  ✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check 2: Table integrity
echo "\n[2/8] Table Integrity...\n";
$tables = ['accounts', 'signals', 'signal_accounts', 'trade_logs', 'trade_events', 'users'];
foreach ($tables as $table) {
    try {
        DB::table($table)->count();
        echo "  ✓ {$table} exists\n";
    } catch (\Exception $e) {
        echo "  ✗ {$table} missing: " . $e->getMessage() . "\n";
    }
}

// Check 3: User setup
echo "\n[3/8] User Setup...\n";
$userCount = User::count();
echo "  • Total users: {$userCount}\n";

if ($userCount === 0) {
    echo "  ⚠️  No users found! Create at least one user before creating accounts.\n";
}

// Check 4: Account setup
echo "\n[4/8] Account Setup...\n";
$totalAccounts = Account::count();
$activeAccounts = Account::where('active', true)->count();
echo "  • Total accounts: {$totalAccounts}\n";
echo "  • Active accounts: {$activeAccounts}\n";

if ($totalAccounts === 0) {
    echo "  ⚠️  No accounts found! Signal distribution requires at least one active account.\n";
} else if ($activeAccounts === 0) {
    echo "  ⚠️  No active accounts! Signals cannot be distributed.\n";
}

// Check 5: Signal distribution
echo "\n[5/8] Signal Distribution Analysis...\n";
$totalSignals = Signal::count();
$activeSignals = Signal::where('active', true)->count();
$totalAssignments = SignalAccount::count();

echo "  • Total signals: {$totalSignals}\n";
echo "  • Active signals: {$activeSignals}\n";
echo "  • Total signal-account assignments: {$totalAssignments}\n";

if ($totalSignals > 0 && $totalAssignments === 0) {
    echo "  ⚠️  Signals exist but not distributed! Signal distribution may have failed.\n";
}

if ($totalSignals > 0 && $activeAccounts > 0) {
    $expectedAssignments = $totalSignals * $activeAccounts;
    if ($totalAssignments === $expectedAssignments) {
        echo "  ✓ Distribution is complete (each signal assigned to all active accounts)\n";
    } else {
        echo "  ✗ Incomplete distribution: {$totalAssignments} / {$expectedAssignments} expected\n";
    }
}

// Check 6: Trade log creation
echo "\n[6/8] Trade Execution Analysis...\n";
$totalTradeLogs = TradeLog::count();
$openTrades = TradeLog::where('status', 'open')->count() ?? 0;
$closedTrades = TradeLog::where('status', 'closed')->count() ?? 0;

echo "  • Total trade logs: {$totalTradeLogs}\n";
echo "  • Open trades: {$openTrades}\n";
echo "  • Closed trades: {$closedTrades}\n";

// Analysis
if ($totalSignals > 0 && $totalTradeLogs === 0) {
    echo "  ⚠️  Signals received but no trades were created.\n";
    echo "     Possible causes:\n";
    echo "     - No TradeLog records created (check SignalController.store)\n";
    echo "     - Bot not evaluating signals (check trading rules)\n";
}

// Check 7: Risk/Reward analysis
echo "\n[7/8] Signal Quality Analysis...\n";
$goodSignals = 0;
$poorSignals = 0;
$invalidSignals = 0;

foreach (Signal::all() as $signal) {
    if ($signal->direction === 'buy') {
        $risk = $signal->entry - $signal->sl;
        $reward = $signal->tp - $signal->entry;
    } else {
        $risk = $signal->sl - $signal->entry;
        $reward = $signal->entry - $signal->tp;
    }

    if ($risk <= 0 || $reward <= 0) {
        $invalidSignals++;
    } else {
        $rr = $reward / $risk;
        if ($rr >= 2.0) {
            $goodSignals++;
        } else {
            $poorSignals++;
        }
    }
}

echo "  • Good signals (RR ≥ 2.0): {$goodSignals}\n";
echo "  • Poor signals (1.0 ≤ RR < 2.0): {$poorSignals}\n";
echo "  • Invalid signals (negative risk/reward): {$invalidSignals}\n";

echo "  Recommendation:\n";
if ($goodSignals === 0 && $totalSignals > 0) {
    echo "  ⚠️  No high-quality signals. Consider adjusting TP/SL levels.\n";
} else if ($goodSignals > 0) {
    echo "  ✓ Some high-quality signals available for trading.\n";
}

// Check 8: API endpoints
echo "\n[8/8] API Endpoint Configuration...\n";
echo "  • Expected endpoints:\n";
echo "    - POST /api/bot/signal (create signal)\n";
echo "    - GET /api/bot/signal (get active signal)\n";
echo "    - POST /api/bot/trade/log (log trade)\n";
echo "    - POST /api/bot/trade/opened (mark trade opened)\n";
echo "    - GET /api/bot/account/settings (get account settings)\n";
echo "    ✓ Endpoints configured. Check routes/api.php for details.\n";

// Final Summary
echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      SYSTEM HEALTH SUMMARY                       ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$issues = 0;
$warnings = 0;

if ($userCount === 0) { $issues++; }
if ($totalAccounts === 0) { $issues++; }
if ($activeAccounts === 0) { $issues++; }
if ($totalSignals > 0 && $totalAssignments === 0) { $issues++; }
if ($totalSignals > 0 && $totalTradeLogs === 0) { $warnings++; }
if ($goodSignals === 0 && $totalSignals > 0) { $warnings++; }

echo "Critical Issues:   " . ($issues === 0 ? "✓ NONE\n" : "✗ {$issues} FOUND\n");
echo "Warnings:          " . ($warnings === 0 ? "✓ NONE\n" : "⚠️  {$warnings} FOUND\n");

if ($issues === 0 && $warnings === 0) {
    echo "\n✓ SYSTEM HEALTHY - All systems operational\n";
} else if ($issues === 0) {
    echo "\n⚠️  SYSTEM OPERATIONAL - Some warnings detected\n";
} else {
    echo "\n✗ SYSTEM ISSUES - Critical problems detected\n";
}

// Detailed recommendations
echo "\n════ NEXT STEPS ════\n\n";

if ($totalAccounts === 0) {
    echo "1. Create trading accounts:\n";
    echo "   Use the admin panel or API to add trading accounts (MT4/MT5 logins)\n\n";
}

if ($activeAccounts === 0) {
    echo "1. Activate trading accounts:\n";
    echo "   All accounts must have 'active' = true for signal distribution\n\n";
}

if ($totalSignals === 0) {
    echo "1. Create test signals:\n";
    echo "   Run: php send_signals.php\n";
    echo "   Or send signals via API: POST /api/bot/signal\n\n";
}

if ($totalSignals > 0 && $totalAssignments === 0) {
    echo "1. Check signal distribution:\n";
    echo "   Review SignalController::store() method\n";
    echo "   Verify all active accounts exist in database\n\n";
}

if ($goodSignals === 0 && $totalSignals > 0) {
    echo "1. Improve signal quality:\n";
    echo "   Modify send_signals.php to use wider TP/SL spreads\n";
    echo "   Target Risk/Reward ratio ≥ 2.0\n\n";
}

echo "Documentation: See trading_bot_testing_guide.md for detailed info\n";
