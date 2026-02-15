<?php

namespace App\Mail;

use App\Models\UserDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDeviceAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $device;
    public $request;

    /**
     * Create a new message instance.
     */
    public function __construct(UserDevice $device, $request)
    {
        $this->device = $device;
        $this->request = $request;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Device Login Alert - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-device-alert',
            with: [
                'device' => $this->device,
                'ip' => $this->request->ip(),
                'userAgent' => $this->request->userAgent(),
                'location' => $this->getLocationFromIP($this->request->ip()),
            ]
        );
    }

    /**
     * Get location from IP.
     */
    protected function getLocationFromIP($ip)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,timezone");
            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'success') {
                return $data;
            }
        } catch (\Exception $e) {
            // Failed to get location
        }

        return null;
    }
}
