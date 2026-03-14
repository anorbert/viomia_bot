<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Authentication\StaffController;
use App\Http\Controllers\SupportController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorController;

//Admin
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\SignalController;
use App\Http\Controllers\ExitLogsController;
use App\Http\Controllers\Admin\UserController;

//Bot Controllers
use App\Http\Controllers\Bot\TradeLogController;

//User Controllers here!!!!!!!!!!!!!!!!!!!!
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserPaymentController;
use App\Http\Controllers\User\UserSignalController;
use App\Http\Controllers\User\UserTradeController;
use App\Http\Controllers\User\UserAccountController;
use App\Http\Controllers\ActivityTrackerController;

//AI Analytics Controllers
use App\Http\Controllers\Admin\AI\AnalyticsController;
use App\Http\Controllers\Admin\AI\CandleLogController;
use App\Http\Controllers\Admin\AI\DecisionController;
use App\Http\Controllers\Admin\AI\SignalLogController;
use App\Http\Controllers\Admin\AI\TradeExecutionController;
use App\Http\Controllers\Admin\AI\TradeOutcomeController;


Route::get('/', function () {
    $subscriptionPlans = \App\Models\SubscriptionPlan::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    return view('index', compact('subscriptionPlans'));
});
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/risk-disclosure', 'risk-disclosure')->name('risk-disclosure');
Route::view('/technology', 'technology')->name('technology');
Route::view('/help', 'help.index')->name('help');

// Support routes (require auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/support', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/tickets', [SupportController::class, 'userTickets'])->name('support.tickets');
});

// Activity Tracking Routes (require auth) - for auto-logout prevention
Route::middleware(['auth'])->group(function () {
    Route::post('/activity/track', [ActivityTrackerController::class, 'trackActivity'])->name('activity.track');
    Route::get('/activity/remaining-time', [ActivityTrackerController::class, 'getRemainingTime'])->name('activity.remaining-time');
    Route::post('/activity/logout', [ActivityTrackerController::class, 'forceLogout'])->name('activity.logout');
});

Route::resource('user_login',LoginController::class);
Route::resource('user_register',RegisterController::class);
Route::get('/user_register', [RegisterController::class, 'index'])->name('user_register');

