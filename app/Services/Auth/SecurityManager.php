<?php

namespace App\Services\Auth;

use App\Contracts\Auth\SecurityManagerInterface;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SecurityManager implements SecurityManagerInterface
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function setupTwoFactor(User $user): array
    {
        $data = $this->twoFactorService->generateSecret($user);

        $this->logSecurityEvent('2FA_SETUP_INITIATED', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return [
            'secret' => $data['secret'],
            'qr_code' => $data['qr_code'],
            'recovery_codes' => $this->getRecoveryCodes($user),
        ];
    }

    public function verifyTwoFactor(User $user, string $code): bool
    {
        $isValid = $this->twoFactorService->verify($user, $code);

        if ($isValid) {
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            $this->logSecurityEvent('2FA_VERIFICATION_SUCCESS', $user, [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } else {
            $this->logSecurityEvent('2FA_VERIFICATION_FAILED', $user, [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'code_provided' => substr($code, 0, 1) . '****', // Log partial code for security
            ]);
        }

        return $isValid;
    }

    public function disableTwoFactor(User $user): bool
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->logSecurityEvent('2FA_DISABLED', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return true;
    }

    public function generateRecoveryCodes(User $user): array
    {
        // Generate recovery codes directly instead of calling protected method
        $recoveryCodes = collect(range(1, 8))->map(function () {
            return \Illuminate\Support\Str::random(10) . '-' . \Illuminate\Support\Str::random(10);
        })->toArray();

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();

        $this->logSecurityEvent('2FA_RECOVERY_CODES_GENERATED', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $recoveryCodes;
    }

    public function verifyRecoveryCode(User $user, string $code): bool
    {
        if (!$user->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (in_array($code, $recoveryCodes)) {
            // Remove used recovery code
            $recoveryCodes = array_diff($recoveryCodes, [$code]);

            $user->forceFill([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ])->save();

            $this->logSecurityEvent('2FA_RECOVERY_CODE_USED', $user, [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return true;
        }

        $this->logSecurityEvent('2FA_RECOVERY_CODE_FAILED', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return false;
    }

    public function requiresTwoFactor(User $user): bool
    {
        return !empty($user->two_factor_secret) && !empty($user->two_factor_confirmed_at);
    }

    public function logSecurityEvent(string $event, ?User $user, array $metadata = []): void
    {
        $logData = array_merge([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'user_id' => $user?->id,
            'ip_address' => $metadata['ip_address'] ?? request()->ip(),
            'user_agent' => $metadata['user_agent'] ?? request()->userAgent(),
        ], $metadata);

        Log::channel('security')->info('Security Event', $logData);

        // Also store in database if needed
        if (config('auth.security.log_to_database', true)) {
            \App\Models\SecurityLog::create([
                'user_id' => $user?->id,
                'event_type' => $event,
                'ip_address' => $logData['ip_address'],
                'user_agent' => $logData['user_agent'],
                'metadata' => json_encode($metadata),
            ]);
        }
    }

    public function checkRateLimit(string $key, int $maxAttempts = 5, int $decayMinutes = 1): bool
    {
        return RateLimiter::tooManyAttempts($key, $maxAttempts);
    }

    public function hitRateLimit(string $key, int $decayMinutes = 1): void
    {
        RateLimiter::hit($key, $decayMinutes * 60);
    }

    public function getRateLimitRemaining(string $key, int $maxAttempts = 5, int $decayMinutes = 1): int
    {
        return RateLimiter::remaining($key, $maxAttempts);
    }

    /**
     * Get recovery codes for user
     */
    protected function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return $this->generateRecoveryCodes($user);
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true);
    }

    /**
     * Check for suspicious activity patterns
     */
    public function detectSuspiciousActivity(User $user, Request $request): bool
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Check for multiple failed login attempts
        $failedAttemptsKey = "auth:failed:{$ip}:{$user->id}";
        if ($this->checkRateLimit($failedAttemptsKey, 10, 30)) { // 10 attempts in 30 minutes
            $this->logSecurityEvent('SUSPICIOUS_MULTIPLE_FAILED_ATTEMPTS', $user, [
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'attempts_count' => RateLimiter::attempts($failedAttemptsKey),
            ]);
            return true;
        }

        // Check for login from unusual location (simplified)
        $lastLoginIp = Cache::get("user:last_login_ip:{$user->id}");
        if ($lastLoginIp && $lastLoginIp !== $ip) {
            $this->logSecurityEvent('LOGIN_FROM_NEW_LOCATION', $user, [
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'previous_ip' => $lastLoginIp,
            ]);
        }

        // Update last login IP
        Cache::put("user:last_login_ip:{$user->id}", $ip, now()->addDays(30));

        return false;
    }

    /**
     * Block suspicious IP temporarily
     */
    public function blockSuspiciousIp(string $ip, int $minutes = 60): void
    {
        Cache::put("auth:blocked_ip:{$ip}", true, now()->addMinutes($minutes));

        $this->logSecurityEvent('IP_BLOCKED', null, [
            'ip_address' => $ip,
            'block_duration_minutes' => $minutes,
        ]);
    }

    /**
     * Check if IP is blocked
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::has("auth:blocked_ip:{$ip}");
    }

    /**
     * Get security score for user (0-100)
     */
    public function getSecurityScore(User $user): int
    {
        $score = 50; // Base score

        // 2FA enabled
        if ($this->requiresTwoFactor($user)) {
            $score += 20;
        }

        // Email verified
        if ($user->hasVerifiedEmail()) {
            $score += 10;
        }

        // Recent password change (within 90 days)
        if ($user->password_changed_at && $user->password_changed_at->diffInDays(now()) < 90) {
            $score += 10;
        }

        // No recent security events
        $recentSecurityEvents = \App\Models\SecurityLog::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($recentSecurityEvents === 0) {
            $score += 10;
        }

        return min(100, $score);
    }
}
