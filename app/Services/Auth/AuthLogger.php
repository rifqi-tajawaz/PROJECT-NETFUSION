<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AuthLogger
{
    protected function logToDb(string $action, string $description, ?string $userId = null, ?string $ip = null, ?string $userAgent = null): void
    {
        try {
            ActivityLog::create([
                'user_id' => $userId, // Can be null for failed login
                'action' => $action,
                'description' => $description,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        } catch (\Exception $e) {
            Log::channel('auth')->error('Failed to write activity log: ' . $e->getMessage());
        }
    }

    /**
     * Log a successful login attempt.
     *
     * @param string $userId
     * @param string $guard
     * @param string|null $ip
     * @param string|null $userAgent
     */
    public function logLogin(string $userId, string $guard, ?string $ip, ?string $userAgent): void
    {
        // Log to file
        Log::channel('auth')->info('User Login Successful', [
            'user_id' => $userId,
            'guard' => $guard,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Log to DB
        $this->logToDb('LOGIN', "User logged in via {$guard} guard.", $userId, $ip, $userAgent);
    }

    /**
     * Log a failed login attempt.
     *
     * @param string $email
     * @param string|null $ip
     * @param string|null $userAgent
     */
    public function logFailedLogin(string $email, ?string $ip, ?string $userAgent): void
    {
        Log::channel('auth')->warning('User Login Failed', [
            'email' => $email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Log to DB ? Maybe better not to spam DB with failed attempts if no user ID
        // But for security dashboard "recent activity", seeing failed attempts is useful.
        // We don't have user_id, but we can store description 'Failed login for email: ...'
        $this->logToDb('LOGIN_FAILED', "Failed login attempt for: {$email}", null, $ip, $userAgent);
    }

    /**
     * Log a logout event.
     *
     * @param string $userId
     * @param string|null $ip
     * @param string|null $userAgent
     */
    public function logLogout(string $userId, ?string $ip, ?string $userAgent): void
    {
        Log::channel('auth')->info('User Logout', [
            'user_id' => $userId,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toIso8601String(),
        ]);

        $this->logToDb('LOGOUT', "User logged out.", $userId, $ip, $userAgent);
    }

    /**
     * Log a user registration event.
     *
     * @param \App\Models\User $user
     */
    public function logRegistration($user): void
    {
        Log::channel('auth')->info('User Registration', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'timestamp' => now()->toIso8601String(),
        ]);

        $this->logToDb('REGISTER', "New user registered: {$user->email}", $user->id, null, null);
    }
}
