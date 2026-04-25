<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'currency',
        'currency_symbol',
        'tax_rate',
        'shipping_cost',
        'footer_text',
        'logo',
        'theme',
        'favicon'
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    // Get full logo URL
    public function getLogoUrl()
    {
        if ($this->logo && file_exists(public_path('uploads/settings/' . $this->logo))) {
            return asset('uploads/settings/' . $this->logo);
        }
        return asset('images/logo.png'); // Default logo
    }

    // Get full logo path for PDF
    public function getLogoPath()
    {
        if ($this->logo && file_exists(public_path('uploads/settings/' . $this->logo))) {
            return public_path('uploads/settings/' . $this->logo);
        }
        return public_path('images/logo.png'); // Default logo path
    }

    // Get full favicon URL
    public function getFaviconUrl()
    {
        if ($this->favicon && file_exists(public_path('uploads/settings/' . $this->favicon))) {
            return asset('uploads/settings/' . $this->favicon);
        }
        return asset('images/favicon.ico'); // Default favicon
    }

    // Get avatar URL method (for users)
    public static function getAvatarUrl($avatar)
    {
        if ($avatar && file_exists(public_path('uploads/avatars/' . $avatar))) {
            return asset('uploads/avatars/' . $avatar);
        }
        return asset('images/avatar-default.png');
    }

    // Static method to get settings
    public static function getSettings()
    {
        return cache()->remember('settings', 3600, function() {
            return self::first() ?? new self();
        });
    }
    protected static function booted()
    {
        static::saved(function () {
            cache()->forget('settings');
        });

        static::deleted(function () {
            cache()->forget('settings');
        });
    }

    // Get currency symbol
    public function getCurrencySymbol()
    {
        return $this->currency_symbol ?? '€';
    }

    // Get tax rate as decimal
    public function getTaxRate()
    {
        return $this->tax_rate ?? 20;
    }

    // Get shipping cost
    public function getShippingCost()
    {
        return $this->shipping_cost ?? 0;
    }

    // Format price with currency
    public function formatPrice($amount)
    {
        return number_format($amount, 2, ',', ' ') . ' ' . $this->getCurrencySymbol();
    }

    public function contact()
    {
        $settings = Setting::first() ?? new Setting();
        return view('contact', compact('settings'));
    }

    public function showContact()
    {
        $settings = Setting::first() ?? new Setting();
        return view('contact', compact('settings'));
    }
}
