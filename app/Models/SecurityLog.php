<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the user that owns the security log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs of a given type.
     */
    public function scopeEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope a query to only include logs from a given IP address.
     */
    public function scopeIpAddress($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Scope a query to only include logs for a given user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include recent logs (last 24 hours).
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subHours(24));
    }

    /**
     * Get metadata value by key.
     */
    public function getMetadata($key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Check if log contains specific metadata key.
     */
    public function hasMetadata($key): bool
    {
        return isset($this->metadata[$key]);
    }

    /**
     * Get human-readable event type.
     */
    public function getEventTypeNameAttribute(): string
    {
        $eventTypes = [
            'LOGIN_SUCCESS' => 'Login Successful',
            'LOGIN_FAILED' => 'Login Failed',
            'LOGOUT' => 'Logout',
            'PASSWORD_CHANGED' => 'Password Changed',
            '2FA_ENABLED' => 'Two-Factor Authentication Enabled',
            '2FA_DISABLED' => 'Two-Factor Authentication Disabled',
            '2FA_VERIFICATION_SUCCESS' => '2FA Verification Success',
            '2FA_VERIFICATION_FAILED' => '2FA Verification Failed',
            '2FA_RECOVERY_CODE_USED' => '2FA Recovery Code Used',
            'ACCOUNT_LOCKED' => 'Account Locked',
            'ACCOUNT_UNLOCKED' => 'Account Unlocked',
            'SUSPICIOUS_ACTIVITY' => 'Suspicious Activity Detected',
            'IP_BLOCKED' => 'IP Address Blocked',
            'LOGIN_FROM_NEW_LOCATION' => 'Login from New Location',
            'SUSPICIOUS_MULTIPLE_FAILED_ATTEMPTS' => 'Multiple Failed Login Attempts',
            'OAUTH_LINKED' => 'OAuth Account Linked',
            'OAUTH_UNLINKED' => 'OAuth Account Unlinked',
        ];

        return $eventTypes[$this->event_type] ?? $this->event_type;
    }

    /**
     * Get risk level based on event type.
     */
    public function getRiskLevelAttribute(): string
    {
        $highRiskEvents = [
            'LOGIN_FAILED',
            '2FA_VERIFICATION_FAILED',
            'ACCOUNT_LOCKED',
            'SUSPICIOUS_ACTIVITY',
            'IP_BLOCKED',
            'LOGIN_FROM_NEW_LOCATION',
            'SUSPICIOUS_MULTIPLE_FAILED_ATTEMPTS',
        ];

        $mediumRiskEvents = [
            'PASSWORD_CHANGED',
            '2FA_DISABLED',
            'OAUTH_UNLINKED',
        ];

        if (in_array($this->event_type, $highRiskEvents)) {
            return 'high';
        }

        if (in_array($this->event_type, $mediumRiskEvents)) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Get risk level color.
     */
    public function getRiskLevelColorAttribute(): string
    {
        return [
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
        ][$this->risk_level] ?? 'secondary';
    }

    /**
     * Get location information from IP address.
     */
    public function getLocationAttribute(): ?array
    {
        if (!$this->ip_address) {
            return null;
        }

        // This is a simplified implementation
        // In production, you might want to use a proper IP geolocation service
        return [
            'ip' => $this->ip_address,
            'country' => 'Unknown',
            'city' => 'Unknown',
            'latitude' => null,
            'longitude' => null,
        ];
    }

    /**
     * Get device information from user agent.
     */
    public function getDeviceInfoAttribute(): array
    {
        if (!$this->user_agent) {
            return [
                'browser' => 'Unknown',
                'os' => 'Unknown',
                'device_type' => 'Unknown',
            ];
        }

        // This is a simplified implementation
        // In production, you might want to use a proper user agent parser
        return [
            'browser' => 'Unknown',
            'os' => 'Unknown',
            'device_type' => 'Unknown',
        ];
    }
}
