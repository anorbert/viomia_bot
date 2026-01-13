<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    /**
     * Display login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('login');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showLoginForm();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user= Auth::user();
        return view('change-pin', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'pin' => 'required|digits:4',
        ]);

        if (!Auth::attempt([
            'phone_number' => $request->phone,
            'password' => $request->pin,
        ], $request->boolean('remember'))) {

            Log::warning('Failed login attempt', ['phone' => $request->phone]);
            return back()->with('error', 'Invalid phone number or PIN.');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->with('error', 'Your account is deactivated.');
        }

        if ($user->is_default_pin) {
            return redirect()->route('user.change-pin.create')
                ->with('warning', 'Please change your default PIN.');
        }

        $user->update(['last_login_at' => now()]);

        return $this->redirectBasedOnRole($user);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|digits:4|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current PIN is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'is_default_pin' => false,
        ]);

        Auth::logout();

        return redirect('/')
            ->with('success', 'PIN changed successfully. Please log in again.');
    }


    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Log::info('User logged out. User ID: ' . Auth::id());
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
        return match ($user->role_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('editor.dashboard'),
            3 => redirect()->route('user.dashboard'),
            default => abort(403),
        };
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
