<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate that account param is present
        $request->validate([
            'account' => 'required|numeric',
        ]);

        $account = Account::where('login', $request->account)->first();

        // Account not found in DB
        if (!$account) {
            return response()->json([
                'active' => false,
                'debug'  => false,
                'reason' => 'Account not found',
            ], 404);
        }

        // Account found but inactive → EA will stop trading
        if (!$account->active) {
            return response()->json([
                'active' => false,
                'debug'  => false,
                'reason' => 'Account is inactive',
            ], 200); // ✅ 200 not 404 — account exists, just disabled
        }

        // Account is active → return real debug flag from DB
        return response()->json([
            'active' => true,
            'debug'  => true,
            'reason' => 'OK',
        ], 200);
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
        // Read raw JSON from MT5
        $raw = $request->getContent();
        \Log::info('Incoming raw Account data Request: ' . $raw);

        // Remove null bytes
        $clean = preg_replace('/\x00/', '', $raw);
        \Log::info('Cleaned Account Snapshot Data: ' . $clean);

        // Decode JSON
        $data = json_decode($clean, true);

        if (!$data) {
            return response()->json([
                'error' => 'Invalid JSON format',
                'raw' => $raw
            ], 400);
        }

        \Log::info('Parsed JSON:', $data);

        // Validate
        $validated = validator($data, [
            'account'    => 'required|string',
            'balance'    => 'required|numeric',
            'equity' => 'required|numeric',
            'margin'     => 'required|numeric',
            'free_margin'        => 'required|numeric',
        ])->validate();

        // ⛔ Prevent duplicate tickets
        if (!Account::where('account', $validated['account'])->exists()) {
            return response()->json([
                'message' => 'No Account Related!'
            ], 200);
        }

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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
