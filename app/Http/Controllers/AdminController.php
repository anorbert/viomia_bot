<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slot;
use App\Models\Zone;
use App\Models\Parking;
use App\Models\User;
use App\Models\PaymentHistory as Payment;
use Carbon\Carbon;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Total slots
        $totalSlots = Slot::count();

        // Occupied slots
        $occupiedSlots = Slot::where('is_occupied', true)->count();

        // Total revenue Today
        $totalRevenue = Parking::whereDay('created_at', now()->day)->sum('bill');

        // Total revenue MOMO
        $momo = Parking::where('payment_method', 'momo')
                        ->whereDay('created_at', now()->day)
                        ->sum('bill');
        // Total revenue Cash
        $cash = Parking::where('payment_method', 'cash')
                        ->whereDay('created_at', now()->day)
                        ->sum('bill');

        // Active tickets
        $activeTickets = Parking::where('status', 'active')->count();

        // Occupancy by zone
        $zones = Zone::withCount([
            'slots as occupied_count' => function ($query) {
                $query->where('is_occupied', true);
            }
        ])->get();
        $zoneNames = $zones->pluck('name');
        $occupancyCounts = $zones->pluck('occupied_count');

        // Revenue trends (last 6 months)
        $monthlyRevenue = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $months = $monthlyRevenue->map(fn($item) => date('F', mktime(0, 0, 0, $item->month, 1)));
        $revenues = $monthlyRevenue->pluck('total');

        // Today's revenue
        $todaysRevenue = Parking::whereDate('created_at', today())->sum('bill');

        // Today's transaction count
        $todaysTransactions = Parking::whereDate('created_at', today())->count();

        // Most used zone this week
        $mostUsedZone = Parking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('zone_id, COUNT(*) as count')
            ->groupBy('zone_id')
            ->with('zone')
            ->orderByDesc('count')
            ->first();

        // Average parking duration today
        $avgDuration = Parking::whereDate('created_at', today())
            ->whereNotNull('exit_time')
            ->get()
            ->map(function ($item) {
                return Carbon::parse($item->created_at)->diffInMinutes(Carbon::parse($item->exit_time));
            })
            ->average();
        // Exempted vehicles count
        $exemptedCount = Parking::where('status', 'exempt')->count();
        return view('dashboard', compact(
            'totalSlots',
            'occupiedSlots',
            'totalRevenue',
            'activeTickets',
            'zoneNames',
            'occupancyCounts',
            'months',
            'revenues',
            'todaysRevenue',
            'todaysTransactions',
            'mostUsedZone',
            'avgDuration',
            'exemptedCount',
            'momo',
            'cash'
        ));
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
