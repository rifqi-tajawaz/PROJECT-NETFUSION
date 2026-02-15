<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class FirstTimeController extends Controller
{
    public function index(): View
    {
        return view('mikrotik-suite.system.first-time-wizard');
    }

    public function generateFirstTime(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'identity' => 'required|string',
            'admin_password' => 'required|string|min:5',
            'timezone' => 'required|string',
            'dns_server' => 'required|string',
            'guest_ssid' => 'nullable|string',
            'guest_password' => 'nullable|string|min:8',
        ]);

        $identity = $request->input('identity');
        $id_password = $request->input('admin_password');
        $timezone = $request->input('timezone');
        $dns = $request->input('dns_server');
        $guest_ssid = $request->input('guest_ssid');
        $guest_pass = $request->input('guest_password');

        $script = "/system identity set name=\"$identity\"\n";
        $script .= "/user set [find name=admin] password=\"$id_password\"\n";
        $script .= "/system clock set time-zone-name=\"$timezone\"\n";
        $script .= "/ip dns set servers=\"$dns\" allow-remote-requests=yes\n";

        if ($guest_ssid) {
            $script .= "\n# Guest WiFi Setup\n";
            $script .= "/interface wireless security-profiles add name=\"guest-profile\" mode=dynamic-keys authentication-types=wpa2-psk wpa2-pre-shared-key=\"$guest_pass\"\n";
            $script .= "/interface wireless add name=\"wlan-guest\" master-interface=wlan1 ssid=\"$guest_ssid\" security-profile=\"guest-profile\" disabled=no\n";
        }

        $script .= ":put \"Basic setup applied.\"\n";

        return response()->json(['script' => $script]);
    }
}

