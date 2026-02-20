<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone_number'  => ['required', 'string', 'max:20', 'unique:users,phone_number'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'role_id'       => ['required', 'exists:roles,id'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_pictures', 'public');
            $data['profile_photo'] = $path;
        }

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    // Changed string $id to User $user (Route Model Binding)
    public function edit(User $user)
    {
        // $this->authorize('update', $user); // Security Layer B
        // Gate::authorize('update', $user);
        if (auth()->user()->role_id !== 1) { abort(403); }
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Changed string $id to User $user
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user); // Security Layer B

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone_number'  => ['required', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($user->id)],
            'email'         => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id'       => ['required', 'exists:roles,id'],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_pictures', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    // Changed string $id to User $user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // public function restore($id)
    // {
    //     $user = User::onlyTrashed()->findOrFail($id);
    //     $this->authorize('restore', $user);
        
    //     $user->restore();

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'User restored successfully.');
    // }
    public function restore($uuid) 
{
    // We search specifically in the trashed records using the UUID
    $user = User::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
    
    $this->authorize('restore', $user); // Security Layer B
    
    $user->restore();

    return redirect()->route('admin.users.index')
        ->with('success', 'User restored successfully.');
}
}