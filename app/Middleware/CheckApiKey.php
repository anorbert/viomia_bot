<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get API key from header
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
            ], 401);
        }

        // Verify API key exists and is active
        $key = ApiKey::where('key', $apiKey)
            // ->where('status', 'active')
            ->first();

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive API key',
            ], 401);
        }

        // Store API key info in request for later use
        $request->apiKey = $key;

        return $next($request);
    }
}
