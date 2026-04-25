<?php

namespace App\Services;

use Gloudemans\Shoppingcart\Facades\Cart as CartModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartPersistenceService
{
    protected $cookieName = 'guest_cart';
    protected $cookieLifetime = 30 * 24 * 60; // 30 days in minutes

    /**
     * Save cart for authenticated user
     */
    public function saveForUser(array $cartData): void
    {
        if (!Auth::check()) {
            return;
        }

        $cart = CartModel::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'items' => $cartData['items'] ?? [],
                'subtotal' => $cartData['subtotal'] ?? 0,
                'tax' => $cartData['tax'] ?? 0,
                'shipping' => $cartData['shipping'] ?? 0,
                'total' => $cartData['total'] ?? 0,
                'coupon_code' => $cartData['coupon_code'] ?? null,
                'discount' => $cartData['discount'] ?? 0,
                'expires_at' => now()->addDays(30),
            ]
        );
    }

    /**
     * Save cart for guest (cookie-based)
     */
    public function saveForGuest(array $cartData): void
    {
        $cookieValue = encrypt(json_encode([
            'cart' => $cartData,
            'created_at' => now()->toISOString(),
        ]));

        Cookie::queue($this->cookieName, $cookieValue, $this->cookieLifetime);
    }

    /**
     * Load cart for authenticated user
     */
    public function loadForUser(): ?array
    {
        if (!Auth::check()) {
            return null;
        }

        $cart = CartModel::where('user_id', Auth::id())->first();

        if (!$cart) {
            return null;
        }

        // Check if cart is expired
        if ($cart->expires_at && $cart->expires_at->isPast()) {
            $cart->delete();
            return null;
        }

        return [
            'items' => $cart->items ?? [],
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'shipping' => $cart->shipping,
            'total' => $cart->total,
            'coupon_code' => $cart->coupon_code,
            'discount' => $cart->discount,
        ];
    }

    /**
     * Load cart for guest
     */
    public function loadForGuest(): ?array
    {
        $cookieValue = request()->cookie($this->cookieName);

        if (!$cookieValue) {
            return null;
        }

        try {
            $data = json_decode(decrypt($cookieValue), true);

            // Check if cart data is too old (30 days max)
            $createdAt = \Carbon\Carbon::parse($data['created_at'] ?? now()->subDays(31));
            if ($createdAt->diffInDays(now()) > 30) {
                $this->clearGuestCart();
                return null;
            }

            return $data['cart'] ?? null;
        } catch (\Exception $e) {
            $this->clearGuestCart();
            return null;
        }
    }

    /**
     * Merge guest cart with user cart after login
     */
    public function mergeOnLogin(User $user, ?array $guestCart): void
    {
        if (!$guestCart) {
            return;
        }

        $userCart = $this->loadForUser();

        if (!$userCart) {
            // No user cart exists, just save the guest cart
            $this->saveForUser($guestCart);
        } else {
            // Merge guest cart with user cart
            $mergedItems = $this->mergeCartItems($userCart['items'], $guestCart['items']);

            $mergedCart = [
                'items' => $mergedItems,
                'subtotal' => $userCart['subtotal'] + $guestCart['subtotal'],
                'tax' => $userCart['tax'] + $guestCart['tax'],
                'shipping' => max($userCart['shipping'], $guestCart['shipping']),
                'total' => $userCart['total'] + $guestCart['total'],
                'coupon_code' => $userCart['coupon_code'] ?? $guestCart['coupon_code'],
                'discount' => $userCart['discount'] + $guestCart['discount'],
            ];

            $this->saveForUser($mergedCart);
        }

        // Clear guest cookie
        $this->clearGuestCart();
    }

    /**
     * Merge cart items intelligently
     */
    private function mergeCartItems(array $userItems, array $guestItems): array
    {
        $mergedItems = $userItems;

        foreach ($guestItems as $guestItem) {
            $found = false;

            foreach ($mergedItems as &$mergedItem) {
                if ($this->itemsMatch($mergedItem, $guestItem)) {
                    $mergedItem['quantity'] += $guestItem['quantity'];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $mergedItems[] = $guestItem;
            }
        }

        return $mergedItems;
    }

    /**
     * Check if two cart items match
     */
    private function itemsMatch(array $item1, array $item2): bool
    {
        return $item1['product_id'] === $item2['product_id']
            && $item1['size'] === $item2['size']
            && $item1['color'] === $item2['color'];
    }

    /**
     * Clear guest cart
     */
    public function clearGuestCart(): void
    {
        Cookie::queue(Cookie::forget($this->cookieName));
    }

    /**
     * Clear user cart
     */
    public function clearUserCart(): void
    {
        if (Auth::check()) {
            CartModel::where('user_id', Auth::id())->delete();
        }
    }
}
