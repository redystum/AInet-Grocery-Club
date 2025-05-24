<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefusedCancellationOrder extends Notification
{
    use Queueable;

    private string $appName;
    private string $logoUrl;
    private string $orderId;
    private string $refusalReason;
    private string $refusalDetails;
    private string $expectedShipDate;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $orderId,
        string $refusalReason,
        string $refusalDetails,
        string $expectedShipDate = 'Within 2-3 business days',
    )
    {
        $this->appName = config('app.name');
        $this->logoUrl = asset('assets/logo.jpg');
        $this->orderId = $orderId;
        $this->refusalReason = $refusalReason;
        $this->refusalDetails = $refusalDetails;
        $this->expectedShipDate = $expectedShipDate;
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
            ->subject('Cancellation Request Denied - Order #' . $this->orderId)
            ->view('emails.pages.refusedCancellationOrder', [
                'logoUrl' => $this->logoUrl,
                'appName' => $this->appName,
                'orderId' => $this->orderId,
                'refusalReason' => $this->refusalReason,
                'refusalDetails' => $this->refusalDetails,
                'expectedShipDate' => $this->expectedShipDate,
                'orderLink' => $orderLink,
                'userName' => $notifiable->name,
                'userPhoto' => $notifiable->photo ?: 'anonymous.png',
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
