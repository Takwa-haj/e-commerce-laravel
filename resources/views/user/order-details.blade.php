@extends('layouts.app')
@section('content')
<style>
    .order-details-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        background: var(--surface);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 24px;
        font-weight: 700;
        color: var(--text_primary);
    }

    .page-title i {
        color: var(--primary);
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg_soft);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text_secondary);
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .back-button:hover {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 15%, transparent);
    }

    .info-card {
        background: var(--surface);
        border: 1px solid var(--border_soft);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .info-card h5 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 18px;
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border_soft);
    }

    .info-card h5 i {
        color: var(--primary);
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        gap: 0.375rem;
    }

    .status-delivered {
        background: color-mix(in srgb, var(--success) 20%, transparent);
        color: var(--success);
    }

    .status-confirmed {
        background: color-mix(in srgb, var(--info) 20%, transparent);
        color: var(--info);
    }

    .status-canceled {
        background: color-mix(in srgb, var(--error) 20%, transparent);
        color: var(--error);
    }

    .status-ordered {
        background: color-mix(in srgb, var(--warning) 20%, transparent);
        color: var(--warning);
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .product-image {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--border_soft);
        background: var(--surface);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .action-button {
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-refund {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-refund:hover:not(:disabled) {
        filter: brightness(1.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px color-mix(in srgb, var(--primary) 35%, transparent);
    }

    .btn-cancel {
        background: var(--surface);
        color: var(--text_secondary);
        border: 1px solid var(--border);
    }

    .btn-cancel:hover:not(:disabled) {
        background: var(--bg_soft);
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .alert-message {
        position: relative;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-success {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        border-color: color-mix(in srgb, var(--success) 25%, transparent);
        color: var(--success);
    }

    .alert-error {
        background: var(--error_bg);
        border-color: color-mix(in srgb, var(--error) 25%, transparent);
        color: var(--error);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th {
        text-align: left;
        padding: 0.75rem 0.5rem;
        font-weight: 600;
        color: var(--text_secondary);
        border-bottom: 2px solid var(--border_soft);
        font-size: 14px;
    }

    .table-custom td {
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--border_soft);
        color: var(--text_primary);
        font-size: 14px;
    }

    .table-custom tr:hover {
        background: var(--bg_soft);
    }

    .refund-status {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .refund-completed {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .refund-pending {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .refund-none {
        background: var(--bg_soft);
        color: var(--text_secondary);
    }

    @media (max-width: 768px) {
        .page-header { padding: 1rem; flex-direction: column; align-items: flex-start; }
        .page-title { font-size: 20px; }
        .info-card { padding: 1rem; }
        .action-button { min-width: 100%; }
    }
</style>

<main class="py-8">
    <div class="order-details-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-receipt"></i>
                <span>Commande #{{ $order->id }}</span>
            </div>
            <a href="{{ route('user.orders') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour aux commandes
            </a>
        </div>

        <!-- Alert Messages -->
        @if(Session::has('success'))
            <div class="alert-message alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ Session::get('success') }}</div>
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert-message alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ Session::get('error') }}</div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Information -->
                <div class="info-card">
                    <h5><i class="fas fa-info-circle"></i> Informations de la commande</h5>
                    <table class="table-custom">
                        <tbody>
                            <tr>
                                <td class="w-1/3 font-medium text-gray-700">N° de commande</td>
                                <td>#{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Date de commande</td>
                                <td>{{ $order->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Statut</td>
                                <td>
                                    @if ($order->status == 'delivered')
                                        <span class="status-badge status-delivered">
                                            <i class="fas fa-check-circle"></i>
                                            Livré
                                        </span>
                                    @elseif ($order->status == 'confirmed')
                                        <span class="status-badge status-confirmed">
                                            <i class="fas fa-check"></i>
                                            Confirmé
                                        </span>
                                    @elseif ($order->status == 'canceled')
                                        <span class="status-badge status-canceled">
                                            <i class="fas fa-times-circle"></i>
                                            Annulé
                                        </span>
                                    @else
                                        <span class="status-badge status-ordered">
                                            <i class="fas fa-clock"></i>
                                            Commandé
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Date de livraison</td>
                                <td>{{ $order->delivered_date ?: 'Non livré' }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Téléphone</td>
                                <td>{{ $order->phone }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Ordered Items -->
                <div class="info-card">
                    <h5><i class="fas fa-box-open"></i> Articles commandés</h5>
                    <div class="overflow-x-auto">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Remboursement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="product-image">
                                            <div class="product-details">
                                                <h6>{{ $item->product->name }}</h6>
                                                <div class="sku">SKU: {{ $item->product->SKU }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price, 2) }}€</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center font-medium">{{ number_format($item->price * $item->quantity, 2) }}€</td>
                                    <td class="text-center">
                                        @if($item->refund_status != 'none')
                                            <span class="refund-status refund-{{ $item->refund_status }}">
                                                <i class="fas fa-{{ $item->refund_status == 'completed' ? 'check' : 'clock' }}"></i>
                                                {{ ucfirst($item->refund_status) }}
                                            </span>
                                        @else
                                            <span class="refund-status refund-none">
                                                <i class="fas fa-minus"></i>
                                                Aucun
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Shipping Address -->
                <div class="info-card">
                    <h5><i class="fas fa-map-marker-alt"></i> Adresse de livraison</h5>
                    <div class="space-y-2 text-sm">
                        <div class="font-medium">{{ $order->name }}</div>
                        <div>{{ $order->address }}</div>
                        <div>{{ $order->locality }}</div>
                        <div>{{ $order->city }}, {{ $order->zip }}</div>
                        <div>{{ $order->country }}</div>
                        <div class="pt-2 border-t border-gray-100">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            {{ $order->phone }}
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="info-card">
                    <h5><i class="fas fa-credit-card"></i> Résumé du paiement</h5>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 2) }}€</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Réduction</span>
                            <span>-{{ number_format($order->discount, 2) }}€</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Livraison</span>
                            <span class="font-medium">
                                @if($order->shipping > 0)
                                    {{ number_format($order->shipping, 2) }}€
                                @else
                                    <span class="text-green-600">Gratuite</span>
                                @endif
                            </span>
                        </div>
                        @if($order->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">TVA</span>
                            <span>{{ number_format($order->tax, 2) }}€</span>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between font-medium text-base">
                                <span>Total</span>
                                <span>{{ number_format($order->total, 2) }}€</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 text-xs">
                            <div class="flex justify-between items-center py-1">
                                <span class="text-gray-500">Méthode</span>
                                <span class="payment-method">
                                    @if($transaction->mode == 'card')
                                        <i class="fas fa-credit-card"></i>
                                    @elseif($transaction->mode == 'paypal')
                                        <i class="fab fa-paypal"></i>
                                    @else
                                        <i class="fas fa-money-bill-wave"></i>
                                    @endif
                                    {{ ucfirst($transaction->mode) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="text-gray-500">Statut</span>
                                <span class="{{ $transaction->status == 'approved' ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            @php
                $canRequestRefund = false;
                $refundMessage = '';

                if($order->status == 'delivered' && $order->delivered_date) {
                    $daysSinceDelivery = now()->diffInDays($order->delivered_date);
                    $hasRefundableItems = false;

                    foreach($order->orderItems as $item) {
                        if($item->refund_status == 'none') {
                            $hasRefundableItems = true;
                            break;
                        }
                    }

                    if($daysSinceDelivery <= 14 && $hasRefundableItems) {
                        $canRequestRefund = true;
                        $refundMessage = "Vous avez " . (14 - $daysSinceDelivery) . " jours restants pour demander un remboursement.";
                    }
                }
            @endphp

            @if($canRequestRefund)
                <a href="{{ route('user.refund.request.form', ['order_id' => $order->id]) }}"
                   class="action-button btn-refund">
                    <i class="fas fa-undo-alt"></i>
                    Demander un remboursement
                </a>
            @else
                <button class="action-button btn-refund" disabled>
                    <i class="fas fa-undo-alt"></i>
                    Demander un remboursement
                </button>
            @endif

            @if($order->status != 'canceled' && $order->status != 'delivered')
                <form id="cancel-order-form" action="{{ route('user.order.cancel') }}" method="POST" class="flex-1">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="button" class="action-button btn-cancel cancel-order w-full">
                        <i class="fas fa-times-circle"></i>
                        Annuler la commande
                    </button>
                </form>
            @else
                <button class="action-button btn-cancel" disabled>
                    <i class="fas fa-times-circle"></i>
                    Annuler la commande
                </button>
            @endif
        </div>
        <!-- Tracking Actions -->
<div class="action-buttons mt-4">
    <a href="{{ route('user.order.tracking.print', ['order_id' => $order->id]) }}"
       class="action-button btn-track" target="_blank">
        <i class="fas fa-print"></i>
        Imprimer le suivi
    </a>

    <a href="{{ route('user.order.invoice', ['order_id' => $order->id]) }}"
       class="action-button btn-invoice" target="_blank">
        <i class="fas fa-file-invoice"></i>
        Télécharger facture
    </a>

    <a href="javascript:void(0)"
       onclick="shareOrderTracking('{{ route('public.order.tracking', ['order_id' => $order->id, 'token' => md5($order->id . $order->created_at)]) }}')"
       class="action-button btn-share">
        <i class="fas fa-share-alt"></i>
        Partager le suivi
    </a>
</div>

<script>
function shareOrderTracking(url) {
    if (navigator.share) {
        navigator.share({
            title: 'Suivi de ma commande #{{ $order->id }}',
            text: 'Suivez ma commande #{{ $order->id }}',
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Lien copié dans le presse-papier !');
        });
    }
}
</script>
        @if($refundMessage)
            <div class="mt-3 text-sm text-gray-500 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                {{ $refundMessage }}
            </div>
        @endif
    </div>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Cancel order confirmation
    $('.cancel-order').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Annuler la commande',
            text: "Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#cancel-order-form').submit();
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert-message').fadeOut();
    }, 5000);
});
</script>
@endpush
@endsection
