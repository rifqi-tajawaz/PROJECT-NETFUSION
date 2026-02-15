<?php

namespace App\Events\Security;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SecurityAlert
{
    use Dispatchable, SerializesModels;

    public User $user;
    public string $alertType;
    public array $metadata;
    public $occurredAt;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $alertType, array $metadata = [])
    {
        $this->user = $user;
        $this->alertType = $alertType;
        $this->metadata = $metadata;
        $this->occurredAt = now();
    }
}
