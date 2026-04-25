<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;

class StripeCheckoutController extends Controller
{
    private $stripe;

    public function __construct()
    {
        $this->middleware('auth')->except(['webhook', 'success', 'cancel']);

        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::setApiVersion('2023-10-16');
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create Stripe Checkout Session
     */
    public function createCheckoutSession(Request $request)
    {
           DB::commit();
          try {
            // Validate cart
            if (Cart::instance('cart')->count() == 0) {
                return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
            }

            // Get cart totals
            $subtotal = Cart::instance('cart')->subtotal();
            $taxRate = 20; // Adjust as needed
            $tax = ($subtotal * $taxRate) / 100;
            $shipping = 0; // Adjust as needed
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->shipping = $shipping;
            $order->total = $total;
            $order->status = 'pending';
            $order->currency = 'EUR';
            $order->currency_symbol = '€';

            // Save order
            $order->save();

            // Create order items
            foreach (Cart::instance('cart')->content() as $item) {
                $orderItem = new \App\Models\OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->save();
            }

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'mode' => 'card',
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Prepare line items for Stripe
            $lineItems = [];
            foreach (Cart::instance('cart')->content() as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->name,
                            'description' => $item->model->short_description ?? $item->name,
                            'metadata' => [
                                'product_id' => $item->id
                            ]
                        ],
                        'unit_amount' => $this->convertToCents($item->price),
                    ],
                    'quantity' => $item->qty,
                ];
            }

            // Add tax if applicable
            if ($tax > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'TVA (' . $taxRate . '%)',
                        ],
                        'unit_amount' => $this->convertToCents($tax),
                    ],
                    'quantity' => 1,
                ];
            }

            // Add shipping if applicable
            if ($shipping > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Frais de livraison',
                        ],
                        'unit_amount' => $this->convertToCents($shipping),
                    ],
                    'quantity' => 1,
                ];
            }

            // Create Stripe Checkout Session
            $checkoutSession = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.checkout.cancel'),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
                'shipping_address_collection' => [
                    'allowed_countries' => ['FR', 'BE', 'CH', 'LU', 'MC'],
                ],
                'phone_number_collection' => [
                    'enabled' => true,
                ],
                'custom_text' => [
                    'submit' => [
                        'message' => 'Merci pour votre commande ! Vous recevrez un email de confirmation.',
                    ],
                ],
            ]);

            // Save session ID to transaction
            $transaction->update([
                'stripe_payment_intent_id' => $checkoutSession->payment_intent,
                'stripe_checkout_session_id' => $checkoutSession->id,
                'stripe_client_secret' => $checkoutSession->client_secret,
            ]);

            DB::commit();

            // Redirect to Stripe Checkout
 return response()->json([
        'url' => $checkoutSession->url,
        'session_id' => $checkoutSession->id
    ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stripe Checkout Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Erreur lors de la création de la session de paiement: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');

            if (!$sessionId) {
                return redirect()->route('cart.index')->with('error', 'Session ID manquant.');
            }

            // Retrieve the session from Stripe
            $session = $this->stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['payment_intent']
            ]);

            $orderId = $session->metadata['order_id'] ?? null;

            if (!$orderId) {
                return redirect()->route('cart.index')->with('error', 'Commande introuvable.');
            }

            $order = Order::find($orderId);

            if (!$order) {
                return redirect()->route('cart.index')->with('error', 'Commande introuvable.');
            }

            // Update order and transaction
            if ($session->payment_status === 'paid') {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'name' => $session->shipping_details->name ?? $session->customer_details->name,
                    'email' => $session->customer_details->email,
                    'phone' => $session->customer_details->phone,
                    'address' => $session->shipping_details->address->line1 ?? '',
                    'city' => $session->shipping_details->address->city ?? '',
                    'zip' => $session->shipping_details->address->postal_code ?? '',
                    'country' => $session->shipping_details->address->country ?? '',
                ]);

                $transaction = Transaction::where('order_id', $order->id)->first();
                if ($transaction) {
                    $transaction->update([
                        'status' => 'approved',
                        'payment_status' => 'succeeded',
                        'payment_completed_at' => now(),
                        'stripe_charge_id' => $session->payment_intent->charges->data[0]->id ?? null,
                        'stripe_payment_method' => $session->payment_intent->payment_method_types[0] ?? 'card',
                    ]);
                }

                // Clear cart
                Cart::instance('cart')->destroy();
                session()->forget(['checkout', 'coupon', 'discounts']);
                session()->put('order_id', $order->id);

                // Send confirmation email
                try {
                    Mail::to($order->email)->send(new OrderConfirmation($order));
                } catch (\Exception $e) {
                    Log::error('Failed to send confirmation email: ' . $e->getMessage());
                }

                return redirect()->route('cart.order.confirmation')->with('success', 'Paiement réussi !');
            } else {
                return redirect()->route('cart.index')->with('error', 'Paiement non complété.');
            }

        } catch (\Exception $e) {
            Log::error('Stripe Checkout Success Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Erreur lors du traitement du paiement.');
        }
    }

    /**
     * Handle canceled payment
     */
    public function cancel()
    {
        return redirect()->route('cart.index')->with('info', 'Paiement annulé. Vous pouvez réessayer.');
    }

    /**
     * Handle Stripe webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'checkout.session.async_payment_failed':
                $session = $event->data->object;
                $this->handleCheckoutSessionFailed($session);
                break;

            default:
                Log::info('Received unhandled event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle completed checkout session
     */
    private function handleCheckoutSessionCompleted($session)
    {
        DB::beginTransaction();
        try {
            $orderId = $session->metadata['order_id'] ?? null;

            if (!$orderId) {
                Log::error('Webhook: No order_id in session metadata');
                return;
            }

            $order = Order::find($orderId);
            if (!$order) {
                Log::error('Webhook: Order not found: ' . $orderId);
                return;
            }

            // Update order with shipping details
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'name' => $session->shipping_details->name ?? $session->customer_details->name,
                'email' => $session->customer_details->email,
                'phone' => $session->customer_details->phone,
                'address' => $session->shipping_details->address->line1 ?? '',
                'city' => $session->shipping_details->address->city ?? '',
                'zip' => $session->shipping_details->address->postal_code ?? '',
                'country' => $session->shipping_details->address->country ?? '',
            ]);

            // Update transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            if ($transaction) {
                $transaction->update([
                    'status' => 'approved',
                    'payment_status' => 'succeeded',
                    'payment_completed_at' => now(),
                    'stripe_charge_id' => $session->payment_intent ?? null,
                    'stripe_checkout_session_id' => $session->id,
                ]);
            }

            // Send confirmation email
            try {
                Mail::to($order->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                Log::error('Webhook: Failed to send confirmation email: ' . $e->getMessage());
            }

            DB::commit();
            Log::info('Webhook: Checkout completed for order #' . $orderId);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook handleCheckoutSessionCompleted error: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed checkout session
     */
    private function handleCheckoutSessionFailed($session)
    {
        $orderId = $session->metadata['order_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            $transaction = Transaction::where('order_id', $orderId)->first();

            if ($order) {
                $order->update(['status' => 'failed', 'payment_status' => 'failed']);
            }

            if ($transaction) {
                $transaction->update([
                    'status' => 'failed',
                    'payment_status' => 'failed',
                    'payment_error' => 'Payment failed via Stripe Checkout'
                ]);
            }

            Log::error('Webhook: Checkout failed for order #' . $orderId);
        }
    }

    /**
     * Convert amount to cents
     */
    private function convertToCents($amount)
    {
        return (int) round($amount * 100);
    }
}
