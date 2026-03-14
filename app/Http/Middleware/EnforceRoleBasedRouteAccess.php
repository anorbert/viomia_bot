<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Advanced middleware to enforce strict role-based route access
 * Prevents cross-role resource access with granular control
 * 
 * Usage: middleware('enforce-role-access:admin') or middleware('enforce-role-access:user,editor')
 */
class EnforceRoleBasedRouteAccess
{
    protected $roleMap = [
        1 => 'admin',
        2 => 'editor',
        3 => 'user',
    ];

    protected $rolePriority = [
        'admin' => 1,
        'editor' => 2,
        'user' => 3,
    ];

    public function handle(Request $request, Closure $next, ...$allowedRoles)
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
        $userRole = $this->roleMap[$user->role_id] ?? null;

        if (!$userRole) {
            \Log::error(sprintf(
                'Invalid role ID for user: %s (Role ID: %s)',
                $user->id,
                $user->role_id
            ));
            abort(500, 'Invalid user role configuration');
        }

        // Check if user's role is in allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            \Log::warning(sprintf(
                'Access denied: User role "%s" not allowed. Required roles: "%s". User ID: %s, Route: %s %s',
                $userRole,
                implode(', ', $allowedRoles),
                $user->id,
                $request->getMethod(),
                $request->getPathInfo()
            ));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Your role does not have access to this resource.');
        }

        return $next($request);
    }
}
