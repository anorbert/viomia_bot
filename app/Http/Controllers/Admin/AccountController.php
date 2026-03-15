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

        //check if the client has an existing account
        $accountExists = Account::where('user_id', $request->client_id)->exists();
        if($accountExists){
            return back()
                ->withInput()
                ->withErrors(['client_id' => 'This client already has registered account.']);
        }

        //check if login is unique
        $loginExists = Account::where('login', $request->account_number)->exists();
        if($loginExists){
            return back()
                ->withInput()
                ->withErrors(['account_number' => 'This account number is already registered.']);
        }
        //check if client has role of user 
        // If is user check if has subscription plan active
        $client = User::find($request->client_id);
        if(!$client->hasRole('user')){
            //check if client has active subscription plan
            $hasActiveSubscription = $client->subscriptions()->where('status', 'active')->exists();
            if(!$hasActiveSubscription){
            //create subscription for client
               $subscription= UserSubscription::create([
                    'user_id' => $client->id,
                    'subscription_plan_id' => 1, // Assuming you have a default plan with ID 1
                    'status' => 'active',
                    'starts_at' => now(),
                    'auto_renew' => false,
                    'amount' => 0, // Set to 0 for free subscription
                    'notes' => 'Automatically created subscription for account creation',
                ]);
                if(!$subscription){
                    return back()
                        ->withInput()
                        ->withErrors(['error' => 'Failed to create subscription for client. Please try again.']);
                } 
            } 
        }
        
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
        $request->validate([
            'login' => 'required|string|max:255',
            'platform' => 'required|in:mt4,mt5',
            'server' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'active' => 'required|in:0,1',
        ]);

        $account = Account::findOrFail($id);
        $account->update([
            'platform' => $request->platform,
            'server' => $request->server,
            'login' => $request->login,
            'password' => $request->password,
            'active' => (bool) $request->active,
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

    /**
     * Toggle account active/inactive status
     */
    public function toggle(string $id)
    {
        $account = Account::findOrFail($id);
        $account->active = !$account->active;
        $account->save();

        $status = $account->active ? 'activated' : 'deactivated';
        return redirect()->route('admin.accounts.index')
                       ->with('success', "Account has been $status successfully");
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
