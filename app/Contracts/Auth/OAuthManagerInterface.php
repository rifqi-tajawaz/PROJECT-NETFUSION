<?php

namespace App\Contracts\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface OAuthManagerInterface
{
    /**
     * Redirect user to OAuth provider
     *
     * @param string $provider
     * @param array $scopes
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(string $provider, array $scopes = []);

    /**
     * Handle OAuth callback
     *
     * @param string $provider
     * @param Request $request
     * @return User|null
     */
    public function callback(string $provider, Request $request): ?User;

    /**
     * Link OAuth account to user
     *
     * @param User $user
     * @param string $provider
     * @param array $providerData
     * @return bool
     */
    public function linkAccount(User $user, string $provider, array $providerData): bool;

    /**
     * Unlink OAuth account from user
     *
     * @param User $user
     * @param string $provider
     * @return bool
     */
    public function unlinkAccount(User $user, string $provider): bool;

    /**
     * Get user's linked OAuth accounts
     *
     * @param User $user
     * @return array
     */
    public function getLinkedAccounts(User $user): array;

    /**
     * Check if user has linked OAuth account
     *
     * @param User $user
     * @param string $provider
     * @return bool
     */
    public function hasLinkedAccount(User $user, string $provider): bool;

    /**
     * Get OAuth provider user data
     *
     * @param string $provider
     * @param string $accessToken
     * @return array|null
     */
    public function getProviderUserData(string $provider, string $accessToken): ?array;

    /**
     * Refresh OAuth token
     *
     * @param User $user
     * @param string $provider
     * @return array|null
     */
    public function refreshToken(User $user, string $provider): ?array;

    /**
     * Revoke OAuth access
     *
     * @param User $user
     * @param string $provider
     * @return bool
     */
    public function revokeAccess(User $user, string $provider): bool;

    /**
     * Validate OAuth token
     *
     * @param string $provider
     * @param string $token
     * @return bool
     */
    public function validateToken(string $provider, string $token): bool;

    /**
     * Get supported OAuth providers
     *
     * @return array
     */
    public function getSupportedProviders(): array;
}
