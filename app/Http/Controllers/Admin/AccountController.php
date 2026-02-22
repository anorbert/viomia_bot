<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\User;
use App\Models\AccountSnapshot;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $accounts = Account::latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clients = User::get();
        return view('admin.accounts.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id'     => 'required|exists:users,id',
            'platform'      => 'required|string|max:10', // MT4 or MT5
            'broker_server' => 'required|string|max:255',
            'account_number'=> 'required|string|max:255',
            'password'      => 'required|string|max:255',
            'account_type'  => 'required|string|max:50'
        ]);

        $account=Account::create([
            'user_id'       => $request->client_id,
            'platform'      => $request->platform,
            'server'        => $request->broker_server,
            'login'         => $request->account_number,
            'password'      => $request->password,
            'account_type'  => $request->account_type,
            'active'        => true,
        ]);
        //check if account created successfully
        if(!$account){
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create account. Please try again.']);
        } 
        // Create initial snapshot
        $snapshot=AccountSnapshot::create([
            'account_id' => $account->id,
            'balance' => 0,
            'equity' => 0,
            'margin' => 0,
            'free_margin' => 0,
            'drawdown' => 0,
        ]);
        
        return redirect()
                ->route('admin.accounts.index')
                ->with('success', 'Account created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $account = Account::findOrFail($id);
        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $account = Account::findOrFail($id);
        $clients = User::get();
        return view('admin.accounts.edit', compact('account', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'client_id' => 'required|exists:users,id',
        ]);
        $account = Account::findOrFail($id);
        $account->update([
            'user_id' => $request->client_id,
            'platform' => $request->platform,
            'server' => $request->server,
            'login' => $request->login, // Ensure login is unique if needed
            'password' => bcrypt($request->password), // Encrypt the password
            'account_type' => $request->account_type,
            'active' => $request->active ? true : false,
        ]);
        return redirect()->route('admin.accounts.index')->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $account = Account::findOrFail($id);
        $account->delete();
        return redirect()->route('admin.accounts.index')->with('success', 'Account deleted successfully');
    }

    public function pending()
    {
        $pendingAccounts = Account::where('is_verified', false)->with('user')->latest()->paginate(10);
        $users = User::all();
        return view('admin.accounts.pending', compact('pendingAccounts', 'users'));
    }

    public function verifyAccount(Account $account, Request $request)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:500',
        ]);

        $account->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verification_notes' => $request->verification_notes,
            'active' => true,
        ]);

        return back()->with('success', 'Account verified and activated successfully!');
    }

    public function rejectAccount(Account $account, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $account->update([
            'is_verified' => false,
            'rejection_reason' => $request->rejection_reason,
            'active' => false,
        ]);

        // Optionally send notification to user
        // Notification::send($account->user, new AccountRejectedNotification($account->rejection_reason));

        return back()->with('success', 'Account rejected. User has been notified.');
    }

    public function fetchData($id)
    {
        $account = Account::findOrFail($id);
        // fetching data from external source


        
        return $fetchedData;

    }
}
