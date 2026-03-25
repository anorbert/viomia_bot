<?php

use App\Http\Controllers\Bot\SignalController;
use App\Http\Controllers\Bot\TradeLogController;
use App\Http\Controllers\Bot\BotStatusController;
use App\Http\Controllers\Bot\NewsController;
use App\Http\Controllers\Bot\AccountController;
use App\Http\Controllers\Bot\TradeEventController;
use App\Http\Controllers\Bot\TradeOutcomeController;
use App\Http\Controllers\Bot\DailySummaryController;
use App\Http\Controllers\Bot\PositionUpdateController;
use App\Http\Controllers\Bot\LossLimitAlertController;
use App\Http\Controllers\Bot\FilterBlockController;
use App\Http\Controllers\Bot\TechnicalSignalController;
use App\Http\Controllers\Bot\EaStatusChangeController;
use App\Http\Controllers\Bot\ErrorLogController;
use App\Http\Controllers\Bot\WhatsappSignalController;
use App\Http\Controllers\Bot\DecisionValidatorController;
use App\Http\Controllers\Bot\SignalValidatorController;
use App\Http\Controllers\Bot\TradeEntryContextController;
use App\Http\Controllers\Bot\P0RaceConditionVerifierController;
use App\Http\Controllers\Bot\SignalPatternController;
use App\Middleware\CheckApiKey;

Route::prefix('bot')->middleware(CheckApiKey::class)->group(function () {
    //====System settings check =======//
    Route::get('/account/settings', [AccountController::class, 'index']); 

    // ===== AI Decision Validation (NEW) =====
    // Called by EA BEFORE placing trade to validate decision and get TP adjustment
    Route::post('/validate-decision', [DecisionValidatorController::class, 'validateDecision']);

    // ===== Signal Validation (NEW - P0-6) =====
    // Validates signal before execution: symbol, prices, RR ratio, lot size, margin
    Route::post('/validate-signal', [SignalValidatorController::class, 'validateSignal']);

    // ===== Signals =====
    Route::get('/signal', [SignalController::class, 'getActive']);
    Route::post('/signal', [SignalController::class, 'store']);

    // ===== Signal Patterns (NEW - P0-2b) =====
    // Saves signal pattern information when signals are generated
    Route::post('/signal/pattern', [SignalPatternController::class, 'store']);
    Route::get('/signal/pattern/analysis', [SignalPatternController::class, 'getAnalysis']);
    Route::get('/signal/pattern/history/{symbol}', [SignalPatternController::class, 'getHistory']);
    Route::put('/signal/pattern/{patternId}/outcome', [SignalPatternController::class, 'linkOutcome']);

    // ===== Trade Logs =====
    Route::post('/trade/log', [TradeLogController::class, 'store']);

    // ===== Trade Events (NEW) =====
    Route::post('/trade/opened', [TradeEventController::class, 'store']);

    // ===== Trade Outcomes (NEW - P0-1) =====
    // Stores complete trade outcome when trade closes: profit, patterns, technical indicators
    Route::post('/trade/outcome', [TradeOutcomeController::class, 'store']);
    Route::get('/trade/outcome/{ticket}', [TradeOutcomeController::class, 'getByTicket']);
    Route::get('/trade/outcome/stats', [TradeOutcomeController::class, 'getStats']);
    Route::get('/trade/outcome/pattern-analysis', [TradeOutcomeController::class, 'getPatternAnalysis']);

    // ===== Trade Entry Context (NEW - P0-4) =====
    // Stores technical state (RSI, patterns, trend) AT ENTRY for proper AI training
    Route::post('/trade/entry-context', [TradeEntryContextController::class, 'store']);
    Route::get('/trade/entry-context/{ticket}', [TradeEntryContextController::class, 'getByTicket']);
    Route::get('/trade/entry-context/training/data', [TradeEntryContextController::class, 'getTrainingData']);
    Route::get('/trade/entry-context/analytics/patterns', [TradeEntryContextController::class, 'patternAnalytics']);

    // ===== P0 Verification & Monitoring (NEW - P0-7) =====
    // Verify race condition protections are installed and working
    Route::get('/debug/verify-p0-7', [P0RaceConditionVerifierController::class, 'verify']);
    Route::get('/debug/dedup-stats', [P0RaceConditionVerifierController::class, 'dedupStats']);
    Route::get('/debug/trace-outcome/{correlationId}', [P0RaceConditionVerifierController::class, 'traceOutcome']);

    // ===== Bot Status =====
    Route::post('/bot/status', [BotStatusController::class, 'update']);
    Route::get('/bot/status', [BotStatusController::class, 'latest']);

    // ===== News Events =====
    Route::get('/news/list', function () {
        return \App\Models\NewsEvent::orderBy('event_time', 'asc')->get();
    });
    Route::post('/news/store', [NewsController::class, 'store']);
    Route::get('/news/next', [NewsController::class, 'next']);

    // ===== Account Snapshots =====
    Route::post('/account/snapshot', [AccountController::class, 'store']);

    // ===== Daily Summary (NEW) =====
    Route::post('/trading/daily-summary', [DailySummaryController::class, 'store']);

    // ===== Position Updates (NEW) =====
    Route::post('/position/update', [PositionUpdateController::class, 'store']);

    // ===== Loss Limit Alerts (NEW) =====
    Route::post('/alert/daily-loss-limit', [LossLimitAlertController::class, 'store']);

    // ===== Filter Blocks (NEW) =====
    Route::post('/filter/blocked', [FilterBlockController::class, 'store']);

    // ===== Technical Signals (NEW) =====
    Route::post('/signal/technical', [TechnicalSignalController::class, 'store']);

    // ===== EA Status Changes (NEW) =====
    Route::post('/ea/status-change', [EaStatusChangeController::class, 'store']);

    // ===== Error Logs (NEW) =====
    Route::post('/error/log', [ErrorLogController::class, 'store']);

    // ===== Whatsapp Signals =====
    Route::post('/whatsapp_signal', [WhatsappSignalController::class, 'store']);

    Route::post('/latestForEA', [WhatsappSignalController::class, 'latestForEA']);

    //Now here we are updating the whatsapp signal as received
    Route::post('/whatsapp_signal/mark_received/{id}', [WhatsappSignalController::class, 'markAsReceived']);
});
