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
        // Validate input
        $request->validate([
            'phone' => 'required',
            'pin' => 'required|digits:4',
        ]);

        // Attempt login
        $credentials = [
            'phone_number' => $request->phone,
            'password' => $request->pin, // still called password in DB
        ];

        // Check if 'remember' checkbox is checked
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            Log::info('User logged in successfully. User ID: ' . $user->id);
            
            // Check if user account is active
            if (!$user->is_active) {
                Auth::logout();
                Log::warning('Login attempt from inactive user. User ID: ' . $user->id);
                return redirect()->back()->with('error', 'Your account has been deactivated.');
            }
            
            //check if pin is 1234 and redirect to change pin page
            if ($credentials['password'] === '1234') {
                Log::info('User attempted to login with default pin. Phone: ' . $request->phone);
                return redirect()->route('user.change-pin.create')->with('warning', 'Please change your default PIN.');
            }
            
            // Update last login timestamp
            $user->update(['last_login_at' => now()]);
            
            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        Log::warning('Failed login attempt for phone: ' . $request->phone);
        return redirect()->back()->with('error', 'Invalid credentials.');
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
    public function update(Request $request, string $id)
    {
        //
         $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:4|max:4|confirmed',
        ]);

        $user = User::findOrFail($id);
        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        Log::info('User PIN changed successfully. User ID: ' . $user->id);
         // Redirect to the change PIN page with a success message
         session()->flash('success', 'PIN changed successfully. Please log in again.');
        // returning to login page with success message
        Auth::logout(); // Log out the user after changing PIN
        return redirect()->route('/')->with('success', 'PIN changed successfully. Please log in again.');

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
    private function redirectBasedOnRole($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        switch ($user->role_id) {
            case 1: // Admin
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            case 2: // Editor
                return redirect()->route('editor.dashboard')->with('success', 'Welcome Editor!');
            case 3: // User
                return redirect()->route('user.dashboard')->with('success', 'Welcome!');
            default:
                Auth::logout();
                Log::error('Unknown role attempted login. User ID: ' . $user->id . ' Role ID: ' . $user->role_id);
                return redirect()->back()->with('error', 'Unauthorized role.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
