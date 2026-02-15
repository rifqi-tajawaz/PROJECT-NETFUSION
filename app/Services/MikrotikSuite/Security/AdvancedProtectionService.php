<?php

declare(strict_types=1);

namespace App\Services\MikrotikSuite\Security;

class AdvancedProtectionService
{
    /**
     * Generate Advanced Firewall Script
     */
    public function generateFirewallScript(array $data): string
    {
        $input_chain = $data['input_chain'] ?? false;
        $forward_chain = $data['forward_chain'] ?? false;
        $icmp = $data['icmp'] ?? false;
        $bogon = $data['bogon'] ?? false;
        $port_scan = $data['port_scan'] ?? false;

        $script = "/ip firewall filter\n";
        $script .= "add action=accept chain=input comment=\"defconf: accept established,related,untracked\" connection-state=established,related,untracked\n";
        $script .= "add action=drop chain=input comment=\"defconf: drop invalid\" connection-state=invalid\n";

        if ($icmp) {
            $script .= "add action=accept chain=input comment=\"defconf: accept ICMP\" protocol=icmp\n";
            $script .= "add action=accept chain=input comment=\"defconf: accept to local loopback (for CAPsMAN)\" dst-address=127.0.0.1\n";
        }

        if ($bogon) {
            // Standard Bogon List
            $script .= "/ip firewall address-list\n";
            $script .= "add list=BOGONS address=0.0.0.0/8 comment=\"Self-Identification\"\n";
            $script .= "add list=BOGONS address=10.0.0.0/8 comment=\"Private-Use\"\n";
            $script .= "add list=BOGONS address=127.0.0.0/8 comment=\"Loopback\"\n";
            $script .= "add list=BOGONS address=169.254.0.0/16 comment=\"Link Local\"\n";
            $script .= "add list=BOGONS address=172.16.0.0/12 comment=\"Private-Use\"\n";
            $script .= "add list=BOGONS address=192.0.2.0/24 comment=\"Test-Net-1\"\n";
            $script .= "add list=BOGONS address=192.168.0.0/16 comment=\"Private-Use\"\n";
            $script .= "/ip firewall filter\n";
            $script .= "add action=drop chain=input comment=\"Drop Bogon Input\" src-address-list=BOGONS\n";
            $script .= "add action=drop chain=forward comment=\"Drop Bogon Forward\" src-address-list=BOGONS\n";
        }

        if ($port_scan) {
            $script .= "add action=add-src-to-address-list address-list=port_scanners address-list-timeout=2w chain=input comment=\"Port Scanner Detect\" protocol=tcp psd=21,3s,3,1\n";
            $script .= "add action=add-src-to-address-list address-list=port_scanners address-list-timeout=2w chain=input comment=\"NMAP FIN Stealth scan\" protocol=tcp tcp-flags=fin,!syn,!rst,!psh,!ack,!urg\n";
            $script .= "add action=add-src-to-address-list address-list=port_scanners address-list-timeout=2w chain=input comment=\"SYN/FIN scan\" protocol=tcp tcp-flags=fin,syn\n";
            $script .= "add action=add-src-to-address-list address-list=port_scanners address-list-timeout=2w chain=input comment=\"SYN/RST scan\" protocol=tcp tcp-flags=syn,rst\n";
            $script .= "add action=drop chain=input comment=\"Drop Port Scanners\" src-address-list=port_scanners\n";
        }

        if ($input_chain) {
            // Drop everything else on Input unless explicitly allowed above (usually you'd allow LAN first)
            // For safety in this generator, we advise users to add allow rules first
            $script .= "add action=drop chain=input comment=\"Drop all not coming from LAN\" in-interface-list=!LAN\n";
        }

        if ($forward_chain) {
            $script .= "add action=accept chain=forward comment=\"defconf: accept in ipsec policy\" ipsec-policy=in,ipsec\n";
            $script .= "add action=accept chain=forward comment=\"defconf: accept out ipsec policy\" ipsec-policy=out,ipsec\n";
            $script .= "add action=fasttrack-connection chain=forward comment=\"defconf: fasttrack\" connection-state=established,related\n";
            $script .= "add action=accept chain=forward comment=\"defconf: accept established,related, untracked\" connection-state=established,related,untracked\n";
            $script .= "add action=drop chain=forward comment=\"defconf: drop invalid\" connection-state=invalid\n";
            $script .= "add action=drop chain=forward comment=\"defconf: drop all from WAN not DSTNATed\" connection-nat-state=!dstnat connection-state=new in-interface-list=WAN\n";
        }

        return $script;
    }

