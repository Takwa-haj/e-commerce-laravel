<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class CartService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = Cache::remember('site_settings', 3600, function () {
            return Setting::first();
        });
    }

    public function getTaxRate()
    {
        return $this->settings->tax_rate ?? config('cart.tax', 20);
    }

    public function getShippingCost()
    {
        return $this->settings->shipping_cost ?? 0;
    }

    public function formatPrice($amount)
    {
        $symbol = $this->settings->currency_symbol ?? '€';
        return number_format($amount, 2) . $symbol;
    }

    public function calculateCartTotals()
    {
        $cart = \Gloudemans\Shoppingcart\Facades\Cart::instance('cart');

        $subtotal = $cart->subtotal() ?? 0;
        $taxRate = $this->getTaxRate();
        $tax = ($subtotal * $taxRate) / 100;
        $shipping = $this->getShippingCost();
        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'tax_rate' => $taxRate,
            'tax' => number_format($tax, 2, '.', ''),
            'shipping' => number_format($shipping, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
        ];
    }

    public function getCurrencySymbol()
    {
        return $this->settings->currency_symbol ?? '€';
    }
}
