<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

$db = new PDO('mysql:host=127.0.0.1;dbname=viomia_bot', 'root', '');

// Check current time
echo "=== TIME INFO ===\n";
echo "Current UTC Time: " . date('Y-m-d H:i:s', time()) . "\n";
echo "Laravel now(): " . \Carbon\Carbon::now()->toDateTimeString() . "\n\n";

// Get today's date range
$today = \Carbon\Carbon::now()->startOfDay();
$todayEnd = \Carbon\Carbon::now()->endOfDay();

echo "=== TODAY DATE RANGE ===\n";
echo "Start: " . $today->toDateTimeString() . "\n";
echo "End: " . $todayEnd->toDateTimeString() . "\n";
echo "Start (Y-m-d): " . $today->format('Y-m-d') . "\n\n";

// Query trades for today
$stmt = $db->query("
    SELECT id, ticket, symbol, profit, created_at 
    FROM trade_logs 
    WHERE created_at >= '{$today->toDateTimeString()}' 
    AND created_at <= '{$todayEnd->toDateTimeString()}'
    ORDER BY created_at DESC
    LIMIT 20
");

echo "=== TRADES FOR TODAY ===\n";
$count = 0;
foreach ($stmt as $row) {
    echo "ID: {$row['id']} | Ticket: {$row['ticket']} | Symbol: {$row['symbol']} | Profit: {$row['profit']} | Created: {$row['created_at']}\n";
    $count++;
}
echo "Total: $count trades\n\n";

// Get all trades from last 30 days to see what data exists
$stmt2 = $db->query("
    SELECT DATE(created_at) as trade_date, COUNT(*) as count, SUM(COALESCE(profit,0)) as pnl
    FROM trade_logs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY trade_date DESC
    LIMIT 30
");

echo "=== LAST 30 DAYS SUMMARY ===\n";
foreach ($stmt2 as $row) {
    echo "Date: {$row['trade_date']} | Count: {$row['count']} | P&L: {$row['pnl']}\n";
}
