<?php

namespace App\Services\MikrotikSuite\ScriptGenerator;

/**
 * Service for generating VPN configuration scripts
 */
class VpnGeneratorService
{
    /**
     * Generate L2TP server configuration script
     *
     * @param array $config L2TP configuration
     * @return string Generated Mikrotik script
     */
    public function generateL2tpServer(array $config): string
    {
        $script = "/interface l2tp-server server\n";
        $script .= "set enabled=yes default-profile={$config['profile']}\n";
        $script .= "set authentication={$config['auth_method']}\n";

        if (isset($config['ipsec_secret'])) {
            $script .= "set use-ipsec=yes ipsec-secret=\"{$config['ipsec_secret']}\"\n";
        }

        $script .= "\n/ppp secret\n";
        foreach ($config['users'] as $user) {
            $script .= sprintf(
                "add name=%s password=%s service=l2tp local-address=%s remote-address=%s\n",
                $user['username'],
                $user['password'],
                $config['local_address'],
                $user['remote_address']
            );
        }

        return $script;
    }

    /**
     * Generate PPTP server configuration script
     *
     * @param array $config PPTP configuration
     * @return string Generated Mikrotik script
     */
    public function generatePptpServer(array $config): string
    {
        $script = "/interface pptp-server server\n";
        $script .= "set enabled=yes default-profile={$config['profile']}\n";
        $script .= "set authentication={$config['auth_method']}\n";

        $script .= "\n/ppp secret\n";
        foreach ($config['users'] as $user) {
            $script .= sprintf(
                "add name=%s password=%s service=pptp local-address=%s remote-address=%s\n",
                $user['username'],
                $user['password'],
                $config['local_address'],
                $user['remote_address']
            );
        }

        return $script;
    }

    /**
     * Generate SSTP server configuration script
     *
     * @param array $config SSTP configuration
     * @return string Generated Mikrotik script
     */
    public function generateSstpServer(array $config): string
    {
        $script = "/interface sstp-server server\n";
        $script .= "set enabled=yes default-profile={$config['profile']}\n";
        $script .= "set certificate={$config['certificate']}\n";
        $script .= "set authentication={$config['auth_method']}\n";

        $script .= "\n/ppp secret\n";
        foreach ($config['users'] as $user) {
            $script .= sprintf(
                "add name=%s password=%s service=sstp local-address=%s remote-address=%s\n",
                $user['username'],
                $user['password'],
                $config['local_address'],
                $user['remote_address']
            );
        }

        return $script;
    }

    /**
     * Generate OpenVPN configuration script
     *
     * @param array $config OpenVPN configuration
     * @return string Generated Mikrotik script
     */
    public function generateOpenvpn(array $config): string
    {
        $script = "/interface ovpn-server server\n";
        $script .= "set enabled=yes port={$config['port']}\n";
        $script .= "set mode={$config['mode']}\n";
        $script .= "set certificate={$config['certificate']}\n";
        $script .= "set cipher={$config['cipher']}\n";
        $script .= "set auth={$config['auth']}\n";

        return $script;
    }

    /**
     * Generate WireGuard configuration script
     *
     * @param array $config WireGuard configuration
     * @return string Generated Mikrotik script
     */
    public function generateWireguard(array $config): string
    {
        $script = "/interface wireguard\n";
        $script .= sprintf(
            "add name=%s listen-port=%s private-key=\"%s\"\n",
            $config['interface_name'],
            $config['listen_port'],
            $config['private_key']
        );

        $script .= "\n/interface wireguard peers\n";
        foreach ($config['peers'] as $peer) {
            $script .= sprintf(
                "add interface=%s public-key=\"%s\" allowed-address=%s endpoint-address=%s endpoint-port=%s\n",
                $config['interface_name'],
                $peer['public_key'],
                $peer['allowed_address'],
                $peer['endpoint_address'] ?? '',
                $peer['endpoint_port'] ?? ''
            );
        }

        $script .= "\n/ip address\n";
        $script .= sprintf(
            "add address=%s interface=%s\n",
            $config['ip_address'],
            $config['interface_name']
        );

        return $script;
    }

    /**
     * Generate VPN tunnel (IPSec) configuration script
     *
     * @param array $config IPSec configuration
     * @return string Generated Mikrotik script
     */
    public function generateVpnTunnel(array $config): string
    {
        $script = "/ip ipsec profile\n";
        $script .= sprintf(
            "add name=%s hash-algorithm=%s enc-algorithm=%s\n",
            $config['profile_name'],
            $config['hash_algorithm'],
            $config['encryption_algorithm']
        );

        $script .= "\n/ip ipsec peer\n";
        $script .= sprintf(
            "add address=%s profile=%s exchange-mode=%s\n",
            $config['peer_address'],
            $config['profile_name'],
            $config['exchange_mode']
        );

        $script .= "\n/ip ipsec proposal\n";
        $script .= sprintf(
            "add name=%s auth-algorithms=%s enc-algorithms=%s pfs-group=%s\n",
            $config['proposal_name'],
            $config['auth_algorithms'],
            $config['enc_algorithms'],
            $config['pfs_group']
        );

        return $script;
    }
}
