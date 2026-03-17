#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Http\Request;
use App\Models\ApiKey;
use App\Models\Account;

// Get or create a test API key
$apiKey = ApiKey::first();
if (!$apiKey) {
    echo "❌ ERROR: No API keys found in database. Please create one first.\n";
    exit(1);
}

echo "📋 API ROUTE TEST REPORT\n";
echo "=======================\n\n";
echo "Using API Key: {$apiKey->key}\n";
echo "Bot ID: {$apiKey->bot_id}\n\n";

// Define test routes with their expected methods and sample payloads
$routes = [
    [
        'method' => 'GET',
        'path' => '/api/bot/account/settings',
        'description' => 'Account Settings',
        'params' => ['account' => 1],
        'expect_auth_error' => false
    ],
    [
        'method' => 'GET',
        'path' => '/api/bot/signal',
        'description' => 'Get Active Signal',
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/signal',
        'description' => 'Store Signal',
        'body' => ['account_id' => 1],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/trade/log',
        'description' => 'Trade Log',
        'body' => ['account_id' => 1],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/trade/opened',
        'description' => 'Trade Event',
        'body' => ['ticket' => '12345', 'account_id' => 1],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/bot/status',
        'description' => 'Update Bot Status',
        'body' => ['status' => 'running'],
        'expect_auth_error' => false
    ],
    [
        'method' => 'GET',
        'path' => '/api/bot/bot/status',
        'description' => 'Get Latest Bot Status',
        'expect_auth_error' => false
    ],
    [
        'method' => 'GET',
        'path' => '/api/bot/news/list',
        'description' => 'List News Events',
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/news/store',
        'description' => 'Store News Event',
        'body' => [],
        'expect_auth_error' => false
    ],
    [
        'method' => 'GET',
        'path' => '/api/bot/news/next',
        'description' => 'Get Next News',
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/account/snapshot',
        'description' => 'Account Snapshot',
        'body' => ['account_id' => 1],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/trading/daily-summary',
        'description' => 'Daily Summary',
        'body' => ['daily_pl' => 100],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/position/update',
        'description' => 'Position Update',
        'body' => ['ticket' => '123', 'entry_price' => 1.100],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/alert/daily-loss-limit',
        'description' => 'Loss Limit Alert',
        'body' => ['daily_loss' => 200],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/filter/blocked',
        'description' => 'Filter Block',
        'body' => ['filter_type' => 'ASIA'],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/signal/technical',
        'description' => 'Technical Signal',
        'body' => ['trend_score' => 0.5],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/ea/status-change',
        'description' => 'EA Status Change',
        'body' => ['status' => 'started'],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/error/log',
        'description' => 'Error Log',
        'body' => ['error_type' => 'CONNECTION', 'error_message' => 'test'],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/whatsapp_signal',
        'description' => 'Whatsapp Signal',
        'body' => [],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/latestForEA',
        'description' => 'Latest For EA',
        'body' => [],
        'expect_auth_error' => false
    ],
    [
        'method' => 'POST',
        'path' => '/api/bot/whatsapp_signal/mark_received/1',
        'description' => 'Mark Signal Received',
        'expect_auth_error' => false
    ],
];

$passed = 0;
$failed = 0;
$issues = [];

// Test each route
foreach ($routes as $index => $route) {
    $method = $route['method'];
    $path = $route['path'];
    $description = $route['description'];
    
    try {
        // Create a test request
        $uri = $path;
        $server = [
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri,
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 80,
            'HTTP_X_API_KEY' => $apiKey->key,
            'CONTENT_TYPE' => 'application/json',
        ];
        
        // Create request with appropriate input
        $input = [];
        $query = [];
        
        if ($method === 'GET' && isset($route['params'])) {
            $query = $route['params'];
        } elseif ($method === 'POST' && isset($route['body'])) {
            $input = $route['body'];
        }
        
        $request = Request::create($uri, $method, $input, [], [], $server, json_encode($input));
        $request->headers->set('X-API-KEY', $apiKey->key);
        
        // Dispatch to router
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        
        // Check if route exists and is not 404
        if ($status === 404) {
            echo "❌ [{$status}] $method $path - $description (ROUTE NOT FOUND)\n";
            $failed++;
            $issues[] = "$description: Route returned 404";
        } elseif ($status === 401) {
            echo "⚠️  [{$status}] $method $path - $description (UNAUTHORIZED - check middleware)\n";
            $failed++;
            $issues[] = "$description: Authentication failed";
        } elseif ($status >= 500) {
            echo "❌ [{$status}] $method $path - $description (SERVER ERROR)\n";
            $failed++;
            $issues[] = "$description: Server error (500+)";
        } else {
            echo "✅ [{$status}] $method $path - $description\n";
            $passed++;
        }
        
    } catch (\Exception $e) {
        echo "❌ $method $path - $description (EXCEPTION)\n";
        echo "   Error: " . $e->getMessage() . "\n";
        $failed++;
        $issues[] = "$description: " . $e->getMessage();
    }
}

echo "\n=======================\n";
echo "SUMMARY\n";
echo "=======================\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: $failed\n";
echo "Total: " . ($passed + $failed) . "\n";

if ($failed > 0) {
    echo "\n⚠️  ISSUES FOUND:\n";
    foreach ($issues as $issue) {
        echo "  - $issue\n";
    }
}

exit($failed > 0 ? 1 : 0);
