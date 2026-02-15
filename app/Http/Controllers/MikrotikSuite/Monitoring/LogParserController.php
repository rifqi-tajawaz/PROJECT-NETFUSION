<?php

namespace App\Http\Controllers\MikrotikSuite\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogParserController extends Controller
{
    /**
     * Log Regex Generator
     */
    public function logRegexGenerator()
    {
        return view('mikrotik-suite.monitoring.troubleshooting.log-regex-generator');
    }

    /**
     * Packet Sniffer & Torch Config
     */
    public function packetSniffer()
    {
        return view('mikrotik-suite.monitoring.troubleshooting.packet-sniffer');
    }

    /**
     * Generate Torch Command
     */
    public function generateTorch(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'interface' => 'required|string',
            'src_address' => 'nullable|string',
            'dst_address' => 'nullable|string',
            'protocol' => 'required|string',
            'port' => 'nullable|string',
        ]);

        $iface = $request->input('interface');
        $src = $request->input('src_address');
        $dst = $request->input('dst_address');
        $proto = $request->input('protocol');
        $port = $request->input('port');

        $script = "/tool torch interface={$iface}";

        if ($src)
            $script .= " src-address={$src}";
        if ($dst)
            $script .= " dst-address={$dst}";
        if ($proto !== 'any')
            $script .= " protocol={$proto}";
        if ($port && $port !== 'any')
            $script .= " port={$port}";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Log Regex Rule and Test Match
     */
    public function generateLogRegex(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'input_string' => 'required|string',
            'pattern' => 'required|string',
        ]);

        $str = $request->input('input_string');
        $pat = $request->input('pattern');

        // Test Regex (Using PCRE which is close enough for most validations)
        // Pass pattern with delimiters if missing? JS regex doesn't use delimiters in constructor.
        // PHP preg_match needs delimiters. We'll use result to inform user.
        $match = false;
        $error = null;

        try {
            // Add delimiters for preg_match
            $regex = "/" . str_replace('/', '\/', $pat) . "/";
            $result = @preg_match($regex, $str); // Suppress errors for invalid regex
            if ($result === false) {
                $error = "Invalid Regex Pattern";
            } else {
                $match = $result === 1;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        // Generate ROS Script
        // Escape characters for ROS string
        $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $pat);
        $script = "/system logging add topics=firewall action=memory message=\"{$escaped}\"";

        return response()->json([
            'status' => 'success',
            'script' => $script,
            'match' => $match,
            'error' => $error,
        ]);
    }
}

