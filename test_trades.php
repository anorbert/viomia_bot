<?php
/**
 * Simple test to verify AdminController today's trades are being counted correctly
 * Run this via: php artisan tinker < test_trades.php
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TradeLog;
use Illuminate\Support\Facades\DB;

// Test queries
echo "=== TESTING TODAY'S TRADE QUERIES ===\n\n";

// Method 1: Using DATE function (new method)
$count1 = TradeLog::whereRaw("DATE(created_at) = CURDATE()")->count();
echo "Method 1 (DATE function): $count1 trades\n";

// Method 2: Using Carbon (old method)
$today = now()->startOfDay();
$count2 = TradeLog::where('created_at', '>=', $today)->count();
echo "Method 2 (Carbon comparison): $count2 trades\n\n";

// Show detailed trade data for today
echo "=== TODAY'S TRADES DETAIL ===\n";
$trades = TradeLog::whereRaw("DATE(created_at) = CURDATE()")
    ->select('id', 'ticket', 'symbol', 'profit', 'created_at')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

if ($trades->count() === 0) {
    echo "No trades found for today\n";
} else {
    foreach ($trades as $trade) {
        echo "ID: {$trade->id} | Ticket: {$trade->ticket} | Symbol: {$trade->symbol} | Profit: {$trade->profit} | Created: {$trade->created_at}\n";
    }
}

echo "\n=== TRADE SUMMARY ===\n";
$pnl = TradeLog::whereRaw("DATE(created_at) = CURDATE()")->sum(DB::raw('COALESCE(profit,0)'));
$wins = TradeLog::whereRaw("DATE(created_at) = CURDATE()")->where('profit', '>', 0)->count();
$losses = TradeLog::whereRaw("DATE(created_at) = CURDATE()")->where('profit', '<', 0)->count();

echo "Total Trades: $count1\n";
echo "Total P&L: " . number_format($pnl, 2) . "\n";
echo "Wins: $wins\n";
echo "Losses: $losses\n";

echo "\n✅ Test complete\n";
exit(0);
