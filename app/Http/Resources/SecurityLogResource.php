<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecurityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'event_type' => $this->event_type,
            'description' => $this->description,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'metadata' => $this->metadata,
            'severity' => $this->getSeverity(),
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at_human' => $this->created_at->diffForHumans(),
        ];
    }

    /**
     * Get severity level based on event type.
     */
    private function getSeverity(): string
    {
        return match($this->event_type) {
            'account_locked', 'suspicious_activity' => 'critical',
            'multiple_failed_logins', 'new_device' => 'high',
            'password_change', 'two_factor_disabled' => 'medium',
            default => 'low',
        };
    }
}
