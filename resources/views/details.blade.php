@extends('layouts.app')
@section('content')
<style>
    .filled-heart{
      color: orange
    }

    /* Styles pour le bouton de partage */
    .share-options-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 1000;
        min-width: 300px;
    }

    .share-dropdown-content {
        background: white;
        border-radius: 8px;
    }

    /* Couleurs des boutons sociaux */
    .btn-facebook {
        background-color: #1877f2;
        color: white;
        border: none;
    }

    .btn-twitter {
        background-color: #1da1f2;
        color: white;
        border: none;
    }

    .btn-whatsapp {
        background-color: #25d366;
        color: white;
        border: none;
    }

    .btn-pinterest {
        background-color: #e60023;
        color: white;
        border: none;
    }

    .btn-facebook:hover, .btn-twitter:hover,
    .btn-whatsapp:hover, .btn-pinterest:hover {
        opacity: 0.9;
        color: white;
    }

    /* Notification toast */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #333;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    .toast-success {
        background: #28a745;
    }

    .toast-error {
        background: #dc3545;
    }

    .toast-warning {
        background: #ffc107;
        color: #212529;
    }

    .toast-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .toast-message {
        margin-right: 15px;
    }

    .toast-close {
        background: none;
        border: none;
        color: inherit;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Étoiles de notation */
    .star-rating__star-icon {
        cursor: pointer;
        transition: fill 0.2s;
    }

    .star-rating__star-icon.filled {
        fill: #ffd700 !important;
    }

    .review-star.filled {
        fill: #ffd700 !important;
    }

    .product-single__reviews-item {
        display: flex;
        gap: 15px;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .customer-avatar img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
    }

    .customer-review {
        flex: 1;
    }

    .review-date {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 10px;
    }

    /* Ajustements responsive */
    @media (max-width: 768px) {
        .share-options-dropdown {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 300px;
        }

        .social-share-buttons .d-flex {
            flex-direction: column;
        }

        .social-share-buttons button {
            width: 100%;
            margin-bottom: 5px;
        }

        .product-single__reviews-item {
            flex-direction: column;
        }
    }

    /* Styles informations supplémentaires */
    .product-single__addtional-info .item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .product-single__addtional-info .item:last-child {
        border-bottom: none;
    }

    .product-single__addtional-info .item label {
        font-weight: 600;
        color: #333;
    }

    /* Badges de statut */
    .stock-badge {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.85em;
        font-weight: 500;
    }

    .in-stock {
        background-color: #d4edda;
        color: #155724;
    }

    .out-of-stock {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Animation de pulsation pour les étoiles */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .rating-error {
        animation: pulse 1s 3;
    }

    /* Styles d'erreur de validation */
    .rating-required {
        border: 2px solid #dc3545 !important;
        border-radius: 4px;
        padding: 5px;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
        display: none;
    }

    .star-rating-container {
        padding: 5px;
        border-radius: 4px;
        transition: border-color 0.3s;
    }

    .star-rating-container.error {
        border: 2px solid #dc3545;
        padding: 8px;
    }

    /* Variant selection styles - UPDATED */
    .variant-option {
        padding: 8px 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 8px;
        margin-bottom: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
    }

    .variant-option:hover:not(.disabled) {
        border-color: #999;
        transform: translateY(-2px);
    }

    .variant-option.active {
        border-color: #d4af37 !important;
        background: #fffbf0;
        color: #d4af37;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(212, 175, 55, 0.2);
    }

    .variant-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }

    .variant-option.disabled:hover {
        border-color: #ddd;
        transform: none;
    }

    .color-option {
        position: relative;
        padding-left: 40px;
    }

    .color-option::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }

    .color-option.active::after {
        content: '✓';
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
        z-index: 1;
    }

    .variant-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .variant-stock {
        font-size: 14px;
        color: #666;
    }

    .variant-price {
        font-size: 18px;
        font-weight: 600;
        color: #d4af37;
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<main class="pt-90">
    <div class="mb-md-1 pb-md-3"></div>
    <section class="product-single container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <div class="product-single__media" data-media-type="vertical-thumbnail">
                    <div class="product-single__image">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide product-single__image-item">
                                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $product->image }}" width="674"
                                        height="674" alt="{{ $product->name }}" />
                                    <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $product->image }}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" title="Zoom">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_zoom" />
                                        </svg>
                                    </a>
                                </div>
                                @foreach (explode(',',$product->images) as $gimg )
                                    <div class="swiper-slide product-single__image-item">
                                        <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $gimg }}" width="674"height="674" alt="{{ $product->name }}" />
                                        <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $gimg}}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" title="Zoom">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_zoom" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-prev">
                                <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_prev_sm" />
                                </svg>
                            </div>
                            <div class="swiper-button-next">
                                <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_next_sm" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="product-single__thumbnail">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide product-single__image-item">
                                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}" width="104" height="104" alt="{{ $product->name }}" />
                                </div>
                                @foreach (explode(',',$product->images) as $gimg )
                                    <div class="swiper-slide product-single__image-item">
                                        <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/thumbnails') }}/{{ $gimg}}" width="104" height="104" alt="{{ $product->name }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex justify-content-between mb-4 pb-md-2">
                    <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                        <a href="{{ url('/') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Accueil</a>
                        <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                        <a href="{{ route('shop.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Boutique</a>
                    </div>

                    <div class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                        <a href="#" class="text-uppercase fw-medium">
                            <svg width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_prev_md" />
                            </svg>
                            <span class="menu-link menu-link_us-s">Précédent</span>
                        </a>
                        <a href="#" class="text-uppercase fw-medium">
                            <span class="menu-link menu-link_us-s">Suivant</span>
                            <svg width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_next_md" />
                            </svg>
                        </a>
                </div>
            </div>
            <h1 class="product-single__name">{{ $product->name }}</h1>
            <div class="product-single__rating">
                <div class="reviews-group d-flex">
                    @php
                        $averageRating = $product->getAverageRatingAttribute();
                        $fullStars = floor($averageRating);
                        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <svg class="review-star filled" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_star" />
                            </svg>
                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: #ffd700;">
                                <use href="#icon_star_half" />
                            </svg>
                        @else
                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_star" />
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="reviews-note text-lowercase text-secondary ms-1">
                    {{ $reviews->count() }} avis
                </span>
            </div>
            <div class="product-single__price">
                <span class="current-price">
                    @if ($product->sale_price)
                        <s class="text-muted">{{ $settings->currency_symbol ?? '€' }}{{ $product->regular_price }}</s>
                        <span class="text-danger fw-bold">{{ $settings->currency_symbol ?? '€' }}{{ $product->sale_price }}</span>
                    @else
                        <span class="fw-bold">{{ $settings->currency_symbol ?? '€' }}{{ $product->regular_price }}</span>
                    @endif
                </span>
            </div>
            <div class="product-single__short-desc">
                <p>{{ $product->short_description }}</p>
            </div>

            <!-- Variant Selection -->
            @if(method_exists($product, 'variants') && ($product->sizes->count() > 0 || $product->colors->count() > 0))
            <div class="product-variants mb-4">
                @if($product->sizes->count() > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Taille:</label>
                    <div class="size-options d-flex flex-wrap">
                        @foreach($product->sizes as $size)
                            @php
                                $available = false;
                                $availableQuantity = 0;

                                if($product->colors->count() > 0) {
                                    // Check if any color variant has this size
                                    $availableVariants = $product->variants()
                                        ->where('size_id', $size->id)
                                        ->where('quantity', '>', 0)
                                        ->get();
                                    $available = $availableVariants->count() > 0;
                                    $availableQuantity = $availableVariants->sum('quantity');
                                } else {
                                    // Check specific size variant
                                    $variant = $product->findVariant($size->id, null);
                                    $available = $variant && $variant->quantity > 0;
                                    $availableQuantity = $available ? $variant->quantity : 0;
                                }
                            @endphp
                            <button type="button"
                                    class="variant-option size-option {{ $available ? '' : 'disabled' }}"
                                    data-type="size"
                                    data-id="{{ $size->id }}"
                                    data-name="{{ $size->name }}"
                                    {{ !$available ? 'disabled' : '' }}>
                                {{ $size->name }}
                                @if($availableQuantity > 0)
                                    <small class="ms-1 text-muted">({{ $availableQuantity }})</small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($product->colors->count() > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Couleur:</label>
                    <div class="color-options d-flex flex-wrap">
                        @foreach($product->colors as $color)
                            @php
                                $available = false;
                                $availableQuantity = 0;

                                if($product->sizes->count() > 0) {
                                    // Check if any size variant has this color
                                    $availableVariants = $product->variants()
                                        ->where('color_id', $color->id)
                                        ->where('quantity', '>', 0)
                                        ->get();
                                    $available = $availableVariants->count() > 0;
                                    $availableQuantity = $availableVariants->sum('quantity');
                                } else {
                                    // Check specific color variant
                                    $variant = $product->findVariant(null, $color->id);
                                    $available = $variant && $variant->quantity > 0;
                                    $availableQuantity = $available ? $variant->quantity : 0;
                                }
                            @endphp
                            <button type="button"
                                    class="variant-option color-option {{ $available ? '' : 'disabled' }}"
                                    data-type="color"
                                    data-id="{{ $color->id }}"
                                    data-name="{{ $color->name }}"
                                    data-hex="{{ $color->hex_code }}"
                                    style="border-color: {{ $color->hex_code }} !important;"
                                    {{ !$available ? 'disabled' : '' }}>
                                <span style="display: inline-block; width: 15px; height: 15px; border-radius: 50%; background-color: {{ $color->hex_code }}; margin-right: 8px; border: 1px solid #ddd;"></span>
                                {{ $color->name }}
                                @if($availableQuantity > 0)
                                    <small class="ms-1 text-muted">({{ $availableQuantity }})</small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Selected variant info -->
                <div id="variant-info" class="variant-info" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="variant-details" class="fw-bold"></span>
                        <span id="variant-quantity" class="badge"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="variant-sku" class="text-muted small"></span>
                        <span id="variant-price" class="variant-price"></span>
                    </div>
                </div>
            </div>
            @endif
<!-- Stock Status Display amélioré -->
@php
    $totalStock = $product->total_quantity;
@endphp

@if($product->stock_status == 'outofstock' || $totalStock == 0)
    <div class="alert alert-danger mt-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <strong class="d-block">Rupture de stock</strong>
                <p class="mb-0 small">Ce produit n'est actuellement pas disponible. Veuillez nous excuser pour la gêne occasionnée.</p>
                @auth
                    @if(auth()->user()->utype == 'ADM')
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-warning mt-2">
                            <i class="icon-refresh"></i> Réapprovisionner
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@else
    @if($totalStock > 0 && $totalStock < 5)
        <div class="alert alert-warning mt-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-clock fa-2x me-3"></i>
                <div>
                    <strong class="d-block">Stock limité !</strong>
                    <p class="mb-0 small">Il ne reste que <span class="fw-bold">{{ $totalStock }}</span> exemplaire(s) en stock. Commandez vite !</p>
                </div>
            </div>
        </div>
    @elseif($totalStock >= 5 && $totalStock < 20)
        <div class="alert alert-info mt-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x me-3"></i>
                <div>
                    <strong class="d-block">En stock</strong>
                    <p class="mb-0 small">{{ $totalStock }} unités disponibles</p>
                </div>
            </div>
        </div>
    @endif
@endif
            @if (Cart::instance('cart')->content()->where('id',$product->id)->count()>0)
                <a href="{{ route('cart.index') }}" class="btn btn-warning mb-3">Voir le Panier</a>
            @else
                <form name="addtocart-form" method="post" action="{{ route('cart.add') }}" id="add-to-cart-form">
                    @csrf
                    <div class="product-single__addtocart">
                        <div class="qty-control position-relative mb-3">
                            <input type="number" name="quantity" id="quantity-input" value="1" min="1" class="qty-control__number text-center">
                            <div class="qty-control__reduce">-</div>
                            <div class="qty-control__increase">+</div>
                        </div>

                        <!-- Hidden fields for selected variant -->
                        <input type="hidden" name="id" value="{{ $product->id }}"/>
                        <input type="hidden" name="name" value="{{ $product->name }}"/>
                        <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price}}"/>
                        <input type="hidden" name="selected_size_id" id="selected-size-id" value="">
                        <input type="hidden" name="selected_color_id" id="selected-color-id" value="">
                        <input type="hidden" name="variant_id" id="selected-variant-id" value="">

                        <button type="submit" class="btn btn-primary btn-addtocart " data-aside="cartDrawer" id="add-to-cart-btn">
                            Ajouter au Panier
                        </button>
                    </div>
                </form>
            @endif

            <div class="product-single__addtolinks">
                @if (Cart::instance ('wishlist')->content()->where('id',$product->id)->count()>0)
                    <form action="{{ route('wishlist.item.remove',['rowId'=>Cart::instance ('wishlist')->content()->where('id',$product->id)->first()->rowId])}}" method="POST" id="frm-remove-item">
                        @csrf
                        @method('DELETE')
                        <a href="javascript:void()" class="menu-link menu-link_us-s add-to-wishlist filled-heart" onclick="document.getElementById('frm-remove-item').submit(); ">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart" />
                            </svg>
                            <span>Retirer de la Liste de Souhaits</span>
                        </a>
                    </form>
                @else
                    <form action="{{ route('wishlist.add') }}" method="POST" id="wishlist-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price:$product->sale_price }}">
                        <input type="hidden" name="quantity" value="1">
                        <a href="javascript:void()" class="menu-link menu-link_us-s add-to-wishlist" onclick="document.getElementById('wishlist-form').submit();">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart" />
                            </svg>
                            <span>Ajouter à la Liste de Souhaits</span>
                        </a>
                    </form>
                @endif

                {{-- Bouton de Partage Mise à Jour --}}
                <div class="share-button position-relative">
                    <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center"
                            onclick="toggleShareOptions()">
                        <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_sharing" />
                        </svg>
                        <span>Partager</span>
                    </button>

                    {{-- Menu déroulant des options de partage --}}
                    <div id="share-options" class="share-options-dropdown" style="display: none;">
                        <div class="share-dropdown-content p-3">
                            <div class="mb-3">
                                <label for="share-url" class="form-label mb-1">Lien de partage</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="share-url"
                                           value="{{ url()->current() }}" readonly>
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                            onclick="copyShareLink()">
                                        <i class="icon-copy"></i> Copier
                                    </button>
                                </div>
                            </div>

                            <div class="social-share-buttons mt-3">
                                <p class="mb-2">Partager sur :</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-facebook" onclick="shareToFacebook()">
                                        <i class="icon-facebook"></i> Facebook
                                    </button>
                                    <button class="btn btn-sm btn-twitter" onclick="shareToTwitter()">
                                        <i class="icon-twitter"></i> Twitter
                                    </button>
                                    <button class="btn btn-sm btn-whatsapp" onclick="shareToWhatsApp()">
                                        <i class="icon-whatsapp"></i> WhatsApp
                                    </button>
                                    <button class="btn btn-sm btn-pinterest" onclick="shareToPinterest()">
                                        <i class="icon-pinterest"></i> Pinterest
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-single__meta-info">
                <div class="meta-item">
                    <label>Référence :</label>
                    <span>{{ $product->SKU ?? 'N/A' }}</span>
                </div>
                <div class="meta-item">
                    <label>Catégorie :</label>
                    <span>{{ $product->Category->name }}</span>
                </div>
                @if($product->brand)
                <div class="meta-item">
                    <label>Marque :</label>
                    <span>{{ $product->brand->name }}</span>
                </div>
                @endif
                <div class="meta-item">
                    <label>Stock :</label>
                    <span class="stock-badge {{ $product->stock_status == 'instock' ? 'in-stock' : 'out-of-stock' }}">
                        {{ $product->stock_status == 'instock' ? 'En Stock' : 'Rupture de Stock' }}
                        @if($product->stock_status == 'instock' && $product->quantity)
                            ({{ $product->quantity }} disponible{{ $product->quantity > 1 ? 's' : '' }})
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="product-single__details-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
                    href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
                    href="#tab-additional-info" role="tab" aria-controls="tab-additional-info"
                    aria-selected="false">Informations Complémentaires</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews"
                    role="tab" aria-controls="tab-reviews" aria-selected="false">Avis ({{ $reviews->count() }})</a>
            </li>
        </ul>
        <div class="tab-content">
            {{-- Onglet Description --}}
            <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
                aria-labelledby="tab-description-tab">
                <div class="product-single__description">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            {{-- Onglet Informations Complémentaires --}}
            <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
                <div class="product-single__addtional-info">
                    <div class="item">
                        <label class="h6">Référence</label>
                        <span>{{ $product->SKU ?? 'N/A' }}</span>
                    </div>
                    <div class="item">
                        <label class="h6">Catégorie</label>
                        <span>{{ $product->category->name ?? 'N/A' }}</span>
                    </div>
                    @if($product->brand)
                    <div class="item">
                        <label class="h6">Marque</label>
                        <span>{{ $product->brand->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                    <div class="item">
                        <label class="h6">Statut du Stock</label>
                        <span class="badge bg-{{ $product->stock_status == 'instock' ? 'success' : 'danger' }}">
                            {{ $product->stock_status == 'instock' ? 'En Stock' : 'Rupture de Stock' }}
                        </span>
                    </div>
                    @if($product->total_quantity)
                    <div class="item">
                        <label class="h6">Quantité Totale Disponible</label>
                        <span>{{ $product->total_quantity }}</span>
                    </div>
                    @endif
                    @if($product->sizes && $product->sizes->count() > 0)
                    <div class="item">
                        <label class="h6">Tailles Disponibles</label>
                        <span>
                            @foreach($product->sizes as $size)
                                <span class="badge bg-light text-dark me-1">{{ $size->name }}</span>
                            @endforeach
                        </span>
                    </div>
                    @endif
                    @if($product->colors && $product->colors->count() > 0)
                    <div class="item">
                        <label class="h6">Couleurs Disponibles</label>
                        <span>
                            @foreach($product->colors as $color)
                                <span class="badge me-1" style="background-color: {{ $color->hex_code }}; color: white; padding: 5px 10px;">
                                    {{ $color->name }}
                                </span>
                            @endforeach
                        </span>
                    </div>
                    @endif
                    <div class="item">
                        <label class="h6">Ajouté le</label>
                        <span>{{ $product->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($product->featured)
                    <div class="item">
                        <label class="h6">Produit en Vedette</label>
                        <span class="badge bg-warning">Oui</span>
                    </div>
                    @endif

                    @if(method_exists($product, 'variants') && $product->variants->count() > 0)
                    <div class="item">
                        <label class="h6">Variantes Disponibles</label>
                        <div class="mt-2">
                            @foreach($product->variants->where('quantity', '>', 0) as $variant)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>
                                        @if($variant->size)
                                            Taille: {{ $variant->size->name }}
                                        @endif
                                        @if($variant->color)
                                            @if($variant->size) | @endif Couleur: {{ $variant->color->name }}
                                        @endif
                                        @if(!$variant->size && !$variant->color)
                                            Variante de base
                                        @endif
                                    </span>
                                    <span class="badge bg-info">{{ $variant->quantity }} disponible(s)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Onglet Avis --}}
            <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
                <h2 class="product-single__reviews-title">Avis ({{ $reviews->count() }})</h2>

                @if($reviews->count() > 0)
                <div class="product-single__reviews-list">
                    @foreach($reviews as $review)
                    <div class="product-single__reviews-item mb-4">
                        <div class="customer-avatar">
                            @if($review->user->avatar)
                                <img loading="lazy" src="{{ asset('uploads/avatars/' . $review->user->avatar) }}"
                                     alt="{{ $review->user->name }}" />
                            @else
                                <img loading="lazy" src="{{ asset('assets/images/avatar.jpg') }}"
                                     alt="Avatar Utilisateur" />
                            @endif
                        </div>
                        <div class="customer-review">
                            <div class="customer-name">
                                <h6>{{ $review->user->name }}</h6>
                                <div class="reviews-group d-flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="review-star {{ $i <= $review->rating ? 'filled' : '' }}"
                                             viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star" />
                                        </svg>
                                    @endfor
                                    <span class="ms-2 text-muted">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                            <div class="review-text">
                                <p>{{ $review->comment }}</p>
                            </div>

                            @if(Auth::check() && (Auth::id() == $review->user_id || Auth::user()->utype == 'ADM'))
                            <div class="review-actions mt-2">
                                @if(Auth::user()->utype == 'ADM' && !$review->approved)
                                    <form action="{{ route('review.approve', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                    </form>
                                @endif
                                <form action="{{ route('review.destroy', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    Aucun avis pour le moment. Soyez le premier à donner votre avis sur ce produit !
                </div>
                @endif

                @auth
                <div class="product-single__review-form mt-5">
                    <form action="{{ route('review.store') }}" method="POST" id="review-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <h5>Donnez votre avis sur "{{ $product->name }}"</h5>
                        <p>Votre adresse email ne sera pas publiée. Les champs obligatoires sont indiqués avec *</p>

                        <div class="select-star-rating mb-4">
                            <label class="mb-2">Votre note *</label>
                            <div class="star-rating-container" id="star-rating-container">
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="star-rating__star-icon me-1" width="30" height="30"
                                             data-rating="{{ $i }}"
                                             onclick="setRating({{ $i }})"
                                             style="cursor: pointer; fill: #ccc; transition: fill 0.2s;">
                                            <use href="#icon_star" />
                                        </svg>
                                    @endfor
                                    <span class="ms-3" id="rating-text">Cliquez sur les étoiles pour noter</span>
                                </div>
                            </div>
                            <input type="hidden" name="rating" id="form-input-rating" value="0" required />
                            <div class="error-message" id="rating-error">
                                Veuillez donner une note en cliquant sur les étoiles.
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <textarea id="form-input-review" class="form-control form-control_gray"
                                      placeholder="Votre avis *" name="comment" cols="30" rows="6" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-action">
                            <button type="button" class="btn btn-primary" onclick="submitReviewForm()">
                                Soumettre l'Avis
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="alert alert-warning mt-4">
                    Veuillez vous <a href="{{ route('login') }}" class="alert-link">connecter</a> pour donner votre avis.
                </div>
                @endauth
            </div>
        </div>
    </div>
</section>

<section class="products-carousel container">
    <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">Produits <strong>Similaires</strong></h2>

    <div id="related_products" class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
                "el": "#related_products .products-pagination",
                "type": "bullets",
                "clickable": true
            },
            "navigation": {
                "nextEl": "#related_products .products-carousel__next",
                "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
                "320": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 2,
                    "spaceBetween": 14
                },
                "768": {
                    "slidesPerView": 3,
                    "slidesPerGroup": 3,
                    "spaceBetween": 24
                },
                "992": {
                    "slidesPerView": 4,
                    "slidesPerGroup": 4,
                    "spaceBetween": 30
                }
            }
        }'>
            <div class="swiper-wrapper">
                @foreach ($rproducts as $rproduct)
                <div class="swiper-slide product-card">
                    <div class="pc__img-wrapper">
                        <a href="{{ route('shop.product.details',['product_slug'=>$rproduct->slug]) }}">
                            <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $rproduct->image }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">
                            @if($rproduct->images)
                                @foreach (explode(",", $rproduct->images) as $gimg)
                                    <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $gimg}}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img pc__img-second">
                                @endforeach
                            @endif
                        </a>
                        @if (Cart::instance('cart')->content()->where('id',$rproduct->id)->count()>0)
                            <a href="{{ route('cart.index') }}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning">Voir le Panier</a>
                        @else
                            <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $rproduct->id }}"/>
                                <input type="hidden" name="quantity" value="1"/>
                                <input type="hidden" name="name" value="{{ $rproduct->name }}"/>
                                <input type="hidden" name="price" value="{{ $rproduct->sale_price == '' ? $rproduct->regular_price : $rproduct->sale_price}}"/>
                                <button type="submit" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium " data-aside="cartDrawer" title="Ajouter au Panier">
                                    Ajouter au Panier
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="pc__info position-relative">
                        <p class="pc__category">{{ $rproduct->category->name }}</p>
                        <h6 class="pc__title">
                            <a href="{{ route('shop.product.details',['product_slug'=>$rproduct->slug]) }}">{{ $rproduct->name }}</a>
                        </h6>
                        <div class="product-card__price d-flex">
                            <span class="money price">
                                @if ($rproduct->sale_price)
                                    <s>{{ $settings->currency_symbol ?? '€' }}{{ $rproduct->regular_price }}</s>
                                    {{ $settings->currency_symbol ?? '€' }}{{ $rproduct->sale_price }}
                                @else
                                    {{ $settings->currency_symbol ?? '€' }}{{ $rproduct->regular_price }}
                                @endif
                            </span>
                        </div>

                        <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                title="Ajouter à la Liste de Souhaits">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_prev_md" />
                </svg>
            </div>
            <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_next_md" />
                </svg>
            </div>

            <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
        </div>
    </div>
