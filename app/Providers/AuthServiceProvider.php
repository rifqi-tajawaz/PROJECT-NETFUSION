<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Contracts\Auth\AuthenticationManagerInterface;
use App\Contracts\Auth\OAuthManagerInterface;
use App\Contracts\Auth\SecurityManagerInterface;
use App\Contracts\Auth\SessionManagerInterface;
use App\Repositories\UserRepository;
use App\Services\Auth\AuthLogger;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthenticationManager;
use App\Services\Auth\OAuthManager;
use App\Services\Auth\SecurityManager;
use App\Services\Auth\SessionManager;
use App\Services\Auth\Strategies\LocalAuthStrategy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
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
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register middleware aliases if needed
        $this->app['router']->aliasMiddleware('auth.security', \App\Http\Middleware\SecurityHeadersMiddleware::class);
        $this->app['router']->aliasMiddleware('auth.2fa', \App\Http\Middleware\EnsureTwoFactorVerified::class);

        // Publish configuration files if needed
        $this->publishes([
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ], 'auth-config');
    }
}
