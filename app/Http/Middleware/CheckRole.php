<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = $user->role_id;

        // Map role IDs to role names for easier checking
        $roleMap = [
            1 => 'admin',
            2 => 'editor',
            3 => 'user',
        ];

        $userRoleName = $roleMap[$userRole] ?? null;

        // Check if user has required role
        if (!in_array($userRoleName, $roles)) {
            \Log::warning('Unauthorized access attempt. User ID: ' . $user->id . ' User Role: ' . $userRoleName . ' Required Roles: ' . implode(',', $roles));
            abort(403, 'Unauthorized. Required role: ' . implode(' or ', $roles));
        }

        return $next($request);
    }
}