</section>
</main>

<script>
    // Fonctionnalité de notation par étoiles
let currentRating = 0;

function setRating(rating) {
    currentRating = rating;
    const ratingInput = document.getElementById('form-input-rating');
    if (ratingInput) {
        ratingInput.value = rating;

        // Mettre à jour l'affichage des étoiles
        const stars = document.querySelectorAll('.star-rating__star-icon');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.fill = '#ffd700';
                star.classList.add('filled');
            } else {
                star.style.fill = '#ccc';
                star.classList.remove('filled');
            }
        });

        // Mettre à jour le texte de notation
        const ratingText = document.getElementById('rating-text');
        const ratingMessages = [
            'Cliquez sur les étoiles pour noter',
            'Mauvais',
            'Moyen',
            'Bon',
            'Très bon',
            'Excellent'
        ];
        if (ratingText) {
            ratingText.textContent = ratingMessages[rating];
            ratingText.style.color = rating > 0 ? '#333' : '#666';
        }

        // Cacher l'erreur si une note est sélectionnée
        const ratingError = document.getElementById('rating-error');
        if (ratingError) {
            ratingError.style.display = 'none';
        }

        // Retirer la classe d'erreur du conteneur
        const starContainer = document.getElementById('star-rating-container');
        if (starContainer) {
            starContainer.classList.remove('error');
        }
    }
}

