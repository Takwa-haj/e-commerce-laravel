<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'address_type',
        'is_default_shipping',
        'is_default_billing',
        'numero',
    ];

    protected $casts = [
        'is_default_shipping' => 'boolean',
        'is_default_billing' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for default shipping addresses.
     */
    public function scopeDefaultShipping($query)
    {
        return $query->where('is_default_shipping', true);
    }

    /**
     * Scope for default billing addresses.
     */
    public function scopeDefaultBilling($query)
    {
        return $query->where('is_default_billing', true);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the formatted address attribute.
     */
    public function getFormattedAddressAttribute(): string
    {
        $address = $this->address_line_1;

        if ($this->address_line_2) {
            $address .= ', ' . $this->address_line_2;
        }

        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
        $address .= ', ' . $this->country;

        return $address;
    }

    /**
     * Check if this is a shipping address.
     */
    public function isShipping(): bool
    {
        return in_array($this->address_type, ['shipping', 'both']);
    }

    /**
     * Check if this is a billing address.
     */
    public function isBilling(): bool
    {
        return in_array($this->address_type, ['billing', 'both']);
    }
}
