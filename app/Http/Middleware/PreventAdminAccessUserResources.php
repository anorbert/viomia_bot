<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Prevents admin users from accessing user-specific resources
 * Admins should use /admin routes, not /user routes
 */
class PreventAdminAccessUserResources
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
            return redirect('/login')->with('error', 'Access denied. Please login as a valid user.');
        }

        $user = Auth::user();
        
        // Role ID 1 = admin, don't allow admins here
        if ($user->role_id === 1) {
            \Log::warning(sprintf(
                'Admin user attempted to access user resource: %s %s. User ID: %s',
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
            return redirect()->back()->with('error', 'Admins cannot access user resources. Please use the admin dashboard.');
        }

        return $next($request);
    }
}
