<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\WeeklyPayment;
use App\Models\SubscriptionPlan;
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
        $type = $request->get('type');

        // Get subscription payments
        $subscriptionPayments = PaymentTransaction::query()
            ->where('user_id', auth()->id())
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('reference','like',"%{$q}%")
                    ->orWhere('provider_txn_id','like',"%{$q}%")
                    ->orWhere('provider','like',"%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when(!$type || $type === 'subscription', fn($qry) => $qry, fn($qry) => $qry->whereRaw('1=0'))
            ->select(
                'id',
                DB::raw("'subscription' as payment_type"),
                'reference',
                'amount',
                'status',
                'provider',
                'created_at',
                'updated_at',
                DB::raw("NULL as week_start"),
                DB::raw("NULL as week_end")
            );

        // Get weekly payments
        $weeklyPayments = WeeklyPayment::query()
            ->where('user_id', auth()->id())
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('reference','like',"%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when(!$type || $type === 'weekly', fn($qry) => $qry, fn($qry) => $qry->whereRaw('1=0'))
            ->select(
                'id',
                DB::raw("'weekly' as payment_type"),
                'reference',
                'amount',
                'status',
                DB::raw("'weekly' as provider"),
                'created_at',
                'updated_at',
                'week_start',
                'week_end'
            );

        // Merge both queries
        $allPayments = $subscriptionPayments->unionAll($weeklyPayments)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Calculate statistics
        $stats = [
            'successful' => PaymentTransaction::where('user_id', auth()->id())->whereIn('status', ['paid', 'success'])->count() + 
                           WeeklyPayment::where('user_id', auth()->id())->where('status', 'paid')->count(),
            'pending' => PaymentTransaction::where('user_id', auth()->id())->where('status', 'pending')->count() + 
                        WeeklyPayment::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'failed' => PaymentTransaction::where('user_id', auth()->id())->where('status', 'failed')->count()
        ];

        $totalAmount = PaymentTransaction::where('user_id', auth()->id())->whereIn('status', ['paid', 'success'])->sum('amount') +
                      WeeklyPayment::where('user_id', auth()->id())->where('status', 'paid')->sum('amount');

        return view('users.payments.index', compact('allPayments', 'q', 'status', 'type', 'stats', 'totalAmount'));
    }

    /**
     * Download PDF invoice for a payment
     */
    public function downloadPDF($id)
    {
        // Try to get from PaymentTransaction first
        $payment = PaymentTransaction::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        // If not found, try WeeklyPayment
        if (!$payment) {
            $payment = WeeklyPayment::where('user_id', auth()->id())
                ->where('id', $id)
                ->first();
        }

        if (!$payment) {
            abort(404);
        }

        // Add payment_type if not present
        if (!isset($payment->payment_type)) {
            $payment->payment_type = $payment instanceof PaymentTransaction ? 'subscription' : 'weekly';
        }

        $data = [
            'payment' => $payment,
            'user' => auth()->user(),
            'company' => [
                'name' => env('APP_NAME', 'VIOMIA'),
                'address' => 'Kigali, Rwanda',
                'street' => 'KG 12 St',
                'phone' => '+250 788 123 456',
                'email' => 'support@viomia.com',
                'signature' => auth()->id() . '-' . date('YmdHis'),
                'logo' => public_path('logo.png')
            ]
        ];

        $pdf = \PDF::loadView('users.payments.invoice-pdf', $data);
        return $pdf->download('Invoice-' . ($payment->reference ?? 'N-A') . '.pdf');
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
        // Try to get from PaymentTransaction first
        $payment = PaymentTransaction::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        // If not found, try WeeklyPayment
        if (!$payment) {
            $payment = WeeklyPayment::where('user_id', auth()->id())
                ->where('id', $id)
                ->first();
        }

        if (!$payment) {
            abort(404);
        }

        // Add payment_type if not present
        if (!isset($payment->payment_type)) {
            $payment->payment_type = $payment instanceof PaymentTransaction ? 'subscription' : 'weekly';
        }

        $data = [
            'payment' => $payment,
            'user' => auth()->user(),
            'company' => [
                'name' => env('APP_NAME', 'VIOMIA'),
                'address' => 'Kigali, Rwanda',
                'street' => 'KG 12 St',
                'phone' => '+250 788 123 456',
                'email' => 'support@viomia.com',
                'signature' => auth()->id() . '-' . date('YmdHis'),
                'logo' => public_path('logo.png')
            ]
        ];

        return view('users.payments.invoice-pdf', $data);
    }

    /**
     * Export all payments as PDF report
     */
    public function exportPDF(Request $request)
    {
        $q = trim($request->get('q', ''));
        $status = $request->get('status');
        $type = $request->get('type');

        // Get subscription payments
        $subscriptionPayments = PaymentTransaction::query()
            ->where('user_id', auth()->id())
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('reference','like',"%{$q}%")
                    ->orWhere('provider_txn_id','like',"%{$q}%")
                    ->orWhere('provider','like',"%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when(!$type || $type === 'subscription', fn($qry) => $qry, fn($qry) => $qry->whereRaw('1=0'))
            ->select(
                'id',
                DB::raw("'subscription' as payment_type"),
                'reference',
                'amount',
                'status',
                'provider',
                'created_at',
                'updated_at',
                DB::raw("NULL as week_start"),
                DB::raw("NULL as week_end")
            );

        // Get weekly payments
        $weeklyPayments = WeeklyPayment::query()
            ->where('user_id', auth()->id())
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('reference','like',"%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when(!$type || $type === 'weekly', fn($qry) => $qry, fn($qry) => $qry->whereRaw('1=0'))
            ->select(
                'id',
                DB::raw("'weekly' as payment_type"),
                'reference',
                'amount',
                'status',
                DB::raw("'weekly' as provider"),
                'created_at',
                'updated_at',
                'week_start',
                'week_end'
            );

        // Merge both queries - get all records without pagination for PDF
        $payments = $subscriptionPayments->unionAll($weeklyPayments)
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'successful' => PaymentTransaction::where('user_id', auth()->id())->whereIn('status', ['paid', 'success'])->count() + 
                           WeeklyPayment::where('user_id', auth()->id())->where('status', 'paid')->count(),
            'pending' => PaymentTransaction::where('user_id', auth()->id())->where('status', 'pending')->count() + 
                        WeeklyPayment::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'failed' => PaymentTransaction::where('user_id', auth()->id())->where('status', 'failed')->count()
        ];

        $data = [
            'payments' => $payments,
            'user' => auth()->user(),
            'stats' => $stats,
            'company' => [
                'name' => env('APP_NAME', 'VIOMIA'),
                'address' => 'Kigali, Rwanda',
                'street' => 'KG 12 St',
                'phone' => '+250 788 123 456',
                'email' => 'support@viomia.com',
                'logo' => public_path('logo.png')
            ]
        ];

        $pdf = \PDF::loadView('users.payments.report-pdf', $data);
        return $pdf->download('Payment-Report-' . date('Y-m-d-His') . '.pdf');
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
