<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\SignalAccount;
use App\Models\TradeLog;
use App\Models\Account;
use App\Models\TradeEvent;
use App\Models\ViomiaSignalLog;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║          TRADING BOT - OPPORTUNITY ANALYZER                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Get all pending signals
$pendingSignals = SignalAccount::where('status', 'pending')
    ->with('signal', 'account')
    ->orderBy('created_at', 'asc')
    ->get();

echo "Found " . $pendingSignals->count() . " pending signal-account assignments\n\n";

if ($pendingSignals->isEmpty()) {
    echo "No pending signals to process.\n";
    exit(0);
}

// Group by account for batch processing
$byAccount = $pendingSignals->groupBy('account_id');

foreach ($byAccount as $accountId => $signals) {
    $account = Account::find($accountId);
    echo "┌────────────────────────────────────────────────────────────┐\n";
    echo "│ ACCOUNT: {$account->login} (ID: {$account->id})\n";
    echo "└────────────────────────────────────────────────────────────┘\n";

    foreach ($signals as $sa) {
        $signal = $sa->signal;
        echo "\n  Signal: {$signal->ticket}\n";
        echo "  ├─ Symbol: {$signal->symbol}\n";
        echo "  ├─ Direction: " . strtoupper($signal->direction) . "\n";
        echo "  ├─ Entry: {$signal->entry}\n";
        echo "  ├─ SL: {$signal->sl}\n";
        echo "  ├─ TP: {$signal->tp}\n";
        echo "  ├─ Timeframe: {$signal->timeframe}\n";
        echo "  └─ Status: {$sa->status}\n";

        // Simulate trading logic
        $riskReward = getRiskRewardRatio($signal->entry, $signal->sl, $signal->tp, $signal->direction);
        echo "  → Risk/Reward ratio: 1:" . number_format($riskReward, 2) . "\n";

        // Check if trade should be executed
        if ($riskReward >= 2.0) {
            echo "  ✓ RR Ratio acceptable (≥2.0)\n";

            // Simulate trade execution
            $executed = executeTradeSimulation($account, $signal, $sa);
            if ($executed) {
                echo "  ✓ TRADE EXECUTED!\n";
            }
        } else {
            echo "  ✗ RR Ratio too low (rejected)\n";
        }
    }

    echo "\n";
}

// Summary stats
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      SUMMARY STATISTICS                          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$totalSignals = Signal::count();
$totalSignalAccounts = SignalAccount::count();
$pendingCount = SignalAccount::where('status', 'pending')->count();
$executedCount = SignalAccount::where('status', 'executed')->count();
$rejectedCount = SignalAccount::where('status', 'rejected')->count();
$totalTrades = TradeLog::count();
$openTrades = TradeLog::where('status', 'open')->count();

echo "Total Signals:                    {$totalSignals}\n";
echo "Total Signal-Account Assignments: {$totalSignalAccounts}\n";
echo "  ├─ Pending:                     {$pendingCount}\n";
echo "  ├─ Executed:                    {$executedCount}\n";
echo "  └─ Rejected:                    {$rejectedCount}\n\n";
echo "Total Trades Created:             {$totalTrades}\n";
echo "Open Trades:                      {$openTrades}\n";

// Account-level summary
echo "\n════ ACCOUNT SUMMARY ════\n";
$accounts = Account::where('active', true)->get();
foreach ($accounts as $acc) {
    $accountTrades = TradeLog::where('account_id', $acc->id)->count();
    $accountSignals = SignalAccount::where('account_id', $acc->id)->count();
    $profitableTrades = TradeLog::where('account_id', $acc->id)
        ->where('profit', '>', 0)
        ->count();

    echo "\n{$acc->login}:\n";
    echo "  • Assigned Signals: {$accountSignals}\n";
    echo "  • Trades Executed: {$accountTrades}\n";
    echo "  • Profitable Trades: {$profitableTrades}\n";

    if ($accountTrades > 0) {
        $totalProfit = TradeLog::where('account_id', $acc->id)->sum('profit') ?? 0;
        echo "  • Total P&L: " . ($totalProfit >= 0 ? '+' : '') . "{$totalProfit}\n";
    }
}

echo "\n";

/**
 * Calculate Risk/Reward ratio
 */
function getRiskRewardRatio($entry, $sl, $tp, $direction) {
    if ($direction === 'buy') {
        $risk = $entry - $sl;
        $reward = $tp - $entry;
    } else {
        $risk = $sl - $entry;
        $reward = $entry - $tp;
    }

    if ($risk <= 0) return 0;
    return $reward / $risk;
}

/**
 * Simulate trade execution
 */
function executeTradeSimulation($account, $signal, $signalAccount) {
    try {
        // Create TradeEvent to mark entry
        $tradeEvent = TradeEvent::create([
            'ticket'        => $signal->ticket,
            'account_id'    => $account->id,
            'signal_id'     => $signal->id,
            'direction'     => $signal->direction,
            'entry_price'   => $signal->entry,
            'lot_size'      => rand(1, 2), // 1-2 lots
            'opened_at'     => now(),
            'status'        => 'open',
        ]);

        // Update SignalAccount to executed
        $signalAccount->update(['status' => 'executed']);

        return true;
    } catch (\Exception $e) {
        echo "      Error: " . $e->getMessage() . "\n";
        return false;
    }
}
