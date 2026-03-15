<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('id')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
            'status' => 'required|in:Active,Inactive',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')
                       ->with('success', 'Role created successfully!');
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
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id . '|max:50',
            'status' => 'required|in:Active,Inactive',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
                       ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of system roles
        if (in_array($role->id, [1, 2, 3])) {
            return redirect()->route('admin.roles.index')
                           ->with('error', 'System roles cannot be deleted!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
                       ->with('success', 'Role deleted successfully!');
    }
}
