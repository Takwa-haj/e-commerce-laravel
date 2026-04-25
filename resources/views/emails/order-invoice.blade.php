@component('mail::message')
# Bonjour {{ $order->name }},

Votre facture pour la commande **#{{ $order->id }}** est prête.

## Résumé de votre commande
- **N° Commande:** {{ $order->id }}
- **Date:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Statut:**
@if($order->status == 'delivered')
Livré
@elseif($order->status == 'confirmed')
Confirmé
@elseif($order->status == 'canceled')
Annulé
@else
Commandé
@endif
- **Total:** {{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}

## Détails de la commande
@component('mail::table')
| Produit | Quantité | Prix |
|---------|----------|------|
@foreach($order->orderItems as $item)
| {{ $item->product->name ?? 'Produit supprimé' }} | {{ $item->quantity }} | {{ number_format($item->price, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }} |
@endforeach
@endcomponent

## Informations de livraison
{{ $order->address }}<br>
{{ $order->locality ? $order->locality . '<br>' : '' }}
{{ $order->zip }} {{ $order->city }}, {{ $order->country }}<br>
Téléphone: {{ $order->phone }}

@component('mail::button', ['url' => route('user.order.details', ['order_id' => $order->id])])
Voir le détail de ma commande
@endcomponent

## Télécharger votre facture
Vous pouvez télécharger votre facture en PDF en pièce jointe de cet email.

## Nous contacter
Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter:

- **Email:** {{ $settings->site_email ?? 'contact@bijoux.fr' }}
- **Téléphone:** {{ $settings->site_phone ?? 'Non défini' }}

Cordialement,<br>
{{ $settings->site_name ?? 'L\'équipe Bijoux' }}
@endcomponent
