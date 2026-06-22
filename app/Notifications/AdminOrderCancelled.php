<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class AdminOrderCancelled extends Notification implements ShouldQueue
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
            ->subject('Will-Call Order Cancelled — ' . $this->order->order_number)
            ->view('emails.order-cancelled', [
                'order' => $this->order,
                'customer' => $this->order->user,
                'audience' => 'admin',
            ]);
    }
}
