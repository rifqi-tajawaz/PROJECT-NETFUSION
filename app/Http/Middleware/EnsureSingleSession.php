<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();
            $currentIp = $this->getClientIp($request);
            $currentUserAgent = $request->userAgent();

            // Update last activity timestamp
            $request->session()->put('last_activity', now()->toIso8601String());
            $request->session()->put('last_ip', $currentIp);
            $request->session()->put('user_agent', $currentUserAgent);

            // Admin users are exempt from single session restriction
            if ($user->isAdmin()) {
                // Still track activity for admins
                $this->trackActivity($user, $request);
                return $next($request);
            }

            // Check for session hijacking attempts
            if ($this->detectSessionHijacking($request, $user)) {
                return $this->handleSessionHijacking($request, $user);
            }

            // If the current session ID does not match the stored session ID
            if ($user->current_session_id && $user->current_session_id !== $currentSessionId) {
                return $this->handleSessionConflict($request, $user);
            }

            // Track user activity
            $this->trackActivity($user, $request);
        }

        return $next($request);
    }

    /**
     * Detect potential session hijacking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function detectSessionHijacking(Request $request, $user): bool
    {
        $lastIp = $request->session()->get('last_ip');
        $currentIp = $this->getClientIp($request);

        // If IP changed significantly (not just local network change)
        if ($lastIp && $currentIp && !$this->isSameLocation($lastIp, $currentIp)) {
            // Check if user agent also changed (high probability of hijacking)
            $lastUserAgent = $request->session()->get('user_agent');
            $currentUserAgent = $request->userAgent();

            if ($lastUserAgent !== $currentUserAgent) {
                Log::warning('Potential session hijacking detected', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'last_ip' => $lastIp,
                    'current_ip' => $currentIp,
                    'last_ua' => $lastUserAgent,
                    'current_ua' => $currentUserAgent,
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     * Check if two IPs are from the same location/network.
     *
     * @param  string  $ip1
     * @param  string  $ip2
     * @return bool
     */
    protected function isSameLocation(string $ip1, string $ip2): bool
    {
        // If IPs are identical
        if ($ip1 === $ip2) {
            return true;
        }

        // Check if both are private/local network IPs
        if ($this->isPrivateIp($ip1) && $this->isPrivateIp($ip2)) {
            return true;
        }

        return false;
    }

    /**
     * Check if IP is a private/local network IP.
     *
     * @param  string  $ip
     * @return bool
     */
    protected function isPrivateIp(string $ip): bool
    {
        // Check for private IP ranges
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '127.0.0.0/8',
            '::1',
        ];

        // Simplified check - in production use proper IP range matching
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            $firstOctet = (int)$parts[0];
            $secondOctet = (int)$parts[1];

            // 10.0.0.0/8
            if ($firstOctet === 10) {
                return true;
            }

            // 172.16.0.0/12
            if ($firstOctet === 172 && $secondOctet >= 16 && $secondOctet <= 31) {
                return true;
            }

            // 192.168.0.0/16
            if ($firstOctet === 192 && $secondOctet === 168) {
                return true;
            }

            // 127.0.0.0/8 (localhost)
            if ($firstOctet === 127) {
                return true;
            }
        }

        // IPv6 localhost
        if ($ip === '::1') {
            return true;
        }

        return false;
    }

    /**
     * Handle session hijacking detection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleSessionHijacking(Request $request, $user): Response
    {
        // Log out user for security
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log security event
        Log::critical('Session terminated due to hijacking attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $this->getClientIp($request),
        ]);

        return redirect()->route('login')->withErrors([
            'email' => 'Your session has been terminated for security reasons. Please log in again.',
        ]);
    }

    /**
     * Handle session conflict (multiple logins).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleSessionConflict(Request $request, $user): Response
    {
        // Log out user
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log security event
        Log::info('User logged out due to session conflict', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $this->getClientIp($request),
            'message' => 'Account accessed from another device',
        ]);

        return redirect()->route('login')->withErrors([
            'email' => 'You have been logged out because your account was accessed from another device.',
        ]);
    }

    /**
     * Track user activity.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function trackActivity($user, Request $request): void
    {
        // Update last activity timestamp every 5 minutes
        if (!$request->session()->has('last_tracked_at') ||
            now()->diffInMinutes($request->session()->get('last_tracked_at')) >= 5) {

            $request->session()->put('last_tracked_at', now());

            // You could update database here if needed
            // $user->update(['last_activity_at' => now()]);
        }
    }

    /**
     * Get the client IP address, accounting for proxies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getClientIp(Request $request): string
    {
        $forwardedIp = $request->header('X-Forwarded-For');
        if ($forwardedIp) {
            return explode(',', $forwardedIp)[0];
        }

        $realIp = $request->header('X-Real-IP');
        if ($realIp) {
            return $realIp;
        }

        return $request->ip();
    }
}
