<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de votre demande de remboursement</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }
        .refund-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .refund-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .refund-details td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .refund-details td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .refund-details tr:last-child td {
            border-bottom: none;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .button:hover {
            background: #5a67d8;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .product-list {
            margin: 15px 0;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-details {
            font-size: 12px;
            color: #6c757d;
        }
        .text-center {
            text-align: center;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mb-20 {
            margin-bottom: 20px;
        }
        hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .refund-details td {
                display: block;
                width: 100%;
            }
            .refund-details td:first-child {
                padding-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mise à jour de votre remboursement</h1>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $refund->user->name }}</strong>,</p>

            <p>Le statut de votre demande de remboursement a été mis à jour.</p>

            <div class="text-center">
                <div class="status-badge" style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }};">
                    <strong>Statut : {{ $statusLabel }}</strong>
                </div>
            </div>

            @if($status == 'approved')
                <div class="alert alert-success">
                    <strong>✓ Demande approuvée !</strong><br>
                    Votre demande de remboursement a été approuvée. Le remboursement sera traité dans les plus brefs délais.
                </div>
            @elseif($status == 'rejected')
                <div class="alert alert-danger">
                    <strong>✗ Demande refusée</strong><br>
                    Nous sommes désolés de vous informer que votre demande de remboursement a été refusée.
                    @if($refund->admin_notes)
                        <br><br><strong>Raison :</strong> {{ $refund->admin_notes }}
                    @endif
                </div>
            @elseif($status == 'processing')
                <div class="alert alert-info">
                    <strong>⟳ Remboursement en cours</strong><br>
                    Votre remboursement est actuellement en cours de traitement. Les fonds seront crédités sur votre compte bancaire sous 5 à 10 jours ouvrés.
                </div>
            @elseif($status == 'completed')
                <div class="alert alert-success">
                    <strong>✓ Remboursement complété</strong><br>
                    Votre remboursement a été complété avec succès. Les fonds ont été transférés sur votre compte.
                </div>
            @elseif($status == 'cancelled')
                <div class="alert alert-warning">
                    <strong>⚠ Demande annulée</strong><br>
                    Votre demande de remboursement a été annulée.
                    @if($refund->admin_notes)
                        <br><br><strong>Raison :</strong> {{ $refund->admin_notes }}
                    @endif
                </div>
            @endif

            <div class="refund-details">
                <h3 style="margin-top: 0;">Détails du remboursement</h3>
                <table>
                    <tr>
                        <td>Numéro de demande :</td>
                        <td><strong>#{{ $refund->id }}</strong></td>
                    </tr>
                    <tr>
                        <td>Commande associée :</td>
                        <td><strong>#{{ $refund->order_id }}</strong></td>
                    </tr>
                    <tr>
                        <td>Montant demandé :</td>
                        <td><strong>{{ number_format($refund->requested_amount, 2) }} €</strong></td>
                    </tr>
                    @if($refund->refund_amount)
                    <tr>
                        <td>Montant remboursé :</td>
                        <td><strong class="amount">{{ number_format($refund->refund_amount, 2) }} €</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <td>Date de la demande :</td>
                        <td>{{ $refund->created_at->format('d/m/Y à H:i') }}</td>
                    </tr>
                    @if($refund->processed_at)
                    <tr>
                        <td>Date de traitement :</td>
                        <td>{{ \Carbon\Carbon::parse($refund->processed_at)->format('d/m/Y à H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            @if($refund->orderItems && $refund->orderItems->count() > 0)
                <h3>Articles concernés</h3>
                <div class="product-list">
                    @foreach($refund->orderItems as $item)
                        <div class="product-item">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="product-image">
                            @else
                                <div class="product-image" style="background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                    <span>📦</span>
                                </div>
                            @endif
                            <div class="product-info">
                                <div class="product-name">{{ $item->product->name ?? 'Produit' }}</div>
                                <div class="product-details">
                                    Quantité : {{ $item->pivot->quantity ?? $item->quantity }} ×
                                    {{ number_format($item->price, 2) }} €
                                </div>
                            </div>
                            <div>
                                <strong>{{ number_format(($item->pivot->quantity ?? $item->quantity) * $item->price, 2) }} €</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($refund->reason_details)
                <div class="refund-details">
                    <h3>Raison du remboursement</h3>
                    <p>{{ $refund->reason_details }}</p>
                </div>
            @endif

            @if($refund->admin_notes && in_array($status, ['rejected', 'cancelled']))
                <div class="alert alert-info">
                    <strong>📝 Note de l'administrateur :</strong><br>
                    {{ $refund->admin_notes }}
                </div>
            @endif

            <hr>

            <div class="text-center">
                <a href="{{ route('user.refund.details', $refund->id) }}" class="button">
                    Voir les détails de ma demande
                </a>
            </div>

            <div class="mt-20 text-center">
                <a href="{{ route('user.orders') }}" style="color: #667eea; text-decoration: none;">
                    ← Retour à mes commandes
                </a>
            </div>

            <hr>

            <div class="alert alert-info" style="font-size: 13px;">
                <strong>Besoin d'aide ?</strong><br>
                Si vous avez des questions concernant votre remboursement, n'hésitez pas à
                <a href="{{ route('contact') }}" style="color: #667eea;">nous contacter</a>.
            </div>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé à {{ $refund->user->email }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>
                <small>
                    Ce message est automatique, merci de ne pas y répondre directement.
                </small>
            </p>
        </div>
    </div>
</body>
</html>
