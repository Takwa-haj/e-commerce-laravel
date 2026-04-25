<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'size_id',
        'color_id',
        'price',
        'quantity',
        'options',
        'rstatus',
        'refund_status',
        'refund_reason',
        'refund_requested_at',
        'refund_processed_at',
        'refund_amount',
        'stripe_refund_id',
        'admin_notes',
        'stock_updated'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime',
        'options' => 'array',
        'stock_updated' => 'boolean'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function refundRequests()
    {
        return $this->belongsToMany(RefundRequest::class, 'refund_request_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function updateStockAfterOrder()
    {
        if ($this->variant_id) {
            $variant = ProductVariant::find($this->variant_id);
            if ($variant) {
                $variant->decrementStock($this->quantity);

                if ($variant->product) {
                    $variant->product->updateStockStatus();
                }

                $this->stock_updated = true;
                $this->save();

                return true;
            }
        }

        if ($this->product) {
            $this->product->decrementStock($this->quantity);
            $this->stock_updated = true;
            $this->save();

            return true;
        }

        return false;
    }

    public function updateVariantStock()
    {
        if ($this->variant_id) {
            $variant = ProductVariant::find($this->variant_id);
            if ($variant) {
                $variant->decrementStock($this->quantity);
                return true;
            }
        }
        return false;
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->product->name;

        if ($this->variant) {
            $name .= ' (' . $this->variant->display_name . ')';
        } elseif ($this->size || $this->color) {
            $parts = [];
            if ($this->size) {
                $parts[] = 'Taille: ' . $this->size->name;
            }
            if ($this->color) {
                $parts[] = 'Couleur: ' . $this->color->name;
            }
            if (!empty($parts)) {
                $name .= ' (' . implode(' | ', $parts) . ')';
            }
        }

        return $name;
    }

    public function getRefundStatusBadgeClass()
    {
        return match($this->refund_status) {
            'none' => 'badge bg-secondary',
            'requested' => 'badge bg-warning text-dark',
            'approved' => 'badge bg-info',
            'rejected' => 'badge bg-danger',
            'processing' => 'badge bg-primary',
            'completed' => 'badge bg-success',
            default => 'badge bg-secondary',
        };
    }

    public function getRefundStatusText()
    {
        return match($this->refund_status) {
            'none' => 'Aucun',
            'requested' => 'Demandé',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'processing' => 'En traitement',
            'completed' => 'Terminé',
            default => $this->refund_status,
        };
    }

    public function canRequestRefund()
    {
        return $this->refund_status == 'none' &&
               $this->order->status == 'delivered' &&
               $this->order->delivered_date &&
               now()->diffInDays($this->order->delivered_date) <= 14;
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
