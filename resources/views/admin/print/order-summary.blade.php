<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé Commande #{{ $order->id }}</title>
    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #d4af37;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #fff;
        }

        .info-box h3 {
            margin-top: 0;
            color: #d4af37;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th {
            background: #d4af37;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #d4af37;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            background: #d4edda;
            color: #155724;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 25px;
            text-align: center;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #d4af37; margin-bottom: 5px;">RÉSUMÉ DE COMMANDE</h1>
        <p style="color: #666; margin: 0;">N° {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
        <p style="color: #666; margin: 5px 0;">Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <div class="barcode">
            *{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}*
        </div>
    </div>

    <div class="order-info">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Date de commande:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div>
                <strong>Statut:</strong> <span class="status">
                    @if($order->status == 'delivered') ✓ Livré
                    @elseif($order->status == 'confirmed') ✓ Confirmé
                    @elseif($order->status == 'canceled') ✗ Annulé
                    @else En traitement @endif
                </span>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>CLIENT</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->address }}</p>
            <p>{{ $order->city }}, {{ $order->zip }}</p>
            <p>{{ $order->country }}</p>
            <p><i class="fas fa-phone"></i> {{ $order->phone }}</p>
            @if($order->user && $order->user->email)
                <p><i class="fas fa-envelope"></i> {{ $order->user->email }}</p>
            @endif
        </div>

        <div class="info-box">
            <h3>LIVRAISON</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->address }}</p>
            @if($order->locality)
                <p>{{ $order->locality }}</p>
            @endif
            <p>{{ $order->city }}, {{ $order->zip }}</p>
            <p>{{ $order->country }}</p>
            @if($order->landmark)
                <p><strong>Repère:</strong> {{ $order->landmark }}</p>
            @endif
        </div>
    </div>

    <h2 style="color: #d4af37; border-bottom: 1px solid #eee; padding-bottom: 5px;">ARTICLES</h2>

    <table class="table">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Produit</th>
                <th width="80" style="text-align: center;">Qté</th>
                <th width="100" style="text-align: right;">Prix Unitaire</th>
                <th width="100" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product ? $item->product->name : 'Produit supprimé' }}
                        @if($item->options)
                            <br><small style="color: #666;">{{ $item->options }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">
                        {{ number_format($item->price, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3 style="margin-top: 0; color: #d4af37;">RÉCAPITULATIF</h3>

        <div class="summary-row">
            <span>Sous-total:</span>
            <span>{{ number_format($order->subtotal, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        @if($order->discount > 0)
        <div class="summary-row">
            <span>Remise:</span>
            <span>- {{ number_format($order->discount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
        @endif

        <div class="summary-row">
            <span>Livraison:</span>
            <span>{{ number_format($order->shipping, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        <div class="summary-row">
            <span>TVA ({{ $settings->tax_rate ?? 20 }}%):</span>
            <span>{{ number_format($order->tax, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        <div class="summary-row grand-total">
            <span>TOTAL:</span>
            <span>{{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
    </div>

    <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
        <h4 style="margin-top: 0; color: #856404;">INFORMATIONS IMPORTANTES</h4>
        <p style="margin: 5px 0;"><strong>Mode de paiement:</strong> {{ $order->transaction->mode ?? 'Non spécifié' }}</p>
        <p style="margin: 5px 0;"><strong>Statut paiement:</strong> {{ ucfirst($order->transaction->status ?? 'en attente') }}</p>
        @if($order->tracking_number)
            <p style="margin: 5px 0;"><strong>Numéro de suivi:</strong> {{ $order->tracking_number }}</p>
        @endif
        @if($order->estimated_delivery)
            <p style="margin: 5px 0;"><strong>Livraison estimée:</strong> {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Merci pour votre commande ! Pour toute question, contactez-nous au {{ $settings->site_phone ?? '' }}</p>
        <p>{{ $settings->site_name ?? 'Bijouterie' }} - {{ \Carbon\Carbon::now()->format('Y') }}</p>
    </div>
</body>
</html>
