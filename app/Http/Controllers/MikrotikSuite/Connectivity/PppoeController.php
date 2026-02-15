<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Connectivity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PppoeController extends Controller
{
    public function secretsGenerator(): View
    {
        return view('mikrotik-suite.connectivity.pppoe.secrets-generator');
    }

    public function pppoeServer(): View
    {
        return view('mikrotik-suite.connectivity.pppoe.pppoe-server');
    }

    public function telegramReporter(): View
    {
        return view('mikrotik-suite.connectivity.pppoe.telegram-reporter');
    }
    /**
     * Generate PPPoE Secrets
     */
    public function generateSecrets(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'qty' => 'required|numeric|min:1|max:1000',
            'prefix' => 'nullable|string',
            'length' => 'required|numeric|min:3|max:20',
            'profile' => 'nullable|string',
            'remote_addr' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        $qty = (int) $request->input('qty');
        $prefix = $request->input('prefix') ?? '';
        $length = (int) $request->input('length');
        $profile = $request->input('profile') ?? 'default';
        $remoteAddr = $request->input('remote_addr'); // e.g. "192.168.10."
        $comment = $request->input('comment');

        $script = "/ppp secret\n";
        $csv = "Username,Password,Service,Profile,RemoteAddress\n";
        $data = [];

        // IP Logic
        $ipBase = '';
        $ipStart = 2;
        if ($remoteAddr) {
            $remoteAddr = trim($remoteAddr);
            if (str_ends_with($remoteAddr, '.')) {
                $ipBase = $remoteAddr;
            } else {
                // Check if it looks like a full IP
                $parts = explode('.', $remoteAddr);
                if (count($parts) == 4 && is_numeric(end($parts))) {
                    // It is a full IP, maybe user wants to start from here? 
                    // But JS logic assumed "192.168.10." style.
                    // If user provides 192.168.10.1, we might want to increment from 1?
                    // Let's stick to the simple logic: if it doesn't end in dot, add dot
                    $ipBase = $remoteAddr . '.';
                } else {
                    $ipBase = $remoteAddr . '.';
                }
            }
        }

        for ($i = 0; $i < $qty; $i++) {
            $user = $prefix . $this->generateRandomString($length);
            $pass = $this->generateRandomString($length);

            $cmd = "add name=\"{$user}\" password=\"{$pass}\" service=pppoe profile=\"{$profile}\"";
            $ip = '';

            if ($ipBase) {
                // If ipBase is "192.168.10.", append 2+i
                $ip = $ipBase . ($ipStart + $i);
                $cmd .= " remote-address={$ip}";
            }

            if ($comment) {
                $cmd .= " comment=\"{$comment}\"";
            }

            $script .= $cmd . "\n";
            $csv .= "{$user},{$pass},pppoe,{$profile},{$ip}\n";

            $data[] = [
                'username' => $user,
                'password' => $pass,
                'profile' => $profile,
                'remote_address' => $ip ?: '-'
            ];
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
            'csv' => $csv,
            'data' => $data,
        ]);
    }

    /**
     * Generate PPPoE Server Script
     */
    public function generateServer(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'service_name' => 'required|string',
            'interface' => 'required|string',
            'one_session' => 'boolean',
            'profile_name' => 'required|string',
            'local_addr' => 'required|string',
            'remote_addr' => 'required|string',
            'dns1' => 'nullable|string',
            'dns2' => 'nullable|string',
        ]);

        $service = $request->input('service_name');
        $iface = $request->input('interface');
        $oneSession = $request->boolean('one_session');
        $profile = $request->input('profile_name');
        $local = $request->input('local_addr');
        $remote = $request->input('remote_addr');
        $dns1 = $request->input('dns1');
        $dns2 = $request->input('dns2');

        $script = "# IP Pool\n";
        $poolName = "{$profile}-pool";
        $script .= "/ip pool add name=\"{$poolName}\" ranges={$remote}\n\n";

        $script .= "# PPP Profile\n";
        $dnsList = array_filter([$dns1, $dns2]);
        $dnsStr = implode(',', $dnsList);
        $script .= "/ppp profile add name=\"{$profile}\" local-address={$local} remote-address=\"{$poolName}\" dns-server=\"{$dnsStr}\" change-tcp-mss=yes\n\n";

        $script .= "# PPPoE Server\n";
        $oneSessionStr = $oneSession ? 'yes' : 'no';
        $script .= "/interface pppoe-server server add service-name=\"{$service}\" interface=\"{$iface}\" default-profile=\"{$profile}\" one-session-per-host={$oneSessionStr} disabled=no\n";

        $script .= "\n# Tip: Make sure the interface '{$iface}' does not have an IP address in the same subnet if you are using a bridge, or check your firewall rules.\n";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate Telegram Reporter Script
     */
    public function generateTelegram(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'bot_token' => 'required|string',
            'chat_id' => 'required|string',
            'trigger_event' => 'required|string|in:pppoe-profile,netwatch',
        ]);

        $token = $request->input('bot_token');
        $chat = $request->input('chat_id');
        $mode = $request->input('trigger_event');

        $script = "";

        if ($mode === 'pppoe-profile') {
            $script .= "# PPPoE Profile 'On Up' Script:\n";
            $script .= "/tool fetch url=\"https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat}&text=PPPoE User %24user logged in from %24caller-id at $[/system clock get time]\"\n\n";
            $script .= "# PPPoE Profile 'On Down' Script:\n";
            $script .= "/tool fetch url=\"https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat}&text=PPPoE User %24user logged out. Uptime: %24uptime\"\n";
        } else {
            $script .= "# Netwatch 'On Up' Script:\n";
            $script .= "/tool fetch url=\"https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat}&text=HOST UP\"\n\n";
            $script .= "# Netwatch 'On Down' Script:\n";
            $script .= "/tool fetch url=\"https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat}&text=HOST DOWN!\"\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    private function generateRandomString($length = 6)
    {
        $characters = 'abcdefghjkmnpqrstuvwxyz23456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

