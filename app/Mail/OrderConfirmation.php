<?php

namespace App\Mail;

use App\Models\Order;
use Symfony\Component\Mime\Email;

class OrderConfirmation extends BaseMail
{
    public $order;
    public $orderItems;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->orderItems = $order->orderItems()->with('product')->get();
    }

    public function build()
    {
        return $this->subject('Confirmation de votre commande #' . $this->order->id)
                    ->view('emails.order-confirmation')
                    ->with([
                        'order' => $this->order,
                        'orderItems' => $this->orderItems
                    ])
                    ->withSymfonyMessage(function (Email $message) {
                        $this->addMailtrapHeaders($message, [
                            'order_id' => $this->order->id,
                            'customer_email' => $this->order->email,
                            'notification_type' => 'order_confirmation'
                        ]);
                    });
    }
}
