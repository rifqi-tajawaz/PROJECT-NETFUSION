<?php

namespace App\Services\MikrotikSuite\ScriptGenerator;

/**
 * Service for generating routing and gateway scripts
 */
class RoutingGeneratorService
{
    /**
     * Generate BGP configuration script
     *
     * @param array $config BGP configuration
     * @return string Generated Mikrotik script
     */
    public function generateBgp(array $config): string
    {
        $script = "/routing bgp instance\n";
        $script .= sprintf(
            "set default as=%s router-id=%s\n",
            $config['as_number'],
            $config['router_id']
        );

        $script .= "\n/routing bgp peer\n";
        foreach ($config['peers'] as $peer) {
            $script .= sprintf(
                "add name=%s remote-address=%s remote-as=%s\n",
                $peer['name'],
                $peer['remote_address'],
                $peer['remote_as']
            );
        }

        $script .= "\n/routing bgp network\n";
        foreach ($config['networks'] as $network) {
            $script .= "add network={$network}\n";
        }

        return $script;
    }

    /**
     * Generate OSPF configuration script
     *
     * @param array $config OSPF configuration
     * @return string Generated Mikrotik script
     */
    public function generateOspf(array $config): string
    {
        $script = "/routing ospf instance\n";
        $script .= sprintf(
            "set default router-id=%s\n",
            $config['router_id']
        );

        $script .= "\n/routing ospf network\n";
        foreach ($config['networks'] as $network) {
            $script .= sprintf(
                "add network=%s area=%s\n",
                $network['network'],
                $network['area']
            );
        }

        return $script;
    }

    /**
     * Generate static route script
     *
     * @param array $routes Array of static routes
     * @return string Generated Mikrotik script
     */
    public function generateStaticRoutes(array $routes): string
    {
        $script = "/ip route\n";
        $script .= "# Static Routes\n";

        foreach ($routes as $route) {
            $script .= sprintf(
                "add dst-address=%s gateway=%s distance=%s comment=\"%s\"\n",
                $route['destination'],
                $route['gateway'],
                $route['distance'] ?? 1,
                $route['comment'] ?? 'Static Route'
            );
        }

        return $script;
    }

    /**
     * Generate failover gateway script
     *
     * @param array $config Failover configuration
     * @return string Generated Mikrotik script
     */
    public function generateFailoverGateway(array $config): string
    {
        $script = "/ip route\n";
        $script .= "# Failover Gateway Configuration\n";

        // Primary gateway
        $script .= sprintf(
            "add dst-address=0.0.0.0/0 gateway=%s distance=1 check-gateway=ping comment=\"Primary Gateway\"\n",
            $config['primary_gateway']
        );

        // Backup gateway
        $script .= sprintf(
            "add dst-address=0.0.0.0/0 gateway=%s distance=2 check-gateway=ping comment=\"Backup Gateway\"\n",
            $config['backup_gateway']
        );

        return $script;
    }

    /**
     * Generate policy routing script
     *
     * @param array $policies Array of routing policies
     * @return string Generated Mikrotik script
     */
    public function generatePolicyRouting(array $policies): string
    {
        $script = "/ip firewall mangle\n";
        $script .= "# Policy Routing - Mark Connections\n";

        foreach ($policies as $policy) {
            $script .= sprintf(
                "add chain=prerouting src-address=%s action=mark-connection new-connection-mark=%s passthrough=yes\n",
                $policy['source_network'],
                $policy['connection_mark']
            );

            $script .= sprintf(
                "add chain=prerouting connection-mark=%s action=mark-routing new-routing-mark=%s passthrough=no\n",
                $policy['connection_mark'],
                $policy['routing_mark']
            );
        }

        $script .= "\n/ip route\n";
        $script .= "# Policy Routing - Routes\n";

        foreach ($policies as $policy) {
            $script .= sprintf(
                "add dst-address=0.0.0.0/0 gateway=%s routing-mark=%s comment=\"%s\"\n",
                $policy['gateway'],
                $policy['routing_mark'],
                $policy['comment'] ?? 'Policy Route'
            );
        }

        return $script;
    }
}
