<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Auto-logout middleware after inactivity period
 * 
 * If user has not performed any action for 10 minutes (600 seconds),
 * they will be automatically logged out
 * 
 * Last activity is tracked in session['last_activity_time']
 */
class AutoLogoutMiddleware
{
    /**
     * Session timeout in seconds (10 minutes = 600 seconds)
     */
    private const SESSION_TIMEOUT = 600;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Get last activity time from session
            $lastActivityTime = Session::get('last_activity_time', time());
            $currentTime = time();
            $inactivityDuration = $currentTime - $lastActivityTime;

            // Check if user has been inactive for more than SESSION_TIMEOUT
            if ($inactivityDuration > self::SESSION_TIMEOUT) {
                // Record session end before logout
                $currentUser = Auth::user();
                if ($currentUser) {
                    $currentUser->recordSessionEnd();
                }

                // Log the auto-logout
                \Log::info(sprintf(
                    'Auto-logout due to inactivity: User ID: %s, Email: %s, Inactive for: %d seconds',
                    Auth::user()->id,
                    Auth::user()->email,
                    $inactivityDuration
                ));

                // Logout the user
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();

                // Return error response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your session has expired due to inactivity. Please log in again.',
                        'error' => 'SESSION_EXPIRED'
                    ], 401);
                }

                return redirect('/login')->with('warning', 'Your session has expired due to inactivity. Please log in again.');
            }

            // Update last activity time for this request
            Session::put('last_activity_time', $currentTime);
        }

        return $next($request);
    }
}
