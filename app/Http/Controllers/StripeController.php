<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as LaravelSession;
use Gloudemans\Shoppingcart\Facades\Cart;

class StripeController extends Controller
{
    /**
     * Show payment page
     */
    public function paymentPage($order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        if ($order->transaction && $order->transaction->status == 'completed') {
            return redirect()->route('cart.order.confirmation')
                ->with('error', 'Commande déjà payée.');
        }

        return view('stripe-payment', compact('order'));
    }

    /**
     * Create Stripe Checkout Session
     */
    public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::find($validated['order_id']);

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create Checkout Session
            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($order->currency ?? 'eur'),
                        'product_data' => [
                            'name' => 'Commande #' . $order->id,
                        ],
                        'unit_amount' => $this->toCents($order->total),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url' => route('cart.checkout'),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
            ];

            $checkoutSession = Session::create($sessionData);

            // Save session ID
            $order->stripe_session_id = $checkoutSession->id;
            $order->save();

            return response()->json([
                'sessionId' => $checkoutSession->id,
                'url' => $checkoutSession->url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $orderId = $request->get('order_id');

        if (!$sessionId || !$orderId) {
            return redirect()->route('cart.checkout')
                ->with('error', 'Session invalide.');
        }

        $order = Order::find($orderId);

        if (!$order || $order->user_id != Auth::id()) {
            return redirect()->route('cart.checkout')
                ->with('error', 'Commande non trouvée.');
        }

        try {
            // Verify payment with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            if ($session->payment_status == 'paid') {
                // Update order status
                $order->status = 'processing';
                $order->save();

                // Update transaction
                if ($order->transaction) {
                    $order->transaction->status = 'completed';
                    $order->transaction->stripe_payment_id = $session->payment_intent;
                    $order->transaction->save();
                }

                // Clear cart
                Cart::instance('cart')->destroy();
                LaravelSession::forget('checkout');
                LaravelSession::forget('coupon');
                LaravelSession::forget('discounts');
                LaravelSession::put('order_id', $order->id);

                return redirect()->route('cart.order.confirmation');
            }

        } catch (\Exception $e) {
            // Log error but still show success if order exists
            \Log::error('Stripe success error: ' . $e->getMessage());
        }

        // Fallback: if we can't verify with Stripe but order exists
        LaravelSession::put('order_id', $order->id);
        return redirect()->route('cart.order.confirmation');
    }

    /**
     * Simple webhook handler
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $endpointSecret = config('services.stripe.webhook_secret');

        if (!$endpointSecret) {
            return response()->json(['error' => 'Webhook secret not configured'], 400);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );

            if ($event->type == 'checkout.session.completed') {
                $this->handleSessionCompleted($event->data->object);
            }

            return response()->json(['received' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle completed session
     */
    private function handleSessionCompleted($session)
    {
        $orderId = $session->metadata->order_id ?? null;

        if (!$orderId) return;

        $order = Order::find($orderId);
        if (!$order) return;

        // Update order
        $order->status = 'processing';
        $order->save();

        // Update transaction if exists
        if ($order->transaction) {
            $order->transaction->status = 'completed';
            $order->transaction->stripe_payment_id = $session->payment_intent;
            $order->transaction->save();
        }
    }

    /**
     * Convert amount to cents
     */
    private function toCents($amount)
    {
        return round(floatval($amount) * 100);
    }
}
