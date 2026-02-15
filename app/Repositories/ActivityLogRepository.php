<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ActivityLogRepository extends BaseRepository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    /**
     * Get logs by user ID.
     */
    public function getByUserId(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->with(['user'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get logs by action type.
     */
    public function getByAction(string $action, int $perPage = 20): LengthAwarePaginator
    {
        return $this->with(['user'])
            ->where('action', $action)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get recent logs.
     */
    public function getRecent(int $limit = 50): Collection
    {
        return $this->with(['user'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get logs by date range.
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->with(['user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get activity statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        return [
            'total_actions' => $query->count(),
            'by_action' => $query->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
        ];
    }
}
