<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ?? null,
            'role' => $this->role?->name,
            'membership_status' => $this->membership_status,
            'membership_expires_at' => $this->membership_expires_at?->toDateTimeString(),
            'email_verified' => !is_null($this->email_verified_at),
            'two_factor_enabled' => !is_null($this->two_factor_secret),
            'last_login_at' => $this->last_login_at?->toDateTimeString(),
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
