<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradeEvent;
use App\Traits\ApiResponseFormatter;

class TradeEventController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store a newly opened trade event
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::info('Trade opened event: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', ['raw' => $raw], 400);
        }

        // Validate
        $validated = validator($data, [
            'ticket'        => 'required|string|unique:trade_events',
            'direction'     => 'required|in:BUY,SELL',
            'entry_price'   => 'required|numeric',
            'sl_price'      => 'required|numeric',
            'tp_price'      => 'required|numeric',
            'lot_size'      => 'required|numeric',
            'signal_source' => 'nullable|string',
            'opened_at'     => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        try {
            $event = TradeEvent::create($validated);
            
            return $this->successResponse(
                'Trade event recorded successfully',
                ['id' => $event->id, 'ticket' => $event->ticket],
                201
            );
        } catch (\Exception $e) {
            \Log::error('Trade event creation failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to record trade event', ['error' => $e->getMessage()], 500);
        }
    }
}
