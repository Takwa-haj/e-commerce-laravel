<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnifiedAddress extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'landmark',
        'address_type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for default addresses.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for addresses belonging to a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for shipping addresses.
     */
    public function scopeShipping($query)
    {
        return $query->whereIn('address_type', ['shipping', 'both']);
    }

    /**
     * Scope for billing addresses.
     */
    public function scopeBilling($query)
    {
        return $query->whereIn('address_type', ['billing', 'both']);
    }

    /**
     * Get formatted address.
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get address as HTML.
     */
    public function getDisplayHtmlAttribute(): string
    {
        $html = "<strong>{$this->full_name}</strong><br>";
        $html .= "{$this->address_line_1}<br>";
        if ($this->address_line_2) {
            $html .= "{$this->address_line_2}<br>";
        }
        $html .= "{$this->city}, {$this->state} {$this->postal_code}<br>";
        $html .= "{$this->country}<br>";
        if ($this->landmark) {
            $html .= "<small class='text-muted'>Repère: {$this->landmark}</small><br>";
        }
        $html .= "<strong>Téléphone:</strong> {$this->phone}";

        return $html;
    }

    /**
     * Get short display text.
     */
    public function getShortDisplayAttribute(): string
    {
        return "{$this->full_name}, {$this->address_line_1}, {$this->city} {$this->postal_code}";
    }

    /**
     * Check if address can be used for shipping.
     */
    public function isShipping(): bool
    {
        return in_array($this->address_type, ['shipping', 'both']);
    }

    /**
     * Check if address can be used for billing.
     */
    public function isBilling(): bool
    {
        return in_array($this->address_type, ['billing', 'both']);
    }
}
