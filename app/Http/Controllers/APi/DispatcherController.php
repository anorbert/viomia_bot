<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function execute(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'symbol'     => 'required|string',
            'direction'  => 'required|in:buy,sell',
            'volume'     => 'required|numeric|min:0.01',
            'price'      => 'nullable|numeric',
            'order_type' => 'nullable|in:market,limit,stop',
            'idempotency_key' => 'nullable|string'
        ]);

        // Use provided idempotency key or generate one for dedup
        $idempotencyKey = $data['idempotency_key'] ?? Str::uuid()->toString();

        // Enqueue job to dispatch
        DispatchOrderJob::dispatch($data['account_id'], $data, $idempotencyKey);

        return response()->json(['ok' => true, 'queued' => true, 'idempotency_key' => $idempotencyKey], 202);
    }

    public function status(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
        ]);

        $account = Account::find($data['account_id']);
        if (!$account) return response()->json(['ok' => false], 404);

        return response()->json([
            'ok' => true,
            'connected' => $account->connected,
            'last_snapshot' => optional($account->snapshots()->latest()->first())->toArray()
        ]);
    }
}
