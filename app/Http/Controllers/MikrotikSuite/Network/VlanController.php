<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class VlanController extends Controller
{
    /**
     * Bridge VLAN Filtering Wizard
     */
    public function bridgeVlanFiltering(): View
    {
        return view('mikrotik-suite.network.vlan.bridge-vlan-filtering');
    }

    /**
     * Management VLAN Security
     */
    public function managementVlan(): View
    {
        return view('mikrotik-suite.network.vlan.management-vlan');
    }

    // =========================================================================
    // GENERATION LOGIC
    // =========================================================================

    public function generateBridgeVlan(Request $request): JsonResponse
    {
        $request->validate([
            'bridge_name' => 'required|string',
            'tagged_ports' => 'nullable|string',
            'untagged_ports' => 'nullable|string',
            'vlan_id' => 'required|integer|min:1|max:4094',
        ]);

        $br = $request->input('bridge_name');
        $tag = $request->input('tagged_ports');
        $untag = $request->input('untagged_ports');
        $vid = $request->input('vlan_id');

        $script = "/interface bridge port\n";

        // PVID for untagged ports
        if ($untag) {
            $ports = explode(',', $untag);
            foreach ($ports as $p) {
                $p = trim($p);
                if ($p) {
                    $script .= "set [find interface={$p}] pvid={$vid} bridge={$br}\n";
                }
            }
        }

        $script .= "\n/interface bridge vlan\n";
        $script .= "add bridge={$br} tagged={$tag} untagged={$untag} vlan-ids={$vid}\n";
        $script .= "\n# Enable filtering LAST to avoid lockout\n";
        $script .= "/interface bridge set \"{$br}\" vlan-filtering=yes";

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function generateManagementVlan(Request $request): JsonResponse
    {
        $request->validate([
            'vlan_id' => 'required|integer|min:1|max:4094',
            'parent_interface' => 'required|string',
            'ip_address' => 'required|string', // Simple validation, could be improved with regex
        ]);

        $vid = $request->input('vlan_id');
        $parent = $request->input('parent_interface');
        $ip = $request->input('ip_address');

        $script = "/interface vlan add name=\"vlan{$vid}-mgmt\" vlan-id={$vid} interface={$parent}\n";
        $script .= "/ip address add address={$ip} interface=\"vlan{$vid}-mgmt\"\n";

        // Attempt to calculate subnet for security (simple approximation)
        $parts = explode('/', $ip);
        if (count($parts) === 2) {
            // Basic subnet calculation for /24 typically used in examples
            // For strict subnetting helper, we'd need more logic or just skip this "smart" part if it's too risky.
            // The JS did a simple regex replacement.
            $ipOnly = $parts[0];
            $subnet = preg_replace('/\.\d+$/', '.0', $ipOnly) . '/24';
            $script .= "/ip service set [find] address={$subnet}\n";
        }

        $script .= "# Note: Restrict IP Services to this subnet manually if the above calculation is imprecise.";

        return response()->json(['status' => 'success', 'script' => $script]);
    }
}

