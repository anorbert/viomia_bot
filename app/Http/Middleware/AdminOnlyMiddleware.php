<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Strict admin-only middleware
 * Rejects all non-admin users with detailed logging
 * 
 * Usage: middleware('admin-only')
 */
class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.'
                ], 403);
            }
            return redirect('/login')->with('error', 'Access denied. Admin privileges required.');
        }

        $user = Auth::user();

        // Only role_id = 1 (admin) is allowed
        if ($user->role_id !== 1) {
            $roleNames = [1 => 'admin', 2 => 'editor', 3 => 'user'];
            $userRole = $roleNames[$user->role_id] ?? 'unknown';

            \Log::warning(sprintf(
                'Admin-only resource access denied: %s user (ID: %s, Email: %s) attempted %s %s',
                $userRole,
                $user->id,
                $user->email,
                $request->getMethod(),
                $request->getPathInfo()
            ));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You must be an administrator to access this resource.');
        }

        return $next($request);
    }
}
