<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Signal;
use App\Models\SignalAccount;
use App\Models\TradeLog;
use App\Models\Account;
use App\Models\TradeEvent;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         CREATE HIGH-QUALITY SIGNALS (RR ≥ 2.0)                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Clean up first
DB::statement('SET FOREIGN_KEY_CHECKS=0');
SignalAccount::truncate();
TradeEvent::truncate();
Signal::truncate();
TradeLog::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// High-quality signals with RR >= 2.0
$signals = [
    // EURUSD - Good buy opportunity
    [
        'account'   => 105338607,
        'ticket'    => 'GOOD_EURUSD_001',
        'symbol'    => 'EURUSD',
        'direction' => 'buy',
        'entry'     => 1.0845,
        'sl'        => 1.0800,      // Risk: 45 pips
        'tp'        => 1.0935,      // Reward: 90 pips → RR: 1:2.0 ✓
        'timeframe' => 'H1',
        'lots'      => 1.0,
    ],
    // GBPUSD - Excellent sell opportunity
    [
        'account'   => 105338607,
        'ticket'    => 'GOOD_GBPUSD_001',
        'symbol'    => 'GBPUSD',
        'direction' => 'sell',
        'entry'     => 1.2765,
        'sl'        => 1.2815,      // Risk: 50 pips
        'tp'        => 1.2665,      // Reward: 100 pips → RR: 1:2.0 ✓
        'timeframe' => 'H1',
        'lots'      => 0.5,
    ],
    // USDJPY - Strong buy with excellent RR
    [
        'account'   => 105338607,
        'ticket'    => 'GOOD_USDJPY_001',
        'symbol'    => 'USDJPY',
        'direction' => 'buy',
        'entry'     => 149.85,
        'sl'        => 148.50,      // Risk: 135 pips
        'tp'        => 152.55,      // Reward: 270 pips → RR: 1:2.0 ✓
        'timeframe' => '4H',
        'lots'      => 2.0,
    ],
    // AUDUSD - Solid buy opportunity
    [
        'account'   => 105338607,
        'ticket'    => 'GOOD_AUDUSD_001',
        'symbol'    => 'AUDUSD',
        'direction' => 'buy',
        'entry'     => 0.6735,
        'sl'        => 0.6700,      // Risk: 35 pips
        'tp'        => 0.6805,      // Reward: 70 pips → RR: 1:2.0 ✓
        'timeframe' => 'H4',
        'lots'      => 1.5,
    ],
    // NZDUSD - Premium signal with RR 1:2.5
    [
        'account'   => 105338607,
        'ticket'    => 'GOOD_NZDUSD_001',
        'symbol'    => 'NZDUSD',
        'direction' => 'sell',
        'entry'     => 0.6125,
        'sl'        => 0.6160,      // Risk: 35 pips
        'tp'        => 0.6037,      // Reward: 88 pips → RR: 1:2.51 ✓
        'timeframe' => 'D1',
        'lots'      => 1.0,
    ],
];

echo "Creating " . count($signals) . " high-quality signals (all with RR ≥ 2.0)...\n\n";

$created = 0;
$distributed = 0;

foreach ($signals as $data) {
    // Calculate RR for display
    if ($data['direction'] === 'buy') {
        $risk = $data['entry'] - $data['sl'];
        $reward = $data['tp'] - $data['entry'];
    } else {
        $risk = $data['sl'] - $data['entry'];
        $reward = $data['entry'] - $data['tp'];
    }
    $rr = $risk > 0 ? $reward / $risk : 0;

    echo "Creating signal: " . $data['ticket'] . " ({$data['symbol']})\n";
    echo "  Direction: " . strtoupper($data['direction']) . " @ {$data['entry']}\n";
    echo "  SL: {$data['sl']} | TP: {$data['tp']}\n";
    echo "  Risk/Reward: 1:" . number_format($rr, 2) . (($rr >= 2.0) ? " ✓ ACCEPTED" : " ✗ REJECTED") . "\n";

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
    $created++;

    // Create TradeLog for requesting account
    $account = Account::where('login', $data['account'])->first();
    if ($account) {
        TradeLog::create([
            'account_id'  => $account->id,
            'ticket'      => $data['ticket'],
            'symbol'      => $data['symbol'],
            'type'        => $data['direction'],
            'lots'        => $data['lots'],
            'open_price'  => $data['entry'],
            'sl'          => $data['sl'],
            'tp'          => $data['tp'],
        ]);
    }

    // Distribute to all active accounts
    $activeAccounts = Account::where('active', true)->get();
    foreach ($activeAccounts as $acc) {
        SignalAccount::create([
            'signal_id'  => $signal->id,
            'account_id' => $acc->id,
            'status'     => 'pending',
            'ticket'     => $data['ticket'],
        ]);
        $distributed++;
    }

    echo "\n";
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      CREATION SUMMARY                           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "Signals Created:      " . $created . "\n";
echo "Distributions Made:   " . $distributed . " (signals × active accounts)\n";
echo "Total Signal-Accounts: " . SignalAccount::count() . "\n";
echo "Total Trade Logs:     " . TradeLog::count() . "\n";

echo "\n════════════════════════════════════════════════════════════════\n\n";

// Now show what happens when bot analyzes these
echo "ANALYZING SIGNALS WITH BOT LOGIC...\n\n";

$accepted = 0;
$rejected = 0;

foreach (Signal::all() as $signal) {
    if ($signal->direction === 'buy') {
        $risk = $signal->entry - $signal->sl;
        $reward = $signal->tp - $signal->entry;
    } else {
        $risk = $signal->sl - $signal->entry;
        $reward = $signal->entry - $signal->tp;
    }

    $rr = $risk > 0 ? $reward / $risk : 0;
    $status = $rr >= 2.0 ? "✓ ACCEPTED" : "✗ REJECTED";
    
    if ($rr >= 2.0) {
        $accepted++;
    } else {
        $rejected++;
    }

    echo "{$signal->ticket}: {$status} (RR: 1:" . number_format($rr, 2) . ")\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "\nTrading Bot Analysis Results:\n";
echo "  • Signals that will be ACCEPTED: {$accepted}\n";
echo "  • Signals that will be REJECTED: {$rejected}\n";
echo "  • Acceptance rate: " . ($created > 0 ? round(($accepted / $created) * 100) : 0) . "%\n";

echo "\n✓ All signals are ready for trading!\n";
echo "  Run: php check_trading_bot.php\n";
echo "  To see the bot execute these high-quality trades.\n";
