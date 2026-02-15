<?php

namespace App\Listeners\User;

use App\Events\User\UserLoggedIn;
use Illuminate\Support\Facades\Cache;

class ClearLoginAttempts
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        // Clear login attempts for this IP and email
        $key = 'login_attempts:' . $event->ip . ':' . $event->user->email;
        Cache::forget($key);
    }
}
