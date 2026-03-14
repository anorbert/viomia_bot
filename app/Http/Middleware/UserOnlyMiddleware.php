<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Strict user-only middleware
 * Prevents admins and editors from accessing user resources
 * 
 * Usage: middleware('user-only')
 */
class UserOnlyMiddleware
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
            return redirect('/login')->with('error', 'Access denied. Regular user access required.');
        }

        $user = Auth::user();

        // Only role_id = 3 (regular user) is allowed
        if ($user->role_id !== 3) {
            $roleNames = [1 => 'admin', 2 => 'editor', 3 => 'user'];
            $userRole = $roleNames[$user->role_id] ?? 'unknown';

            \Log::warning(sprintf(
                'User-only resource access denied: %s user (ID: %s, Email: %s) attempted %s %s',
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
            return redirect()->back()->with('error', 'Administrators and editors cannot access user-only resources.');
        }

        return $next($request);
    }
}
