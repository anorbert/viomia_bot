<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TechnicalSignal;
use App\Traits\ApiResponseFormatter;

class TechnicalSignalController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store technical indicator values
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        
        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'trend_score'           => 'required|numeric',
            'choch_signal'          => 'required|in:BULLISH_REVERSAL,BEARISH_REVERSAL,NO_REVERSAL',
            'rsi_value'             => 'required|numeric',
            'atr_value'             => 'required|numeric',
            'ema_20'                => 'required|numeric',
            'ema_50'                => 'required|numeric',
            'signal_description'    => 'required|string',
            'captured_at'           => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        try {
            $signal = TechnicalSignal::create($validated);
            
            return $this->successResponse('Technical signal recorded', ['id' => $signal->id], 201);
        } catch (\Exception $e) {
            \Log::error('Technical signal creation failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to record signal', null, 500);
        }
    }
}
