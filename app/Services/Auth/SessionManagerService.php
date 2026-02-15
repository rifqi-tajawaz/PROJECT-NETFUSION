<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDevice;
use App\Models\SecurityLog;

class SessionManagerService
{
    /**
     * Get all active sessions for a user.
     */
    public function getUserSessions(int $userId): array
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $payload = json_decode($session->payload, true);

                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => $session->last_activity,
                    'last_activity_human' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'device' => $this->parseDeviceFromSession($session),
                    'is_current' => $session->id === request()->session()->getId(),
                    'is_active' => $session->last_activity > (time() - config('session.lifetime') * 60),
                ];
            })
            ->toArray();

        return $sessions;
    }

    /**
     * Parse device information from session.
     */
    protected function parseDeviceFromSession($session): array
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($session->user_agent);

        return [
            'browser' => $agent->browser() . ' ' . $agent->version($agent->browser()),
            'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
        ];
    }

    /**
     * Invalidate a specific session.
     */
    public function invalidateSession(string $sessionId, int $userId): bool
    {
        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->first();

        if ($session) {
            // Log session termination
            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'session_terminated',
                'details' => [
                    'session_id' => $sessionId,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                ]
            ]);

            return DB::table('sessions')->where('id', $sessionId)->delete() > 0;
        }

        return false;
    }

    /**
     * Invalidate all sessions except current.
     */
    public function invalidateOtherSessions(int $userId): int
    {
        $currentSessionId = request()->session()->getId();

        $count = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        if ($count > 0) {
            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'all_sessions_terminated',
                'details' => [
                    'current_session_preserved' => true,
                    'terminated_count' => $count,
                ]
            ]);
        }

        return $count;
    }

    /**
     * Invalidate all sessions for a user.
     */
    public function invalidateAllSessions(int $userId): int
    {
        $count = DB::table('sessions')
            ->where('user_id', $userId)
            ->delete();

        if ($count > 0) {
            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'all_sessions_terminated',
                'details' => [
                    'current_session_preserved' => false,
                    'terminated_count' => $count,
                ]
            ]);
        }

        return $count;
    }

    /**
     * Check if user has multiple active sessions.
     */
    public function hasMultipleSessions(int $userId): bool
    {
        return DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>', time() - config('session.lifetime') * 60)
            ->count() > 1;
    }

    /**
     * Get session count by device type.
     */
    public function getSessionStatsByDevice(int $userId): array
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>', time() - config('session.lifetime') * 60)
            ->get();

        $stats = [
            'total' => $sessions->count(),
            'desktop' => 0,
            'mobile' => 0,
            'tablet' => 0,
        ];

        foreach ($sessions as $session) {
            $device = $this->parseDeviceFromSession($session);
            $stats[$device['device_type']]++;
        }

        return $stats;
    }

    /**
     * Get login sessions for last N days.
     */
    public function getLoginHistory(int $userId, int $days = 30): array
    {
        $logs = SecurityLog::where('user_id', $userId)
            ->whereIn('event_type', ['login_success', 'logout'])
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'event_type' => $log->event_type,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at,
                    'created_at_human' => $log->created_at->diffForHumans(),
                    'location' => $this->getLocationFromIP($log->ip_address),
                ];
            })
            ->toArray();

        return $logs;
    }

    /**
     * Get location from IP.
     */
    protected function getLocationFromIP(string $ip): array
    {
        try {
            $cached = \Cache::get('ip_location:' . $ip);
            if ($cached) {
                return $cached;
            }

            $client = new \GuzzleHttp\Client();
            $response = $client->get("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,timezone,lat,lon");
            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                $location = [
                    'country' => $data['country'],
                    'region' => $data['regionName'],
                    'city' => $data['city'],
                    'timezone' => $data['timezone'],
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                ];

                \Cache::put('ip_location:' . $ip, $location, 3600);
                return $location;
            }
        } catch (\Exception $e) {
            // Failed to get location
        }

        return [
            'country' => 'Unknown',
            'region' => 'Unknown',
            'city' => 'Unknown',
            'timezone' => 'Unknown',
        ];
    }

    /**
     * Detect concurrent logins from different locations.
     */
    public function detectConcurrentLogins(int $userId): array
    {
        $activeSessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>', time() - config('session.lifetime') * 60)
            ->get();

        if ($activeSessions->count() <= 1) {
            return [];
        }

        $locations = [];
        $suspicious = [];

        foreach ($activeSessions as $session) {
            $location = $this->getLocationFromIP($session->ip_address);
            $locationKey = $location['country'] . '|' . $location['city'];

            if (!isset($locations[$locationKey])) {
                $locations[$locationKey] = [];
            }

            $locations[$locationKey][] = [
                'ip' => $session->ip_address,
                'last_activity' => $session->last_activity,
                'location' => $location,
            ];
        }

        // Check for multiple locations
        if (count($locations) > 1) {
            $suspicious[] = 'multiple_locations';
        }

        // Check for impossible travel
        $sessionArray = $activeSessions->toArray();
        for ($i = 0; $i < count($sessionArray); $i++) {
            for ($j = $i + 1; $j < count($sessionArray); $j++) {
                if ($this->isImpossibleTravel($sessionArray[$i]->ip_address, $sessionArray[$j]->ip_address)) {
                    $suspicious[] = 'impossible_travel';
                    break 2;
                }
            }
        }

        return [
            'locations' => array_values($locations),
            'suspicious' => $suspicious,
            'session_count' => $activeSessions->count(),
        ];
    }

    /**
     * Check for impossible travel between IPs.
     */
    protected function isImpossibleTravel(string $ip1, string $ip2): bool
    {
        try {
            $loc1 = $this->getLocationFromIP($ip1);
            $loc2 = $this->getLocationFromIP($ip2);

            // If both locations have coordinates
            if (isset($loc1['lat'], $loc1['lon'], $loc2['lat'], $loc2['lon'])) {
                $distance = $this->calculateDistance($loc1['lat'], $loc1['lon'], $loc2['lat'], $loc2['lon']);

                // If distance is more than 1000km, it's suspicious
                return $distance > 1000;
            }
        } catch (\Exception $e) {
            // Failed to calculate distance
        }

        return false;
    }

    /**
     * Calculate distance between two coordinates in km.
     */
    protected function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Extend session lifetime.
     */
    public function extendSession(string $sessionId, int $minutes = 60): bool
    {
        return DB::table('sessions')
            ->where('id', $sessionId)
            ->update(['last_activity' => time() + ($minutes * 60)]) > 0;
    }
}
