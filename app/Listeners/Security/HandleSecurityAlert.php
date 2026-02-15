<?php

namespace App\Listeners\Security;

use App\Events\Security\SecurityAlert;
use App\Models\SecurityLog;
use App\Mail\NewDeviceAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HandleSecurityAlert
{
    /**
     * Handle the event.
     */
    public function handle(SecurityAlert $event): void
    {
        // Log to security_logs table
        SecurityLog::create([
            'user_id' => $event->user->id,
            'event_type' => $event->alertType,
            'description' => $this->getAlertDescription($event->alertType),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $event->metadata,
        ]);

        // Send email notification for critical alerts
        if ($this->shouldSendNotification($event->alertType)) {
            try {
                Mail::to($event->user)->send(new NewDeviceAlert(
                    $event->user,
                    $event->alertType,
                    $event->metadata
                ));
            } catch (\Exception $e) {
                Log::error('Failed to send security alert email', [
                    'user_id' => $event->user->id,
                    'alert_type' => $event->alertType,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::warning('Security alert triggered', [
            'user_id' => $event->user->id,
            'alert_type' => $event->alertType,
            'metadata' => $event->metadata,
        ]);
    }

    /**
     * Get human-readable alert description.
     */
    private function getAlertDescription(string $alertType): string
    {
        return match($alertType) {
            'new_device' => 'Login from new device detected',
            'suspicious_activity' => 'Suspicious activity detected',
            'multiple_failed_logins' => 'Multiple failed login attempts',
            'password_change' => 'Password changed',
            'account_locked' => 'Account locked due to security concerns',
            'two_factor_disabled' => 'Two-factor authentication disabled',
            default => 'Security event occurred',
        };
    }

    /**
     * Determine if notification should be sent.
     */
    private function shouldSendNotification(string $alertType): bool
    {
        return in_array($alertType, [
            'new_device',
            'suspicious_activity',
            'account_locked',
            'multiple_failed_logins',
        ]);
    }
}
