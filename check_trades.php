<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\TradeLog;
use App\Models\TradeEvent;
use App\Models\SignalAccount;
use App\Models\Account;

echo "=== DATABASE STATE ===\n\n";

echo "ACCOUNTS: " . Account::count() . "\n";
$accounts = Account::select('id', 'login', 'active', 'user_id')->get();
foreach ($accounts as $a) {
    echo "  ID {$a->id}: Login={$a->login} Active=" . ($a->active ? 'YES' : 'NO') . " User={$a->user_id}\n";
}

echo "\nSIGNALS: " . Signal::count() . "\n";
$signals = Signal::select('id', 'symbol', 'ticket', 'direction', 'entry', 'active', 'created_at')->get();
foreach ($signals as $s) {
    echo "  ID {$s->id}: {$s->symbol} {$s->direction} @ {$s->entry} (Ticket: {$s->ticket}) Active: " . ($s->active ? 'YES' : 'NO') . "\n";
}

echo "\nSIGNAL_ACCOUNTS: " . SignalAccount::count() . "\n";
$signalAccounts = SignalAccount::select('id', 'signal_id', 'account_id', 'status', 'ticket')->get();
foreach ($signalAccounts as $sa) {
    echo "  ID {$sa->id}: Signal {$sa->signal_id} → Account {$sa->account_id} (Status: {$sa->status}, Ticket: {$sa->ticket})\n";
}

echo "\nTRADE_LOGS: " . TradeLog::count() . "\n";
$trades = TradeLog::select('id', 'ticket', 'symbol', 'type', 'lots', 'open_price', 'close_price', 'profit', 'status', 'created_at')->get();
foreach ($trades as $t) {
    echo "  ID {$t->id}: Ticket {$t->ticket} {$t->symbol} {$t->type} {$t->lots}lots @ {$t->open_price} | Status: {$t->status} | Profit: {$t->profit}\n";
}

echo "\nTRADE_EVENTS: " . TradeEvent::count() . "\n";
$events = TradeEvent::select('id', 'ticket', 'direction', 'entry_price', 'lot_size', 'opened_at')->get();
foreach ($events as $e) {
    echo "  ID {$e->id}: Ticket {$e->ticket} {$e->direction} {$e->lot_size}lots @ {$e->entry_price}\n";
}

echo "\n=== KEY ISSUES FOUND ===\n";
$activeAccounts = Account::where('active', true)->count();
echo "Active accounts: {$activeAccounts}\n";

if ($activeAccounts == 0) {
    echo "⚠️  NO ACTIVE ACCOUNTS! Signal distribution will fail.\n";
    echo "    This is why SignalAccount records were not created.\n";
    echo "    This is also why no TradeLog records exist.\n";
}

echo "\n=== CONCLUSION ===\n";
echo "Signals created: " . Signal::count() . "\n";
echo "TradeLog records: " . TradeLog::count() . "\n";
echo "SignalAccount records: " . SignalAccount::count() . "\n";
echo "Missing: " . (Signal::count() - TradeLog::count()) . " trade logs!\n";
