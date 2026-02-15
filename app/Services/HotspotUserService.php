<?php

namespace App\Services;

use App\Events\Hotspot\UserCreated;
use App\Events\Hotspot\UserDeleted;
use App\Models\HotspotUser;
use App\Models\User;
use App\Repositories\HotspotUserRepository;
use App\Services\Contracts\HotspotUserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HotspotUserService implements HotspotUserServiceInterface
{
    public function __construct(
        private HotspotUserRepository $userRepository,
        private RouterService $routerService
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getAllUsers(int $perPage = 20): LengthAwarePaginator
    {
        return $this->userRepository
            ->with(['profile'])
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserById(int $id): HotspotUser
    {
        return $this->userRepository
            ->with(['profile'])
            ->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function createUser(array $data): HotspotUser
    {
        $user = $this->userRepository->create($data);

        // Fire event
        event(new UserCreated($user, Auth::user()));

        Log::info('Hotspot user created', [
            'user_id' => $user->id,
            'username' => $user->username,
            'created_by' => Auth::id(),
        ]);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function createUsers(array $usersData): array
    {
        $createdUsers = [];

        foreach ($usersData as $userData) {
            try {
                $user = $this->createUser($userData);
                $createdUsers[] = $user;
            } catch (\Exception $e) {
                Log::error('Failed to create hotspot user', [
                    'data' => $userData,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $createdUsers;
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(int $id, array $data): HotspotUser
    {
        $user = $this->userRepository->update($id, $data);

        Log::info('Hotspot user updated', [
            'user_id' => $user->id,
            'username' => $user->username,
            'updated_by' => Auth::id(),
        ]);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findOrFail($id);
        $username = $user->username;
        $routerId = $user->router_id;
        $result = $this->userRepository->delete($id);

        if ($result) {
            // Fire event
            event(new UserDeleted($username, Auth::user(), $routerId));

            Log::info('Hotspot user deleted', [
                'username' => $username,
                'deleted_by' => Auth::id(),
            ]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function syncToRouter(int $routerId): bool
    {
        try {
            $users = $this->userRepository->getUsersByRouter($routerId);

            foreach ($users as $user) {
                // Sync logic here - depends on RouterOS API implementation
                $this->routerService->executeCommand($routerId, "/ip/hotspot/user/add");
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to sync users to router', [
                'router_id' => $routerId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveUsersCount(): int
    {
        return $this->userRepository->getActiveUsers()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiredUsers(): array
    {
        return $this->userRepository->getExpiredUsers()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function extendValidity(int $id, string $duration): HotspotUser
    {
        $user = $this->getUserById($id);

        $validUntil = now()->add($duration);

        return $this->updateUser($id, [
            'valid_until' => $validUntil,
        ]);
    }
}
