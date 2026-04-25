@extends('layouts.app')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mes commandes</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3">Mes Commandes</h1>
                <p class="text-muted">Historique et suivi de vos commandes</p>
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
                            <i class="fas fa-shopping-bag fs-4 me-2 text-primary"></i>
                            <h5 class="mb-0">Historique des commandes</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        @if($orders->count() > 0)
                            <div class="orders-table-responsive">
                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>N° Commande</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Articles</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td class="fw-medium">#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td class="fw-bold">{{ number_format($order->total, 2) }}€</td>
                                            <td>
                                                <span class="order-status status-{{ $order->status }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td>{{ $order->orderItems->count() }}</td>
                                            <td>
                                                <a href="{{ route('user.order.details', $order->id) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="ms-1 d-none d-md-inline">Détails</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($orders->hasPages())
                            <div class="mt-4">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                </div>
                                <h6 class="mb-2">Aucune commande</h6>
                                <p class="text-muted mb-3">Vous n'avez pas encore passé de commande.</p>
                                <a href="{{ route('shop.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Découvrir nos produits
                                </a>
                            </div>
                        @endif
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

    .account-card-header h5 {
        margin: 0;
        color: var(--text_primary);
        font-weight: 600;
    }

    .account-card-body { padding: 1.5rem; }

    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; }

    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .orders-table-responsive { overflow-x: auto; }

    .orders-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .orders-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text_secondary);
        border-bottom: 2px solid var(--border_soft);
        font-size: 0.875rem;
    }

    .orders-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border_soft);
        color: var(--text_primary);
        vertical-align: middle;
    }

    .orders-table tbody tr { transition: background-color 0.2s ease; }

    .orders-table tbody tr:hover { background-color: var(--bg_soft); }

    .order-status {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-delivered {
        background: color-mix(in srgb, var(--success) 15%, transparent);
        color: var(--success);
    }

    .status-confirmed {
        background: color-mix(in srgb, var(--info) 15%, transparent);
        color: var(--info);
    }

    .status-canceled {
        background: color-mix(in srgb, var(--error) 15%, transparent);
        color: var(--error);
    }

    .status-ordered {
        background: color-mix(in srgb, var(--warning) 15%, transparent);
        color: var(--warning);
    }

    .status-shipped {
        background: color-mix(in srgb, var(--info) 15%, transparent);
        color: var(--info);
    }

    .btn-outline-primary {
        border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
        color: var(--primary);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
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

    .pagination { margin-bottom: 0; }

    @media (max-width: 768px) {
        .orders-table th, .orders-table td { padding: 0.75rem; }
        .order-status { white-space: nowrap; }
    }
</style>
@endsection
