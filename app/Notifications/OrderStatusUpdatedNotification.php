<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Order #' . $this->order->id . ' Status Update')
            ->greeting('Hello ' . $this->order->user->name . ',')
            ->line('Your order status has been updated.')
            ->line('**Order Details:**')
            ->line('- Order ID: #' . $this->order->id)
            ->line('- Previous Status: ' . ucfirst($this->oldStatus))
            ->line('- Current Status: ' . ucfirst($this->newStatus))
            ->line('- Updated On: ' . now()->format('F d, Y H:i'));

        if ($this->newStatus == 'shipped' && $this->order->tracking_number) {
            $mail->line('**Tracking Information:**')
                 ->line('- Tracking Number: ' . $this->order->tracking_number)
                 ->line('- Carrier: ' . ($this->order->shipping_carrier ?? 'N/A'))
                 ->line('- Estimated Delivery: ' . ($this->order->estimated_delivery ? $this->order->estimated_delivery->format('F d, Y') : 'Not specified'));

            if ($this->order->shipping_carrier == 'UPS') {
                $mail->action('Track Package', 'https://www.ups.com/track?loc=en_US&tracknum=' . $this->order->tracking_number);
            } elseif ($this->order->shipping_carrier == 'FedEx') {
                $mail->action('Track Package', 'https://www.fedex.com/fedextrack/?trknbr=' . $this->order->tracking_number);
            } elseif ($this->order->shipping_carrier == 'DHL') {
                $mail->action('Track Package', 'https://www.dhl.com/en/express/tracking.html?AWB=' . $this->order->tracking_number);
            } elseif (in_array($this->order->shipping_carrier, ['La Poste', 'Colissimo'])) {
                $mail->action('Track Package', 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . $this->order->tracking_number);
            }
        }

        $mail->line('Thank you for shopping with us!')
             ->action('View Order Details', url('/user/orders/' . $this->order->id));

        return $mail;
    }
}
