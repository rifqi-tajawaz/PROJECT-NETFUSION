<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(\App\Services\NetFusion\MikhmonAPI::class, function ($app) {
            $api = new \App\Services\NetFusion\MikhmonAPI();
            // Connection handled by EnsureRouterConnected middleware
            return $api;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(); // Checks HaveIBeenPwned API
        });

        \Illuminate\Support\Facades\Event::listen(
            [
                \Illuminate\Auth\Events\Login::class,
                \Illuminate\Auth\Events\Logout::class,
                \Illuminate\Auth\Events\Failed::class,
                \Illuminate\Auth\Events\PasswordReset::class,
                \Illuminate\Auth\Events\Registered::class,
            ],
            \App\Listeners\LogAuthActivity::class
        );

        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
