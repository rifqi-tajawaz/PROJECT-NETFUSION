<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CalculatorsController extends Controller
{
    // LB Calculators
    public function lbEcmpCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.lb-ecmp-calculator');
    }

    public function lbNthCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.lb-nth-calculator');
    }

    public function lbPccCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.lb-pcc-calculator');
    }

    // Network Calculators
    public function bandwidthCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.bandwidth-calculator');
    }

    public function burstCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.burst-calculator');
    }

    public function ipCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.ip-calculator');
    }

    public function pcqCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.pcq-calculator');
    }

    // Other Calculators
    // Other Calculators
    public function antennaHeight(): View
    {
        return view('mikrotik-suite.wireless.antenna-calculator');
    }

    public function ramProxyCalculator(): View
    {
        return view('mikrotik-suite.utilities.calculators.ram-proxy-calculator');
    }

    /**
     * Calculate IP Network Details
     */
    public function calculateIp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ip' => ['required', 'ipv4'],
            'cidr' => ['required', 'integer', 'min:0', 'max:32'],
        ]);

        $ip = $validated['ip'];
        $cidr = (int) $validated['cidr'];

        $ipLong = ip2long($ip);
        $maskLong = -1 << (32 - $cidr);
        $networkLong = $ipLong & $maskLong;
        $broadcastLong = $networkLong | (~$maskLong & 0xFFFFFFFF);

        // Hosts calculation
        if ($cidr == 32) {
            $hosts = 1;
            $firstIp = $ip;
            $lastIp = $ip;
        } elseif ($cidr == 31) {
            $hosts = 2; // Point-to-point usually used as such
            $firstIp = long2ip($networkLong);
            $lastIp = long2ip($broadcastLong);
        } else {
            $hosts = pow(2, 32 - $cidr) - 2;
            $firstIp = long2ip($networkLong + 1);
            $lastIp = long2ip($broadcastLong - 1);
        }

        // IP Class
        $firstOctet = (int) explode('.', $ip)[0];
        if ($firstOctet >= 1 && $firstOctet <= 126)
            $class = "Class A";
        elseif ($firstOctet >= 128 && $firstOctet <= 191)
            $class = "Class B";
        elseif ($firstOctet >= 192 && $firstOctet <= 223)
            $class = "Class C";
        elseif ($firstOctet >= 224 && $firstOctet <= 239)
            $class = "Class D (Multicast)";
        else
            $class = "Class E (Experimental)";

        // Binary Formatting
        $toBin = fn($n) => str_pad(decbin($n), 32, '0', STR_PAD_LEFT);
        $fmtBin = fn($bin) => implode('.', str_split($bin, 8));

        $binary = [
            'ip' => $fmtBin($toBin($ipLong)),
            'mask' => $fmtBin($toBin($maskLong)),
            'network' => $fmtBin($toBin($networkLong)),
            'broadcast' => $fmtBin($toBin($broadcastLong)),
        ];

        return response()->json([
            'network' => long2ip($networkLong),
            'broadcast' => long2ip($broadcastLong),
            'mask' => long2ip($maskLong),
            'hosts' => $hosts,
            'cidr' => $cidr,
            'class' => $class,
            'first_ip' => $firstIp,
            'last_ip' => $lastIp,
            'binary' => $binary,
        ]);
    }

    /**
     * Calculate Bandwidth Oversubscription
     */
    public function calculateBandwidth(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'total_down' => ['required', 'numeric', 'min:0'],
            'total_up' => ['required', 'numeric', 'min:0'],
            'res_down_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'res_up_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'tiers' => ['required', 'array'],
            'tiers.*.name' => ['required', 'string'],
            'tiers.*.down' => ['required', 'numeric', 'min:0'],
            'tiers.*.up' => ['required', 'numeric', 'min:0'],
            'tiers.*.clients' => ['required', 'numeric', 'min:0'],
        ]);

        $totalDown = (float) $validated['total_down'];
        $totalUp = (float) $validated['total_up'];
        $resDownPct = (float) $validated['res_down_pct'];
        $resUpPct = (float) $validated['res_up_pct'];

        $reservedDown = $totalDown * ($resDownPct / 100);
        $reservedUp = $totalUp * ($resUpPct / 100);

        $soldDown = 0;
        $soldUp = 0;

        foreach ($validated['tiers'] as $tier) {
            $soldDown += $tier['down'] * $tier['clients'];
            $soldUp += $tier['up'] * $tier['clients'];
        }

        $availDown = $totalDown - $reservedDown - $soldDown;
        $availUp = $totalUp - $reservedUp - $soldUp;

        return response()->json([
            'total_down' => $totalDown,
            'total_up' => $totalUp,
            'reserved_down' => round($reservedDown, 2),
            'reserved_up' => round($reservedUp, 2),
            'sold_down' => round($soldDown, 2),
            'sold_up' => round($soldUp, 2),
            'avail_down' => round($availDown, 2),
            'avail_up' => round($availUp, 2),
        ]);
    }

    /**
     * Calculate Burst Settings
     */
    public function calculateBurst(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'max_limit' => ['required', 'numeric', 'min:0'],
            'burst_limit' => ['required', 'numeric', 'min:0'],
            'burst_threshold' => ['required', 'numeric', 'min:0'],
            'burst_time' => ['required', 'numeric', 'min:0'],
        ]);

        $max = $validated['max_limit'];
        $limit = $validated['burst_limit'];
        $threshold = $validated['burst_threshold'];
        $time = $validated['burst_time'];

        if ($max >= $limit) {
            return response()->json([
                'error' => 'Burst Limit must be greater than Max Limit'
            ], 422);
        }

        // Logic Source: MikroTik Wiki
        // Actual Burst Time = (BurstThreshold * BurstTime) / BurstLimit
        $actualTime = 0;
        if ($limit > 0) {
            $actualTime = ($threshold * $time) / $limit;
        }

        // Generate Script
        $maxStr = $max . 'M';
        $limitStr = $limit . 'M';
        $thresholdStr = $threshold . 'M';

        $script = sprintf(
            '/queue simple add name="Burst-Client" target=192.168.88.10/32 max-limit=%s/%s burst-limit=%s/%s burst-threshold=%s/%s burst-time=%s/%s',
            $maxStr,
            $maxStr,
            $limitStr,
            $limitStr,
            $thresholdStr,
            $thresholdStr,
            $time,
            $time
        );

        return response()->json([
            'actual_time' => round($actualTime, 2),
            'script' => $script,
        ]);
    }

    /**
     * Calculate PCQ Limits
     */
    public function calculatePcq(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'active_users' => ['required', 'integer', 'min:1'],
            'total_down' => ['required', 'numeric', 'min:0'],
            'total_up' => ['required', 'numeric', 'min:0'],
            'rate_down' => ['required', 'numeric', 'min:0'],
            'rate_up' => ['required', 'numeric', 'min:0'],
        ]);

        $users = $validated['active_users'];
        $rateDown = $validated['rate_down'];
        $rateUp = $validated['rate_up'];
        $totalDown = $validated['total_down'];
        $totalUp = $validated['total_up'];

        // Logic: 50ms buffer rule
        // Limit (KiB) = Rate(Mbps) * 1000 * 50ms / 8 / 1024
        // Simplified: Rate(Mbps) * 6.25 -> rounded to 50KiB min.
        $limitDown = max(50, round(($rateDown * 1000 * 50) / 8 / 1024));
        $limitUp = max(50, round(($rateUp * 1000 * 50) / 8 / 1024));

        $totalLimitDown = $limitDown * $users;
        $totalLimitUp = $limitUp * $users;

        // Generate Script
        $script = "/queue type\n";
        $script .= "add kind=pcq name=\"pcq-download-custom\" pcq-rate={$rateDown}M pcq-limit={$limitDown}KiB pcq-total-limit={$totalLimitDown}KiB pcq-classifier=dst-address pcq-dst-address6-mask=64 pcq-src-address6-mask=64\n";
        $script .= "add kind=pcq name=\"pcq-upload-custom\" pcq-rate={$rateUp}M pcq-limit={$limitUp}KiB pcq-total-limit={$totalLimitUp}KiB pcq-classifier=src-address pcq-dst-address6-mask=64 pcq-src-address6-mask=64\n\n";

        $script .= "/queue simple\n";
        $script .= "add name=\"Total-Queue\" target=192.168.88.0/24 max-limit={$totalUp}M/{$totalDown}M queue=pcq-upload-custom/pcq-download-custom\n";

        return response()->json([
            'limit_down' => $limitDown,
            'limit_up' => $limitUp,
            'total_limit_down' => $totalLimitDown,
            'total_limit_up' => $totalLimitUp,
            'script' => $script,
        ]);
    }

    /**
     * Calculate LB PCC
     */
    public function calculateLbPcc(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lan_interface' => ['required', 'string'],
            'wan_if' => ['required', 'array', 'min:2'],
            'wan_gw' => ['required', 'array', 'min:2'],
            'dns' => ['boolean'],
            'failover' => ['boolean'],
        ]);

        $lanIf = $validated['lan_interface'];
        $wanIfs = $validated['wan_if'];
        $wanGws = $validated['wan_gw'];
        $dns = $request->boolean('dns');
        $failover = $request->boolean('failover');
        $count = count($wanIfs);

        $script = "/ip firewall mangle\n";
        $script .= "# PCC Load Balancing Script Generated by Dashboard Center\n";
        $script .= "# Date: " . now() . "\n\n";

        $script .= "# 1. Mark Connections\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=prerouting dst-address-type=!local in-interface={$lanIf} per-connection-classifier=both-addresses_and_ports:{$count}/{$i} action=mark-connection new-connection-mark=WAN{$num}_conn passthrough=yes comment=\"PCC WAN {$num}\"\n";
        }
        $script .= "\n";

        $script .= "# 2. Mark Routing\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=prerouting connection-mark=WAN{$num}_conn in-interface={$lanIf} action=mark-routing new-routing-mark=to_WAN{$num} passthrough=no\n";
        }
        $script .= "\n";

        $script .= "# 3. Input/Output Rules\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=input connection-mark=no-mark in-interface={$wanIfs[$i]} action=mark-connection new-connection-mark=WAN{$num}_conn passthrough=yes\n";
            $script .= "add chain=output connection-mark=WAN{$num}_conn action=mark-routing new-routing-mark=to_WAN{$num} passthrough=no\n";
        }
        $script .= "\n";

        $script .= "/ip fire nat\n";
        $script .= "# 4. Masquerade\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=srcnat out-interface={$wanIfs[$i]} action=masquerade comment=\"Masq WAN {$num}\"\n";
        }
        $script .= "\n";

        $script .= "/ip route\n";
        $script .= "# 5. Routes with Routing Marks\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[$i]} routing-mark=to_WAN{$num} check-gateway=ping distance=1 comment=\"Route WAN {$num}\"\n";
        }

        $script .= "\n# 6. Default Routes (Failover Distance)\n";
        if ($failover) {
            for ($i = 0; $i < $count; $i++) {
                $num = $i + 1;
                $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[$i]} distance={$num} check-gateway=ping comment=\"Main Table WAN {$num}\"\n";
            }
        } else {
            $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[0]} distance=1 check-gateway=ping comment=\"Main Gateway\"\n";
        }

        if ($dns) {
            $script .= "\n/ip dns set servers=8.8.8.8,8.8.4.4 allow-remote-requests=yes\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Calculate LB NTH
     */
    public function calculateLbNth(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lan_interface' => ['required', 'string'],
            'wan_if' => ['required', 'array', 'min:2'],
            'wan_gw' => ['required', 'array', 'min:2'],
            'failover' => ['boolean'],
        ]);

        $lanIf = $validated['lan_interface'];
        $wanIfs = $validated['wan_if'];
        $wanGws = $validated['wan_gw'];
        $failover = $request->boolean('failover');
        $count = count($wanIfs);

        $script = "/ip firewall mangle\n";
        $script .= "# NTH Load Balancing Script Generated by Dashboard Center\n";
        $script .= "# Date: " . now() . "\n\n";

        $script .= "# 1. Mark Connections (NTH)\n";
        for ($i = 0; $i < $count; $i++) {
            $counter = $i + 1;
            $script .= "add chain=prerouting dst-address-type=!local in-interface={$lanIf} nth={$count},{$counter} action=mark-connection new-connection-mark=WAN{$counter}_conn passthrough=yes comment=\"NTH WAN {$counter}\"\n";
        }
        $script .= "\n";

        $script .= "# 2. Mark Routing\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=prerouting connection-mark=WAN{$num}_conn in-interface={$lanIf} action=mark-routing new-routing-mark=to_WAN{$num} passthrough=no\n";
        }
        $script .= "\n";

        $script .= "# 3. Input/Output (Return Traffic)\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=input connection-mark=no-mark in-interface={$wanIfs[$i]} action=mark-connection new-connection-mark=WAN{$num}_conn passthrough=yes\n";
            $script .= "add chain=output connection-mark=WAN{$num}_conn action=mark-routing new-routing-mark=to_WAN{$num} passthrough=no\n";
        }
        $script .= "\n";

        $script .= "/ip fire nat\n";
        $script .= "# 4. Masquerade\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add chain=srcnat out-interface={$wanIfs[$i]} action=masquerade comment=\"Masq WAN {$num}\"\n";
        }
        $script .= "\n";

        $script .= "/ip route\n";
        $script .= "# 5. Routes\n";
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[$i]} routing-mark=to_WAN{$num} check-gateway=ping distance=1 comment=\"Route WAN {$num}\"\n";
        }

        $script .= "\n# 6. Default Routes (Failover)\n";
        if ($failover) {
            for ($i = 0; $i < $count; $i++) {
                $num = $i + 1;
                $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[$i]} distance={$num} check-gateway=ping comment=\"Main Table WAN {$num}\"\n";
            }
        } else {
            $script .= "add dst-address=0.0.0.0/0 gateway={$wanGws[0]} distance=1 check-gateway=ping comment=\"Main Gateway\"\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Calculate LB ECMP
     */
    public function calculateLbEcmp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gw_ip' => ['required', 'array', 'min:2'],
            'check_gateway' => ['boolean'],
            'dns' => ['boolean'],
        ]);

        $gws = array_filter($validated['gw_ip'], fn($v) => !empty($v));
        if (count($gws) < 2) {
            return response()->json(['error' => 'Please enter at least 2 Gateways'], 422);
        }

        $checkGw = $request->boolean('check_gateway');
        $dns = $request->boolean('dns');

        $script = "/ip route\n";
        $script .= "# ECMP Load Balancing Script Generated by Dashboard Center\n";
        $script .= "# Date: " . now() . "\n\n";

        $gatewayStr = implode(',', $gws);
        $checkStr = $checkGw ? ' check-gateway=ping' : '';

        $script .= "# Main ECMP Route\n";
        $script .= "add dst-address=0.0.0.0/0 gateway={$gatewayStr}{$checkStr} distance=1 comment=\"ECMP Load Balancing\"\n";

        if ($dns) {
            $script .= "\n/ip dns set servers=8.8.8.8,8.8.4.4 allow-remote-requests=yes\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Calculate RAM Proxy
     */
    public function calculateRamProxy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'total_ram' => ['required', 'numeric', 'min:0'],
            'ram_unit' => ['required', 'in:MB,GB'],
            'usage_ratio' => ['required', 'numeric', 'min:0', 'max:100'],
            'avg_obj_size' => ['required', 'numeric', 'min:0'],
        ]);

        $totalRam = $validated['total_ram'];
        $ramUnit = $validated['ram_unit'];
        $usageRatio = $validated['usage_ratio'] / 100;
        $avgObjSizeKb = $validated['avg_obj_size'];

        $ramBytes = $totalRam;
        if ($ramUnit === 'MB')
            $ramBytes *= 1024 * 1024;
        if ($ramUnit === 'GB')
            $ramBytes *= 1024 * 1024 * 1024;

        $maxCacheSize = floor($ramBytes * $usageRatio);

        // Est Objects
        $avgObjSizeBytes = $avgObjSizeKb * 1024;
        $estObjects = ($avgObjSizeBytes > 0) ? floor($maxCacheSize / $avgObjSizeBytes) : 0;

        // Formatting
        if ($maxCacheSize > 1024 * 1024 * 1024) {
            $displayCache = number_format($maxCacheSize / (1024 * 1024 * 1024), 2) . ' GB';
        } else {
            $displayCache = number_format($maxCacheSize / (1024 * 1024), 0) . ' MB';
        }

        $script = "/ip proxy set enabled=yes port=8080 ";
        $script .= "max-cache-size={$maxCacheSize} "; // Bytes
        $script .= "cache-on-disk=no "; // RAM Cache
        $script .= "max-client-connections=600 max-server-connections=600";

        return response()->json([
            'cache_size_display' => $displayCache,
            'est_objects' => number_format($estObjects),
            'script' => $script,
        ]);
    }
}
