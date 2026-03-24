<?php
$db = new PDO('mysql:host=127.0.0.1;dbname=viomia_bot', 'root', '');

// Get all trades by date
$stmt = $db->query("
    SELECT DATE(created_at) as trade_date, COUNT(*) as count, SUM(COALESCE(profit,0)) as pnl
    FROM trade_logs
    GROUP BY DATE(created_at)
    ORDER BY trade_date DESC
    LIMIT 30
");

$output = "=== TRADES BY DATE ===\n";
foreach ($stmt as $row) {
    $output .= "Date: {$row['trade_date']} | Count: {$row['count']} | P&L: {$row['pnl']}\n";
}

// Also get the latest trade
$stmt2 = $db->query("SELECT MAX(created_at) as latest_trade FROM trade_logs");
$latest = $stmt2->fetch();
$output .= "\nLatest trade timestamp: " . ($latest['latest_trade'] ?? 'No trades') . "\n";

// Get current date in database
$stmt3 = $db->query("SELECT NOW() as current_time");
$current = $stmt3->fetch();
$output .= "Current DB time: " . $current['current_time'] . "\n";

file_put_contents('debug_output.txt', $output);
echo "Output written to debug_output.txt\n";
?>
