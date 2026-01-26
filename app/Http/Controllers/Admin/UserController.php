<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::with('role')->latest()->get();
        $roles = Role::orderBy('name')->get(); // for create modal
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'phone_number' => ['required','string','max:20','unique:users,phone_number'],
            'email'        => ['nullable','email','max:255','unique:users,email'],
            'role_id'      => ['required','exists:roles,id'],
            'password'     => ['required','string','min:6','confirmed'],
        ]);
        //check if password is set
        if (empty($data['password'])) {
            return redirect()->back()->withErrors(['password' => 'Password is required.'])->withInput();
        }
        
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
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
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'phone_number' => ['required','string','max:20', Rule::unique('users','phone_number')->ignore($user->id)],
            'email'        => ['nullable','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'role_id'      => ['required','exists:roles,id'],
            'password'     => ['nullable','string','min:6','confirmed'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user->delete(); // soft delete
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'User restored successfully.');
    }
}
