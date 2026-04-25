<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande de remboursement</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Demande de remboursement reçue</h1>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>

            <p>Nous vous confirmons la réception de votre demande de remboursement <strong>#{{ $refund->id }}</strong> pour la commande <strong>#{{ $order->id }}</strong>.</p>

            <div class="info-box">
                <h3>Récapitulatif de votre demande :</h3>
                <table>
                    <tr>
                        <th>Numéro de demande :</th>
                        <td>#{{ $refund->id }}</td>
                    </tr>
                    <tr>
                        <th>Montant demandé :</th>
                        <td class="amount">{{ number_format($refund->requested_amount, 2) }} €</td>
                    </tr>
                    <tr>
                        <th>Raison :</th>
                        <td>{{ $refund->getReasonTypeText() ?? $refund->reason_type }}</td>
                    </tr>
                    <tr>
                        <th>Détails :</th>
                        <td>{{ $refund->reason_details }}</td>
                    </tr>
                    <tr>
                        <th>Date de la demande :</th>
                        <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <p>Notre équipe va examiner votre demande et vous tiendra informé de son évolution par email. Le traitement peut prendre jusqu'à 5 jours ouvrés.</p>

            <div class="info-box" style="border-left-color: #17a2b8;">
                <h4>📋 Prochaines étapes :</h4>
                <ol style="margin: 0; padding-left: 20px;">
                    <li>Notre équipe examine votre demande</li>
                    <li>Vous recevrez une notification pour chaque mise à jour</li>
                    <li>Une fois approuvé, le remboursement sera traité sous 5-10 jours ouvrés</li>
                </ol>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('user.refunds') }}" class="button">
                    Suivre ma demande
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
