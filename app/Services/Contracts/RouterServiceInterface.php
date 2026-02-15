<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;

interface RouterServiceInterface
{
    /**
     * Connect to router.
     */
    public function connect(array $credentials): bool;

    /**
     * Disconnect from router.
     */
    public function disconnect(int $routerId): bool;

    /**
     * Execute command on router.
     */
    public function executeCommand(int $routerId, string $command): array;

    /**
     * Get router information.
     */
    public function getRouterInfo(int $routerId): array;

    /**
     * Get router system resources.
     */
    public function getSystemResources(int $routerId): array;

    /**
     * Sync data from router.
     */
    public function syncData(int $routerId): bool;

    /**
     * Test router connection.
     */
    public function testConnection(int $routerId): bool;

    /**
     * Get all routers status.
     */
    public function getAllRoutersStatus(): Collection;

    /**
     * Get online routers.
     */
    public function getOnlineRouters(): Collection;

    /**
     * Get offline routers.
     */
    public function getOfflineRouters(): Collection;
}
