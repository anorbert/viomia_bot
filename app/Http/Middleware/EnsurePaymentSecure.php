<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaymentSecure
{
    /**
     * Handle incoming payment requests with security checks
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enforce HTTPS
        if (!$request->secure() && !app()->isLocal()) {
            return response()->json(['error' => 'HTTPS required'], 403);
        }

        // Only allow from MOMO IP whitelist for webhook
        if ($request->routeIs('payments.momo-webhook')) {
            if (!$this->isAllowedMomoIp($request->ip())) {
                return response()->json(['error' => 'IP not whitelisted'], 403);
            }
        }

        return $next($request);
    }

    /**
     * Check if IP is in MOMO whitelist
     */
    private function isAllowedMomoIp($ip)
    {
        // Get MOMO IP whitelist from config
        $whitelist = config('services.momo.ip_whitelist', []);
        
        // Allow localhost for testing
        if (app()->isLocal() && ($ip === '127.0.0.1' || $ip === '::1')) {
            return true;
        }

        return in_array($ip, $whitelist);
    }
}
