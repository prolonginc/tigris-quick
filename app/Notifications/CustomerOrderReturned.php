<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class CustomerOrderReturned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param array $returnedItems list of ['name', 'description', 'quantity']
     */
    public function __construct(public Order $order, public array $returnedItems)
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
            ->subject('Your Will-Call Order Return Was Processed')
            ->view('emails.order-returned', [
                'order' => $this->order,
                'customer' => $notifiable,
                'returnedItems' => $this->returnedItems,
                'audience' => 'customer',
            ]);
    }
}
