<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class IdentityController extends Controller
{
    public function identity(): View
    {
        return view('mikrotik-suite.system.identity.identity');
    }

    public function generateIdentity(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'identity' => 'required|string|max:255',
        ]);

        $identity = $request->input('identity');
        $script = "/system identity set name=\"$identity\"";

        return response()->json(['script' => $script]);
    }

    public function banner(): View
    {
        return view('mikrotik-suite.system.identity.banner');
    }

    public function generateBanner(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'banner' => 'required|string',
        ]);

        // Escape quotes for routeros script
        $banner = str_replace('"', '\\"', $request->input('banner'));
        $script = "/system note set note=\"$banner\"";

        return response()->json(['script' => $script]);
    }
}

