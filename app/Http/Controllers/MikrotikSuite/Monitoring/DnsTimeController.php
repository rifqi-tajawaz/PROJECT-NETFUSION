<?php

namespace App\Http\Controllers\MikrotikSuite\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DnsTimeController extends Controller
{
    public function dnsOverHttps()
    {
        return view('mikrotik-suite.monitoring.dns-time.dns-over-https');
    }

    public function ntpClient()
    {
        return view('mikrotik-suite.monitoring.dns-time.ntp-client');
    }

    /**
     * Generate DNS over HTTPS Script
     */
    public function generateDoH(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'provider' => 'required|string',
            'custom_url' => 'nullable|url',
            'verify_cert' => 'required|boolean',
        ]);

        $provider = $request->input('provider');
        $customUrl = $request->input('custom_url');
        $verify = $request->input('verify_cert');

        $providers = [
            'cloudflare' => [
                'url' => 'https://cloudflare-dns.com/dns-query',
                'cert' => 'https://cacerts.digicert.com/DigiCertGlobalRootG2.crt.pem'
            ],
            'google' => [
                'url' => 'https://dns.google/dns-query',
                'cert' => 'https://pki.goog/roots.pem'
            ],
            'quad9' => [
                'url' => 'https://dns.quad9.net/dns-query',
                'cert' => 'https://www.quad9.net/quad9.crt'
            ],
            'nextdns' => [
                'url' => 'https://dns.nextdns.io/', // Needs ID appending in client usually, but here handled by input?
                // The client JS attached ID to url before sending, or sent ID? 
                // Client JS: value="nextdns", then set customUrl input to template. 
                // So if user selected NextDNS, they filled custom_url.
                'cert' => ''
            ]
        ];

        // Logic adjustment based on Client JS:
        // if (prov === 'custom' || (prov === 'nextdns' && document.getElementById('customUrl').value)) { url = customUrl }

        $url = '';
        if ($provider === 'custom' || ($provider === 'nextdns' && $customUrl)) {
            $url = $customUrl;
        } else {
            $url = $providers[$provider]['url'] ?? '';
        }

        $script = "/tool fetch url=\"https://curl.se/ca/cacert.pem\" dst-path=\"flash/cacert.pem\"\n";
        $script .= "/certificate import file-name=flash/cacert.pem passphrase=\"\"\n";
        $script .= "# Note: The above imports generic CA roots. Specific roots may be needed.\n\n";

        $verifyStr = $verify ? 'yes' : 'no';
        $script .= "/ip dns set use-doh-server=\"{$url}\" verify-doh-cert={$verifyStr}\n";
        $script .= "/ip dns static add name=\"cloudflare-dns.com\" address=1.1.1.1\n";
        $script .= "/ip dns static add name=\"dns.google\" address=8.8.8.8\n";
        $script .= "# Add static entries for the DoH domain to bootstrap connection.\n";

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }

    /**
     * Generate NTP Client Script
     */
    public function generateNtp(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'timezone' => 'required|string',
            'primary_ntp' => 'required|string',
            'secondary_ntp' => 'required|string',
            // server_mode checkbox
        ]);

        $tz = $request->input('timezone');
        $s1 = $request->input('primary_ntp');
        $s2 = $request->input('secondary_ntp');
        $serve = $request->boolean('server_mode');

        $script = "/system clock set time-zone-name=\"{$tz}\"\n";
        $script .= "/system ntp client set enabled=yes mode=unicast primary-ntp=\"{$s1}\" secondary-ntp=\"{$s2}\"\n";

        if ($serve) {
            $script .= "/system ntp server set enabled=yes manycast=yes broadcast=no multicast=no\n";
        }

        return response()->json([
            'status' => 'success',
            'script' => $script,
        ]);
    }
}

