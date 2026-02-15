<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by OAuth provider and provider ID.
     *
     * @param string $provider
     * @param string $providerId
     * @return User|null
     */
    public function findByProvider(string $provider, string $providerId): ?User
    {
        return User::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update user information.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Get user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get all users.
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = User::query();

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['provider'])) {
            $query->where('provider', $filters['provider']);
        }

        if (isset($filters['verified'])) {
            $query->whereNotNull('email_verified_at');
        }

        if (isset($filters['active'])) {
            $query->where('is_active', $filters['active']);
        }

        return $query->latest()->get();
    }

    /**
     * Get users with specific roles.
     *
     * @param array $roles
     * @return Collection
     */
    public function getByRoles(array $roles): Collection
    {
        return User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
    }

    /**
     * Get users with specific permissions.
     *
     * @param array $permissions
     * @return Collection
     */
    public function getByPermissions(array $permissions): Collection
    {
        return User::whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('name', $permissions);
        })->get();
    }

    /**
     * Get users created within date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return User::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    /**
     * Get users who haven't verified their email.
     *
     * @return Collection
     */
    public function getUnverifiedUsers(): Collection
    {
        return User::whereNull('email_verified_at')->get();
    }

    /**
     * Get users with 2FA enabled.
     *
     * @return Collection
     */
    public function getTwoFactorEnabledUsers(): Collection
    {
        return User::whereNotNull('two_factor_secret')
            ->whereNotNull('two_factor_confirmed_at')
            ->get();
    }

    /**
     * Get users with OAuth accounts.
     *
     * @param string|null $provider
     * @return Collection
     */
    public function getOAuthUsers(?string $provider = null): Collection
    {
        $query = User::whereNotNull('provider')
            ->whereNotNull('provider_id');

        if ($provider) {
            $query->where('provider', $provider);
        }

        return $query->get();
    }

    /**
     * Get user statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $activeUsers = User::where('is_active', true)->count();
        $twoFactorUsers = User::whereNotNull('two_factor_secret')
            ->whereNotNull('two_factor_confirmed_at')
            ->count();
        $oauthUsers = User::whereNotNull('provider')
            ->whereNotNull('provider_id')
            ->count();
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();

        return [
            'total_users' => $totalUsers,
            'verified_users' => $verifiedUsers,
            'unverified_users' => $totalUsers - $verifiedUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $totalUsers - $activeUsers,
            'two_factor_users' => $twoFactorUsers,
            'oauth_users' => $oauthUsers,
            'recent_users' => $recentUsers,
        ];
    }

    /**
     * Check if user has specific role.
     *
     * @param User $user
     * @param string $role
     * @return bool
     */
    public function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    /**
     * Check if user has specific permission.
     *
     * @param User $user
     * @param string $permission
     * @return bool
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return $user->hasPermissionTo($permission);
    }

    /**
     * Assign role to user.
     *
     * @param User $user
     * @param string $role
     * @return bool
     */
    public function assignRole(User $user, string $role): bool
    {
        return $user->assignRole($role);
    }

    /**
     * Remove role from user.
     *
     * @param User $user
     * @param string $role
     * @return bool
     */
    public function removeRole(User $user, string $role): bool
    {
        return $user->removeRole($role);
    }

    /**
     * Sync user roles.
     *
     * @param User $user
     * @param array $roles
     * @return array
     */
    public function syncRoles(User $user, array $roles): array
    {
        return $user->syncRoles($roles);
    }

    /**
     * Give permission to user.
     *
     * @param User $user
     * @param string $permission
     * @return bool
     */
    public function givePermission(User $user, string $permission): bool
    {
        return $user->givePermissionTo($permission);
    }

    /**
     * Revoke permission from user.
     *
     * @param User $user
     * @param string $permission
     * @return bool
     */
    public function revokePermission(User $user, string $permission): bool
    {
        return $user->revokePermissionTo($permission);
    }

    /**
     * Sync user permissions.
     *
     * @param User $user
     * @param array $permissions
     * @return array
     */
    public function syncPermissions(User $user, array $permissions): array
    {
        return $user->syncPermissions($permissions);
    }

    /**
     * Get user's all roles.
     *
     * @param User $user
     * @return array
     */
    public function getUserRoles(User $user): array
    {
        return $user->roles->pluck('name')->toArray();
    }

    /**
     * Get user's all permissions.
     *
     * @param User $user
     * @return array
     */
    public function getUserPermissions(User $user): array
    {
        return $user->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Activate user account.
     *
     * @param User $user
     * @return bool
     */
    public function activate(User $user): bool
    {
        return $user->update(['is_active' => true]);
    }

    /**
     * Deactivate user account.
     *
     * @param User $user
     * @return bool
     */
    public function deactivate(User $user): bool
    {
        return $user->update(['is_active' => false]);
    }

    /**
     * Lock user account.
     *
     * @param User $user
     * @param string|null $reason
     * @return bool
     */
    public function lock(User $user, ?string $reason = null): bool
    {
        return $user->update([
            'is_locked' => true,
            'lock_reason' => $reason,
            'locked_at' => now(),
        ]);
    }

    /**
     * Unlock user account.
     *
     * @param User $user
     * @return bool
     */
    public function unlock(User $user): bool
    {
        return $user->update([
            'is_locked' => false,
            'lock_reason' => null,
            'locked_at' => null,
        ]);
    }

    /**
     * Check if user is locked.
     *
     * @param User $user
     * @return bool
     */
    public function isLocked(User $user): bool
    {
        return $user->is_locked ?? false;
    }

    /**
     * Check if user is active.
     *
     * @param User $user
     * @return bool
     */
    public function isActive(User $user): bool
    {
        return $user->is_active ?? true;
    }
}
