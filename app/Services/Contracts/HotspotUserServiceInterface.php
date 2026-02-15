<?php

namespace App\Services\Contracts;

use App\Models\HotspotUser;
use Illuminate\Pagination\LengthAwarePaginator;

interface HotspotUserServiceInterface
{
    /**
     * Get all hotspot users with pagination.
     */
    public function getAllUsers(int $perPage = 20): LengthAwarePaginator;

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): HotspotUser;

    /**
     * Create new hotspot user.
     */
    public function createUser(array $data): HotspotUser;

    /**
     * Create multiple hotspot users.
     */
    public function createUsers(array $usersData): array;

    /**
     * Update hotspot user.
     */
    public function updateUser(int $id, array $data): HotspotUser;

    /**
     * Delete hotspot user.
     */
    public function deleteUser(int $id): bool;

    /**
     * Sync users to router.
     */
    public function syncToRouter(int $routerId): bool;

    /**
     * Get active users count.
     */
    public function getActiveUsersCount(): int;

    /**
     * Get expired users.
     */
    public function getExpiredUsers(): array;

    /**
     * Extend user validity.
     */
    public function extendValidity(int $id, string $duration): HotspotUser;
}
