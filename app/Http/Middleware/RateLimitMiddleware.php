<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        // Convert to integers
        $maxAttempts = (int) $maxAttempts;
        $decayMinutes = (int) $decayMinutes;

        $key = $this->resolveRequestSignature($request);

        // Check IP reputation
        if ($this->isSuspiciousIP($request->ip())) {
            $maxAttempts = min($maxAttempts, 5); // Stricter limit for suspicious IPs
            $decayMinutes = max($decayMinutes, 15); // Longer decay
        }

        // Check if already blocked
        if ($this->isBlocked($request->ip())) {
            return $this->buildResponse($request, 'IP address temporarily blocked due to suspicious activity', 429);
        }

        // Increment attempts
        $attempts = Cache::increment($key);

        if ($attempts === 1) {
            Cache::put($key, $attempts, now()->addMinutes($decayMinutes));
        }

        // Log attempts for security monitoring
        $this->logAttempt($request, $attempts, $maxAttempts);

        if ($attempts > $maxAttempts) {
            $this->handleRateLimitExceeded($request, $attempts);
            return $this->buildResponse($request, 'Too many attempts. Please try again later.', 429);
        }

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - $attempts));
        $response->headers->set('X-RateLimit-Reset', now()->addMinutes($decayMinutes)->timestamp);

        return $response;
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $signature = sprintf(
            '%s:%s:%s',
            $request->ip(),
            $request->path(),
            $request->header('User-Agent', '')
        );

        return 'rate_limit:' . md5($signature);
    }

    /**
     * Check if IP is suspicious.
     */
    protected function isSuspiciousIP(string $ip): bool
    {
        $reputation = Cache::get('ip_reputation:' . $ip, ['score' => 100]);

        return $reputation['score'] < 70;
    }

    /**
     * Check if IP is blocked.
     */
    protected function isBlocked(string $ip): bool
    {
        return Cache::has('blocked_ip:' . $ip);
    }

    /**
     * Handle rate limit exceeded.
     */
    protected function handleRateLimitExceeded(Request $request, int $attempts): void
    {
        $ip = $request->ip();

        // Update IP reputation
        $reputation = Cache::get('ip_reputation:' . $ip, ['score' => 100, 'reasons' => []]);
        $reputation['score'] -= ($attempts * 5);
        $reputation['reasons'][] = 'rate_limit_exceeded';

        Cache::put('ip_reputation:' . $ip, $reputation, 3600); // 1 hour

        // Block if very low reputation
        if ($reputation['score'] < 20) {
            Cache::put('blocked_ip:' . $ip, true, 3600); // 1 hour block

            // Log security event
            $this->logSecurityEvent($request, 'ip_blocked', [
                'reason' => 'low_reputation',
                'score' => $reputation['score'],
                'attempts' => $attempts
            ]);
        }
    }

    /**
     * Log attempt for monitoring.
     */
    protected function logAttempt(Request $request, int $attempts, int $maxAttempts): void
    {
        if ($attempts > $maxAttempts * 0.8) { // Log when approaching limit
            $this->logSecurityEvent($request, 'rate_limit_warning', [
                'attempts' => $attempts,
                'limit' => $maxAttempts,
                'percentage' => round(($attempts / $maxAttempts) * 100, 2)
            ]);
        }
    }

    /**
     * Log security event.
     */
    protected function logSecurityEvent(Request $request, string $event, array $data = []): void
    {
        \App\Models\SecurityLog::create([
            'user_id' => auth()->id(),
            'event_type' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => array_merge($data, [
                'path' => $request->path(),
                'method' => $request->method(),
            ])
        ]);
    }

    /**
     * Build error response.
     */
    protected function buildResponse(Request $request, string $message, int $status): \Illuminate\Http\JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $message,
                'retry_after' => Cache::get('rate_limit_retry:' . $request->ip(), 60)
            ], $status);
        }

        return back()
            ->with('error', $message)
            ->withInput($request->only('email'));
    }
}