// Callbacks (NO auth — provider posts here)
Route::post('/payments/callback/momo', [CheckoutController::class, 'momoCallback'])->name('payments.callback.momo');
Route::post('/payments/webhook/binance', [CheckoutController::class, 'binanceWebhook'])->name('payments.webhook.binance');

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Apply cross-role prevention and user-only middleware
    Route::middleware(['prevent-cross-role', 'prevent-admin-user-access', 'verify-ownership'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/metrics', [UserDashboardController::class, 'metrics'])
        ->name('dashboard.metrics');
    
    // Change PIN
    Route::get('/change-pin', [LoginController::class, 'create'])->name('change-pin.create');
    Route::post('/change-pin', [LoginController::class, 'update'])->name('change-pin.update');
    
    Route::resource('/profile', UserProfileController::class);
    Route::get('/profile/{id}/change-password', [UserProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/{id}/change-password', [UserProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::resource('/password', UserPasswordController::class);

    Route::resource('/subscriptions', UserSubscriptionController::class);
    Route::post('/subscriptions/payment', [UserSubscriptionController::class,'payment'])->name('subscriptions.payment');
    Route::get('/subscriptions/payment-pending/{reference}', [UserSubscriptionController::class,'paymentPending'])->name('subscriptions.payment-pending');
    Route::get('/subscriptions/payment-status/{reference}', [UserSubscriptionController::class,'paymentStatus'])->name('subscriptions.payment-status');
    Route::resource('/payments', UserPaymentController::class);
    Route::get('/payments/{id}/pdf', [UserPaymentController::class, 'downloadPDF'])->name('payments.pdf');
    Route::get('/payments-export/pdf', [UserPaymentController::class, 'exportPDF'])->name('payments.export-pdf');

    Route::resource('/signals', UserSignalController::class);
    Route::get('/executions', [UserSignalController::class,'executions'])->name('executions.index');
    Route::get('/weekly-report', [UserSignalController::class,'weeklyReport'])->name('weekly-report.index');
    Route::post('/weekly-payment', [UserSignalController::class,'storePayment'])->name('weekly-payment.store');

    // Trading activity pages (create controllers later)
    Route::get('/trades/open', [UserTradeController::class,'open'])->name('trades.open');
    Route::get('/trades/history', [UserTradeController::class,'history'])->name('trades.history');

    // Accounts pages (create controllers later)
    Route::resource('/accounts', UserAccountController::class);
    Route::get('/accounts/pending', [UserAccountController::class,'pending'])->name('accounts.pending');
    Route::get('/accounts/activate/{id}', [UserAccountController::class,'activateAccount'])->name('accounts.activate');

    // Plans + checkout
    Route::get('/plans', [CheckoutController::class, 'plans'])->name('plans.index');
    Route::get('/plans/{plan:slug}', [CheckoutController::class, 'showPlan'])->name('plans.show');

    Route::post('/checkout/{plan:slug}', [CheckoutController::class, 'start'])->name('checkout.start');
    Route::get('/checkout/pending/{reference}', [CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::get('/checkout/status/{reference}', [CheckoutController::class, 'status'])->name('checkout.status');
    });
});

//Admin Routes - Protected by role middleware & cross-role access prevention
Route::prefix('admin')
    ->middleware(['auth', 'role:admin', 'prevent-cross-role', 'admin-only'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        Route::get('/dashboard/metrics', [AdminController::class, 'metrics'])
        ->name('dashboard.metrics');

        // Clients
        Route::resource('clients', ClientController::class);
        Route::get('clients/{client}/subscriptions', [ClientController::class, 'subscriptions'])
            ->name('clients.subscriptions');

        // Trading Accounts
        Route::resource('accounts', AccountController::class);
        Route::get('accounts-pending', [AccountController::class, 'pending'])->name('accounts.pending');
        Route::post('accounts/{account}/verify', [AccountController::class, 'verifyAccount'])->name('accounts.verify');
        Route::post('accounts/{account}/reject', [AccountController::class, 'rejectAccount'])->name('accounts.reject');

        // Bots
        Route::resource('bots', BotController::class);
        Route::get('bots/logs', [BotController::class, 'logs'])->name('bots.logs');
        Route::get('bots/{id}/settings', [BotController::class, 'settings'])->name('bots.settings');
        Route::post('bots/{id}/settings/update', [BotController::class, 'updateSettings'])->name('bots.settings.update');

        // Trading Activity
        Route::resource('trades', TradeLogController::class);

        // Route::get('trades/statistics', [TradeController::class, 'statistics'])->name('trades.statistics');
        Route::get('statistics', [TradeController::class, 'statistic1'])->name('trades.statistics');

        Route::get('trades/symbols', [TradeController::class, 'symbols'])->name('trades.symbols');

        // Payments
        Route::resource('banks', BankController::class);
        Route::post('/banks/{id}/toggle', [BankController::class, 'toggle'])->name('banks.toggle'); // optional
        Route::resource('subscription_plans', SubscriptionPlanController::class);

        Route::resource('payments', PaymentController::class);
        Route::get('payment-plans', [PaymentController::class, 'plans'])->name('payments.plans');
        Route::get('payment-reports', [PaymentController::class, 'reports'])->name('payments.reports');

        // Settings
        Route::resource('users', UserController::class);
        Route::post('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('roles', RoleController::class);
        Route::resource('settings', SettingController::class);
        Route::post('settings/save', [SettingController::class, 'save'])->name('settings.save');

        // Signals
        Route::resource('signals', SignalController::class);

        //AI Analytics Routes
        Route::prefix('ai')->name('ai.')->group(function () {
            // AI Dashboard
            Route::get('dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
            // Market Data (Candles)
            Route::resource('candles', CandleLogController::class);
            // AI Decisions
            Route::resource('decisions', DecisionController::class);
            // Signals Sent
            Route::resource('signal-logs', SignalLogController::class);
            // Trade Executions
            Route::resource('executions', TradeExecutionController::class);
            // Trade Outcomes
            Route::resource('outcomes', TradeOutcomeController::class);
            // AI Performance
            Route::get('performance', [AnalyticsController::class, 'performance'])->name('performance');
        });
    });

// Help & Support Page - Public route
Route::view('/help', 'help.index')->name('help');

// Contact Form - Public route
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    // Validate the form data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:100',
        'message' => 'required|string|max:5000',
    ]);
    
    // TODO: Send email to support team
    // TODO: Store contact form data in database or mail service
    
    // For now, just redirect back with success message
    return redirect('/')->with('status', 'Thank you for contacting us! We will get back to you soon.');
})->name('contact.store');