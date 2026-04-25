<x-mail::message>
# Mise à jour de votre commande

Bonjour {{ $order->name }},

**{{ $notification->title }}**

{{ $notification->message }}

## Détails de la commande
- **Numéro de commande:** #{{ $order->id }}
- **Date de commande:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Statut actuel:** {{ ucfirst($order->status) }}
@if($order->tracking_number)
- **Numéro de suivi:** {{ $order->tracking_number }}
@endif
@if($order->current_location)
- **Localisation actuelle:** {{ $order->current_location }}
@endif
@if($order->estimated_delivery)
- **Livraison estimée:** {{ $order->estimated_delivery->format('d/m/Y') }}
@endif

## Suivi en temps réel
Vous pouvez suivre l'avancement de votre commande en temps réel depuis votre espace client :
<x-mail::button :url="route('user.order.tracking', ['order_id' => $order->id])">
Voir le suivi de commande
</x-mail::button>

## Détails de livraison
**Adresse de livraison:**
{{ $order->name }}
{{ $order->address }}
{{ $order->zip }} {{ $order->city }}
{{ $order->country }}

## Total de la commande
**Sous-total:** {{ number_format($order->subtotal, 2) }}€
**Livraison:** {{ number_format($order->shipping, 2) }}€
**Taxes:** {{ number_format($order->tax, 2) }}€
@if($order->discount > 0)
**Réduction:** -{{ number_format($order->discount, 2) }}€
@endif
**Total:** **{{ number_format($order->total, 2) }}€**

---

Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter.

Cordialement,<br>
L'équipe {{ $settings->site_name ?? config('app.name') }}
</x-mail::message>
