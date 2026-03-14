<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Prevents regular users from accessing admin-specific resources
 * Users should use /user routes, not /admin routes
 */
class PreventUserAccessAdminResources
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
            return redirect('/login')->with('error', 'Access denied. Admin access required.');
        }

        $user = Auth::user();
        
        // Role ID 3 = user, deny access to admin resources
        if ($user->role_id === 3) {
            \Log::warning(sprintf(
                'Regular user attempted to access admin resource: %s %s. User ID: %s',
                $request->getMethod(),
                $request->getPathInfo(),
                $user->id
            ));
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You do not have permission to access admin resources.');
        }

        return $next($request);
    }
}
