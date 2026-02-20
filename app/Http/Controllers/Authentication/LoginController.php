<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Display login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('welcome');
    }

    public function index()
    {
        return $this->showLoginForm();
    }

    /**
     * Show change PIN form
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('error', 'Please log in first.');
        }

        return view('change-pin', compact('user'));
    }

    /**
     * Login using phone + hashed PIN
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'pin'   => ['required'],
        ]);

        // Find the user
        $user = User::where('phone_number', $request->phone)->first();

        // If user not found or pin mismatch
        if (!$user || !Hash::check($request->pin, $user->password)) {
            Log::warning('Failed login attempt', ['phone' => $request->phone]);
            return back()
                ->withInput($request->only('phone'))
                ->with('error', 'Invalid phone number or PIN.');
        }

        // Check if deactivated
        // if (!$user->is_active) {
        //     return back()
        //         ->withInput($request->only('phone'))
        //         ->with('error', 'Your account is deactivated.');
        // }

        // Login user
        Auth::login($user, $request->boolean('remember'));

        // Update login timestamp
        $user->update(['last_login_at' => now()]);

        // Force default PIN change
        if ($user->is_default_pin) {
            return redirect()->route('user.change-pin.create')
                ->with('warning', 'Please change your default PIN.');
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Change PIN
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('error', 'Please log in first.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current PIN is incorrect']);
        }

        if ($request->current_password === $request->new_password) {
            return back()->withErrors(['new_password' => 'New PIN must be different from current PIN.']);
        }

        $user->update([
            'password'       => Hash::make($request->new_password),
            'is_default_pin' => false,
        ]);

        Auth::logout();

        return redirect('/')
            ->with('success', 'PIN changed successfully. Please log in again.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info('User logged out. User ID: ' . Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    /**
     * Redirect user based on role
     */
    private function redirectBasedOnRole(User $user)
    {
        return match ((int) $user->role_id) {
            1       => redirect()->route('admin.dashboard'),
            2       => redirect()->route('editor.dashboard'),
            3       => redirect()->route('user.dashboard'),
            default => abort(403, 'Unauthorized role'),
        };
    }
}
