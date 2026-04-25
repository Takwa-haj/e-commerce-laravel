<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; color: white; text-align: center; }
        .content { padding: 30px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; }
        .order-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="content">
            <h2>Bonjour {{ $recipientName }},</h2>

            <div>
                {!! $content !!}
            </div>

            @if(isset($order) && $order)
            <div class="order-info">
                <h4>Informations sur la commande :</h4>
                <p><strong>Référence :</strong> #{{ $order->id }}</p>
                <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p><strong>Montant :</strong> {{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
            </div>
            @endif

            <p style="margin-top: 30px;">
                Cordialement,<br>
                L'équipe {{ config('app.name') }}
            </p>
        </div>

        <div class="footer">
            <p>
                Cet email vous a été envoyé par l'administrateur de {{ config('app.name') }}.<br>
                Merci de ne pas répondre à cet email directement.
            </p>
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
