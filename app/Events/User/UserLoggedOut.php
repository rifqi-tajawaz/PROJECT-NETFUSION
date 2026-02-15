<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedOut
{
    use Dispatchable, SerializesModels;

    public User $user;
    public string $sessionId;
    public $loggedOutAt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $sessionId)
    {
        $this->user = $user;
        $this->sessionId = $sessionId;
        $this->loggedOutAt = now();
    }
}
