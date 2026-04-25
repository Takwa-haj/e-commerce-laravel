<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
class EmailService
{
    public function sendOrderStatusEmail(Order $order, $oldStatus, $newStatus)
    {
        $settings = Setting::getSettings();
        $user = $order->user;

        if (!$user || !$user->email) {
            return false;
        }

        $data = [
            'order' => $order,
            'settings' => $settings,
            'oldStatus' => $oldStatus,
            'newStatus' => $newStatus
        ];

        try {
            Mail::send('emails.order-status-updated', $data, function($message) use ($user, $order, $settings) {
                $message->to($user->email)
                        ->subject('Mise à jour de votre commande #' . $order->id . ' - ' . $settings->site_name);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendInvoiceEmail(Order $order)
    {
        $settings = Setting::getSettings();
        $user = $order->user;

        if (!$user || !$user->email) {
            return false;
        }

        // Generate PDF
        $pdf = Pdf::loadView('admin.orders.print.invoice', compact('order', 'settings'));
        $pdfContent = $pdf->output();

        $data = [
            'order' => $order,
            'settings' => $settings
        ];

        try {
            Mail::send('emails.invoice', $data, function($message) use ($user, $order, $settings, $pdfContent) {
                $message->to($user->email)
                        ->subject('Votre facture #' . $order->id . ' - ' . $settings->site_name)
                        ->attachData($pdfContent, 'facture-' . $order->id . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Invoice email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
