<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Models\SecurityLog;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class DeviceFingerprintService
{
    protected Agent $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Generate device fingerprint from request.
     */
    public function generateFingerprint(Request $request): array
    {
        $this->agent->setUserAgent($request->userAgent());

        $fingerprintData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => $this->agent->browser() . ' ' . $this->agent->version($this->agent->browser()),
            'platform' => $this->agent->platform() . ' ' . $this->agent->version($this->agent->platform()),
            'device' => $this->getDeviceType(),
            'languages' => $request->header('Accept-Language', ''),
            'timezone' => $request->header('Timezone', '') ?: $request->get('timezone', ''),
            'screen_resolution' => $request->header('X-Screen-Resolution', ''),
            'color_depth' => $request->header('X-Color-Depth', ''),
            'plugins' => $request->header('X-Plugins', ''),
            'fonts' => $request->header('X-Fonts', ''),
            'canvas' => $request->header('X-Canvas', ''),
            'webgl' => $request->header('X-WebGL', ''),
        ];

        $fingerprint = hash('sha256', json_encode($fingerprintData));

        return [
            'fingerprint' => $fingerprint,
            'data' => $fingerprintData,
            'is_mobile' => $this->agent->isMobile(),
            'is_tablet' => $this->agent->isTablet(),
            'is_robot' => $this->agent->isRobot(),
        ];
    }

    /**
     * Get device type.
     */
    protected function getDeviceType(): string
    {
        if ($this->agent->isMobile()) {
            return 'mobile';
        }

        if ($this->agent->isTablet()) {
            return 'tablet';
        }

        if ($this->agent->isRobot()) {
            return 'bot';
        }

        return 'desktop';
    }

    /**
     * Register or update device for user.
     */
    public function registerDevice(Request $request, int $userId): UserDevice
    {
        $fingerprint = $this->generateFingerprint($request);
        $now = now();

        // Check if device already exists
        $existingDevice = UserDevice::where('user_id', $userId)
            ->where('fingerprint', $fingerprint['fingerprint'])
            ->first();

        if ($existingDevice) {
            // Update existing device
            $existingDevice->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_seen_at' => $now,
            ]);

            return $existingDevice;
        }

        // Create new device
        $device = UserDevice::create([
            'user_id' => $userId,
            'fingerprint' => $fingerprint['fingerprint'],
            'device_name' => $this->generateDeviceName($fingerprint['data']),
            'device_type' => $fingerprint['data']['device'],
            'platform' => $fingerprint['data']['platform'],
            'browser' => $fingerprint['data']['browser'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'first_seen_at' => $now,
            'last_seen_at' => $now,
            'is_trusted' => false,
            'device_data' => $fingerprint['data'],
        ]);

        // Send security alert for new device
        $this->handleNewDevice($request, $userId, $device);

        return $device;
    }

    /**
     * Handle new device detection.
     */
    protected function handleNewDevice(Request $request, int $userId, UserDevice $device): void
    {
        try {
            // Log security event
            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'new_device_login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => [
                    'device_id' => $device->id,
                    'device_name' => $device->device_name,
                    'device_type' => $device->device_type,
                    'location' => $this->getLocationFromIP($request->ip()),
                ]
            ]);

            // Send email notification
            $user = \App\Models\User::find($userId);
            if ($user) {
                \Mail::to($user->email)->send(new \App\Mail\NewDeviceAlert($device, $request));
            }
        } catch (\Throwable $e) {
            \Log::error("Failed to handle new device alert: " . $e->getMessage());
        }
    }

    /**
     * Generate device name.
     */
    protected function generateDeviceName(array $data): string
    {
        $parts = [];

        if (!empty($data['platform']) && $data['platform'] !== 'Unknown') {
            $parts[] = $data['platform'];
        }

        if (!empty($data['browser']) && $data['browser'] !== 'Unknown') {
            $parts[] = $data['browser'];
        }

        if (!empty($data['device']) && $data['device'] !== 'desktop') {
            $parts[] = ucfirst($data['device']);
        }

        return empty($parts) ? 'Unknown Device' : implode(' - ', $parts);
    }

    /**
     * Get location from IP.
     */
    public function getLocationFromIP(string $ip): array
    {
        try {
            $response = Cache::remember('ip_location:' . $ip, 3600, function () use ($ip) {
                $client = new \GuzzleHttp\Client();
                $response = $client->get("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,timezone");
                return json_decode($response->getBody(), true);
            });

            if ($response['status'] === 'success') {
                return [
                    'country' => $response['country'],
                    'region' => $response['regionName'],
                    'city' => $response['city'],
                    'timezone' => $response['timezone'],
                ];
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
     * Detect suspicious login patterns.
     */
    public function detectSuspiciousActivity(Request $request, int $userId): array
    {
        $suspicious = [];
        $warnings = [];

        // Check for multiple locations
        $recentDevices = UserDevice::where('user_id', $userId)
            ->where('last_seen_at', '>=', now()->subHours(24))
            ->distinct('ip_address')
            ->count();

        if ($recentDevices > 2) {
            $suspicious[] = 'multiple_locations';
        }

        // Check for impossible travel
        $lastDevice = UserDevice::where('user_id', $userId)
            ->where('ip_address', '!=', $request->ip())
            ->orderBy('last_seen_at', 'desc')
            ->first();

        if ($lastDevice && $this->isImpossibleTravel($lastDevice->ip_address, $request->ip())) {
            $suspicious[] = 'impossible_travel';
        }

        // Check for new device
        $fingerprint = $this->generateFingerprint($request);
        $existingDevice = UserDevice::where('user_id', $userId)
            ->where('fingerprint', $fingerprint['fingerprint'])
            ->first();

        if (!$existingDevice) {
            $warnings[] = 'new_device';
        }

        return [
            'suspicious' => $suspicious,
            'warnings' => $warnings,
            'risk_score' => $this->calculateRiskScore($suspicious, $warnings)
        ];
    }

    /**
     * Check for impossible travel.
     */
    protected function isImpossibleTravel(string $fromIP, string $toIP): bool
    {
        try {
            $fromLocation = $this->getLocationFromIP($fromIP);
            $toLocation = $this->getLocationFromIP($toIP);

            if ($fromLocation['city'] === $toLocation['city']) {
                return false;
            }

            // Simple check - if cities are in different countries, it's suspicious
            return $fromLocation['country'] !== $toLocation['country'];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Calculate risk score.
     */
    protected function calculateRiskScore(array $suspicious, array $warnings): int
    {
        $score = 0;

        // Suspicious activities have higher weight
        $score += count($suspicious) * 30;
        $score += count($warnings) * 10;

        return min(100, $score);
    }

    /**
     * Check if device is trusted.
     */
    public function isTrustedDevice(Request $request, int $userId): bool
    {
        $fingerprint = $this->generateFingerprint($request);

        $device = UserDevice::where('user_id', $userId)
            ->where('fingerprint', $fingerprint['fingerprint'])
            ->where('is_trusted', true)
            ->first();

        return !is_null($device);
    }

    /**
     * Mark device as trusted.
     */
    public function trustDevice(string $fingerprint, int $userId): bool
    {
        $device = UserDevice::where('user_id', $userId)
            ->where('fingerprint', $fingerprint)
            ->first();

        if ($device) {
            $device->update(['is_trusted' => true]);

            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'device_trusted',
                'details' => ['device_id' => $device->id]
            ]);

            return true;
        }

        return false;
    }

    /**
     * Revoke device access.
     */
    public function revokeDevice(string $fingerprint, int $userId): bool
    {
        $device = UserDevice::where('user_id', $userId)
            ->where('fingerprint', $fingerprint)
            ->first();

        if ($device) {
            // Invalidate all sessions for this device
            $this->invalidateDeviceSessions($device);

            // Delete device
            $device->delete();

            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => 'device_revoked',
                'details' => ['device_name' => $device->device_name]
            ]);

            return true;
        }

        return false;
    }

    /**
     * Invalidate all sessions for a device.
     */
    protected function invalidateDeviceSessions(UserDevice $device): void
    {
        $sessions = \DB::table('sessions')
            ->where('user_id', $device->user_id)
            ->where('ip_address', $device->ip_address)
            ->where('user_agent', 'like', '%' . substr(data_get($device->device_data, 'user_agent'), 0, 50) . '%')
            ->get();

        foreach ($sessions as $session) {
            \DB::table('sessions')->where('id', $session->id)->delete();
        }
    }
}
