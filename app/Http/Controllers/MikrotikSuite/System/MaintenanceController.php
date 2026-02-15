<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class MaintenanceController extends Controller
{
    public function autoUpgrade(): View
    {
        return view('mikrotik-suite.system.maintenance.auto-upgrade');
    }

    public function generateAutoUpgrade(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'channel' => 'required|in:stable,long-term,testing,development',
            'time' => 'required|string',
        ]);

        $channel = $request->input('channel');
        $time = $request->input('time');

        // Simple script to check update and upgrade
        $script = "/system package update set channel=$channel\n";
        $script .= "/system scheduler add name=\"auto-upgrade\" start-time=$time interval=1d on-event={\n";
        $script .= "    /system package update check-for-updates\n";
        $script .= "    :if ([/system package update get status] = \"New version is available\") do={\n";
        $script .= "        /system package update install\n";
        $script .= "    }\n";
        $script .= "}";

        return response()->json(['script' => $script]);
    }

    public function backupAutomation(): View
    {
        return view('mikrotik-suite.system.maintenance.backup-automation');
    }

    public function generateBackupAutomation(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'interval' => 'required|string',
        ]);

        $email = $request->input('email');
        $interval = $request->input('interval');

        $script = "/system scheduler add name=\"auto-backup-email\" interval=$interval on-event={\n";
        $script .= "    /system backup save name=email-backup\n";
        $script .= "    /export file=email-export\n";
        $script .= "    /tool e-mail send to=\"$email\" subject=\"[MikroTik] Backup\" body=\"Configuration backup attached.\" file=email-backup.backup,email-export.rsc\n";
        $script .= "}";

        return response()->json(['script' => $script]);
    }

    public function userManagement(): View
    {
        return view('mikrotik-suite.system.maintenance.user-management');
    }

    public function generateUserManagement(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => 'required|string|alpha_dash',
            'password' => 'required|string|min:4',
            'group' => 'required|in:full,read,write',
            'address' => 'nullable|string',
        ]);

        $user = $request->input('username');
        $pass = $request->input('password');
        $group = $request->input('group');
        $address = $request->input('address');

        $script = "/user add name=\"$user\" password=\"$pass\" group=$group";

        if ($address) {
            $script .= " address=$address";
        }

        return response()->json(['script' => $script]);
    }
}

