<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
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
            'ticket_id' => $this->ticket_id,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'role' => $this->user->role?->name,
            ]),
            'message' => $this->message,
            'is_admin' => $this->is_admin,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at_human' => $this->created_at->diffForHumans(),
        ];
    }
}
