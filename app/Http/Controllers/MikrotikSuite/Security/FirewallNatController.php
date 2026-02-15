<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Security;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class FirewallNatController extends Controller
{
    /**
     * Display fasttrack rules configuration wizard
     */
    public function fasttrackRules(): View
    {
        return view('mikrotik-suite.security.firewall.fasttrack-rules');
    }

    /**
     * Display filter rules configuration wizard
     */
    public function filterRules(): View
    {
        return view('mikrotik-suite.security.firewall.filter-rules');
    }

    /**
     * Display mangle rules configuration wizard
     */
    public function mangleRules(): View
    {
        return view('mikrotik-suite.security.firewall.mangle-rules');
    }

    /**
     * Display port forwarding configuration wizard
     */
    public function portForwarding(): View
    {
        return view('mikrotik-suite.security.firewall.port-forwarding');
    }

    /**
     * Display port static routing configuration wizard
     */
    public function portStaticRouting(): View
    {
        return view('mikrotik-suite.security.firewall.port-static-routing');
    }
}

