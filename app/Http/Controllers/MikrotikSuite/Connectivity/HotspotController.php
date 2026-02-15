<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Connectivity;

use App\Http\Controllers\Controller;
use App\Services\MikrotikSuite\ScriptGenerator\HotspotGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class HotspotController extends Controller
{
    private HotspotGeneratorService $generatorService;

    public function __construct(HotspotGeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function userGenerator(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.user-generator');
    }

    public function hotspotWizard(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.hotspot-wizard');
    }

    public function blockSharing(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.block-sharing');
    }

    public function expiredNotification(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.expired-notification');
    }

    public function qrCodeWifi(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.qr-code-wifi');
    }

    public function bandwidthLimiter(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.bandwidth-limiter');
    }

    public function loginTemplate(): View
    {
        return view('mikrotik-suite.connectivity.hotspot.login-template');
    }

    /**
     * Generate Hotspot Users logic
     */
    public function generateUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:1000',
            'userModel' => 'required|string|in:user_pass,user_eq_pass',
            'prefix' => 'nullable|string|max:10',
            'length' => 'required|integer|min:3|max:20',
            'profile' => 'nullable|string',
            'timeLimit' => 'nullable|string|max:20',
        ]);

        $result = $this->generatorService->generateUsersScript([
            'qty' => (int) $validated['qty'],
            'mode' => $validated['userModel'],
            'prefix' => $validated['prefix'] ?? '',
            'length' => (int) $validated['length'],
            'profile' => $validated['profile'] ?? 'default',
            'timeLimit' => $validated['timeLimit'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'script' => $result['script'],
            'csv' => $result['csv'],
            'users' => $result['users']
        ]);
    }

    /**
     * Generate Hotspot Wizard script
     */
    public function generateWizard(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'iface' => 'required|string',
            'local_net' => 'required|string|regex:/^([0-9]{1,3}\.){3}[0-9]{1,3}\/[0-9]{1,2}$/', // CIDR
            'pool_range' => 'required|string',
            'dns_name' => 'required|string',
        ]);

        $script = $this->generatorService->generateWizardScript(
            $validated['iface'],
            $validated['local_net'],
            $validated['pool_range'],
            $validated['dns_name']
        );

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Block Sharing script
     */
    public function generateBlockSharing(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'script' => $this->generatorService->generateBlockSharingScript(),
        ]);
    }

    /**
     * Generate Expired Notification script
     */
    public function generateExpiredNotification(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'interval' => 'required|string',
            'remove_user' => 'boolean',
            'log_event' => 'boolean',
        ]);

        $script = $this->generatorService->generateExpiredNotificationScript(
            $validated['interval'],
            $request->boolean('remove_user'),
            $request->boolean('log_event')
        );

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate WiFi QR String
     */
    public function generateQrCode(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'ssid' => 'required|string',
            'encryption' => 'required|string|in:WPA,WEP,nopass',
            'password' => 'nullable|string',
            'hidden' => 'boolean',
        ]);

        $result = $this->generatorService->generateQrCodeUrl(
            $validated['ssid'],
            $validated['encryption'],
            $validated['password'],
            $request->boolean('hidden')
        );

        return response()->json([
            'status' => 'success',
            'qr_string' => $result['qr_string'],
            'qr_url' => $result['qr_url'],
            'ssid' => $validated['ssid'],
            'password' => $validated['encryption'] === 'nopass' ? null : $validated['password'],
        ]);
    }

    /**
     * Generate Bandwidth Limiter Script
     */
    public function generateBandwidthLimiter(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'target' => 'required|string',
            'max_up' => 'required|numeric|min:0',
            'max_down' => 'required|numeric|min:0',
            'comment' => 'nullable|string',
        ]);

        $script = $this->generatorService->generateBandwidthLimiterScript(
            $validated['target'],
            $validated['max_up'],
            $validated['max_down'],
            $validated['comment']
        );

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

