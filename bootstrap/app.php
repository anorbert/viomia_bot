<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'user.active' => \App\Http\Middleware\CheckUserActive::class,
            'payment.secure' => \App\Http\Middleware\EnsurePaymentSecure::class,
            'admin-only' => \App\Http\Middleware\AdminOnlyMiddleware::class,
            'user-only' => \App\Http\Middleware\UserOnlyMiddleware::class,
            'prevent-admin-user-access' => \App\Http\Middleware\PreventAdminAccessUserResources::class,
            'prevent-user-admin-access' => \App\Http\Middleware\PreventUserAccessAdminResources::class,
            'prevent-cross-role' => \App\Http\Middleware\PreventCrossRoleResourceAccess::class,
            'enforce-role-access' => \App\Http\Middleware\EnforceRoleBasedRouteAccess::class,
            'verify-ownership' => \App\Http\Middleware\VerifyResourceOwnership::class,
            'auto-logout' => \App\Http\Middleware\AutoLogoutMiddleware::class,
        ]);

        // Append auto-logout to all web routes
        $middleware->appendToGroup('web', \App\Http\Middleware\AutoLogoutMiddleware::class);

        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
