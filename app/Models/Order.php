<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
    'user_id',
    'subtotal',
    'discount',
    'tax',
    'shipping',
    'total',
    'name',
    'phone',
    'locality',
    'address',
    'city',
    'zip',
    'country',
    'landmark',
    'status',
    'confirmed_date',
    'delivered_date',
    'cancelled_date',
    'tracking_number',
    'current_location',
    'tracking_notes',
    'shipped_date',
    'estimated_delivery',
    'carrier',
    'tracking_url',
    'is_archived',
    'archived_at',
    'delivery_notes'
];
    public function trackingNotifications()
    {
        return $this->hasMany(TrackingNotification::class);
    }

    public function getTrackingHistory()
    {
        return [
            [
                'status' => 'ordered',
                'date' => $this->created_at,
                'location' => 'Commande reçue',
                'description' => 'Votre commande a été confirmée'
            ],
        ];
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function transaction(){
        return $this->hasOne(Transaction::class);
    }
}

