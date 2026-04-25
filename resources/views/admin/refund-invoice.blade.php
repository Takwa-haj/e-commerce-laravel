<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avoir/Remboursement #{{ $refund->id }}</title>
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

        .refund-header {
            border-bottom: 2px solid #d9534f;
            padding-bottom: 20px;
            margin-bottom: 30px;
            color: #d9534f;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .refund-info {
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
            color: #d9534f;
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

        .refund-label {
            display: inline-block;
            background: #d9534f;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-box {
            background: #fdf7f7;
            border: 1px solid #f5c6cb;
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
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #721c24;
        }

        .table td {
            border: 1px solid #f5c6cb;
            padding: 10px;
        }

        .table tr:nth-child(even) {
            background: #fdf7f7;
        }

        .refund-section {
            margin-top: 30px;
            padding: 20px;
            background: #fdf7f7;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #f5c6cb;
        }

        .refund-total {
            font-size: 16px;
            font-weight: bold;
            color: #d9534f;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #721c24;
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
        }

        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #28a745; color: white; }
        .status-processing { background: #17a2b8; color: white; }
        .status-completed { background: #6f42c1; color: white; }
        .status-rejected { background: #dc3545; color: white; }
        .status-cancelled { background: #6c757d; color: white; }

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

        .reason-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(217, 83, 79, 0.1);
            z-index: -1;
            font-weight: bold;
            white-space: nowrap;
        }

        .original-order {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #6c757d;
        }
    </style>
</head>
<body>
    <div class="watermark">REMBOURSEMENT</div>

    <div class="print-date">
        Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <div class="refund-header clearfix">
        <div class="company-info">
            @if($settings->logo)
                <img src="{{ public_path('uploads/settings/' . $settings->logo) }}" class="logo" alt="Logo">
            @endif
            <h1>AVOIR / REMBOURSEMENT</h1>
            <p>{{ $settings->site_name ?? 'Bijouterie' }}</p>
            <p>{{ $settings->site_address ?? '' }}</p>
            <p>Tél: {{ $settings->site_phone ?? '' }}</p>
            <p>Email: {{ $settings->site_email ?? '' }}</p>
            <p>SIRET: {{ $settings->siret ?? 'Non renseigné' }}</p>
            <p>TVA Intra: {{ $settings->vat_number ?? 'Non renseigné' }}</p>
        </div>

        <div class="refund-info">
            <div class="refund-label">DOCUMENT DE REMBOURSEMENT</div>
            <h2>AVOIR N° REF-{{ str_pad($refund->id, 6, '0', STR_PAD_LEFT) }}</h2>
            <p><strong>Date du document:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            <p><strong>Date de la demande:</strong> {{ $refund->created_at->format('d/m/Y') }}</p>
            @if($refund->processed_at)
                <p><strong>Date de traitement:</strong> {{ $refund->processed_at->format('d/m/Y') }}</p>
            @endif
            <p>
                <strong>Statut:</strong>
                <span class="status-badge status-{{ $refund->status }}">
                    @switch($refund->status)
                        @case('pending')
                            En attente
                        @break
                        @case('approved')
                            Approuvé
                        @break
                        @case('processing')
                            En traitement
                        @break
                        @case('completed')
                            Terminé
                        @break
                        @case('rejected')
                            Rejeté
                        @break
                        @case('cancelled')
                            Annulé
                        @break
                        @default
                            {{ $refund->status }}
                    @endswitch
                </span>
            </p>
            <div class="barcode">
                *REF-{{ str_pad($refund->id, 6, '0', STR_PAD_LEFT) }}*
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="original-order">
        <h3>RÉFÉRENCE DE LA COMMANDE ORIGINALE</h3>
        <p><strong>Commande N°:</strong> {{ str_pad($refund->order_id, 8, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Date de commande:</strong> {{ $refund->order->created_at->format('d/m/Y') }}</p>
        <p><strong>Total de la commande:</strong> {{ number_format($refund->order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
    </div>

    <div class="info-box clearfix">
        <div style="float: left; width: 50%;">
            <h3>CLIENT:</h3>
            @if($refund->order && $refund->order->user)
                <p><strong>{{ $refund->order->user->name }}</strong></p>
                <p>Email: {{ $refund->order->user->email }}</p>
            @endif
            @if($refund->order)
                <p>{{ $refund->order->address }}</p>
                <p>{{ $refund->order->city }}, {{ $refund->order->zip }}</p>
                <p>{{ $refund->order->country }}</p>
                <p>Tél: {{ $refund->order->phone }}</p>
            @endif
        </div>

        <div style="float: right; width: 50%;">
            <h3>INFORMATIONS DE REMBOURSEMENT:</h3>
            <p><strong>Méthode de paiement originale:</strong> {{ $refund->order->transaction->mode ?? 'Non spécifiée' }}</p>
            <p><strong>Remboursement vers:</strong> {{ $refund->refund_method ?? 'Méthode originale' }}</p>
            @if($refund->stripe_refund_id)
                <p><strong>Référence Stripe:</strong> {{ $refund->stripe_refund_id }}</p>
            @endif
            @if($refund->admin_notes)
                <p><strong>Notes internes:</strong> {{ $refund->admin_notes }}</p>
            @endif
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="reason-box">
        <h3>RAISON DU REMBOURSEMENT</h3>
        <p><strong>Type:</strong> {{ $refund->getReasonTypeText() }}</p>
        <p><strong>Détails:</strong> {{ $refund->reason_details ?? 'Non spécifié' }}</p>
        @if($refund->attachments)
            <p><strong>Pièces jointes:</strong> Présentes ({{ count(json_decode($refund->attachments, true)) ?? 0 }} fichiers)</p>
        @endif
    </div>

    <h2>ARTICLES CONCERNÉS</h2>

    <table class="table">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Produit</th>
                <th width="80" style="text-align: center;">Référence</th>
                <th width="80" style="text-align: center;">Prix Unitaire HT</th>
                <th width="80" style="text-align: center;">Quantité</th>
                <th width="100" style="text-align: right;">Sous-total HT</th>
                <th width="100" style="text-align: right;">Montant à rembourser</th>
            </tr>
        </thead>
        <tbody>
            @php
                $orderItems = $refund->orderItems ?? collect();
                $totalRefundAmount = 0;
            @endphp
            @foreach($orderItems as $index => $item)
                @php
                    $itemRefundAmount = $item->pivot->refund_amount ?? ($item->price * $item->pivot->quantity ?? $item->price * $item->quantity);
                    $totalRefundAmount += $itemRefundAmount;
                @endphp
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
                        {{ $item->pivot->quantity ?? $item->quantity }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($item->price * ($item->pivot->quantity ?? $item->quantity), 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                    <td style="text-align: right; color: #d9534f; font-weight: bold;">
                        {{ number_format($itemRefundAmount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                    </td>
                </tr>
            @endforeach
            @if($orderItems->count() == 0)
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Aucun article spécifique - Remboursement global
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="refund-section">
        <h3 style="color: #d9534f;">CALCUL DU REMBOURSEMENT</h3>

        <div class="total-row">
            <span>Sous-total articles:</span>
            <span>{{ number_format($totalRefundAmount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        @if($refund->shipping_refund)
        <div class="total-row">
            <span>Frais de livraison:</span>
            <span>{{ number_format($refund->shipping_refund, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
        @endif

        <div class="total-row">
            <span>TVA ({{ $settings->tax_rate ?? 20 }}%):</span>
            <span>{{ number_format($totalRefundAmount * (($settings->tax_rate ?? 20) / 100), 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        @if($refund->adjustment_fee)
        <div class="total-row">
            <span>Frais de dossier:</span>
            <span>- {{ number_format($refund->adjustment_fee, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>
        @endif

        <div class="total-row refund-total">
            <span>MONTANT TOTAL DU REMBOURSEMENT:</span>
            <span>
                @if($refund->refund_amount)
                    {{ number_format($refund->refund_amount, 2, ',', ' ') }}
                @else
                    {{ number_format($refund->requested_amount, 2, ',', ' ') }}
                @endif
                {{ $settings->currency_symbol ?? '€' }}
            </span>
        </div>

        <div class="total-row">
            <span><strong>Montant demandé:</strong></span>
            <span>{{ number_format($refund->requested_amount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
        </div>

        <div class="total-row">
            <span><strong>Montant approuvé:</strong></span>
            <span>
                @if($refund->refund_amount)
                    {{ number_format($refund->refund_amount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                @else
                    <em>À déterminer</em>
                @endif
            </span>
        </div>
    </div>

    <div style="margin-top: 30px; padding: 20px; border: 1px solid #d4edda; border-radius: 5px; background: #d4edda;">
        <h3 style="color: #155724;">INFORMATIONS IMPORTANTES</h3>
        <p><strong>Politique de remboursement:</strong></p>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>Le délai de traitement est de 5 à 10 jours ouvrés</li>
            <li>Le remboursement sera effectué sur le moyen de paiement original</li>
            <li>Les frais de retour sont à la charge du client sauf erreur de notre part</li>
            <li>Les produits doivent être retournés dans leur état d'origine</li>
            <li>Pour toute question, contactez notre service client</li>
        </ul>
    </div>

    <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4>SUIVI ADMINISTRATIF</h4>
        <table class="table table-sm" style="background: var(--surface);">
            <tr>
                <td width="30%"><strong>ID Transaction Stripe:</strong></td>
                <td>{{ $refund->stripe_refund_id ?? 'Non applicable' }}</td>
            </tr>
            <tr>
                <td><strong>Traité par:</strong></td>
                <td>{{ $refund->processed_by ?? 'Non spécifié' }}</td>
            </tr>
            <tr>
                <td><strong>Notes internes:</strong></td>
                <td>{{ $refund->admin_notes ?? 'Aucune' }}</td>
            </tr>
            <tr>
                <td><strong>Date d'échéance:</strong></td>
                <td>
                    @if($refund->due_date)
                        {{ \Carbon\Carbon::parse($refund->due_date)->format('d/m/Y') }}
                    @else
                        Non définie
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ $settings->site_name ?? 'Bijouterie' }} - {{ $settings->site_address ?? '' }}</p>
        <p>Tél: {{ $settings->site_phone ?? '' }} | Email: {{ $settings->site_email ?? '' }}</p>
        <p>SIRET: {{ $settings->siret ?? 'XXXXXX' }} | TVA Intra: {{ $settings->vat_number ?? 'XXXXXX' }}</p>
        <p>Document interne - Ce document ne constitue pas une preuve de paiement</p>
        <p>Page 1/1</p>
    </div>
</body>
</html>
