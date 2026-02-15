<?php

namespace App\Services\MikrotikSuite\Validators;

/**
 * Service for validating IP addresses and network configurations
 */
class IpValidatorService
{
    /**
     * Validate IPv4 address
     *
     * @param string $ip IP address to validate
     * @return bool
     */
    public function isValidIpv4(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Validate IPv4 network (CIDR notation)
     *
     * @param string $network Network in CIDR notation (e.g., 192.168.1.0/24)
     * @return bool
     */
    public function isValidNetwork(string $network): bool
    {
        if (!str_contains($network, '/')) {
            return false;
        }

        [$ip, $mask] = explode('/', $network);

        if (!$this->isValidIpv4($ip)) {
            return false;
        }

        $mask = (int) $mask;
        return $mask >= 0 && $mask <= 32;
    }

    /**
     * Validate if IP is in private range
     *
     * @param string $ip IP address
     * @return bool
     */
    public function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false;
    }

    /**
     * Validate gateway IP
     *
     * @param string $gateway Gateway IP
     * @param string $network Network CIDR
     * @return bool
     */
    public function isGatewayInNetwork(string $gateway, string $network): bool
    {
        if (!$this->isValidIpv4($gateway) || !$this->isValidNetwork($network)) {
            return false;
        }

        [$networkIp, $mask] = explode('/', $network);

        $gatewayLong = ip2long($gateway);
        $networkLong = ip2long($networkIp);
        $maskLong = -1 << (32 - (int) $mask);

        return ($gatewayLong & $maskLong) === ($networkLong & $maskLong);
    }

    /**
     * Calculate network address from IP and mask
     *
     * @param string $ip IP address
     * @param int $mask Subnet mask (CIDR)
     * @return string Network address
     */
    public function getNetworkAddress(string $ip, int $mask): string
    {
        $ipLong = ip2long($ip);
        $maskLong = -1 << (32 - $mask);
        $networkLong = $ipLong & $maskLong;

        return long2ip($networkLong);
    }

    /**
     * Calculate broadcast address
     *
     * @param string $network Network in CIDR notation
     * @return string Broadcast address
     */
    public function getBroadcastAddress(string $network): string
    {
        [$ip, $mask] = explode('/', $network);

        $ipLong = ip2long($ip);
        $maskLong = -1 << (32 - (int) $mask);
        $broadcastLong = $ipLong | (~$maskLong);

        return long2ip($broadcastLong);
    }

    /**
     * Get usable IP range in a network
     *
     * @param string $network Network in CIDR notation
     * @return array First and last usable IPs
     */
    public function getUsableRange(string $network): array
    {
        [$ip, $mask] = explode('/', $network);
        $mask = (int) $mask;

        $networkAddress = $this->getNetworkAddress($ip, $mask);
        $broadcastAddress = $this->getBroadcastAddress($network);

        $firstUsable = long2ip(ip2long($networkAddress) + 1);
        $lastUsable = long2ip(ip2long($broadcastAddress) - 1);

        return [
            'first' => $firstUsable,
            'last' => $lastUsable,
            'count' => ip2long($lastUsable) - ip2long($firstUsable) + 1
        ];
    }
}
