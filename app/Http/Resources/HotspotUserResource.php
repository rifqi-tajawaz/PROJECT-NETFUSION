<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotspotUserResource extends JsonResource
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
            'username' => $this->username,
            'profile' => $this->whenLoaded('profile', fn() => $this->profile),
            'router_id' => $this->router_id,
            'status' => $this->status,
            'uptime' => $this->uptime,
            'limit_uptime' => $this->limit_uptime,
            'bytes_in' => $this->bytes_in ?? 0,
            'bytes_out' => $this->bytes_out ?? 0,
            'bytes_in_formatted' => $this->formatBytes($this->bytes_in ?? 0),
            'bytes_out_formatted' => $this->formatBytes($this->bytes_out ?? 0),
            'total_bytes' => ($this->bytes_in ?? 0) + ($this->bytes_out ?? 0),
            'total_bytes_formatted' => $this->formatBytes(($this->bytes_in ?? 0) + ($this->bytes_out ?? 0)),
            'comment' => $this->comment,
            'created_at' => $this->created_at?->toDateTimeString(),
            'last_seen' => $this->last_seen?->toDateTimeString(),
            'valid_until' => $this->valid_until?->toDateTimeString(),
            'is_expired' => $this->valid_until && $this->valid_until->isPast(),
        ];
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
