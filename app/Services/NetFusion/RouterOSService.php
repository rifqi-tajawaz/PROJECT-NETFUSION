<?php

namespace App\Services\NetFusion;

use App\Services\NetFusion\MikhmonAPI;
use Illuminate\Support\Facades\Session;

class RouterOSService
{
    public $api;

    // Injecting Automatically Connected API
    public function __construct(MikhmonAPI $api)
    {
        $this->api = $api;
    }

    // Connection Helper used by SettingsController
    public function connect($ip, $user, $pass, $port = 8728)
    {
        return $this->api->connect($ip, $user, $pass, $port);
    }

    // Helper to check/restore connection explicitly (optional now, but keeping for compatibility)
    public function checkConnection()
    {
        if ($this->api->connected) {
            return true;
        }
        // Connection is now handled by AppServiceProvider logic when MikhmonAPI is resolved,
        // so if we are here and not connected, maybe session expired or failed.
        return false;
    }
}
