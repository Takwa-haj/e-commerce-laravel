<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mise à jour de commande</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d4af37; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .status-box {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #d4af37;
        }
        .tracking-link {
            display: inline-block;
            background: #d4af37;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mise à jour de votre commande</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $order->name }},</p>

            <p>Le statut de votre commande <strong>#{{ $order->id }}</strong> a été mis à jour :</p>

            <div class="status-box">
                <p><strong>Ancien statut :</strong> {{ $oldStatus }}</p>
                <p><strong>Nouveau statut :</strong> {{ $newStatus }}</p>
                <p><strong>Date de mise à jour :</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>

            <p>Vous pouvez suivre votre commande en utilisant le lien ci-dessous :</p>

            <a href="{{ route('user.order.tracking', ['order_id' => $order->id]) }}" class="tracking-link">
                Suivre ma commande
            </a>

            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

            <p>Cordialement,<br>
            L'équipe {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
