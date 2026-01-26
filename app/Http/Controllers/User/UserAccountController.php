<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;

class UserAccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('users.accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'platform'     => ['required','string','max:20'],   // MT4/MT5/cTrader
            'server'       => ['required','string','max:100'],
            'login'        => ['required','string','max:50'],
            'password'     => ['required','string','max:255'],
            'account_type' => ['nullable','string','max:20'],   // real/demo
            'meta'         => ['nullable','array'],
            'meta.currency'=> ['nullable','string','max:10'],
            'meta.leverage'=> ['nullable','string','max:20'],
        ]);

        // prevent duplicate login per user
        $exists = Account::where('user_id', $request->user()->id)
            ->where('login', $data['login'])
            ->where('server', $data['server'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['login' => 'This account login is already added for this server.'])->withInput();
        }

        Account::create([
            'user_id'      => $request->user()->id,
            'platform'     => $data['platform'],
            'server'       => $data['server'],
            'login'        => $data['login'],
            'password'     => $data['password'], // auto encrypted by model
            'account_type' => $data['account_type'] ?? null,
            'active'       => false,
            'connected'    => false, // will be updated by health check
            'meta'         => $data['meta'] ?? null,
        ]);

        return back()->with('success', 'Account connected successfully.');
    }

    public function update(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) abort(403);

        $data = $request->validate([
            'platform'     => ['required','string','max:20'],
            'server'       => ['required','string','max:100'],
            'password'     => ['nullable','string','max:255'],
            'account_type' => ['nullable','string','max:20'],
            'meta'         => ['nullable','array'],
            'meta.currency'=> ['nullable','string','max:10'],
            'meta.leverage'=> ['nullable','string','max:20'],
        ]);

        $update = [
            'platform'     => $data['platform'],
            'server'       => $data['server'],
            'account_type' => $data['account_type'] ?? null,
            'meta'         => $data['meta'] ?? $account->meta,
        ];

        // only update password if provided
        if (!empty($data['password'])) {
            $update['password'] = $data['password']; // encrypted by model
        }

        $account->update($update);

        return back()->with('success', 'Account updated successfully.');
    }

    public function destroy(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) abort(403);

        // optional: if account is active, block deletion
        if($account->active) return back()->withErrors(['delete' => 'Deactivate first before deleting.']);

        $account->delete();
        return back()->with('success', 'Account removed.');
    }

    public function toggle(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) abort(403);

        $account->active = !$account->active;
        $account->save();

        return response()->json(['ok' => true, 'active' => (bool)$account->active]);
    }
}