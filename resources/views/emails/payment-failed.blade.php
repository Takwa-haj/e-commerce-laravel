<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Échec du paiement</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #fee; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc2626; }
        .content { background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #dc2626; margin: 0;">Échec du paiement</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $order->name }},</p>

            <p>Nous avons rencontré un problème lors du traitement du paiement de votre commande <strong>#{{ $order->id }}</strong>.</p>

            @if($error)
                <div style="background: #fef2f2; padding: 15px; border-radius: 6px; margin: 20px 0;">
                    <p style="margin: 0; color: #dc2626;"><strong>Erreur :</strong> {{ $error }}</p>
                </div>
            @endif

            <p>Pour réessayer le paiement et finaliser votre commande, cliquez sur le bouton ci-dessous :</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('order.retry-payment', ['order' => $order->id]) }}" class="button">
                    Réessayer le paiement
                </a>
            </div>

            <p><strong>Détails de la commande :</strong></p>
            <ul>
                <li>Numéro de commande : #{{ $order->id }}</li>
                <li>Date : {{ $order->created_at->format('d/m/Y H:i') }}</li>
                <li>Montant : {{ number_format($order->total, 2) }}€</li>
            </ul>

            <p>Si le problème persiste, vous pouvez :</p>
            <ol>
                <li>Vérifier les informations de votre carte bancaire</li>
                <li>Contacter votre banque</li>
                <li>Essayer un autre moyen de paiement</li>
                <li>Nous contacter pour obtenir de l'aide</li>
            </ol>

            <p>Votre commande a été mise en attente et sera annulée automatiquement si le paiement n'est pas finalisé dans les 24 heures.</p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Pour toute assistance, contactez-nous à <a href="mailto:{{ $settings->site_email ?? 'support@example.com' }}">{{ $settings->site_email ?? 'support@example.com' }}</a></p>
            <p>© {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
