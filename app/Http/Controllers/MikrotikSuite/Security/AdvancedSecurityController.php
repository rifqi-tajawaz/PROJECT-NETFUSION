<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AdvancedSecurityController extends Controller
{
    public function inputChain(): View
    {
        return view('mikrotik-suite.security.advanced.input-chain');
    }

    public function forwardChain(): View
    {
        return view('mikrotik-suite.security.advanced.forward-chain');
    }

    public function ddosProtection(): View
    {
        return view('mikrotik-suite.security.advanced.ddos-protection');
    }

    public function portKnocking(): View
    {
        return view('mikrotik-suite.security.advanced.port-knocking');
    }

    public function bogonIps(): View
    {
        return view('mikrotik-suite.security.advanced.bogon-ips');
    }

    public function layer7Protocol(): View
    {
        return view('mikrotik-suite.security.advanced.layer7-protocol');
    }

    public function portForwarding(): View
    {
        return view('mikrotik-suite.security.firewall.port-forwarding');
    }

    public function masquerade(): View
    {
        return view('mikrotik-suite.security.firewall.masquerade');
    }
}

