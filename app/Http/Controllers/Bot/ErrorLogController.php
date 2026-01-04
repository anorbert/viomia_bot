<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ErrorLog;
use App\Traits\ApiResponseFormatter;

class ErrorLogController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Log bot error
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::error('Bot error: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'error_type'       => 'required|string',
            'error_message'    => 'required|string',
            'price_at_error'   => 'nullable|numeric',
            'balance'          => 'required|numeric',
            'equity'           => 'required|numeric',
            'error_at'         => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        try {
            $errorLog = ErrorLog::create($validated);

            // TODO: Send alert notification if critical error
            
            return $this->successResponse('Error logged', ['id' => $errorLog->id], 201);
        } catch (\Exception $e) {
            \Log::error('Error log creation failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to log error', null, 500);
        }
    }
}
