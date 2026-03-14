<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Verify that users can only access resources they own
 * Prevents users from accessing other users' data
 * Admins can access all user resources
 * 
 * This middleware checks the {id} or {user_id} parameter in the route
 */
class VerifyResourceOwnership
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
            return redirect('/login')->with('error', 'Access denied. Authentication required.');
        }

        $user = Auth::user();
        
        // Admins (role_id = 1) can access all resources
        if ($user->role_id === 1) {
            return $next($request);
        }

        // For regular users, check if they own the resource
        $resourceId = $request->route('id') ?? $request->route('user_id') ?? null;

        if ($resourceId) {
            // Ensure user can only access their own resources
            if ($resourceId !== $user->id && $resourceId !== $user->uuid) {
                \Log::warning(sprintf(
                    'Resource ownership violation: User %s (ID: %s) attempted to access resource %s',
                    $user->email,
                    $user->id,
                    $resourceId
                ));

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'You can only access your own resources.');
            }
        }

        return $next($request);
    }
}
