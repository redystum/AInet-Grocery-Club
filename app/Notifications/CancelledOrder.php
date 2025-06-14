<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancelledOrder extends Notification
{
    use Queueable;
    
    private string $appName;
    private string $logoUrl;
    private string $orderId;
    private string $orderDate;
    private string $cancellationReason;
    private string $cancellationDetails;
    private string $refundAmount;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $orderId,
        string $orderDate,
        string $cancellationReason,
        string $cancellationDetails,
        string $refundAmount = '0.00',
    ) {
        $this->appName = config('app.name');
        $this->logoUrl = asset('assets/logo.jpg');
        $this->orderId = $orderId;
        $this->orderDate = $orderDate;
        $this->cancellationReason = $cancellationReason;
        $this->cancellationDetails = $cancellationDetails;
        $this->refundAmount = $refundAmount;
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

        return (new MailMessage)
            ->subject('Order Cancellation Notification')
            ->view('emails.pages.cancelledOrder', [
                'logoUrl' => $this->logoUrl,
                'appName' => $this->appName,
                'orderId' => $this->orderId,
                'orderDate' => $this->orderDate,
                'cancellationReason' => $this->cancellationReason,
                'cancellationDetails' => $this->cancellationDetails,
                'refundAmount' => $this->refundAmount,
                'userName' => $notifiable->name,
                'userPhoto' => $notifiable->photo ?: 'anonymous.png',
                'orderLink' => $orderLink,
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
