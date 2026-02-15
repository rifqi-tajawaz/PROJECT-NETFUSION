<?php

namespace App\DTOs\Mikrotik;

class HotspotUserDTO
{
    public $id;
    public $name;
    public $password;
    public $profile;
    public $server;
    public $comment;
    public $uptime;
    public $bytesIn;
    public $bytesOut;
    public $limitUptime;
    public $limitBytesTotal;
    public $macAddress;
    public $disabled;

    public function __construct(array $data)
    {
        $this->id = $data['.id'] ?? null;
        $this->name = $data['name'] ?? 'Unknown';
        $this->password = $data['password'] ?? '';
        $this->profile = $data['profile'] ?? 'default';
        $this->server = $data['server'] ?? 'all';
        $this->comment = $data['comment'] ?? null;
        $this->uptime = $data['uptime'] ?? '0s';
        $this->bytesIn = (int)($data['bytes-in'] ?? 0);
        $this->bytesOut = (int)($data['bytes-out'] ?? 0);
        $this->limitUptime = $data['limit-uptime'] ?? null;
        $this->limitBytesTotal = isset($data['limit-bytes-total']) ? (int)$data['limit-bytes-total'] : null;
        $this->macAddress = $data['mac-address'] ?? '-';
        $this->disabled = isset($data['disabled']) && ($data['disabled'] == 'true' || $data['disabled'] === true);
    }

    public function isExpired(): bool
    {
        if ($this->limitUptime === '1s') return true;
        if ($this->limitUptime && $this->uptime === $this->limitUptime) return true;
        if ($this->limitBytesTotal > 0 && ($this->bytesIn + $this->bytesOut) >= $this->limitBytesTotal) return true;
        return false;
    }
    
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function getUsagePercent(): float
    {
         if ($this->limitBytesTotal > 0) {
             return min(100, (($this->bytesIn + $this->bytesOut) / $this->limitBytesTotal) * 100);
         }
         return 0;
    }

    public function getTotalBytes(): int
    {
        return $this->bytesIn + $this->bytesOut;
    }
}
