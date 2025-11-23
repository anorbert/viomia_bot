<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\NewsEvent;
use Carbon\Carbon;


class NewsController extends Controller
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
        $news= NewsEvent::create([
            'event_time' => $request->event_time,
            'currency'   => $request->currency,
            'impact'     => $request->impact,
            'event_name' => $request->event_name,
            'source'     => 'manual',
            'raw'        => json_encode($request->all())
        ]);

        return back()->with('ok', 'News saved.');
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

    public function next(Request $request)
    {
        $currency = $request->query('currency', 'USD');
        $now = Carbon::now();

        $event = NewsEvent::where('currency', $currency)
            ->where('impact', 'high')
            ->orderBy('event_time', 'asc')
            ->get();
        return $event;

        if (!$event) {
            return response()->json(['message' => 'No upcoming news'], 404);
        }

        return response()->json([
            'id' => $event->id,
            'currency' => $event->currency,
            'event_name' => $event->event_name,
            'event_time' => $event->event_time,
            'impact' => $event->impact,
            'notified' => $event->notified,
        ]);
    }
}
