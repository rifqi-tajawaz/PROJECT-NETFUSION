<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class EnterpriseMplsController extends Controller
{
    /**
     * Display LDP & VPLS Configuration Wizard
     */
    public function mplsLdpVpls(): View
    {
        return view('mikrotik-suite.network.enterprise.ldp-vpls');
    }

    /**
     * Display Traffic Engineering (TE) Configurator
     */
    public function trafficEngineering(): View
    {
        return view('mikrotik-suite.network.enterprise.traffic-engineering');
    }

    // =========================================================================
    // GENERATION LOGIC
    // =========================================================================

    public function generateLdpVpls(Request $request): JsonResponse
    {
        $request->validate([
            'router_id' => 'required|ipv4',
            'interface' => 'required|string',
            'remote_peer' => 'nullable|ipv4',
            'vpls_id' => 'required|integer',
        ]);

        $rid = $request->input('router_id');
        $iface = $request->input('interface');
        $peer = $request->input('remote_peer');
        $vID = $request->input('vpls_id');

        $script = "/interface bridge add name=loopback comment=\"Loopback\"\n";
        $script .= "/ip address add address={$rid}/32 interface=loopback\n";
        $script .= "/mpls ldp set enabled=yes transport-address={$rid} lsr-id={$rid}\n";
        $script .= "/mpls ldp interface add interface={$iface}\n";

        if ($peer) {
            $script .= "/interface vpls add name=vpls1 remote-peer={$peer} vpls-id={$vID} disabled=no\n";
        }

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function generateTrafficEngineering(Request $request): JsonResponse
    {
        $request->validate([
            'tunnel_name' => 'required|string',
            'to_address' => 'required|ipv4',
            'bandwidth' => 'required|numeric',
            'primary_path' => 'nullable|string',
        ]);

        $name = $request->input('tunnel_name');
        $to = $request->input('to_address');
        $bw = $request->input('bandwidth');
        $path = $request->input('primary_path');

        $script = "/interface traffic-eng add name=\"{$name}\" to-address={$to} bandwidth={$bw}M";
        if ($path && $path !== 'dynamic') {
            $script .= " primary-path=\"{$path}\"";
        }
        $script .= " record-route=yes from-address=0.0.0.0 disabled=no";

        return response()->json(['status' => 'success', 'script' => $script]);
    }
}

