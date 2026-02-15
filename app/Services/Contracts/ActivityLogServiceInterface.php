<?php

namespace App\Services\Contracts;

use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityLogServiceInterface
{
    /**
     * Log activity.
     */
    public function log(array $data): ActivityLog;

    /**
     * Get logs by user.
     */
    public function getLogsByUser(int $userId, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get recent logs.
     */
    public function getRecentLogs(int $limit = 50): array;

    /**
     * Get logs by action type.
     */
    public function getLogsByAction(string $action, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get activity statistics.
     */
    public function getStatistics(array $filters = []): array;

    /**
     * Clear old logs.
     */
    public function clearOldLogs(int $days = 90): int;
}
