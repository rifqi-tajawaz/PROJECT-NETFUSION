<?php

namespace App\Http\Controllers\MikrotikSuite\Customization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    // Branding Theme
    public function brandingMaker()
    {
        return view('mikrotik-suite.customization.branding-theme.branding-maker');
    }

    public function logoAssets()
    {
        return view('mikrotik-suite.customization.branding-theme.logo-assets');
    }

    public function webfigSkin()
    {
        return view('mikrotik-suite.customization.branding-theme.webfig-skin');
    }

    // Hotspot Templates
    public function customTemplate()
    {
        return view('mikrotik-suite.customization.hotspot-templates.custom-template');
    }

    public function loginTemplateV6()
    {
        return view('mikrotik-suite.customization.hotspot-templates.login-template-v6');
    }

    public function loginTemplateV7()
    {
        return view('mikrotik-suite.customization.hotspot-templates.login-template-v7');
    }

    // Special Tools
    public function rscBeautifier()
    {
        return view('mikrotik-suite.customization.special-tools.rsc-beautifier');
    }

    public function supoutReader()
    {
        return view('mikrotik-suite.customization.special-tools.supout-reader');
    }

    public function wifiidAutoLogin()
    {
        return view('mikrotik-suite.customization.special-tools.wifiid-auto-login');
    }

    /**
     * Generate Branding Script
     */
    public function generateBranding(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'identity' => 'required|string',
            'note' => 'nullable|string',
            'ascii' => 'nullable|string',
        ]);

        $identity = $request->input('identity');
        $note = $request->input('note');
        $ascii = $request->input('ascii');

        // Escape quotes
        $safeNote = str_replace('"', '\\"', $note ?? '');
        $safeAscii = str_replace('"', '\\"', $ascii ?? '');
        $fullNote = ($safeAscii ? $safeAscii . "\\n" : "") . $safeNote;

        $script = "/system identity set name=\"" . str_replace('"', '\\"', $identity) . "\"\n";
        $script .= "/system note set note=\"{$fullNote}\" show-at-login=yes\n";

        // Optional: Banner MOTD file
        if ($safeAscii) {
            $script .= "/file print file=banner.txt\n";
            $script .= "/file set banner.txt contents=\"{$safeAscii}\"\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate WifiID Auto Login Script
     */
    public function generateWifiId(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'uid' => 'required|string',
            'pwd' => 'required|string',
            'iface' => 'required|string',
        ]);

        $u = $request->input('uid');
        $p = $request->input('pwd');
        // iface is valid but not used in the simple script example provided in JS, 
        // but maybe we can include it as a comment or strictly follow JS logic which didn't use it in the string?
        // JS logic: let script = `/tool fetch url="https://welcome2.wifi.id/login" http-method=post http-data="username=${u}&password=${p}" keep-result=no\n`;
        // It captured iface but didn't use it? Let's verify existing JS.

        // JS: const i = document.getElementById('iface').value;
        // JS: let script = `/tool fetch ...` -> i is unused.

        // I will just ignore iface for now in logic, but valid it to show we handle it.

        $script = "/tool fetch url=\"https://welcome2.wifi.id/login\" http-method=post http-data=\"username={$u}&password={$p}\" keep-result=no\n";
        $script .= "# Note: This is an example POST request. You may need to adjust the URL or parameters based on the current landing page.\n";
        $script .= "# Add this to Scheduler to run every 5 minutes to keep session alive.";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

