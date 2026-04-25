<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\NewsletterSubscriber;
use App\Models\EmailLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;

class EmailController extends Controller
{
    /**
     * Display email dashboard
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalSubscribers = NewsletterSubscriber::where('is_active', true)->count();
        $recentLogs = EmailLog::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.emails.index', compact('totalUsers', 'totalOrders', 'totalSubscribers', 'recentLogs'));
    }

    /**
     * Display email compose page
     */
    public function compose()
    {
        $users = User::whereNotNull('email')->orderBy('name')->get();
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('admin.emails.compose', compact('users', 'recentOrders'));
    }

    /**
     * Send custom email
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:customer,user,manual',
            'recipient_id' => 'required_if:recipient_type,customer,user',
            'recipient_email' => 'required_if:recipient_type,manual|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'order_id' => 'nullable|exists:orders,id',
            'attach_invoice' => 'nullable|boolean'
        ]);

        try {
            $recipientEmail = '';
            $recipientName = '';

            if ($request->recipient_type === 'manual') {
                $recipientEmail = $request->recipient_email;
                $recipientName = 'Client';
            } elseif ($request->recipient_type === 'customer') {
                $order = Order::find($request->recipient_id);
                if ($order) {
                    $recipientEmail = $order->email;
                    $recipientName = $order->name;
                }
            } elseif ($request->recipient_type === 'user') {
                $user = User::find($request->recipient_id);
                if ($user) {
                    $recipientEmail = $user->email;
                    $recipientName = $user->name;
                }
            }

            if (!$recipientEmail) {
                return redirect()->back()->with('error', 'Destinataire non trouvé.');
            }

            $order = null;
            if ($request->order_id) {
                $order = Order::with('orderItems.product')->find($request->order_id);
            }

            $settings = Setting::first() ?? new Setting();

            // Send email
            Mail::send('emails.admin-custom', [
                'subject' => $request->subject,
                'recipientName' => $recipientName,
                'content' => nl2br($request->message),
                'order' => $order,
                'settings' => $settings
            ], function ($message) use ($recipientEmail, $recipientName, $request, $order) {
                $message->to($recipientEmail, $recipientName)
                    ->subject($request->subject);

                if ($request->attach_invoice && $order) {
                    $pdf = Pdf::loadView('emails.invoice', [
                        'order' => $order,
                        'settings' => Setting::first()
                    ]);
                    $message->attachData($pdf->output(), 'facture-commande-' . $order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            // Log the email
            $logData = [
                'recipient_type' => $request->recipient_type,
                'subject' => $request->subject,
                'recipient_count' => 1,
                'sent_count' => 1,
                'recipients' => json_encode([$recipientEmail]),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'order_id' => $order ? $order->id : null,
                    'attach_invoice' => $request->attach_invoice
                ])
            ];

            if ($order && Schema::hasColumn('email_logs', 'order_id')) {
                $logData['order_id'] = $order->id;
            }

            EmailLog::create($logData);

            return redirect()->back()->with('success', 'Email envoyé avec succès à ' . $recipientEmail);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    /**
     * Send bulk emails
     */
    public function bulkSend(Request $request)
    {
        $request->validate([
            'recipients' => 'required|in:all_users,subscribed,recent_customers',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'test_mode' => 'nullable|boolean'
        ]);

        try {
            $recipients = collect();

            switch ($request->recipients) {
                case 'all_users':
                    $recipients = User::whereNotNull('email')->get();
                    break;
                case 'subscribed':
                    $recipients = User::where('newsletter_subscribed', true)
                        ->whereNotNull('email')
                        ->get();
                    break;
                case 'recent_customers':
                    $recipients = User::whereHas('orders', function($query) {
                        $query->where('created_at', '>=', now()->subDays(30));
                    })->whereNotNull('email')->get();
                    break;
            }

            if ($request->test_mode) {
                $recipients = collect([Auth::user()]);
            }

            if ($recipients->isEmpty()) {
                return redirect()->back()->with('error', 'Aucun destinataire trouvé.');
            }

            $sentCount = 0;
            $failedCount = 0;
            $failedEmails = [];
            $settings = Setting::first() ?? new Setting();

            foreach ($recipients as $recipient) {
                try {
                    Mail::send('emails.custom', [
                        'subject' => $request->subject,
                        'name' => $recipient->name,
                        'content' => nl2br($request->message),
                        'settings' => $settings
                    ], function ($message) use ($recipient, $request) {
                        $message->to($recipient->email, $recipient->name)
                            ->subject($request->subject);
                    });

                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $failedEmails[] = $recipient->email;
                    Log::error('Erreur lors de l\'envoi à ' . $recipient->email . ' : ' . $e->getMessage());
                }

                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second
            }

            EmailLog::create([
                'recipient_type' => 'bulk_' . $request->recipients,
                'subject' => $request->subject,
                'recipient_count' => $sentCount + $failedCount,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'recipients' => json_encode($recipients->pluck('email')->toArray()),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'test_mode' => $request->test_mode,
                    'failed_emails' => $failedEmails
                ])
            ]);

