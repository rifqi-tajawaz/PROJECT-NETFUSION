<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'verified', 'two-factor'])
                ->prefix('mikrotik-suite')
                ->name('mikrotik-suite.')
                ->group(base_path('routes/mikrotik-suite.php'));

            Route::middleware(['web', 'auth', 'admin', 'admin.2fa'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth'])
                ->prefix('account')
                ->name('account.')
                ->group(base_path('routes/security.php'));

            Route::middleware(['web'])
                ->group(base_path('routes/fallback.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'two-factor' => \App\Http\Middleware\EnsureTwoFactorVerified::class,
            'rate-limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'device-fingerprint' => \App\Http\Middleware\DeviceFingerprintMiddleware::class,
            'router.connected' => \App\Http\Middleware\EnsureRouterConnected::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'prevent-impersonation-actions' => \App\Http\Middleware\PreventActionsWhileImpersonating::class,
            'check-login-attempts' => \App\Http\Middleware\CheckLoginAttempts::class,
            'check-password-expiration' => \App\Http\Middleware\CheckPasswordExpiration::class,
            'admin.2fa' => \App\Http\Middleware\EnforceAdminTwoFactor::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\EnsureSingleSession::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\CheckImpersonationExpiration::class,
            \App\Http\Middleware\CheckPasswordExpiration::class,
        ]);

        // Apply rate limiting to authentication routes
        // $middleware->group([
        //     'rate-limit:5,1' => ['login', 'register', 'password.request', 'password.email'],
        //     'rate-limit:10,1' => ['password.reset', 'verification.resend'],
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
