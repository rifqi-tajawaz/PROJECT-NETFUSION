<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\HotspotService;
use App\Services\NetFusion\Modules\PppService;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Exception;

class DashboardController extends Controller
{
    protected $hotspotService;
    protected $pppService;
    protected $systemService;

    public function __construct(
        HotspotService $hotspotService,
        PppService $pppService,
        SystemRouterService $systemService
    ) {
        $this->hotspotService = $hotspotService;
        $this->pppService = $pppService;
        $this->systemService = $systemService;
    }

    public function index(Request $request)
    {
        $dashboardData = [];

        try {
            if (Session::has('router_session')) {
                // Get RouterOS system info
                $resource = $this->systemService->getResources();
                $identity = $this->systemService->getIdentity();

                // Get Active Interface fallback
                $defaultInterface = 'ether1';
                $allInterfaces = $this->systemService->getInterfaces();
                foreach ($allInterfaces as $iface) {
                    if (isset($iface['running']) && $iface['running'] == 'true' && $iface['disabled'] == 'false') {
                        $defaultInterface = $iface['name'];
                        break;
                    }
                }

                $trafficInterface = Session::get('router_session')['traffic_interface'] ?? $defaultInterface;

                // Get Hotspot data
                $activeUsers = $this->hotspotService->getActiveUsers();
                $hotspotUsers = $this->hotspotService->getUsers(); // Fetch all for accurate count
                $hotspotProfiles = $this->hotspotService->getProfiles();
                $hotspotServers = $this->hotspotService->getServers();

                // Get PPPoE data
                $pppoeSecrets = $this->pppService->getSecrets();
                $pppoeActive = $this->pppService->getActive();

                // Get interface traffic
                $interfaceTraffic = $this->systemService->getTraffic($trafficInterface);

                // Calculate statistics
                $dashboardData = [
                    'router' => [
                        'identity' => $identity['name'] ?? 'Unknown',
                        'uptime' => $resource['uptime'] ?? 'Unknown',
                        'version' => $resource['version'] ?? 'Unknown',
                        'cpu_load' => $resource['cpu-load'] ?? 0,
                        'memory_total' => $this->systemService->formatBytes($resource['total-memory'] ?? 0),
                        'memory_free' => $this->systemService->formatBytes($resource['free-memory'] ?? 0),
                        'hdd_total' => $this->systemService->formatBytes($resource['total-hdd-space'] ?? 0),
                        'hdd_free' => $this->systemService->formatBytes($resource['free-hdd-space'] ?? 0),
                    ],
                    'hotspot' => [
                        'active_users_count' => count($activeUsers),
                        'total_users_count' => count($hotspotUsers),
                        'profiles_count' => count($hotspotProfiles),
                        'servers_count' => count($hotspotServers),
                        'active_users' => array_slice($activeUsers, 0, 10), // Latest 10
                    ],
                    'pppoe' => [
                        'active_count' => count($pppoeActive),
                        'total_secrets_count' => count($pppoeSecrets),
                        'active_connections' => array_slice($pppoeActive, 0, 10), // Latest 10
                    ],
                    'traffic' => [
                        'interface' => $trafficInterface,
                        'rx_byte' => $this->systemService->formatBytes($interfaceTraffic[0]['rx-bits-per-second'] ?? 0),
                        'tx_byte' => $this->systemService->formatBytes($interfaceTraffic[0]['tx-bits-per-second'] ?? 0),
                        'rx_packet' => number_format($interfaceTraffic[0]['rx-packets-per-second'] ?? 0),
                        'tx_packet' => number_format($interfaceTraffic[0]['tx-packets-per-second'] ?? 0),
                    ],
                    'session' => Session::get('router_session'),
                    'currency' => Session::get('router_session')['currency'] ?? 'Rp',
                    'all_interfaces' => $allInterfaces, // Pass all interfaces
                ];
            }
        } catch (Exception $e) {
            // If connection fails, show error but still render dashboard
            $dashboardData['error'] = 'Connection error: ' . $e->getMessage();
            $dashboardData['all_interfaces'] = [];
        }

        return view('netfusion.dashboard', compact('dashboardData'));
    }

    /**
     * API endpoint for live dashboard updates
     */
    public function liveData(Request $request)
    {
        try {
            if (!Session::has('router_session')) {
                return response()->json(['error' => 'No active session'], 401);
            }

            // Interface logic
            $trafficInterface = $request->input('interface');
            if ($trafficInterface) {
                // Update session preference
                $session = Session::get('router_session');
                $session['traffic_interface'] = $trafficInterface;
                Session::put('router_session', $session);
            } else {
                $trafficInterface = Session::get('router_session')['traffic_interface'] ?? 'ether1';
            }

            $resource = $this->systemService->getResources();
            $activeUsers = $this->hotspotService->getActiveUsers();
            $pppoeActive = $this->pppService->getActive();

            $interfaceTraffic = $this->systemService->getTraffic($trafficInterface);

            return response()->json([
                'cpu_load' => $resource['cpu-load'] ?? 0,
                'memory_usage' => $this->systemService->calculateMemoryUsage($resource),
                'active_users' => count($activeUsers),
                'pppoe_active' => count($pppoeActive),
                'traffic' => [
                    'rx' => $this->systemService->formatBytes($interfaceTraffic[0]['rx-bits-per-second'] ?? 0),
                    'tx' => $this->systemService->formatBytes($interfaceTraffic[0]['tx-bits-per-second'] ?? 0),
                    'raw_rx' => $interfaceTraffic[0]['rx-bits-per-second'] ?? 0,
                    'raw_tx' => $interfaceTraffic[0]['tx-bits-per-second'] ?? 0,
                    'interface' => $trafficInterface
                ],
                'timestamp' => now()->format('H:i:s')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
