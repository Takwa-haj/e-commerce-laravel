@extends('layouts.app')
@section('content')

<style>
    main.pt-90 {
        padding-top: 90px;
        background: var(--bg_soft);
    }

    /* Hero Section */
    .wishlist-hero {
        background: var(--surface);
        padding: 80px 0;
        margin-bottom: 40px;
        text-align: center;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 4px 20px var(--card_shadow);
    }

    .wishlist-heart-icon {
        font-size: 80px;
        margin-bottom: 20px;
        display: inline-block;
        animation: pulse 2s infinite;
        color: var(--primary);
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .wishlist-hero h1 {
        color: var(--text_primary);
        font-weight: 600;
    }

    .wishlist-hero p {
        color: var(--text_secondary);
        font-size: 1.1rem;
    }

    /* Wishlist Grid */
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .wishlist-card {
        background: var(--surface);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 15px var(--card_shadow);
        transition: all 0.3s ease;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px var(--card_shadow);
    }

    .wishlist-card-image-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: var(--bg_soft);
    }

    .wishlist-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .wishlist-card:hover .wishlist-card-image {
        transform: scale(1.05);
    }

    .wishlist-card-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--surface);
        color: var(--text_primary);
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 2px 8px var(--card_shadow);
    }

    .wishlist-card-content { padding: 20px; }

    .wishlist-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text_primary);
        line-height: 1.4;
    }

    .wishlist-card-description {
        font-size: 13px;
        color: var(--text_secondary);
        line-height: 1.5;
    }

    .wishlist-card-price {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--divider);
    }

    .price-amount {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
    }

    .price-original {
        font-size: 14px;
        color: var(--text_secondary);
        text-decoration: line-through;
        margin-left: 8px;
    }

    .wishlist-card-actions { display: flex; gap: 10px; }

    .btn-action {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-to-cart {
        background: var(--primary);
        color: white;
    }

    .btn-add-to-cart:hover {
        filter: brightness(0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 30%, transparent);
    }

    .btn-remove {
        background: var(--bg_soft);
        color: var(--error);
        border: 1px solid var(--border_soft);
    }

    .btn-remove:hover {
        background: color-mix(in srgb, var(--error) 10%, transparent);
        border-color: var(--error);
    }

    /* Stats */
    .wishlist-stats {
        background: var(--surface);
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 15px var(--card_shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stats-item { text-align: center; flex: 1; padding: 10px; }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
    }

    .stats-label {
        font-size: 14px;
        color: var(--text_secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Empty Wishlist */
    .empty-wishlist {
        text-align: center;
        padding: 80px 20px;
        background: var(--surface);
        border-radius: 8px;
        box-shadow: 0 2px 15px var(--card_shadow);
    }

    .empty-wishlist-icon {
        font-size: 100px;
        color: var(--primary);
        opacity: 0.8;
    }

    .empty-wishlist-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--text_primary);
    }

    .empty-wishlist-subtitle {
        font-size: 16px;
        color: var(--text_secondary);
        max-width: 500px;
        margin: 0 auto 30px;
        line-height: 1.6;
    }

    /* How-to Cards */
    .how-to-use {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }

    .how-to-card {
        background: var(--surface);
        padding: 25px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 15px var(--card_shadow);
        transition: all 0.3s ease;
    }

    .how-to-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px var(--card_shadow);
    }

    .how-to-icon {
        font-size: 36px;
        color: var(--primary);
    }

    .how-to-card h5 { color: var(--text_primary); font-weight: 600; }
    .how-to-card p { color: var(--text_secondary); font-size: 14px; }

    /* Actions */
    .wishlist-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid var(--divider);
    }

    .btn-clear-wishlist {
        background: var(--bg_soft);
        color: var(--error);
        border: 1px solid var(--border_soft);
        padding: 14px 28px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-clear-wishlist:hover {
        background: color-mix(in srgb, var(--error) 10%, transparent);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px var(--card_shadow);
    }

    .btn-shop-more {
        background: var(--primary);
        color: white;
        padding: 14px 28px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-shop-more:hover {
        filter: brightness(0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px color-mix(in srgb, var(--primary) 30%, transparent);
        color: white;
    }

    /* Notification */
    .wishlist-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--surface);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 8px 30px var(--card_shadow);
        display: none;
        align-items: center;
        gap: 15px;
        z-index: 1000;
        border-left: 4px solid var(--primary);
    }

    .wishlist-count-badge {
        position: fixed;
        top: 100px;
        right: 30px;
        background: var(--surface);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px var(--card_shadow);
        border: 2px solid var(--primary);
        z-index: 999;
        font-weight: 700;
        color: var(--primary);
    }

    @media (max-width: 992px) {
        .wishlist-grid { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); }
        .wishlist-hero { padding: 60px 20px; }
        .wishlist-heart-icon { font-size: 60px; }
        .wishlist-stats { flex-direction: column; gap: 20px; }
    }

    @media (max-width: 768px) {
        .wishlist-hero h1 { font-size: 2rem; }
        .wishlist-card-image-wrapper { height: 180px; }
        .how-to-use { grid-template-columns: 1fr; }
        .wishlist-actions { flex-direction: column; }
    }

    @media (max-width: 576px) {
        .wishlist-grid { grid-template-columns: 1fr; }
        .empty-wishlist-icon { font-size: 70px; }
    }
</style>

