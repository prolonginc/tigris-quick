<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class CustomerOrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Tigris Auto Glass {$this->order->order_number} • Pickup Now")
            ->view('emails.order-received', [
                'order' => $this->order,
                'customer' => $notifiable,
            ]);
    }
}
