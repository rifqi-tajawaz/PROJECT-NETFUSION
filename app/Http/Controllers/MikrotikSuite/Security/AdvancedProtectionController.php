<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Security;

use App\Http\Controllers\Controller;
use App\Services\MikrotikSuite\Security\AdvancedProtectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AdvancedProtectionController extends Controller
{
    public function index(): View
    {
        return view('mikrotik-suite.security.advanced-protection');
    }

    public function generate(Request $request, AdvancedProtectionService $service): JsonResponse
    {
        $script = $service->generateFirewallScript($request->all());

        return response()->json(['script' => $script]);
    }

    public function generatePortKnocking(Request $request, AdvancedProtectionService $service): JsonResponse
    {
        $request->validate([
            'mode' => 'required|in:icmp,port',
            'interface' => 'required|string',
            'ports' => 'required|string',
            'duration' => 'required|string',
        ]);

        $script = $service->generatePortKnockingScript($request->all());

        return response()->json(['script' => $script]);
    }
}
