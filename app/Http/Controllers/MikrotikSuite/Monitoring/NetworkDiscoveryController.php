<?php

namespace App\Http\Controllers\MikrotikSuite\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NetworkDiscoveryController extends Controller
{
    public function interfaceBonding()
    {
        return view('mikrotik-suite.monitoring.network-discovery.interface-bonding');
    }

    public function macAddressTools()
    {
        return view('mikrotik-suite.monitoring.network-discovery.mac-address-tools');
    }

    public function neighbourViewer()
    {
        return view('mikrotik-suite.monitoring.network-discovery.neighbour-viewer');
    }

    /**
     * Generate Neighbour Discovery Script
     */
    public function generateNeighbourDiscovery(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'interface_list' => 'required|string',
            'cdp' => 'boolean', // Checkbox not sent if unchecked, so validation rule 'boolean' handles true/false/1/0/null? boolean rule fails if key missing? No, default false logic.
            // Better to check 'nullable'. Checkboxes: if unchecked, not in Request if using plain standard submit, but formData includes it? No, formData follows standard HTML form rules (unchecked = missing).
            // Laravel 'boolean' rule: "The field under validation must be able to be cast as a boolean. Accepted input are true, false, 1, 0, "1", and "0"."
            // We should use $request->boolean('key') helper which handles missing keys as false.
        ]);

        $list = $request->input('interface_list');
        $cdp = $request->boolean('cdp');
        $lldp = $request->boolean('lldp');
        $mndp = $request->boolean('mndp');

        $script = "/ip neighbor discovery-settings set discover-interface-list={$list}";
        $script .= " lldp-protocol=" . ($lldp ? 'yes' : 'no');
        $script .= " cdp-protocol=" . ($cdp ? 'yes' : 'no');
        $script .= " mndp-protocol=" . ($mndp ? 'yes' : 'no');

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Interface Bonding Script
     */
    public function generateBonding(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'bonding_name' => 'required|string',
            'slaves' => 'required|string',
            'mode' => 'required|string',
            'monitor' => 'required|string',
            'interval' => 'required|integer',
        ]);

        $name = $request->input('bonding_name');
        $slaves = $request->input('slaves');
        $mode = $request->input('mode');
        $mon = $request->input('monitor');
        $int = $request->input('interval');

        $script = "/interface bonding add name=\"{$name}\" slaves={$slaves} mode={$mode} link-monitoring={$mon}";

        if ($mon === 'mii') {
            $script .= " mii-interval={$int}ms";
        } else {
            $script .= " arp-interval={$int}ms arp-ip-targets=...";
            $script .= "\n# Note: For ARP monitoring, specify 'arp-ip-targets' manually.";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate MAC Scan IP Command
     */
    public function generateMacScan(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'range' => 'required|string', // Could use ipv4/cidr validation but sometimes range is complex
        ]);

        $iface = $request->input('interface');
        $range = $request->input('range');

        $script = "/tool ip-scan interface={$iface} address-range={$range}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate MAC Ping/Telnet Command
     */
    public function generateMacPing(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'mac_address' => 'required|mac_address', // Laravel has mac_address rule? Yes, standard.
            'tool' => 'required|in:ping,telnet',
        ]);

        $mac = $request->input('mac_address');
        $tool = $request->input('tool');

        if ($tool === 'ping') {
            $script = "/ping {$mac}";
        } else {
            $script = "/tool mac-telnet {$mac}";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

