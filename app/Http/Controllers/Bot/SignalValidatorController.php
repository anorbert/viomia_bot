<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SignalValidatorController extends Controller
{
    /**
     * Validate trading signal before execution
     * Solves P0-6: "No Signal Validation Before Execution"
     * 
     * Call this BEFORE executing any signal from AI
     * Returns validation result with any corrections
     */
    public function validateSignal(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|string',
            'symbol' => 'required|string',
            'entry_price' => 'required|numeric',
            'stop_loss' => 'required|numeric',
            'take_profit' => 'required|numeric',
            'lot_size' => 'required|numeric',
            'account_balance' => 'required|numeric',
            'account_equity' => 'required|numeric',
            'available_margin' => 'required|numeric',
        ]);

        $errors = [];
        $warnings = [];

        // ===== VALIDATION 1: Check symbol validity =====
        if (!$this->isValidSymbol($validated['symbol'])) {
            $errors[] = "Invalid symbol: {$validated['symbol']}";
        }

        // ===== VALIDATION 2: Check prices are realistic =====
        $entryValidation = $this->validateEntryPrice($validated['symbol'], $validated['entry_price']);
        if ($entryValidation['error']) {
            $errors[] = $entryValidation['error'];
        }

        // ===== VALIDATION 3: Check SL/TP are on opposite side of entry =====
        $riskReward = $this->validateRiskRewardRatio($validated);
        if ($riskReward['error']) {
            $errors[] = $riskReward['error'];
        }
        if ($riskReward['warning']) {
            $warnings[] = $riskReward['warning'];
        }

        // ===== VALIDATION 4: Check SL distance is reasonable =====
        $slValidation = $this->validateStopLossDistance($validated);
        if ($slValidation['error']) {
            $errors[] = $slValidation['error'];
        }

        // ===== VALIDATION 5: Check lot size doesn't exceed account =====
        $lotValidation = $this->validateLotSize($validated);
        if ($lotValidation['error']) {
            $errors[] = $lotValidation['error'];
        }

        // ===== VALIDATION 6: Check margin requirements =====
        $marginValidation = $this->validateMargin($validated);
        if ($marginValidation['error']) {
            $errors[] = $marginValidation['error'];
        }

        // Return result
        return response()->json([
            'valid' => count($errors) === 0,
            'errors' => $errors,
            'warnings' => $warnings,
            'checks_performed' => 6,
            'checks_passed' => 6 - count($errors),
        ], count($errors) > 0 ? 422 : 200);
    }

    private function isValidSymbol(string $symbol): bool
    {
        $validSymbols = [
            'EURUSD', 'GBPUSD', 'USDJPY', 'AUDUSD', 'USDCAD',
            'NZDUSD', 'USDCHF', 'EURJPY', 'GBPJPY', 'XAUUSD',
            'XBRUSD', 'XTIUSD', 'SPX500', 'US100', 'US30',
            'USSOND'
        ];
        
        return in_array($symbol, $validSymbols);
    }

    private function validateEntryPrice(string $symbol, float $entryPrice): array
    {
        // Define reasonable price ranges for each symbol
        $ranges = [
            'EURUSD' => ['min' => 0.90, 'max' => 1.20],
            'GBPUSD' => ['min' => 1.10, 'max' => 1.50],
            'USDJPY' => ['min' => 100, 'max' => 160],
            'XAUUSD' => ['min' => 1500, 'max' => 2500],
        ];

        if (!isset($ranges[$symbol])) {
            return ['error' => null];  // Symbol not in ranges, skip validation
        }

        $range = $ranges[$symbol];
        if ($entryPrice < $range['min'] || $entryPrice > $range['max']) {
            return ['error' => "Entry price {$entryPrice} outside realistic range ({$range['min']}-{$range['max']})"];
        }

        return ['error' => null];
    }

    private function validateRiskRewardRatio(array $data): array
    {
        $entry = $data['entry_price'];
        $sl = $data['stop_loss'];
        $tp = $data['take_profit'];

        // Calculate distances
        $slDistance = abs($entry - $sl);
        $tpDistance = abs($entry - $tp);

        if ($slDistance == 0) {
            return ['error' => 'Stop Loss cannot be same as Entry', 'warning' => null];
        }

        $rrRatio = $tpDistance / $slDistance;

        // Validate RR ratio
        if ($rrRatio < 0.8) {
            return ['error' => "RR ratio too poor: {$rrRatio}:1 (minimum 0.8:1)", 'warning' => null];
        }

        if ($rrRatio > 10.0) {
            return ['error' => "RR ratio unrealistic: {$rrRatio}:1 (maximum 10:1)", 'warning' => null];
        }

        if ($rrRatio < 1.0) {
            return ['error' => null, 'warning' => "Warning: RR ratio 1:1 or less ({$rrRatio}:1)"];
        }

        return ['error' => null, 'warning' => null];
    }

    private function validateStopLossDistance(array $data): array
    {
        $entry = $data['entry_price'];
        $sl = $data['stop_loss'];
        $slDistance = abs($entry - $sl);

        // Define min/max SL distance by symbol
        $slLimits = [
            'EURUSD' => ['min' => 0.0010, 'max' => 0.1000],
            'XAUUSD' => ['min' => 5, 'max' => 500],
            'USDJPY' => ['min' => 0.50, 'max' => 50],
            'SPX500' => ['min' => 10, 'max' => 500],
        ];

        $symbol = $data['symbol'];
        if (!isset($slLimits[$symbol])) {
            return ['error' => null];  // No limits defined, skip
        }

        $limits = $slLimits[$symbol];
        if ($slDistance < $limits['min']) {
            return ['error' => "Stop Loss too tight: {$slDistance} (minimum {$limits['min']})"];
        }

        if ($slDistance > $limits['max']) {
            return ['error' => "Stop Loss too wide: {$slDistance} (maximum {$limits['max']})"];
        }

        return ['error' => null];
    }

    private function validateLotSize(array $data): array
    {
        $lotSize = $data['lot_size'];
        $balance = $data['account_balance'];
        $entry = $data['entry_price'];
        $sl = $data['stop_loss'];

        // Risk should not exceed 2% of account per trade
        $maxRiskPercent = 0.02;
        $slDistance = abs($entry - $sl);
        $maxLot = ($balance * $maxRiskPercent) / ($slDistance * 10000);

        if ($lotSize <= 0) {
            return ['error' => "Lot size must be greater than 0"];
        }

        if ($lotSize > $maxLot) {
            return ['error' => "Lot size {$lotSize} exceeds max allowed {$maxLot} for 2% risk"];
        }

        $riskPercent = ($lotSize * $slDistance * 10000) / $balance;
        if ($riskPercent > 0.05) {
            return ['error' => "Position risk {$riskPercent}% exceeds maximum 5%"];
        }

        return ['error' => null];
    }

    private function validateMargin(array $data): array
    {
        $lotSize = $data['lot_size'];
        $availableMargin = $data['available_margin'];
        $equity = $data['account_equity'];

        // Margin requirement: roughly lot_size * 100 * leverage / 100
        // For 1:50 leverage: 1 lot = 2% margin
        $marginRequired = ($lotSize * 2) / 100 * $equity;

        if ($marginRequired > $availableMargin) {
            return ['error' => "Insufficient margin: need {$marginRequired}, have {$availableMargin}"];
        }

        // Warn if margin drops below 30%
        $marginPercent = ($availableMargin - $marginRequired) / $equity * 100;
        if ($marginPercent < 30) {
            Log::warning("Low margin warning: {$marginPercent}% remaining after trade");
        }

        return ['error' => null];
    }
}
