<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #d4af37;">Merci pour votre commande !</h1>
            <p style="font-size: 18px;">Votre commande #{{ $order->id }} a été confirmée.</p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2 style="color: #333;">Récapitulatif de la commande</h2>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total :</strong> {{ $order->currency_symbol ?? '€' }}{{ number_format($order->total, 2) }}</p>
            <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <div style="margin-bottom: 30px;">
            <h3>Articles commandés</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Produit</th>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Quantité</th>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $item->product->name }}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $item->quantity }}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $order->currency_symbol ?? '€' }}{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="background: #fefce8; padding: 20px; border-radius: 8px; border-left: 4px solid #d4af37;">
            <h3 style="color: #b45309;">Informations de livraison</h3>
            <p>{{ $order->name }}</p>
            <p>{{ $order->address }}</p>
            <p>{{ $order->locality }}</p>
            <p>{{ $order->city }}, {{ $order->zip }}, {{ $order->country }}</p>
            <p><strong>Tél :</strong> {{ $order->phone }}</p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <p>Pour suivre votre commande, connectez-vous à votre compte.</p>
            <p style="color: #666; font-size: 14px;">Merci de votre confiance !</p>
        </div>
    </div>
</body>
</html>
