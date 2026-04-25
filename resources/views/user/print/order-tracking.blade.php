<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Commande #{{ $order->id }}</title>
    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #f8f9fa;
            padding: 20px;
        }

        .tracking-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #d4af37;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .barcode {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 3px;
            font-weight: bold;
        }

        .barcode-label {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }

        .info-box {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
        }

        .info-box h3 {
            color: #d4af37;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .tracking-timeline {
            margin: 30px 0;
            position: relative;
            padding-left: 30px;
        }

        .timeline-track {
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #d4af37;
        }

        .timeline-step {
            position: relative;
            margin-bottom: 25px;
            padding-bottom: 25px;
        }

        .timeline-step:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: -30px;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: 2px solid #d4af37;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .timeline-step.completed .timeline-icon {
            background: #d4af37;
            color: white;
        }

        .timeline-step.current .timeline-icon {
            background: white;
            border-color: #d4af37;
            color: #d4af37;
            box-shadow: 0 0 0 5px rgba(212, 175, 55, 0.1);
        }

        .timeline-content {
            margin-left: 10px;
        }

        .timeline-date {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .timeline-text {
            font-weight: 600;
            color: #333;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .products-table th {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }

        .products-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
        }

        .status-delivered { background: #d4edda; color: #155724; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-ordered { background: #fff3cd; color: #856404; }
        .status-canceled { background: #f8d7da; color: #721c24; }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 1px dashed #d4af37;
            border-radius: 8px;
            background: #fef9e7;
        }

        .qr-label {
            font-size: 11px;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .tracking-url {
            font-family: monospace;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 10px 0;
            word-break: break-all;
            font-size: 10px;
        }

        .print-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="print-date">
        Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <div class="tracking-container">
        <!-- Header -->
        <div class="header">
            <h1>SUIVI DE COMMANDE</h1>
            <div class="order-number">Commande #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>

            <!-- Barcode Section -->
            <!-- Barcode Section -->
<div class="barcode-section" style="text-align: center; margin: 30px 0;">
    <h3 style="color: #d4af37; margin-bottom: 20px; border-bottom: 2px solid #d4af37; padding-bottom: 10px;">
        <i class="fas fa-barcode"></i> CODE-BARRES DE SUIVI
    </h3>

    <!-- Use the generated barcode HTML -->
    {!! $barcodeHtml !!}

    <!-- Additional verification -->
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid #d4af37;">
        <p style="margin: 0; font-size: 12px; color: #666;">
            <strong>Vérification:</strong> Ce code-barres représente le numéro de commande
            <strong>#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</strong>
        </p>
    </div>
</div>

            <!-- Status Badge -->
            <div>
                <span class="status-badge status-{{ $order->status }}">
                    @if($order->status == 'delivered') ✓ Livré
                    @elseif($order->status == 'confirmed') ✓ Confirmé
                    @elseif($order->status == 'canceled') ✗ Annulé
                    @else En traitement @endif
                </span>
            </div>
        </div>

        <!-- Order Info -->
        <div class="info-grid">
            <div class="info-box">
                <h3>INFORMATIONS CLIENT</h3>
                <p><strong>{{ $order->name }}</strong></p>
                <p>{{ $order->address }}</p>
                <p>{{ $order->city }}, {{ $order->zip }}</p>
                <p>{{ $order->country }}</p>
                <p>Tél: {{ $order->phone }}</p>
                <p>Email: {{ $order->user->email ?? 'N/A' }}</p>
            </div>

            <div class="info-box">
                <h3>DÉTAILS COMMANDE</h3>
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> {{ number_format($order->total, 2, ',', ' ') }} €</p>
                <p><strong>Articles:</strong> {{ $order->orderItems->count() }}</p>
                @if($order->tracking_number)
                <p><strong>N° de suivi:</strong> {{ $order->tracking_number }}</p>
                @endif
                @if($order->carrier)
                <p><strong>Transporteur:</strong> {{ $order->carrier }}</p>
                @endif
                @if($order->estimated_delivery)
                <p><strong>Livraison estimée:</strong> {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div style="margin: 30px 0;">
            <h3 style="color: #d4af37; border-bottom: 1px solid #eee; padding-bottom: 10px;">HISTORIQUE</h3>
            <div class="tracking-timeline">
                <div class="timeline-track"></div>

                <!-- Order Placed -->
                <div class="timeline-step completed">
                    <div class="timeline-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Commande passée</div>
                    </div>
                </div>

                <!-- Order Confirmed -->
                @if($order->status == 'confirmed' || $order->status == 'delivered')
                <div class="timeline-step {{ $order->status == 'confirmed' ? 'current' : 'completed' }}">
                    <div class="timeline-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            @if($order->confirmed_date)
                                {{ \Carbon\Carbon::parse($order->confirmed_date)->format('d/m/Y H:i') }}
                            @else
                                En attente
                            @endif
                        </div>
                        <div class="timeline-text">Commande confirmée</div>
                    </div>
                </div>
                @endif

                <!-- Shipped -->
                @if($order->tracking_number)
                <div class="timeline-step {{ $order->status == 'confirmed' ? 'current' : ($order->status == 'delivered' ? 'completed' : '') }}">
                    <div class="timeline-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            @if($order->confirmed_date)
                                {{ \Carbon\Carbon::parse($order->confirmed_date)->addDay()->format('d/m/Y H:i') }}
                            @endif
                        </div>
                        <div class="timeline-text">Expédiée</div>
                    </div>
                </div>
                @endif

                <!-- Delivered -->
                @if($order->status == 'delivered')
                <div class="timeline-step completed">
                    <div class="timeline-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            @if($order->delivered_date)
                                {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') }}
                            @endif
                        </div>
                        <div class="timeline-text">Livrée</div>
                    </div>
                </div>
                @endif

                <!-- Canceled -->
                @if($order->status == 'canceled')
                <div class="timeline-step completed">
                    <div class="timeline-icon">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            @if($order->cancelled_date)
                                {{ \Carbon\Carbon::parse($order->cancelled_date)->format('d/m/Y H:i') }}
                            @endif
                        </div>
                        <div class="timeline-text">Annulée</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Products -->
        @if($order->orderItems->count() > 0)
        <div style="margin: 30px 0;">
            <h3 style="color: #d4af37; border-bottom: 1px solid #eee; padding-bottom: 10px;">ARTICLES COMMANDÉS</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Totals -->
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <span>Sous-total:</span>
                <span>{{ number_format($order->subtotal, 2, ',', ' ') }} €</span>
            </div>
            @if($order->discount > 0)
            <div style="display: flex; justify-content: space-between; margin: 5px 0; color: #28a745;">
                <span>Remise:</span>
                <span>-{{ number_format($order->discount, 2, ',', ' ') }} €</span>
            </div>
            @endif
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <span>Livraison:</span>
                <span>{{ number_format($order->shipping, 2, ',', ' ') }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <span>TVA:</span>
                <span>{{ number_format($order->tax, 2, ',', ' ') }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 5px 0; font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px;">
                <span>TOTAL:</span>
                <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
            </div>
        </div>

        <!-- QR Code / Tracking Link -->
        <div class="qr-section">
            <h4 style="margin-top: 0; color: #d4af37;">SUIVI EN LIGNE</h4>
            <div class="tracking-url">
                {{ route('public.order.tracking', ['order_id' => $order->id, 'token' => md5($order->id . $order->created_at)]) }}
            </div>
            <div class="qr-label">
                Scannez le code QR ou copiez le lien pour suivre votre commande
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ $settings->site_name ?? config('app.name') }}</p>
            @if($settings->site_address ?? false)
            <p>{{ $settings->site_address }}</p>
            @endif
            @if($settings->site_phone ?? false)
            <p>Tél: {{ $settings->site_phone }}</p>
            @endif
            @if($settings->site_email ?? false)
            <p>Email: {{ $settings->site_email }}</p>
            @endif
            <p>Pour toute question, contactez notre service client</p>
        </div>
    </div>

    <script>
        // Auto-print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
                // Optional: Close window after printing
                 setTimeout(function() { window.close(); }, 1000);
            }, 500);
        };
    </script>
</body>
</html>
