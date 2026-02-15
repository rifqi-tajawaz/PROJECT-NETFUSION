<?php

namespace App\Traits\User;

trait HasPresentation
{
    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->provider && in_array($this->provider, ['google', 'github', 'facebook'])) {
            return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=200&d=mp';
        }

        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=200&d=identicon';
    }

    /**
     * Get user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? explode('@', $this->email)[0];
    }

    /**
     * Get user's provider name.
     */
    public function getProviderNameAttribute(): ?string
    {
        if (!$this->provider) {
            return null;
        }

        $providers = [
            'google' => 'Google',
            'facebook' => 'Facebook',
            'github' => 'GitHub',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
            'microsoft' => 'Microsoft',
        ];

        return $providers[$this->provider] ?? ucfirst($this->provider);
    }

    /**
     * Get user's account status.
     */
    public function getAccountStatusAttribute(): string
    {
        if ($this->isLocked()) {
            return 'locked';
        }

        if (!$this->isActive()) {
            return 'inactive';
        }

        if (!$this->hasVerifiedEmail()) {
            return 'pending';
        }

        return 'active';
    }

    /**
     * Get user's account status color.
     */
    public function getAccountStatusColorAttribute(): string
    {
        return [
            'locked' => 'danger',
            'inactive' => 'warning',
            'pending' => 'info',
            'active' => 'success',
        ][$this->account_status] ?? 'secondary';
    }

    /**
     * Get user's security level.
     */
    public function getSecurityLevelAttribute(): string
    {
        $score = $this->security_score;

        if ($score >= 80) {
            return 'excellent';
        }

        if ($score >= 60) {
            return 'good';
        }

        if ($score >= 40) {
            return 'fair';
        }

        return 'poor';
    }

    /**
     * Get user's security level color.
     */
    public function getSecurityLevelColorAttribute(): string
    {
        return [
            'excellent' => 'success',
            'good' => 'info',
            'fair' => 'warning',
            'poor' => 'danger',
        ][$this->security_level] ?? 'secondary';
    }

    /**
     * Get user's last login information.
     */
    public function getLastLoginInfoAttribute(): array
    {
        return [
            'at' => $this->last_login_at,
            'ip' => $this->last_login_ip,
            'ago' => $this->last_login_at ? $this->last_login_at->diffForHumans() : null,
        ];
    }

    /**
     * Get user's registration date.
     */
    public function getRegisteredAtAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get user's registration date relative to now.
     */
    public function getRegisteredAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get membership status badge color.
     */
    public function getMembershipStatusColorAttribute(): string
    {
        if ($this->membership_expire && \Illuminate\Support\Carbon::parse($this->membership_expire)->isPast()) {
            return 'danger';
        }

        return match ($this->membership_status) {
            'active' => 'success',
            'trial' => 'warning',
            'expired' => 'danger',
            default => 'secondary',
        };
    }
}