// Validation du formulaire d'avis
function validateReviewForm() {
    const ratingInput = document.getElementById('form-input-rating');
    const ratingError = document.getElementById('rating-error');
    const commentInput = document.getElementById('form-input-review');
    const starContainer = document.getElementById('star-rating-container');

    let isValid = true;

    // Validation de la note
    if (!ratingInput || ratingInput.value == 0 || currentRating == 0) {
        if (ratingError) {
            ratingError.style.display = 'block';
            ratingError.textContent = 'Veuillez donner une note en cliquant sur les étoiles.';
        }

        // Ajouter la classe d'erreur au conteneur
        if (starContainer) {
            starContainer.classList.add('error');
            starContainer.style.animation = 'pulse 1s 3';
            setTimeout(() => {
                starContainer.style.animation = '';
            }, 3000);
        }

        isValid = false;
    } else {
        if (ratingError) {
            ratingError.style.display = 'none';
        }
        if (starContainer) {
            starContainer.classList.remove('error');
        }
    }

    // Validation du commentaire
    if (commentInput && commentInput.value.trim().length < 10) {
        showToast('Votre avis doit contenir au moins 10 caractères.', 'error');
        commentInput.focus();
        commentInput.style.borderColor = '#dc3545';
        isValid = false;
    } else if (commentInput) {
        commentInput.style.borderColor = '';
    }

    return isValid;
}

