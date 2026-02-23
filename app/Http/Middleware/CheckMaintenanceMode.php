<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SettingsService;

class CheckMaintenanceMode
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
        // Skip check for admin users
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            return $next($request);
        }

        // Check if maintenance mode is enabled
        if (SettingsService::isEnabled('maintenance_mode')) {
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
