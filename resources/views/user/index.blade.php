
@extends('layouts.app')
@section('content')
<main class="py-5" style="background: var(--bg-main);">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" style="color: var(--text-secondary);">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--text-main);">Tableau de bord</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3" style="color: var(--text-main);">Mon Compte</h1>
                <p class="text-muted" style="color: var(--text-secondary);">Bienvenue sur votre espace personnel</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fs-4 me-2" style="color: var(--primary);"></i>
                            <h5 class="mb-0" style="color: var(--text-main);">Tableau de bord</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        <div class="welcome-message">
                            <p class="mb-0" style="color: var(--text-main);">
                                Bonjour <strong style="color: var(--primary);">{{ $user->name }}</strong>,
                                <br class="d-sm-none">
                                <span style="color: var(--text-secondary);">vous êtes connecté à votre espace personnel.</span>
                            </p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="dashboard-stat-card">
                                    <div class="stat-icon" style="background: rgba(232, 183, 177, 0.1);">
                                        <i class="fas fa-shopping-bag" style="color: var(--primary);"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h6 class="stat-value" style="color: var(--text-main);">{{ $recentOrders->count() }}</h6>
                                        <p class="stat-label" style="color: var(--text-secondary);">Commandes récentes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="dashboard-stat-card">
                                    <div class="stat-icon" style="background: rgba(216, 195, 165, 0.1);">
                                        <i class="fas fa-heart" style="color: var(--secondary);"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h6 class="stat-value" style="color: var(--text-main);">{{ Auth::user()->wishlist->count() }}</h6>
                                        <p class="stat-label" style="color: var(--text-secondary);">Favoris</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="account-card mt-4">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fs-4 me-2" style="color: var(--primary);"></i>
                            <h5 class="mb-0" style="color: var(--text-main);">Commandes récentes</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        @if($recentOrders->count() > 0)
                            <div class="orders-list">
                                @foreach($recentOrders as $order)
                                    <a href="{{ route('user.order.details', $order->id) }}" class="order-item">
                                        <div class="order-info">
                                            <div>
                                                <h6 class="mb-1" style="color: var(--text-main);">Commande #{{ $order->id }}</h6>
                                                <small style="color: var(--text-secondary);">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $order->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="order-status status-{{ $order->status }}">
                                                    {{ $order->status }}
                                                </span>
                                                <div class="mt-1">
                                                    <strong style="color: var(--text-main);">{{ number_format($order->total, 2) }}€</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('user.orders') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-right me-2"></i>Voir toutes mes commandes
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-box-open fa-3x" style="color: var(--text-muted);"></i>
                                </div>
                                <h6 class="mb-2" style="color: var(--text-main);">Aucune commande</h6>
                                <p class="text-muted mb-3" style="color: var(--text-secondary);">Vous n'avez pas encore passé de commande.</p>
                                <a href="{{ url('/shop') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Commencer mes achats
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Account Details Summary -->
                <div class="account-card mt-4">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user fs-4 me-2" style="color: var(--primary);"></i>
                            <h5 class="mb-0" style="color: var(--text-main);">Informations personnelles</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label" style="color: var(--text-secondary);">Nom complet</span>
                                    <span class="info-value" style="color: var(--text-main);">{{ $user->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label" style="color: var(--text-secondary);">Email</span>
                                    <span class="info-value" style="color: var(--text-main);">{{ $user->email }}</span>
                                    @if($user->hasVerifiedEmail())
                                        <span class="verified-badge ms-2" style="color: var(--success-bg);">
                                            <i class="fas fa-check-circle"></i> Vérifié
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label" style="color: var(--text-secondary);">Téléphone</span>
                                    <span class="info-value" style="color: var(--text-main);">{{ $user->phone ?? $user->numero ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label" style="color: var(--text-secondary);">Membre depuis</span>
                                    <span class="info-value" style="color: var(--text-main);">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.account.details') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Modifier mes informations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .account-card {
        background: var(--surface);
        border-radius: 16px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 24px;
    }

    .account-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .account-card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--bg_soft), var(--surface));
        border-bottom: 1px solid var(--border_soft);
    }

    .account-card-header h5 { margin: 0; color: var(--text_primary); font-weight: 600; }
    .account-card-body { padding: 1.5rem; }
    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; }
    .breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 500; }

    .welcome-message {
        padding: 1.25rem;
        background: color-mix(in srgb, var(--primary) 5%, transparent);
        border-radius: 12px;
        border-left: 4px solid var(--primary);
    }

    .welcome-message strong { color: var(--primary); }

    .dashboard-stat-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: var(--bg_soft);
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid var(--border_soft);
    }

    .dashboard-stat-card:hover {
        background: var(--surface);
        border-color: color-mix(in srgb, var(--primary) 30%, transparent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .stat-icon i { font-size: 1.25rem; }
    .stat-content { flex: 1; }
    .stat-value { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1; }
    .stat-label { margin-bottom: 0; font-size: 0.875rem; }

    .orders-list { display: flex; flex-direction: column; gap: 0.75rem; }

    .order-item {
        display: block;
        padding: 1rem;
        background: var(--bg_soft);
        border: 1px solid var(--border_soft);
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .order-item:hover {
        background: var(--surface);
        border-color: color-mix(in srgb, var(--primary) 30%, transparent);
        transform: translateX(4px);
        box-shadow: 0 2px 8px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .order-info { display: flex; justify-content: space-between; align-items: center; }

    .order-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-delivered { background: color-mix(in srgb, var(--success) 15%, transparent); color: var(--success); }
    .status-confirmed { background: color-mix(in srgb, var(--info) 15%, transparent); color: var(--info); }
    .status-canceled { background: color-mix(in srgb, var(--error) 15%, transparent); color: var(--error); }
    .status-ordered { background: color-mix(in srgb, var(--warning) 15%, transparent); color: var(--warning); }

    .info-row {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border_soft);
    }

    .info-row:last-child { border-bottom: none; }
    .info-label { width: 120px; font-size: 0.875rem; color: var(--text_secondary); }
    .info-value { font-weight: 500; color: var(--text_primary); }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--success);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--bg_soft);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
    }

    .btn-outline-primary {
        border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
        color: var(--primary);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .order-info { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .info-row { flex-direction: column; align-items: flex-start; }
        .info-label { width: 100%; margin-bottom: 0.25rem; }
    }
</style>
@endsection
