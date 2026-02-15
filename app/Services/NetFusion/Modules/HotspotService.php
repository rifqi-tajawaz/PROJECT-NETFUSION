<?php

namespace App\Services\NetFusion\Modules;

use App\Services\NetFusion\MikhmonAPI;
use Illuminate\Support\Facades\Cache;
use App\DTOs\Mikrotik\HotspotUserDTO;

class HotspotService
{
    protected $api;

    public function __construct(MikhmonAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Get Hotspot Users
     */
    public function getUsers($data = [])
    {
        // Cache full list for 60 seconds
        if (empty($data)) {
            $key = 'hotspot_users_' . $this->api->host;
            return Cache::remember($key, 60, function () {
                return $this->api->comm('/ip/hotspot/user/print');
            });
        }
        return $this->api->comm('/ip/hotspot/user/print', $data);
    }

    /**
     * Add Hotspot User
     */
    public function addUser($data)
    {
        // Ensure default server if not provided
        if (!isset($data['server'])) {
            $data['server'] = 'all';
        }
        $ret = $this->api->comm('/ip/hotspot/user/add', $data);
        Cache::forget('hotspot_users_' . $this->api->host);
        return $ret;
    }

    /**
     * Remove Hotspot User
     */
    public function removeUser($id)
    {
        $ret = $this->api->comm('/ip/hotspot/user/remove', ['.id' => $id]);
        Cache::forget('hotspot_users_' . $this->api->host);
        return $ret;
    }

    /**
     * Update Hotspot User
     */
    public function updateUser($id, $data)
    {
        $data['.id'] = $id;
        $ret = $this->api->comm('/ip/hotspot/user/set', $data);
        Cache::forget('hotspot_users_' . $this->api->host);
        return $ret;
    }

    /**
     * Enable/Disable User
     */
    public function toggleUser($id, $enable = true)
    {
        $command = $enable ? '/ip/hotspot/user/enable' : '/ip/hotspot/user/disable';
        return $this->api->comm($command, ['.id' => $id]);
    }

    /**
     * Get Hotspot User Profile
     */
    public function getProfiles()
    {
        return $this->api->comm('/ip/hotspot/user/profile/print');
    }

    /**
     * Get Active Hotspot Users
     */
    public function getActiveUsers()
    {
        return $this->api->comm('/ip/hotspot/active/print');
    }

    /**
     * Remove Active User (Disconnect)
     */
    public function removeActiveUser($id)
    {
        return $this->api->comm('/ip/hotspot/active/remove', ['.id' => $id]);
    }

    /**
     * Generate Batch Users
     */
    public function generateBatch($data, $qty)
    {
        $generated = [];
        for ($i = 0; $i < $qty; $i++) {
            $username = $data['prefix'] . rand(1000, 9999); // Simple random generation for now
            // Ensure uniqueness could be handled here or rely on RouterOS error

            $userData = [
                'server' => $data['server'] ?? 'all',
                'profile' => $data['profile'],
                'name' => $username,
                'password' => $username, // User=Pass for voucher usually
                'comment' => $data['comment'] ?? 'batch-' . date('Ymd'),
                'limit-uptime' => $data['limit_uptime'] ?? '0s',
            ];

            $this->addUser($userData);
            $generated[] = $userData;
        }
        return $generated;
    }

    /**
     * Get Single Profile
     */
    public function getProfile($id)
    {
        $profiles = $this->api->comm('/ip/hotspot/user/profile/print', ['.id' => $id]);
        return $profiles[0] ?? null;
    }

    /**
     * Add Profile
     */
    public function addProfile($data)
    {
        return $this->api->comm('/ip/hotspot/user/profile/add', $data);
    }

    /**
     * Update Profile
     */
    public function updateProfile($id, $data)
    {
        $data['.id'] = $id;
        return $this->api->comm('/ip/hotspot/user/profile/set', $data);
    }

    /**
     * Remove Profile
     */
    public function removeProfile($id)
    {
        return $this->api->comm('/ip/hotspot/user/profile/remove', ['.id' => $id]);
    }

    /**
     * Extract Price Info from Profile Name or Comment
     */
    public function getProfilePriceInfo($name)
    {
        if (preg_match('/(\d+)K/i', $name, $matches)) {
            return $matches[1] * 1000;
        }
        return 0;
    }

    /**
     * Get Hotspot Servers
     */
    public function getServers()
    {
        return $this->api->comm('/ip/hotspot/print');
    }

    /**
     * Reset User Counters
     */
    public function resetCounters($id)
    {
        $ret = $this->api->comm('/ip/hotspot/user/reset-counters', ['.id' => $id]);
        Cache::forget('hotspot_users_' . $this->api->host);
        return $ret;
    }

    /* -------------------------------------------------------------------------- */
    /*                        HOSTS, BINDINGS & COOKIES                           */
    /* -------------------------------------------------------------------------- */

    /**
     * Get Hotspot Hosts
     */
    public function getHosts()
    {
        return $this->api->comm('/ip/hotspot/host/print');
    }

    /**
     * Remove Host
     */
    public function removeHost($id)
    {
        return $this->api->comm('/ip/hotspot/host/remove', ['.id' => $id]);
    }

    /**
     * Get IP Bindings
     */
    public function getIpBindings()
    {
        return $this->api->comm('/ip/hotspot/ip-binding/print');
    }

    /**
     * Add IP Binding
     */
    public function addIpBinding($data)
    {
        return $this->api->comm('/ip/hotspot/ip-binding/add', $data);
    }

    /**
     * Update IP Binding
     */
    public function updateIpBinding($id, $data)
    {
        $data['.id'] = $id;
        return $this->api->comm('/ip/hotspot/ip-binding/set', $data);
    }

    /**
     * Remove IP Binding
     */
    public function removeIpBinding($id)
    {
        return $this->api->comm('/ip/hotspot/ip-binding/remove', ['.id' => $id]);
    }

    /**
     * Toggle IP Binding
     */
    public function toggleIpBinding($id, $enable = true)
    {
        $command = $enable ? '/ip/hotspot/ip-binding/enable' : '/ip/hotspot/ip-binding/disable';
        return $this->api->comm($command, ['.id' => $id]);
    }

    /**
     * Get Hotspot Cookies
     */
    public function getCookies()
    {
        return $this->api->comm('/ip/hotspot/cookie/print');
    }

    /**
     * Remove Hotspot Cookie
     */
    public function removeCookie($id)
    {
        return $this->api->comm('/ip/hotspot/cookie/remove', ['.id' => $id]);
    }

    /* -------------------------------------------------------------------------- */
    /*                               REPORTING SYSTEM                             */
    /* -------------------------------------------------------------------------- */

    /**
     * Save Selling Report (Stored as System Script)
     */
    public function saveSellingReport($data)
    {
        $data['date'] = date('d/m/Y');
        $data['time'] = date('H:i:s');
        // Store as JSON in comment
        $comment = json_encode($data);
        // Unique name
        $name = 'rep-' . date('YmdHis') . '-' . rand(100, 999);

        return $this->api->comm('/system/script/add', [
            'name' => $name,
            'source' => '', // Empty source, just storage
            'comment' => $comment
        ]);
    }

    /**
     * Get Selling Reports
     */
    public function getSellingReports($month = null, $day = null)
    {
        // Fetch all report scripts
        $scripts = $this->api->comm('/system/script/print', ['?~name' => 'rep-']);

        $reports = [];
        foreach ($scripts as $script) {
            $comment = $script['comment'] ?? '';
            $data = json_decode($comment, true);

            if ($data) {
                $data['id'] = $script['.id']; // Add script ID for deletion

                // Parse date for filtering
                // Format assumed: d/m/Y (from saveSellingReport)
                $dateParts = explode('/', $data['date']);
                if (count($dateParts) == 3) {
                    $reportMonth = $dateParts[1] . $dateParts[2]; // mY -> 122024
                    $reportDay = $dateParts[0]; // d -> 23

                    // Filter by Month (mY)
                    if ($month && $reportMonth != $month)
                        continue;

                    // Filter by Day (d)
                    if ($day && $reportDay != $day)
                        continue;
                }

                $reports[] = $data;
            }
        }

        // Sort by date/time descending
        usort($reports, function ($a, $b) {
            $t1 = strtotime(str_replace('/', '-', $a['date']) . ' ' . $a['time']);
            $t2 = strtotime(str_replace('/', '-', $b['date']) . ' ' . $b['time']);
            return $t2 - $t1;
        });

        return $reports;
    }

    /**
     * Delete Selling Report
     */
    public function deleteSellingReport($id)
    {
        return $this->api->comm('/system/script/remove', ['.id' => $id]);
    }

    /**
     * Get Available Report Months
     */
    public function getReportMonths()
    {
        $scripts = $this->api->comm('/system/script/print', ['?~name' => 'rep-']);
        $months = [];

        foreach ($scripts as $script) {
            $comment = $script['comment'] ?? '';
            $data = json_decode($comment, true);

            if ($data && isset($data['date'])) {
                $dateParts = explode('/', $data['date']);
                if (count($dateParts) == 3) {
                    $mY = $dateParts[1] . $dateParts[2]; // 122024
                    $human = date('F Y', mktime(0, 0, 0, $dateParts[1], 10, $dateParts[2]));

                    if (!isset($months[$mY])) {
                        $months[$mY] = $human;
                    }
                }
            }
        }

        return $months;
    }

    /**
     * Process Stats and Filter Users
     */
    public function processStatsAndFilter($allUsers, $activeUsers, $filters = [])
    {
        $stats = [
            'totalCount' => count($allUsers),
            'onlineCount' => count($activeUsers),
            'expiredCount' => 0,
            'disabledCount' => 0,
            'uniqueComments' => [],
        ];

        $filteredUsers = [];

        foreach ($allUsers as $userData) {
            $user = new HotspotUserDTO($userData);

            // Stats
            if ($user->isDisabled()) {
                $stats['disabledCount']++;
            }
            if ($user->isExpired()) {
                $stats['expiredCount']++;
            }
            if (!empty($user->comment)) {
                $stats['uniqueComments'][] = $user->comment;
            }

            // Filtering
            $match = true;
            if (isset($filters['profile']) && $filters['profile'] && $user->profile != $filters['profile']) {
                $match = false;
            }
            if ($match && isset($filters['comment']) && $filters['comment']) {
                $search = strtolower($filters['comment']);
                if (!str_contains(strtolower($user->comment ?? ''), $search)) {
                    $match = false;
                }
            }
            if ($match && isset($filters['status']) && $filters['status'] === 'expired') {
                if (!$user->isExpired()) {
                    $match = false;
                }
            }

            if ($match) {
                $filteredUsers[] = $user;
            }
        }

        $stats['uniqueComments'] = array_unique($stats['uniqueComments']);
        
        return [
            'users' => array_values($filteredUsers),
            'stats' => $stats
        ];
    }
}
