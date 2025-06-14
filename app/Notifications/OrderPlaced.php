<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    private string $appName;
    private string $logoUrl;
    private string $orderId;
    private string $deliveryDate;
    private string $deliveryLocation;
    private array $items;
    private bool $delayed;


    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $orderId,
        string $deliveryDate,
        string $deliveryLocation,
        array  $items = [],
        bool   $delayed = false,
    )
    {
        $this->appName = config('app.name');
        $this->logoUrl = asset('assets/logo.png');
        $this->orderId = $orderId;
        $this->deliveryDate = $deliveryDate;
        $this->deliveryLocation = $deliveryLocation;
        $this->items = $items;
        $this->delayed = $delayed;
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
        $orderLink = url(route('orders', [
            'order' => $this->orderId,
        ], false));

        $mailMessage = (new MailMessage)
            ->subject('Order Completion Notification')
            ->view('emails.build.OrderPlaced', [
                'logoUrl' => $this->logoUrl,
                'appName' => $this->appName,
                'orderId' => $this->orderId,
                'userName' => $notifiable->name,
                'userPhoto' => $notifiable->photo ?: 'anonymous.png',
                'orderLink' => $orderLink,
                'deliveryDate' => $this->deliveryDate,
                'deliveryLocation' => $this->deliveryLocation,
                'items' => $this->items,
                'delayed' => $this->delayed,
            ]);

        return $mailMessage;
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
