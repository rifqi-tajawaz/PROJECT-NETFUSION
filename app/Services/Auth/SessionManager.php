<?php

namespace App\Services\Auth;

use App\Contracts\Auth\SessionManagerInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class SessionManager implements SessionManagerInterface
{
    protected Agent $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function createSession(User $user, ?Request $request = null, string $guard = 'web'): string
    {
        $sessionId = Session::getId();

        if ($request && config('session.driver') === 'database') {
            $this->updateSessionData($sessionId, $user, $request);
        }

        return $sessionId;
    }

    public function getActiveSessions(User $user): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return $this->formatSessionData($session);
            })
            ->toArray();

        return $sessions;
    }

    public function getCurrentSession(Request $request): ?array
    {
        $sessionId = $request->session()->getId();

        if (config('session.driver') !== 'database') {
            return null;
        }

        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            return null;
        }

        return $this->formatSessionData($session);
    }

    public function invalidateSession(string $sessionId, User $user): bool
    {
        if (config('session.driver') !== 'database') {
            return false;
        }

        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        return $deleted > 0;
    }

    public function invalidateOtherSessions(User $user, Request $request): int
    {
        if (config('session.driver') !== 'database') {
            return 0;
        }

        $currentSessionId = $request->session()->getId();

        $deleted = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        return $deleted;
    }

    public function invalidateAllSessions(User $user): int
    {
        if (config('session.driver') !== 'database') {
            return 0;
        }

        $deleted = DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        return $deleted;
    }

    public function isValidSession(string $sessionId): bool
    {
        if (config('session.driver') !== 'database') {
            return false;
        }

        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->first();

        return $session && $session->last_activity > (time() - config('session.lifetime') * 60);
    }

    public function updateSessionActivity(string $sessionId): bool
    {
        if (config('session.driver') !== 'database') {
            return false;
        }

        $updated = DB::table('sessions')
            ->where('id', $sessionId)
            ->update(['last_activity' => time()]);

        return $updated > 0;
    }

    public function cleanExpiredSessions(): int
    {
        if (config('session.driver') !== 'database') {
            return 0;
        }

        $expired = time() - (config('session.lifetime') * 60);

        $deleted = DB::table('sessions')
            ->where('last_activity', '<=', $expired)
            ->delete();

        return $deleted;
    }

    public function getSessionDevices(User $user): array
    {
        $sessions = $this->getActiveSessions($user);

        $devices = [];
        foreach ($sessions as $session) {
            $deviceKey = $session['device_type'] . '_' . $session['platform'] . '_' . $session['browser'];

            if (!isset($devices[$deviceKey])) {
                $devices[$deviceKey] = [
                    'device_type' => $session['device_type'],
                    'platform' => $session['platform'],
                    'browser' => $session['browser'],
                    'sessions' => [],
                ];
            }

            $devices[$deviceKey]['sessions'][] = $session;
        }

        return array_values($devices);
    }

    /**
     * Update session data with user and request information
     */
    protected function updateSessionData(string $sessionId, User $user, Request $request): void
    {
        $payload = [
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_info' => $this->getDeviceInfo($request),
        ];

        DB::table('sessions')
            ->where('id', $sessionId)
            ->update($payload);
    }

    /**
     * Format session data for display
     */
    protected function formatSessionData($session): array
    {
        $this->agent->setUserAgent($session->user_agent ?? '');

        return [
            'id' => $session->id,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'device_type' => $this->getDeviceType(),
            'platform' => $this->agent->platform() ?: 'Unknown',
            'browser' => $this->agent->browser() ?: 'Unknown',
            'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            'created_at' => \Carbon\Carbon::createFromTimestamp($session->last_activity - 3600)->toDateTimeString(),
            'is_current' => $session->id === Session::getId(),
        ];
    }

    /**
     * Get device information from request
     */
    protected function getDeviceInfo(Request $request): array
    {
        $this->agent->setUserAgent($request->userAgent());

        return [
            'device_type' => $this->getDeviceType(),
            'platform' => $this->agent->platform(),
            'browser' => $this->agent->browser(),
            'browser_version' => $this->agent->version($this->agent->browser()),
            'is_mobile' => $this->agent->isMobile(),
            'is_tablet' => $this->agent->isTablet(),
            'is_desktop' => $this->agent->isDesktop(),
            'robot' => $this->agent->robot(),
            'languages' => $this->agent->languages(),
        ];
    }

    /**
     * Get device type
     */
    protected function getDeviceType(): string
    {
        if ($this->agent->isTablet()) {
            return 'Tablet';
        }

        if ($this->agent->isMobile()) {
            return 'Mobile';
        }

        if ($this->agent->isDesktop()) {
            return 'Desktop';
        }

        return 'Unknown';
    }

    /**
     * Check for suspicious session activity
     */
    public function detectSuspiciousSessionActivity(User $user, Request $request): bool
    {
        $currentSession = $this->getCurrentSession($request);

        if (!$currentSession) {
            return false;
        }

        // Check for multiple sessions from different locations
        $sessions = $this->getActiveSessions($user);
        $uniqueIps = collect($sessions)->pluck('ip_address')->unique();

        if ($uniqueIps->count() > 3) { // More than 3 different IPs
            return true;
        }

        // Check for sessions from unusual devices
        $deviceTypes = collect($sessions)->pluck('device_type')->unique();
        if ($deviceTypes->count() > 2) { // More than 2 different device types
            return true;
        }

        return false;
    }

    /**
     * Get session statistics
     */
    public function getSessionStatistics(User $user): array
    {
        $sessions = $this->getActiveSessions($user);

        return [
            'total_sessions' => count($sessions),
            'unique_ips' => collect($sessions)->pluck('ip_address')->unique()->count(),
            'unique_devices' => collect($sessions)->pluck('device_type')->unique()->count(),
            'unique_browsers' => collect($sessions)->pluck('browser')->unique()->count(),
            'oldest_session' => collect($sessions)->min('created_at'),
            'newest_session' => collect($sessions)->max('created_at'),
        ];
    }
}
