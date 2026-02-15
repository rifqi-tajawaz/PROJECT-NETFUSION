<?php

namespace App\Http\Controllers\MikrotikSuite\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingIntegrationController extends Controller
{
    public function daloradius()
    {
        // Daloradius is just a frontend for FreeRADIUS, so same config often applies
        // But we can create separate views or reuse. Let's reuse for now or just point to same logic.
        return view('mikrotik-suite.resources.billing.daloradius');
    }

    public function dmaRadius()
    {
        return view('mikrotik-suite.resources.billing.dma-radius');
    }

    public function freeradius()
    {
        return view('mikrotik-suite.resources.billing.freeradius');
    }

    public function mikhmon()
    {
        return view('mikrotik-suite.resources.billing.mikhmon');
    }

    /**
     * Generate Mikhmon Integration Script
     */
    public function generateMikhmon(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user' => 'required|string',
            'pass' => 'required|string',
            'group' => 'required|string',
            'apiService' => 'nullable|boolean',
        ]);

        $user = $request->input('user');
        $pass = $request->input('pass');
        $group = $request->input('group');
        $api = $request->input('apiService'); // true or null

        // Script to create user
        $script = "/user add name=\"$user\" group=\"$group\" password=\"$pass\" comment=\"Mikhmon User\"\n";

        // Enable API service if requested
        if ($api) {
            $script .= "/ip service set api disabled=no port=8728 address=0.0.0.0/0\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Generate FreeRADIUS Script
     */
    public function generateFreeradius(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'server' => 'required|ipv4',
            'secret' => 'required|string',
            'services' => 'array',
            'timeout' => 'required|integer|min:100',
        ]);

        $server = $request->input('server');
        $secret = $request->input('secret');
        $services = $request->input('services', []); // Expecting array e.g. ['hotspot','ppp']
        $timeout = $request->input('timeout');

        // Convert array to comma-separated string for 'service' param
        // RouterOS format: service=hotspot,ppp,login
        $serviceList = implode(',', $services);
        if (empty($serviceList)) {
            // Default or error? Let's generic it.
            $serviceList = "hotspot";
        }

        $script = "/radius add address=\"$server\" secret=\"$secret\" service=\"$serviceList\" timeout=\"{$timeout}ms\" comment=\"FreeRADIUS\"\n";
        $script .= "/radius incoming set accept=yes port=3799\n"; // Usually required for CoA

        return response()->json(['script' => $script]);
    }
}

