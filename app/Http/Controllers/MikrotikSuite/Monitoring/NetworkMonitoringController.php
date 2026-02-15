<?php

namespace App\Http\Controllers\MikrotikSuite\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NetworkMonitoringController extends Controller
{
    public function attix5Monitor()
    {
        // Treat as "Backup Service Monitor"
        return view('mikrotik-suite.monitoring.network-monitoring.attix5-monitor');
    }

    public function netwatchAlert()
    {
        return view('mikrotik-suite.monitoring.network-monitoring.netwatch-alert');
    }

    public function trafficMonitor()
    {
        return view('mikrotik-suite.monitoring.network-monitoring.traffic-monitor');
    }

    public function trafficSniffer()
    {
        return view('mikrotik-suite.monitoring.network-monitoring.traffic-sniffer');
    }

    /**
     * Generate Netwatch Alert Script
     */
    public function generateNetwatchAlert(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'host' => 'required|ipv4',
            'interval' => 'required|string',
            'timeout' => 'required|integer',
            'email_to' => 'nullable|email',
            // log_action and email_action checkboxes
        ]);

        $host = $request->input('host');
        $interval = $request->input('interval');
        $timeout = $request->input('timeout');
        $email = $request->input('email_to');

        $doLog = $request->boolean('log_action');
        $doEmail = $request->boolean('email_action');

        $upScript = "";
        $downScript = "";

        if ($doLog) {
            $upScript .= ":log info \"Netwatch: Host {$host} is UP\";\n";
            $downScript .= ":log error \"Netwatch: Host {$host} is DOWN\";\n";
        }

        if ($doEmail && $email) {
            $upScript .= "/tool e-mail send to=\"{$email}\" subject=\"[Netwatch] UP: {$host}\" body=\"Host {$host} is back online.\";\n";
            $downScript .= "/tool e-mail send to=\"{$email}\" subject=\"[Netwatch] DOWN: {$host}\" body=\"Host {$host} is unreachable.\";\n";
        }

        $script = "/tool netwatch add host={$host} interval={$interval} timeout={$timeout}ms comment=\"Check {$host}\"";
        if ($upScript) {
            $script .= " up-script={ \n" . trim($upScript) . " \n}";
        }
        if ($downScript) {
            $script .= " down-script={ \n" . trim($downScript) . " \n}";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Backup Service Monitor Script (Attix5)
     */
    public function generateAttix(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'service_name' => 'required|string',
            'server_host' => 'required|string',
            'port' => 'required|integer',
            'method' => 'required|in:tcp,icmp',
            'email' => 'nullable|email',
        ]);

        $svc = $request->input('service_name');
        $host = $request->input('server_host');
        $port = $request->input('port');
        $method = $request->input('method');
        $email = $request->input('email');

        $checkCmd = "";
        if ($method === 'tcp') {
            $checkCmd = ":local result [/ping {$host} count=3];";
            $checkCmd .= "\n    # Note: Port check is complex in ROS scripting without fetch. Using Ping for basic availability.";
        } else {
            $checkCmd = ":local result [/ping {$host} count=3];";
        }

        $svcClean = str_replace(' ', '-', $svc);
        $script = "/system scheduler add name=\"Check-{$svcClean}\" interval=10m on-event={\n";
        $script .= "    :local svc \"{$svc}\";\n";
        $script .= "    :local target \"{$host}\";\n";
        $script .= "    {$checkCmd}\n";
        $script .= "    :if (\$result = 0) do={\n";
        $script .= "        :log error \"\$svc at \$target is DOWN!\";\n";
        if ($email) {
            $script .= "        /tool e-mail send to=\"{$email}\" subject=\"[ALERT] \$svc Down\" body=\"Service \$svc at \$target is unreachable.\";\n";
        }
        $script .= "    } else={\n";
        $script .= "        :log info \"\$svc is UP.\";\n";
        $script .= "    }\n";
        $script .= "}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Traffic Sniffer Command (TZSP)
     */
    public function generateSniffer(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'protocol' => 'required|string',
            'output_mode' => 'required|in:stream,file',
            'server_ip' => 'required_if:output_mode,stream|nullable|ipv4',
            'filename' => 'required_if:output_mode,file|nullable|string',
            'limit' => 'required_if:output_mode,file|nullable|integer',
        ]);

        $iface = $request->input('interface');
        $proto = $request->input('protocol');
        $mode = $request->input('output_mode');

        $script = "/tool sniffer set interface=\"{$iface}\"";

        if ($proto !== 'all') {
            $script .= " filter-protocol={$proto}";
        }

        if ($mode === 'stream') {
            $srv = $request->input('server_ip');
            $script .= " streaming-enabled=yes streaming-server={$srv}";
        } else {
            $file = $request->input('filename');
            $limit = $request->input('limit');
            $script .= " streaming-enabled=no file-name=\"{$file}\" file-limit={$limit}";
        }

        $script .= "\n/tool sniffer start";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Traffic Monitor Script
     */
    public function generateTrafficMonitor(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'traffic_type' => 'required|string',
            'threshold' => 'required|numeric',
            'trigger' => 'required|string',
            'event_name' => 'required|string',
        ]);

        $iface = $request->input('interface');
        $type = $request->input('traffic_type');
        $thresh = $request->input('threshold');
        $trigger = $request->input('trigger');
        $name = $request->input('event_name');

        $bps = (int) $thresh * 1000000;

        $script = "/interface traffic-monitor add name=\"{$name}\" interface=\"{$iface}\" traffic=\"{$type}\" threshold={$bps} trigger={$trigger} on-event={\n";
        $script .= "    :log warning \"Traffic Monitor '{$name}': Traffic is {$trigger} {$thresh}Mbps on {$iface}\";\n";
        $script .= "    # Add custom actions here (e.g. disable queue, send email)\n";
        $script .= "}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

