<?php

namespace App\Repositories;

use App\Models\HotspotUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class HotspotUserRepository extends BaseRepository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(HotspotUser $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active users.
     */
    public function getActiveUsers(): Collection
    {
        return $this->query()->where('status', 'active')->get();
    }

    /**
     * Get users by router ID.
     */
    public function getUsersByRouter(int $routerId): Collection
    {
        return $this->query()->where('router_id', $routerId)->get();
    }

    /**
     * Get expired users.
     */
    public function getExpiredUsers(): Collection
    {
        return $this->query()
            ->where('valid_until', '<', now())
            ->get();
    }

    /**
     * Get users with profile.
     */
    public function getUsersWithProfile(): Collection
    {
        return $this->with(['profile'])->get();
    }

    /**
     * Find user by username.
     */
    public function findByUsername(string $username): ?Model
    {
        return $this->findBy('username', $username);
    }

    /**
     * Get users count by status.
     */
    public function getCountByStatus(): array
    {
        return $this->query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get top bandwidth users.
     */
    public function getTopBandwidthUsers(int $limit = 10): Collection
    {
        return $this->query()
            ->selectRaw('*, (bytes_in + bytes_out) as total_bytes')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }
}
