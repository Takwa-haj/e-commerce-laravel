<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\CartPersistenceService;

class MergeGuestCart
{
    protected $cartService;

    public function __construct(CartPersistenceService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Request $request, Closure $next)
    {
        // If user just logged in, merge guest cart
        if (Auth::check() && session('guest_cart_available')) {
            $guestCart = $this->cartService->loadForGuest();

            if ($guestCart) {
                $this->cartService->mergeOnLogin(Auth::user(), $guestCart);
            }

            session()->forget('guest_cart_available');
        }

        // If user has guest cart, flag it for possible merge
        if (!Auth::check() && $request->cookie('guest_cart')) {
            session(['guest_cart_available' => true]);
        }

        return $next($request);
    }
}
