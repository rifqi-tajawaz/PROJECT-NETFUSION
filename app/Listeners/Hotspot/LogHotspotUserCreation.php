<?php

namespace App\Listeners\Hotspot;

use App\Events\Hotspot\UserCreated;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LogHotspotUserCreation
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        ActivityLog::create([
            'user_id' => $event->creator->id,
            'action' => 'hotspot_user_created',
            'description' => "Hotspot user '{$event->hotspotUser->username}' created",
            'metadata' => [
                'hotspot_user_id' => $event->hotspotUser->id,
                'username' => $event->hotspotUser->username,
                'profile' => $event->hotspotUser->profile,
                'router_id' => $event->routerId,
            ],
        ]);

        Log::info('Hotspot user created', [
            'hotspot_user_id' => $event->hotspotUser->id,
            'username' => $event->hotspotUser->username,
            'created_by' => $event->creator->id,
        ]);
    }
}
