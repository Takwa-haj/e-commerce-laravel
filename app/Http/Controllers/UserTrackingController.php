<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserTrackingController extends Controller
{
    /**
     * Show tracking page with all user's trackable orders
     */
    public function index()
    {
        $user = Auth::user();

        // Get all trackable orders (not canceled)
        $orders = Order::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('email', $user->email);
            })
            ->where('status', '!=', 'canceled')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Count orders by status for stats
        $stats = [
            'ordered' => Order::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })->where('status', 'ordered')->count(),

            'confirmed' => Order::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })->where('status', 'confirmed')->count(),

            'delivered' => Order::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })->where('status', 'delivered')->count(),
        ];
        $settings = Setting::first();

        return view('user.tracking.index', compact('orders', 'stats'));
    }

    /**
     * Show tracking details for specific order
     */
    public function show($order_id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('id', $order_id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('email', Auth::user()->email);
            })
            ->firstOrFail();

        $settings = Setting::first();

        // Calculate timeline
        $timeline = $this->generateTimeline($order);

        // Get estimated delivery
        $estimatedDelivery = $this->calculateEstimatedDelivery($order);

        return view('user.tracking.show', compact('order', 'settings', 'timeline', 'estimatedDelivery'));
    }

    /**
     * Print tracking page
     */
    public function print($order_id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('id', $order_id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('email', Auth::user()->email);
            })
            ->firstOrFail();

        $settings = Setting::first();
        if (!$settings) {
            $settings = new \stdClass();
            $settings->site_name = config('app.name');
            $settings->site_phone = '';
        }

        $timeline = $this->generateTimeline($order);

        $pdf = Pdf::loadView('tracking.print.', compact('order', 'settings', 'timeline'));
        return $pdf->stream('suivi-commande-' . $order->id . '.pdf');
    }

    /**
     * Quick tracking by order number
     */
    public function quickTrack(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email'
        ]);

        $order = Order::where('id', $request->order_number)
            ->where('email', $request->email)
            ->first();

        if (!$order) {
            return back()->withErrors(['error' => 'Commande non trouvée. Vérifiez le numéro de commande et l\'email.']);
        }

        return redirect()->route('user.tracking.show', ['order_id' => $order->id]);
    }

    /**
     * Generate timeline for order
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
            'current' => false,
            'icon' => 'shopping-cart'
        ];

        // If confirmed
        if ($order->status == 'confirmed' || $order->status == 'delivered') {
            $timeline[] = [
                'date' => $order->confirmed_date,
                'title' => 'Commande confirmée',
                'description' => 'Votre commande est en préparation',
                'completed' => true,
                'current' => false,
                'icon' => 'check'
            ];

            // If shipped
            if ($order->tracking_number) {
                $timeline[] = [
                    'date' => $order->confirmed_date, // or use shipped_date if you have it
                    'title' => 'Expédiée',
                    'description' => 'Votre colis est en route',
                    'completed' => true,
                    'current' => $order->status == 'confirmed',
                    'icon' => 'shipping-fast'
                ];
            }
        }

        // If delivered
        if ($order->status == 'delivered') {
            $timeline[] = [
                'date' => $order->delivered_date,
                'title' => 'Livrée',
                'description' => 'Votre commande a été livrée',
                'completed' => true,
                'current' => true,
                'icon' => 'truck'
            ];
        }

        // If canceled
        if ($order->status == 'canceled') {
            $timeline[] = [
                'date' => $order->cancelled_date,
                'title' => 'Annulée',
                'description' => 'Votre commande a été annulée',
                'completed' => true,
                'current' => true,
                'icon' => 'times'
            ];
        }

        return $timeline;
    }

    /**
     * Calculate estimated delivery date
     */
    private function calculateEstimatedDelivery($order)
    {
        if ($order->delivered_date) {
            return [
                'date' => $order->delivered_date,
                'status' => 'delivered',
                'message' => 'Livrée le ' . $order->delivered_date = Carbon::now()
            ];
        }

        if ($order->estimated_delivery) {
            $now = Carbon::now();
            $estimated = Carbon::parse($order->estimated_delivery);
            $daysLeft = $now->diffInDays($estimated, false);

            if ($daysLeft > 0) {
                return [
                    'date' => $estimated,
                    'status' => 'pending',
                    'message' => 'Livraison prévue le ' . $estimated->format('d/m/Y') . " (dans $daysLeft jour" . ($daysLeft > 1 ? 's' : '') . ")"
                ];
            } else {
                return [
                    'date' => $estimated,
                    'status' => 'delayed',
                    'message' => 'Livraison prévue le ' . $estimated->format('d/m/Y') . ' (en retard)'
                ];
            }
        }

        // Default estimation based on order date
        $estimated = $order->created_at->addDays(3);

        return [
            'date' => $estimated,
            'status' => 'estimated',
            'message' => 'Livraison estimée le ' . $estimated->format('d/m/Y')
        ];
    }
}
