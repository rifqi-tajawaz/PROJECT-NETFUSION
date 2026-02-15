<?php

namespace App\Services\MikrotikSuite\ScriptGenerator;

class HotspotGeneratorService
{
    public function generateUsersScript(array $config): array
    {
        $qty = $config['qty'];
        $mode = $config['mode'];
        $prefix = $config['prefix'];
        $length = $config['length'];
        $profile = $config['profile'];
        $timeLimit = $config['timeLimit'];

        $users = [];
        $script = "/ip hotspot user\n";
        $csv = "Username,Password,Profile,TimeLimit\n";

        for ($i = 0; $i < $qty; $i++) {
            $rnd = $this->generateRandomString($length);
            $user = $prefix . $rnd;
            $pass = ($mode === 'user_eq_pass') ? $user : $this->generateRandomString($length);

            // Script
            $line = "add name=\"{$user}\" password=\"{$pass}\" profile=\"{$profile}\"";
            if (!empty($timeLimit)) {
                $line .= " limit-uptime={$timeLimit}";
            }
            $script .= $line . "\n";

            // CSV
            $timeLimitStr = $timeLimit ?? '';
            $csv .= "{$user},{$pass},{$profile},{$timeLimitStr}\n";

            // Data for frontend cards
            $users[] = [
                'user' => $user,
                'pass' => $pass,
                'limit' => $timeLimit ?? 'Unlimited'
            ];
        }

        return [
            'script' => $script,
            'csv' => $csv,
            'users' => $users
        ];
    }

    public function generateWizardScript(string $iface, string $net, string $pool, string $dns): string
    {
        $netIp = explode('/', $net)[0];
        $script = "/ip address add address={$net} interface={$iface}\n";
        $script .= "/ip pool add name=hs-pool-1 ranges={$pool}\n";
        $script .= "/ip hotspot profile add name=hsprof1 dns-name=\"{$dns}\" hotspot-address={$netIp} html-directory=hotspot\n";
        $script .= "/ip hotspot add name=hotspot1 interface={$iface} address-pool=hs-pool-1 profile=hsprof1 disabled=no\n";
        $script .= "/ip hotspot user add name=admin password=admin\n";
        return $script;
    }

    public function generateBlockSharingScript(): string
    {
        $script = "/ip firewall mangle\n";
        $script .= "add action=change-ttl chain=postrouting new-ttl=set:1 out-interface=all-wireless passthrough=yes comment=\"Block Tethering\"\n";
        $script .= "add action=change-ttl chain=prerouting new-ttl=set:1 in-interface=all-wireless passthrough=yes comment=\"Block Tethering\"";
        return $script;
    }

    public function generateExpiredNotificationScript(string $interval, bool $remove, bool $log): string
    {
        $script = "/system scheduler add name=\"CheckExpiredUsers\" interval={$interval} on-event={\n";
        $script .= "  :foreach i in=[/ip hotspot user find where limit-uptime!=0] do={\n";
        $script .= "    :local uptime [/ip hotspot user get \$i uptime];\n";
        $script .= "    :local limit [/ip hotspot user get \$i limit-uptime];\n";
        $script .= "    :if (\$uptime >= \$limit) do={\n";
        $script .= "       :local uname [/ip hotspot user get \$i name];\n";

        if ($log) {
            $script .= "       :log info \"User \$uname expired and removed.\";\n";
        }
        if ($remove) {
            $script .= "       /ip hotspot user remove \$i;\n";
        }

        $script .= "       /ip hotspot active remove [find user=\$uname];\n";
        $script .= "    }\n";
        $script .= "  }\n";
        $script .= "}\n";

        return $script;
    }

    public function generateQrCodeUrl(string $ssid, string $encryption, ?string $password, bool $hidden): array
    {
        // WIFI Format: WIFI:T:WPA;S:MyNetwork;P:MyPassword;H:false;;
        $wifiString = "WIFI:";

        // Type
        if ($encryption === 'nopass') {
            $wifiString .= "T:nopass;";
        } else {
            $wifiString .= "T:{$encryption};";
        }

        // SSID
        $wifiString .= "S:{$ssid};";

        // Password
        if ($encryption !== 'nopass') {
            $wifiString .= "P:{$password};";
        }

        // Hidden
        $wifiString .= $hidden ? "H:true;" : "H:false;";
        $wifiString .= ";";

        $apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($wifiString);

        return [
            'qr_string' => $wifiString,
            'qr_url' => $apiUrl
        ];
    }

    public function generateBandwidthLimiterScript(string $target, string $up, string $down, ?string $comment): string
    {
        $comment = $comment ?? "limit-{$target}";
        return "/queue simple add name=\"limit-{$target}\" target={$target} max-limit={$up}M/{$down}M comment=\"{$comment}\"";
    }

    private function generateRandomString(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $charLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[random_int(0, $charLength - 1)];
        }
        return $randomString;
    }
}
