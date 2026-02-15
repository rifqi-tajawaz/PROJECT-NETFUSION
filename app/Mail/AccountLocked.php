<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountLocked extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * The IP address that caused the lockout.
     *
     * @var string|null
     */
    public $ipAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?string $ipAddress = null)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Account Locked - Security Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-locked',
            with: [
                'user' => $this->user,
                'lockedAt' => $this->user->locked_at,
                'lockReason' => $this->user->lock_reason,
                'ipAddress' => $this->ipAddress,
                'timestamp' => now()->format('F j, Y, g:i a'),
            ]
        );
    }
}
