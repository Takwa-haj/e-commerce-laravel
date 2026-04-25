@extends('layouts.app')
@section('content')
<style>
    .tracking-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ===== ACCOUNT CARD STYLES ===== */
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

    .account-card-header h4, .account-card-header h5 {
        margin: 0;
        color: var(--text_primary);
        font-weight: 600;
    }

    .account-card-body {
        padding: 1.5rem;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-item.active {
        color: var(--text_secondary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin: 1.5rem 0;
    }

    .stat-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 1.75rem 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text_secondary);
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .text-muted {
        color: var(--text_secondary) !important;
        font-size: 0.75rem;
    }

    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin: 0;
    }

    .tracking-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .tracking-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px var(--card_shadow);
        border-color: color-mix(in srgb, var(--primary) 20%, transparent);
    }

    .tracking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border_soft);
    }

    .order-number {
        font-weight: 600;
        color: var(--text_primary);
        font-size: 1.125rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        gap: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-ordered {
        background: color-mix(in srgb, var(--error) 15%, transparent);
        color: var(--error);
    }

    .status-confirmed {
        background: color-mix(in srgb, var(--info) 15%, transparent);
        color: var(--info);
    }

    .status-delivered {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .status-shipped {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .tracking-info { margin: 1.25rem 0; }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin: 0.625rem 0;
        font-size: 0.875rem;
    }

    .info-label {
        color: var(--text_secondary);
        font-weight: 500;
    }

    .info-value {
        font-weight: 500;
        color: var(--text_primary);
    }

    .font-monospace { font-family: monospace; }

    .tracking-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-track {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-track:hover {
        filter: brightness(1.05);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
        color: white;
    }

    .btn-print {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--surface);
        color: var(--text_secondary);
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-print:hover {
        background: var(--bg_soft);
        color: var(--primary);
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border_soft);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--bg_soft);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--text_secondary);
    }

    .empty-state h4 {
        color: var(--text_primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text_secondary);
        margin-bottom: 1.5rem;
    }

    .pagination { gap: 0.25rem; margin-bottom: 0; }

    .page-link {
        border: 1px solid var(--border);
        color: var(--text_secondary);
        border-radius: 8px;
        margin: 0 2px;
        padding: 0.5rem 0.75rem;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-color: var(--primary);
        color: white;
    }

    .page-link:hover {
        background: var(--bg_soft);
        border-color: var(--primary);
        color: var(--primary);
    }

    .help-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--divider);
    }

    .help-card {
        background: var(--bg_soft);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid var(--border_soft);
        height: 100%;
    }

    .help-card:hover {
        background: var(--surface);
        border-color: color-mix(in srgb, var(--primary) 20%, transparent);
        transform: translateY(-2px);
    }

    .help-card i { color: var(--primary); font-size: 1.1rem; }
    .help-card strong { color: var(--text_primary); }
    .help-card p { color: var(--text_secondary); margin-bottom: 0; font-size: 0.813rem; }

    .page-header-custom { margin-bottom: 1.5rem; }
    .page-header-custom h1 { font-size: 1.75rem; font-weight: 700; color: var(--text_primary); margin-bottom: 0.5rem; }
    .page-header-custom p { color: var(--text_secondary); margin-bottom: 0; }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; gap: 1rem; }
        .orders-grid { grid-template-columns: 1fr; }
        .tracking-card { padding: 1.25rem; }
        .tracking-actions { flex-direction: column; }
        .btn-print { width: 100%; }
        .tracking-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    }

    @media (max-width: 480px) {
        .stat-value { font-size: 2rem; }
    }
</style>

<main class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Suivi de commande</li>
                    </ol>
                </nav>
                <div class="page-header-custom">
                    <h1>Suivi de commande</h1>
                    <p>Suivez vos commandes en temps réel, consultez les informations de livraison et téléchargez vos documents.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Commandées</div>
                        <div class="stat-value">{{ $stats['ordered'] }}</div>
                        <div class="text-muted">En attente</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Confirmées</div>
                        <div class="stat-value">{{ $stats['confirmed'] }}</div>
                        <div class="text-muted">En préparation</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Livrées</div>
                        <div class="stat-value">{{ $stats['delivered'] }}</div>
                        <div class="text-muted">Terminées</div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list me-2" style="color: #667eea;"></i>
                            <h5 class="mb-0">Mes commandes à suivre</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        @if($orders->count() > 0)
                            <div class="orders-grid">
                                @foreach($orders as $order)
                                <div class="tracking-card">
                                    <div class="tracking-header">
                                        <div class="order-number">Commande #{{ $order->id }}</div>
                                        <span class="status-badge status-{{ $order->status }}">
                                            @if($order->status == 'delivered')
                                                <i class="fas fa-check-circle"></i> Livrée
                                            @elseif($order->status == 'confirmed')
                                                <i class="fas fa-check"></i> Confirmée
                                            @elseif($order->status == 'shipped')
                                                <i class="fas fa-shipping-fast"></i> Expédiée
                                            @else
                                                <i class="fas fa-clock"></i> Commandée
                                            @endif
                                        </span>
                                    </div>

                                    <div class="tracking-info">
                                        <div class="info-row">
                                            <span class="info-label">Date</span>
                                            <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Montant</span>
                                            <span class="info-value">{{ number_format($order->total, 2) }}€</span>
                                        </div>
                                        @if($order->tracking_number)
                                        <div class="info-row">
                                            <span class="info-label">N° de suivi</span>
                                            <span class="info-value font-monospace">{{ $order->tracking_number }}</span>
                                        </div>
                                        @endif
                                        @if($order->estimated_delivery)
                                        <div class="info-row">
                                            <span class="info-label">Livraison estimée</span>
                                            <span class="info-value">{{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="tracking-actions">
                                        <a href="{{ route('tracking.show', ['order_id' => $order->id]) }}" class="btn-track">
                                            <i class="fas fa-eye"></i>
                                            Voir le suivi
                                        </a>
                                        <a href="{{ route('tracking.print', ['order_id' => $order->id]) }}"
                                           target="_blank"
                                           class="btn-print"
                                           title="Imprimer">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($orders->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                            @endif

                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <h4>Aucune commande à suivre</h4>
                                <p>Vous n'avez pas de commandes en cours de traitement ou livraison.</p>
                                <a href="{{ route('user.orders') }}" class="btn-track" style="display: inline-flex; width: auto; padding: 0.75rem 2rem;">
                                    <i class="fas fa-shopping-bag me-2"></i>Voir toutes mes commandes
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <h5 class="mb-4" style="color: #1F2937; font-weight: 600;">
                        <i class="fas fa-question-circle me-2" style="color: #667eea;"></i>Aide et assistance
                    </h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    <strong>Service client</strong>
                                </div>
                                <p class="small">Du lundi au vendredi, 9h-18h</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    <strong>Email</strong>
                                </div>
                                <p class="small">{{ $settings->site_email ?? 'contact@votreboutique.com' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Délais de livraison</strong>
                                </div>
                                <p class="small">2-5 jours ouvrés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
