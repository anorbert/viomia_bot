<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Activity Tracker Controller
 * 
 * Handles user activity updates to prevent auto-logout
 * Called via AJAX from client-side JavaScript
 */
class ActivityTrackerController extends Controller
{
    /**
     * Update user's last activity timestamp
     * 
     * This endpoint should be called periodically to keep the session alive
     * JavaScript on client will call this endpoint on page interaction (click, scroll, keypress)
     */
    public function trackActivity(Request $request)
    {
        if (Auth::check()) {
            // Update last activity time in session
            Session::put('last_activity_time', time());
            Session::save();

            return response()->json([
                'success' => true,
                'message' => 'Activity tracked',
                'timestamp' => time()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not authenticated'
        ], 401);
    }

    /**
     * Get remaining time before auto-logout
     * 
     * Returns the number of seconds remaining before session timeout
     */
    public function getRemainingTime(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $lastActivityTime = Session::get('last_activity_time', time());
        $currentTime = time();
        $inactivityDuration = $currentTime - $lastActivityTime;
        $sessionTimeout = 600; // 10 minutes in seconds
        $remainingTime = max(0, $sessionTimeout - $inactivityDuration);

        return response()->json([
            'success' => true,
            'remaining_seconds' => $remainingTime,
            'session_timeout' => $sessionTimeout,
            'inactive_seconds' => $inactivityDuration
        ]);
    }

    /**
     * Force logout the current user
     */
    public function forceLogout(Request $request)
    {
        if (Auth::check()) {
            \Log::info(sprintf(
                'User logged out: User ID: %s, Email: %s',
                Auth::user()->id,
                Auth::user()->email
            ));

            Auth::logout();
        }

        Session::invalidate();
        Session::regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
