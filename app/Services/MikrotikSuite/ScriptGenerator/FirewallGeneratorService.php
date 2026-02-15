<?php

namespace App\Services\MikrotikSuite\ScriptGenerator;

/**
 * Service for generating Mikrotik firewall and NAT scripts
 */
class FirewallGeneratorService
{
    /**
     * Generate fasttrack rules script
     *
     * @param array $options Configuration options
     * @return string Generated Mikrotik script
     */
    public function generateFasttrackRules(array $options = []): string
    {
        $script = "/ip firewall filter\n";
        $script .= "# Fasttrack Rules for Performance Optimization\n";
        $script .= "add action=fasttrack-connection chain=forward connection-state=established,related comment=\"Fasttrack Established/Related\"\n";
        $script .= "add action=accept chain=forward connection-state=established,related comment=\"Accept Established/Related\"\n";

        if (isset($options['add_invalid_drop']) && $options['add_invalid_drop']) {
            $script .= "add action=drop chain=forward connection-state=invalid comment=\"Drop Invalid\"\n";
        }

        return $script;
    }

    /**
     * Generate filter rules script
     *
     * @param array $rules Array of filter rules
     * @return string Generated Mikrotik script
     */
    public function generateFilterRules(array $rules): string
    {
        $script = "/ip firewall filter\n";
        $script .= "# Custom Filter Rules\n";

        foreach ($rules as $rule) {
            $script .= $this->buildFilterRule($rule);
        }

        return $script;
    }

    /**
     * Generate mangle rules script
     *
     * @param array $rules Array of mangle rules
     * @return string Generated Mikrotik script
     */
    public function generateMangleRules(array $rules): string
    {
        $script = "/ip firewall mangle\n";
        $script .= "# Mangle Rules for Traffic Marking\n";

        foreach ($rules as $rule) {
            $script .= $this->buildMangleRule($rule);
        }

        return $script;
    }

    /**
     * Generate port forwarding script
     *
     * @param array $forwards Array of port forwarding rules
     * @return string Generated Mikrotik script
     */
    public function generatePortForwarding(array $forwards): string
    {
        $script = "/ip firewall nat\n";
        $script .= "# Port Forwarding Rules\n";

        foreach ($forwards as $forward) {
            $script .= sprintf(
                "add action=dst-nat chain=dstnat dst-port=%s protocol=%s to-addresses=%s to-ports=%s comment=\"%s\"\n",
                $forward['external_port'],
                $forward['protocol'] ?? 'tcp',
                $forward['internal_ip'],
                $forward['internal_port'] ?? $forward['external_port'],
                $forward['comment'] ?? 'Port Forward'
            );
        }

        return $script;
    }

    /**
     * Build individual filter rule
     *
     * @param array $rule Rule configuration
     * @return string Rule line
     */
    private function buildFilterRule(array $rule): string
    {
        $parts = ["add"];

        if (isset($rule['chain'])) {
            $parts[] = "chain={$rule['chain']}";
        }

        if (isset($rule['action'])) {
            $parts[] = "action={$rule['action']}";
        }

        if (isset($rule['protocol'])) {
            $parts[] = "protocol={$rule['protocol']}";
        }

        if (isset($rule['src_address'])) {
            $parts[] = "src-address={$rule['src_address']}";
        }

        if (isset($rule['dst_address'])) {
            $parts[] = "dst-address={$rule['dst_address']}";
        }

        if (isset($rule['comment'])) {
            $parts[] = "comment=\"{$rule['comment']}\"";
        }

        return implode(' ', $parts) . "\n";
    }

    /**
     * Build individual mangle rule
     *
     * @param array $rule Rule configuration
     * @return string Rule line
     */
    private function buildMangleRule(array $rule): string
    {
        $parts = ["add"];

        if (isset($rule['chain'])) {
            $parts[] = "chain={$rule['chain']}";
        }

        if (isset($rule['action'])) {
            $parts[] = "action={$rule['action']}";
        }

        if (isset($rule['new_packet_mark'])) {
            $parts[] = "new-packet-mark={$rule['new_packet_mark']}";
        }

        if (isset($rule['passthrough'])) {
            $parts[] = "passthrough=" . ($rule['passthrough'] ? 'yes' : 'no');
        }

        if (isset($rule['comment'])) {
            $parts[] = "comment=\"{$rule['comment']}\"";
        }

        return implode(' ', $parts) . "\n";
    }
}
