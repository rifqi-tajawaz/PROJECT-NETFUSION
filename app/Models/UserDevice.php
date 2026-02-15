<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fingerprint',
        'device_name',
        'device_type',
        'platform',
        'browser',
        'ip_address',
        'user_agent',
        'first_seen_at',
        'last_seen_at',
        'is_trusted',
        'device_data',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_trusted' => 'boolean',
        'device_data' => 'json',
    ];

    /**
     * Get the user that owns the device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get device display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->device_name ?: 'Unknown Device';
    }

    /**
     * Get device icon based on type.
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'mobile' => 'smartphone',
            'tablet' => 'tablet',
            'desktop' => 'monitor',
            'bot' => 'robot',
        ];

        return $icons[$this->device_type] ?? 'device';
    }

    /**
     * Get last seen relative time.
     */
    public function getLastSeenAgoAttribute(): string
    {
        return $this->last_seen_at ? $this->last_seen_at->diffForHumans() : 'Never';
    }

    /**
     * Get device location.
     */
    public function getLocationAttribute(): ?string
    {
        $location = $this->device_data['location'] ?? null;

        if ($location && $location['city'] !== 'Unknown') {
            return implode(', ', array_filter([$location['city'], $location['country']]));
        }

        return null;
    }

    /**
     * Mark device as trusted.
     */
    public function trust(): void
    {
        $this->update(['is_trusted' => true]);

        SecurityLog::create([
            'user_id' => $this->user_id,
            'event_type' => 'device_trusted',
            'details' => [
                'device_id' => $this->id,
                'device_name' => $this->device_name,
            ]
        ]);
    }

    /**
     * Revoke device trust.
     */
    public function revoke(): void
    {
        $this->update(['is_trusted' => false]);

        SecurityLog::create([
            'user_id' => $this->user_id,
            'event_type' => 'device_revoked',
            'details' => [
                'device_id' => $this->id,
                'device_name' => $this->device_name,
            ]
        ]);
    }

    /**
     * Check if device is currently active (used in last 30 minutes).
     */
    public function isCurrentlyActive(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(30));
    }

    /**
     * Get session count for this device.
     */
    public function getSessionCountAttribute(): int
    {
        return \DB::table('sessions')
            ->where('user_id', $this->user_id)
            ->where('ip_address', $this->ip_address)
            ->where('user_agent', 'like', '%' . substr($this->device_data['user_agent'], 0, 50) . '%')
            ->count();
    }

    /**
     * Scope a query to only include trusted devices.
     */
    public function scopeTrusted($query)
    {
        return $query->where('is_trusted', true);
    }

    /**
     * Scope a query to only include untrusted devices.
     */
    public function scopeUntrusted($query)
    {
        return $query->where('is_trusted', false);
    }

    /**
     * Scope a query to only include active devices.
     */
    public function scopeActive($query)
    {
        return $query->where('last_seen_at', '>=', now()->subDays(30));
    }

    /**
     * Scope a query to only include mobile devices.
     */
    public function scopeMobile($query)
    {
        return $query->where('device_type', 'mobile');
    }

    /**
     * Scope a query to only include desktop devices.
     */
    public function scopeDesktop($query)
    {
        return $query->where('device_type', 'desktop');
    }

    /**
     * Scope a query to only include devices from specific IP.
     */
    public function scopeFromIP($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }
}
