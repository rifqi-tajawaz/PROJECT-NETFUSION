<?php

namespace App\Contracts\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

interface AuthenticationManagerInterface
{
    /**
     * Authenticate user with credentials
     *
     * @param array $credentials
     * @param string $provider
     * @param Request|null $request
     * @return Authenticatable|null
     */
    public function authenticate(array $credentials, string $provider = 'local', ?Request $request = null): ?Authenticatable;

    /**
     * Register new user
     *
     * @param array $data
     * @param string $provider
     * @return Authenticatable
     */
    public function register(array $data, string $provider = 'local'): Authenticatable;

    /**
     * Logout user
     *
     * @param Request|null $request
     * @param string $guard
     * @return bool
     */
    public function logout(?Request $request = null, string $guard = 'web'): bool;

    /**
     * Generate JWT token for user
     *
     * @param Authenticatable $user
     * @param array $abilities
     * @return array
     */
    public function generateToken(Authenticatable $user, array $abilities = ['*']): array;

    /**
     * Validate JWT token
     *
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token): bool;

    /**
     * Refresh JWT token
     *
     * @param string $token
     * @return array|null
     */
    public function refreshToken(string $token): ?array;

    /**
     * Revoke token
     *
     * @param string $token
     * @return bool
     */
    public function revokeToken(string $token): bool;
}
