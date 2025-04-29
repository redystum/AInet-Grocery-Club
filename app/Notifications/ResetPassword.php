<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class ResetPassword extends Notification
{
    use Queueable;

    private string $appName;

    private string $loginTime;

    private string $ipAddress;

    private array $deviceInfo;

    private string $loginLocation;

    private string $logoUrl;

    private string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->appName = config('app.name');
        $this->loginTime = now()->format('F j, Y \a\t g:i A T');
        $this->ipAddress = app()->isProduction() ? request()->ip() : '194.210.216.34';
        $this->logoUrl = asset('assets/logo.jpg');

        $agent = new Agent;
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
            $location->countryName,
        ])) : 'Location unavailable';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $userName = $notifiable->name;
        $userPhoto = $notifiable->photo ?: 'anonymous.png';

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->view('emails.pages.resetPassword', [
                'url' => $url,
                'expirationTime' => 1, // 1 hour
                'appName' => $this->appName,
                'loginTime' => $this->loginTime,
                'ipAddress' => $this->ipAddress,
                'userName' => $userName,
                'deviceInfo' => $this->deviceInfo,
                'userPhoto' => $userPhoto,
                'loginLocation' => $this->loginLocation,
                'logoUrl' => $this->logoUrl,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
