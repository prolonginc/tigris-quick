<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class AdminOrderPlaced extends Notification implements ShouldQueue
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
            ->subject("{$this->order->user->business_name} {$this->order->order_number} • Pickup Now")
            ->view('emails.order', [
                'order' => $this->order,
                'customer' => $this->order->user,
            ]);
    }
}
