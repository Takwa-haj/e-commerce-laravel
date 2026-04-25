@extends('layouts.app')
@section('content')
<style>
    .text-success { color: var(--success); }
    .text-danger { color: var(--error); }

    main.pt-90 {
        padding-top: 90px;
        background: var(--bg_main);
    }

    .shop-checkout.container {
        background: var(--surface);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 20px var(--card_shadow);
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .page-title {
        color: var(--text_main);
        font-weight: 600;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border_soft);
    }

    /* Checkout Steps */
    .checkout-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
        background: var(--surface);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 15px var(--card_shadow);
    }

    .checkout-steps::before {
        content: '';
        position: absolute;
        top: 35px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: var(--border_soft);
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
        color: var(--text_secondary);
        flex: 1;
    }

    .checkout-steps__item.active { color: var(--primary); }

    .checkout-steps__item-number {
        width: 40px;
        height: 40px;
        background: var(--surface);
        border: 2px solid var(--border_soft);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .checkout-steps__item.active .checkout-steps__item-number {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .checkout-steps__item-title em {
        font-style: normal;
        font-size: 12px;
        color: var(--text_secondary);
    }

    /* Table Styles */
    .cart-table {
        width: 100%;
        background: var(--surface);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 15px var(--card_shadow);
    }

    .cart-table th {
        background: var(--bg_soft);
        padding: 16px 12px;
        font-weight: 600;
        border-bottom: 2px solid var(--border_soft);
        color: var(--text_main);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cart-table td {
        padding: 20px 12px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border_soft);
        color: var(--text_main);
    }

    .cart-table tr:hover td {
        background: color-mix(in srgb, var(--primary) 8%, transparent);
    }

    /* Quantity Control */
    .qty-control {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border_soft);
        background: var(--surface);
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--text_main);
    }

    .qty-btn:hover {
        background: var(--bg_soft);
        border-color: var(--primary);
        color: var(--primary);
    }

    .qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    .qty-value {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        color: var(--text_main);
    }

    /* Remove Button */
    .remove-btn {
        color: var(--error);
        background: transparent;
        border: none;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .remove-btn:hover { background: var(--error_bg); }

    /* Cart Totals */
    .cart-totals {
        background: var(--surface);
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 15px var(--card_shadow);
    }

    .cart-totals h3 {
        color: var(--text_main);
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border_soft);
    }

    .cart-totals th {
        font-weight: 500;
        padding: 12px 0;
        color: var(--text_secondary);
        text-align: left;
    }

    .cart-totals td {
        text-align: right;
        padding: 12px 0;
        font-weight: 600;
        color: var(--text_main);
    }

    .cart-totals tr:last-child { border-top: 2px solid var(--divider); }

    .cart-totals tr:last-child th,
    .cart-totals tr:last-child td {
        color: var(--primary);
        font-size: 1.1em;
        padding-top: 15px;
    }

    /* Coupon Form */
    .coupon-form {
        background: var(--surface);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px var(--card_shadow);
        display: flex;
        gap: 10px;
    }

    .coupon-form input[type="text"] {
        border: 1px solid var(--border_soft);
        background: var(--surface);
        color: var(--text_main);
        border-radius: 6px;
        padding: 12px 16px;
        flex-grow: 1;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .coupon-form input[type="text"]:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 8%, transparent);
    }

    .coupon-form input[type="text"]::placeholder {
        color: var(--text_secondary);
    }

    .coupon-form button[type="submit"] {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .coupon-form button[type="submit"]:hover {
        filter: brightness(0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 20%, transparent);
    }

    /* Buttons */
    .btn-checkout {
        background: var(--primary);
        border: none;
        padding: 16px 32px;
        font-weight: 600;
        border-radius: 6px;
        width: 100%;
        transition: all 0.3s ease;
        color: white;
        cursor: pointer;
    }

    .btn-checkout:hover {
        filter: brightness(0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px color-mix(in srgb, var(--primary) 30%, transparent);
    }

    .btn-outline-danger {
        color: var(--error);
        border-color: var(--error);
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover { background: var(--error); color: white; }

    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover { background: var(--primary); color: white; }

    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
        border-radius: 6px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        filter: brightness(0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px color-mix(in srgb, var(--primary) 30%, transparent);
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: var(--surface);
        border-radius: 8px;
        box-shadow: 0 2px 15px var(--card_shadow);
    }

    .empty-cart-icon { font-size: 64px; color: var(--text_secondary); }

    .empty-cart h4 { color: var(--text_main); font-weight: 600; }
    .empty-cart p { color: var(--text_secondary); }

    /* Product Attributes */
    .product-attributes { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }

    .attribute-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--bg_soft);
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 12px;
        color: var(--text_secondary);
        border: 1px solid var(--border_soft);
    }

    .color-swatch {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid var(--border_soft);
    }

    /* Alerts */
    .alert { border-radius: 6px; border: none; box-shadow: 0 2px 15px var(--card_shadow); }

    .alert-success {
        background-color: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-danger {
        background-color: var(--error_bg);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    @media (max-width: 768px) {
        .checkout-steps { flex-direction: column; gap: 20px; }
        .checkout-steps::before { display: none; }
        .checkout-steps__item { flex-direction: row; text-align: left; gap: 15px; }
        .cart-table th, .cart-table td { padding: 12px 8px; font-size: 13px; }
    }

    @media (max-width: 576px) {
        .shop-checkout.container { padding: 15px; }
        .qty-control { flex-direction: column; gap: 5px; }
        .coupon-form { flex-direction: column; }
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title mb-4">Panier d'achat</h2>

        <div class="checkout-steps mb-5">
            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Panier</span>
                    <em>Gérez vos articles</em>
                </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Livraison</span>
                    <em>Entrez vos coordonnées</em>
                </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Paiement</span>
                    <em>Paiement sécurisé</em>
                </span>
            </a>
        </div>

        @if($items->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table__wrapper mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive-lg">
                                <table class="cart-table table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4" style="width: 35%; min-width: 320px;">Produit</th>
                                            <th class="text-center" style="width: 10%; min-width: 90px;">Prix</th>
                                            <th class="text-center" style="width: 18%; min-width: 140px;">Quantité</th>
                                            <th class="text-center" style="width: 15%; min-width: 120px;">Sous-total</th>
                                            <th class="text-center pe-4" style="width: 10%; min-width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="shopping-cart__product-item me-3 flex-shrink-0">
                                                        <img loading="lazy"
                                                             src="{{ asset('uploads/products/thumbnails') }}/{{$item->model->image}}"
                                                             width="70" height="70"
                                                             alt="{{ $item->name }}"
                                                             class="rounded"
                                                             style="object-fit: cover;">
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width: 0;">
                                                        <h6 class="mb-1 text-truncate" style="max-width: 200px; color: var(--text-main, #4A2E2E); font-weight: 600;">{{ $item->name }}</h6>
                                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                                            Réf: {{ $item->model->sku ?? 'N/A' }}
                                                        </small>

                                                        <!-- Color and Size Attributes -->
                                                        <div class="product-attributes">
                                                            @if(!empty($item->options['color']))
                                                            <div class="attribute-item">
                                                                <span class="attribute-label">Couleur:</span>
                                                                <span class="attribute-value">
                                                                    @if(strpos($item->options['color'], '#') === 0)
                                                                        <span class="color-swatch" style="background-color: {{ $item->options['color'] }};"></span>
                                                                    @endif
                                                                    {{ $item->options['color'] }}
                                                                </span>
                                                            </div>
                                                            @endif

                                                            @if(!empty($item->options['size']))
                                                            <div class="attribute-item">
                                                                <span class="attribute-label">Taille:</span>
                                                                <span class="attribute-value">{{ $item->options['size'] }}</span>
                                                            </div>
                                                            @endif

                                                            @if(!empty($item->options['color_name']) && empty($item->options['color']))
                                                            <div class="attribute-item">
                                                                <span class="attribute-label">Couleur:</span>
                                                                <span class="attribute-value">{{ $item->options['color_name'] }}</span>
                                                            </div>
                                                            @endif

                                                            @if(!empty($item->options['color_code']))
                                                            <div class="attribute-item">
                                                                <span class="attribute-label">Code couleur:</span>
                                                                <span class="attribute-value">
                                                                    <span class="color-swatch" style="background-color: {{ $item->options['color_code'] }};"></span>
                                                                    {{ $item->options['color_code'] }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle px-2">
                                                <span class="shopping-cart__product-price fw-semibold d-block" style="color: var(--text-main, #4A2E2E);">{{ $item->price}}€</span>
                                            </td>
                                            <td class="text-center align-middle px-2">
                                                <div class="qty-control d-flex justify-content-center align-items-center">
                                                    <form method="POST" action="{{ route('cart.qty.decrease',['rowId'=>$item->rowId]) }}" class="d-inline-block me-1">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="qty-btn"
                                                                {{ $item->qty <= 1 ? 'disabled' : '' }}
                                                                title="Diminuer">−</button>
                                                    </form>
                                                    <span class="qty-value mx-2 px-2 d-inline-block" style="min-width: 30px; color: var(--text-main, #4A2E2E);">{{ $item->qty }}</span>
                                                    <form method="POST" action="{{ route('cart.qty.increase',['rowId'=>$item->rowId]) }}" class="d-inline-block ms-1">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="qty-btn" title="Augmenter">+</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle px-2">
                                                <span class="shopping-cart__subtotal fw-bold d-block" style="color: var(--text-main, #4A2E2E);">{{ $item->subtotal() }} €</span>
                                            </td>
                                            <td class="text-center align-middle pe-4">
                                                <form method="POST" action="{{ route('cart.item.remove',['rowId'=>$item->rowId]) }}" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="remove-btn p-2" title="Supprimer">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-table-footer">
                    <div class="row g-3">
                        <div class="col-md-8">
                            @if(!Session::has('coupon'))
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="coupon-form">
                                @csrf
                                <div class="input-group">
                                    <input class="form-control" type="text" name="coupon_code"
                                           placeholder="Entrez votre code promo" value="">
                                    <button class="btn btn-light" type="submit" style="background: var(--primary, #B76E79); color: white; border: none; font-weight: 600;">APPLIQUER</button>
                                </div>
                            </form>
                            @else
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="alert alert-success p-3">
                                @csrf @method('DELETE')
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>{{ Session::get('coupon')['code'] }}</strong> appliqué avec succès !
                                        <div class="text-muted small">Réduction: -{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['discount'] ?? 0 }}</div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Retirer</button>
                                </div>
                            </form>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <form action="{{ route('cart.empty') }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                                    <i class="bi bi-trash me-2"></i>Vider le panier
                                </button>
                            </form>
                        </div>
                    </div>

                    @if(Session::has('success') || Session::has('error'))
                    <div class="mt-3">
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @elseif(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top: 90px;">
                    <div class="shopping-cart__totals-wrapper mb-4">
                        <div class="cart-totals">
                            <h3 class="mb-4 pb-2 border-bottom">Récapitulatif de commande</h3>

                            @if(Session::has('discounts'))
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th class="border-0">Sous-total</th>
                                        <td class="text-end border-0">{{ $settings->currency_symbol ?? '€' }}{{ Cart::instance('cart')->subtotal() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Réduction ({{ Session::get('coupon')['code'] }})</th>
                                        <td class="text-end text-danger">-{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['discount'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sous-total après réduction</th>
                                        <td class="text-end">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['subtotal'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Livraison</th>
                                        <td class="text-end">
                                            @if((Session::get('discounts')['shipping'] ?? 0) > 0)
                                                {{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['shipping'] }}
                                            @else
                                                <span class="text-success">Gratuit</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TVA ({{ $settings->tax_rate ?? config('cart.tax') }}%)</th>
                                        <td class="text-end">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['tax'] }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <th class="pt-3"><h5 class="mb-0">Total</h5></th>
                                        <td class="text-end pt-3"><h5 class="mb-0">{{ $settings->currency_symbol ?? '€' }}{{ Session::get('discounts')['total'] }}</h5></td>
                                    </tr>
                                </tbody>
                            </table>
                            @else
                            @php
                                $cartService = app(\App\Services\CartService::class);
                                $taxRate = $cartService->getTaxRate();
                                $subtotal = Cart::instance('cart')->subtotal();
                                $tax = ($subtotal * $taxRate) / 100;
                                $shipping = $cartService->getShippingCost();
                                $total = $subtotal + $tax + $shipping;
                            @endphp

                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th class="border-0">Sous-total</th>
                                        <td class="text-end border-0">{{ $cartService->formatPrice($subtotal) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Livraison</th>
                                        <td class="text-end">
                                            @if($shipping > 0)
                                                {{ $cartService->formatPrice($shipping) }}
                                            @else
                                                <span class="text-success">Gratuit</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TVA ({{ $taxRate }}%)</th>
                                        <td class="text-end">{{ $cartService->formatPrice($tax) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <th class="pt-3"><h5 class="mb-0">Total</h5></th>
                                        <td class="text-end pt-3"><h5 class="mb-0">{{ $cartService->formatPrice($total) }}</h5></td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>

                    <div class="mobile_fixed-btn_wrapper">
                        <div class="button-wrapper">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-checkout btn-lg w-100 mb-3">
                                <i class="bi bi-lock-fill me-2"></i>PROCÉDER AU PAIEMENT
                            </a>
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="bi bi-arrow-left me-2"></i>CONTINUER MES ACHATS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h4 class="mb-3">Votre panier est vide</h4>
            <p class="text-muted mb-4">Il semble que vous n'ayez pas encore ajouté d'articles à votre panier.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-bag me-2"></i>COMMENCER MES ACHATS
            </a>
        </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(function(){
        // Contrôles de quantité
        $(".qty-btn").on("click", function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        });

        // Supprimer un article
        $(".remove-btn").on("click", function(e){
            if(!confirm('Êtes-vous sûr de vouloir retirer cet article de votre panier ?')) {
                e.preventDefault();
            }
        });

        // Confirmation de vidage du panier
        $("button[type='submit']").on("click", function(){
            if($(this).text().includes('Vider le panier')) {
                return confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?');
            }
        });
    });
</script>
@endpush
