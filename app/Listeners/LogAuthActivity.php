<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;

class LogAuthActivity
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $action = $this->getActionName($event);
        $user = $this->getUser($event);

        if (!$action) {
            return;
        }

        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $this->getDescription($event),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function getActionName($event)
    {
        return match (get_class($event)) {
            Login::class => 'LOGIN',
            Logout::class => 'LOGOUT',
            Failed::class => 'LOGIN_FAILED',
            PasswordReset::class => 'PASSWORD_RESET',
            Registered::class => 'REGISTERED',
            default => null,
        };
    }

    protected function getUser($event)
    {
        if (isset($event->user) && $event->user) {
            return $event->user;
        }

        // For failed login, we might strictly not have a user object user-provided credentials
        if ($event instanceof Failed && isset($event->user)) {
            return $event->user;
        }

        return null;
    }

    protected function getDescription($event)
    {
        if ($event instanceof Failed) {
            return 'Failed login attempt for email: ' . ($event->credentials['email'] ?? 'unknown');
        }

        return 'User ' . strtolower(class_basename($event));
    }
}
