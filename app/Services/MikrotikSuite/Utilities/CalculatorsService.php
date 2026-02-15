<?php

namespace App\Services\MikrotikSuite\Utilities;

class CalculatorsService
{
    /**
     * Calculate IPv4 Network Details (including Breakdown)
     * 
     * @param string $ip
     * @param string|int $mask
     * @param string|null $action 'breakdown' or null
     * @param int|null $targetCidr for breakdown
     * @return array
     */
    public function calculateIp(string $ip, $mask, ?string $action = null, ?int $targetCidr = 0)
    {
        // 1. Validate IP (Basic check, Controller should handle HTTP validation but we do logic check)
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException('Invalid IPv4 Address');
        }

        // 2. Handle Netmask
        $cidr = 0;
        if (strpos($mask, '.') !== false) {
            if (!filter_var($mask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                throw new \InvalidArgumentException('Invalid Netmask Format');
            }
            $long = ip2long($mask);
            $base = ip2long('255.255.255.255');
            $cidr = 32 - log(($long ^ $base) + 1, 2);
        } else {
            $cidr = (int) $mask;
            if ($cidr < 0 || $cidr > 32) {
                throw new \InvalidArgumentException('Invalid CIDR (0-32)');
            }
        }

        $ipLong = ip2long($ip);
        $maskLong = -1 << (32 - $cidr);
        $networkLong = $ipLong & $maskLong;
        $broadcastLong = $networkLong | (~$maskLong);

        // 3. Breakdown Logic
        if ($action === 'breakdown') {
            if ($targetCidr <= $cidr || $targetCidr > 32) {
                throw new \InvalidArgumentException("New Mask must be greater than current mask (/$cidr) and <= 32");
            }

            $subnets = [];
            $currentIpLong = $networkLong;
            $subnetSize = pow(2, 32 - $targetCidr);
            $totalSubnets = pow(2, $targetCidr - $cidr);
            $maxSubnets = 256;

            for ($i = 0; $i < min($totalSubnets, $maxSubnets); $i++) {
                $sNetLong = $currentIpLong;
                $sCastLong = $sNetLong + $subnetSize - 1;

                $sHostMin = long2ip($sNetLong + 1);
                $sHostMax = long2ip($sCastLong - 1);

                if ($targetCidr == 31) {
                    $sHostMin = long2ip($sNetLong);
                    $sHostMax = long2ip($sCastLong);
                    $sHosts = 2;
                } elseif ($targetCidr == 32) {
                    $sHostMin = long2ip($sNetLong);
                    $sHostMax = long2ip($sNetLong);
                    $sHosts = 1;
                } else {
                    $sHosts = $subnetSize - 2;
                }

                $subnets[] = [
                    'index' => $i + 1,
                    'network' => long2ip($sNetLong),
                    'range' => $sHostMin . ' - ' . $sHostMax,
                    'broadcast' => long2ip($sCastLong),
                    'hosts' => number_format($sHosts)
                ];

                $currentIpLong += $subnetSize;
            }

            return [
                'subnets' => $subnets,
                'total_subnets' => $totalSubnets,
                'shown_subnets' => count($subnets),
                'target_cidr' => $targetCidr
            ];
        }

        // 4. Standard Calculation
        $hostMinLong = $networkLong + 1;
        $hostMaxLong = $broadcastLong - 1;

        if ($cidr == 31) {
            $hostMinLong = $networkLong;
            $hostMaxLong = $broadcastLong;
            $hostsCount = 2;
        } elseif ($cidr == 32) {
            $hostMinLong = $networkLong;
            $hostMaxLong = $networkLong;
            $hostsCount = 1;
        } else {
            $hostsCount = max(0, pow(2, 32 - $cidr) - 2);
        }

        $network = long2ip($networkLong);
        $broadcast = long2ip($broadcastLong);
        $hostMin = long2ip($hostMinLong);
        $hostMax = long2ip($hostMaxLong);
        $netmask = long2ip($maskLong);
        $wildcard = long2ip(~$maskLong);

        // Binaries
        $binaryIp = str_pad(decbin($ipLong), 32, '0', STR_PAD_LEFT);
        $binaryNetmask = str_pad(decbin($maskLong), 32, '0', STR_PAD_LEFT);
        $binaryNetwork = str_pad(decbin($networkLong), 32, '0', STR_PAD_LEFT);
        $binaryBroadcast = str_pad(decbin($broadcastLong), 32, '0', STR_PAD_LEFT);

        $formatBinary = function ($bin) {
            return implode('.', str_split($bin, 8));
        };

        // Class Detection
        $firstOctet = (int) explode('.', $ip)[0];
        $class = 'Unknown';
        if ($firstOctet >= 1 && $firstOctet <= 126)
            $class = 'Class A';
        elseif ($firstOctet >= 128 && $firstOctet <= 191)
            $class = 'Class B';
        elseif ($firstOctet >= 192 && $firstOctet <= 223)
            $class = 'Class C';
        elseif ($firstOctet >= 224 && $firstOctet <= 239)
            $class = 'Class D (Multicast)';
        elseif ($firstOctet >= 240 && $firstOctet <= 255)
            $class = 'Class E (Experimental)';

        // IP Type
        $ipType = 'Public IP';
        if (
            ($ipLong >= ip2long('10.0.0.0') && $ipLong <= ip2long('10.255.255.255')) ||
            ($ipLong >= ip2long('172.16.0.0') && $ipLong <= ip2long('172.31.255.255')) ||
            ($ipLong >= ip2long('192.168.0.0') && $ipLong <= ip2long('192.168.255.255'))
        ) {
            $ipType = 'Local (Private IP)';
        } elseif ($ipLong >= ip2long('127.0.0.0') && $ipLong <= ip2long('127.255.255.255')) {
            $ipType = 'Loopback';
        }

        // DHCP Logic
        $gateway = $ip;
        $poolStartLong = $ipLong + 1;
        if ($ipLong == $networkLong || $ipLong == $broadcastLong) {
            $gateway = $hostMin;
            $poolStartLong = $hostMinLong + 1;
        }
        if ($poolStartLong > $hostMaxLong) {
            $poolStartLong = $hostMaxLong;
        }
        $poolStart = long2ip($poolStartLong);
        $poolEnd = $hostMax;

        return [
            'ip' => $ip,
            'cidr' => $cidr,
            'netmask' => $netmask,
            'wildcard' => $wildcard,
            'network' => $network,
            'broadcast' => $broadcast,
            'host_min' => $hostMin,
            'host_max' => $hostMax,
            'hosts_count' => number_format($hostsCount),
            'class' => $class,
            'ip_type' => $ipType,
            'binary_ip' => $formatBinary($binaryIp),
            'binary_netmask' => $formatBinary($binaryNetmask),
            'binary_network' => $formatBinary($binaryNetwork),
            'binary_broadcast' => $formatBinary($binaryBroadcast),
            'network_cidr' => $network . '/' . $cidr,
            'dhcp_gateway' => $gateway,
            'pool_range' => $poolStart . ' - ' . $poolEnd
        ];
    }

