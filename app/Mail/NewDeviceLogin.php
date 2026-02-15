<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDeviceLogin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The IP address of the new login.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * The user agent string.
     *
     * @var string
     */
    public $userAgent;

    /**
     * The location (if available).
     *
     * @var string|null
     */
    public $location;

    /**
     * The login timestamp.
     *
     * @var string
     */
    public $loginTime;

    /**
     * Create a new message instance.
     */
    public function __construct(string $ipAddress, string $userAgent, ?string $location = null)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->location = $location;
        $this->loginTime = now()->format('F j, Y, g:i a');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 New Device Login - Security Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-device-login',
            with: [
                'ipAddress' => $this->ipAddress,
                'userAgent' => $this->getUserAgentInfo(),
                'location' => $this->location,
                'loginTime' => $this->loginTime,
            ]
        );
    }

    /**
     * Get user-friendly user agent information.
     *
     * @return array
     */
    protected function getUserAgentInfo(): array
    {
        $ua = $this->userAgent;

        // Detect browser
        $browser = 'Unknown';
        if (preg_match('/Chrome/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari/i', $ua) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/Opera/i', $ua)) {
            $browser = 'Opera';
        }

        // Detect OS
        $os = 'Unknown';
        if (preg_match('/Windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iOS/i', $ua)) {
            $os = 'iOS';
        }

        // Detect device type
        $device = 'Desktop';
        if (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) {
            $device = preg_match('/iPad/i', $ua) ? 'Tablet' : 'Mobile';
        }

        return [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'raw' => $ua,
        ];
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
