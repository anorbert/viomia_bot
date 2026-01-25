<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Authentication\StaffController;

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


Route::get('/', function () {
    return view('welcome');
});

Route::resource('user_login',LoginController::class);
Route::resource('user_register',RegisterController::class);
Route::get('/user_register', [RegisterController::class, 'index'])->name('user_register');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

//Admin Routes - Protected by role middleware
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])
        ->name('dashboard.metrics');

        // Clients
        Route::resource('clients', ClientController::class);
        Route::get('clients/{client}/subscriptions', [ClientController::class, 'subscriptions'])
            ->name('clients.subscriptions');

        // Trading Accounts
        Route::resource('accounts', AccountController::class);
        Route::get('accounts/{account}/verify', [AccountController::class, 'verify'])
            ->name('accounts.verify');
        Route::get('accounts-pending', [AccountController::class, 'pending'])
            ->name('accounts.pending');

        // Bots
        Route::resource('bots', BotController::class);
        Route::get('bots/logs', [BotController::class, 'logs'])->name('bots.logs');
        Route::get('bots/{id}/settings', [BotController::class, 'settings'])->name('bots.settings');
        Route::post('bots/{id}/settings/update', [BotController::class, 'updateSettings'])->name('bots.settings.update');

        // Trading Activity
        Route::resource('trades', TradeLogController::class);
        Route::get('trades/statistics', [TradeController::class, 'statistics'])->name('trades.statistics');
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
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('roles', RoleController::class);
        Route::resource('settings', SettingController::class);
        Route::post('settings/save', [SettingController::class, 'save'])->name('settings.save');

        // Signals
        Route::resource('signals', SignalController::class);

    });

