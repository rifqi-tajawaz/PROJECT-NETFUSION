<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Services\Auth\DeviceFingerprintService;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceManagementController extends Controller
{
    protected DeviceFingerprintService $deviceService;

    public function __construct(DeviceFingerprintService $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->middleware('auth');
    }

    /**
     * Display device management page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $devices = $user->devices()
            ->orderBy('last_seen_at', 'desc')
            ->get();

        // Get current session device
        $currentFingerprint = $this->deviceService->generateFingerprint($request);
        $currentDevice = $devices->firstWhere('fingerprint', $currentFingerprint['fingerprint']);

        return view('security.devices.index', compact(
            'devices',
            'currentDevice',
            'currentFingerprint'
        ));
    }

    /**
     * Trust a device.
     */
    public function trust(Request $request, $deviceId)
    {
        $device = UserDevice::where('id', $deviceId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $device->trust();

        return back()->with('success', 'Device has been marked as trusted.');
    }

    /**
     * Revoke device access.
     */
    public function revoke(Request $request, $deviceId)
    {
        $device = UserDevice::where('id', $deviceId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if this is the current device
        $currentFingerprint = $this->deviceService->generateFingerprint($request)['fingerprint'];
        if ($device->fingerprint === $currentFingerprint) {
            return back()->with('error', 'You cannot revoke access for the current device.');
        }

        $this->deviceService->revokeDevice($device->fingerprint, Auth::id());

        return back()->with('success', 'Device access has been revoked.');
    }

    /**
     * Revoke all devices except current.
     */
    public function revokeAll(Request $request)
    {
        $user = Auth::user();
        $currentFingerprint = $this->deviceService->generateFingerprint($request)['fingerprint'];

        $revokedCount = UserDevice::where('user_id', $user->id)
            ->where('fingerprint', '!=', $currentFingerprint)
            ->count();

        // Revoke all devices except current
        UserDevice::where('user_id', $user->id)
            ->where('fingerprint', '!=', $currentFingerprint)
            ->get()
            ->each(function ($device) {
                $this->deviceService->revokeDevice($device->fingerprint, $device->user_id);
            });

        return back()->with('success', "Revoked access for {$revokedCount} devices.");
    }

    /**
     * Get device location data via AJAX.
     */
    public function getDeviceLocation(Request $request, $deviceId)
    {
        $device = UserDevice::where('id', $deviceId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $location = $this->deviceService->getLocationFromIP($device->ip_address);

        return response()->json($location);
    }
}