// Soumission du formulaire d'avis
function submitReviewForm() {
    if (validateReviewForm()) {
        // Montrer un message de confirmation
        showToast('Envoi de votre avis...', 'info');

        // Soumettre le formulaire
        document.getElementById('review-form').submit();
    }
}

// Fonctionnalité de partage
function toggleShareOptions() {
    const shareOptions = document.getElementById('share-options');
    if (shareOptions.style.display === 'none' || shareOptions.style.display === '') {
        shareOptions.style.display = 'block';
    } else {
        shareOptions.style.display = 'none';
    }
}

function copyShareLink() {
    const urlInput = document.getElementById('share-url');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // Pour les appareils mobiles

    try {
        navigator.clipboard.writeText(urlInput.value);
        showToast('Lien copié dans le presse-papier !', 'success');
    } catch (err) {
        document.execCommand('copy');
        showToast('Lien copié dans le presse-papier !', 'success');
    }
}

// Fonctions de partage sur les réseaux sociaux
function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent("Découvrez ce produit : {{ $product->name }}");
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400');
    showToast('Ouverture de Facebook...', 'info');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent("Découvrez ce produit : {{ $product->name }}");
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
    showToast('Ouverture de Twitter...', 'info');
}

function shareToWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent("Découvrez ce produit : {{ $product->name }}\n\n");
    window.open(`https://wa.me/?text=${text}${url}`, '_blank');
    showToast('Ouverture de WhatsApp...', 'info');
}

