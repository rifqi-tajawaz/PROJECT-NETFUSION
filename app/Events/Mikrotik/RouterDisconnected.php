<?php

namespace App\Events\Mikrotik;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class RouterDisconnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $routerId;
    public $routerName;
    public $disconnectedBy;
    public $disconnectedAt;
    public $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(int $routerId, string $routerName, User $disconnectedBy, string $reason = 'manual')
    {
        $this->routerId = $routerId;
        $this->routerName = $routerName;
        $this->disconnectedBy = $disconnectedBy;
        $this->disconnectedAt = Carbon::now();
        $this->reason = $reason;
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
        return 'router.disconnected';
    }
}
