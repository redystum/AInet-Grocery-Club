<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class PasswordResetSuccessNotification extends Mailable
{
    use Queueable, SerializesModels;

    private string $appName;
    private string $loginTime;
    private string $ipAddress;
    private string $userName;
    private array $deviceInfo;
    private string $loginLocation;
    private string $userPhoto;
    private string $logoUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->appName = config('app.name');
        $this->loginTime = now()->format('F j, Y \a\t g:i A T');
        $this->ipAddress = app()->isProduction() ? request()->ip() : "194.210.216.34";
        $this->userName = $user->name;
        $this->userPhoto = $user->photo ?: "anonymous.png";
        $this->logoUrl = asset('assets/logo.jpg');

        // Parse device info using Jenssegers Agent
        $agent = new Agent();
        $this->deviceInfo = [
            'device' => $agent->device() ?: 'Unknown Device',
            'platform' => $agent->platform() ?: 'Unknown OS',
            'browser' => $agent->browser() ?: 'Unknown Browser',
            'is_desktop' => $agent->isDesktop(),
            'is_mobile' => $agent->isMobile(),
        ];

        $location = Location::get($this->ipAddress);
        $this->loginLocation = $location ? implode(', ', array_filter([
            $location->cityName,
            $location->regionName,
            $location->countryName
        ])) : 'Location unavailable';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your password has been updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pages.passwordResetSuccess',
            with: [
                'appName' => $this->appName,
                'loginTime' => $this->loginTime,
                'ipAddress' => $this->ipAddress,
                'userName' => $this->userName,
                'deviceInfo' => $this->deviceInfo,
                'userPhoto' => $this->userPhoto,
                'loginLocation' => $this->loginLocation,
                'logoUrl' => $this->logoUrl,
            ],
        );
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
