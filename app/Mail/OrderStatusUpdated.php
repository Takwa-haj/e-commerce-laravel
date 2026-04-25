<?php

namespace App\Mail;

use App\Models\Order;
use Symfony\Component\Mime\Email;

class OrderStatusUpdated extends BaseMail
{
    public $order;
    public $oldStatus;
    public $newStatus;
    public $statusLabels = [
        'ordered' => 'Commandé',
        'confirmed' => 'Confirmé',
        'delivered' => 'Livré',
        'canceled' => 'Annulé'
    ];

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        return $this->subject('Mise à jour de votre commande #' . $this->order->id)
                    ->view('emails.order-status-updated')
                    ->with([
                        'order' => $this->order,
                        'oldStatus' => $this->statusLabels[$this->oldStatus] ?? $this->oldStatus,
                        'newStatus' => $this->statusLabels[$this->newStatus] ?? $this->newStatus,
                    ])
                    ->withSymfonyMessage(function (Email $message) {
                        $this->addMailtrapHeaders($message, [
                            'order_id' => $this->order->id,
                            'old_status' => $this->oldStatus,
                            'new_status' => $this->newStatus,
                            'notification_type' => 'order_status_update'
                        ]);
                    });
    }
}
