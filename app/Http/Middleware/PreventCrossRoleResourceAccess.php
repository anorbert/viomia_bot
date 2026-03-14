<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Advanced middleware to prevent any cross-role resource access
 * Analyzes request URI and enforces strict boundaries
 * 
 * Rules:
 * - /admin/* routes → Only role_id=1 (admin)
 * - /user/* routes → Only role_id=3 (user)
 * - /editor/* routes → Only role_id=2 (editor)
 */
class PreventCrossRoleResourceAccess
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
            return redirect('/login')->with('error', 'Access denied. Please login.');
        }

        $user = Auth::user();
        $path = $request->getPathInfo();

        // Define route prefixes and required roles
        $routeRestrictions = [
            '/admin' => 1,      // Only admin (role_id = 1)
            '/editor' => 2,     // Only editor (role_id = 2)
            '/user' => 3,       // Only user (role_id = 3)
        ];

        // Check each restriction
        foreach ($routeRestrictions as $routePrefix => $requiredRoleId) {
            if (strpos($path, $routePrefix) === 0) {
                if ($user->role_id !== $requiredRoleId) {
                    $roleNames = [1 => 'admin', 2 => 'editor', 3 => 'user'];
                    $userRoleName = $roleNames[$user->role_id] ?? 'unknown';
                    $requiredRoleName = $roleNames[$requiredRoleId] ?? 'unknown';

                    \Log::warning(sprintf(
                        'Cross-role access attempt blocked: %s user (ID: %s) tried to access %s resource at %s %s',
                        $userRoleName,
                        $user->id,
                        $requiredRoleName,
                        $request->getMethod(),
                        $path
                    ));

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Access denied.'
                        ], 403);
                    }
                    return redirect()->back()->with('error', 'You do not have access to this section of the application.');
                }
            }
        }

        return $next($request);
    }
}
