<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

// Models & Policies
use App\Models\User;

// Contracts
use App\Contracts\Auth\AuthenticationManagerInterface;
use App\Contracts\Auth\OAuthManagerInterface;
use App\Contracts\Auth\SecurityManagerInterface;
use App\Contracts\Auth\SessionManagerInterface;

// Services & Repositories
use App\Repositories\UserRepository;
use App\Services\Auth\AuthLogger;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthenticationManager;
use App\Services\Auth\OAuthManager;
use App\Services\Auth\SecurityManager;
use App\Services\Auth\SessionManager;
use App\Services\Auth\Strategies\LocalAuthStrategy;

// Events
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\Mikrotik\RouterConnected;
use App\Events\Mikrotik\RouterDisconnected;
use App\Events\Hotspot\UserCreated;
use App\Events\Hotspot\UserDeleted;
use App\Events\Security\SecurityAlert;
use App\Events\System\BackupCompleted;

// Listeners
use App\Listeners\User\LogUserLogin;
use App\Listeners\User\UpdateLastLogin;
use App\Listeners\User\ClearLoginAttempts;
use App\Listeners\Mikrotik\LogRouterConnection;
use App\Listeners\Mikrotik\CacheRouterStatus;
use App\Listeners\Hotspot\LogHotspotUserCreation;
use App\Listeners\Security\HandleSecurityAlert;
use App\Listeners\System\CleanupOldBackups;

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

        // --- From AuthServiceProvider ---

        // Register UserRepository
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        // Register AuthenticationManager
        $this->app->singleton(AuthenticationManagerInterface::class, function ($app) {
            return new AuthenticationManager($app->make(UserRepository::class));
        });

        // Register OAuthManager
        $this->app->singleton(OAuthManagerInterface::class, function ($app) {
            return new OAuthManager($app->make(UserRepository::class));
        });

        // Register SecurityManager
        $this->app->singleton(SecurityManagerInterface::class, function ($app) {
            return new SecurityManager($app->make(UserRepository::class));
        });

        // Register SessionManager
        $this->app->singleton(SessionManagerInterface::class, function ($app) {
            return new SessionManager($app->make(UserRepository::class));
        });

        // Register AuthLogger
        $this->app->singleton(AuthLogger::class, function ($app) {
            return new AuthLogger();
        });

        // Register AuthService
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(AuthLogger::class),
                $app->make(UserRepository::class)
            );
        });

        // Register LocalAuthStrategy
        $this->app->singleton(LocalAuthStrategy::class, function ($app) {
            return new LocalAuthStrategy($app->make(UserRepository::class));
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

        // --- From AuthServiceProvider ---

        // Register middleware aliases if needed
        $this->app['router']->aliasMiddleware('auth.security', \App\Http\Middleware\SecurityHeadersMiddleware::class);
        $this->app['router']->aliasMiddleware('auth.2fa', \App\Http\Middleware\EnsureTwoFactorVerified::class);

        // --- From EventServiceProvider ---

        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );

        foreach ([
            LogUserLogin::class,
            UpdateLastLogin::class,
            ClearLoginAttempts::class,
        ] as $listener) {
            Event::listen(UserLoggedIn::class, $listener);
        }

        // UserLoggedOut::class - empty in original

        foreach ([
            LogRouterConnection::class,
            CacheRouterStatus::class,
        ] as $listener) {
            Event::listen(RouterConnected::class, $listener);
        }

        // RouterDisconnected::class - empty in original

        Event::listen(
            UserCreated::class,
            LogHotspotUserCreation::class
        );

        // UserDeleted::class - empty in original

        Event::listen(
            SecurityAlert::class,
            HandleSecurityAlert::class
        );

        Event::listen(
            BackupCompleted::class,
            CleanupOldBackups::class
        );

        // Existing listener
        Event::listen(
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
