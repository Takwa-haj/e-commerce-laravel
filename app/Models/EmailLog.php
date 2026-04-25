<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    protected $fillable = [
        'recipient_type',
        'subject',
        'recipient_count',
        'sent_count',
        'failed_count',
        'recipients',
        'content',
        'sent_by',
        'order_id',
        'brevo_template_id',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'recipients' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scope for order-related emails
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId)
                     ->orWhereJsonContains('metadata->order_id', $orderId);
    }

    // Get recipient emails as array
    public function getRecipientEmailsAttribute()
    {
        if (is_string($this->recipients)) {
            return json_decode($this->recipients, true) ?? [];
        }
        return $this->recipients ?? [];
    }

    // Get metadata value
    public function getMeta($key, $default = null)
    {
        $meta = $this->metadata;
        if (is_string($meta)) {
            $meta = json_decode($meta, true) ?? [];
        }
        return $meta[$key] ?? $default;
    }
}
