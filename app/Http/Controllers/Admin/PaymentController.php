<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Hash;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $payments = PaymentTransaction::with(['user', 'plan'])->latest()->paginate(50);
        $banks = Bank::all();
        return view('admin.payments.index', compact('payments', 'banks'));
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
        $payment = PaymentTransaction::with(['user', 'plan'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
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
        $payment = PaymentTransaction::findOrFail($id);
        
        // Validate the request
        $request->validate([
            'status' => 'in:success,pending,failed',
        ]);

        // Update the payment status
        $payment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.payments.show', $payment->id)
                        ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display payment reports
     */
    public function reports(Request $request)
    {
        $query = \App\Models\PaymentTransaction::with(['user', 'plan']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by provider
        if ($request->has('provider') && $request->provider !== '') {
            $query->where('provider', $request->provider);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by reference or user email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('email', 'like', "%{$search}%")
                           ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        // Calculate statistics
        $stats = [
            'total_transactions' => \App\Models\PaymentTransaction::count(),
            'total_amount' => \App\Models\PaymentTransaction::sum('amount'),
            'completed' => \App\Models\PaymentTransaction::where('status', 'success')->count(),
            'pending' => \App\Models\PaymentTransaction::where('status', 'pending')->count(),
            'failed' => \App\Models\PaymentTransaction::where('status', 'failed')->count(),
            'amount_by_provider' => \App\Models\PaymentTransaction::selectRaw('provider, SUM(amount) as total')
                ->where('status', 'success')
                ->groupBy('provider')
                ->get(),
        ];

        return view('admin.payments.reports', compact('transactions', 'stats'));
    }

    /**
     * Resend payment notification to user
     */
    public function resend(string $id)
    {
        $payment = PaymentTransaction::with('user')->findOrFail($id);

        if (!$payment->user) {
            return redirect()->route('admin.payments.show', $payment->id)
                            ->with('error', 'User not found for this payment.');
        }

        try {
            // Send payment confirmation email
            \Illuminate\Support\Facades\Mail::to($payment->user->email)->send(
                new \App\Mail\PaymentConfirmation($payment)
            );
            
            return redirect()->route('admin.payments.show', $payment->id)
                            ->with('success', 'Payment confirmation email sent to ' . $payment->user->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.payments.show', $payment->id)
                            ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
