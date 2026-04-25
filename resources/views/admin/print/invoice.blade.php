<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->id }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .invoice-header {
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .invoice-info {
            float: right;
            width: 50%;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        h1 {
            color: #d4af37;
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        h2 {
            font-size: 18px;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-box {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #d4af37;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }

        .status-delivered { background: #d4edda; color: #155724; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-ordered { background: #fff3cd; color: #856404; }
        .status-canceled { background: #f8d7da; color: #721c24; }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 30px;
            text-align: center;
            margin: 20px 0;
        }

        .print-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="print-date">
        Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <div class="invoice-header clearfix">
        <div class="company-info">
            @if($settings->logo)
                <img src="{{ public_path('uploads/settings/' . $settings->logo) }}" class="logo" alt="Logo">
            @endif
            <h1>{{ $settings->site_name ?? 'Bijouterie' }}</h1>
            <p>{{ $settings->site_address ?? '' }}</p>
            <p>Tél: {{ $settings->site_phone ?? '' }}</p>
            <p>Email: {{ $settings->site_email ?? '' }}</p>
            <p>SIRET: {{ $settings->siret ?? 'Non renseigné' }}</p>
            <p>TVA Intra: {{ $settings->vat_number ?? 'Non renseigné' }}</p>
        </div>

        <div class="invoice-info">
            <h2>FACTURE</h2>
            <p><strong>N° Facture:</strong> {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Date Facture:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            <p><strong>Date Commande:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p>
                <strong>Statut:</strong>
                <span class="status-badge status-{{ $order->status }}">
                    @if($order->status == 'delivered') Livré
                    @elseif($order->status == 'confirmed') Confirmé
                    @elseif($order->status == 'canceled') Annulé
                    @else Commandé @endif
                </span>
            </p>
            <div class="barcode">
                *{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}*
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="info-box clearfix">
        <div style="float: left; width: 50%;">
            <h3>FACTURÉ À:</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->address }}</p>
            <p>{{ $order->city }}, {{ $order->zip }}</p>
            <p>{{ $order->country }}</p>
            <p>Tél: {{ $order->phone }}</p>
            @if($order->user && $order->user->email)
                <p>Email: {{ $order->user->email }}</p>
            @endif
        </div>

        <div style="float: right; width: 50%;">
            <h3>INFORMATIONS DE LIVRAISON:</h3>
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

    <div class="clearfix"></div>

    <h2>DÉTAILS DE LA COMMANDE</h2>

    <table class="table">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Produit</th>
                <th width="80" style="text-align: center;">Référence</th>
                <th width="80" style="text-align: center;">Prix Unitaire</th>
                <th width="60" style="text-align: center;">Quantité</th>
                <th width="100" style="text-align: right;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product ? $item->product->name : 'Produit supprimé' }}
                        @if($item->options)
                            <br><small>{{ $item->options }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {{ $item->product ? $item->product->SKU : 'N/A' }}
                    </td>
                    <td style="text-align: center;">
                        {{ number_format($item->price, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $item->quantity }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span>Sous-total:</span>
            <span>{{ number_format($order->subtotal, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        @if($order->discount > 0)
        <div class="total-row">
            <span>Remise:</span>
            <span>- {{ number_format($order->discount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
        @endif

        <div class="total-row">
            <span>Frais de livraison:</span>
            <span>{{ number_format($order->shipping, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        <div class="total-row">
            <span>TVA ({{ $settings->tax_rate ?? 20 }}%):</span>
            <span>{{ number_format($order->tax, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        <div class="total-row grand-total">
            <span>TOTAL TTC:</span>
            <span>{{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
    </div>

    @if($order->transaction)
    <div style="margin-top: 30px;">
        <h3>INFORMATIONS DE PAIEMENT</h3>
        <p><strong>Méthode de paiement:</strong> {{ $order->transaction->mode ?? 'Non spécifié' }}</p>
        <p><strong>Statut du paiement:</strong> {{ ucfirst($order->transaction->status ?? 'en attente') }}</p>
        @if($order->transaction->transaction_id)
            <p><strong>Référence transaction:</strong> {{ $order->transaction->transaction_id }}</p>
        @endif
    </div>
    @endif

    <div style="margin-top: 40px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>CONDITIONS ET INFORMATIONS</h3>
        <p><strong>Date de livraison estimée:</strong>
            @if($order->estimated_delivery)
                {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}
            @else
                Non spécifiée
            @endif
        </p>
        <p><strong>Transporteur:</strong> {{ $order->carrier ?? 'Standard' }}</p>
        @if($order->tracking_number)
            <p><strong>Numéro de suivi:</strong> {{ $order->tracking_number }}</p>
        @endif

        <div style="margin-top: 20px; font-size: 10px; line-height: 1.4;">
            <p><strong>Conditions générales de vente:</strong></p>
            <p>1. Les prix sont indiqués en TTC (Toutes Taxes Comprises).</p>
            <p>2. Le délai de rétractation est de 14 jours à compter de la réception.</p>
            <p>3. Les retours doivent être effectués dans leur emballage d'origine.</p>
            <p>4. La garantie des produits est de 2 ans à compter de la date d'achat.</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ $settings->site_name ?? 'Bijouterie' }} - {{ $settings->site_address ?? '' }}</p>
        <p>Tél: {{ $settings->site_phone ?? '' }} | Email: {{ $settings->site_email ?? '' }}</p>
        <p>SIRET: {{ $settings->siret ?? 'XXXXXX' }} | TVA Intra: {{ $settings->vat_number ?? 'XXXXXX' }}</p>
        <p>Cette facture a été générée automatiquement et ne nécessite pas de signature.</p>
    </div>
</body>
</html>
