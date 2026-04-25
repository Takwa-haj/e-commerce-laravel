<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'tagline',
        'title',
        'subtitle',
        'description',
        'link',
        'button_text',
        'price',
        'price_text',
        'image',
        'featured_image',
        'type',
        'background_color',
        'text_position',
        'is_active',
        'order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Accessor for display price
    public function getDisplayPriceAttribute()
    {
        if ($this->price_text) {
            return $this->price_text;
        }

        if ($this->price) {
            return '€' . number_format($this->price, 2);
        }

        return null;
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        return asset('uploads/slides/' . $this->image);
    }

    // Get featured image URL
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('uploads/slides/featured/' . $this->featured_image);
        }
        return null;
    }

    // Scope for active slides
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for homepage slides
    public function scopeHome($query)
    {
        return $query->where('type', 'home');
    }

    // Order by position
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }
}
