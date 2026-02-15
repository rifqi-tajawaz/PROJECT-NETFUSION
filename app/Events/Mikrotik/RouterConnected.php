<?php

namespace App\Events\Mikrotik;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class RouterConnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $routerId;
    public $routerName;
    public $connectedBy;
    public $connectedAt;

    /**
     * Create a new event instance.
     */
    public function __construct(int $routerId, string $routerName, User $connectedBy)
    {
        $this->routerId = $routerId;
        $this->routerName = $routerName;
        $this->connectedBy = $connectedBy;
        $this->connectedAt = Carbon::now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('routers.' . $this->routerId),
            new Channel('routers'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'router.connected';
    }
}
