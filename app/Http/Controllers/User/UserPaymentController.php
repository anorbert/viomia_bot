<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $status = $request->get('status');

        $payments = PaymentTransaction::query()
            ->where('user_id', auth()->id())
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('reference','like',"%{$q}%")
                    ->orWhere('provider_txn_id','like',"%{$q}%")
                    ->orWhere('provider','like',"%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('users.payments.index', compact('payments','q','status'));
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
}
