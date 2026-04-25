<?php

namespace App\Mail;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $refund;
    public $status;
    public $statusLabel;
    public $statusColor;

    /**
     * Create a new message instance.
     */
    public function __construct(RefundRequest $refund, $status)
    {
        $this->refund = $refund;
        $this->status = $status;
        $this->statusLabel = $this->getStatusLabel($status);
        $this->statusColor = $this->getStatusColor($status);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->status) {
            'approved' => '✅ Votre demande de remboursement a été approuvée',
            'rejected' => '❌ Votre demande de remboursement a été refusée',
            'processing' => '🔄 Votre remboursement est en cours de traitement',
            'completed' => '✓ Votre remboursement a été complété',
            'cancelled' => '⚠️ Votre demande de remboursement a été annulée',
            default => '📝 Mise à jour de votre demande de remboursement',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-status-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'En attente',
            'under_review' => 'En révision',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'processing' => 'En traitement',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default => $status,
        };
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => '#ffc107',
            'under_review' => '#17a2b8',
            'approved' => '#0d6efd',
            'rejected' => '#dc3545',
            'processing' => '#6c757d',
            'completed' => '#28a745',
            'cancelled' => '#343a40',
            default => '#6c757d',
        };
    }
}
