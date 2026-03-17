<?php

namespace App\Http\Controllers\Bot;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DecisionValidatorController extends Controller
{
    /**
     * Validate an EA trading decision with AI confidence scoring
     * Called by EA BEFORE placing trade
     * AI adjusts TAKE PROFIT level based on confidence, not lot size
     * 
     * POST /api/bot/validate-decision
     * Response time SLA: <500ms
     */
    public function validateDecision(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|string',
            'symbol' => 'required|string',
            'direction' => 'required|in:BUY,SELL',
            'entry_price' => 'required|numeric',
            'stop_loss' => 'required|numeric',
            'take_profit' => 'required|numeric',
            'patterns' => 'required|array',  // ['BOS', 'LIQSWP', 'OBBLOCK']
            'rsi' => 'nullable|numeric',
            'atr' => 'nullable|numeric',
            'trend' => 'nullable|integer',
            'session' => 'nullable|integer',
        ]);

        try {
            // Call AI scoring
            $aiScore = $this->scoreWithAI($validated);
            
            // Calculate AI-adjusted TP based on confidence
            $adjustedTP = $this->calculateAdjustedTP(
                $validated['take_profit'],
                $validated['entry_price'],
                $aiScore['confidence'],
                $validated['direction']
            );
            
            Log::info('Decision validated', [
                'symbol' => $validated['symbol'],
                'confidence' => $aiScore['confidence'],
                'original_tp' => $validated['take_profit'],
                'adjusted_tp' => $adjustedTP,
                'tp_adjustment' => $aiScore['tp_factor'],
            ]);

            return response()->json([
                'confidence' => $aiScore['confidence'],           // 0.0 to 1.0
                'recommendation' => $aiScore['recommendation'],   // EXECUTE|CAUTION|SKIP
                'reasoning' => $aiScore['reasoning'],            // Why this confidence?
                'original_tp' => $validated['take_profit'],
                'adjusted_tp' => $adjustedTP,                   // Use this TP instead
                'tp_adjustment_factor' => $aiScore['tp_factor'], // For UI display
                'ok_to_execute' => $aiScore['confidence'] >= 0.45, // Execute if >= 0.45
            ]);
            
        } catch (\Exception $e) {
            Log::error('Decision validation error', ['error' => $e->getMessage()]);
            
            // If AI fails, fail-safe: use original TP
            return response()->json([
                'confidence' => 0.50,
                'recommendation' => 'CAUTION',
                'reasoning' => 'AI service unavailable - using original TP',
                'original_tp' => $validated['take_profit'],
                'adjusted_tp' => $validated['take_profit'],
                'tp_adjustment_factor' => 1.0,
                'ok_to_execute' => true,  // Fail-open: still allow trade, just skip AI adjustment
            ]);
        }
    }

    /**
     * Calculate AI-adjusted Take Profit
     * 
     * High confidence → extend TP (more aggressive profit taking)
     * Medium confidence → keep TP (EA's original target)
     * Low confidence → reduce TP (take profits early, reduce exposure)
     */
    private function calculateAdjustedTP(
        float $originalTP,
        float $entryPrice,
        float $confidence,
        string $direction
    ): float {
        // Determine TP adjustment factor based on AI confidence
        $tpFactor = match(true) {
            $confidence >= 0.85 => 1.20,  // 20% wider TP (most aggressive)
            $confidence >= 0.75 => 1.10,  // 10% wider TP
            $confidence >= 0.60 => 1.00,  // Original TP
            $confidence >= 0.45 => 0.85,  // 15% closer TP (take profit earlier)
            default => 0.70,              // 30% closer TP (very conservative)
        };

        // Calculate pip distance from entry
        $pipDistance = abs($originalTP - $entryPrice);
        
        // Adjust pip distance by factor
        $adjustedDistance = $pipDistance * $tpFactor;
        
        // Calculate new TP
        if ($direction === 'BUY') {
            $adjustedTP = $entryPrice + $adjustedDistance;
        } else { // SELL
            $adjustedTP = $entryPrice - $adjustedDistance;
        }
        
        return $adjustedTP;
    }

    private function scoreWithAI(array $data): array
    {
        try {
            // Call Python AI service with 400ms timeout
            $response = Http::timeout(0.4)
                ->post('http://94.72.112.148:8001/validate', $data);

            if (!$response->successful()) {
                return $this->failSafeScore('AI service returned error');
            }

            $aiResult = $response->json();
            $confidence = min(1.0, max(0.0, $aiResult['confidence'] ?? 0.5));

            // Determine TP adjustment recommendation
            $tpFactor = match(true) {
                $confidence >= 0.85 => 1.20,
                $confidence >= 0.75 => 1.10,
                $confidence >= 0.60 => 1.00,
                $confidence >= 0.45 => 0.85,
                default => 0.70,
            };

            return [
                'confidence' => $confidence,
                'tp_factor' => $tpFactor,
                'recommendation' => match(true) {
                    $confidence >= 0.75 => 'EXECUTE',
                    $confidence >= 0.45 => 'CAUTION',
                    default => 'SKIP',
                },
                'reasoning' => $aiResult['reasoning'] ?? 'AI validation completed',
            ];

        } catch (\Exception $e) {
            Log::warning('AI validation timeout or error', ['error' => $e->getMessage()]);
            return $this->failSafeScore('AI validation timeout - using original TP');
        }
    }

    private function failSafeScore(string $reason): array
    {
        return [
            'confidence' => 0.50,
            'tp_factor' => 1.0,  // No adjustment
            'recommendation' => 'CAUTION',
            'reasoning' => $reason,
        ];
    }
}
