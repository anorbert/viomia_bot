<?php

// Add these routes to routes/api.php or routes/web.php

use App\Http\Controllers\User\PaymentController;

Route::middleware(['auth:sanctum'])->group(function () {
    // User payment history and audit logs
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::get('/payments/{paymentId}/audit-log', [PaymentController::class, 'auditLog'])->name('payments.audit-log');
    
    // Payment management (requires HTTPS)
    Route::middleware(['payment.secure'])->group(function () {
        Route::post('/payments/{paymentId}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        Route::post('/payments/{paymentId}/resend-link', [PaymentController::class, 'resendPaymentLink'])->name('payments.resend-link');
    });
});

// Public webhook endpoint (protected by signature validation)
Route::middleware(['payment.secure'])->post('/webhooks/momo', [PaymentController::class, 'momoWebhook'])->name('payments.momo-webhook');
