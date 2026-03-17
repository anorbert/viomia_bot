<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\SignalAccount;
use App\Models\TradeLog;
use App\Models\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

echo "=== SENDING SIGNALS VIA API ===\n\n";

// Delete previous signals for clean test (respect foreign keys)
DB::statement('SET FOREIGN_KEY_CHECKS=0');
SignalAccount::truncate();
TradeLog::truncate();
Signal::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Get API KEY from BotSetting (optional - we're not using it in this script)
$apiKey = 'test_api_key_12345';

$signals = [
    [
        'account'   => 105338607,
        'ticket'    => 'SIG_TEST_001',
        'symbol'    => 'EURUSD',
        'direction' => 'buy',
        'entry'     => 1.0845,
        'sl'        => 1.0800,
        'tp'        => 1.0900,
        'timeframe' => 'H1',
        'lots'      => 1.0,
    ],
    [
        'account'   => 105338607,
        'ticket'    => 'SIG_TEST_002',
        'symbol'    => 'GBPUSD',
        'direction' => 'sell',
        'entry'     => 1.2765,
        'sl'        => 1.2800,
        'tp'        => 1.2700,
        'timeframe' => 'H1',
        'lots'      => 0.5,
    ],
    [
        'account'   => 105338607,
        'ticket'    => 'SIG_TEST_003',
        'symbol'    => 'USDJPY',
        'direction' => 'buy',
        'entry'     => 149.85,
        'sl'        => 148.50,
        'tp'        => 151.20,
        'timeframe' => '4H',
        'lots'      => 2.0,
    ],
];

// Try to send signals via HTTP (internal loopback will work if server is running)
// For now, let's simulate what the controller does instead
echo "Simulating signal distribution (like the controller would do)...\n\n";

foreach ($signals as $data) {
    echo "Processing signal: {$data['ticket']} ({$data['symbol']})\n";

    // Create Signal
    $signal = Signal::create([
        'ticket'    => $data['ticket'],
        'symbol'    => $data['symbol'],
        'direction' => $data['direction'],
        'entry'     => $data['entry'],
        'sl'        => $data['sl'],
        'tp'        => $data['tp'],
        'timeframe' => $data['timeframe'],
        'active'    => true,
    ]);
    echo "  ✓ Signal created (ID: {$signal->id})\n";

    // Create TradeLog for the requesting account
    $account = Account::where('login', $data['account'])->first();
    if ($account) {
        $tradeLog = TradeLog::create([
            'account_id'  => $account->id,
            'ticket'      => $data['ticket'],
            'symbol'      => $data['symbol'],
            'type'        => $data['direction'],
            'lots'        => $data['lots'],
            'open_price'  => $data['entry'],
            'sl'          => $data['sl'],
            'tp'          => $data['tp'],
        ]);
        echo "  ✓ TradeLog created for account {$account->login} (ID: {$tradeLog->id})\n";
    } else {
        echo "  ✗ Account {$data['account']} not found\n";
    }

    // Distribute to all active accounts
    $activeAccounts = Account::where('active', true)->get();
    foreach ($activeAccounts as $acc) {
        $signalAccount = SignalAccount::create([
            'signal_id'  => $signal->id,
            'account_id' => $acc->id,
            'status'     => 'pending',
            'ticket'     => $data['ticket'],
        ]);
        echo "    → Distributed to {$acc->login}\n";
    }

    echo "\n";
}

echo "=== DISTRIBUTION COMPLETE ===\n\n";

// Show final state
$signals = Signal::count();
$signalAccounts = SignalAccount::count();
$tradeLogs = TradeLog::count();

echo "Signals created: {$signals}\n";
echo "SignalAccount records: {$signalAccounts}\n";
echo "TradeLog records: {$tradeLogs}\n";

echo "\n=== SIGNAL DISTRIBUTION DETAILS ===\n";
foreach (Signal::all() as $sig) {
    $distributed = SignalAccount::where('signal_id', $sig->id)->count();
    echo "{$sig->ticket} ({$sig->symbol}): distributed to {$distributed} accounts\n";
}
