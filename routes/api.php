<?php

use App\Http\Controllers\Bot\SignalController;
use App\Http\Controllers\Bot\TradeLogController;
use App\Http\Controllers\Bot\BotStatusController;

Route::middleware('apikey')->group(function () {

    // Signals
    Route::get('/signal', [SignalController::class, 'getActive']);
    Route::post('/signal', [SignalController::class, 'store']);

    // Trade Logs
    Route::post('/trade/log', [TradeLogController::class, 'store']);

    // Bot Status
    Route::post('/bot/status', [BotStatusController::class, 'update']);
    Route::get('/bot/status', [BotStatusController::class, 'latest']);

});
