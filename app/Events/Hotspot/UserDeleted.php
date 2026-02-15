<?php

namespace App\Events\Hotspot;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $username;
    public User $deletedBy;
    public int $routerId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $username, User $deletedBy, int $routerId)
    {
        $this->username = $username;
        $this->deletedBy = $deletedBy;
        $this->routerId = $routerId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hotspot.' . $this->routerId),
            new Channel('hotspot'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'hotspot.user.deleted';
    }
}
