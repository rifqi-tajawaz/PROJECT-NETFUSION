<?php

namespace App\Listeners\User;

use App\Events\User\UserLoggedIn;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LogUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'action' => 'login',
            'description' => 'User logged in ' . ($event->remembered ? 'with "Remember Me"' : 'without "Remember Me"'),
            'ip_address' => $event->ip,
            'user_agent' => $event->userAgent,
            'metadata' => [
                'remembered' => $event->remembered,
                'logged_in_at' => $event->loggedAt->toDateTimeString(),
            ],
        ]);

        Log::info('User logged in', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
