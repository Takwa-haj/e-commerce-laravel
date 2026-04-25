<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'approved',
        'status',
        'approved_at',
        'declined_at'
    ];

    protected $casts = [
         'approved' => 'boolean',
        'approved_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for approved reviews
    // Scopes
public function scopeApproved($query)
{
    return $query->where('status', 'approved')->orWhere(function($q2) {
              $q2->whereNull('status')->where('approved', true);
          });
}

public function scopePending($query)
{
    return $query->where(function($q) {
        $q->where('status', 'pending')
          ->orWhere(function($q2) {
              $q2->whereNull('status')->where('approved', false);
          });
    });
}

public function scopeDeclined($query)
{
    return $query->where('status', 'declined');
}

}
