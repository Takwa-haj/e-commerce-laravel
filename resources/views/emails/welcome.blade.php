<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue sur {{ config('app.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { background: #fff; padding: 40px; border-radius: 0 0 8px 8px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .benefits { margin: 30px 0; }
        .benefit-item { display: flex; align-items: flex-start; margin-bottom: 15px; }
        .benefit-icon { background: #10b981; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: white; margin: 0;">Bienvenue {{ $name }} !</h1>
            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">Nous sommes ravis de vous accueillir sur {{ config('app.name') }}</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $name }},</p>

            <p>Merci de vous être inscrit(e) sur {{ config('app.name') }} ! Votre compte a été créé avec succès.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('home') }}" class="button">
                    Commencer mes achats
                </a>
            </div>

            <div class="benefits">
                <h3 style="color: #111827; margin-bottom: 20px;">Vos avantages :</h3>

                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>
                        <strong>Suivi des commandes</strong><br>
                        Suivez l'état de vos commandes en temps réel
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>
                        <strong>Historique d'achat</strong><br>
                        Retrouvez toutes vos commandes passées
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>
                        <strong>Adresses enregistrées</strong><br>
                        Sauvegardez vos adresses de livraison
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>
                        <strong>Listes de souhaits</strong><br>
                        Enregistrez vos produits favoris
                    </div>
                </div>
            </div>

            <p><strong>Prochaines étapes :</strong></p>
            <ol>
                <li>Complétez votre profil dans votre espace client</li>
                <li>Ajoutez vos adresses de livraison</li>
                <li>Explorez notre catalogue de produits</li>
                <li>Profitez de nos offres spéciales pour les nouveaux membres</li>
            </ol>

            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 30px 0;">
                <p style="margin: 0; font-size: 14px; color: #6b7280;">
                    <strong>💡 Conseil :</strong> Vérifiez votre adresse email pour bénéficier de toutes les fonctionnalités.
                    Si vous n'avez pas reçu d'email de vérification, vous pouvez en demander un nouveau depuis votre profil.
                </p>
            </div>

            <p>Si vous avez des questions ou besoin d'aide, notre équipe de support est là pour vous :</p>
            <ul>
                <li>Email : {{ $settings->site_email ?? 'support@example.com' }}</li>
                <li>Téléphone : {{ $settings->site_phone ?? 'Non disponible' }}</li>
                <li>Assistance en ligne : Disponible du lundi au vendredi, 9h-18h</li>
            </ul>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé à l'adresse {{ $user->email ?? 'votre adresse email' }}.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>{{ $settings->site_address ?? '' }}</p>
        </div>
    </div>
</body>
</html>