function shareToPinterest() {
    const url = encodeURIComponent(window.location.href);
    const media = encodeURIComponent("{{ asset('uploads/products/' . $product->image) }}");
    const description = encodeURIComponent("{{ $product->name }} - {{ $product->short_description }}");
    window.open(`https://pinterest.com/pin/create/button/?url=${url}&media=${media}&description=${description}`, '_blank', 'width=600,height=400');
    showToast('Ouverture de Pinterest...', 'info');
}

// API Web Share pour les navigateurs modernes
function shareNative() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $product->name }}",
            text: "{{ $product->short_description }}",
            url: window.location.href,
        })
        .then(() => showToast('Partage réussi !', 'success'))
        .catch((error) => showToast('Erreur de partage : ' + error, 'error'));
    } else {
        // Retour au menu de partage personnalisé
        toggleShareOptions();
    }
}

// Notification toast
function showToast(message, type = 'info') {
    // Supprimer les notifications existantes
    const existingToast = document.getElementById('custom-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Créer une nouvelle notification
    const toast = document.createElement('div');
    toast.id = 'custom-toast';
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;

    document.body.appendChild(toast);

    // Suppression automatique après 3 secondes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// ==============================================
// VARIANT SELECTION WITH REAL-TIME STOCK
// ==============================================
let selectedSize = null;
let selectedColor = null;
let selectedVariant = null;
let productStock = {{ $product->quantity }};

// Initialize variant selection
function initVariantSelection() {
    console.log('Initializing variant selection...');

    const variantOptions = document.querySelectorAll('.variant-option:not(.disabled)');
    const variantInfo = document.getElementById('variant-info');
    const variantDetails = document.getElementById('variant-details');
    const variantQuantity = document.getElementById('variant-quantity');
    const variantPrice = document.getElementById('variant-price');
    const variantSKU = document.getElementById('variant-sku');
    const selectedSizeInput = document.getElementById('selected-size-id');
    const selectedColorInput = document.getElementById('selected-color-id');
    const selectedVariantInput = document.getElementById('selected-variant-id');
    const quantityInput = document.getElementById('quantity-input');
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    // Size option click handler
    document.querySelectorAll('.size-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            if (this.classList.contains('disabled')) return;

            // Remove active class from all sizes
            document.querySelectorAll('.size-option').forEach(opt => {
                opt.classList.remove('active');
            });

            // Add active class to selected size
            this.classList.add('active');

            selectedSize = {
                id: this.dataset.id,
                name: this.dataset.name
            };

            if (selectedSizeInput) {
                selectedSizeInput.value = this.dataset.id;
            }

            checkVariant();
        });
    });

    // Color option click handler
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            if (this.classList.contains('disabled')) return;

            // Remove active class from all colors
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('active');
            });

            // Add active class to selected color
            this.classList.add('active');

            selectedColor = {
                id: this.dataset.id,
                name: this.dataset.name
            };

            if (selectedColorInput) {
                selectedColorInput.value = this.dataset.id;
            }

            checkVariant();
        });
    });

    // Function to check variant via API
    function checkVariant() {
        if (!selectedSize && !selectedColor) {
            // No variant selected
            variantInfo.style.display = 'none';
            selectedVariant = null;
            if (selectedVariantInput) selectedVariantInput.value = '';

            const hasSizes = {{ $product->sizes->count() > 0 ? 'true' : 'false' }};
            const hasColors = {{ $product->colors->count() > 0 ? 'true' : 'false' }};

            if (hasSizes || hasColors) {
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Sélectionnez une variante';
                addToCartBtn.classList.remove('btn-primary');
                addToCartBtn.classList.add('btn-secondary');
            }
            return;
        }

        // Show loading state
        variantInfo.style.display = 'block';
        variantDetails.textContent = 'Vérification...';
        variantQuantity.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        variantPrice.textContent = '';
        variantSKU.textContent = '';

        // Prepare data
        const data = {
            size_id: selectedSize ? selectedSize.id : null,
            color_id: selectedColor ? selectedColor.id : null
        };

        // API call
        fetch('/api/product/{{ $product->id }}/variant-check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.variant) {
                selectedVariant = data.variant;
                if (selectedVariantInput) {
                    selectedVariantInput.value = data.variant.id || '';
                }

                // Update display
                let sizeText = selectedSize ? `Taille: ${selectedSize.name}` : '';
                let colorText = selectedColor ? `Couleur: ${selectedColor.name}` : '';
                variantDetails.textContent = [sizeText, colorText].filter(Boolean).join(' | ');

                // Stock status
                const inStock = data.variant.quantity > 0;
                const stockClass = inStock ? 'success' : 'danger';
                const stockText = inStock ? `${data.variant.quantity} disponible(s)` : 'Rupture de stock';
                variantQuantity.innerHTML = `<span class="badge bg-${stockClass}">${stockText}</span>`;

                // Price
                if (data.variant.sale_price && data.variant.sale_price < data.variant.price) {
                    variantPrice.innerHTML = `
                        <span class="text-muted text-decoration-line-through me-2">
                            {{ $settings->currency_symbol ?? '€' }}${data.variant.price}
                        </span>
                        <span class="text-danger fw-bold">
                            {{ $settings->currency_symbol ?? '€' }}${data.variant.sale_price}
                        </span>
                    `;
                } else {
                    variantPrice.innerHTML = `
                        <span class="fw-bold">{{ $settings->currency_symbol ?? '€' }}${data.variant.price}</span>
                    `;
                }

                // SKU
                variantSKU.textContent = `SKU: ${data.variant.sku || 'N/A'}`;

                // Update add to cart button
                if (inStock) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = 'Ajouter au Panier';
                    addToCartBtn.classList.remove('btn-secondary');
                    addToCartBtn.classList.add('btn-primary');

                    if (quantityInput) {
                        quantityInput.max = data.variant.quantity;
                        if (parseInt(quantityInput.value) > data.variant.quantity) {
                            quantityInput.value = data.variant.quantity;
                        }
                    }
                } else {
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = 'Rupture de stock';
                    addToCartBtn.classList.remove('btn-primary');
                    addToCartBtn.classList.add('btn-secondary');
                }
            } else {
                // No variant found
                variantDetails.textContent = 'Combinaison non disponible';
                variantQuantity.innerHTML = '<span class="badge bg-danger">Non disponible</span>';
                variantPrice.textContent = '';
                variantSKU.textContent = '';
                selectedVariant = null;
                if (selectedVariantInput) selectedVariantInput.value = '';

                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Combinaison non disponible';
                addToCartBtn.classList.remove('btn-primary');
                addToCartBtn.classList.add('btn-secondary');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            variantDetails.textContent = 'Erreur de vérification';
            variantQuantity.innerHTML = '<span class="badge bg-warning">Réessayez</span>';
        });
    }

    // Auto-select first available color if no sizes
    const hasSizes = {{ $product->sizes->count() > 0 ? 'true' : 'false' }};
    const hasColors = {{ $product->colors->count() > 0 ? 'true' : 'false' }};

    if (hasColors && !hasSizes) {
        const firstColor = document.querySelector('.color-option:not(.disabled)');
        if (firstColor) {
            setTimeout(() => firstColor.click(), 500);
        }
    }
}

