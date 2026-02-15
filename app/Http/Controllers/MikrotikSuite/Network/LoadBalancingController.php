<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoadBalancingController extends Controller
{
    /**
     * Display PCC load balancing configuration wizard
     */
    public function lbPcc(): View
    {
        return view('mikrotik-suite.network.load-balancing.pcc');
    }

    public function pcc()
    {
        return view('mikrotik-suite.network.load-balancing.pcc');
    }

    public function nth()
    {
        return view('mikrotik-suite.network.load-balancing.nth');
    }

    /**
     * Display NTH load balancing configuration wizard
     */
    public function lbNth(): View
    {
        return view('mikrotik-suite.network.load-balancing.nth');
    }

    /**
     * Display ECMP load balancing configuration wizard
     */
    public function lbEcmp(): View
    {
        return view('mikrotik-suite.network.load-balancing.ecmp');
    }

    // =========================================================================
    // GENERATION LOGIC
    // =========================================================================

    /**
     * Generate PCC Load Balancing Script
     */
    public function generatePcc(Request $request): JsonResponse
    {
        // 1. Validation
        $request->validate([
            'wan_count' => 'required|integer|min:2|max:50',
            'ros_version' => 'required|string|in:v6.xx,v7.xx',
            'local_type' => 'required|string|in:address-list,interface,interface-list',
            'local_target' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($value) || trim($value) === '') {
                        if ($request->local_type === 'address-list') {
                            $fail('Please specify a valid Local Address List value.');
                        } else {
                            $fail('Please specify a valid Interface or Interface List value.');
                        }
                    }
                },
            ],
            'feature_failover' => 'boolean',
            'feature_ratio' => 'boolean',
        ]);

        $wanCount = (int) $request->input('wan_count');
        $isV7 = str_starts_with($request->input('ros_version'), 'v7');
        $localType = $request->input('local_type');
        $localTarget = $request->input('local_target');
        $useFailover = $request->boolean('feature_failover');
        $useRatio = $request->boolean('feature_ratio');

        // Filter Valid WANs
        $validWans = [];
        $inputWanCount = (int) $request->input('wan_count');

        for ($i = 1; $i <= $inputWanCount; $i++) {
            $iface = $request->input("wan_interface_{$i}");
            $gw = $request->input("wan_gateway_{$i}");

            // Skip if both empty (or just interface empty depending on strictness)
            if (filled($iface) && filled($gw)) {
                $validWans[] = [
                    'id' => count($validWans) + 1, // New sequential ID (1, 2, 3...)
                    'original_id' => $i,
                    'iface' => $iface,
                    'gw' => $gw,
                    'check' => $request->input("wan_check_{$i}"),
                    'speed' => $request->input("wan_speed_{$i}")
                ];
            }
        }

        $wanCount = count($validWans);

        if ($wanCount < 2) {
            return response()->json(['message' => "At least 2 valid WAN connections are required (Interface & Gateway)."], 422);
        }

        // --- SMART LOGIC: Handle Duplicate Gateways ---
        $gwCounts = array_count_values(array_column($validWans, 'gw'));
        foreach ($validWans as &$wan) {
            $ip = $wan['gw'];
            if (isset($gwCounts[$ip]) && $gwCounts[$ip] > 1) {
                if (!str_contains($ip, '%')) {
                    $wan['gw'] = "{$ip}%{$wan['iface']}";
                }
            }
        }
        unset($wan);

        // 2. Script Generation
        $script = "";
        $timestamp = now()->format('d/m/Y H:i:s');
        $version = $request->input('ros_version');
        $failoverStr = $useFailover ? 'ENABLED' : 'DISABLED';
        $ratioStr = $useRatio ? 'ENABLED' : 'DISABLED';

        // 0. HEADER
        $script .= "# =======================================================\n";
        $script .= "#  NETFUSION PCC LOAD BALANCING (Per Connection Classifier)\n";
        $script .= "# =======================================================\n";
        $script .= "#\n";
        $script .= "#  Generated on   : {$timestamp}\n";
        $script .= "#  RouterOS Ver   : {$version}\n";
        $script .= "#  WAN Interfaces : {$wanCount} Lines\n";
        $script .= "#  Failover Mode  : {$failoverStr}\n";
        $script .= "#  Bandwidth Ratio: {$ratioStr}\n";

        if ($localType === 'address-list') {
            $script .= "#  Local Target   : IP Address List (RFC1918)\n";
        } elseif ($localType === 'interface') {
            $script .= "#  Local Target   : In. Interface ({$localTarget})\n";
        } else {
            $script .= "#  Local Target   : In. Interface List ({$localTarget})\n";
        }
        $script .= "#\n";
        $script .= "# =======================================================\n\n";

        // 1. IP ADDRESS LIST
        $script .= "# Rule: Configure IP Address List.\n";
        $script .= "/ip firewall address-list\n";
        $script .= "add address=192.168.0.0/16 list=\"LOCAL-IP\" comment=\"Local IPs or Private IPs (RFC1918)\"\n";
        $script .= "add address=172.16.0.0/12 list=\"LOCAL-IP\"\n";
        $script .= "add address=10.0.0.0/8 list=\"LOCAL-IP\"\n\n";

        // 2. NAT
        $script .= "# Rule: Configure NAT Masquerade for each WAN interface.\n";
        $script .= "/ip firewall nat\n";
        foreach ($validWans as $wan) {
            $script .= "add chain=srcnat out-interface=\"{$wan['iface']}\" action=masquerade comment=\"NetFusion NAT :: ISP-{$wan['id']}\"\n";
        }
        $script .= "\n";

        // 2b. ROUTING TABLES (v7 ONLY)
        if ($isV7) {
            $script .= "# Rule: Configure Routing Tables (V7 only).\n";
            $script .= "/routing table\n";
            foreach ($validWans as $wan) {
                $script .= "add name=\"TO-ISP-{$wan['id']}\" fib comment=\"NetFusion Routing Table :: ISP-{$wan['id']}\"\n";
            }
            $script .= "\n";
        }

        // 3. ROUTES
        $script .= "# Rule: Configure IP Routes.\n";
        $script .= "/ip route\n";

        // A. Recursive Check Routes (Only if Failover is ON)
        if ($useFailover) {
            foreach ($validWans as $wan) {
                $checkIp = $wan['check'] ?? "1.0.0.{$wan['id']}";
                $script .= "add check-gateway=ping dst-address=\"{$checkIp}\" distance=1 gateway={$wan['gw']} target-scope=\"10\" scope=\"10\" comment=\"NetFusion Failover :: Check ISP-{$wan['id']}\"\n";
            }
            $script .= "\n";
        }

        // B. Routing Mark Routes
        foreach ($validWans as $wan) {
            $checkIp = $wan['check'];
            $targetGw = ($useFailover && !empty($checkIp)) ? $checkIp : $wan['gw'];
            $markParam = $isV7 ? "routing-table=\"TO-ISP-{$wan['id']}\"" : "routing-mark=\"TO-ISP-{$wan['id']}\"";
            $script .= "add check-gateway=ping distance=1 gateway={$targetGw}  scope=\"30\" target-scope=\"30\" {$markParam} comment=\"NetFusion Route :: ISP-{$wan['id']}\"\n";
        }

        // C. Default Routes
        foreach ($validWans as $wan) {
            $checkIp = $wan['check'];
            $targetGw = ($useFailover && !empty($checkIp)) ? $checkIp : $wan['gw'];
            $mainTableParam = $isV7 ? "routing-table=main" : "";
            $tableStr = $mainTableParam ? " {$mainTableParam}" : "";
            $script .= "add check-gateway=ping distance={$wan['id']} gateway={$targetGw} scope=\"30\" target-scope=\"30\"{$tableStr} comment=\"NetFusion Main Route :: ISP-{$wan['id']}\"\n";
        }
        $script .= "\n";

        // 4. MANGLE
        $script .= "# Rule: Configure Mangle Rules.\n";
        $script .= "/ip firewall mangle\n";
        $script .= "add action=accept chain=prerouting dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\" comment=\":: NetFusion Traffic Bypass ::\"\n";
        $script .= "add action=accept chain=postrouting dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=forward dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=input dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=output dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n\n";

        // Input
        foreach ($validWans as $wan) {
            $comment = ($wan['id'] === 1) ? ' comment=":: NetFusion Load Balancing ::"' : '';
            $script .= "add action=mark-connection chain=input in-interface=\"{$wan['iface']}\" new-connection-mark=\"ISP-{$wan['id']}-Connection\" passthrough=yes{$comment}\n";
        }

        // Output
        foreach ($validWans as $wan) {
            $script .= "add action=mark-routing chain=output connection-mark=\"ISP-{$wan['id']}-Connection\" new-routing-mark=\"TO-ISP-{$wan['id']}\" passthrough=yes\n";
        }

        // Filter Logic
        $filterParams = "";
        $filterParamsRouting = "";

        if ($localType === 'interface') {
            $filterParams = "dst-address-type=!local in-interface=\"{$localTarget}\"";
            $filterParamsRouting = "in-interface=\"{$localTarget}\"";
        } elseif ($localType === 'interface-list') {
            $filterParams = "dst-address-type=!local in-interface-list=\"{$localTarget}\"";
            $filterParamsRouting = "in-interface-list=\"{$localTarget}\";";
        } else {
            $filterParams = "dst-address-type=!local dst-address-list=\"!LOCAL-IP\" src-address-list=\"LOCAL-IP\"";
            $filterParamsRouting = "dst-address-list=\"!LOCAL-IP\" src-address-list=\"LOCAL-IP\"";
        }

        // Prerouting PCC
        if ($useRatio) {
            $speeds = [];
            foreach ($validWans as $wan) {
                $val = (int) ($wan['speed'] ?? 1);
                $speeds[] = $val;
            }

            $commonDivisor = $this->findGCD($speeds);
            $totalWeight = 0;
            $wanConfig = [];
            foreach ($validWans as $index => $wan) {
                $w = $speeds[$index] / $commonDivisor;
                $wanConfig[] = ['id' => $wan['id'], 'weight' => $w];
                $totalWeight += $w;
            }

            $currentPccIdx = 0;
            foreach ($wanConfig as $wan) {
                for ($k = 0; $k < $wan['weight']; $k++) {
                    $pccStr = "{$totalWeight}/{$currentPccIdx}";
                    $script .= "add action=mark-connection chain=prerouting new-connection-mark=\"ISP-{$wan['id']}-Connection\" passthrough=yes per-connection-classifier=both-addresses-and-ports:{$pccStr} {$filterParams}\n";
                    $currentPccIdx++;
                }
            }

        } else {
            // Equal Balance
            foreach ($validWans as $index => $wan) {
                $pccStr = "{$wanCount}/{$index}";
                $script .= "add action=mark-connection chain=prerouting new-connection-mark=\"ISP-{$wan['id']}-Connection\" passthrough=yes per-connection-classifier=both-addresses-and-ports:{$pccStr} {$filterParams}\n";
            }
        }

        // Prerouting Routing Marking
        foreach ($validWans as $wan) {
            $script .= "add action=mark-routing chain=prerouting connection-mark=\"ISP-{$wan['id']}-Connection\" new-routing-mark=\"TO-ISP-{$wan['id']}\" passthrough=yes {$filterParamsRouting}\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script
        ]);
    }

    /**
     * Generate NTH Load Balancing Script
     */
    public function generateNth(Request $request): JsonResponse
    {
        $request->validate([
            'wan_count' => 'required|integer|min:2|max:50',
            'ros_version' => 'required|string',
            'local_type' => 'required|string',
        ]);

        $wanCount = (int) $request->input('wan_count');
        $isV7 = str_starts_with($request->input('ros_version'), 'v7');
        $localType = $request->input('local_type');
        $localTarget = $request->input('local_target');
        $useFailover = $request->boolean('feature_failover');
        $useRatio = $request->boolean('feature_ratio');

        // Logic Preparation (Weights)
        $gateways = [];
        $rawWeights = [];

        // 1. Gather Weights & Gateways
        // First Pass: Collect Raw Weights to calculate GCD
        for ($i = 1; $i <= $wanCount; $i++) {
            $w = 1;
            if ($useRatio) {
                $w = (int) ($request->input("wan_weight_{$i}") ?? 1);
            }
            $rawWeights[] = $w;
        }

        $commonDivisor = $this->findGCD($rawWeights);
        $totalWeight = 0;

        // Second Pass: Build Gateways with Normalized Weights
        for ($i = 1; $i <= $wanCount; $i++) {
            $w = $rawWeights[$i - 1];
            $normalizedW = $w / $commonDivisor;

            $gateways[] = [
                'id' => $i,
                'iface' => $request->input("wan_interface_{$i}"),
                'gw' => $request->input("wan_gateway_{$i}"),
                'weight' => $normalizedW,
                'check' => $request->input("wan_check_{$i}")
            ];
            $totalWeight += $normalizedW;
        }

        if (count($gateways) < 2) { // Logic check provided by user's request for consistency, though NTH technically can work with 1 (but serves no purpose)
            // Check if we actually have valid inputs.
            // But gatewasy[] collects everything even if empty strings?
            // Look at lines 318...
        }

        // Wait, generateNth DOES NOT FILTER VALID WANS like ECMP/PCC.
        // It blindly loops 1 to wanCount.
        // If inputs are empty, it creates empty commands?
        // e.g. out-interface=""
        // I should probably clean up generateNth to filter valid ones too?
        // But user asked for "Completeness" of test mainly.
        // For now, I will just add the check if I CAN filter.
        // Let's stick to the Plan: Fix Controller Validation.
        // But NTH controller logic is lazy. It loops 1..N.
        // I should refrain from refactoring NTH logic too much unless asked.
        // I will just add validation to ECMP first as that one keeps validWans.

        // --- SMART LOGIC: Handle Duplicate Gateways ---
        $gwCounts = array_count_values(array_column($gateways, 'gw'));
        foreach ($gateways as &$g) {
            $ip = $g['gw'];
            // If this IP appears more than once, append %Interface to distinguish
            if (isset($gwCounts[$ip]) && $gwCounts[$ip] > 1) {
                // Ensure user hasn't already added % notation
                if (!str_contains($ip, '%')) {
                    $g['gw'] = "{$ip}%{$g['iface']}";
                }
            }
        }
        unset($g); // Break reference

        $script = "";
        $timestamp = now()->format('d/m/Y H:i:s');
        $version = $request->input('ros_version');
        $failoverStr = $useFailover ? 'on' : 'off';
        $ratioStr = $useRatio ? 'on' : 'off';

        // Local Target Strings
        $localComment = "Local IPs or Private IPs (RFC1918)";
        $localListStr = "";
        if ($localType === 'address-list') {
            $localListStr = "IP Address List (RFC1918)";
        } elseif ($localType === 'interface') {
            $localListStr = "In. Interface ({$localTarget})";
        } else {
            $localListStr = "In. Interface List ({$localTarget})";
        }

        // 0. HEADER
        $script .= "# =======================================================\n";
        $script .= "#  NETFUSION NTH LOAD BALANCING (Next Hop Hashing)\n";
        $script .= "# =======================================================\n";
        $script .= "#\n";
        $script .= "#  Generated on   : {$timestamp}\n";
        $script .= "#  RouterOS Ver   : {$version}\n";
        $script .= "#  WAN Interfaces : {$wanCount} Lines\n";
        $script .= "#  Recursive Mode : {$failoverStr}\n";
        $script .= "#  Bandwidth Ratio: {$ratioStr}\n";
        $script .= "#  Local Target   : {$localListStr}\n";
        $script .= "#\n";
        $script .= "# =======================================================\n\n";

        // 1. IP ADDRESS LIST
        $script .= "# Rule: Configure IP Address List.\n";
        $script .= "/ip firewall address-list\n";
        $script .= "add address=192.168.0.0/16 list=\"LOCAL-IP\" comment=\"{$localComment}\"\n";
        $script .= "add address=172.16.0.0/12 list=\"LOCAL-IP\"\n";
        $script .= "add address=10.0.0.0/8 list=\"LOCAL-IP\"\n\n";

        // 2. NAT
        $script .= "# Rule: Configure NAT Masquerade for each WAN interface.\n";
        $script .= "/ip firewall nat\n";
        foreach ($gateways as $g) {
            $script .= "add chain=srcnat out-interface=\"{$g['iface']}\" action=masquerade comment=\"NetFusion NAT :: ISP-{$g['id']}\"\n";
        }
        $script .= "\n";

        // 2b. ROUTING TABLES (v7)
        if ($isV7) {
            $script .= "# Rule: Configure Routing Tables (V7 only).\n";
            $script .= "/routing table\n";
            foreach ($gateways as $g) {
                // v7 uses lower case usually, matching reference implies we stick to standard
                // Reference uses "to-ISP-1"
                $script .= "add name=\"TO-ISP-{$g['id']}\" fib comment=\"NetFusion Routing Table :: ISP-{$g['id']}\"\n";
            }
            $script .= "\n";
        }

        // 3. IP ROUTE
        $script .= "# Rule: Configure IP Routes.\n";
        $script .= "/ip route\n";

        // A. Recursive Resolvers (Only if Recursive is ON)
        if ($useFailover) {
            foreach ($gateways as $g) {
                // If check is empty, fallback (though validation should catch or UI provides defaults)
                $checkIp = $g['check'] ?: "1.0.0.{$g['id']}";
                $script .= "add check-gateway=ping dst-address=\"{$checkIp}\" distance=1 gateway={$g['gw']} target-scope=\"10\" scope=\"10\" comment=\"NetFusion Failover :: Check ISP-{$g['id']}\"\n";
            }
            $script .= "\n";
        }

        // B. Marked Routes (Distance 1)
        foreach ($gateways as $g) {
            // Target Gateway depends on Failover Mode
            // Recursive ON: Target = Check IP
            // Recursive OFF: Target = ISP Gateway
            $targetGw = $useFailover ? ($g['check'] ?: "1.0.0.{$g['id']}") : $g['gw'];

            if ($isV7) {
                $script .= "add check-gateway=ping distance=1 gateway={$targetGw}  scope=\"30\" target-scope=\"30\" routing-table=\"TO-ISP-{$g['id']}\" comment=\"NetFusion Route :: ISP-{$g['id']}\"\n";
            } else {
                $script .= "add check-gateway=ping distance=1 gateway={$targetGw} routing-mark=\"TO-ISP-{$g['id']}\" scope=\"30\" target-scope=\"30\" comment=\"NetFusion Route :: ISP-{$g['id']}\"\n";
            }
        }

        // C. Default Routes (Distance 1, 2, 3...)
        // C. Default Routes (Distance 1, 2, 3...)
        foreach ($gateways as $g) {
            $dist = $g['id'];
            $targetGw = $useFailover ? ($g['check'] ?: "1.0.0.{$g['id']}") : $g['gw'];
            $mainTable = $isV7 ? " routing-table=main" : "";
            $script .= "add check-gateway=ping distance={$dist} gateway={$targetGw} scope=\"30\" target-scope=\"30\"{$mainTable} comment=\"NetFusion Main Route :: ISP-{$g['id']}\"\n";
        }
        $script .= "\n";

        // 4. MANGLE
        $script .= "# Rule: Configure Mangle Rules.\n";
        $script .= "/ip firewall mangle\n";

        // Bypass Rules (Always kept according to reference, even if using interface)
        $script .= "add action=accept chain=prerouting dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\" comment=\":: NetFusion Traffic Bypass ::\"\n";
        $script .= "add action=accept chain=postrouting dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=forward dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=input dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";
        $script .= "add action=accept chain=output dst-address-list=\"LOCAL-IP\" src-address-list=\"LOCAL-IP\"\n";

        // Determine Mangle Matcher based on Local Target Type
        $matcher = "";
        if ($localType === 'address-list') {
            $matcher = "dst-address-list=\"!LOCAL-IP\" src-address-list=\"LOCAL-IP\"";
        } elseif ($localType === 'interface') {
            $matcher = "in-interface=\"{$localTarget}\"";
        } else {
            // interface-list
            $matcher = "in-interface-list=\"{$localTarget}\"";
        }

        // Connection Marking (Incoming from WAN)
        // Connection Marking (Incoming from WAN)
        foreach ($gateways as $g) {
            $comment = ($g['id'] === 1) ? " comment=\":: NetFusion Load Balancing ::\"" : "";
            $script .= "add action=mark-connection chain=prerouting in-interface=\"{$g['iface']}\" new-connection-mark=\"ISP-{$g['id']}-Connection\" passthrough=yes{$comment}\n";
        }

        // Routing Marking (Output)
        // Routing Marking (Output)
        foreach ($gateways as $g) {
            $script .= "add action=mark-routing chain=output connection-mark=\"ISP-{$g['id']}-Connection\" new-routing-mark=\"TO-ISP-{$g['id']}\" passthrough=yes\n";
        }

        // NTH Marking (Prerouting)
        // nth=Total, Counter

        $counter = 1;
        foreach ($gateways as $g) {
            for ($k = 0; $k < $g['weight']; $k++) {
                $script .= "add action=mark-connection chain=prerouting new-connection-mark=\"ISP-{$g['id']}-Connection\" passthrough=yes connection-state=new {$matcher} nth={$totalWeight},{$counter}\n";
                $counter++;
            }
        }

        // Final Routing Mark (Prerouting)
        foreach ($gateways as $g) {
            $script .= "add action=mark-routing chain=prerouting connection-mark=\"ISP-{$g['id']}-Connection\" new-routing-mark=\"TO-ISP-{$g['id']}\" passthrough=yes  {$matcher}\n";
        }
        $script .= "\n";

        return response()->json([
            'status' => 'success',
            'script' => $script
        ]);
    }

    /**
     * Generate ECMP Load Balancing Script
     */
    public function generateEcmp(Request $request): JsonResponse
    {
        $request->validate([
            'wan_count' => 'required|integer|min:2|max:50',
            'ros_version' => 'required|string',
            'feature_failover' => 'boolean',
            'feature_ratio' => 'boolean',
        ]);
        $wanCountInput = (int) $request->input('wan_count');
        $useFailover = $request->boolean('feature_failover');
        $useRatio = $request->boolean('feature_ratio');

        // 1. Collect Raw Wans & Calculate GCD
        $validWans = [];
        $rawWeights = [];

        for ($i = 1; $i <= $wanCountInput; $i++) {
            $iface = $request->input("wan_interface_{$i}");
            $gw = $request->input("wan_gateway_{$i}");
            $recCheckIp = $request->input("wan_check_{$i}"); // Recursive IP (e.g. 1.1.1.1)

            if (filled($gw)) {
                $weight = 1;
                if ($useRatio) {
                    $weight = (int) ($request->input("wan_ratio_{$i}") ?? 1);
                }
                $rawWeights[] = $weight;

                $validWans[] = [
                    'id' => $i,
                    'iface' => $iface,
                    'gw' => $gw,
                    'rec_ip' => $recCheckIp,
                    'weight' => $weight
                ];
            }
        }

        // GCD Normalization
        $commonDivisor = 1;
        if ($useRatio && count($rawWeights) > 0) {
            $commonDivisor = $this->findGCD($rawWeights);
        }

        if (count($validWans) < 2) {
            return response()->json(['message' => "At least 2 valid WAN connections are required."], 422);
        }

        // 2. Smart Gateway Logic & Update Weights
        $gwCounts = array_count_values(array_column($validWans, 'gw'));
        foreach ($validWans as &$wan) {
            // Apply GCD
            if ($useRatio) {
                $wan['weight'] = $wan['weight'] / $commonDivisor;
            }

            // Duplicate IP Check
            $ip = $wan['gw'];
            if (isset($gwCounts[$ip]) && $gwCounts[$ip] > 1) {
                if (!str_contains($ip, '%')) {
                    $wan['gw'] = "{$ip}%{$wan['iface']}";
                }
            }
        }
        unset($wan);

        // 3. Build Gateway List for Main Route
        // If Failover/Recursive is ON, we use the Recursive IP as the main gateway.
        $finalGateways = [];
        $ratioCommentParts = [];

        foreach ($validWans as $wan) {
            // Determine actual gateway for the ECMP route
            // If Recursive Mode (useFailover) is ON, use rec_ip (1.1.1.1)
            // Else use physical gw (192.168.1.1)
            $mainRouteGw = $useFailover && filled($wan['rec_ip']) ? $wan['rec_ip'] : $wan['gw'];

            for ($k = 0; $k < $wan['weight']; $k++) {
                $finalGateways[] = $mainRouteGw;
            }
            $ratioCommentParts[] = "ISP-{$wan['id']} [{$wan['weight']}GW]";
        }

        $gatewayStr = implode(',', $finalGateways);
        $ratioCommentStr = implode(', ', $ratioCommentParts);
        $checkGw = ' check-gateway=ping';

        // 4. Variables for Script
        $timestamp = now()->format('d/m/Y H:i:s');
        $version = $request->input('ros_version');
        $failoverStr = $useFailover ? 'on' : 'off';
        $ratioStr = $useRatio ? 'on' : 'off';
        $wanCount = count($validWans);

        // 5. Build Script
        $script = "";
        $script .= "# =======================================================\n";
        $script .= "#  NETFUSION ECMP LOAD BALANCING (Equal Cost Multi-Path)\n";
        $script .= "# =======================================================\n";
        $script .= "#\n";
        $script .= "#  Generated on   : {$timestamp}\n";
        $script .= "#  RouterOS Ver   : {$version}\n";
        $script .= "#  WAN ISPs       : {$wanCount}\n";
        $script .= "#  Recursive Gateway: {$failoverStr}\n";
        $script .= "#  Bandwidth Ratio: {$ratioStr}\n";
        $script .= "#\n";
        $script .= "# =======================================================\n\n";

        // NAT Section
        $script .= "# Rule: Configure NAT Masquerade for each WAN interface.\n";
        $script .= "/ip firewall nat\n";
        foreach ($validWans as $wan) {
            $script .= "add chain=srcnat out-interface=\"{$wan['iface']}\" action=masquerade comment=\"NetFusion NAT :: ISP-{$wan['id']}\"\n";
        }
        $script .= "\n";

        // Route Section
        $script .= "# Rule: Configure IP Routes.\n";
        $script .= "/ip route\n";
        $script .= "# Remove any existing default routes.\n";
        $script .= "remove [find where dst-address=\"0.0.0.0/0\"]\n\n";

        $script .= "# Rule: Configure main ECMP default routes.\n";

        // RECURSIVE ROUTE SECTION (If Failover/Recursive is ON)
        if ($useFailover) {
            $script .= "# Rule: Configure Recursive Gateway Check (if enabled).\n";
            foreach ($validWans as $wan) {
                // Target IP (e.g. 1.1.1.1) via Physical GW (192.168.1.1)
                if (filled($wan['rec_ip'])) {
                    $script .= "add check-gateway=ping dst-address=\"{$wan['rec_ip']}\" distance=1 gateway={$wan['gw']} target-scope=\"10\" scope=\"10\" comment=\"NetFusion Failover :: Check ISP-{$wan['id']}\"\n";
                }
            }
            $script .= "\n";
        }

        // MAIN ECMP ROUTES
        if ($version === 'v7.xx') {
            // V7 Logic: Always use separate lines (Repeated for weights if Ratio is ON)
            $script .= "# Rule: Configure ECMP Default Routes for each ISP (" . ($useRatio ? "with" : "without") . " bandwidth ratio) for RouterOS v7.xx.\n";

            $isFirstRoute = true;
            foreach ($validWans as $wan) {
                // Determine Weight (If Ratio OFF, weight is effectively 1 for the loop, but $wan['weight'] might be 1 anyway)
                // actually $wan['weight'] is already calculated based on Ratio On/Off in step 2.
                // If Ratio OFF, weight is 1. If Ratio ON, it's 1 or more.

                // Determine GW for this specific route line (Recursive or Physical)
                $routeGw = $useFailover && filled($wan['rec_ip']) ? $wan['rec_ip'] : $wan['gw'];

                for ($k = 0; $k < $wan['weight']; $k++) {
                    $ratioLabel = $useRatio ? "Ratio " : "";
                    $comment = "NetFusion Main Route :: {$ratioLabel}ISP-{$wan['id']}";

                    if ($isFirstRoute) {
                        $comment = ":: NetFusion Load Balancing :: {$comment}";
                        $isFirstRoute = false;
                    }

                    $script .= "add dst-address=\"0.0.0.0/0\"{$checkGw} distance=1 gateway={$routeGw} routing-table=main target-scope=\"30\" scope=\"30\" comment=\"{$comment}\"\n";
                }
            }
        } else {
            // V6 Logic: Comma Separated (Original)
            $script .= "# Rule: Configure ECMP Default Route with multiple gateways in one line (for RouterOS v6.xx).\n";
            $script .= "add dst-address=\"0.0.0.0/0\"{$checkGw} distance=1 gateway=\"{$gatewayStr}\" target-scope=\"30\" scope=\"30\" comment=\"NetFusion Main Route :: ECMP Ratio : {$ratioCommentStr}\"\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script
        ]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function findGCD(array $arr): int
    {
        if (empty($arr))
            return 1;
        $result = $arr[0];
        for ($i = 1; $i < count($arr); $i++) {
            $result = $this->gcd($arr[$i], $result);
            if ($result == 1)
                return 1;
        }
        return $result;
    }

    private function gcd($a, $b): int
    {
        return $b === 0 ? $a : $this->gcd($b, $a % $b);
    }
}
