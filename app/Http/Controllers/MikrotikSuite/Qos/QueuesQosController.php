<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Qos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class QueuesQosController extends Controller
{
    // Bandwidth Sharing
    public function burstConfiguration(): View
    {
        return view('mikrotik-suite.qos.shaping.burst-configuration');
    }

    public function generateBurst(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'limit' => 'required|string',
            'burstLimit' => 'required|string',
            'burstThreshold' => 'required|string',
            'burstTime' => 'required|numeric',
        ]);

        $limit = $request->input('limit');
        $bl = $request->input('burstLimit');
        $bt = $request->input('burstThreshold');
        $time = $request->input('burstTime');

        $script = "/queue simple add name=\"Burst_Queue\" target=192.168.88.0/24 max-limit=$limit/$limit burst-limit=$bl/$bl burst-threshold=$bt/$bt burst-time=$time/$time\n";

        return response()->json(['script' => $script]);
    }

    public function qosPriority(): View
    {
        return view('mikrotik-suite.qos.shaping.qos-priority');
    }

    public function generatePriority(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'qname' => 'required|string',
            'prio' => 'required|integer|min:1|max:8',
        ]);

        $qname = $request->input('qname');
        $prio = $request->input('prio');

        $script = "/queue simple set [find name=\"$qname\"] priority=$prio/$prio\n";

        return response()->json(['script' => $script]);
    }

    public function sharedBandwidth(): View
    {
        return view('mikrotik-suite.qos.shaping.shared-bandwidth');
    }

    public function generateSharedBandwidth(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'total' => 'required|string',
            'clients' => 'required|string',
        ]);

        $name = $request->input('name');
        $total = $request->input('total');
        $clients_raw = $request->input('clients');

        $script = "/queue simple add name=\"$name\" target=\"\" max-limit=$total/$total\n";

        $clients = explode(PHP_EOL, $clients_raw);
        $i = 1;
        foreach ($clients as $ip) {
            $ip = trim($ip);
            if (empty($ip))
                continue;
            $script .= "/queue simple add name=\"$name-client$i\" target=\"$ip\" parent=\"$name\" max-limit=$total/$total\n";
            $i++;
        }

        return response()->json(['script' => $script]);
    }

    // Queue Configuration
    public function pcqConfiguration(): View
    {
        return view('mikrotik-suite.qos.queues.pcq-configuration');
    }

    public function generatePcq(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'rate' => 'required|string',
            'class' => 'required|string',
        ]);

        $name = $request->input('name');
        $rate = $request->input('rate');
        $class = $request->input('class');

        $script = "/queue type add name=\"$name\" kind=pcq pcq-rate=$rate pcq-classifier=$class\n";

        return response()->json(['script' => $script]);
    }

    public function queueOptimizer(): View
    {
        return view('mikrotik-suite.qos.queues.queue-optimizer');
    }

    public function queueTree(): View
    {
        return view('mikrotik-suite.qos.queues.queue-tree');
    }

    public function generateQueueTree(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'parentName' => 'required|string',
            'parentIface' => 'required|string',
            'totalBw' => 'required|string',
            'childName' => 'nullable|string',
            'childLimit' => 'nullable|string',
            'packetMark' => 'nullable|string',
        ]);

        $pName = $request->input('parentName');
        $pIface = $request->input('parentIface');
        $total = $request->input('totalBw');

        $script = "/queue tree add name=\"$pName\" parent=\"$pIface\" max-limit=$total\n";

        if ($request->filled('childName') && $request->filled('childLimit') && $request->filled('packetMark')) {
            $cName = $request->input('childName');
            $cLimit = $request->input('childLimit');
            $mark = $request->input('packetMark');
            $script .= "/queue tree add name=\"$cName\" parent=\"$pName\" packet-mark=\"$mark\" limit-at=$cLimit max-limit=$cLimit\n";
        }

        return response()->json(['script' => $script]);
    }

    public function simpleQueue(): View
    {
        return view('mikrotik-suite.qos.queues.simple-queue');
    }

    public function generateSimpleQueue(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'target' => 'required|string',
            'maxUp' => 'nullable|string',
            'maxDown' => 'nullable|string',
        ]);

        $name = $request->input('name');
        $target = $request->input('target');
        $up = $request->input('maxUp') ?: '0';
        $down = $request->input('maxDown') ?: '0';

        $script = "/queue simple add name=\"$name\" target=\"$target\" max-limit=$up/$down\n";

        return response()->json(['script' => $script]);
    }

    public function tokenBucket(): View
    {
        return view('mikrotik-suite.qos.shaping.token-bucket');
    }
}
