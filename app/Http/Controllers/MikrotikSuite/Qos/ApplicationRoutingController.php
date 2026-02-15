<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Qos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ApplicationRoutingController extends Controller
{
    /**
     * Display gaming routes configuration wizard
     */
    public function gamingRoutes(): View
    {
        return view('mikrotik-suite.qos.application-routing.gaming-routes');
    }

    /**
     * Display social media routes configuration wizard
     */
    public function socialMediaRoutes(): View
    {
        return view('mikrotik-suite.qos.application-routing.social-media-routes');
    }

    /**
     * Display streaming routes configuration wizard
     */
    public function streamingRoutes(): View
    {
        return view('mikrotik-suite.qos.application-routing.streaming-routes');
    }

    /**
     * Generate Gaming Routes script
     */
    public function generateGaming(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'game_preset' => 'required|string',
            'custom_ports' => 'nullable|string',
            'src_address' => 'required|string',
            'priority' => 'required|integer|min:1|max:8',
        ]);

        $preset = $request->input('game_preset');
        $custom = $request->input('custom_ports');
        $src = $request->input('src_address');
        $prio = $request->input('priority');

        $rules = [];

        switch ($preset) {
            case 'mobile_legends':
                $rules[] = ['proto' => 'tcp', 'ports' => '30000-30010'];
                $rules[] = ['proto' => 'udp', 'ports' => '30000-30010'];
                break;
            case 'pubg_mobile':
                $rules[] = ['proto' => 'tcp', 'ports' => '17500'];
                $rules[] = ['proto' => 'udp', 'ports' => '10000-20000'];
                break;
            case 'free_fire':
                $rules[] = ['proto' => 'tcp', 'ports' => '39698-39700'];
                $rules[] = ['proto' => 'udp', 'ports' => '39698-39700']; // Usually both but mostly TCP
                break;
            case 'valorant':
                $rules[] = ['proto' => 'udp', 'ports' => '7000-7500'];
                $rules[] = ['proto' => 'tcp', 'ports' => '2099']; // Login/Chat often TCP
                break;
            case 'custom':
                // Parse "udp:1234,tcp:5678"
                if ($custom) {
                    $parts = explode(',', $custom);
                    foreach ($parts as $part) {
                        $p = explode(':', trim($part));
                        if (count($p) == 2) {
                            $rules[] = ['proto' => strtolower(trim($p[0])), 'ports' => trim($p[1])];
                        } elseif (count($p) == 1 && is_numeric(trim($p[0]))) {
                            // Default to both if just number? Or UDP? Let's assume UDP for games if unspecified
                            $rules[] = ['proto' => 'udp', 'ports' => trim($p[0])];
                        }
                    }
                }
                break;
            default:
                break;
        }

        $script = "/queue tree add name=\"Game_Priority\" parent=global packet-mark=game_pkt priority=$prio\n";

        foreach ($rules as $rule) {
            $proto = $rule['proto'];
            $dst = $rule['ports'];
            if ($proto && $dst) {
                $script .= "/ip firewall mangle add chain=prerouting src-address=$src protocol=$proto dst-port=$dst action=mark-packet new-packet-mark=game_pkt passthrough=no comment=\"Game Traffic ($proto:$dst)\"\n";
            }
        }

        if (empty($rules) && $preset == 'custom') {
            $script .= "/log warning \"No valid custom ports provided for Game Traffic generator\"\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Generate Social Media Routes script
     */
    public function generateSocialMedia(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'platform' => 'required|string',
            'action' => 'required|string|in:mark,route',
        ]);

        $platform = $request->input('platform');
        $action = $request->input('action');

        $domains = match ($platform) {
            'facebook' => '.facebook.com, .fbcdn.net',
            'instagram' => '.instagram.com',
            'tiktok' => '.tiktok.com, .byteoversea.com',
            'whatsapp' => '.whatsapp.net',
            default => ''
        };

        $script = "/ip firewall layer7-protocol add name=\"$platform\" regexp=\"$domains\"\n";

        if ($action === 'mark') {
            $script .= "/ip firewall mangle add chain=prerouting layer7-protocol=\"$platform\" action=mark-packet new-packet-mark=\"$platform-pkt\" passthrough=no\n";
        } else {
            $script .= "/ip firewall mangle add chain=prerouting layer7-protocol=\"$platform\" action=mark-routing new-routing-mark=\"to_vpn\" passthrough=yes\n";
        }

        return response()->json(['script' => $script]);
    }

    /**
     * Generate Streaming Routes script
     */
    public function generateStreaming(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'service' => 'required|string',
        ]);

        $service = $request->input('service');
        $domains = match ($service) {
            'youtube' => 'googlevideo.com',
            'netflix' => 'nflxvideo.net',
            default => ''
        };

        $script = "/ip firewall layer7-protocol add name=\"$service\" regexp=\"$domains\"\n";
        $script .= "/ip firewall mangle add chain=prerouting layer7-protocol=\"$service\" action=mark-packet new-packet-mark=\"stream_pkt\" passthrough=no\n";

        return response()->json(['script' => $script]);
    }

    /**
     * Display website routes configuration wizard
     */
    public function websiteRoutes(): View
    {
        return view('mikrotik-suite.qos.application-routing.website-routes');
    }
}

