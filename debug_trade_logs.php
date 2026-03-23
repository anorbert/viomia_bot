<?php
/**
 * Trade Logs Debugging Script
 * Diagnoses why trades are not being saved in trade_logs
 * 
 * Usage: php debug_trade_logs.php
 * Location: Laravel root directory
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Support\Facades\DB;
use App\Models\Signal;
use App\Models\TradeLog;
use App\Models\Account;
use App\Models\TradeEvent;

echo "═══════════════════════════════════════════════════════════════════════════════\n";
echo "Trade Logs Debugging - Why Aren't Trades Being Saved?\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n\n";

$issues = [];

// ============================
// CHECK 1: Accounts
// ============================
echo "CHECK 1: Accounts in Database\n";
echo "──────────────────────────────\n\n";

$accounts = Account::all();
if ($accounts->isEmpty()) {
    echo "❌ NO ACCOUNTS FOUND!\n";
    echo "   → You must create an account first\n";
    echo "   → Command: php artisan tinker >>> Account::create(['login' => YOUR_ACCOUNT_NUMBER, 'name' => 'Trading Account', 'active' => true])\n";
    $issues[] = "No accounts configured";
} else {
    echo "✅ Accounts Found: " . $accounts->count() . "\n";
    foreach ($accounts as $acc) {
        $status = $acc->active ? "ACTIVE" : "INACTIVE";
        echo "   - Account {$acc->id}: Login={$acc->login}, Status={$status}\n";
    }
}
echo "\n";

// ============================
// CHECK 2: Recent Signals
// ============================
echo "CHECK 2: Recent Signals\n";
echo "──────────────────────\n\n";

$signals = Signal::latest()->limit(5)->get();
if ($signals->isEmpty()) {
    echo "❌ NO SIGNALS FOUND!\n";
    echo "   → EA is not sending signal to /api/bot/signal endpoint\n";
    echo "   → OR signals are being deleted\n";
    $issues[] = "No signals being created";
} else {
    echo "✅ Recent Signals: " . Signal::count() . " total\n";
    foreach ($signals as $sig) {
        $created = $sig->created_at->format('H:i:s');
        echo "   - Ticket: {$sig->ticket}, Symbol: {$sig->symbol}, Direction: {$sig->direction}, Created: {$created}\n";
    }
}
echo "\n";

// ============================
// CHECK 3: Recent Trade Logs
// ============================
echo "CHECK 3: Recent Trade Logs\n";
echo "──────────────────────────\n\n";

$tradeLogs = TradeLog::latest()->limit(5)->get();
if ($tradeLogs->isEmpty()) {
    echo "❌ NO TRADE LOGS FOUND!\n";
    echo "   → Signals are not creating TradeLog records\n";
    echo "   → OR TradeLog records are being deleted\n";
    $issues[] = "No trade logs being created";
} else {
    echo "✅ Recent Trade Logs: " . TradeLog::count() . " total\n";
    foreach ($tradeLogs as $log) {
        $account = Account::find($log->account_id);
        $accountName = $account ? $account->login : 'UNKNOWN';
        $status = $log->status ?? 'unknown';
        $profit = $log->profit ?? 0;
        $created = $log->created_at->format('H:i:s');
        echo "   - Ticket: {$log->ticket}, Symbol: {$log->symbol}, Type: {$log->type}, Status: {$status}, Profit: {$profit}, Account: {$accountName}, Created: {$created}\n";
    }
}
echo "\n";

// ============================
// CHECK 4: Closed vs Open Trades
// ============================
echo "CHECK 4: Trade Status Breakdown\n";
echo "────────────────────────────────\n\n";

$openCount = TradeLog::where('status', 'open')->count();
$closedCount = TradeLog::where('status', 'closed')->count();
$partialCount = TradeLog::where('status', 'partial_closed')->count();

echo "   - Open trades: " . $openCount . "\n";
echo "   - Closed trades: " . $closedCount . "\n";
echo "   - Partially closed: " . $partialCount . "\n";

if ($closedCount == 0 && TradeLog::count() > 0) {
    echo "\n   ⚠️  WARNING: No closed trades found!\n";
    echo "   → EA is placing trades but NOT closing them properly\n";
    echo "   → OR SendClosedTrade() is not being called\n";
    $issues[] = "No closed trades - SendClosedTrade() may not be working";
}
echo "\n";

// ============================
// CHECK 5: Account-Signal Match
// ============================
echo "CHECK 5: Signal Accounts Match\n";
echo "───────────────────────────────\n\n";

if (!$signals->isEmpty() && !$accounts->isEmpty()) {
    foreach ($signals->take(3) as $sig) {
        $signal_account = Account::find($sig->account_id);
        if (!$signal_account) {
            echo "❌ Signal {$sig->ticket}: Account ID {$sig->account_id} NOT FOUND in accounts table\n";
            $issues[] = "Signal references non-existent account";
        } else {
            echo "✅ Signal {$sig->ticket}: Account {$signal_account->login} exists\n";
        }
    }
} else {
    echo "⏭️  Skipped (need signals and accounts)\n";
}
echo "\n";

// ============================
// CHECK 6: Recent Errors in Logs
// ============================
echo "CHECK 6: Recent Errors in Logs\n";
echo "───────────────────────────────\n\n";

$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = array_reverse(file($logFile));
    $errors = [];
    
    foreach ($lines as $line) {
        if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
            if (stripos($line, 'signal') !== false || stripos($line, 'trade') !== false || stripos($line, 'account') !== false) {
                $errors[] = trim($line);
                if (count($errors) >= 5) break;
            }
        }
    }
    
    if (!empty($errors)) {
        echo "⚠️  Recent errors found:\n";
        foreach ($errors as $err) {
            // Clean up the log line for display
            $shortened = substr($err, 0, 120) . (strlen($err) > 120 ? '...' : '');
            echo "   - {$shortened}\n";
        }
    } else {
        echo "✅ No recent trade-related errors in logs\n";
    }
} else {
    echo "⚠️  Log file not found\n";
}
echo "\n";

// ============================
// CHECK 7: Database Integrity
// ============================
echo "CHECK 7: Database Tables\n";
echo "────────────────────────\n\n";

$tables = ['signals', 'trade_logs', 'accounts'];
foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "✅ {$table}: {$count} records\n";
    } catch (\Exception $e) {
        echo "❌ {$table}: Table error - " . $e->getMessage() . "\n";
        $issues[] = "Database table issue: {$table}";
    }
}
echo "\n";

// ============================
// SUMMARY
// ============================
echo "═══════════════════════════════════════════════════════════════════════════════\n";
echo "SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n\n";

if (empty($issues)) {
    echo "✅ All checks passed!\n\n";
    echo "System appears to be configured correctly.\n";
    echo "If trades are still not being saved:\n";
    echo "  1. Verify EA is actually sending signal to /api/bot/signal\n";
    echo "  2. Check EA logs (DebugMode = true)\n";
    echo "  3. Verify API key is correct (TEST_API_KEY_123)\n";
    echo "  4. Test manually: curl -X POST http://94.72.112.148:8011/api/bot/signal ...\n";
} else {
    echo "❌ Issues found:\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". " . $issue . "\n";
    }
    echo "\nNext steps:\n";
    echo "  1. Fix the issues listed above\n";
    echo "  2. Rerun this script to verify fixes\n";
    echo "  3. Test with a demo trade\n";
}

echo "\n═══════════════════════════════════════════════════════════════════════════════\n";
echo "\nFor detailed diagnosis: See WHY_TRADES_NOT_SAVED_DIAGNOSIS_GUIDE.md\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n";