// Quantity control
function initQuantityControls() {
    const quantityControl = document.querySelector('.product-single__addtocart .qty-control');
    if (!quantityControl) return;

    const reduceBtn = quantityControl.querySelector('.qty-control__reduce');
    const increaseBtn = quantityControl.querySelector('.qty-control__increase');
    const numberInput = quantityControl.querySelector('.qty-control__number');

    if (!reduceBtn || !increaseBtn || !numberInput) return;

    // Remove existing listeners
    const newReduceBtn = reduceBtn.cloneNode(true);
    const newIncreaseBtn = increaseBtn.cloneNode(true);

    reduceBtn.parentNode.replaceChild(newReduceBtn, reduceBtn);
    increaseBtn.parentNode.replaceChild(newIncreaseBtn, increaseBtn);

    newReduceBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const currentValue = parseInt(numberInput.value) || 1;
        if (currentValue > 1) {
            numberInput.value = currentValue - 1;
            numberInput.dispatchEvent(new Event('change'));
        }
    });

    newIncreaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const currentValue = parseInt(numberInput.value) || 1;
        let maxQuantity = 999;

        if (selectedVariant && selectedVariant.quantity) {
            maxQuantity = selectedVariant.quantity;
        } else {
            maxQuantity = productStock;
        }

        if (currentValue < maxQuantity) {
            numberInput.value = currentValue + 1;
            numberInput.dispatchEvent(new Event('change'));
        } else {
            showToast(`Quantité maximale: ${maxQuantity}`, 'warning');
        }
    });

    numberInput.addEventListener('change', function() {
        const value = parseInt(this.value) || 1;
        const min = parseInt(this.min) || 1;
        let max = parseInt(this.max) || 999;

        if (selectedVariant && selectedVariant.quantity) {
            max = selectedVariant.quantity;
            this.max = max;
        } else {
            max = productStock;
            this.max = max;
        }

        if (value > max) {
            this.value = max;
            showToast(`Quantité réduite à ${max}`, 'warning');
        } else if (value < min) {
            this.value = min;
        }
    });
}

