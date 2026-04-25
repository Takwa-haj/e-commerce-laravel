


<div class="account-nav-wrapper">
    <!-- User Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar-container">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}"
                         class="profile-avatar"
                         alt="{{ Auth::user()->name }}">
                @else
                    <div class="avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                @if(Auth::user()->hasVerifiedEmail())
                    <span class="verified-badge">
                        <i class="fas fa-check-circle"></i>
                    </span>
                @endif
            </div>
            <div class="profile-info">
                <h4 class="profile-greeting">Bienvenue,</h4>
                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                <p class="profile-email">{{ Auth::user()->email }}</p>
                <a href="{{ route('user.account.details') }}" class="profile-edit-link">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
            </div>
        </div>
    </div>

    <!-- Account Navigation -->
    <div class="navigation-card">
        <div class="nav-section">
            <h4 class="nav-section-title">Mon Compte</h4>
            <a href="{{ route('user.index') }}"
               class="nav-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('user.orders') }}"
               class="nav-item {{ request()->routeIs('user.orders') || request()->routeIs('user.order.details') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <span>Mes commandes</span>
                @php
                    $pendingOrders = Auth::user()->orders()->whereIn('status', ['ordered', 'confirmed'])->count();
                @endphp
                @if($pendingOrders > 0)
                    <span class="nav-badge">{{ $pendingOrders }}</span>
                @endif
            </a>

            <a href="{{ route('tracking.index') }}"
               class="nav-item {{ request()->routeIs('user.tracking.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <span>Suivi de commande</span>
                @php
                    $inTransitOrders = Auth::user()->orders()
                        ->where('status', 'confirmed')
                        ->whereNotNull('tracking_number')
                        ->whereNull('delivered_date')
                        ->count();
                @endphp
                @if($inTransitOrders > 0)
                    <span class="nav-badge">{{ $inTransitOrders }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <h4 class="nav-section-title">Mes Préférences</h4>

            {{-- FIXED: Updated route name --}}
            <a href="{{ route('user.addresses.index') }}"
               class="nav-item {{ request()->routeIs('user.addresses.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span>Mes adresses</span>
            </a>

            <a href="{{ route('user.account.details') }}"
               class="nav-item {{ request()->routeIs('user.account.details') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span>Détails du compte</span>
                @if(!Auth::user()->hasVerifiedEmail())
                    <span class="nav-badge warning">!</span>
                @endif
            </a>

            <a href="{{ route('wishlist.index') }}"
               class="nav-item {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <span>Ma liste d'envies</span>
                @php
                    $wishlistCount = Auth::user()->wishlist->count();
                @endphp
                @if($wishlistCount > 0)
                    <span class="nav-badge">{{ $wishlistCount }}</span>
                @endif
            </a>

            <a href="{{ route('user.reviews') }}"
               class="nav-item {{ request()->routeIs('user.reviews') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-star"></i>
                </div>
                <span>Mes avis</span>
                @php
                    $reviewCount = Auth::user()->reviews->count();
                @endphp
                @if($reviewCount > 0)
                    <span class="nav-badge">{{ $reviewCount }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <h4 class="nav-section-title">Service Client</h4>
            <a href="{{ route('user.refunds') }}"
               class="nav-item {{ request()->routeIs('user.refunds') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <span>Mes retours</span>
            </a>
        </div>

        <div class="nav-section">
            <h4 class="nav-section-title">Communication</h4>
            <a href="{{ route('user.messages') }}"
               class="nav-item {{ request()->routeIs('user.messages') || request()->routeIs('user.message.details') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <span>Mes messages</span>
                @php
                    $unreadMessagesCount = Auth::user()->contacts()->where('read', false)->count();
                @endphp
                @if($unreadMessagesCount > 0)
                    <span class="nav-badge">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="nav-item logout">
                @csrf
                <button type="submit" class="logout-button">
                    <div class="nav-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Wishlist Preview -->
    @if(Auth::user()->wishlist->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-heart"></i>
                <h5>Ma liste d'envies</h5>
            </div>
            <a href="{{ route('wishlist.index') }}" class="view-all">
                Voir tout ({{ Auth::user()->wishlist->count() }})
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="wishlist-preview">
            @foreach(Auth::user()->wishlist->take(3) as $item)
                <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}"
                   class="wishlist-item">
                    @if($item->product && $item->product->image)
                        <div class="wishlist-image-container">
                            <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                 alt="{{ $item->product->name ?? 'Produit' }}"
                                 class="wishlist-thumbnail">
                            <div class="wishlist-overlay">
                                <span class="view-product-btn">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="wishlist-info">
                            <p class="wishlist-product-name">{{ Str::limit($item->product->name ?? 'Produit', 25) }}</p>
                            <p class="wishlist-product-price">{{ number_format($item->product->price ?? 0, 2) }} €</p>
                        </div>
                    @else
                        <div class="wishlist-placeholder">
                            <i class="fas fa-gem"></i>
                        </div>
                    @endif
                </a>
            @endforeach
            @if(Auth::user()->wishlist->count() > 3)
                <a href="{{ route('wishlist.index') }}" class="wishlist-item view-more">
                    <div class="view-more-content">
                        <span>+{{ Auth::user()->wishlist->count() - 3 }}</span>
                        <small>Voir plus</small>
                    </div>
                </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Recent Orders -->
    @php
        $recentOrders = Auth::user()->orders()->latest()->take(3)->get();
    @endphp

    @if($recentOrders->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-box-open"></i>
                <h5>Commandes récentes</h5>
            </div>
            <a href="{{ route('user.orders') }}" class="view-all">
                Voir tout
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="recent-orders">
            @foreach($recentOrders as $order)
            <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}" class="recent-order-item">
                <div class="order-info">
                    <div class="order-header">
                        <span class="order-number">Commande #{{ $order->order_number ?? $order->id }}</span>
                        <span class="order-amount">{{ number_format($order->total, 2) }} €</span>
                    </div>
                    <div class="order-details">
                        <span class="order-date">{{ $order->created_at->format('d/m/Y') }}</span>
                        <span class="order-items">{{ $order->items_count ?? $order->orderItems->count() }} article(s)</span>
                    </div>
                </div>
                <div class="order-status">
                    @if ($order->status == 'delivered')
                        <span class="status-badge delivered">
                            <i class="fas fa-check-circle"></i>
                            Livré
                        </span>
                    @elseif ($order->status == 'confirmed')
                        <span class="status-badge confirmed">
                            <i class="fas fa-check"></i>
                            Confirmé
                        </span>
                    @elseif ($order->status == 'shipped')
                        <span class="status-badge shipped">
                            <i class="fas fa-shipping-fast"></i>
                            Expédié
                        </span>
                    @elseif ($order->status == 'canceled')
                        <span class="status-badge canceled">
                            <i class="fas fa-times-circle"></i>
                            Annulé
                        </span>
                    @else
                        <span class="status-badge ordered">
                            <i class="fas fa-clock"></i>
                            Commandé
                        </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Mobile Hamburger Menu -->
    <div class="mobile-menu-container">
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <span class="hamburger-icon">
                <i class="fas fa-user-circle"></i>
            </span>
            <span class="close-icon">
                <i class="fas fa-times"></i>
            </span>
        </button>

        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

        <div class="mobile-menu-content" id="mobileMenuContent">
            <div class="mobile-menu-header">
                <div class="mobile-profile">
                    <div class="mobile-avatar-container">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}"
                                 class="mobile-avatar"
                                 alt="{{ Auth::user()->name }}">
                        @else
                            <div class="mobile-avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="mobile-profile-info">
                        <h3 class="mobile-profile-name">{{ Auth::user()->name }}</h3>
                        <p class="mobile-profile-email">{{ Auth::user()->email }}</p>
                        <a href="{{ route('user.account.details') }}" class="mobile-edit-profile">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>

            <div class="mobile-menu-items">
                <a href="{{ route('user.index') }}"
                   class="mobile-menu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                    <i class="fas fa-gem"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('user.orders') }}"
                   class="mobile-menu-item {{ request()->routeIs('user.orders') || request()->routeIs('user.order.details') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Mes commandes</span>
                    @if($pendingOrders > 0)
                        <span class="mobile-menu-badge">{{ $pendingOrders }}</span>
                    @endif
                </a>
                <a href="{{ route('tracking.index') }}"
                    class="mobile-menu-item {{ request()->routeIs('user.tracking.*') ? 'active' : '' }}">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Suivi de commande</span>
                    @if($inTransitOrders > 0)
                        <span class="mobile-menu-badge">{{ $inTransitOrders }}</span>
                    @endif
                </a>

                {{-- FIXED: Updated route name for mobile menu --}}
                <a href="{{ route('user.addresses.index') }}"
                   class="mobile-menu-item {{ request()->routeIs('user.addresses.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Mes adresses</span>
                </a>

                <a href="{{ route('wishlist.index') }}"
                   class="mobile-menu-item {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                    <i class="fas fa-heart"></i>
                    <span>Mes favoris</span>
                    @if($wishlistCount > 0)
                        <span class="mobile-menu-badge">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <div class="mobile-menu-divider"></div>

                <a href="{{ route('shop.index') }}" class="mobile-menu-item">
                    <i class="fas fa-store"></i>
                    <span>Boutique</span>
                </a>

                {{-- FIXED: Updated route name for mobile menu --}}
                <a href="{{ route('user.messages') }}"
                   class="mobile-menu-item {{ request()->routeIs('user.messages') || request()->routeIs('user.message.details') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Mes messages</span>
                    @if($unreadMessagesCount > 0)
                        <span class="mobile-menu-badge">{{ $unreadMessagesCount }}</span>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile" class="mobile-menu-item logout">
                    @csrf
                    <button type="submit" class="mobile-logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .account-nav-wrapper {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text_main);
        background: transparent;
        position: relative;
        padding-bottom: 80px;
    }

    .profile-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary), transparent);
        opacity: 0.05;
        border-radius: 0 16px 0 0;
    }

    .profile-header {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .profile-avatar-container {
        position: relative;
        margin-right: 20px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--surface);
        box-shadow: 0 4px 12px var(--card_shadow);
        transition: all 0.3s ease;
        background: var(--surface);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 18px var(--card_shadow);
    }

    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 28px;
        border: 2px solid var(--surface);
        box-shadow: 0 4px 12px var(--card_shadow);
    }

    .verified-badge {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid var(--surface);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-info {
        flex: 1;
    }

    .profile-greeting {
        font-size: 13px;
        color: var(--text_secondary);
        margin-bottom: 6px;
        font-weight: 500;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .profile-name {
        font-size: 22px;
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 6px;
    }

    .profile-email {
        font-size: 14px;
        color: var(--text_secondary);
        font-weight: 400;
        margin-bottom: 14px;
    }

    .profile-edit-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        transition: all 0.3s ease;
    }

    .profile-edit-link:hover {
        background: color-mix(in srgb, var(--primary) 18%, transparent);
        color: var(--primary);
    }

    .navigation-card {
        background: var(--surface);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        margin-bottom: 24px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .navigation-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .nav-section {
        padding: 16px 0;
    }

    .nav-section:not(:last-child) {
        border-bottom: 1px solid var(--border_soft);
    }

    .nav-section-title {
        font-size: 11px;
        font-weight: 600;
        color: var(--text_secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 20px 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border_soft);
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: var(--text_secondary);
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
        background: transparent;
    }

    .nav-item:hover {
        background: var(--bg_soft);
        padding-left: 22px;
        color: var(--text_primary);
    }

    .nav-item.active {
        background: var(--bg_soft);
        color: var(--text_primary);
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 24px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 0 2px 2px 0;
    }

    .nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        transition: all 0.3s ease;
    }

    .nav-item:hover .nav-icon {
        background: color-mix(in srgb, var(--primary) 18%, transparent);
        transform: translateY(-1px);
    }

    .nav-item.active .nav-icon {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .nav-icon i {
        font-size: 14px;
        color: var(--primary);
    }

    .nav-item.active .nav-icon i {
        color: white;
    }

    .nav-item span {
        font-size: 14px;
        font-weight: 500;
        flex: 1;
    }

    .nav-badge {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-size: 11px;
        font-weight: 600;
        min-width: 20px;
        height: 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        box-shadow: 0 2px 4px color-mix(in srgb, var(--primary) 30%, transparent);
    }

    .nav-badge.warning {
        background: linear-gradient(135deg, var(--error), var(--error));
    }

    .logout-button {
        background: none;
        border: none;
        color: inherit;
        font: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0;
        text-align: left;
        transition: all 0.3s ease;
    }

    .logout:hover {
        background: color-mix(in srgb, var(--error) 8%, transparent);
        color: var(--error);
    }

    .logout:hover .nav-icon {
        background: color-mix(in srgb, var(--error) 12%, transparent);
    }

    .logout:hover .nav-icon i {
        color: var(--error);
    }

    .section-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary);
        font-size: 16px;
    }

    .section-title h5 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text_primary);
        margin: 0;
    }

    .view-all {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        transition: all 0.3s ease;
    }

    .view-all:hover {
        background: color-mix(in srgb, var(--primary) 18%, transparent);
        color: var(--primary);
        gap: 8px;
    }

    .view-all i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .view-all:hover i {
        transform: translateX(3px);
    }

    .wishlist-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .wishlist-item {
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s ease;
    }

    .wishlist-item:hover {
        transform: translateY(-4px);
    }

    .wishlist-image-container {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        background: var(--bg_soft);
        margin-bottom: 10px;
        border: 1px solid var(--border_soft);
    }

    .wishlist-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .wishlist-item:hover .wishlist-thumbnail {
        transform: scale(1.08);
    }

    .wishlist-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, var(--hover_overlay), transparent);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .wishlist-item:hover .wishlist-overlay {
        opacity: 1;
    }

    .view-product-btn {
        width: 30px;
        height: 30px;
        background: var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text_primary);
        font-size: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px var(--card_shadow);
    }

    .wishlist-item:hover .view-product-btn {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    .wishlist-info {
        text-align: center;
    }

    .wishlist-product-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--text_secondary);
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .wishlist-product-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary);
    }

    .wishlist-placeholder {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg_soft);
        color: var(--text_secondary);
        font-size: 24px;
        border: 1px dashed var(--border);
    }

    .wishlist-item.view-more {
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg_soft);
        border: 1px dashed var(--border);
    }

    .view-more-content {
        text-align: center;
    }

    .view-more-content span {
        display: block;
        font-size: 20px;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .view-more-content small {
        font-size: 11px;
        color: var(--text_secondary);
        font-weight: 500;
    }

    .recent-orders {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .recent-order-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 14px;
        background: var(--bg_soft);
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        border: 1px solid var(--border_soft);
        flex-wrap: wrap;
        gap: 8px;
    }

    .recent-order-item:hover {
        background: var(--surface);
        border-color: var(--border);
        transform: translateX(2px);
        box-shadow: 0 2px 8px var(--card_shadow);
    }

    .order-info {
        flex: 1;
        min-width: 0;
    }

    .order-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
        gap: 8px;
    }

    .order-number {
        font-size: 13px;
        font-weight: 600;
        color: var(--text_primary);
    }

    .order-amount {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        white-space: nowrap;
    }

    .order-details {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 11px;
        color: var(--text_secondary);
    }

    .order-date::before { content: '📅'; font-size: 9px; }
    .order-items::before { content: '📦'; font-size: 9px; }

    .order-status {
        flex-shrink: 0;
        margin-left: auto;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .status-badge i { font-size: 9px; }

    .status-badge.delivered {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .status-badge.confirmed {
        background: color-mix(in srgb, var(--info) 15%, transparent);
        color: var(--info);
    }

    .status-badge.shipped {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .status-badge.ordered {
        background: color-mix(in srgb, var(--error) 15%, transparent);
        color: var(--error);
    }

    .status-badge.canceled {
        background: var(--bg_soft);
        color: var(--text_secondary);
    }

    /* Mobile Menu */
    .mobile-menu-container {
        display: none;
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1000;
    }

    .mobile-menu-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px color-mix(in srgb, var(--primary) 40%, transparent);
        transition: all 0.3s ease;
        position: relative;
        z-index: 1002;
    }

    .mobile-menu-toggle:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 25px color-mix(in srgb, var(--primary) 50%, transparent);
    }

    .close-icon { display: none; }

    .mobile-menu-overlay {
        position: fixed;
        inset: 0;
        background: var(--hover_overlay);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu-content {
        position: fixed;
        top: 0;
        right: -100%;
        width: 300px;
        height: 100%;
        background: var(--surface);
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1001;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 24px var(--card_shadow);
    }

    .mobile-menu-content.active { right: 0; }

    .mobile-menu-header {
        padding: 24px;
        background: linear-gradient(135deg, var(--bg_soft), var(--surface));
        border-bottom: 1px solid var(--border_soft);
    }

    .mobile-profile {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .mobile-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
        background: var(--surface);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 20%, transparent);
    }

    .mobile-avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        border: 2px solid var(--surface);
    }

    .mobile-profile-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 4px;
    }

    .mobile-profile-email {
        font-size: 13px;
        color: var(--text_secondary);
        margin-bottom: 8px;
    }

    .mobile-edit-profile {
        font-size: 12px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .mobile-menu-item {
        display: flex;
        align-items: center;
        padding: 14px 24px;
        color: var(--text_secondary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .mobile-menu-item:hover {
        background: var(--bg_soft);
        padding-left: 26px;
    }

    .mobile-menu-item.active {
        background: var(--bg_soft);
        color: var(--text_primary);
        border-left: 3px solid var(--primary);
    }

    .mobile-menu-item i {
        width: 20px;
        font-size: 14px;
        color: var(--primary);
        margin-right: 16px;
    }

    .mobile-menu-badge {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-size: 10px;
        font-weight: 600;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        margin-left: 8px;
    }

    .mobile-menu-divider {
        height: 1px;
        background: var(--divider);
        margin: 12px 24px;
    }

    .mobile-menu-item.logout:hover {
        background: color-mix(in srgb, var(--error) 8%, transparent);
        color: var(--error);
    }

    .mobile-menu-item.logout:hover i {
        color: var(--error);
    }

    @media (max-width: 768px) {
        .mobile-menu-container { display: block; }
        .navigation-card { display: none; }
    }

    @media (min-width: 769px) {
        .mobile-menu-container { display: none; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced logout confirmation
        const logoutForms = document.querySelectorAll('#logout-form, #logout-form-mobile');
        logoutForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                    this.submit();
                }
            });
        });

        // Enhanced card hover effects
        const cards = document.querySelectorAll('.section-card, .profile-card');
        cards.forEach(card => {
            const originalShadow = card.style.boxShadow;

            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 30px rgba(140, 98, 57, 0.12)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = originalShadow;
            });
        });

        // Mobile Menu with enhanced animations
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuContent = document.getElementById('mobileMenuContent');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                mobileMenuOverlay.classList.toggle('active');
                mobileMenuContent.classList.toggle('active');

                if (this.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                    this.style.transform = 'rotate(180deg)';
                } else {
                    document.body.style.overflow = '';
                    this.style.transform = '';
                }
            });

            mobileMenuOverlay.addEventListener('click', function() {
                this.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                mobileMenuContent.classList.remove('active');
                document.body.style.overflow = '';
                mobileMenuToggle.style.transform = '';
            });

            // Close menu on menu item click
            const menuItems = document.querySelectorAll('.mobile-menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!this.classList.contains('logout')) {
                        mobileMenuOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        mobileMenuContent.classList.remove('active');
                        document.body.style.overflow = '';
                        mobileMenuToggle.style.transform = '';
                    }
                });
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenuContent.classList.contains('active')) {
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    mobileMenuContent.classList.remove('active');
                    document.body.style.overflow = '';
                    mobileMenuToggle.style.transform = '';
                }
            });
        }

        // Add animation to wishlist items
        const wishlistItems = document.querySelectorAll('.wishlist-item');
        wishlistItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.style.animation = 'fadeInUp 0.5s ease forwards';
            item.style.opacity = '0';
        });

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
