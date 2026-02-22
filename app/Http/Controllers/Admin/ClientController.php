<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display listing of clients.
     * Use withTrashed() because 'Inactive' users are soft-deleted.
     */
   public function index()
    {
        $clients = User::where('role_id', 1)
            ->withTrashed() 
            ->with([
                'accounts' => function($q) {
                    $q->withTrashed(); 
                },
                // Eager load the latest subscription and its associated plan
                'currentSubscription' => function($q) {
                    $q->withTrashed(); 
                },
                'currentSubscription.plan' 
            ])
            ->withCount('accounts')
            ->latest()
            ->get();

        return view('admin.clients.index', compact('clients'));
    }
    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password'     => 'required|string|min:8',
        ]);

        $validated['role_id'] = 1;
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Update client details.
     * Route Model Binding works automatically via UUID.
     */
    public function update(Request $request, User $client)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($client->id)],
            'phone_number' => 'nullable|string|max:20',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * TOGGLE STATUS (Delete/Restore)
     * No status field needed: logic depends on deleted_at being null or not.
     */
    public function destroy(string $uuid)
    {
        // Find user manually to include those already trashed
        $client = User::withTrashed()->where('uuid', $uuid)->firstOrFail();

        if ($client->trashed()) {
            // Restore user and their associated accounts
            DB::transaction(function () use ($client) {
                $client->restore();
                // Optionally restore accounts if they were soft-deleted too
                $client->accounts()->withTrashed()->restore();
            });

            return redirect()->back()->with('success', 'Client status: ACTIVE');
        }

        // Soft delete user and their accounts
        DB::transaction(function () use ($client) {
            $client->accounts()->delete(); 
            $client->delete(); 
        });

        return redirect()->back()->with('warning', 'Client status: INACTIVE');
    }

    /**
     * PERMANENT DELETE (Force Wipe)
     * Cleans storage and removes DB record forever.
     */
    public function forceDelete(string $uuid)
    {
        $client = User::withTrashed()->where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();
        try {
            // Clean physical file
            if ($client->profile_photo && Storage::disk('public')->exists($client->profile_photo)) {
                Storage::disk('public')->delete($client->profile_photo);
            }

            // Wipe accounts and user permanently
            $client->accounts()->withTrashed()->forceDelete();
            $client->forceDelete();

            DB::commit();
            return redirect()->route('admin.clients.index')->with('success', 'Client permanently wiped.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Critical Error: ' . $e->getMessage());
        }
    }
}