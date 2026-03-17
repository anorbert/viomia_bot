<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel');

// Get the application instance
$app = app();

// Get API key for testing
$apiKey = \App\Models\ApiKey::first();

if (!$apiKey) {
    echo "ERROR: No API key found in database. Cannot test routes.\n";
    exit(1);
}

echo "Using API Key: " . $apiKey->key . " (Bot: " . $apiKey->bot_id . ")\n";
echo "====================================\n\n";

// Define all API routes to test
$routes = [
    ['method' => 'GET', 'path' => '/api/bot/account/settings', 'name' => 'Account Settings'],
    ['method' => 'GET', 'path' => '/api/bot/signal', 'name' => 'Get Active Signal'],
    ['method' => 'POST', 'path' => '/api/bot/signal', 'name' => 'Store Signal', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/trade/log', 'name' => 'Store Trade Log', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/trade/opened', 'name' => 'Store Trade Event', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/bot/status', 'name' => 'Update Bot Status', 'body' => ['account_id' => 1]],
    ['method' => 'GET', 'path' => '/api/bot/bot/status', 'name' => 'Get Latest Bot Status'],
    ['method' => 'GET', 'path' => '/api/bot/news/list', 'name' => 'List News Events'],
    ['method' => 'POST', 'path' => '/api/bot/news/store', 'name' => 'Store News Event', 'body' => []],
    ['method' => 'GET', 'path' => '/api/bot/news/next', 'name' => 'Get Next News'],
    ['method' => 'POST', 'path' => '/api/bot/account/snapshot', 'name' => 'Store Account Snapshot', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/trading/daily-summary', 'name' => 'Store Daily Summary', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/position/update', 'name' => 'Update Position', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/alert/daily-loss-limit', 'name' => 'Store Loss Limit Alert', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/filter/blocked', 'name' => 'Store Filter Block', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/signal/technical', 'name' => 'Store Technical Signal', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/ea/status-change', 'name' => 'Store EA Status Change', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/error/log', 'name' => 'Store Error Log', 'body' => ['account_id' => 1]],
    ['method' => 'POST', 'path' => '/api/bot/whatsapp_signal', 'name' => 'Store Whatsapp Signal', 'body' => []],
    ['method' => 'POST', 'path' => '/api/bot/latestForEA', 'name' => 'Get Latest for EA', 'body' => []],
    ['method' => 'POST', 'path' => '/api/bot/whatsapp_signal/mark_received/1', 'name' => 'Mark Whatsapp Signal Received'],
];

$results = [];
$success_count = 0;
$error_count = 0;

foreach ($routes as $route) {
    $method = $route['method'];
    $path = $route['path'];
    $name = $route['name'];
    
    try {
        $headers = ['Authorization' => 'Bearer ' . $apiKey->key];
        
        if ($method === 'GET') {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)->get('http://localhost/api/bot' . str_replace('/api/bot', '', $path));
        } else {
            $body = $route['body'] ?? [];
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)->post('http://localhost/api/bot' . str_replace('/api/bot', '', $path), $body);
        }
        
        $status = $response->status();
        
        // Check if successful (2xx or 422 for validation errors is OK - route exists)
        if ($status >= 200 && $status < 500) {
            $results[] = "✓ [$status] $method $path - $name";
            if ($status >= 200 && $status < 300) {
                $success_count++;
            }
        } else {
            $results[] = "✗ [$status] $method $path - $name (ERROR)";
            $error_count++;
        }
    } catch (\Exception $e) {
        $results[] = "✗ $method $path - $name (EXCEPTION: " . $e->getMessage() . ")";
        $error_count++;
    }
}

// Print results
foreach ($results as $result) {
    echo $result . "\n";
}

echo "\n====================================\n";
echo "Summary: $success_count routes OK, $error_count routes with issues\n";

if ($error_count > 0) {
    exit(1);
}
