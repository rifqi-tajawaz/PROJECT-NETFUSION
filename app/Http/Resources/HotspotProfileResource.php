<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotspotProfileResource extends JsonResource
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
            'router_id' => $this->router_id,
            'rate_limit' => $this->rate_limit,
            'status' => $this->status ?? 'active',
            'default_profile' => $this->default_profile ?? false,
            'users_count' => $this->whenCounted('users', fn() => $this->users_count ?? 0),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
