<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $ip;
    public string $userAgent;
    public bool $remembered;
    public $loggedAt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $ip, string $userAgent, bool $remembered = false)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->remembered = $remembered;
        $this->loggedAt = now();
    }
}
