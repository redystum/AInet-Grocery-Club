<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountActivation extends Notification
{
    use Queueable;

    private string $appName;

    private string $logoUrl;

    private string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($url)
    {
        $this->appName = config('app.name');
        $this->logoUrl = asset('assets/logo.jpg');
        $this->url = $url;
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
        $userName = $notifiable->name;
        $userPhoto = $notifiable->photo ?: 'anonymous.png';

        return (new MailMessage)
            ->subject('Account Activation')
            ->view('emails.build.activateAccount', [
                'url' => $this->url,
                'appName' => $this->appName,
                'userName' => $userName,
                'userPhoto' => $userPhoto,
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