    public function calculateBandwidth(array $data)
    {
        $totalDown = (float) $data['total_down'];
        $totalUp = (float) $data['total_up'];
        $resDownPct = (float) $data['reserved_down_pct'];
        $resUpPct = (float) $data['reserved_up_pct'];

        $resDownVal = $totalDown * ($resDownPct / 100);
        $resUpVal = $totalUp * ($resUpPct / 100);

        $effectiveDown = $totalDown - $resDownVal;
        $effectiveUp = $totalUp - $resUpVal;

        $tiers = $data['tiers'];
        $soldDown = 0;
        $soldUp = 0;
        $totalClients = 0;
        $processedTiers = [];

        foreach ($tiers as $tier) {
            $tDown = (float) $tier['down'];
            $tUp = (float) $tier['up'];
            $count = (int) $tier['count'];

            $tierTotalDown = $tDown * $count;
            $tierTotalUp = $tUp * $count;

            $soldDown += $tierTotalDown;
            $soldUp += $tierTotalUp;
            $totalClients += $count;

            $processedTiers[] = [
                'name' => $tier['name'],
                'down' => $tDown,
                'up' => $tUp,
                'count' => $count,
                'total_down' => $tierTotalDown,
                'total_up' => $tierTotalUp,
            ];
        }

        $ratioDown = ($effectiveDown > 0) ? ($soldDown / $effectiveDown) : 0;
        $ratioUp = ($effectiveUp > 0) ? ($soldUp / $effectiveUp) : 0;

        $ratioDownStr = '1:' . number_format($ratioDown, 1);
        $ratioUpStr = '1:' . number_format($ratioUp, 1);

        return [
            'config' => [
                'total_down' => $totalDown,
                'total_up' => $totalUp,
                'reserved_down_val' => $resDownVal,
                'reserved_up_val' => $resUpVal,
                'effective_down' => $effectiveDown,
                'effective_up' => $effectiveUp,
            ],
            'usage' => [
                'sold_down' => $soldDown,
                'sold_up' => $soldUp,
                'total_clients' => $totalClients,
            ],
            'analysis' => [
                'ratio_down' => $ratioDown,
                'ratio_up' => $ratioUp,
                'ratio_down_str' => $ratioDownStr,
                'ratio_up_str' => $ratioUpStr,
                'free_down' => max(0, $effectiveDown - $soldDown),
                'free_up' => max(0, $effectiveUp - $soldUp),
            ],
            'tiers' => $processedTiers
        ];
    }



