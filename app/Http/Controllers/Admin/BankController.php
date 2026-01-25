<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::latest()->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'phone'    => ['required'],
            'password' => ['required','string'],
            'logo'     => ['nullable','image','max:2048'],
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Password does not match your login password.')->withInput();
        }

        // sanitize phone (because column is integer)
        $phoneDigits = preg_replace('/\D+/', '', (string) $request->phone);
        if (strlen($phoneDigits) < 8) {
            return back()->with('error', 'Phone number is invalid.')->withInput();
        }

        // hardcoded values (move to .env later)
        $appId  = "D9AA542A-EAB0-4EED-9D65-BBC054F60DDC";
        $secret = "07192788-21CF-4565-B8C8-EDA62FEEE063";

        if (Bank::where('payment_owner', $request->name)->exists()) {
            return back()->with('error', 'Bank already exists with this name.')->withInput();
        }

        // if (Bank::where('appId', $appId)->exists()) {
        //     return back()->with('error', 'Bank with this App ID already exists.')->withInput();
        // }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('banks', 'public');
        }

        Bank::create([
            'payment_owner' => $request->name,
            'appId'         => $appId,
            'secret'        => $secret,
            'logo'          => $logoPath,
            'phone_number'  => (int) $phoneDigits,
            // charges default is 2.5 from migration
            // balance default is 0 from migration
            'status'        => 'ACTIVE',
            'deactivated_at'=> null,
        ]);

        return back()->with('success', 'Bank registered successfully.');
    }

    public function edit(string $id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, string $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'name'   => ['required','string','max:255'],
            'phone'  => ['required'],
            'charges'=> ['required','numeric','min:0','max:100'],
            'logo'   => ['nullable','image','max:2048'],
        ]);

        // unique name check excluding current bank
        if (Bank::where('payment_owner', $request->name)->where('id', '!=', $bank->id)->exists()) {
            return back()->with('error', 'Another bank already uses this name.')->withInput();
        }

        $phoneDigits = preg_replace('/\D+/', '', (string) $request->phone);
        if (strlen($phoneDigits) < 8) {
            return back()->with('error', 'Phone number is invalid.')->withInput();
        }

        // handle logo replace
        $logoPath = $bank->logo;
        if ($request->hasFile('logo')) {
            if ($bank->logo && Storage::disk('public')->exists($bank->logo)) {
                Storage::disk('public')->delete($bank->logo);
            }
            $logoPath = $request->file('logo')->store('banks', 'public');
        }

        $bank->update([
            'payment_owner' => $request->name,
            'phone_number'  => (int) $phoneDigits,
            'charges'       => $request->charges,
            'logo'          => $logoPath,
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(string $id)
    {
        $bank = Bank::findOrFail($id);

        if ($bank->logo && Storage::disk('public')->exists($bank->logo)) {
            Storage::disk('public')->delete($bank->logo);
        }

        $bank->delete();

        return back()->with('success', 'Bank deleted successfully.');
    }

    public function toggle(string $id)
    {
        $bank = Bank::findOrFail($id);

        $current = strtoupper($bank->status ?? 'INACTIVE');

        if ($current === 'ACTIVE') {
            $bank->status = 'INACTIVE';
            $bank->deactivated_at = now();
        } else {
            $bank->status = 'ACTIVE';
            $bank->deactivated_at = null;
        }

        $bank->save();

        return back()->with('success', 'Bank status updated to '.$bank->status.'.');
    }
}
