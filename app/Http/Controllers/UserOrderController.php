<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\CssBarcodeHelper;

class UserOrderController extends Controller
{
    /**
     * Track order for authenticated user
     */
    public function trackOrder($order_id)
    {
        $order = Order::with(['orderItems.product', 'user'])
            ->where('id', $order_id)
            ->where('user_id', Auth::id()) // FIXED: Only check user_id
            ->firstOrFail();

        $settings = Setting::first();

        return view('user.order-tracking', compact('order', 'settings'));
    }

    /**
     * Print tracking page
     */

public function printTracking($order_id)
{
    $order = Order::with(['orderItems.product', 'user'])
        ->where('id', $order_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $settings = Setting::first();

    // Generate barcode using CSS
    $barcodeHtml = CssBarcodeHelper::generateSimpleBarcode($order->id, 60);
    // OR use EAN style:
    // $barcodeHtml = CssBarcodeHelper::generateEanStyleBarcode($order->id, 80);

    $pdf = Pdf::loadView('user.print.order-tracking', compact('order', 'settings', 'barcodeHtml'));

    return $pdf->stream('suivi-commande-' . $order->id . '.pdf');
}

    /**
     * Public tracking (with token)
     */
    public function publicTrackOrder($order_id, $token)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($order_id);

        // Verify token
        $expectedToken = md5($order->id . $order->created_at);
        if ($token !== $expectedToken) {
            abort(404);
        }

        $settings = Setting::first();

        return view('public.order-tracking', compact('order', 'settings'));
    }

    /**
     * Print invoice for user
     */
    public function printInvoice($order_id)
    {
        $order = Order::with(['orderItems.product', 'transaction', 'user'])
            ->where('id', $order_id)
            ->where('user_id', Auth::id()) // FIXED: Only check user_id
            ->firstOrFail();

        $settings = Setting::first();
        if (!$settings) {
            $settings = new \stdClass();
            $settings->site_name = config('app.name');
            $settings->site_address = '';
            $settings->site_phone = '';
            $settings->site_email = '';
            $settings->siret = '';
            $settings->vat_number = '';
            $settings->currency_symbol = '€';
            $settings->tax_rate = 20;
            $settings->logo = null;
        }

        $date = Carbon::now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('user.print.invoice', compact('order', 'settings', 'date'));
        return $pdf->stream('facture-' . $order->id . '.pdf');
    }

    /**
     * Update order status - USERS SHOULD NOT BE ABLE TO DO THIS!
     * This should be an admin-only function
     */
    public function updateStatus(Request $request)
    {
        // Users should not be able to update order status!
        // This should be removed or moved to admin controller
        return redirect()->back()->with('error', 'Action non autorisée');
    }

    /**
     * Alternative: Allow users to cancel their own orders (only if not shipped)
     */
    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Check if user owns this order
        if ($order->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }

        // Check if order can be cancelled (only ordered status)
        if ($order->status != 'ordered') {
            return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée');
        }

        $oldStatus = $order->status;
        $order->status = 'canceled';
        $order->cancelled_date = Carbon::now();
        $order->save();

        // Send cancellation email
        $this->sendCancellationEmail($order);

        return redirect()->back()->with('success', 'Commande annulée avec succès');
    }

    /**
     * Send cancellation email
     */
    private function sendCancellationEmail($order)
    {
        try {
            $user = $order->user;
            if ($user && $user->email) {
                Mail::to($user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation email: ' . $e->getMessage());
        }
    }

    /**
     * Get user's orders for tracking
     */
    public function getTrackableOrders()
    {
        $user = Auth::user();

        $orders = Order::with(['orderItems.product'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'ordered' => Order::where('user_id', $user->id)
                ->where('status', 'ordered')->count(),
            'confirmed' => Order::where('user_id', $user->id)
                ->where('status', 'confirmed')->count(),
            'delivered' => Order::where('user_id', $user->id)
                ->where('status', 'delivered')->count(),
        ];

        return view('tracking.index', compact('orders', 'stats'));
    }

    /**
     * Generate tracking timeline
     */
    private function generateTimeline($order)
    {
        $timeline = [];

        // Order placed
        $timeline[] = [
            'date' => $order->created_at,
            'title' => 'Commande passée',
            'description' => 'Votre commande a été enregistrée',
            'completed' => true,
            'icon' => 'shopping-cart'
        ];

        // Order confirmed
        if ($order->confirmed_date || $order->status == 'confirmed' || $order->status == 'delivered') {
            $timeline[] = [
                'date' => $order->confirmed_date,
                'title' => 'Commande confirmée',
                'description' => 'Votre commande est en préparation',
                'completed' => true,
                'icon' => 'check'
            ];
        }

        // Shipped
        if ($order->tracking_number) {
            $timeline[] = [
                'date' => $order->confirmed_date,
                'title' => 'Expédiée',
                'description' => 'Votre colis est en route',
                'completed' => $order->status == 'delivered',
                'icon' => 'shipping-fast'
            ];
        }

        // Delivered
        if ($order->delivered_date) {
            $timeline[] = [
                'date' => $order->delivered_date,
                'title' => 'Livrée',
                'description' => 'Votre commande a été livrée',
                'completed' => true,
                'icon' => 'truck'
            ];
        }

        return $timeline;
    }

    /**
     * Get estimated delivery
     */
    private function getEstimatedDelivery($order)
    {
        if ($order->delivered_date) {
            return [
                'date' => $order->delivered_date,
                'status' => 'delivered',
                'message' => 'Livrée le ' . $order->delivered_date->format('d/m/Y')
            ];
        }

        if ($order->estimated_delivery) {
            $estimated = Carbon::parse($order->estimated_delivery);
            $daysLeft = Carbon::now()->diffInDays($estimated, false);

            if ($daysLeft > 0) {
                return [
                    'date' => $estimated,
                    'status' => 'pending',
                    'message' => 'Livraison prévue le ' . $estimated->format('d/m/Y')
                ];
            }
        }

        // Default estimation
        $estimated = $order->created_at->addDays(3);
        return [
            'date' => $estimated,
            'status' => 'estimated',
            'message' => 'Livraison estimée le ' . $estimated->format('d/m/Y')
        ];
    }
}
