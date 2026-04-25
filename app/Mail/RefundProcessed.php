<?php

namespace App\Mail;

use Stripe\Refund;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundProcessed extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;
    public $order;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
        $this->order = $refund->order;
        $this->user = $this->order->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Remboursement traité pour la commande #{$this->order->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-processed',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