<!-- Notification Toast -->
<div id="wishlistNotification" class="wishlist-notification">
    <div class="notification-icon">
        <i class="bi bi-check-circle-fill"></i>
    </div>
    <div class="notification-content">
        <h5>Ajouté au panier</h5>
        <p>L'article a été déplacé avec succès.</p>
    </div>
    <button class="notification-close" onclick="closeNotification()">
        <i class="bi bi-x"></i>
    </button>
</div>

@if(Cart::instance('wishlist')->content()->count() > 0)
<div class="wishlist-count-badge" id="wishlistCountBadge">
    {{ Cart::instance('wishlist')->content()->count() }}
</div>
@endif

<main class="pt-90" style="padding-top: 90px;">
    <div class="wishlist-hero">
        <div class="container">
            <div class="text-center">
                <div class="wishlist-heart-icon">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h1 class="display-6 fw-semibold mb-3">Ma Liste de Souhaits</h1>
                <p class="lead">
                    Conservez vos articles préférés pour plus tard
                </p>
            </div>
        </div>
    </div>

    <section class="container">
        @if(Cart::instance('wishlist')->content()->count() > 0)
        <!-- Statistiques -->
        <div class="wishlist-stats">
            <div class="stats-item">
                <div class="stats-number">{{ Cart::instance('wishlist')->content()->count() }}</div>
                <div class="stats-label">Articles</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ Cart::instance('wishlist')->subtotal() }}€</div>
                <div class="stats-label">Valeur totale</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">
                    {{ Cart::instance('wishlist')->content()->count() > 0 ? round(Cart::instance('wishlist')->subtotal() / Cart::instance('wishlist')->content()->count(), 2) : 0 }}€
                </div>
                <div class="stats-label">Prix moyen</div>
            </div>
        </div>

        <!-- Grille des articles -->
        <div class="wishlist-grid">
            @foreach($items as $item)
            <div class="wishlist-card">
                <div class="wishlist-card-image-wrapper">
                    <img loading="lazy"
                         src="{{ asset('uploads/products/thumbnails') }}/{{ $item->model->image }}"
                         alt="{{ $item->name }}"
                         class="wishlist-card-image"
                         onerror="this.src='https://placehold.co/400x300/f8f9fa/e9ecef?text=Produit'">

                    <div class="wishlist-card-badge">
                        <i class="bi bi-check-circle"></i>
                        Disponible
                    </div>
                </div>

                <div class="wishlist-card-content">
                    <h3 class="wishlist-card-title">
                        {{ Str::limit($item->name, 50) }}
                    </h3>

                    @if($item->model->short_description)
                    <p class="wishlist-card-description">
                        {{ Str::limit($item->model->short_description, 100) }}
                    </p>
                    @endif

                    <div class="wishlist-card-price">
                        <div>
                            <span class="price-amount">{{ $item->price }} €</span>
                            @if($item->model->compare_price)
                            <span class="price-original">{{ $item->model->compare_price }} €</span>
                            @endif
                        </div>
                        <div style="color: #999; font-size: 13px;">
                            <i class="bi bi-eye"></i>
                            {{ $item->model->views ?? 0 }}
                        </div>
                    </div>

                    <div class="wishlist-card-actions">
                        <form method="POST" action="{{ route('wishlist.move.to.cart',['rowId'=>$item->rowId]) }}">
                            @csrf
                            <button type="submit" class="btn-action btn-add-to-cart" data-product-name="{{ $item->name }}">
                                <i class="bi bi-cart-plus"></i>
                                Ajouter
                            </button>
                        </form>

                        <form action="{{ route('wishlist.item.remove',['rowId'=>$item->rowId])}}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-remove">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="wishlist-actions">
            <form method="POST" action="{{ route('wishlist.item.clear') }}">
                @csrf @method('DELETE')
                <button class="btn-clear-wishlist" type="submit">
                    <i class="bi bi-trash"></i>
                    Vider la liste
                </button>
            </form>

            <a href="{{ route('shop.index') }}" class="btn-shop-more">
                <i class="bi bi-bag-plus"></i>
                Voir plus d'articles
            </a>
        </div>

        @else
        <!-- État vide -->
        <div class="empty-wishlist">
            <div class="empty-wishlist-icon">
                <i class="bi bi-heart"></i>
            </div>
            <h2 class="empty-wishlist-title">Votre liste est vide</h2>
            <p class="empty-wishlist-subtitle">
                Ajoutez des articles en cliquant sur l'icône cœur des produits.
            </p>

            <a href="{{ route('shop.index') }}" class="btn-shop-more" style="max-width: 200px; margin: 0 auto; display: inline-flex; justify-content: center;">
                <i class="bi bi-bag me-2"></i>
                Explorer
            </a>

            <div class="how-to-use">
                <div class="how-to-card">
                    <div class="how-to-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="mb-2">Parcourir</h5>
                    <p>
                        Découvrez nos produits
                    </p>
                </div>

                <div class="how-to-card">
                    <div class="how-to-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h5 class="mb-2">Sauvegarder</h5>
                    <p>
                        Cliquez sur le cœur
                    </p>
                </div>

                <div class="how-to-card">
                    <div class="how-to-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h5 class="mb-2">Acheter</h5>
                    <p>
                        Ajoutez au panier
                    </p>
                </div>
            </div>
        </div>
        @endif
    </section>
</main>

@endsection
