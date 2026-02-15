<?php

namespace App\Contracts\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface SessionManagerInterface
{
    /**
     * Create new session for user
     *
     * @param User $user
     * @param Request|null $request
     * @param string $guard
     * @return string
     */
    public function createSession(User $user, ?Request $request = null, string $guard = 'web'): string;

    /**
     * Get active sessions for user
     *
     * @param User $user
     * @return array
     */
    public function getActiveSessions(User $user): array;

    /**
     * Get current session info
     *
     * @param Request $request
     * @return array|null
     */
    public function getCurrentSession(Request $request): ?array;

    /**
     * Invalidate session
     *
     * @param string $sessionId
     * @param User $user
     * @return bool
     */
    public function invalidateSession(string $sessionId, User $user): bool;

    /**
     * Invalidate all sessions except current
     *
     * @param User $user
     * @param Request $request
     * @return int
     */
    public function invalidateOtherSessions(User $user, Request $request): int;

    /**
     * Invalidate all sessions for user
     *
     * @param User $user
     * @return int
     */
    public function invalidateAllSessions(User $user): int;

    /**
     * Check if session is valid
     *
     * @param string $sessionId
     * @return bool
     */
    public function isValidSession(string $sessionId): bool;

    /**
     * Update session activity
     *
     * @param string $sessionId
     * @return bool
     */
    public function updateSessionActivity(string $sessionId): bool;

    /**
     * Clean expired sessions
     *
     * @return int
     */
    public function cleanExpiredSessions(): int;

    /**
     * Get session devices info
     *
     * @param User $user
     * @return array
     */
    public function getSessionDevices(User $user): array;
}
