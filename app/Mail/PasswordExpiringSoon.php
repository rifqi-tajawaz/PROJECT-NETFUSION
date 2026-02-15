<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordExpiringSoon extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The number of days remaining until password expires.
     *
     * @var int
     */
    public $daysRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(int $daysRemaining)
    {
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $urgency = match(true) {
            $this->daysRemaining === 1 => 'URGENT: ',
            $this->daysRemaining <= 3 => 'REMINDER: ',
            default => '',
        };

        return new Envelope(
            subject: $urgency . 'Your Password Will Expire in ' . $this->daysRemaining . ' Day' . ($this->daysRemaining > 1 ? 's' : ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-expiring-soon',
            with: [
                'daysRemaining' => $this->daysRemaining,
                'expiryDate' => now()->addDays($this->daysRemaining)->format('F j, Y'),
                'urgencyLevel' => $this->getUrgencyLevel(),
            ]
        );
    }

    /**
     * Get the urgency level based on days remaining.
     *
     * @return string
     */
    protected function getUrgencyLevel(): string
    {
        return match(true) {
            $this->daysRemaining === 1 => 'critical',
            $this->daysRemaining <= 3 => 'high',
            $this->daysRemaining <= 7 => 'medium',
            default => 'low',
        };
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
