<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification
{
    use Queueable;

    private string $appName;

    private string $logoUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->appName = config('app.name');
        $this->logoUrl = asset('assets/logo.jpg');
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
        return (new MailMessage)
            ->subject('Welcome to '.$this->appName)
            ->view('emails.build.welcome', [
                'userName' => $notifiable->name,
                'userPhoto' => $notifiable->photo ?: 'anonymous.png',
                'logoUrl' => $this->logoUrl,
                'appName' => $this->appName,
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
