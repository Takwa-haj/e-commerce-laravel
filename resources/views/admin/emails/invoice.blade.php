<!DOCTYPE html>
<html>
<head>
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border-radius: 5px; border: 1px solid #dee2e6; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $settings->site_name ?? 'Votre Boutique' }}</h2>
            <p>Facture #{{ $order->id }}</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $order->name }},</p>

            <p>Veuillez trouver ci-joint la facture de votre commande #{{ $order->id }}.</p>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p><strong>Résumé de la commande :</strong></p>
                <p>Date : {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p>Montant total : {{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
                <p>Statut : {{ ucfirst($order->status) }}</p>
            </div>

            <p>Pour toute question concernant votre commande, n'hésitez pas à nous contacter.</p>

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
