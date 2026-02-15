<?php

namespace App\Services\MikrotikSuite\Validators;

/**
 * Service for validating Mikrotik configuration parameters
 */
class ConfigValidatorService
{
    /**
     * Validate interface name
     *
     * @param string $interface Interface name
     * @return bool
     */
    public function isValidInterface(string $interface): bool
    {
        // Mikrotik interface names: alphanumeric, dash, underscore
        return preg_match('/^[a-zA-Z0-9_-]+$/', $interface) === 1;
    }

    /**
     * Validate port number
     *
     * @param int $port Port number
     * @return bool
     */
    public function isValidPort(int $port): bool
    {
        return $port >= 1 && $port <= 65535;
    }

    /**
     * Validate port range
     *
     * @param string $portRange Port range (e.g., "80-443")
     * @return bool
     */
    public function isValidPortRange(string $portRange): bool
    {
        if (str_contains($portRange, '-')) {
            [$start, $end] = explode('-', $portRange);
            return $this->isValidPort((int) $start) &&
                $this->isValidPort((int) $end) &&
                (int) $start <= (int) $end;
        }

        return $this->isValidPort((int) $portRange);
    }

    /**
     * Validate protocol
     *
     * @param string $protocol Protocol name
     * @return bool
     */
    public function isValidProtocol(string $protocol): bool
    {
        $validProtocols = ['tcp', 'udp', 'icmp', 'esp', 'ah', 'gre'];
        return in_array(strtolower($protocol), $validProtocols);
    }

    /**
     * Validate chain name
     *
     * @param string $chain Chain name
     * @param string $type Type (filter, nat, mangle)
     * @return bool
     */
    public function isValidChain(string $chain, string $type = 'filter'): bool
    {
        $validChains = [
            'filter' => ['input', 'forward', 'output'],
            'nat' => ['srcnat', 'dstnat'],
            'mangle' => ['prerouting', 'input', 'forward', 'output', 'postrouting']
        ];

        return isset($validChains[$type]) &&
            in_array(strtolower($chain), $validChains[$type]);
    }

    /**
     * Validate action
     *
     * @param string $action Action name
     * @param string $type Type (filter, nat, mangle)
     * @return bool
     */
    public function isValidAction(string $action, string $type = 'filter'): bool
    {
        $validActions = [
            'filter' => ['accept', 'drop', 'reject', 'jump', 'return', 'fasttrack-connection'],
            'nat' => ['masquerade', 'src-nat', 'dst-nat', 'redirect'],
            'mangle' => ['mark-connection', 'mark-packet', 'mark-routing', 'change-mss']
        ];

        return isset($validActions[$type]) &&
            in_array(strtolower($action), $validActions[$type]);
    }

    /**
     * Validate AS number (BGP)
     *
     * @param int $asNumber AS number
     * @return bool
     */
    public function isValidAsNumber(int $asNumber): bool
    {
        // Valid AS numbers: 1-4294967295 (32-bit)
        return $asNumber >= 1 && $asNumber <= 4294967295;
    }

    /**
     * Validate RouterOS version format
     *
     * @param string $version Version string
     * @return bool
     */
    public function isValidRouterOsVersion(string $version): bool
    {
        // Format: v6.xx or v7.xx or RouterOS v6.xx
        return preg_match('/^(RouterOS\s+)?v[67]\.\d+(\.\d+)?$/', $version) === 1;
    }

    /**
     * Validate encryption algorithm
     *
     * @param string $algorithm Algorithm name
     * @return bool
     */
    public function isValidEncryptionAlgorithm(string $algorithm): bool
    {
        $validAlgorithms = [
            'des',
            '3des',
            'aes-128',
            'aes-192',
            'aes-256',
            'aes-128-cbc',
            'aes-192-cbc',
            'aes-256-cbc',
            'aes-128-ctr',
            'aes-192-ctr',
            'aes-256-ctr'
        ];

        return in_array(strtolower($algorithm), $validAlgorithms);
    }

    /**
     * Validate hash algorithm
     *
     * @param string $algorithm Algorithm name
     * @return bool
     */
    public function isValidHashAlgorithm(string $algorithm): bool
    {
        $validAlgorithms = ['md5', 'sha1', 'sha256', 'sha512'];
        return in_array(strtolower($algorithm), $validAlgorithms);
    }
}