            $message = "Emails envoyés : {$sentCount} sur " . ($sentCount + $failedCount) . " destinataires.";

            if ($failedCount > 0) {
                return redirect()->back()->with('warning', $message . ' Échecs : ' . $failedCount);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi groupé : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmationEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['orderItems.product', 'user'])->findOrFail($order_id);
            $settings = Setting::first() ?? new Setting();

            // Determine recipient email
            $recipientEmail = $order->email;
            $recipientName = $order->name;

            // If order has a user with email, use that instead
            if ($order->user && $order->user->email) {
                $recipientEmail = $order->user->email;
                $recipientName = $order->user->name;
            }

            if (!$recipientEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune adresse email trouvée pour ce client.'
                ], 400);
            }

            $subject = $request->input('subject', 'Confirmation de commande #' . $order->id);
            $customMessage = $request->input('message', '');

            Mail::send('emails.order-confirmation', [
                'order' => $order,
                'orderItems' => $order->orderItems,
                'settings' => $settings,
                'currency_symbol' => $settings->currency_symbol ?? '€',
                'customMessage' => $customMessage
            ], function ($message) use ($recipientEmail, $recipientName, $subject, $settings) {
                $message->to($recipientEmail, $recipientName)
                    ->subject($subject . ' - ' . ($settings->site_name ?? config('app.name')));
            });

            // Log the email
            $logData = [
                'recipient_type' => 'order_confirmation',
                'subject' => $subject,
                'recipient_count' => 1,
                'sent_count' => 1,
                'recipients' => json_encode([$recipientEmail]),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'order_id' => $order->id,
                    'order_total' => $order->total,
                    'custom_message' => $customMessage
                ])
            ];

            // Add order_id to log
            $logData['order_id'] = $order->id;

            EmailLog::create($logData);

            return response()->json([
                'success' => true,
                'message' => 'Email de confirmation envoyé avec succès à ' . $recipientEmail
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur confirmation: ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order invoice email
     */
    public function sendOrderInvoiceEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['user', 'orderItems.product'])->findOrFail($order_id);
            $settings = Setting::first() ?? new Setting();

            // Determine recipient email
            $recipientEmail = $order->email;
            $recipientName = $order->name;

            if ($order->user && $order->user->email) {
                $recipientEmail = $order->user->email;
                $recipientName = $order->user->name;
            }

            $pdf = Pdf::loadView('emails.invoice', [
                'order' => $order,
                'settings' => $settings
            ]);

            $subject = $request->input('subject', 'Facture - Commande #' . $order->id);
            $customMessage = $request->input('message', '');

            Mail::send('emails.order-invoice', [
                'order' => $order,
                'settings' => $settings,
                'customMessage' => $customMessage
            ], function ($message) use ($recipientEmail, $recipientName, $pdf, $subject, $order) {
                $message->to($recipientEmail, $recipientName)
                    ->subject($subject)
                    ->attachData($pdf->output(), 'facture-commande-' . $order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            $logData = [
                'recipient_type' => 'order_invoice',
                'subject' => $subject,
                'recipient_count' => 1,
                'sent_count' => 1,
                'recipients' => json_encode([$recipientEmail]),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'order_id' => $order->id,
                    'document_type' => 'invoice'
                ])
            ];

            $logData['order_id'] = $order->id;

            EmailLog::create($logData);

            return response()->json([
                'success' => true,
                'message' => 'Facture envoyée par email avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur facture: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order summary email
     */
    public function sendOrderSummaryEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['user', 'orderItems.product'])->findOrFail($order_id);
            $settings = Setting::first() ?? new Setting();

            // Determine recipient email
            $recipientEmail = $order->email;
            $recipientName = $order->name;

            if ($order->user && $order->user->email) {
                $recipientEmail = $order->user->email;
                $recipientName = $order->user->name;
            }

            $subject = $request->input('subject', 'Résumé de commande #' . $order->id);

            Mail::send('emails.order-summary', [
                'order' => $order,
                'settings' => $settings
            ], function ($message) use ($recipientEmail, $recipientName, $subject) {
                $message->to($recipientEmail, $recipientName)
                    ->subject($subject);
            });

            $logData = [
                'recipient_type' => 'order_summary',
                'subject' => $subject,
                'recipient_count' => 1,
                'sent_count' => 1,
                'recipients' => json_encode([$recipientEmail]),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode(['order_id' => $order->id])
            ];

            $logData['order_id'] = $order->id;

            EmailLog::create($logData);

            return response()->json([
                'success' => true,
                'message' => 'Résumé de commande envoyé par email avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur résumé: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order status update email
     */
    public function sendOrderStatusEmail(Request $request, $order_id)
    {
        try {
            $order = Order::with(['user'])->findOrFail($order_id);
            $settings = Setting::first() ?? new Setting();

            $newStatus = $request->input('status');
            $oldStatus = $order->status;

            // Determine recipient email
            $recipientEmail = $order->email;
            $recipientName = $order->name;

            if ($order->user && $order->user->email) {
                $recipientEmail = $order->user->email;
                $recipientName = $order->user->name;
            }

            $statusLabels = [
                'ordered' => 'Commandé',
                'confirmed' => 'Confirmé',
                'delivered' => 'Livré',
                'canceled' => 'Annulé'
            ];

            $emailSent = false;

            if ($request->input('send_email', true) && $oldStatus !== $newStatus) {
                Mail::send('emails.order-status-updated', [
                    'order' => $order,
                    'oldStatus' => $statusLabels[$oldStatus] ?? $oldStatus,
                    'newStatus' => $statusLabels[$newStatus] ?? $newStatus,
                    'customMessage' => $request->input('message'),
                    'settings' => $settings
                ], function ($message) use ($recipientEmail, $recipientName, $order, $settings) {
                    $message->to($recipientEmail, $recipientName)
                        ->subject('Mise à jour de votre commande #' . $order->id . ' - ' . ($settings->site_name ?? config('app.name')));
                });

                $logData = [
                    'recipient_type' => 'order_status_update',
                    'subject' => 'Mise à jour de commande #' . $order->id,
                    'recipient_count' => 1,
                    'sent_count' => 1,
                    'recipients' => json_encode([$recipientEmail]),
                    'sent_by' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => json_encode([
                        'order_id' => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus
                    ])
                ];

                $logData['order_id'] = $order->id;

                EmailLog::create($logData);

                $emailSent = true;
            }

            $order->status = $newStatus;

            if ($newStatus == 'confirmed') {
                $order->confirmed_date = now();
            } elseif ($newStatus == 'delivered') {
                $order->delivered_date = now();
            } elseif ($newStatus == 'canceled') {
                $order->cancelled_date = now();
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès.',
                'email_sent' => $emailSent
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur statut: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            $appName = config('app.name');
            $time = now()->format('d/m/Y H:i:s');

            Mail::send('emails.test', [
                'appName' => $appName,
                'time' => $time
            ], function ($message) use ($request, $appName) {
                $message->to($request->test_email)
                    ->subject('Test de configuration email - ' . $appName);
            });

            EmailLog::create([
                'recipient_type' => 'test',
                'subject' => 'Test Email Configuration',
                'recipient_count' => 1,
                'sent_count' => 1,
                'recipients' => json_encode([$request->test_email]),
                'sent_by' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode(['type' => 'test_email'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email test envoyé avec succès à ' . $request->test_email
            ]);

        } catch (\Exception $e) {
            Log::error('Test email error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show email templates
     */
    public function templates()
    {
        $templates = [
            'order-confirmation' => 'Confirmation de commande',
            'order-status-updated' => 'Mise à jour de statut',
            'order-invoice' => 'Facture',
            'order-summary' => 'Résumé de commande',
            'payment-failed' => 'Échec de paiement',
            'welcome' => 'Email de bienvenue',
            'newsletter' => 'Newsletter',
            'custom' => 'Email personnalisé'
        ];

        return view('admin.emails.templates', compact('templates'));
    }

    /**
     * Preview email template
     */
    public function previewTemplate($template)
    {
        $dummyData = $this->getDummyDataForTemplate($template);
        $settings = Setting::first() ?? new Setting();
        $dummyData['settings'] = $settings;

        $viewMap = [
            'order-confirmation' => 'emails.order-confirmation',
            'order-status-updated' => 'emails.order-status-updated',
            'order-invoice' => 'emails.order-invoice',
            'order-summary' => 'emails.order-summary',
            'payment-failed' => 'emails.payment-failed',
            'welcome' => 'emails.welcome',
            'newsletter' => 'emails.newsletter',
            'custom' => 'emails.custom'
        ];

        return view($viewMap[$template] ?? 'emails.custom', $dummyData);
    }

    /**
     * Get dummy data for template preview
     */
    private function getDummyDataForTemplate($template)
    {
        $settings = Setting::first() ?? new Setting();

        $dummyOrder = (object)[
            'id' => 12345,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'address' => '123 Rue Principale',
            'city' => 'Paris',
            'zip' => '75001',
            'country' => 'France',
            'status' => 'confirmed',
            'total' => 149.99,
            'subtotal' => 129.99,
            'shipping' => 10.00,
            'tax' => 10.00,
            'created_at' => now(),
            'currency_symbol' => '€'
        ];

        switch ($template) {
            case 'order-confirmation':
                return [
                    'order' => $dummyOrder,
                    'orderItems' => $this->createDummyOrderItems(),
                    'currency_symbol' => '€'
                ];
            case 'order-status-updated':
                return [
                    'order' => $dummyOrder,
                    'oldStatus' => 'Commandé',
                    'newStatus' => 'Confirmé',
                    'customMessage' => 'Votre commande est maintenant confirmée.'
                ];
            default:
                return [
                    'name' => 'John Doe',
                    'subject' => 'Email de test',
                    'content' => '<p>Ceci est un email de test.</p>'
                ];
        }
    }

    private function createDummyOrderItems()
    {
        return collect([
            (object)[
                'product' => (object)['name' => 'Produit exemple 1'],
                'quantity' => 1,
                'price' => 49.99
            ],
            (object)[
                'product' => (object)['name' => 'Produit exemple 2'],
                'quantity' => 1,
                'price' => 80.00
            ]
        ]);
    }

    /**
     * Quick email action for orders
     */
    public function quickEmailAction(Request $request, $order_id)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'invoice':
                return $this->sendOrderInvoiceEmail($request, $order_id);
            case 'summary':
                return $this->sendOrderSummaryEmail($request, $order_id);
            case 'confirmation':
                return $this->sendOrderConfirmationEmail($request, $order_id);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Action non reconnue.'
                ], 400);
        }
    }

    /**
     * Get email statistics
     */
    public function getEmailStatistics()
    {
        $totalSent = EmailLog::count();
        $successfulSent = EmailLog::where('sent_count', '>', 0)->count();
        $successRate = $totalSent > 0 ? round(($successfulSent / $totalSent) * 100) : 100;

        return response()->json([
            'success' => true,
            'total_sent' => $totalSent,
            'today' => EmailLog::whereDate('created_at', today())->count(),
            'week' => EmailLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => EmailLog::whereMonth('created_at', now()->month)->count(),
            'success_rate' => $successRate . '%'
        ]);
    }

    /**
     * Get users for email interface
     */
    public function getUsers()
    {
        $users = User::select('id', 'name', 'email', 'utype', 'created_at', 'last_login_at')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Send email to single user
     */
    public function sendUserEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $settings = Setting::first() ?? new Setting();

            Mail::send('emails.custom', [
                'name' => $user->name,
                'subject' => $request->subject,
                'content' => nl2br($request->message),
                'settings' => $settings
            ], function ($message) use ($user, $request) {
                $message->to($user->email, $user->name)
                    ->subject($request->subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Email envoyé à ' . $user->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send welcome email
     */
    public function sendWelcomeEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $settings = Setting::first() ?? new Setting();

            Mail::send('emails.welcome', [
                'name' => $user->name,
                'user' => $user,
                'settings' => $settings
            ], function ($message) use ($user, $settings) {
                $message->to($user->email, $user->name)
                    ->subject('Bienvenue sur ' . ($settings->site_name ?? config('app.name')));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email de bienvenue envoyé à ' . $user->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send bulk emails from user selection
     */
    public function sendBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            $userIds = $request->user_ids;
            $users = User::whereIn('id', $userIds)->get();
            $settings = Setting::first() ?? new Setting();

            $sentCount = 0;
            $failedCount = 0;

            foreach ($users as $user) {
                try {
                    $personalizedMessage = str_replace(
                        ['{name}', '{email}'],
                        [$user->name, $user->email],
                        $request->message
                    );

                    Mail::send('emails.custom', [
                        'name' => $user->name,
                        'subject' => $request->subject,
                        'content' => nl2br($personalizedMessage),
                        'settings' => $settings
                    ], function ($message) use ($user, $request) {
                        $message->to($user->email, $user->name)
                            ->subject($request->subject);
                    });

                    $sentCount++;
                    usleep(100000); // 0.1 second delay
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Failed to send to {$user->email}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$sentCount} email(s) envoyé(s)" . ($failedCount > 0 ? ", {$failedCount} échec(s)" : "")
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
