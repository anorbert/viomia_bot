<?php

use App\Http\Controllers\Bot\SignalController;
use App\Http\Controllers\Bot\TradeLogController;
use App\Http\Controllers\Bot\BotStatusController;
use App\Http\Controllers\Bot\NewsController;

Route::prefix('bot')->group(function () {
    // Signals
    Route::get('/signal', [SignalController::class, 'getActive']);
    Route::post('/signal', [SignalController::class, 'store']);

    // Trade Logs
    Route::post('/trade/log', [TradeLogController::class, 'store']);

    // Bot Status
    Route::post('/bot/status', [BotStatusController::class, 'update']);
    Route::get('/bot/status', [BotStatusController::class, 'latest']);

    //Dealing with News Events
    Route::get('/news/list', function () {
        return \App\Models\NewsController::orderBy('event_time', 'asc')->get();
    });
    Route::post('/news/store', [NewsController::class, 'store']);

    Route::get('/news/next', [NewsController::class, 'next']);
});
