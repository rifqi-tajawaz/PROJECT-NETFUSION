<?php

namespace App\Http\Controllers\MikrotikSuite\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadsController extends Controller
{
    // Unified Index
    public function index()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    // Desktop Apps
    public function bandwidthTest()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function netinstall()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function syslogDaemon()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function theDude()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function trafficCounter()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function winboxLinux()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function winboxMacos()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function winboxWindows()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    // Mobile Apps
    public function beaconManager()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function mikrotikHome()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function vpnClient()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function winboxMobile()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    // RouterOS Firmware
    public function routerosIndonesia()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }

    public function routerosOfficial()
    {
        return view('mikrotik-suite.resources.downloads.index');
    }
}