// Form validation
function initFormValidation() {
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (!addToCartForm) return;

    addToCartForm.addEventListener('submit', function(e) {
        const hasSizes = {{ $product->sizes->count() > 0 ? 'true' : 'false' }};
        const hasColors = {{ $product->colors->count() > 0 ? 'true' : 'false' }};

        if ((hasSizes || hasColors) && !selectedVariant) {
            e.preventDefault();
            let message = 'Veuillez sélectionner ';
            if (hasSizes && hasColors) {
                message += 'une taille et une couleur';
            } else if (hasSizes) {
                message += 'une taille';
            } else if (hasColors) {
                message += 'une couleur';
            }
            showToast(message, 'error');
            return false;
        }

        if (selectedVariant && selectedVariant.quantity < 1) {
            e.preventDefault();
            showToast('Cette variante est en rupture de stock', 'error');
            return false;
        }

        const quantity = parseInt(document.getElementById('quantity-input')?.value) || 1;
        if (selectedVariant && quantity > selectedVariant.quantity) {
            e.preventDefault();
            showToast(`Stock maximum: ${selectedVariant.quantity}`, 'error');
            return false;
        }
    });
}

// Initialize everything
document.addEventListener('DOMContentLoaded', function() {
    initVariantSelection();
    setTimeout(initQuantityControls, 200);
    initFormValidation();
});
// ==============================================
// QUANTITY CONTROL - ISOLATED AND FIXED
// ==============================================
function initQuantityControls() {
    const quantityControl = document.querySelector('.product-single__addtocart .qty-control');

    if (!quantityControl) return;

    // Check if already initialized
    if (quantityControl.dataset.initialized === 'true') return;

    const reduceBtn = quantityControl.querySelector('.qty-control__reduce');
    const increaseBtn = quantityControl.querySelector('.qty-control__increase');
    const numberInput = quantityControl.querySelector('.qty-control__number');

    if (!reduceBtn || !increaseBtn || !numberInput) return;

    // Mark as initialized
    quantityControl.dataset.initialized = 'true';

    // Remove and recreate buttons to avoid duplicate event listeners
    const newReduceBtn = reduceBtn.cloneNode(true);
    const newIncreaseBtn = increaseBtn.cloneNode(true);

    // Replace old buttons
    reduceBtn.parentNode.replaceChild(newReduceBtn, reduceBtn);
    increaseBtn.parentNode.replaceChild(newIncreaseBtn, increaseBtn);

    // Add fresh event listeners
    newReduceBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const currentValue = parseInt(numberInput.value) || 1;
        if (currentValue > 1) {
            numberInput.value = currentValue - 1;
            // Trigger change event
            numberInput.dispatchEvent(new Event('change'));
        }
    });

    // In your details.blade.php JavaScript, update the increase button handler:
