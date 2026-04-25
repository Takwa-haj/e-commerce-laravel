<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class OrderPdfService
{
    private $currencySymbol;
    private $taxRate;
    private $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
        $this->currencySymbol = $this->settings->currency_symbol ?? '€';
        $this->taxRate = $this->settings->tax_rate ?? 20;
    }

    /**
     * Generate invoice PDF
     */
    public function generateInvoice(Order $order)
    {
        $data = [
            'order' => $order,
            'orderItems' => $order->orderItems()->with('product')->get(),
            'transaction' => $order->transaction,
            'settings' => $this->settings,
            'currencySymbol' => $this->currencySymbol,
            'type' => 'invoice',
            'title' => 'Facture #' . $order->id,
        ];

        return Pdf::loadView('admin.pdf.invoice', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'Helvetica');
    }

    /**
     * Generate order summary PDF
     */
    public function generateOrderSummary(Order $order)
    {
        $data = [
            'order' => $order,
            'orderItems' => $order->orderItems()->with('product')->get(),
            'settings' => $this->settings,
            'currencySymbol' => $this->currencySymbol,
            'type' => 'summary',
            'title' => 'Résumé de Commande #' . $order->id,
        ];

        return Pdf::loadView('admin.pdf.order-summary', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'Helvetica')
            ->setOption('isHtml5ParserEnabled', true);
    }

    /**
     * Generate delivery note PDF
     */
    public function generateDeliveryNote(Order $order)
    {
        $data = [
            'order' => $order,
            'orderItems' => $order->orderItems()->with('product')->get(),
            'settings' => $this->settings,
            'currencySymbol' => $this->currencySymbol,
            'type' => 'delivery',
            'title' => 'Bon de Livraison #' . $order->id,
        ];

        return Pdf::loadView('admin.pdf.delivery-note', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'Helvetica');
    }

    /**
     * Generate shipping label PDF
     */
    public function generateShippingLabel(Order $order)
    {
        $data = [
            'order' => $order,
            'settings' => $this->settings,
            'type' => 'shipping',
            'title' => 'Étiquette d\'Expédition',
        ];

        return Pdf::loadView('admin.pdf.shipping-label', $data)
            ->setPaper([0, 0, 144, 288]) // 4x6 inches in points
            ->setOption('defaultFont', 'Helvetica');
    }

    /**
     * Generate packing slip PDF
     */
    public function generatePackingSlip(Order $order)
    {
        $data = [
            'order' => $order,
            'orderItems' => $order->orderItems()->with('product')->get(),
            'settings' => $this->settings,
            'currencySymbol' => $this->currencySymbol,
            'type' => 'packing',
            'title' => 'Bordereau de Colisage #' . $order->id,
        ];

        return Pdf::loadView('admin.pdf.packing-slip', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'Helvetica');
    }

    /**
     * Generate receipt PDF
     */
    public function generateReceipt(Order $order)
    {
        $data = [
            'order' => $order,
            'orderItems' => $order->orderItems()->with('product')->take(5)->get(),
            'transaction' => $order->transaction,
            'settings' => $this->settings,
            'currencySymbol' => $this->currencySymbol,
            'type' => 'receipt',
            'title' => 'Reçu #' . $order->id,
        ];

        return Pdf::loadView('admin.pdf.receipt', $data)
            ->setPaper([0, 0, 226.77, 340.16]) // A5 in points
            ->setOption('defaultFont', 'Helvetica');
    }

    /**
     * Batch generate invoices for multiple orders
     */
    public function generateBatchInvoices($orderIds)
    {
        $orders = Order::whereIn('id', $orderIds)->with(['orderItems.product', 'transaction'])->get();

        $pdfs = [];
        foreach ($orders as $order) {
            $pdfs[] = $this->generateInvoice($order);
        }

        return $pdfs;
    }
}
