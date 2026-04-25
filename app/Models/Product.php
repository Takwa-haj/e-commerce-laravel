<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'short_description',
        'description',
        'image',
        'images',
        'regular_price',
        'sale_price',
        'SKU',
        'quantity',
        'stock_status',
        'featured',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'has_discount',
        'current_price',
        'discount_percentage',
        'average_rating',
        'review_count',
        'total_quantity'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, "brand_id");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    public function getHasDiscountAttribute()
    {
        return $this->sale_price !== null && $this->sale_price < $this->regular_price;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->hasDiscount ? $this->sale_price : $this->regular_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->hasDiscount) {
            return 0;
        }
        return round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
    }

    /**
     * Get total quantity - ALWAYS calculate from variants if they exist
     */
    public function getTotalQuantityAttribute()
    {
        if ($this->variants()->exists()) {
            return $this->variants()->sum('quantity');
        }
        return (int)$this->quantity;
    }

    /**
     * Update stock status and quantity based on variants
     */
    public function updateStockStatus()
    {
        if ($this->variants()->exists()) {
            $totalQuantity = $this->variants()->sum('quantity');
            $hasStock = $this->variants()->where('quantity', '>', 0)->exists();

            $this->quantity = $totalQuantity;
            $this->stock_status = $hasStock ? 'instock' : 'outofstock';
        } else {
            $this->stock_status = $this->quantity > 0 ? 'instock' : 'outofstock';
        }

        $this->save();
        return $this;
    }

    public function findVariant($sizeId = null, $colorId = null)
    {
        return $this->variants()
            ->when($sizeId, function($query) use ($sizeId) {
                return $query->where('size_id', $sizeId);
            })
            ->when($colorId, function($query) use ($colorId) {
                return $query->where('color_id', $colorId);
            })
            ->first();
    }

    public function getAvailableVariants()
    {
        return $this->variants()
            ->with(['size', 'color'])
            ->where('quantity', '>', 0)
            ->get();
    }

    public function hasVariant($sizeId = null, $colorId = null)
    {
        return $this->variants()
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->exists();
    }

    public function getVariantQuantity($sizeId = null, $colorId = null)
    {
        $variant = $this->findVariant($sizeId, $colorId);
        return $variant ? $variant->quantity : 0;
    }

    public function decrementStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->decrement('quantity', $quantity);
            $this->updateStockStatus();
            return true;
        }
        return false;
    }
}
