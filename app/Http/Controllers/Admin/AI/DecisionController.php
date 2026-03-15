<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViomiaDecision;
use Illuminate\Support\Facades\DB;

class DecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ViomiaDecision::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('symbol', 'like', "%{$search}%");
        }

        // Filter by decision
        if ($request->filled('decision')) {
            $query->where('decision', $request->input('decision'));
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('decided_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('decided_at', '<=', $request->input('to_date'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'decided_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $decisions = $query->paginate(20);

        // Get unique decisions for filter
        $uniqueDecisions = ViomiaDecision::select('decision')
            ->distinct()
            ->orderBy('decision')
            ->pluck('decision');

        return view('admin.ai.decisions.index', [
            'decisions' => $decisions,
            'uniqueDecisions' => $uniqueDecisions,
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
