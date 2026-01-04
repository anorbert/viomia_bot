<?php

use App\Http\Controllers\Bot\SignalController;
use App\Http\Controllers\Bot\TradeLogController;
use App\Http\Controllers\Bot\BotStatusController;
use App\Http\Controllers\Bot\NewsController;
use App\Http\Controllers\Bot\AccountController;
use App\Http\Controllers\Bot\TradeEventController;
use App\Http\Controllers\Bot\DailySummaryController;
use App\Http\Controllers\Bot\PositionUpdateController;
use App\Http\Controllers\Bot\LossLimitAlertController;
use App\Http\Controllers\Bot\FilterBlockController;
use App\Http\Controllers\Bot\TechnicalSignalController;
use App\Http\Controllers\Bot\EaStatusChangeController;
use App\Http\Controllers\Bot\ErrorLogController;
use App\Middleware\CheckApiKey;

Route::prefix('bot')->middleware(CheckApiKey::class)->group(function () {
    // ===== Signals =====
    Route::get('/signal', [SignalController::class, 'getActive']);
    Route::post('/signal', [SignalController::class, 'store']);

    // ===== Trade Logs =====
    Route::post('/trade/log', [TradeLogController::class, 'store']);

    // ===== Trade Events (NEW) =====
    Route::post('/trade/opened', [TradeEventController::class, 'store']);

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
});
