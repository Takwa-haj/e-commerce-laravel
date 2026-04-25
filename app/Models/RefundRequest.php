<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'order_item_ids',
        'quantities',
        'reason_type',
        'reason_details',
        'additional_info',
        'attachments',
        'requested_amount',
        'refund_amount',
        'status',
        'admin_notes',
        'stripe_refund_id',
        'processed_at',
        'reviewed_at'
    ];

    protected $casts = [
        'order_item_ids' => 'array',
        'quantities' => 'array',
        'attachments' => 'array',
        'requested_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'reviewed_at' => 'datetime'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'refund_request_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Helper methods
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge bg-warning',
            'under_review' => 'badge bg-info',
            'approved' => 'badge bg-primary',
            'rejected' => 'badge bg-danger',
            'processing' => 'badge bg-secondary',
            'completed' => 'badge bg-success',
            'cancelled' => 'badge bg-dark',
            default => 'badge bg-secondary',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'under_review' => 'En examen',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'processing' => 'En traitement',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default => ucfirst($this->status),
        };
    }

    public function getReasonTypeText()
    {
        return match($this->reason_type) {
            'defective' => 'Produit défectueux',
            'wrong' => 'Mauvais article',
            'damaged' => 'Article endommagé',
            'other' => 'Autre raison',
            default => $this->reason_type,
        };
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getFormattedProcessedAt()
    {
        return $this->processed_at ? $this->processed_at->format('d/m/Y H:i') : null;
    }

    /**
     * Check if status can be updated
     */
    public function canUpdateTo($newStatus)
    {
        $validTransitions = [
            'pending' => ['under_review', 'approved', 'rejected', 'cancelled'],
            'under_review' => ['approved', 'rejected', 'cancelled'],
            'approved' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'rejected' => ['cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        return in_array($newStatus, $validTransitions[$this->status]);
    }

    /**
     * Get timeline steps based on current status
     */
    public function getTimelineSteps()
    {
        $steps = [
            'submitted' => [
                'label' => 'Demande soumise',
                'date' => $this->created_at,
                'completed' => true,
                'current' => false
            ],
            'under_review' => [
                'label' => 'En examen',
                'date' => in_array($this->status, ['under_review', 'approved', 'rejected', 'processing', 'completed', 'cancelled'])
                    ? $this->created_at->addMinutes(30)
                    : null,
                'completed' => in_array($this->status, ['under_review', 'approved', 'rejected', 'processing', 'completed', 'cancelled']),
                'current' => $this->status === 'under_review'
            ],
            'processed' => [
                'label' => 'Traitement',
                'date' => in_array($this->status, ['approved', 'rejected', 'processing', 'completed', 'cancelled'])
                    ? $this->processed_at
                    : null,
                'completed' => in_array($this->status, ['approved', 'rejected', 'processing', 'completed', 'cancelled']),
                'current' => in_array($this->status, ['approved', 'rejected', 'processing'])
            ],
            'completed' => [
                'label' => 'Terminé',
                'date' => $this->status === 'completed' ? $this->processed_at : null,
                'completed' => $this->status === 'completed',
                'current' => $this->status === 'completed'
            ]
        ];

        // Add cancellation step if applicable
        if ($this->status === 'cancelled') {
            $steps['cancelled'] = [
                'label' => 'Annulé',
                'date' => $this->processed_at,
                'completed' => true,
                'current' => true
            ];
        }

        return $steps;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accessors
    public function getTotalItemsAttribute()
    {
        return count($this->order_item_ids ?? []);
    }

    public function getIsProcessableAttribute()
    {
        return in_array($this->status, ['approved', 'processing']);
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['pending', 'under_review']);
    }
}
