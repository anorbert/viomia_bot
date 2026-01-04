<?php

namespace App\Traits;

trait ApiResponseFormatter
{
    /**
     * Parse raw JSON from MQL5 bot
     */
    protected function parseRawJson($raw)
    {
        // Remove null bytes and hidden characters
        $clean = preg_replace('/\x00/', '', $raw);
        
        // Decode JSON
        $data = json_decode($clean, true);
        
        if (!$data) {
            return null;
        }
        
        return $data;
    }

    /**
     * Send success response
     */
    protected function successResponse($message, $data = null, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send error response
     */
    protected function errorResponse($message, $details = null, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'details' => $details,
        ], $statusCode);
    }
}
