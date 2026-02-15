<?php

namespace App\Listeners\User;

use App\Events\User\UserLoggedIn;

class UpdateLastLogin
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => $event->ip,
        ]);
    }
}
