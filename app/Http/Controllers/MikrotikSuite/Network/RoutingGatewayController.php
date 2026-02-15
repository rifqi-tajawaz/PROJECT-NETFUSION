<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RoutingGatewayController extends Controller
{
    /**
     * Display BGP generator configuration wizard
     */
    public function bgpGenerator(): View
    {
        return view('mikrotik-suite.network.config.bgp-generator');
    }

    /**
     * Generate BGP Config
     */
    public function generateBgp(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'local_as' => 'required|integer',
            'router_id' => 'required|ipv4',
            'remote_as' => 'required|integer',
            'remote_ip' => 'required|ipv4',
            'network' => 'nullable|string', // CIDR validation could be added
        ]);

        $las = $request->input('local_as');
        $rid = $request->input('router_id');
        $ras = $request->input('remote_as');
        $rip = $request->input('remote_ip');
        $net = $request->input('network');

        $script = "/routing bgp instance set default as={$las} router-id={$rid}\n";
        $script .= "/routing bgp peer add name=\"peer1\" remote-address={$rip} remote-as={$ras} ttl=default\n";

        if ($net) {
            $script .= "/routing bgp network add network={$net} synchronize=no\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Display failover gateway configuration wizard
     */
    public function failoverGateway(): View
    {
        return view('mikrotik-suite.network.config.failover-gateway');
    }

    /**
     * Generate Failover Gateway Config
     */
    public function generateFailover(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'gateway_1' => 'required|ipv4',
            'check_host_1' => 'required|ipv4',
            'gateway_2' => 'required|ipv4',
            'check_host_2' => 'required|ipv4',
        ]);

        $gw1 = $request->input('gateway_1');
        $chk1 = $request->input('check_host_1');
        $gw2 = $request->input('gateway_2');
        $chk2 = $request->input('check_host_2');

        $script = "/ip route\n";
        $script .= "add dst-address={$chk1} gateway={$gw1} scope=10 comment=\"Check-ISP1\"\n";
        $script .= "add dst-address={$chk2} gateway={$gw2} scope=10 comment=\"Check-ISP2\"\n";
        $script .= "add dst-address=0.0.0.0/0 gateway={$chk1} distance=1 check-gateway=ping comment=\"Primary Recursive\"\n";
        $script .= "add dst-address=0.0.0.0/0 gateway={$chk2} distance=2 check-gateway=ping comment=\"Secondary Recursive\"\n";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Display OSPF generator configuration wizard
     */
    public function ospfGenerator(): View
    {
        return view('mikrotik-suite.network.config.ospf-generator');
    }

    /**
     * Generate OSPF Config
     */
    public function generateOspf(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'router_id' => 'required|ipv4',
            'network' => 'required|string',
            'area' => 'required|string',
            // Checkboxes
        ]);

        $rid = $request->input('router_id');
        $net = $request->input('network');
        $area = $request->input('area');
        $redist = $request->boolean('redistribute_connected');
        $def = $request->boolean('redistribute_default');

        $script = "/routing ospf instance set [ find default=yes ] router-id={$rid}";

        if ($redist) {
            $script .= " redistribute-connected=as-type-1";
        }
        if ($def) {
            $script .= " redistribute-default=always-as-type-1";
        }

        $script .= "\n/routing ospf network add network={$net} area={$area}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Display policy routing configuration wizard
     */
    public function policyRouting(): View
    {
        return view('mikrotik-suite.network.config.policy-routing');
    }

    /**
     * Generate Policy Routing Config
     */
    public function generatePolicyRouting(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'source_address' => 'required|string',
            'routing_mark' => 'required|string',
            'target_gateway' => 'required|ipv4',
        ]);

        $src = $request->input('source_address');
        $mark = $request->input('routing_mark');
        $gw = $request->input('target_gateway');

        $script = "/ip firewall mangle add chain=prerouting src-address={$src} action=mark-routing new-routing-mark={$mark} passthrough=yes comment=\"PBR\"\n";
        $script .= "/ip route add dst-address=0.0.0.0/0 gateway={$gw} routing-table={$mark} comment=\"Route for {$mark}\"\n";
        $script .= "# Note: For ROS v7, ensure routing table exists or is created automatically.";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Display static route generator configuration wizard
     */
    public function staticRouteGenerator(): View
    {
        return view('mikrotik-suite.network.config.static-route-generator');
    }

    /**
     * Generate Static Route Config
     */
    public function generateStaticRoute(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'destination' => 'required|string',
            'gateway' => 'required|string', // Could be IP or interface name
            'distance' => 'required|integer',
            'comment' => 'nullable|string',
        ]);

        $dst = $request->input('destination');
        $gw = $request->input('gateway');
        $dist = $request->input('distance');
        $cmt = $request->input('comment', 'Static Route');

        $script = "/ip route add dst-address={$dst} gateway={$gw} distance={$dist} comment=\"{$cmt}\" check-gateway=ping";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

