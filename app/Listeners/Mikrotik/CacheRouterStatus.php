<?php

namespace App\Listeners\Mikrotik;

use App\Events\Mikrotik\RouterConnected;
use Illuminate\Support\Facades\Cache;

class CacheRouterStatus
{
    /**
     * Handle the event.
     */
    public function handle(RouterConnected $event): void
    {
        // Cache router status for 5 minutes
        Cache::put(
            "router_{$event->routerId}_status",
            'online',
            now()->addMinutes(5)
        );

        Cache::put(
            "router_{$event->routerId}_last_seen",
            now()->toDateTimeString(),
            now()->addHours(1)
        );
    }
}
