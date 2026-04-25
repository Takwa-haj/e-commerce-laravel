<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorPalette extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'colors'
    ];

    protected $casts = [
        'colors' => 'array',
        'is_active' => 'boolean'
    ];

    public static function getDefaultColors(): array
    {
        return [
            'primary' => '#D4AF37',
            'secondary' => '#1A1A1A',
            'accent' => '#AA8C2F',
            'background' => '#FFFFFF',
            'surface' => '#F9FAFB',
            'bg_main' => '#FFFFFF',
            'bg_soft' => '#F9FAFB',
            'bg_light' => '#FFFFFF',
            'text_primary' => '#111827',
            'text_secondary' => '#6B7280',
            'text_main' => '#111827',
            'border' => '#E5E7EB',
            'border_soft' => '#F3F4F6',
            'divider' => '#E5E7EB',
            'success' => '#10B981',
            'warning' => '#F59E0B',
            'error' => '#EF4444',
            'info' => '#3B82F6',
            'card_shadow' => 'rgba(0, 0, 0, 0.1)',
            'hover_overlay' => 'rgba(0, 0, 0, 0.5)',
            'gold_highlight' => '#D4AF37',
            'champagne_glow' => '#F5E7C8',
            'sale_badge' => '#EF4444',
            'error_bg' => '#DC3545',
        ];
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
