<?php

namespace App\Models;

use App\Traits\User\HasPresentation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use \Laravel\Sanctum\HasApiTokens, HasFactory, Notifiable, HasPresentation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'login_alerts',
        'password',
        'avatar',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'is_active',
        'is_locked',
        'lock_reason',
        'locked_at',
        'password_changed_at',
        'password_expires_at',
        'must_change_password',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'membership_status',
        'membership_package',
        'membership_pay_date',
        'membership_expire',
        'email_notifications',
        'current_session_id',
        'trial_used_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'provider_token',
        'provider_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'provider_token' => 'encrypted',
            'provider_refresh_token' => 'encrypted',
            'is_active' => 'boolean',
            'login_alerts' => 'boolean',
            'email_notifications' => 'boolean',
            'is_locked' => 'boolean',
            'must_change_password' => 'boolean',
            'password_changed_at' => 'datetime',
            'password_expires_at' => 'datetime',
            'locked_at' => 'datetime',
            'last_login_at' => 'datetime',
            'membership_pay_date' => 'date',
            'membership_expire' => 'date',
            'trial_used_at' => 'datetime',
        ];
    }

    /**
     * Get the security logs for the user.
     */
    public function securityLogs()
    {
        return $this->hasMany(SecurityLog::class);
    }

    /**
     * Get the user's password history.
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Get the user's active sessions.
     */
    public function sessions()
    {
        return $this->hasMany(\Illuminate\Database\Eloquent\Model::class)
            ->where('payload', 'like', '%"' . $this->id . '"%');
    }

    /**
     * Determine if the user has verified their email address.
     *
     * Override default behavior to ensure admin-created users are considered verified.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if user has 2FA enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !empty($this->two_factor_secret) && !empty($this->two_factor_confirmed_at);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active ?? true;
    }

    /**
     * Check if user is locked.
     */
    public function isLocked(): bool
    {
        return $this->is_locked ?? false;
    }

    /**
     * Check if user can login.
     */
    public function canLogin(): bool
    {
        // Check email verification first if required
        if (config('auth.verification.must_verify_email', true) && !$this->hasVerifiedEmail()) {
            return false;
        }

        return $this->isActive() && !$this->isLocked();
    }

    /**
     * Check if user's password is expired.
     *
     * @return bool
     */
    public function isPasswordExpired(): bool
    {
        // If must_change_password is true, always consider as expired
        if ($this->must_change_password) {
            return true;
        }

        // If no expiration date set, check if it should expire
        if (!$this->password_expires_at) {
            return false;
        }

        // Check if expiration date has passed
        return $this->password_expires_at->isPast();
    }

    /**
     * Get days remaining until password expiration.
     *
     * @return int|null Returns null if password doesn't expire
     */
    public function getPasswordExpirationDays(): ?int
    {
        if (!$this->password_expires_at) {
            return null;
        }

        $days = now()->diffInDays($this->password_expires_at, false);

        return $days > 0 ? $days : 0;
    }

    /**
     * Set password expiration based on policy.
     *
     * @return bool
     */
    public function setPasswordExpiration(): bool
    {
        $expirationDays = (int) config('auth.security.password_expiration_days', 90);

        if ($expirationDays <= 0) {
            // Password never expires
            return $this->update([
                'password_expires_at' => null,
            ]);
        }

        return $this->update([
            'password_changed_at' => now(),
            'password_expires_at' => now()->addDays($expirationDays),
            'must_change_password' => false,
        ]);
    }

    /**
     * Check if password expiration should be enforced.
     *
     * @return bool
     */
    public function shouldExpirePassword(): bool
    {
        return (int) config('auth.security.password_expiration_days', 90) > 0;
    }

    /**
     * Mark password as requiring change on next login.
     *
     * @return bool
     */
    public function forcePasswordChange(): bool
    {
        return $this->update([
            'must_change_password' => true,
        ]);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // Prevent double-sending in the same request (Debounce)
        static $recentlySent = [];
        if (isset($recentlySent[$this->id])) {
            return;
        }
        $recentlySent[$this->id] = true;

        // Generate cryptographically secure 6-digit OTP
        // Using random_int() for cryptographic security (CSPRNG)
        $otp = (string) random_int(100000, 999999);

        // Store in Cache for 15 minutes
        // Key: email_verification_otp_{user_id}
        \Illuminate\Support\Facades\Cache::put('email_verification_otp_' . $this->id, $otp, now()->addMinutes(15));

        // Send custom notification
        $this->notify(new \App\Notifications\VerifyEmailOtp($otp));
    }

    /**
     * Get user's role names.
     */
    public function getRoleNamesAttribute(): array
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Get user's permission names.
     */
    public function getPermissionNamesAttribute(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->hasPermissionTo($permission);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->permissions()->whereIn('name', $permissions)->count() === count($permissions);
    }

    /**
     * Get user's security score (0-100).
     */
    protected function securityScore(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $score = 100; // Start with perfect score

                // Deduct if password is expired or expiring soon
                if ($this->isPasswordExpired()) {
                    $score -= 30; // Big penalty for expired password
                } elseif ($this->getPasswordExpirationDays() !== null && $this->getPasswordExpirationDays() <= 7) {
                    $score -= 15; // Moderate penalty if expiring within 7 days
                }

                // Deduct if no 2FA enabled
                if (!$this->hasTwoFactorEnabled()) {
                    $score -= 20;
                }

                // Deduct if email not verified
                if (!$this->hasVerifiedEmail()) {
                    $score -= 10;
                }

                // Deduct if account is inactive
                if (!$this->isActive()) {
                    $score -= 20;
                }

                // Deduct if account is locked
                if ($this->isLocked()) {
                    $score -= 30;
                }

                // Deduct if old password (not changed in 90 days)
                if (!$this->password_changed_at || $this->password_changed_at->diffInDays(now()) >= 90) {
                    $score -= 10;
                }

                return max(0, min(100, $score));
            }
        );
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include locked users.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    /**
     * Scope a query to only include unlocked users.
     */
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope a query to only include unverified users.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Scope a query to only include users with 2FA enabled.
     */
    public function scopeWithTwoFactor($query)
    {
        return $query->whereNotNull('two_factor_secret')
            ->whereNotNull('two_factor_confirmed_at');
    }

    /**
     * Scope a query to only include users without 2FA.
     */
    public function scopeWithoutTwoFactor($query)
    {
        return $query->whereNull('two_factor_secret')
            ->orWhereNull('two_factor_confirmed_at');
    }

    /**
     * Scope a query to only include users from specific OAuth provider.
     */
    public function scopeFromProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope a query to only include OAuth users.
     */
    public function scopeOAuth($query)
    {
        return $query->whereNotNull('provider')
            ->whereNotNull('provider_id');
    }

    /**
     * Scope a query to only include local users (no OAuth).
     */
    public function scopeLocal($query)
    {
        return $query->whereNull('provider')
            ->orWhereNull('provider_id');
    }

    /**
     * Check if user has active membership.
     */
    public function hasActiveMembership(): bool
    {
        if ($this->membership_status === 'active') {
            // Check expiry if set
            if ($this->membership_expire && \Illuminate\Support\Carbon::parse($this->membership_expire)->isPast()) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if current session is impersonating.
     */
    public function isImpersonating(): bool
    {
        return session()->has('impersonated_by');
    }

    /**
     * Get the admin user who is impersonating (if any).
     */
    public function getImpersonator(): ?User
    {
        if (!$this->isImpersonating()) {
            return null;
        }

        return User::find(session('impersonated_by'));
    }

    /**
     * Get impersonation details.
     */
    public function getImpersonationDetails(): ?array
    {
        if (!$this->isImpersonating()) {
            return null;
        }

        return [
            'admin_id' => session('impersonated_by'),
            'admin' => $this->getImpersonator(),
            'started_at' => session('impersonation_started_at'),
            'duration' => session('impersonation_started_at') ? now()->diffForHumans(session('impersonation_started_at'), true) : null,
        ];
    }
}
