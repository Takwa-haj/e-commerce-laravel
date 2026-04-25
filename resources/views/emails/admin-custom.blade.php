<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Message' }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d4af37; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $settings->site_name ?? config('app.name') }}</h2>
        </div>
        <div class="content">
            <p>Bonjour {{ $recipientName ?? 'Client' }},</p>

            {!! $content !!}

            @if(isset($order))
            <hr>
            <h3>Détails de la commande #{{ $order->id }}</h3>
            <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p><strong>Total:</strong> {{ number_format($order->total, 2, ',', ' ') }} €</p>
            @endif

            <p>Cordialement,<br>L'équipe {{ $settings->site_name ?? config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>{{ $settings->site_address ?? '' }} | {{ $settings->site_phone ?? '' }}</p>
            <p>Cet email a été envoyé par l'administration.</p>
        </div>
    </div>
</body>
</html>
