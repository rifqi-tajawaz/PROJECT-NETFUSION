<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AutomationController extends Controller
{
    /**
     * Auto Reboot Scheduler
     */
    public function autoReboot(): View
    {
        return view('mikrotik-suite.system.automation.auto-reboot');
    }

    public function generateAutoReboot(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'time' => 'required|string',
            'interval' => 'required|string',
        ]);

        $time = $request->input('time');
        $interval = $request->input('interval');

        $script = "/system scheduler add name=\"auto-reboot\" start-time=$time interval=$interval on-event=\"/system reboot\"";

        return response()->json(['script' => $script]);
    }

    /**
     * Bandwidth Scheduler
     */
    public function bandwidthScheduler(): View
    {
        return view('mikrotik-suite.system.automation.bandwidth-scheduler');
    }

    public function generateBandwidth(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'queue_name' => 'required|string',
            'day_limit' => 'required|string',
            'day_time' => 'required|string',
            'night_limit' => 'required|string',
            'night_time' => 'required|string',
        ]);

        $queue = $request->input('queue_name');
        $dayLimit = $request->input('day_limit');
        $dayTime = $request->input('day_time');
        $nightLimit = $request->input('night_limit');
        $nightTime = $request->input('night_time');

        // Scheduler for Day Limit
        $script = "/system scheduler add name=\"bw-day-$queue\" start-time=$dayTime interval=1d on-event=\"/queue simple set [find name=\\\"$queue\\\"] max-limit=$dayLimit\"\n";

        // Scheduler for Night Limit
        $script .= "/system scheduler add name=\"bw-night-$queue\" start-time=$nightTime interval=1d on-event=\"/queue simple set [find name=\\\"$queue\\\"] max-limit=$nightLimit\"";

        return response()->json(['script' => $script]);
    }

    /**
     * Advanced Scheduler Builder
     */
    public function schedulerBuilder(): View
    {
        return view('mikrotik-suite.system.automation.scheduler-builder');
    }

    public function generateScheduler(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|alpha_dash',
            'interval' => 'required|string',
            'start_time' => 'required|string',
            'on_event' => 'required|string',
        ]);

        $name = $request->input('name');
        $interval = $request->input('interval');
        $startTime = $request->input('start_time');
        $event = $request->input('on_event'); // Code content

        // Escape quotes in event body if needed, or wrap in {} in RouterOS
        // Using {} is safer for multi-line scripts
        $script = "/system scheduler add name=\"$name\" start-time=$startTime interval=$interval on-event={\n$event\n}";

        return response()->json(['script' => $script]);
    }
}

