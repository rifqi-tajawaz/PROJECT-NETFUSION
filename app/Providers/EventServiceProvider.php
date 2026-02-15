<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Custom Events
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\Mikrotik\RouterConnected;
use App\Events\Mikrotik\RouterDisconnected;
use App\Events\Hotspot\UserCreated;
use App\Events\Hotspot\UserDeleted;
use App\Events\Security\SecurityAlert;
use App\Events\System\BackupCompleted;

// Custom Listeners
use App\Listeners\User\LogUserLogin;
use App\Listeners\User\UpdateLastLogin;
use App\Listeners\User\ClearLoginAttempts;
use App\Listeners\Mikrotik\LogRouterConnection;
use App\Listeners\Mikrotik\CacheRouterStatus;
use App\Listeners\Hotspot\LogHotspotUserCreation;
use App\Listeners\Security\HandleSecurityAlert;
use App\Listeners\System\CleanupOldBackups;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // User Events
        UserLoggedIn::class => [
            LogUserLogin::class,
            UpdateLastLogin::class,
            ClearLoginAttempts::class,
        ],

        UserLoggedOut::class => [
            // Add logout listeners here
        ],

        // Mikrotik Router Events
        RouterConnected::class => [
            LogRouterConnection::class,
            CacheRouterStatus::class,
        ],

        RouterDisconnected::class => [
            // Add disconnect listeners here
        ],

        // Hotspot Events
        UserCreated::class => [
            LogHotspotUserCreation::class,
        ],

        UserDeleted::class => [
            // Add deletion listeners here
        ],

        // Security Events
        SecurityAlert::class => [
            HandleSecurityAlert::class,
        ],

        // System Events
        BackupCompleted::class => [
            CleanupOldBackups::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
