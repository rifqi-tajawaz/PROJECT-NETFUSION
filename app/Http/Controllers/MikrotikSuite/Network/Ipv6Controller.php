<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class Ipv6Controller extends Controller
{
    /**
     * EUI-64 Calculator View
     */
    public function eui64Calculator(): View
    {
        return view('mikrotik-suite.network.ipv6.eui64-calculator');
    }

    /**
     * Subnetting /64 Generator View
     */
    public function subnettingGenerator(): View
    {
        return view('mikrotik-suite.network.ipv6.subnetting-generator');
    }

    /**
     * IPv6 Firewall Generator View
     */
    public function firewallV6Generator(): View
    {
        return view('mikrotik-suite.network.ipv6.firewall-generator');
    }

    /**
     * Neighbor Discovery Configuration View
     */
    public function neighborDiscovery(): View
    {
        return view('mikrotik-suite.network.ipv6.neighbor-discovery');
    }

    // =========================================================================
    // GENERATION LOGIC
    // =========================================================================

    public function generateFirewall(Request $request): JsonResponse
    {
        // No valid input needed for baseline, but we validate to ensure it is a POST
        $script = "/ipv6 firewall filter\n";
        $script .= "add chain=input action=accept connection-state=established,related comment=\"Accept Established/Related\"\n";
        $script .= "add chain=input action=drop connection-state=invalid comment=\"Drop Invalid\"\n";
        $script .= "add chain=input action=accept protocol=icmpv6 comment=\"Accept ICMPv6\"\n";
        $script .= "add chain=input action=accept protocol=udp port=33434-33534 comment=\"Accept UDP Traceroute\"\n";
        $script .= "add chain=input action=accept protocol=udp dst-port=546 src-address=fe80::/10 comment=\"Accept DHCPv6-Client prefix delegation\"\n";
        $script .= "add chain=input action=drop in-interface-list=WAN comment=\"Drop all other input from WAN\"\n";

        $script .= "\n# Forward Chain\n";
        $script .= "add chain=forward action=accept connection-state=established,related comment=\"Accept Established/Related\"\n";
        $script .= "add chain=forward action=drop connection-state=invalid comment=\"Drop Invalid\"\n";
        $script .= "add chain=forward action=accept protocol=icmpv6 comment=\"Accept ICMPv6\"\n";
        $script .= "add chain=forward action=accept in-interface-list=LAN comment=\"Accept LAN to WAN\"\n";
        $script .= "add chain=forward action=drop comment=\"Drop everything else\"\n";

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function generateNeighborDiscovery(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'managed' => 'boolean',
            'other' => 'boolean',
            'advertise_mac' => 'boolean',
            'min_interval' => 'required|integer',
            'max_interval' => 'required|integer',
        ]);

        $iface = $request->input('interface');
        $m = $request->boolean('managed') ? 'yes' : 'no';
        $o = $request->boolean('other') ? 'yes' : 'no';
        $adv = $request->boolean('advertise_mac') ? 'yes' : 'no';
        $min = $request->input('min_interval');
        $max = $request->input('max_interval');

        $script = "/ipv6 nd set [find interface={$iface}] disabled=no ra-interval={$min}s-{$max}s";
        $script .= " managed-address-configuration={$m}";
        $script .= " other-configuration={$o}";
        $script .= " advertise-mac-address={$adv}";

        $script .= "\n# If entry doesn't exist, use add:\n";
        $script .= "#/ipv6 nd add interface={$iface} ...";

        return response()->json(['status' => 'success', 'script' => $script]);
    }
}
