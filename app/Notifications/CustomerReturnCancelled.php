<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class CustomerReturnCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param array $restoredItems list of ['name', 'description', 'quantity']
     */
    public function __construct(public Order $order, public array $restoredItems)
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
            ->subject('Your Order Return Was Cancelled')
            ->view('emails.order-return-cancelled', [
                'order' => $this->order,
                'customer' => $notifiable,
                'restoredItems' => $this->restoredItems,
                'audience' => 'customer',
            ]);
    }
}
