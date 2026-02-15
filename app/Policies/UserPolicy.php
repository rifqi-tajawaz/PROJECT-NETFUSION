<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * User Policy
 *
 * Defines authorization rules for user-related actions.
 * Prevents unauthorized access and insecure direct object references.
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view a specific user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being viewed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $target): bool
    {
        // Users can view their own profile
        // Admins can view any profile
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        // Only admins can manually create users
        // (Registration is handled separately without authentication)
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update a specific user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $target): bool
    {
        // Users can update their own profile
        // Admins can update any profile
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete a specific user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being deleted
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $target): bool
    {
        // Users can delete their own account
        // Admins can delete any account (except themselves)
        if ($user->id === $target->id) {
            return true;
        }

        if ($user->isAdmin()) {
            // Admins cannot delete themselves through this policy
            return $user->id !== $target->id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete a specific user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $target): bool
    {
        // Only admins can force delete
        // Admins cannot force delete themselves
        return $user->isAdmin() && $user->id !== $target->id;
    }

    /**
     * Determine whether the user can restore a specific user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $target): bool
    {
        // Only admins can restore deleted users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the email address.
     *
     * Email changes require additional verification to prevent account takeover.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateEmail(User $user, User $target): bool
    {
        // Users can update their own email (requires verification)
        // Admins can update any email
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the password.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updatePassword(User $user, User $target): bool
    {
        // Users can only update their own password
        // Admins should use password reset instead of directly changing passwords
        return $user->id === $target->id;
    }

    /**
     * Determine whether the user can update the role.
     *
     * Role changes are sensitive operations that should be restricted.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateRole(User $user, User $target): bool
    {
        // Only admins can change roles
        // Admins cannot change their own role (to prevent accidentally removing admin access)
        if (!$user->isAdmin()) {
            return false;
        }

        // Prevent admins from changing their own role
        if ($user->id === $target->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can manage two-factor authentication.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageTwoFactor(User $user, User $target): bool
    {
        // Users can manage their own 2FA settings
        // Admins can manage 2FA for any user (useful for account recovery)
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the two-factor recovery codes.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being viewed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewRecoveryCodes(User $user, User $target): bool
    {
        // Only the user themselves can view their own recovery codes
        // Even admins should not be able to view recovery codes
        return $user->id === $target->id;
    }

    /**
     * Determine whether the user can impersonate another user.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user to impersonate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function impersonate(User $user, User $target): bool
    {
        // Only admins can impersonate other users
        if (!$user->isAdmin()) {
            return false;
        }

        // Cannot impersonate yourself
        if ($user->id === $target->id) {
            return false;
        }

        // Cannot impersonate other admins (security measure)
        if ($target->isAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can view sensitive information.
     *
     * Sensitive information includes: IP logs, login history, session data, etc.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being viewed
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewSensitiveInfo(User $user, User $target): bool
    {
        // Users can view their own sensitive information
        // Admins can view sensitive information for any user
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can manage API tokens.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageApiTokens(User $user, User $target): bool
    {
        // Users can manage their own API tokens
        // Admins can manage API tokens for any user
        return $user->id === $target->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can manage permissions.
     *
     * Direct permission management is highly sensitive.
     *
     * @param  \App\Models\User  $user  The authenticated user
     * @param  \App\Models\User  $target  The user being updated
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function managePermissions(User $user, User $target): bool
    {
        // Only admins can manage permissions
        if (!$user->isAdmin()) {
            return false;
        }

        // Prevent admins from removing their own admin permissions
        if ($user->id === $target->id) {
            return false;
        }

        return true;
    }
}
