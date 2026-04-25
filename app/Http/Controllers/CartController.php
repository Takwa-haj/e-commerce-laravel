<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\UserAddress;
use App\Models\UnifiedAddress;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Services\CartService;
use App\Models\Address;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $cartService;
    protected $settings;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->settings = Setting::first();
    }

    public function index()
    {
        $items = Cart::instance('cart')->content();
        $settings = $this->settings;
        $totals = $this->cartService->calculateCartTotals();

        if (Session::has('discounts')) {
            $totals['discount'] = Session::get('discounts')['discount'];
            $totals['subtotal_after_discount'] = Session::get('discounts')['subtotal'];
            $totals['tax'] = Session::get('discounts')['tax'];
            $totals['shipping'] = Session::get('discounts')['shipping'];
            $totals['total'] = Session::get('discounts')['total'];
        }

        return view('cart', compact('items', 'totals', 'settings'));
    }

    public function add_to_cart(Request $request)
    {
        Log::info('=== ADD TO CART START ===');
        Log::info('Request data:', $request->all());

        $product = Product::with(['variants.size', 'variants.color'])->find($request->id);

        if (!$product) {
            return back()->with('error', 'Produit non trouvé');
        }

        $quantity = (int)$request->quantity;
        $variantId = $request->input('variant_id');
        $sizeId = $request->input('selected_size_id');
        $colorId = $request->input('selected_color_id');

        Log::info("Product: {$product->id} - {$product->name}");
        Log::info("Variant ID: {$variantId}, Size: {$sizeId}, Color: {$colorId}, Qty: {$quantity}");

        $options = [];
        $price = $product->sale_price ?? $product->regular_price;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);

            if (!$variant) {
                return back()->with('error', 'Variante non trouvée');
            }

            if ($variant->quantity < $quantity) {
                return back()->with('error', "Stock insuffisant. Disponible: {$variant->quantity}");
            }

            $options['variant_id'] = $variant->id;
            $options['size_id'] = $variant->size_id;
            $options['color_id'] = $variant->color_id;

            if ($variant->size) {
                $options['size'] = $variant->size->name;
            }
            if ($variant->color) {
                $options['color'] = $variant->color->name;
                $options['color_hex'] = $variant->color->hex_code;
            }

            $price = $variant->sale_price ?? $variant->price;

        } elseif ($sizeId || $colorId) {
            $variant = $product->findVariant($sizeId, $colorId);

            if ($variant) {
                if ($variant->quantity < $quantity) {
                    return back()->with('error', "Stock insuffisant. Disponible: {$variant->quantity}");
                }

                $options['variant_id'] = $variant->id;
                $options['size_id'] = $sizeId;
                $options['color_id'] = $colorId;
                $price = $variant->sale_price ?? $variant->price;
            } else {
                $options['size_id'] = $sizeId;
                $options['color_id'] = $colorId;

                if ($sizeId) {
                    $size = $product->sizes()->where('sizes.id', $sizeId)->first();
                    if ($size) $options['size'] = $size->name;
                }
                if ($colorId) {
                    $color = $product->colors()->where('colors.id', $colorId)->first();
                    if ($color) {
                        $options['color'] = $color->name;
                        $options['color_hex'] = $color->hex_code;
                    }
                }
            }
        }

        if ($product->variants()->exists() && !isset($options['variant_id'])) {
            return back()->with('error', 'Veuillez sélectionner une taille et/ou une couleur');
        }

        if (!isset($options['variant_id']) && $product->quantity < $quantity) {
            return back()->with('error', "Stock insuffisant. Disponible: {$product->quantity}");
        }

        $cartId = $this->generateCartId($product->id, $options);

        $existingItem = Cart::instance('cart')->search(function($cartItem) use ($cartId) {
            return $cartItem->id == $cartId;
        })->first();

        if ($existingItem) {
            $newQty = $existingItem->qty + $quantity;

            if (isset($options['variant_id'])) {
                $variant = ProductVariant::find($options['variant_id']);
                if ($variant && $newQty > $variant->quantity) {
                    return back()->with('error', "Quantité totale dépasse le stock. Max: {$variant->quantity}");
                }
            }

            Cart::instance('cart')->update($existingItem->rowId, $newQty);
        } else {
            Cart::instance('cart')->add([
                'id' => $cartId,
                'name' => $product->name,
                'qty' => $quantity,
                'price' => $price,
                'weight' => 0,
                'options' => $options
            ])->associate('App\Models\Product');
        }

        Log::info('=== ADD TO CART END ===');

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier');
    }

    private function generateCartId($productId, $options)
    {
        if (!empty($options['variant_id'])) {
            return $productId . '_v_' . $options['variant_id'];
        }

        $parts = [(string)$productId];
        if (!empty($options['size_id'])) $parts[] = 's_' . $options['size_id'];
        if (!empty($options['color_id'])) $parts[] = 'c_' . $options['color_id'];

        return implode('_', $parts);
    }

    public function increase_cart_quantity($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        if (!$item) return back()->with('error', 'Article non trouvé');

        $newQty = $item->qty + 1;

        if (isset($item->options['variant_id'])) {
            $variant = ProductVariant::find($item->options['variant_id']);
            if ($variant && $newQty > $variant->quantity) {
                return back()->with('error', "Stock max: {$variant->quantity}");
            }
        } else {
            $productId = $this->extractProductId($item->id);
            $product = Product::find($productId);
            if ($product && $newQty > $product->quantity) {
                return back()->with('error', "Stock max: {$product->quantity}");
            }
        }

        Cart::instance('cart')->update($rowId, $newQty);
        return back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        if ($item && $item->qty > 1) {
            Cart::instance('cart')->update($rowId, $item->qty - 1);
        }
        return back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return back();
    }

    public function place_an_order(Request $request)
    {
        Log::info('=== PLACE ORDER START ===');

        $user = Auth::user();

        $address = $this->getOrCreateAddress($request, $user->id);
        if (!$address) {
            return back()->with('error', 'Adresse invalide');
        }

        $this->setAmountforCheckout();

        $order = $this->createOrder($user->id, $address);
        Log::info("Order created: {$order->id}");

        foreach (Cart::instance('cart')->content() as $item) {
            $this->processOrderItem($order, $item);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        $transaction->status = "pending";
        $transaction->payment_status = "pending";
        $transaction->save();

        Log::info('=== PLACE ORDER END ===');

        if ($request->mode == "card") {
            Cart::instance("cart")->destroy();
            Session::put('pending_order_id', $order->id);
            return redirect()->route('stripe.payment', ['order_id' => $order->id]);
        } elseif ($request->mode == "cod") {
            Cart::instance("cart")->destroy();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            Session::put('order_id', $order->id);
            return redirect()->route('cart.order.confirmation');
        }

        return back()->with('error', 'Mode de paiement invalide');
    }

    private function processOrderItem($order, $item)
    {
        Log::info("Processing item: {$item->name}, Qty: {$item->qty}");

        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $this->extractProductId($item->id);
        $orderItem->price = $item->price;
        $orderItem->quantity = $item->qty;
        $orderItem->stock_updated = false;

        if (isset($item->options['variant_id'])) {
            $orderItem->variant_id = $item->options['variant_id'];
        }

        if (isset($item->options['size_id'])) {
            $orderItem->size_id = $item->options['size_id'];
        }

        if (isset($item->options['color_id'])) {
            $orderItem->color_id = $item->options['color_id'];
        }

        $orderItem->save();
        Log::info("OrderItem saved: {$orderItem->id}");

        $this->updateStock($orderItem);
    }

    private function updateStock($orderItem)
    {
        Log::info("=== UPDATE STOCK ===");
        Log::info("OrderItem: {$orderItem->id}, Variant: {$orderItem->variant_id}, Qty: {$orderItem->quantity}");

        if ($orderItem->stock_updated) {
            Log::info("Stock already updated");
            return true;
        }

        if ($orderItem->variant_id) {
            $variant = ProductVariant::find($orderItem->variant_id);

            if ($variant) {
                Log::info("Variant BEFORE: Qty={$variant->quantity}");

                $variant->quantity = max(0, $variant->quantity - $orderItem->quantity);
                $variant->save();

                Log::info("Variant AFTER: Qty={$variant->quantity}");

                $orderItem->stock_updated = true;
                $orderItem->save();

                if ($variant->product) {
                    $variant->product->updateStockStatus();
                    Log::info("Parent product updated: Qty={$variant->product->quantity}, Status={$variant->product->stock_status}");
                }

                return true;
            }
        } else {
            $product = Product::find($orderItem->product_id);

            if ($product) {
                Log::info("Product BEFORE: Qty={$product->quantity}");

                $product->quantity = max(0, $product->quantity - $orderItem->quantity);
                $product->updateStockStatus();

                Log::info("Product AFTER: Qty={$product->quantity}, Status={$product->stock_status}");

                $orderItem->stock_updated = true;
                $orderItem->save();

                return true;
            }
        }

        return false;
    }

    private function extractProductId($cartItemId)
    {
        if (strpos($cartItemId, '_') !== false) {
            $parts = explode('_', $cartItemId);
            return (int)$parts[0];
        }
        return (int)$cartItemId;
    }

    /**
     * Get or create address for order - UPDATED FOR UNIFIED ADDRESSES
     */
    private function getOrCreateAddress($request, $userId)
    {
        // Check if user selected a saved unified address
        if ($request->has('use_saved_address') && $request->boolean('use_saved_address') && $request->saved_address_id) {
            $unifiedAddress = UnifiedAddress::where('id', $request->saved_address_id)
                ->where('user_id', $userId)
                ->first();

            if ($unifiedAddress) {
                return $this->convertUnifiedAddressToOrderAddress($unifiedAddress);
            }
        }

        // Get user's default unified address
        $unifiedAddress = UnifiedAddress::forUser($userId)->default()->first();

        if ($unifiedAddress && !$request->has('force_new_address')) {
            return $this->convertUnifiedAddressToOrderAddress($unifiedAddress);
        }

        // Create new address from form data
        $request->validate([
            'full_name' => 'required|max:255',
            'phone' => 'required|string|max:20',
            'postal_code' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'landmark' => 'required|string|max:255',
        ]);

        // Save to unified_addresses if requested
        if ($request->has('save_to_account') && $request->boolean('save_to_account')) {
            $isDefault = UnifiedAddress::forUser($userId)->count() === 0;

            UnifiedAddress::create([
                'user_id' => $userId,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'FR',
                'landmark' => $request->landmark,
                'address_type' => 'shipping',
                'is_default' => $isDefault,
            ]);
        }

        // Create order address
        $orderAddress = new Address();
        $orderAddress->name = $request->full_name;
        $orderAddress->phone = $request->phone;
        $orderAddress->zip = $request->postal_code;
        $orderAddress->state = $request->state;
        $orderAddress->city = $request->city;
        $orderAddress->address = $request->address_line_1;
        $orderAddress->locality = $request->address_line_2 ?? '';
        $orderAddress->landmark = $request->landmark;
        $orderAddress->country = $request->country ?? 'FR';
        $orderAddress->user_id = $userId;
        $orderAddress->isdefault = true;
        $orderAddress->save();

        return $orderAddress;
    }

    /**
     * Convert unified address to order address
     */
    private function convertUnifiedAddressToOrderAddress($unifiedAddress)
    {
        $orderAddress = new Address();
        $orderAddress->name = $unifiedAddress->full_name;
        $orderAddress->phone = $unifiedAddress->phone;
        $orderAddress->zip = $unifiedAddress->postal_code;
        $orderAddress->state = $unifiedAddress->state;
        $orderAddress->city = $unifiedAddress->city;
        $orderAddress->address = $unifiedAddress->address_line_1;
        $orderAddress->locality = $unifiedAddress->address_line_2 ?? '';
        $orderAddress->landmark = $unifiedAddress->landmark ?? '';
        $orderAddress->country = $unifiedAddress->country;
        $orderAddress->user_id = $unifiedAddress->user_id;
        $orderAddress->isdefault = true;
        $orderAddress->save();

        return $orderAddress;
    }

    private function createOrder($userId, $address)
    {
        $order = new Order();
        $order->user_id = $userId;
        $order->subtotal = Session::get('checkout')['subtotal'] ?? Cart::instance('cart')->subtotal();
        $order->discount = Session::get('checkout')['discount'] ?? 0;
        $order->tax = Session::get('checkout')['tax'] ?? 0;
        $order->shipping = Session::get('checkout')['shipping'] ?? $this->getShippingCost();
        $order->total = Session::get('checkout')['total'] ?? Cart::instance('cart')->total();
        $order->tax_rate = $this->cartService->getTaxRate();
        $order->currency = $this->settings->currency ?? 'EUR';
        $order->currency_symbol = $this->settings->currency_symbol ?? '€';
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        return $order;
    }

    private function getShippingCost()
    {
        return $this->settings ? $this->settings->shipping_cost : 0;
    }

    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->count()) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'shipping' => Session::get('discounts')['shipping'],
                'total' => Session::get('discounts')['total'],
            ]);
        } else {
            $subtotal = Cart::instance('cart')->subtotal();
            $taxRate = $this->cartService->getTaxRate();
            $tax = ($subtotal * $taxRate) / 100;
            $shipping = $this->cartService->getShippingCost();
            $total = $subtotal + $tax + $shipping;

            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
            ]);
        }
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $settings = $this->settings;

        // Get unified addresses
        $savedAddresses = UnifiedAddress::forUser($user->id)
            ->shipping()
            ->latest()
            ->get();

        $defaultAddress = $savedAddresses->where('is_default', true)->first();

        return view('checkout', compact('settings', 'savedAddresses', 'defaultAddress'));
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('expiry_date', '>=', Carbon::today())
            ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Code coupon invalide!');
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);

        $this->calculateDiscount();
        return back()->with('success', 'Coupon appliqué!');
    }

    public function calculateDiscount()
    {
        $discount = 0;

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');

            if ($coupon['type'] == 'fixed') {
                $discount = $coupon['value'];
            } else {
                $discount = (Cart::instance('cart')->subtotal() * $coupon['value']) / 100;
            }

            $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxRate = $this->cartService->getTaxRate();
            $taxAfterDiscount = ($subtotalAfterDiscount * $taxRate) / 100;
            $shippingCost = $this->cartService->getShippingCost();
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount + $shippingCost;

            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
                'shipping' => number_format(floatval($shippingCost), 2, '.', ''),
                'total' => number_format(floatval($totalAfterDiscount), 2, '.', ''),
            ]);
        }
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success', 'Coupon retiré');
    }
}
