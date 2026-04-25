<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d4af37; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
        .total { font-size: 18px; font-weight: bold; color: #047857; }
        .order-details { background: var(--surface); padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Commande Confirmée ✨</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $order->name }}</strong>,</p>

            @if(!empty($customMessage))
                <p>{!! nl2br(e($customMessage)) !!}</p>
            @else
                <p>Nous vous confirmons que votre commande <strong>#{{ $order->id }}</strong> a bien été enregistrée et est en cours de traitement.</p>
            @endif

            <div class="order-details">
                <h3>📋 Détails de la commande</h3>
                <p><strong>N° de commande:</strong> {{ $order->id }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Statut:</strong> Confirmée</p>
            </div>

            <h3>🛍️ Articles commandés</h3>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($orderItems as $item)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">
                        {{ $item->product->name ?? 'Produit' }}
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; text-align: center;">
                        x{{ $item->quantity }}
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; text-align: right;">
                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $currency_symbol }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold;">Total:</td>
                    <td style="padding: 10px; text-align: right; font-weight: bold; color: #047857;">
                        {{ number_format($order->total, 2, ',', ' ') }} {{ $currency_symbol }}
                    </td>
                </tr>
            </table>

            <div class="order-details">
                <h3>📍 Adresse de livraison</h3>
                <p>
                    {{ $order->name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->zip }} {{ $order->city }}<br>
                    {{ $order->country }}<br>
                    <strong>Tél:</strong> {{ $order->phone }}
                </p>
            </div>

            <p>Merci pour votre confiance !</p>
            <p>Cordialement,<br>L'équipe {{ $settings->site_name ?? config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>{{ $settings->site_address ?? '' }} | {{ $settings->site_phone ?? '' }}</p>
            <p>© {{ date('Y') }} - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
