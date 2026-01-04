<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'Your account has been deactivated.');
            }

            // Check if user account hasn't been deleted
            if ($user->trashed()) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'Your account has been deleted.');
            }
        }

        return $next($request);
    }
}
