<?php

namespace App\Mail;

use App\Models\Order;
use Symfony\Component\Mime\Email;

class PaymentFailed extends BaseMail
{
    public $order;
    public $error;

    public function __construct(Order $order, $error = null)
    {
        $this->order = $order;
        $this->error = $error;
    }

    public function build()
    {
        return $this->subject('Échec du paiement - Commande #' . $this->order->id)
                    ->view('emails.payment-failed')
                    ->with([
                        'order' => $this->order,
                        'error' => $this->error
                    ])
                    ->withSymfonyMessage(function (Email $message) {
                        $this->addMailtrapHeaders($message, [
                            'order_id' => $this->order->id,
                            'notification_type' => 'payment_failed',
                            'error_code' => $this->error ? 'payment_error' : 'unknown'
                        ]);
                    });
    }
}
