<?php

namespace App\Repositories;

use App\Models\SecurityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SecurityLogRepository extends BaseRepository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(SecurityLog $model)
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
     * Get logs by event type.
     */
    public function getByEventType(string $eventType, int $perPage = 20): LengthAwarePaginator
    {
        return $this->with(['user'])
            ->where('event_type', $eventType)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get critical security events.
     */
    public function getCriticalEvents(int $limit = 100): Collection
    {
        $criticalEvents = [
            'account_locked',
            'suspicious_activity',
            'multiple_failed_logins',
        ];

        return $this->with(['user'])
            ->whereIn('event_type', $criticalEvents)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent security alerts.
     */
    public function getRecentAlerts(int $limit = 50): Collection
    {
        return $this->with(['user'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get security statistics.
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
            'total_events' => $query->count(),
            'by_event_type' => $query->selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->pluck('count', 'event_type')
                ->toArray(),
        ];
    }

    /**
     * Check for suspicious activity patterns.
     */
    public function detectSuspiciousActivity(string $ip, int $threshold = 5): bool
    {
        $recentAttempts = $this->query()
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->whereIn('event_type', ['multiple_failed_logins', 'suspicious_activity'])
            ->count();

        return $recentAttempts >= $threshold;
    }
}
