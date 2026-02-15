<?php

namespace App\Listeners\Mikrotik;

use App\Events\Mikrotik\RouterConnected;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LogRouterConnection
{
    /**
     * Handle the event.
     */
    public function handle(RouterConnected $event): void
    {
        ActivityLog::create([
            'user_id' => $event->connectedBy->id,
            'action' => 'router_connected',
            'description' => "Router '{$event->routerName}' connected successfully",
            'metadata' => [
                'router_id' => $event->routerId,
                'router_name' => $event->routerName,
                'connected_at' => $event->connectedAt->toDateTimeString(),
            ],
        ]);

        Log::info('Router connected', [
            'router_id' => $event->routerId,
            'router_name' => $event->routerName,
            'connected_by' => $event->connectedBy->id,
        ]);
    }
}
