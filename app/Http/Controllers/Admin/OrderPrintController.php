<?php
// app/Http/Controllers/Admin/OrderPrintController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderPrintController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * Print invoice for a single order
     */
    public function printInvoice($order_id)
    {
        $order = Order::with(['orderItems.product', 'user', 'transaction'])->findOrFail($order_id);
        $settings = Setting::first();
        $date = Carbon::now()->format('d/m/Y H:i');

        // Get barcodes
        $barcodes = $this->barcodeService->getAllBarcodes($order->id);

        $pdf = Pdf::loadView('admin.print.invoice', [
            'order' => $order,
            'settings' => $settings,
            'date' => $date,
            'barcodes' => $barcodes
        ]);

        return $pdf->stream('facture-' . $order->id . '.pdf');
    }

    /**
     * Print shipping label for a single order
     */
    public function printShippingLabel($order_id)
    {
        $order = Order::findOrFail($order_id);
        $settings = Setting::first();

        // Get barcodes
        $barcodes = $this->barcodeService->getAllBarcodes($order->id);

        $pdf = Pdf::loadView('admin.print.shipping-label', [
            'order' => $order,
            'settings' => $settings,
            'barcodes' => $barcodes
        ]);

        $pdf->setPaper([0, 0, 288, 432], 'portrait'); // 4x6 inches
        return $pdf->stream('etiquette-expedition-' . $order->id . '.pdf');
    }

    /**
     * Print packing slip for a single order
     */
    public function printPackingSlip($order_id)
    {
        $order = Order::with(['orderItems.product'])->findOrFail($order_id);
        $settings = Setting::first();

        // Get barcodes
        $barcodes = $this->barcodeService->getAllBarcodes($order->id);

        $pdf = Pdf::loadView('admin.print.packing-slip', [
            'order' => $order,
            'settings' => $settings,
            'barcodes' => $barcodes
        ]);

        return $pdf->stream('bordereau-colisage-' . $order->id . '.pdf');
    }

    /**
     * Print order summary for a single order
     */
    public function printOrderSummary($order_id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($order_id);
        $settings = Setting::first();
        $date = Carbon::now()->format('d/m/Y H:i');

        // Get barcodes
        $barcodes = $this->barcodeService->getAllBarcodes($order->id);

        $pdf = Pdf::loadView('admin.print.order-summary', [
            'order' => $order,
            'settings' => $settings,
            'date' => $date,
            'barcodes' => $barcodes
        ]);

        return $pdf->stream('resume-commande-' . $order->id . '.pdf');
    }

    /**
     * Print delivery note for a single order
     */
    public function printDeliveryNote($order_id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->with('product')->get();
        $settings = Setting::first();
        $date = Carbon::now()->format('d/m/Y H:i');

        // Get barcodes
        $barcodes = $this->barcodeService->getAllBarcodes($order->id);

        $pdf = Pdf::loadView('admin.print.delivery-note', [
            'order' => $order,
            'orderItems' => $orderItems,
            'settings' => $settings,
            'date' => $date,
            'barcodes' => $barcodes
        ]);

        return $pdf->stream('bon-livraison-' . $order->id . '.pdf');
    }

    /**
     * Batch print invoices
     */
    public function batchPrintInvoices(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::with(['orderItems.product', 'user', 'transaction'])
            ->whereIn('id', $request->order_ids)
            ->orderBy('id', 'desc')
            ->get();

        $settings = Setting::first();
        $date = Carbon::now()->format('d/m/Y H:i');

        // Generate barcodes for each order
        foreach ($orders as $order) {
            $order->barcodes = $this->barcodeService->getAllBarcodes($order->id);
        }

        $pdf = Pdf::loadView('admin.print.batch-invoices', [
            'orders' => $orders,
            'settings' => $settings,
            'date' => $date
        ]);

        return $pdf->stream('factures-batch-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Batch print shipping labels
     */
    public function batchPrintShippingLabels(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::whereIn('id', $request->order_ids)
            ->orderBy('id', 'desc')
            ->get();

        $settings = Setting::first();

        // Generate barcodes for each order
        foreach ($orders as $order) {
            $order->barcodes = $this->barcodeService->getAllBarcodes($order->id);
        }

        $pdf = Pdf::loadView('admin.print.batch-shipping-labels', [
            'orders' => $orders,
            'settings' => $settings
        ]);

        $pdf->setPaper([0, 0, 288, 432], 'portrait'); // 4x6 inches
        return $pdf->stream('etiquettes-batch-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Batch print order summaries
     */
    public function batchPrintSummaries(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::with(['orderItems.product', 'user'])
            ->whereIn('id', $request->order_ids)
            ->orderBy('id', 'desc')
            ->get();

        $settings = Setting::first();
        $date = Carbon::now()->format('d/m/Y H:i');

        // Generate barcodes for each order
        foreach ($orders as $order) {
            $order->barcodes = $this->barcodeService->getAllBarcodes($order->id);
        }

        $pdf = Pdf::loadView('admin.print.batch-summaries', [
            'orders' => $orders,
            'settings' => $settings,
            'date' => $date
        ]);

        return $pdf->stream('resumes-batch-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Send invoice email to customer
     */
    public function sendInvoiceEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['orderItems.product', 'user', 'transaction'])->findOrFail($order_id);
            $settings = Setting::first();
            $date = Carbon::now()->format('d/m/Y H:i');

            // Get barcodes
            $barcodes = $this->barcodeService->getAllBarcodes($order->id);

            // Generate PDF
            $pdf = Pdf::loadView('admin.print.invoice', [
                'order' => $order,
                'settings' => $settings,
                'date' => $date,
                'barcodes' => $barcodes
            ]);

            $pdfPath = storage_path('app/temp/facture-' . $order->id . '.pdf');

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($pdfPath);

            // Get customer email
            $customerEmail = $order->user->email ?? $order->email;

            if (!$customerEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune adresse email trouvée pour ce client.'
                ], 400);
            }

            // Send email
            Mail::send('emails.invoice', ['order' => $order, 'settings' => $settings], function($message) use ($customerEmail, $pdfPath, $order, $settings) {
                $message->to($customerEmail)
                        ->subject('Votre facture #' . $order->id . ' - ' . ($settings->site_name ?? 'Votre boutique'))
                        ->attach($pdfPath, [
                            'as' => 'facture-' . $order->id . '.pdf',
                            'mime' => 'application/pdf',
                        ]);
            });

            // Clean up temp file
            unlink($pdfPath);

            return response()->json([
                'success' => true,
                'message' => 'Facture envoyée par email avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending invoice email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send summary email to customer
     */
    public function sendSummaryEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['orderItems.product', 'user'])->findOrFail($order_id);
            $settings = Setting::first();
            $date = Carbon::now()->format('d/m/Y H:i');

            // Get barcodes
            $barcodes = $this->barcodeService->getAllBarcodes($order->id);

            // Generate PDF
            $pdf = Pdf::loadView('admin.print.order-summary', [
                'order' => $order,
                'settings' => $settings,
                'date' => $date,
                'barcodes' => $barcodes
            ]);

            $pdfPath = storage_path('app/temp/resume-' . $order->id . '.pdf');

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($pdfPath);

            // Get customer email
            $customerEmail = $order->user->email ?? $order->email;

            if (!$customerEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune adresse email trouvée pour ce client.'
                ], 400);
            }

            // Send email
            Mail::send('emails.order-summary', ['order' => $order, 'settings' => $settings], function($message) use ($customerEmail, $pdfPath, $order, $settings) {
                $message->to($customerEmail)
                        ->subject('Résumé de votre commande #' . $order->id . ' - ' . ($settings->site_name ?? 'Votre boutique'))
                        ->attach($pdfPath, [
                            'as' => 'resume-commande-' . $order->id . '.pdf',
                            'mime' => 'application/pdf',
                        ]);
            });

            // Clean up temp file
            unlink($pdfPath);

            return response()->json([
                'success' => true,
                'message' => 'Résumé de commande envoyé par email avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending summary email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ], 500);
        }
    }
}