newIncreaseBtn.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    const currentValue = parseInt(numberInput.value) || 1;
    let maxQuantity = 999;

    if (selectedVariant && selectedVariant.quantity) {
        maxQuantity = selectedVariant.quantity;
    } else if (window.productStock) {
        // Use global stock variable if set
        maxQuantity = window.productStock;
    }

    if (currentValue < maxQuantity) {
        numberInput.value = currentValue + 1;
        // Trigger change event
        numberInput.dispatchEvent(new Event('change'));
    } else {
        showToast(`Quantité maximale: ${maxQuantity} (stock disponible)`, 'warning');
    }
});

// Also update the input change handler:
numberInput.addEventListener('change', function() {
    const value = parseInt(this.value) || 1;
    const min = parseInt(this.min) || 1;
    let max = parseInt(this.max) || 999;

    if (selectedVariant && selectedVariant.quantity) {
        max = selectedVariant.quantity;
        this.max = max;
    } else if (window.productStock) {
        max = window.productStock;
        this.max = max;
    }

    if (value > max) {
        this.value = max;
        showToast(`Quantité réduite à ${max} (stock maximum)`, 'warning');
    } else if (value < min) {
        this.value = min;
    }
});
    numberInput.addEventListener('input', function() {
        const value = parseInt(this.value) || 1;
        if (value < 1) {
            this.value = 1;
        }
    });
}


// ==============================================
// MAIN INITIALIZATION
// ==============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing product details page...');

    // Initialize rating if returning from validation error
    const oldRating = {{ old('rating', 0) }};
    if (oldRating > 0) {
        setRating(oldRating);
    } else {
        // Check if there's already a rating error
        const ratingError = @json($errors->has('rating'));
        if (ratingError) {
            const starContainer = document.getElementById('star-rating-container');
            if (starContainer) {
                starContainer.classList.add('error');
            }
            const ratingErrorElement = document.getElementById('rating-error');
            if (ratingErrorElement) {
                ratingErrorElement.style.display = 'block';
            }
        }
    }

    // Initialize variant selection
    initVariantSelection();

    // Initialize quantity controls with delay
    setTimeout(initQuantityControls, 200);

    // Handle tab changes
    const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function() {
            // Re-initialize quantity controls when switching to product tab
            if (this.getAttribute('href') === '#tab-description') {
                setTimeout(initQuantityControls, 100);
            }
        });
    });

    // Close share dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const shareButton = document.querySelector('.to-share');
        const shareOptions = document.getElementById('share-options');

        if (shareOptions && shareButton &&
            !shareButton.contains(event.target) &&
            !shareOptions.contains(event.target)) {
            shareOptions.style.display = 'none';
        }
    });

    // Handle review form Enter key
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                submitReviewForm();
            }
        });
    }

    // Initialize image gallery if needed
    function initImageGallery() {
        const mainImages = document.querySelectorAll('.product-single__image-item img');
        const thumbnails = document.querySelectorAll('.product-single__thumbnail img');

        if (mainImages.length > 0 && thumbnails.length > 0) {
            thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    // Mettre à jour l'image principale
                    if (mainImages[index]) {
                        const mainImageContainer = document.querySelector('.product-single__image');
                        if (mainImageContainer) {
                            const newImage = mainImages[index].cloneNode(true);
                            const oldImage = mainImageContainer.querySelector('img');
                            if (oldImage) {
                                oldImage.replaceWith(newImage);
                            }
                        }
                    }

                    // Mettre à jour la vignette active
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }
    }

    initImageGallery();
});

// Function for manual testing
function testColorSelectionManual() {
    const colorOptions = document.querySelectorAll('.color-option:not(.disabled)');
    if (colorOptions.length > 0) {
        console.log('Testing manual color click...');
        colorOptions[0].click();
    } else {
        console.log('No available color options found');
    }
}
</script>
@endsection
