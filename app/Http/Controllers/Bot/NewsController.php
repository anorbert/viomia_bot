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
        // 1️⃣ Log request source
        \Log::info('Incoming News Request', [
            'method' => $request->method(),
            'query'  => $request->query(),
        ]);

        // 2️⃣ Read currency from query string (MT5-compatible)
        $currency = $request->query('currency', 'USD');

        $now = Carbon::now();

        // 3️⃣ Fetch nearest upcoming HIGH-impact news
        $event = NewsEvent::where('currency', $currency)
            ->where('impact', 'high')
            ->where('event_time', '>=', $now)
            ->orderBy('event_time', 'asc')
            ->first();

        if (!$event) {            
            \Log::info('No News Available at moment');
            return response()->json([
                'message' => 'No upcoming high-impact news'
            ], 200);

        }
        \Log::info('News lookup', [
            'currency' => $currency,
            'now' => Carbon::now()->toDateTimeString(),
            'count' => NewsEvent::count(),
        ]);
        return response()->json([
            'id'         => $event->id,
            'currency'   => $event->currency,
            'event_name' => $event->event_name,
            'event_time' => Carbon::parse($event->event_time)->format('Y-m-d H:i:s'),
            'impact'     => $event->impact,
            'notified'   => (int) $event->notified,
        ]);
    }


}