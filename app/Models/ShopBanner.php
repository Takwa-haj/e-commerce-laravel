<?php
// app/Models/ShopBanner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'tagline',
        'description',
        'image',
        'alt_text',
        'button_text',
        'button_link',
        'background_color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
