<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\SignalAccount;
use App\Models\TradeLog;
use App\Models\TradeEvent;
use App\Models\Account;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                   TRADING RESULTS SUMMARY                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Summary stats
$totalSignals = Signal::count();
$activeSignals = Signal::where('active', true)->count();
$pendingAssignments = SignalAccount::where('status', 'pending')->count();
$executedAssignments = SignalAccount::where('status', 'executed')->count();
$rejectedAssignments = SignalAccount::where('status', 'rejected')->count();

echo "SIGNAL PIPELINE:\n";
echo "  Created:           {$totalSignals} signals\n";
echo "  Active:            {$activeSignals} signals\n";
echo "  Distributed:       " . SignalAccount::count() . " signal-account assignments\n\n";

echo "SIGNAL STATUS:\n";
echo "  Pending:           {$pendingAssignments}\n";
echo "  Executed:          {$executedAssignments}\n";
echo "  Rejected:          {$rejectedAssignments}\n\n";

// Trade events (actual executed trades)
$tradeEvents = TradeEvent::count();

echo "EXECUTION RESULTS:\n";
echo "  Trade Events:      {$tradeEvents} (actual executed trades)\n\n";

// Signal quality
echo "SIGNAL QUALITY ANALYSIS:\n";
foreach (Signal::all() as $signal) {
    if ($signal->direction === 'buy') {
        $risk = $signal->entry - $signal->sl;
        $reward = $signal->tp - $signal->entry;
    } else {
        $risk = $signal->sl - $signal->entry;
        $reward = $signal->entry - $signal->tp;
    }
    $rr = $risk > 0 ? $reward / $risk : 0;
    $status = $rr >= 2.0 ? "✓ ACCEPT" : "✗ REJECT";
    $distributed = SignalAccount::where('signal_id', $signal->id)->count();
    echo "  {$signal->ticket}: {$status} (RR: 1:" . number_format($rr, 2) . ") → {$distributed} accounts\n";
}

echo "\n════════════════════════════════════════════════════════════════\n\n";

// Account performance
$accounts = Account::where('active', true)->get();
echo "ACCOUNT PERFORMANCE:\n";
foreach ($accounts as $acc) {
    $signals = SignalAccount::where('account_id', $acc->id)->count();
    $accepted = SignalAccount::where('account_id', $acc->id)->where('status', 'executed')->count();
    $rejected = SignalAccount::where('account_id', $acc->id)->where('status', 'rejected')->count();
    
    if ($signals > 0) {
        echo "\n  {$acc->login}:\n";
        echo "    Assignments:       {$signals}\n";
        echo "    Executed:          {$accepted}\n";
        echo "    Rejected:          {$rejected}\n";
        echo "    Acceptance Rate:   " . ($signals > 0 ? round(($accepted / $signals) * 100) : 0) . "%\n";
    }
}

echo "\n════════════════════════════════════════════════════════════════\n\n";

// Next steps
echo "NEXT STEPS:\n";
if ($tradeEvents === 0 && $executedAssignments > 0) {
    echo "✓ Signals have been accepted and are ready to execute\n";
    echo "  Trade execution would happen in the live bot\n";
} else if ($tradeEvents > 0) {
    echo "✓ Trades have been executed\n";
    echo "  Monitor the trade_events table for execution details\n";
} else {
    echo "⚠️  No trades executed yet\n";
    echo "  Check signal quality - may need RR >= 2.0\n";
}

echo "\nDocumentation:\n";
echo "  • setup_overview.md - Project structure\n";
echo "  • trading_bot_testing_guide.md - Testing guide\n\n";
