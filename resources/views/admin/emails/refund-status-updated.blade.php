@component('mail::message')
# Mise à jour de votre demande de remboursement

Bonjour {{ $refund->user->name }},

Votre demande de remboursement **#{{ $refund->id }}** a été mise à jour.

**Détails de la demande :**
- N° de demande : #{{ $refund->id }}
- Commande associée : #{{ $refund->order_id }}
- Montant demandé : {{ number_format($refund->requested_amount, 2) }}€
- Nouveau statut : **{{ $statusLabel }}**
- Date de mise à jour : {{ now()->format('d/m/Y H:i') }}

@if($refund->admin_notes)
**Note de l'administrateur :**
{{ $refund->admin_notes }}
@endif

@if($status == 'completed' && $refund->stripe_refund_id)
**Informations de remboursement :**
- Référence Stripe : {{ $refund->stripe_refund_id }}
- Montant remboursé : {{ number_format($refund->refund_amount, 2) }}€
- Les fonds devraient apparaître sur votre compte sous 5-10 jours ouvrés.
@endif

@component('mail::button', ['url' => route('user.refund.details', $refund->id), 'color' => 'primary'])
Voir les détails du remboursement
@endcomponent

Si vous avez des questions, n'hésitez pas à nous contacter.

Cordialement,
L'équipe {{ config('app.name') }}
@endcomponent  
