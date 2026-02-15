<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Wireless;

use App\Http\Controllers\Controller;
use App\Services\MikrotikSuite\Wireless\WirelessLinkPlannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class WirelessLinkPlannerController extends Controller
{
    protected WirelessLinkPlannerService $plannerService;

    public function __construct(WirelessLinkPlannerService $plannerService)
    {
        $this->plannerService = $plannerService;
    }

    public function index(): View
    {
        return view('mikrotik-suite.tools.wireless.link-planner');
    }

    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'site_a_height' => 'required|numeric|min:0',
            'site_a_tx_power' => 'required|numeric',
            'site_a_ant_gain' => 'required|numeric',
            'site_a_cable_loss' => 'nullable|numeric|min:0',
            'site_b_height' => 'required|numeric|min:0',
            'site_b_rx_sens' => 'required|numeric',
            'site_b_ant_gain' => 'required|numeric',
            'site_b_cable_loss' => 'nullable|numeric|min:0',
            'distance' => 'required|numeric|min:0.01',
            'frequency' => 'required|numeric|min:1',
            'rain_rate' => 'nullable|numeric|min:0',
            'protocol' => 'nullable|string',
            'channel_width' => 'nullable|numeric',
            'polarization' => 'nullable|string',
            'obstacles' => 'nullable|array',
            'obstacles.*.distance' => 'required|numeric|min:0',
            'obstacles.*.height' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->plannerService->calculate($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
}
