<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LossLimitAlert;
use App\Traits\ApiResponseFormatter;

class LossLimitAlertController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store daily loss limit alert
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::alert('Loss limit hit: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'daily_loss'        => 'required|numeric',
            'daily_loss_limit'  => 'required|numeric',
            'limit_type'        => 'required|in:USD,PERCENT',
            'balance'           => 'required|numeric',
            'equity'            => 'required|numeric',
            'alert_at'          => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        try {
            $alert = LossLimitAlert::create($validated);

            // TODO: Send notification to admin/user
            
            return $this->successResponse('Loss limit alert recorded', ['id' => $alert->id], 201);
        } catch (\Exception $e) {
            \Log::error('Loss limit alert failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to record alert', null, 500);
        }
    }
}
