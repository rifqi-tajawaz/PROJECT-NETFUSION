<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class SecurityController extends Controller
{
    /**
     * Security Dashboard
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('mikrotik-suite.security.hide-router-identity');
    }

    public function advancedProtection(): View
    {
        return view('mikrotik-suite.security.advanced-protection');
    }

    public function autoBackup(): View
    {
        return view('mikrotik-suite.security.auto-backup');
    }


    public function dhcpRogueDetection(): View
    {
        return view('mikrotik-suite.security.dhcp-rogue');
    }

    public function portKnocking(): View
    {
        return view('mikrotik-suite.security.advanced.port-knocking');
    }

    public function contentFilter(): View
    {
        return view('mikrotik-suite.security.content-filter');
    }

    public function hideRouterIdentity(): View
    {
        return view('mikrotik-suite.security.hide-identity');
    }

    public function mangleObfuscator(): View
    {
        return view('mikrotik-suite.security.mangle-obfuscator');
    }

}

