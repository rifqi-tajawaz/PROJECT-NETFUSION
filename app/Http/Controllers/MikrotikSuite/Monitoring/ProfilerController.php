<?php

namespace App\Http\Controllers\MikrotikSuite\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilerController extends Controller
{
    /**
     * CPU Profiling Tools
     */
    public function cpuProfiling()
    {
        return view('mikrotik-suite.monitoring.troubleshooting.cpu-profiling');
    }

    /**
     * Graphing & Monitoring
     */
    public function graphing()
    {
        return view('mikrotik-suite.monitoring.troubleshooting.graphing');
    }

    /**
     * Generate CPU Profiling Command
     */
    public function generateCpuProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'duration' => 'required|string',
            'cpu_core' => 'required|string',
            'sort_by' => 'required|in:total,name,usage',
        ]);

        $dur = $request->input('duration');
        $cpu = $request->input('cpu_core');
        $sort = $request->input('sort_by');

        $script = "/tool profile duration={$dur}";

        if ($cpu !== 'all') {
            $script .= " cpu={$cpu}";
        }

        $script .= " sort-by={$sort}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Graphing Config Script
     */
    public function generateGraphing(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            // checkboxes
            'interface_target' => 'nullable|string',
            'allowed_address' => 'required|string',
        ]);

        $resGraph = $request->boolean('resource_graph');
        $ifaceGraph = $request->boolean('interface_graph');
        $queueGraph = $request->boolean('queue_graph');

        $iface = $request->input('interface_target');
        $allow = $request->input('allowed_address');

        $script = "";

        if ($resGraph) {
            $script .= "/tool graphing resource add allow-address={$allow} store-on-disk=yes\n";
        }

        if ($ifaceGraph && $iface) {
            $script .= "/tool graphing interface add interface={$iface} allow-address={$allow} store-on-disk=yes\n";
        }

        if ($queueGraph) {
            $script .= "/tool graphing queue add simple-queue=all allow-address={$allow} store-on-disk=yes\n";
        }

        if (empty($script)) {
            $script = "# No graphs selected.";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

