<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSummaryMail extends BaseMail
{
    use Queueable, SerializesModels;

    public $order;
    public $settings;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->settings = \App\Models\Setting::first();
    }

    public function build()
    {
        return $this->subject('Résumé de votre commande #' . $this->order->id)
                    ->view('emails.order-summary')
                    ->with([
                        'order' => $this->order,
                        'settings' => $this->settings,
                    ]);
    }
}
