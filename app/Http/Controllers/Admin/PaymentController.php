<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $banks = Bank::all();
        return view('admin.payments.index', compact('banks'));
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
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        //Hardcoded values for APPID and APPSectret
        $appId = "D9AA542A-EAB0-4EED-9D65-BBC054F60DDC";
        $secret = "07192788-21CF-4565-B8C8-EDA62FEEE063";
        $callback = "https://amirah.iws.rw/api/fdiMtnPay";

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('banks', 'public');
        }
        // Check if the bank already exists
        $existingBank = Bank::where('payment_owner', $request->name)->first();
        if ($existingBank) {
            return redirect()->back()->with('error', 'Bank already exists.');
        }

        // Verify password match with logged-in user
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Password does not match your login password.');
        }

        // Check if a bank with the same appId already exists
        $existingBank = Bank::where('appId', $request->appId)->first();
        if ($existingBank) {
            return redirect()->back()->with('error', 'Bank with this App ID already exists.');
        }

        // Create a new bank record
        Bank::create([
            'payment_owner' => $request->name,
            'appId' => $appId,
            'secret' => $secret,
            'charges' => 0,
            'phone_number' => $request->phone,
            'logo' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Bank registered successfully.');
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
