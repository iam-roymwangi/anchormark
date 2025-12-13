<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $orderData;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, array $orderData = [])
    {
        $this->order = $order;
        $this->orderData = $orderData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Order Received - ' . $this->order->order_number)
                    ->view('emails.order-notification')
                    ->with([
                        'order' => $this->order,
                        'orderData' => $this->orderData,
                    ]);
    }
}