<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'sku',
        'price',
        'sale_price',
        'quantity',
        'is_active',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'quantity' => 'integer'
    ];

    protected $appends = [
        'has_discount',
        'current_price',
        'display_name',
        'stock_status',
        'stock_status_label',
        'stock_status_color'
    ];

    protected static function booted()
    {
        static::saved(function ($variant) {
            if ($variant->product) {
                $variant->product->updateStockStatus();
            }
        });

        static::deleted(function ($variant) {
            if ($variant->product) {
                $variant->product->updateStockStatus();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function decrementStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    public function incrementStock($quantity)
    {
        $this->quantity += $quantity;
        $this->save();
        return true;
    }

    /**
     * Reserve quantity - called by OrderItemObserver
     */
    public function reserveQuantity($quantity)
    {
        return $this->decrementStock($quantity);
    }

    /**
     * Release quantity - when order is cancelled
     */
    public function releaseQuantity($quantity)
    {
        return $this->incrementStock($quantity);
    }

    public function getHasDiscountAttribute()
    {
        return $this->sale_price !== null &&
               $this->sale_price > 0 &&
               $this->sale_price < $this->price;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->has_discount ? $this->sale_price : $this->price;
    }

    public function getDisplayNameAttribute()
    {
        $parts = [];
        if ($this->size) $parts[] = $this->size->name;
        if ($this->color) $parts[] = $this->color->name;
        return implode(' - ', $parts) ?: 'Default';
    }

    public function getStockStatusAttribute()
    {
        if ($this->quantity > 10) return 'instock';
        if ($this->quantity > 0) return 'lowstock';
        return 'outofstock';
    }

    public function getStockStatusLabelAttribute()
    {
        return [
            'instock' => 'En stock',
            'lowstock' => 'Stock faible',
            'outofstock' => 'Rupture'
        ][$this->stock_status] ?? 'Inconnu';
    }

    public function getStockStatusColorAttribute()
    {
        return [
            'instock' => 'success',
            'lowstock' => 'warning',
            'outofstock' => 'danger'
        ][$this->stock_status] ?? 'secondary';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
