<?php

namespace App\Services\NetFusion\Modules;

use App\Services\NetFusion\MikhmonAPI;

class SystemRouterService
{
    protected $api;

    public function __construct(MikhmonAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Get System Resources
     */
    public function getResources()
    {
        $resource = $this->api->comm('/system/resource/print');
        return $resource[0] ?? [];
    }

    /**
     * Function to monitor traffic (aggregated)
     */
    public function getTraffic($interface)
    {
        return $this->api->comm('/interface/monitor-traffic', [
            'interface' => $interface,
            'once' => 'true'
        ]);
    }

    /**
     * Get All Interfaces
     */
    public function getInterfaces()
    {
        return $this->api->comm('/interface/print');
    }

    /**
     * Helper to get WAN interface (simple heuristic)
     */
    public function getWanInterface()
    {
        // Simplistic approach: find running interface that is not bridge (usually) or just first running
        $interfaces = $this->getInterfaces();
        foreach ($interfaces as $iface) {
            if ($iface['running'] == 'true' && $iface['disabled'] == 'false') {
                return $iface['name']; // Just return first running interface for now
            }
        }
        return 'ether1'; // Fallback
    }

    /**
     * Reboot Router
     */
    public function reboot()
    {
        return $this->api->comm('/system/reboot');
    }

    /**
     * Shutdown Router
     */
    public function shutdown()
    {
        return $this->api->comm('/system/shutdown');
    }

    /**
     * Get Router Identity
     */
    public function getIdentity()
    {
        $identity = $this->api->comm('/system/identity/print');
        return $identity[0] ?? ['name' => 'MikroTik'];
    }

    /**
     * Format bytes to human readable format
     */
    public function formatBytes($size, $precision = 2)
    {
        if ((float) $size == 0)
            return '0B';

        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Calculate memory usage percentage
     */
    public function calculateMemoryUsage($resource)
    {
        $total = $resource['total-memory'] ?? 1;
        $free = $resource['free-memory'] ?? 0;
        $used = $total - $free;

        return $total > 0 ? round(($used / $total) * 100, 1) : 0;
    }

    /**
     * Get Schedulers
     */
    public function getSchedulers()
    {
        return $this->api->comm('/system/scheduler/print');
    }

    /**
     * Add Scheduler
     */
    public function addScheduler($data)
    {
        return $this->api->comm('/system/scheduler/add', $data);
    }

    /**
     * Remove Scheduler
     */
    public function removeScheduler($id)
    {
        return $this->api->comm('/system/scheduler/remove', ['.id' => $id]);
    }

    /**
     * Enable/Disable Scheduler
     */
    public function toggleScheduler($id, $disable = true)
    {
        $action = $disable ? 'disable' : 'enable';
        return $this->api->comm('/system/scheduler/' . $action, ['.id' => $id]);
    }

    /**
     * Get DHCP Leases
     */
    public function getDhcpLeases()
    {
        return $this->api->comm('/ip/dhcp-server/lease/print');
    }

    /**
     * Make DHCP Lease Static
     */
    public function makeDhcpStatic($id)
    {
        return $this->api->comm('/ip/dhcp-server/lease/make-static', ['.id' => $id]);
    }

    /**
     * Remove DHCP Lease
     */
    public function removeDhcpLease($id)
    {
        return $this->api->comm('/ip/dhcp-server/lease/remove', ['.id' => $id]);
    }

    /**
     * Get System Logs
     */
    public function getLogs($limit = 100, $topic = null, $search = null)
    {
        // Fetch logs (Mikrotik returns oldest first by default)
        // We can't easily limit "last N" via API without fetching all or knowing count.
        // But for performance, we just fetch. If we could use 'from' it would be better.
        // Assuming we fetch all and slice for now, or use buffer if available.
        // Optimization: Use 'detail' to potentially limit? No.

        $logs = $this->api->comm('/log/print');

        // Sort newest first
        $logs = array_reverse($logs);

        // Filter
        if ($topic || $search) {
            $logs = array_filter($logs, function ($log) use ($topic, $search) {
                // Topic Filter
                if ($topic && isset($log['topics'])) {
                    // Check if requested topic exists in the comma-separated topics
                    // or if it matches one of the topic words.
                    // $log['topics'] is typically "system,info"
                    if (!str_contains($log['topics'], $topic)) {
                        return false;
                    }
                }

                // Search Filter (Message)
                if ($search && isset($log['message'])) {
                    if (stripos($log['message'], $search) === false) {
                        return false;
                    }
                }

                return true;
            });
        }

        // Limit
        return array_slice($logs, 0, $limit);
    }
}

