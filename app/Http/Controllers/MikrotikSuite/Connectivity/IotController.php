<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Connectivity;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class IotController extends Controller
{
    /**
     * MQTT Publisher Configuration
     */
    public function mqttConfig(): View
    {
        return view('mikrotik-suite.connectivity.iot.mqtt');
    }

    /**
     * MQTT Publisher Configuration (Legacy/Specific)
     */
    public function mqttPublisher(): View
    {
        // For now use the same, or assume basic config covers it
        return view('mikrotik-suite.connectivity.iot.mqtt');
    }

    /**
     * LoRaWAN Gateway Configuration
     */
    public function lorawanGateway(): View
    {
        return view('mikrotik-suite.connectivity.iot.lorawan');
    }
    /**
     * Generate MQTT Config Script
     */
    public function generateMqtt(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'url' => 'required|string',
            'cid' => 'required|string',
        ]);

        $name = $request->input('name');
        $url = $request->input('url');
        $cid = $request->input('cid');

        $script = "/iot mqtt brokers add name=\"{$name}\" url=\"{$url}\" client-id=\"{$cid}\"";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
    /**
     * Generate LoRaWAN Config Script
     */
    public function generateLorawan(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'addr' => 'required|string',
            'ports' => 'required|numeric',
        ]);

        $name = $request->input('name');
        $addr = $request->input('addr');
        $ports = $request->input('ports');

        $script = "/lora servers add name=\"{$name}\" address=\"{$addr}\" up-port={$ports} down-port={$ports}\n";
        $script .= "/lora traffic set servers=\"{$name}\"";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

