<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContainerController extends Controller
{
    /**
     * Generate PiHole Script
     */
    public function generatePihole(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'ip_address' => 'required|ipv4',
            'gateway' => 'required|ipv4',
            'password' => 'required|string',
            'dns1' => 'nullable|ipv4',
            'dns2' => 'nullable|ipv4',
        ]);

        $iface = $request->interface;
        $ip = $request->ip_address;
        $gw = $request->gateway;
        $pass = $request->password;
        $dns1 = $request->dns1 ?? '8.8.8.8';
        $dns2 = $request->dns2 ?? '1.1.1.1';

        $script = "/interface veth add name=veth-pihole address={$ip}/24 gateway={$gw}\n";
        $script .= "/interface bridge port add bridge=docker interface=veth-pihole\n";
        $script .= "/container/envs add name=pihole_envs key=TZ value=\"Asia/Jakarta\"\n";
        $script .= "/container/envs add name=pihole_envs key=WEBPASSWORD value=\"{$pass}\"\n";
        $script .= "/container/envs add name=pihole_envs key=PIHOLE_DNS_1 value=\"{$dns1}\"\n";
        $script .= "/container/envs add name=pihole_envs key=PIHOLE_DNS_2 value=\"{$dns2}\"\n";
        $script .= "/container/mounts add name=pihole_etc src=pihole/etc dst=/etc/pihole\n";
        $script .= "/container/mounts add name=pihole_dnsmasq src=pihole/dnsmasq dst=/etc/dnsmasq.d\n";
        $script .= "/container/config/set registry-url=https://registry-1.docker.io\n";
        $script .= "/container add remote-image=pihole/pihole:latest interface=veth-pihole root-dir=pihole/root mounts=pihole_etc,pihole_dnsmasq envlist=pihole_envs logging=yes\n";
        $script .= ":delay 10s\n";
        $script .= "/container start [find tag=\"pihole/pihole:latest\"]\n";

        return response()->json(['script' => $script]);
    }

    /**
     * Generate AdGuard Home Script
     */
    public function generateAdguard(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'ip_address' => 'required|ipv4',
            'gateway' => 'required|ipv4',
        ]);

        $iface = $request->interface;
        $ip = $request->ip_address;
        $gw = $request->gateway;

        $script = "/interface veth add name=veth-adguard address={$ip}/24 gateway={$gw}\n";
        $script .= "/interface bridge port add bridge=docker interface=veth-adguard\n";
        $script .= "/container/mounts add name=adguard_work src=adguard/work dst=/opt/adguardhome/work\n";
        $script .= "/container/mounts add name=adguard_conf src=adguard/conf dst=/opt/adguardhome/conf\n";
        $script .= "/container/config/set registry-url=https://registry-1.docker.io\n";
        $script .= "/container add remote-image=adguard/adguardhome:latest interface=veth-adguard root-dir=adguard/root mounts=adguard_work,adguard_conf logging=yes\n";
        $script .= ":delay 10s\n";
        $script .= "/container start [find tag=\"adguard/adguardhome:latest\"]\n";

        return response()->json(['script' => $script]);
    }

    /**
     * Generate Speedtest Script
     */
    public function generateSpeedtest(Request $request): JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'ip_address' => 'required|ipv4',
            'gateway' => 'required|ipv4',
        ]);

        $ip = $request->ip_address;
        $gw = $request->gateway;

        $script = "/interface veth add name=veth-speedtest address={$ip}/24 gateway={$gw}\n";
        $script .= "/interface bridge port add bridge=docker interface=veth-speedtest\n";
        $script .= "/container/config/set registry-url=https://registry-1.docker.io\n";
        $script .= "/container add remote-image=openspeedtest/latest interface=veth-speedtest root-dir=speedtest/root logging=yes\n";
        $script .= ":delay 10s\n";
        $script .= "/container start [find tag=\"openspeedtest/latest\"]\n";

        return response()->json(['script' => $script]);
    }

    /**
     * Generate AdBlock Script (Script Based)
     */
    public function generateAdblock(Request $request): JsonResponse
    {
        // Simple script generation for fetching hosts
        $script = "/tool fetch url=\"https://raw.githubusercontent.com/StevenBlack/hosts/master/hosts\" mode=https dst-path=adblock_hosts.txt\n";
        $script .= ":delay 5s\n";
        $script .= ":local fileContent [/file get adblock_hosts.txt contents]\n";
        $script .= "# Complex parsing would go here, but RouterOS scripting is limited for 5MB+ files.\n";
        $script .= "# Recommended: Use PiHole or AdGuard container instead.\n";

        return response()->json(['script' => $script]);
    }
    /**
     * PiHole Installer Wizard
     */
    public function piholeInstaller(): View
    {
        return view('mikrotik-suite.utilities.container.pihole');
    }

    /**
     * AdGuard Home Wizard
     */
    public function adguardHome(): View
    {
        return view('mikrotik-suite.utilities.container.adguard');
    }

    /**
     * Speedtest Server Wizard
     */
    public function speedtestServer(): View
    {
        return view('mikrotik-suite.utilities.container.speedtest');
    }

    /**
     * Universal AdBlock Installer
     */
    public function adblockInstaller(): View
    {
        return view('mikrotik-suite.utilities.container.adblock');
    }
}

