<?php

namespace App\Contracts\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface SecurityManagerInterface
{
    /**
     * Setup 2FA for user
     *
     * @param User $user
     * @return array
     */
    public function setupTwoFactor(User $user): array;

    /**
     * Verify 2FA code
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyTwoFactor(User $user, string $code): bool;

    /**
     * Disable 2FA for user
     *
     * @param User $user
     * @return bool
     */
    public function disableTwoFactor(User $user): bool;

    /**
     * Generate recovery codes
     *
     * @param User $user
     * @return array
     */
    public function generateRecoveryCodes(User $user): array;

    /**
     * Verify recovery code
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyRecoveryCode(User $user, string $code): bool;

    /**
     * Check if user should be verified with 2FA
     *
     * @param User $user
     * @return bool
     */
    public function requiresTwoFactor(User $user): bool;

    /**
     * Log security event
     *
     * @param string $event
     * @param User|null $user
     * @param array $metadata
     * @return void
     */
    public function logSecurityEvent(string $event, ?User $user, array $metadata = []): void;

    /**
     * Check rate limit
     *
     * @param string $key
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @return bool
     */
    public function checkRateLimit(string $key, int $maxAttempts = 5, int $decayMinutes = 1): bool;

    /**
     * Increment rate limit
     *
     * @param string $key
     * @param int $decayMinutes
     * @return void
     */
    public function hitRateLimit(string $key, int $decayMinutes = 1): void;

    /**
     * Get remaining attempts for rate limit
     *
     * @param string $key
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @return int
     */
    public function getRateLimitRemaining(string $key, int $maxAttempts = 5, int $decayMinutes = 1): int;
}