    /**
     * Generate Port Knocking Script
     */
    public function generatePortKnockingScript(array $data): string
    {
        $mode = $data['mode'];
        $interface = $data['interface'];
        $ports = $data['ports'];
        $duration = $data['duration'];
        $safeMode = $data['safe_mode'] ?? false;

        // Professional Header
        $date = date('Y-m-d H:i:s');
        $script = "###############################################################################\n";
        $script .= "# [NETFUSION] PORT KNOCKING CONFIGURATION\n";
        $script .= "###############################################################################\n";
        $script .= "# Generated: {$date}\n";
        $script .= "# Mode     : " . strtoupper($mode) . "\n";
        $script .= "# Interface: {$interface}\n";
        $script .= "# Target   : {$ports}\n";
        $script .= "###############################################################################\n\n";

        $script .= "/ip firewall filter\n";
        $script .= "# CLEANUP: Remove old NetFusion Port Knocking rules to avoid duplicates\n";
        $script .= "remove [find comment~\"\\[NETFUSION\\]\"]\n\n";

        // Safe Mode (Anti-Lockout)
        if ($safeMode) {
            $script .= "# [SAFE MODE] Ensure current session is NOT dropped during setup\n";
            $script .= "add action=accept chain=input connection-state=established,related \\\n";
            $script .= "    comment=\"[NETFUSION] Allow established - Prevents Lockout\"\n\n";
        }

        if ($mode === 'icmp') {
            $p1 = $data['packet1'] ?? 100;
            $p2 = $data['packet2'] ?? 200;

            $script .= "# KNOCK STAGE 1: Hit with ICMP size {$p1}\n";
            $script .= "add action=add-src-to-address-list address-list=\"port-knocking-1\" address-list-timeout=10s \\\n";
            $script .= "    chain=input protocol=icmp packet-size={$p1} in-interface={$interface} \\\n";
            $script .= "    comment=\"[NETFUSION] Knock 1: {$p1} bytes\"\n\n";

            $script .= "# KNOCK STAGE 2: Hit with ICMP size {$p2} -> UNLOCK\n";
            $script .= "add action=add-src-to-address-list address-list=\"port-knocking-2\" address-list-timeout={$duration} \\\n";
            $script .= "    chain=input protocol=icmp packet-size={$p2} src-address-list=\"port-knocking-1\" \\\n";
            $script .= "    in-interface={$interface} comment=\"[NETFUSION] Knock 2: {$p2} bytes -> GRANT ACCESS\"\n";
        } else {
            // Port Sequence Mode
            $knockPorts = array_map('trim', explode(',', $data['knock_ports'] ?? '1234,5678'));
            $timeout = '10s';

            $script .= "# KNOCK SEQUENCE: " . implode(' -> ', $knockPorts) . " -> UNLOCK\n\n";

            foreach ($knockPorts as $index => $port) {
                $step = $index + 1;
                $isLast = $index === count($knockPorts) - 1;

                $prevList = ($index === 0) ? null : "port-knocking-step-" . ($index);
                $currentList = ($isLast) ? "port-knocking-2" : "port-knocking-step-{$step}";
                $listTimeout = ($isLast) ? $duration : $timeout;

                $script .= "# Step {$step}: Knock Port {$port}\n";
                $script .= "add action=add-src-to-address-list address-list=\"{$currentList}\" address-list-timeout={$listTimeout} \\\n";
                $script .= "    chain=input protocol=tcp dst-port={$port} in-interface={$interface} " . ($prevList ? "src-address-list=\"{$prevList}\" " : "") . "\\\n";
                $script .= "    comment=\"[NETFUSION] Knock {$step}: TCP {$port}\"\n\n";
            }
        }

        $script .= "# FINAL ACTION: Accept Access\n";
        $script .= "add action=accept chain=input protocol=tcp dst-port={$ports} src-address-list=\"port-knocking-2\" \\\n";
        $script .= "    in-interface={$interface} comment=\"[NETFUSION] Open {$ports} for Unlocked IP\"\n\n";

        $script .= "# FINAL ACTION: Block Unauthorized Access\n";
        $script .= "add action=drop chain=input protocol=tcp dst-port={$ports} src-address-list=\"!port-knocking-2\" \\\n";
        $script .= "    in-interface={$interface} comment=\"[NETFUSION] Block Unauthorized Access to {$ports}\"";

        return trim($script);
    }
}