    public function calculatePcq(array $data)
    {
        $totalDown = (float) $data['total_down'];
        $totalUp = (float) $data['total_up'];
        $activeUsers = (int) ($data['active_users'] ?? 1);
        $rateDown = (float) $data['rate_down'];
        $rateUp = (float) $data['rate_up'];

        if ($activeUsers <= 0)
            $activeUsers = 1;

        $limitDown = 50;
        if ($rateDown > 2)
            $limitDown = round(($rateDown / 2) * 50);
        if ($limitDown < 50)
            $limitDown = 50;

        $limitUp = 50;
        if ($rateUp > 2)
            $limitUp = round(($rateUp / 2) * 50);
        if ($limitUp < 50)
            $limitUp = 50;

        $totalLimitDown = $limitDown * $activeUsers;
        $totalLimitUp = $limitUp * $activeUsers;

        $script = $this->generatePcqScript($totalDown, $totalUp, $rateDown, $rateUp, $limitDown, $limitUp, $totalLimitDown, $totalLimitUp);

        return [
            'limit_down' => $limitDown,
            'limit_up' => $limitUp,
            'total_limit_down' => $totalLimitDown,
            'total_limit_up' => $totalLimitUp,
            'script' => $script
        ];
    }

    private function generatePcqScript($totalDown, $totalUp, $rateDown, $rateUp, $limitDown, $limitUp, $totalLimitDown, $totalLimitUp)
    {
        $rateDownStr = "{$rateDown}M";
        $rateUpStr = "{$rateUp}M";
        $maxDownStr = "{$totalDown}M";
        $maxUpStr = "{$totalUp}M";

        return "/ip firewall mangle
add action=mark-connection chain=prerouting new-connection-mark=client_conn passthrough=yes src-address-list=client_net
add action=mark-packet chain=prerouting connection-mark=client_conn new-packet-mark=client_upload passthrough=no src-address-list=client_net
add action=mark-packet chain=postrouting connection-mark=client_conn dst-address-list=client_net new-packet-mark=client_download passthrough=no

/queue type
add kind=pcq name=pcq_download pcq-classifier=dst-address pcq-dst-address6-mask=64 pcq-limit={$limitDown}KiB pcq-rate={$rateDownStr} pcq-src-address6-mask=64 pcq-total-limit={$totalLimitDown}KiB
add kind=pcq name=pcq_upload pcq-classifier=src-address pcq-dst-address6-mask=64 pcq-limit={$limitUp}KiB pcq-rate={$rateUpStr} pcq-src-address6-mask=64 pcq-total-limit={$totalLimitUp}KiB

/queue tree
add max-limit={$maxDownStr} name=Total_Download parent=global queue=default
add max-limit={$maxUpStr} name=Total_Upload parent=global queue=default
add name=Client_Download packet-mark=client_download parent=Total_Download queue=pcq_download
add name=Client_Upload packet-mark=client_upload parent=Total_Upload queue=pcq_upload";
    }
}
