<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\Mime\Email;

class OrderInvoiceMail extends BaseMail
{
    public $order;
    public $settings;
    public $pdf;

    public function __construct(Order $order, $pdf = null)
    {
        $this->order = $order;
        $this->pdf = $pdf;
        $this->settings = Setting::getSettings();
    }

    public function build()
    {
        $email = $this->subject('Votre facture - Commande #' . $this->order->id)
            ->markdown('emails.order-invoice')
            ->with([
                'order' => $this->order,
                'settings' => $this->settings,
            ]);

        if ($this->pdf) {
            $email->attachData($this->pdf->output(), 'facture-commande-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $email->withSymfonyMessage(function (Email $message) {
            $this->addMailtrapHeaders($message, [
                'order_id' => $this->order->id,
                'document_type' => 'invoice',
                'notification_type' => 'invoice'
            ]);
        });
    }
}
