<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class SwitchingController extends Controller
{
    /**
     * Display Bonding (LACP) configurator
     */
    public function bonding(): View
    {
        return view('mikrotik-suite.network.switching.bonding');
    }

    /**
     * Display Spanning Tree Protocol (STP) configurator
     */
    public function spanningTree(): View
    {
        return view('mikrotik-suite.network.switching.spanning-tree');
    }

    // =========================================================================
    // GENERATION LOGIC
    // =========================================================================

    public function generateBonding(Request $request): JsonResponse
    {
        $request->validate([
            'bonding_name' => 'required|string|max:50',
            'slaves' => 'required|string',
            'mode' => 'required|string',
            'hash_policy' => 'nullable|string',
        ]);

        $name = $request->input('bonding_name');
        $slaves = $request->input('slaves');
        $mode = $request->input('mode');
        $hash = $request->input('hash_policy');

        $script = "/interface bonding add name=\"{$name}\" slaves={$slaves} mode={$mode}";
        if ($mode === '802.3ad') {
            $script .= " transmit-hash-policy={$hash} min-links=1";
        }
        $script .= "\n/interface bonding set \"{$name}\" disabled=no";

        return response()->json(['status' => 'success', 'script' => $script]);
    }

    public function generateSpanningTree(Request $request): JsonResponse
    {
        $request->validate([
            'bridge_name' => 'required|string',
            'protocol_mode' => 'required|string',
            'priority' => 'required|string',
        ]);

        $br = $request->input('bridge_name');
        $mode = $request->input('protocol_mode');
        $prio = $request->input('priority');

        if ($prio === 'custom') {
            $prio = '0x8000'; // Default safe fallback
        }

        $script = "/interface bridge set \"{$br}\" protocol-mode={$mode}";
        if ($mode !== 'none') {
            $script .= " priority={$prio}";
        }
        $script .= "\n# Tip: Lower priority = More likely to be Root Bridge.";

        return response()->json(['status' => 'success', 'script' => $script]);
    }
}

