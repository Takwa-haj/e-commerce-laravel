<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'mode',
        'status',
        'stripe_payment_id',
    'stripe_payment_method',
    'stripe_customer_id',
    'stripe_refund_id',
        'payment_status',
        'payment_error',
        'payment_completed_at'
    ];

    protected $casts = [
        'stripe_metadata' => 'array',
        'payment_completed_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsPaid($stripeChargeId = null, $metadata = null)
    {
        $this->update([
            'status' => 'approved',
            'payment_status' => 'succeeded',
            'payment_completed_at' => now(),
            'stripe_charge_id' => $stripeChargeId,
            'stripe_metadata' => $metadata
        ]);
    }

    public function markAsFailed($error = null)
    {
        $this->update([
            'status' => 'failed',
            'payment_status' => 'failed',
            'payment_error' => $error
        ]);
    }
}
