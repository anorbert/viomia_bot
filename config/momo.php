<?php

return [
    'momo' => [
        // API Configuration
        'api_key' => env('MOMO_API_KEY', ''),
        'api_user' => env('MOMO_API_USER', ''),
        'api_secret' => env('MOMO_API_SECRET', ''),
        
        // Primary and secondary API endpoints
        'primary_endpoint' => env('MOMO_PRIMARY_ENDPOINT', 'https://sandbox.momoapi.mtn.com'),
        'secondary_endpoint' => env('MOMO_SECONDARY_ENDPOINT', 'https://proxy.momoapi.mtn.com'),
        
        // IP Whitelist (MOMO server IPs that can send webhooks)
        // Add actual MOMO IPs in .env file as: MOMO_IP_WHITELIST=ip1,ip2,ip3
        'ip_whitelist' => array_filter(
            array_map('trim', 
                explode(',', env('MOMO_IP_WHITELIST', ''))
            )
        ),
        
        // Webhook settings
        'webhook' => [
            'timeout' => env('MOMO_WEBHOOK_TIMEOUT', 30),
            'retry_attempts' => env('MOMO_WEBHOOK_RETRY', 3),
            'signature_algorithm' => 'sha256', // HMAC-SHA256
        ],
        
        // Currency settings
        'currency' => env('MOMO_CURRENCY', 'RWF'), // Rwanda Franc
        
        // Rate limiting for webhook
        'rate_limit' => [
            'max_requests' => env('MOMO_RATE_LIMIT_REQUESTS', 100),
            'per_minutes' => env('MOMO_RATE_LIMIT_MINUTES', 1),
        ],
    ],
];
