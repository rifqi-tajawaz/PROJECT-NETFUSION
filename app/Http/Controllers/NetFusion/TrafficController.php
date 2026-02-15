<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Exception;

class TrafficController extends Controller
{
    protected $systemService;

    public function __construct(SystemRouterService $systemService)
    {
        $this->systemService = $systemService;
    }

    /**
     * Display Traffic Monitor Page
     */
    public function index()
    {
        $interfaces = [];
        try {
            if (Session::has('router_session')) {
                $interfaces = $this->systemService->getInterfaces();
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        // Filter valid interfaces (e.g., ethernet, bridge, vlan)
        $interfaces = array_filter($interfaces, function ($iface) {
            return !in_array($iface['type'] ?? '', ['loopback', 'unknown']);
        });

        return view('netfusion.monitor.traffic', compact('interfaces'));
    }

    /**
     * Get Live Traffic Data (JSON)
     */
    public function monitor(Request $request)
    {
        $interface = $request->get('interface', 'ether1');

        try {
            if (Session::has('router_session')) {
                $data = $this->systemService->getTraffic($interface);

                if ($data) {
                    // Check if $data is array of arrays (Mikrotik sometimes returns list)
                    $traffic = isset($data[0]) ? $data[0] : $data;

                    return response()->json([
                        'rx' => (int) ($traffic['rx-bits-per-second'] ?? 0),
                        'tx' => (int) ($traffic['tx-bits-per-second'] ?? 0),
                        'raw_rx' => (int) ($traffic['rx-bits-per-second'] ?? 0),
                        'raw_tx' => (int) ($traffic['tx-bits-per-second'] ?? 0),
                        'error' => false
                    ]);
                }
            }
        } catch (Exception $e) {
            // specific handling
        }

        return response()->json(['rx' => 0, 'tx' => 0, 'error' => true]);
    }
}
