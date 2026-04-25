@extends('layouts.app')
@section('content')
<style>
    .text-success {
        color: #278c04;
    }
    .text-danger {
        color: #d61808;
    }
    .cart-totals {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .cart-totals th {
        font-weight: 500;
        padding: 12px 0;
        color: #6c757d;
    }
    .cart-totals td {
        text-align: right;
        padding: 12px 0;
        font-weight: 600;
        color: #212529;
    }
    .checkout-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }
    .checkout-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    .checkout-steps__item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 2;
        text-decoration: none;
        color: #6c757d;
        flex: 1;
    }
    .checkout-steps__item.active {
        color: #d4af37;
    }
    .checkout-steps__item-number {
        width: 40px;
        height: 40px;
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .checkout-steps__item.active .checkout-steps__item-number {
        background: #d4af37;
        border-color: #d4af37;
        color: white;
    }
    .checkout-steps__item-title {
        display: flex;
        flex-direction: column;
        font-size: 14px;
    }
    .checkout-steps__item-title em {
        font-style: normal;
        font-size: 12px;
        color: #adb5bd;
        margin-top: 2px;
    }
    .order-complete {
        text-align: center;
        padding: 40px 20px;
    }
    .order-complete__message {
        margin-bottom: 40px;
    }
    .order-complete__message h3 {
        color: #278c04;
        margin: 20px 0 10px;
        font-weight: 600;
    }
    .order-complete__message p {
        color: #6c757d;
        font-size: 18px;
    }
    .order-info {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .order-info__item {
        text-align: center;
        padding: 15px;
        flex: 1;
        min-width: 200px;
    }
    .order-info__item label {
        display: block;
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 8px;
        font-weight: 500;
    }
    .order-info__item span {
        font-weight: 600;
        font-size: 16px;
        color: #212529;
        display: block;
    }
    .checkout-cart-items, .checkout-totals {
        width: 100%;
        border-collapse: collapse;
    }
    .checkout-cart-items th, .checkout-totals th {
        text-align: left;
        padding: 16px 0;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        background: #f8f9fa;
    }
    .checkout-cart-items td, .checkout-totals td {
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .checkout-cart-items td.text-right, .checkout-totals td.text-right {
        text-align: right;
        font-weight: 600;
    }
    .btn-primary {
        background: linear-gradient(135deg, #d4af37 0%, #bfb0ce 100%);
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-primary {
        border: 2px solid #d4af37;
        color: #d4af37;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title mb-4">Commande Reçue</h2>

        <div class="checkout-steps mb-5">
            <a href="{{ route('cart.index') }}" class="checkout-steps__item">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Panier</span>
                    <em>Gérez vos articles</em>
                </span>
            </a>
            <a href="{{ route('cart.checkout') }}" class="checkout-steps__item">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Livraison</span>
                    <em>Entrez vos coordonnées</em>
                </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Confirmation</span>
                    <em>Commande confirmée</em>
                </span>
            </a>
        </div>

        <div class="order-complete">
            <div class="order-complete__message">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#278c04" />
                    <path d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z" fill="white" />
                </svg>
                <h3>Votre commande est terminée !</h3>
                <p>Merci. Votre commande a bien été reçue.</p>
            </div>

            <div class="order-info">
                <div class="order-info__item">
                    <label>Numéro de commande</label>
                    <span>#{{ $order->id }}</span>
                </div>
                <div class="order-info__item">
                    <label>Date</label>
                    <span>{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="order-info__item">
                    <label>Total</label>
                    <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
                </div>
                <div class="order-info__item">
                    <label>Mode de paiement</label>
                    <span>
                        @php
                            $paymentMethods = [
                                'card' => 'Carte bancaire',
                                'paypal' => 'PayPal',
                                'cod' => 'Paiement à la livraison'
                            ];
                        @endphp
                        {{ $paymentMethods[$order->transaction->mode] ?? ucfirst($order->transaction->mode) }}
                    </span>
                </div>
            </div>

            <div class="checkout__totals-wrapper mt-5">
                <div class="cart-totals">
                    <h3 class="mb-4 pb-2 border-bottom">Détails de la commande</h3>

                    <table class="checkout-cart-items mb-4">
                        <thead>
                            <tr>
                                <th>PRODUIT</th>
                                <th class="text-right">SOUS-TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                             width="60" height="60"
                                             alt="{{ $item->product->name }}"
                                             class="rounded me-3"
                                             style="object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Quantité: {{ $item->quantity }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="checkout-totals">
                        <tbody>
                            <tr>
                                <th>SOUS-TOTAL</th>
                                <td class="text-right">{{ number_format($order->subtotal, 2, ',', ' ') }} €</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <th>RÉDUCTION</th>
                                <td class="text-right text-danger">-{{ number_format($order->discount, 2, ',', ' ') }} €</td>
                            </tr>
                            @endif
                            <tr>
                                <th>LIVRAISON</th>
                                <td class="text-right">
                                    @if($order->shipping > 0)
                                        {{ number_format($order->shipping, 2, ',', ' ') }} €
                                    @else
                                        <span class="text-success">Gratuite</span>
                                    @endif
                                </td>
                            </tr>
                            @if($order->tax > 0)
                            <tr>
                                <th>TVA ({{ $settings->tax_rate ?? config('cart.tax') }}%)</th>
                                <td class="text-right">{{ number_format($order->tax, 2, ',', ' ') }} €</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <th class="pt-3">
                                    <h5 class="mb-0">TOTAL</h5>
                                </th>
                                <td class="text-right pt-3">
                                    <h5 class="mb-0">{{ number_format($order->total, 2, ',', ' ') }} €</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('shop.index') }}" class="btn btn-primary">
                    <i class="bi bi-bag me-2"></i>CONTINUER MES ACHATS
                </a>
                @if(auth()->check())
                <a href="{{ route('user.orders') }}" class="btn btn-outline-primary ms-3">
                    <i class="bi bi-list-check me-2"></i>VOIR MES COMMANDES
                </a>
                @endif
                <div class="mt-4">
                    <small class="text-muted">
                        Un email de confirmation vous a été envoyé à {{ auth()->user()->email ?? 'votre adresse email' }}
                    </small>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(function(){
        // Animation de confirmation
        $('.order-complete__message svg').css({
            'transform': 'scale(0)',
            'transition': 'transform 0.6s ease-out'
        });

        setTimeout(function(){
            $('.order-complete__message svg').css('transform', 'scale(1)');
        }, 300);

        // Impression de la commande
        $('.btn-print').on('click', function(){
            window.print();
        });
    });
</script>
@endpush
