<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Authentication\StaffController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\UserController;

//Admin

use App\Http\Controllers\Users\UserReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user_login',LoginController::class);
Route::resource('user_register',RegisterController::class);
Route::get('/user_register', [RegisterController::class, 'index'])->name('user_register');

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::resource('vehicles', ExemptedVehicleController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reports', UserReportController::class);
    Route::resource('change-pin', LoginController::class);
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::middleware('auth')->group(function () {
    //Dashboard
    Route::resource('staff',StaffController::class);
    Route::resource('vehicles',VehicleController::class);
   
    Route::resource('logs',ExitLogsController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/editor/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');  

});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route::resource('exempted-vehicles', ExemptedVehicleController::class);
    Route::resource('payments', AdminPaymentController::class);
    Route::resource('reports', AdminReportController::class);

});

