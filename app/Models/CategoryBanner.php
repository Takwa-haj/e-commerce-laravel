<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'alt_text',
        'price_from',
        'price_text',
        'button_text',
        'button_link',
        'position',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_from' => 'decimal:2'
    ];

    /**
     * Get the display price text
     */
    public function getDisplayPriceAttribute()
    {
        if ($this->price_text) {
            return $this->price_text;
        }

        if ($this->price_from) {
            return 'À partir de €' . number_format($this->price_from, 0);
        }

        return null;
    }

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc')
                    ->orderBy('sort_order', 'asc');
    }
}
