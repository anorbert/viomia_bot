<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Display user profile.
     */
    public function index()
    {
        $user = Auth::user();
        return view('users.profile.index', compact('user'));
    }

    /**
     * Show the form for editing user profile.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        
        // Check if user is editing their own profile
        if ($user->id !== $id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('users.profile.edit', compact('user'));
    }

    /**
     * Update the user profile in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        
        // Check if user is updating their own profile
        if ($user->id !== $id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->update($validated);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Display password change form.
     */
    public function changePassword(string $id)
    {
        $user = Auth::user();
        
        if ($user->id !== $id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('users.profile.change-password', compact('user'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request, string $id)
    {
        $user = Auth::user();
        
        if ($user->id !== $id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.profile.index')
            ->with('success', 'Password changed successfully!');
    }
}

