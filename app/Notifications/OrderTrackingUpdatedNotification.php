<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderTrackingUpdatedNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
        
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Tracking Information for Order #' . $this->order->id)
            ->greeting('Hello ' . $this->order->user->name . ',')
            ->line('Your order has been shipped! Here are your tracking details:')
            ->line('**Order Details:**')
            ->line('- Order ID: #' . $this->order->id)
            ->line('- Status: Shipped')
            ->line('- Shipping Date: ' . $this->order->shipped_date->format('F d, Y'))
            ->line('**Tracking Information:**')
            ->line('- Tracking Number: **' . $this->order->tracking_number . '**')
            ->line('- Carrier: ' . ($this->order->shipping_carrier ?? 'N/A'))
            ->line('- Estimated Delivery: ' . ($this->order->estimated_delivery ? $this->order->estimated_delivery->format('F d, Y') : 'To be determined'));

        // Add tracking link based on carrier
        if ($this->order->shipping_carrier == 'UPS') {
            $mail->action('Track Package on UPS', 'https://www.ups.com/track?loc=en_US&tracknum=' . $this->order->tracking_number);
        } elseif ($this->order->shipping_carrier == 'FedEx') {
            $mail->action('Track Package on FedEx', 'https://www.fedex.com/fedextrack/?trknbr=' . $this->order->tracking_number);
        } elseif ($this->order->shipping_carrier == 'DHL') {
            $mail->action('Track Package on DHL', 'https://www.dhl.com/en/express/tracking.html?AWB=' . $this->order->tracking_number);
        } elseif (in_array($this->order->shipping_carrier, ['La Poste', 'Colissimo'])) {
            $mail->action('Track Package on La Poste', 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . $this->order->tracking_number);
        } else {
            $mail->line('You can use the tracking number above on the carrier\'s website.');
        }

        $mail->line('Thank you for shopping with us!')
             ->action('View Order Details', url('/user/orders/' . $this->order->id));

        return $mail;
    }
}
