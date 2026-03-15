<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViomiaCandleLog;
use Illuminate\Support\Facades\DB;

class CandleLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ViomiaCandleLog::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('symbol', 'like', "%{$search}%");
        }

        // Filter by session
        if ($request->filled('session')) {
            $query->where('session', $request->input('session'));
        }

        // Filter by trend
        if ($request->filled('trend')) {
            $query->where('trend', $request->input('trend'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $candles = $query->paginate(20);
        
        // Get unique sessions for filter
        $sessions = ViomiaCandleLog::select('session')
            ->distinct()
            ->orderBy('session')
            ->pluck('session');

        return view('admin.ai.candles.index', [
            'candles' => $candles,
            'sessions' => $sessions,
        ]);
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
