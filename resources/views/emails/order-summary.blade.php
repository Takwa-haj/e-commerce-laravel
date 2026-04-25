<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résumé de commande #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border-radius: 5px; border: 1px solid #dee2e6; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d; }
        .order-details { margin: 20px 0; }
        .order-details table { width: 100%; border-collapse: collapse; }
        .order-details th, .order-details td { padding: 8px; border-bottom: 1px solid #dee2e6; text-align: left; }
        .order-details th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $settings->site_name ?? 'Votre Boutique' }}</h2>
            <p>Résumé de commande #{{ $order->id }}</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $order->name }},</p>

            <p>Veuillez trouver ci-joint le résumé de votre commande #{{ $order->id }}.</p>

            <div class="order-details">
                <h4>Résumé de la commande :</h4>
                <table>
                    <tr>
                        <th>Date</th>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td>{{ ucfirst($order->status) }}</td>
                    </tr>
                    <tr>
                        <th>Montant total</th>
                        <td>{{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</td>
                    </tr>
                    <tr>
                        <th>Nombre d'articles</th>
                        <td>{{ $order->orderItems->count() }}</td>
                    </tr>
                </table>
            </div>

            <h4>Articles commandés :</h4>
            <div class="order-details">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h4>Informations de livraison :</h4>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p><strong>{{ $order->name }}</strong></p>
                <p>{{ $order->address }}</p>
                @if($order->locality)<p>{{ $order->locality }}</p>@endif
                <p>{{ $order->zip }} {{ $order->city }}, {{ $order->country }}</p>
                <p><strong>Téléphone :</strong> {{ $order->phone }}</p>
            </div>

            <p>Merci pour votre confiance !</p>

            <p>Cordialement,<br>
            L'équipe {{ $settings->site_name ?? 'Votre Boutique' }}</p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            <p>{{ $settings->site_name ?? 'Votre Boutique' }} | {{ $settings->site_address ?? '' }}</p>
        </div>
    </div>
</body>
</html>
