<?php

namespace App\Services\Auth;

use App\Mail\AccountLocked;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Login Attempt Service
 *
 * Manages login attempt tracking and account lockout functionality.
 * Prevents brute force attacks through IP-based and email-based rate limiting.
 */
class LoginAttemptService
{
    /**
     * Maximum allowed login attempts before temporary lockout.
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * Decay time in minutes for temporary lockout.
     */
    private const DECAY_MINUTES = 15;

    /**
     * Maximum attempts before permanent blacklist.
     */
    private const MAX_ATTEMPTS_PERMANENT = 20;

    /**
     * Record a failed login attempt.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return void
     */
    public function recordFailedAttempt(string $email, string $ipAddress): void
    {
        $key = $this->getAttemptKey($email, $ipAddress);
        $attempts = Cache::get($key, 0) + 1;

        Cache::put($key, $attempts, now()->addMinutes(self::DECAY_MINUTES));

        // Log security event
        Log::warning('Failed login attempt', [
            'email' => $email,
            'ip' => $ipAddress,
            'attempts' => $attempts,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Check if should be permanently blacklisted
        if ($attempts >= self::MAX_ATTEMPTS_PERMANENT) {
            $this->blacklistPermanently($email, $ipAddress);
        }
    }

    /**
     * Clear login attempts after successful login.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return void
     */
    public function clearAttempts(string $email, string $ipAddress): void
    {
        $key = $this->getAttemptKey($email, $ipAddress);
        Cache::forget($key);

        // Log successful login
        Log::info('Login successful - attempts cleared', [
            'email' => $email,
            'ip' => $ipAddress,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Check if the email/IP combination is locked out.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return bool
     */
    public function isLockedOut(string $email, string $ipAddress): bool
    {
        // Check permanent blacklist first
        if ($this->isPermanentlyBlacklisted($email, $ipAddress)) {
            return true;
        }

        // Check temporary lockout
        $key = $this->getAttemptKey($email, $ipAddress);
        $attempts = Cache::get($key, 0);

        return $attempts >= self::MAX_ATTEMPTS;
    }

    /**
     * Get remaining lockout time in minutes.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return int|null  Returns null if not locked out
     */
    public function getLockoutTimeRemaining(string $email, string $ipAddress): ?int
    {
        if ($this->isPermanentlyBlacklisted($email, $ipAddress)) {
            return -1; // -1 indicates permanent lockout
        }

        $key = $this->getAttemptKey($email, $ipAddress);
        $attempts = Cache::get($key, 0);

        if ($attempts < self::MAX_ATTEMPTS) {
            return null;
        }

        $ttl = Cache::get($key);
        if ($ttl === null) {
            return null;
        }

        // Return remaining time in minutes
        return ceil(self::DECAY_MINUTES);
    }

    /**
     * Get the current number of failed attempts.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return int
     */
    public function getAttemptsCount(string $email, string $ipAddress): int
    {
        $key = $this->getAttemptKey($email, $ipAddress);
        return Cache::get($key, 0);
    }

    /**
     * Get attempts remaining before lockout.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return int
     */
    public function getAttemptsRemaining(string $email, string $ipAddress): int
    {
        $attempts = $this->getAttemptsCount($email, $ipAddress);
        return max(0, self::MAX_ATTEMPTS - $attempts);
    }

    /**
     * Permanently blacklist an email/IP combination.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return void
     */
    protected function blacklistPermanently(string $email, string $ipAddress): void
    {
        $key = $this->getBlacklistKey($email, $ipAddress);
        Cache::put($key, true, now()->addYears(10)); // Effectively permanent

        Log::critical('Permanently blacklisted due to excessive failed attempts', [
            'email' => $email,
            'ip' => $ipAddress,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Check if email/IP is permanently blacklisted.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return bool
     */
    protected function isPermanentlyBlacklisted(string $email, string $ipAddress): bool
    {
        $key = $this->getBlacklistKey($email, $ipAddress);
        return Cache::has($key);
    }

    /**
     * Remove from permanent blacklist (admin action).
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return bool
     */
    public function removeFromBlacklist(string $email, string $ipAddress): bool
    {
        $key = $this->getBlacklistKey($email, $ipAddress);
        $removed = Cache::forget($key);

        if ($removed) {
            Log::info('Removed from blacklist', [
                'email' => $email,
                'ip' => $ipAddress,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return $removed;
    }

    /**
     * Get all blacklisted entries (admin use).
     *
     * Note: This is a simplified version. In production, you'd want
     * to store blacklists in a database with proper indexing.
     *
     * @return array
     */
    public function getBlacklistedEntries(): array
    {
        // In production, implement database query instead
        return Cache::get('login_blacklist_log', []);
    }

    /**
     * Send lock notification to user.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return void
     */
    public function sendLockNotification(string $email, string $ipAddress): void
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            try {
                Mail::to($user->email)->send(new AccountLocked($user, $ipAddress));

                Log::info('Account lock notification sent', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $ipAddress,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send account lock notification', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Get the cache key for login attempts.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return string
     */
    protected function getAttemptKey(string $email, string $ipAddress): string
    {
        return 'login_attempt:' . md5($email . '|' . $ipAddress);
    }

    /**
     * Get the cache key for blacklist.
     *
     * @param  string  $email
     * @param  string  $ipAddress
     * @return string
     */
    protected function getBlacklistKey(string $email, string $ipAddress): string
    {
        return 'login_blacklist:' . md5($email . '|' . $ipAddress);
    }
}
