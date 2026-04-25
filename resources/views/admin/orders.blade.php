@extends('layouts.admin')
@section('content')

<style>
    /* Elegant Jewelry Admin Theme */
    :root {
        --gold-primary: #d4af37;
        --gold-light: #f8f0d5;
        --gold-dark: #b8860b;
        --gold-subtle: rgba(212, 175, 55, 0.08);

        --emerald: #047857;
        --ruby: #be123c;
        --sapphire: #1d4ed8;
        --amber: #f59e0b;

        --bg-light: #fefefe;
        --bg-card: #ffffff;
        --border-light: #e5e7eb;
        --border-gold: rgba(212, 175, 55, 0.2);

        --text-dark: #1f2937;
        --text-medium: #4b5563;
        --text-light: #6b7280;

        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Clean Table */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        background: var(--bg-card);
    }

    .table-modern thead th {
        background: var(--bg-card);
        color: var(--text-dark);
        font-weight: 600;
        padding: 16px 20px;
        border-bottom: 2px solid var(--border-gold);
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: var(--gold-subtle);
    }

    .table-modern tbody td {
        padding: 16px 20px;
        color: var(--text-medium);
        font-size: 14px;
        border-bottom: 1px solid var(--border-light);
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* Batch Selection */
    .batch-checkbox {
        width: 18px;
        height: 18px;
        border: 1px solid var(--border-light);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: none;
    }

    .batch-checkbox:checked {
        background: var(--gold-primary);
        border-color: var(--gold-primary);
    }

    .batch-checkbox:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
    }

    .batch-mode .batch-checkbox {
        display: block;
    }

    .batch-mode .table-modern tbody tr.selected {
        background-color: var(--gold-subtle);
        border-left: 3px solid var(--gold-primary);
    }

    /* Batch Actions */
    .batch-actions {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 16px 20px;
        margin: 20px 0;
        display: none;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: var(--shadow-md);
    }

    .batch-actions.show {
        display: flex;
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .batch-info {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: var(--text-medium);
    }

    .batch-count {
        font-weight: 600;
        color: var(--gold-primary);
        font-size: 18px;
    }

    .batch-controls {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .batch-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
        background: white;
    }

    .batch-btn-primary {
        background: var(--gold-primary);
        color: white;
        border-color: var(--gold-primary);
    }

    .batch-btn-primary:hover {
        background: var(--gold-dark);
        border-color: var(--gold-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .batch-btn-outline {
        background: white;
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .batch-btn-outline:hover {
        background: var(--bg-light);
        border-color: var(--border-gold);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .batch-btn-danger {
        background: white;
        color: var(--ruby);
        border: 1px solid rgba(190, 18, 60, 0.2);
    }

    .batch-btn-danger:hover {
        background: rgba(190, 18, 60, 0.05);
        border-color: var(--ruby);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
    }

    .status-delivered {
        background: rgba(4, 120, 87, 0.1);
        color: var(--emerald);
        border-color: rgba(4, 120, 87, 0.2);
    }

    .status-confirmed {
        background: rgba(29, 78, 216, 0.1);
        color: var(--sapphire);
        border-color: rgba(29, 78, 216, 0.2);
    }

    .status-canceled {
        background: rgba(190, 18, 60, 0.1);
        color: var(--ruby);
        border-color: rgba(190, 18, 60, 0.2);
    }

    .status-ordered {
        background: rgba(212, 175, 55, 0.1);
        color: var(--gold-primary);
        border-color: rgba(212, 175, 55, 0.2);
    }

    /* Stats Cards */
    .stats-card {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--border-gold);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 20px;
        color: var(--gold-primary);
        background: var(--gold-subtle);
        border: 1px solid var(--border-gold);
    }

    .stats-value {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .stats-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* Header */
    .page-header {
        background: var(--bg-card);
        color: var(--text-dark);
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
    }

    .page-header h3 {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 24px;
    }

    .page-header .header-subtitle {
        color: var(--text-light);
        font-size: 14px;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-tab {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--border-light);
        background: var(--bg-card);
        color: var(--text-medium);
    }

    .filter-tab:hover {
        border-color: var(--gold-primary);
        background: var(--gold-subtle);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .filter-tab.active {
        background: var(--gold-primary);
        color: white;
        border-color: var(--gold-primary);
        box-shadow: var(--shadow-md);
    }

    .filter-tab .badge {
        background: rgba(255, 255, 255, 0.2);
        color: inherit;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Search Container */
    .search-container {
        background: var(--bg-card);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }

    .search-box {
        position: relative;
        max-width: 500px;
    }

    .search-box input {
        padding: 10px 44px 10px 16px;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
        width: 100%;
        background: var(--bg-card);
        color: var(--text-dark);
    }

    .search-box input:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    .search-box button {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 44px;
        border: none;
        background: transparent;
        color: var(--text-medium);
        font-size: 16px;
        cursor: pointer;
        border-radius: 0 6px 6px 0;
        transition: all 0.2s ease;
    }

    .search-box button:hover {
        color: var(--gold-primary);
    }

    /* Action Buttons */
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: var(--text-medium);
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .action-btn:hover {
        border-color: var(--gold-primary);
        color: var(--gold-primary);
        background: var(--gold-subtle);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Typography */
    .order-id {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 15px;
    }

    .order-id::before {
        content: '#';
        color: var(--gold-primary);
        margin-right: 2px;
    }

    .customer-name {
        font-weight: 500;
        color: var(--text-dark);
    }

    .customer-email {
        font-size: 12px;
        color: var(--text-light);
    }

    .order-amount {
        font-weight: 600;
        color: var(--emerald);
        font-size: 15px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 48px;
        color: var(--border-light);
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 18px;
        color: var(--text-medium);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .empty-state-text {
        color: var(--text-light);
        max-width: 400px;
        margin: 0 auto 20px;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Pagination */
    .pagination-modern .page-link {
        border: 1px solid var(--border-light);
        color: var(--text-medium);
        font-weight: 500;
        border-radius: 6px;
        margin: 0 2px;
        transition: all 0.2s ease;
        background: var(--bg-card);
        padding: 8px 12px;
        font-size: 14px;
    }

    .pagination-modern .page-link:hover {
        border-color: var(--gold-primary);
        color: var(--gold-primary);
        background: var(--gold-subtle);
    }

    .pagination-modern .page-item.active .page-link {
        background: var(--gold-primary);
        color: white;
        border-color: var(--gold-primary);
    }

    .pagination-modern .page-item.disabled .page-link {
        color: #9ca3af;
        background: var(--bg-light);
        border-color: var(--border-light);
    }

    /* Modal */
    .batch-modal .modal-content {
        border-radius: 12px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-lg);
    }

    .batch-modal .modal-header {
        background: var(--bg-card);
        color: var(--text-dark);
        border-radius: 12px 12px 0 0;
        border-bottom: 1px solid var(--border-light);
        padding: 20px 24px;
    }

    .batch-modal .modal-body {
        padding: 24px;
    }

    .batch-option {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: var(--bg-card);
    }

    .batch-option:hover {
        border-color: var(--gold-primary);
        background: var(--gold-subtle);
        transform: translateY(-1px);
    }

    .batch-option.selected {
        border-color: var(--gold-primary);
        background: var(--gold-subtle);
    }

    .batch-option-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        margin-bottom: 12px;
        background: var(--gold-primary);
    }

    .batch-option-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
        font-size: 14px;
    }

    .batch-option-desc {
        font-size: 12px;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* Floating Button */
    .print-float-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--gold-primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        transition: all 0.2s ease;
    }

    .print-float-btn:hover {
        background: var(--gold-dark);
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    /* Print Styles */
    @media print {
        .print-float-btn,
        .batch-actions,
        .filter-tabs,
        .search-container,
        .action-btn,
        .stats-card,
        .page-header,
        .batch-checkbox {
            display: none !important;
        }

        .table-modern {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-modern thead th,
        .table-modern tbody td {
            padding: 12px 16px;
            font-size: 13px;
        }

        .stats-value {
            font-size: 20px;
        }

        .filter-tab {
            padding: 6px 12px;
            font-size: 12px;
        }

        .print-float-btn {
            bottom: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            font-size: 18px;
        }

        .batch-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .batch-info {
            justify-content: center;
        }

        .batch-controls {
            justify-content: center;
        }
    }
</style>

@php
    $settings = \App\Models\Setting::getSettings();
    $currencySymbol = $settings->currency_symbol ?? '€';

    // Stats calculations
    $totalOrders = $orders->total();
    $totalAmount = $orders->sum('total');
    $pendingOrders = $orders->where('status', 'ordered')->count();
    $deliveredOrders = $orders->where('status', 'delivered')->count();

    // Status counts
    $confirmedOrders = $orders->where('status', 'confirmed')->count();
    $canceledOrders = $orders->where('status', 'canceled')->count();

    // Get current filter
    $currentStatus = request('status');
@endphp

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Modern Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h3>Gestion des Commandes</h3>
                    <div class="header-subtitle">
                        <i class="fas fa-chart-line me-1"></i>
                        Suivi et gestion de toutes vos commandes
                    </div>
                </div>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Tableau de bord</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right opacity-50"></i>
                    </li>
                    <li>
                        <div class="text-tiny fw-bold">Commandes</div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stats-card stats-card-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="stats-icon stats-icon-primary me-3">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="stats-label">Total Commandes</div>
                                <div class="stats-value">{{ $totalOrders }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card stats-card-success">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="stats-icon stats-icon-success me-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <div class="stats-label">Montant Total</div>
                                <div class="stats-value">{{ number_format($totalAmount, 0, ',', ' ') }} {{ $currencySymbol }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card stats-card-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="stats-icon stats-icon-warning me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="stats-label">En Attente</div>
                                <div class="stats-value">{{ $pendingOrders }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card stats-card-info">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="stats-icon stats-icon-info me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="stats-label">Livrées</div>
                                <div class="stats-value">{{ $deliveredOrders }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('admin.orders') }}"
               class="filter-tab filter-tab-all {{ !$currentStatus ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                Toutes
                <span class="badge">{{ $totalOrders }}</span>
            </a>

            <a href="{{ route('admin.orders', ['status' => 'ordered']) }}"
               class="filter-tab filter-tab-ordered {{ $currentStatus == 'ordered' ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                Commandées
                <span class="badge">{{ $pendingOrders }}</span>
            </a>

            <a href="{{ route('admin.orders', ['status' => 'confirmed']) }}"
               class="filter-tab filter-tab-confirmed {{ $currentStatus == 'confirmed' ? 'active' : '' }}">
                <i class="fas fa-check"></i>
                Confirmées
                <span class="badge">{{ $confirmedOrders }}</span>
            </a>

            <a href="{{ route('admin.orders', ['status' => 'delivered']) }}"
               class="filter-tab filter-tab-delivered {{ $currentStatus == 'delivered' ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                Livrées
                <span class="badge">{{ $deliveredOrders }}</span>
            </a>

            <a href="{{ route('admin.orders', ['status' => 'canceled']) }}"
               class="filter-tab filter-tab-canceled {{ $currentStatus == 'canceled' ? 'active' : '' }}">
                <i class="fas fa-times"></i>
                Annulées
                <span class="badge">{{ $canceledOrders }}</span>
            </a>
        </div>

        <!-- Search and Actions -->
        <div class="search-container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('admin.orders') }}" method="GET" class="search-box">
                        <input type="text"
                               name="query"
                               value="{{ request()->input('query') ?? '' }}"
                               placeholder="Rechercher par ID, nom, email, téléphone..."
                               class="form-control">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="batch-btn batch-btn-outline" onclick="toggleBatchMode()">
                            <i class="fas fa-check-square me-1"></i>
                            Sélection multiple
                        </button>
                        <a href="{{ route('admin.orders') }}" class="batch-btn batch-btn-outline">
                            <i class="fas fa-redo me-1"></i>
                            Actualiser
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(request()->has('query'))
        <div class="alert alert-info alert-modern mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-search me-2"></i>
                    Résultats de recherche pour : <strong>"{{ request()->input('query') }}"</strong>
                    <span class="ms-2 text-muted">({{ $orders->total() }} résultat(s))</span>
                </div>
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <i class="fas fa-times me-1"></i> Effacer
                </a>
            </div>
        </div>
        @endif

        <!-- Batch Actions Panel -->
        <div class="batch-actions" id="batchActions">
            <div class="batch-info">
                <i class="fas fa-check-circle text-primary fs-5"></i>
                <span>Commandes sélectionnées :</span>
                <span class="batch-count" id="selectedCount">0</span>
                <span class="text-muted" id="selectedTotal">(0.00 {{ $currencySymbol }})</span>
            </div>
            <div class="batch-controls">
                <button type="button" class="batch-btn batch-btn-primary" onclick="openBatchPrintModal()">
                    <i class="fas fa-print me-1"></i>
                    Imprimer sélection
                </button>
                <button type="button" class="batch-btn batch-btn-outline" onclick="markAsDelivered()">
                    <i class="fas fa-check-circle me-1"></i>
                    Marquer comme livré
                </button>
                <button type="button" class="batch-btn batch-btn-danger" onclick="cancelSelectedOrders()">
                    <i class="fas fa-times-circle me-1"></i>
                    Annuler sélection
                </button>
                <button type="button" class="batch-btn batch-btn-outline" onclick="clearSelection()">
                    <i class="fas fa-times me-1"></i>
                    Tout désélectionner
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="wg-box">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">
                                <input type="checkbox" id="selectAll" class="batch-checkbox"
                                       onchange="toggleSelectAll(this)">
                            </th>
                            <th width="100">N° Commande</th>
                            <th>Client</th>
                            <th width="120" class="text-center">Montant</th>
                            <th width="120" class="text-center">Statut</th>
                            <th width="120" class="text-center">Date</th>
                            <th width="80" class="text-center">Articles</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @if($orders->count() > 0)
                            @foreach ($orders as $order)
                            <tr class="order-row" data-order-id="{{ $order->id }}" data-order-total="{{ $order->total }}">
                                <td class="text-center">
                                    <input type="checkbox" class="batch-checkbox order-checkbox"
                                           value="{{ $order->id }}"
                                           onchange="updateSelection()">
                                </td>
                                <td>
                                    <span class="order-id">{{ $order->id }}</span>
                                </td>
                                <td>
    <div class="customer-info">
        <div class="customer-name">
            {{ $order->user ? $order->user->name : $order->name }}
        </div>
        @if($order->user && $order->user->email)
            <div class="customer-email">{{ $order->user->email }}</div>
        @else
            <div class="customer-email text-muted">
                <small><i class="fas fa-user-slash me-1"></i> Commande invité</small>
            </div>
        @endif
        <div class="customer-phone">
            <small><i class="fas fa-phone"></i> {{ $order->phone }}</small>
        </div>
    </div>
</td>
                                <td class="text-center">
                                    <span class="order-amount">
                                        {{ number_format($order->total, 2, ',', ' ') }} {{ $currencySymbol }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($order->status == 'delivered')
                                        <span class="status-badge status-delivered">
                                            <i class="fas fa-check-circle"></i> Livré
                                        </span>
                                    @elseif ($order->status == 'confirmed')
                                        <span class="status-badge status-confirmed">
                                            <i class="fas fa-check"></i> Confirmé
                                        </span>
                                    @elseif ($order->status == 'canceled')
                                        <span class="status-badge status-canceled">
                                            <i class="fas fa-times-circle"></i> Annulé
                                        </span>
                                    @else
                                        <span class="status-badge status-ordered">
                                            <i class="fas fa-clock"></i> Commandé
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="order-date">
                                        <div class="order-date-day">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="order-date-time">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-1">
                                        {{ $order->orderItems->count() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.order.details',['order_id'=>$order->id]) }}"
                                           class="action-btn action-btn-view" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.order.print.invoice', ['order_id' => $order->id]) }}"
                                           target="_blank" class="action-btn action-btn-print" title="Imprimer facture">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="action-btn dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" title="Plus d'options">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.order.print.invoice', ['order_id' => $order->id]) }}"
                                                       target="_blank">
                                                        <i class="fas fa-file-invoice me-2"></i> Facture
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.order.print.shipping-label', ['order_id' => $order->id]) }}"
                                                       target="_blank">
                                                        <i class="fas fa-tag me-2"></i> Étiquette
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                @if($order->status != 'canceled')
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                       onclick="cancelSingleOrder({{ $order->id }})">
                                                        <i class="fas fa-times-circle me-2"></i> Annuler
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <h4 class="empty-state-title">Aucune commande trouvée</h4>
                                        <p class="empty-state-text">
                                            @if(request()->has('query') || request()->has('status'))
                                                Aucune commande ne correspond à vos critères de recherche.
                                            @else
                                                Aucune commande n'a été passée pour le moment.
                                            @endif
                                        </p>
                                        @if(request()->has('query') || request()->has('status'))
                                            <a href="{{ route('admin.orders') }}" class="batch-btn batch-btn-primary">
                                                <i class="fas fa-list me-2"></i> Voir toutes les commandes
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($orders->count() > 0)
            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="text-muted">
                        Affichage de <strong>{{ $orders->firstItem() }}</strong> à <strong>{{ $orders->lastItem() }}</strong>
                        sur <strong>{{ $orders->total() }}</strong> commandes
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="text-muted">
                        Montant total affiché:
                        <strong class="text-success">{{ number_format($orders->sum('total'), 2, ',', ' ') }} {{ $currencySymbol }}</strong>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-modern justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </ul>
                </nav>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

<!-- Batch Print Modal -->
<div class="modal fade batch-modal" id="batchPrintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-print me-2"></i>
                    Impression par lot
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6 class="mb-2">Sélectionnez le type d'impression :</h6>
                    <p class="text-muted">Vous avez sélectionné <span id="modalSelectedCount" class="fw-bold">0</span> commande(s)</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="batch-option batch-option-1" onclick="selectBatchOption(1)">
                            <div class="batch-option-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="batch-option-title">Factures</div>
                            <div class="batch-option-desc">Imprimer toutes les factures en PDF</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="batch-option batch-option-2" onclick="selectBatchOption(2)">
                            <div class="batch-option-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="batch-option-title">Étiquettes</div>
                            <div class="batch-option-desc">Étiquettes d'expédition (4x6)</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="batch-option batch-option-3" onclick="selectBatchOption(3)">
                            <div class="batch-option-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="batch-option-title">Résumés</div>
                            <div class="batch-option-desc">Résumés de commande</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Les documents seront ouverts dans de nouveaux onglets. Vous devrez les imprimer séparément.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="batch-btn batch-btn-outline" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button type="button" class="batch-btn batch-btn-primary" onclick="executeBatchPrint()" id="executePrintBtn" disabled>
                    <i class="fas fa-print me-1"></i> Lancer l'impression
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Print Button -->
<button onclick="window.print()" class="print-float-btn d-print-none" title="Imprimer (Ctrl+P)">
    <i class="fas fa-print"></i>
</button>

<script>
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Batch selection variables
    let batchMode = false;
    let selectedBatchOption = null;

    // Toggle batch mode
    function toggleBatchMode() {
        batchMode = !batchMode;
        const tableContainer = document.querySelector('.wg-box');
        const batchActions = document.getElementById('batchActions');

        if (batchMode) {
            tableContainer.classList.add('batch-mode');
            batchActions.classList.add('show');
            // Show checkboxes
            document.querySelectorAll('.batch-checkbox').forEach(cb => {
                cb.style.display = 'block';
            });
        } else {
            tableContainer.classList.remove('batch-mode');
            batchActions.classList.remove('show');
            // Hide checkboxes and clear selection
            document.querySelectorAll('.batch-checkbox').forEach(cb => {
                cb.style.display = 'none';
                cb.checked = false;
            });
            // Remove selected class from rows
            document.querySelectorAll('.order-row').forEach(row => {
                row.classList.remove('selected');
            });
            updateSelection();
        }
    }

    // Select/deselect all orders
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
            updateRowSelection(cb);
        });
        updateSelection();
    }

    // Update row visual selection
    function updateRowSelection(checkbox) {
        const row = checkbox.closest('.order-row');
        if (checkbox.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }
    }

    // Update selection counter and total
    function updateSelection() {
        const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        const selectedCountElement = document.getElementById('selectedCount');
        const selectedTotalElement = document.getElementById('selectedTotal');

        // Update count
        selectedCountElement.textContent = selectedCount;

        // Calculate total amount
        let totalAmount = 0;
        selectedCheckboxes.forEach(cb => {
            const row = cb.closest('.order-row');
            const orderTotal = parseFloat(row.dataset.orderTotal) || 0;
            totalAmount += orderTotal;
        });

        // Update total display
        selectedTotalElement.textContent = `(${totalAmount.toFixed(2)} ${'{{ $currencySymbol }}'})`;

        // Update row selection state
        selectedCheckboxes.forEach(cb => updateRowSelection(cb));

        // Update modal count
        document.getElementById('modalSelectedCount').textContent = selectedCount;

        // Enable/disable batch buttons based on selection
        const batchButtons = document.querySelectorAll('.batch-btn:not(.batch-btn-outline)');
        if (selectedCount > 0) {
            batchButtons.forEach(btn => btn.disabled = false);
        } else {
            batchButtons.forEach(btn => btn.disabled = true);
        }
    }

    // Clear all selections
    function clearSelection() {
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.checked = false;
            updateRowSelection(cb);
        });
        document.getElementById('selectAll').checked = false;
        updateSelection();
    }

    // Open batch print modal
    function openBatchPrintModal() {
        const selectedCount = document.querySelectorAll('.order-checkbox:checked').length;
        if (selectedCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Aucune sélection',
                text: 'Veuillez sélectionner au moins une commande.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        // Reset batch option
        selectedBatchOption = null;
        document.querySelectorAll('.batch-option').forEach(opt => opt.classList.remove('selected'));
        document.getElementById('executePrintBtn').disabled = true;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('batchPrintModal'));
        modal.show();
    }

    // Select batch option in modal
    function selectBatchOption(option) {
        selectedBatchOption = option;
        document.querySelectorAll('.batch-option').forEach(opt => opt.classList.remove('selected'));
        event.target.closest('.batch-option').classList.add('selected');
        document.getElementById('executePrintBtn').disabled = false;
    }

    // Execute batch print
    function executeBatchPrint() {
        if (!selectedBatchOption) {
            Swal.fire({
                icon: 'error',
                title: 'Option non sélectionnée',
                text: 'Veuillez sélectionner un type d\'impression.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        const orderIds = Array.from(selectedCheckboxes).map(cb => cb.value);

        // Determine print type
        let printType = '';
        let printName = '';

        switch (selectedBatchOption) {
            case 1:
                printType = 'invoice';
                printName = 'factures';
                break;
            case 2:
                printType = 'shipping-label';
                printName = 'étiquettes';
                break;
            case 3:
                printType = 'summary';
                printName = 'résumés';
                break;
        }

        // Open print windows
        orderIds.forEach(orderId => {
            let url = '';
            switch (printType) {
                case 'invoice':
                    url = `/admin/order/${orderId}/print/invoice`;
                    break;
                case 'shipping-label':
                    url = `/admin/order/${orderId}/print/shipping-label`;
                    break;
                case 'summary':
                    url = `/admin/order/${orderId}/print/summary`;
                    break;
            }
            window.open(url, '_blank');
        });

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('batchPrintModal'));
        modal.hide();

        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Impression lancée',
            html: `
                <div class="text-center">
                    <i class="fas fa-print fa-3x text-primary mb-3"></i>
                    <p class="mb-2">L'impression des ${printName} a été lancée.</p>
                    <p class="text-muted small mb-0">${orderIds.length} document(s) vont s'ouvrir dans de nouveaux onglets.</p>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Compris',
            confirmButtonColor: '#4361ee'
        });
    }

    // Helper function to create batch update form with CSRF protection
    function createBatchUpdateForm(action, orderIds, status) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;
        form.style.display = 'none';

        // Add CSRF token
        const csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value = csrfToken;
        form.appendChild(csrfTokenInput);

        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        // Add order IDs as array
        orderIds.forEach(orderId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_ids[]';
            input.value = orderId;
            form.appendChild(input);
        });

        // Add status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'order_status';
        statusInput.value = status;
        form.appendChild(statusInput);

        // Add batch flag
        const batchInput = document.createElement('input');
        batchInput.type = 'hidden';
        batchInput.name = 'batch_update';
        batchInput.value = 'true';
        form.appendChild(batchInput);

        document.body.appendChild(form);
        return form;
    }

    // Mark selected orders as delivered
    function markAsDelivered() {
        const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Aucune sélection',
                text: 'Veuillez sélectionner au moins une commande.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        Swal.fire({
            title: 'Marquer comme livré',
            text: `Êtes-vous sûr de vouloir marquer ${selectedCheckboxes.length} commande(s) comme livrée(s) ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Oui, marquer comme livré',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const orderIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                const form = createBatchUpdateForm('{{ route("admin.order.status.update") }}', orderIds, 'delivered');
                form.submit();
            }
        });
    }

    // Cancel selected orders
    function cancelSelectedOrders() {
        const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Aucune sélection',
                text: 'Veuillez sélectionner au moins une commande.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        Swal.fire({
            title: 'Annuler les commandes',
            text: `Êtes-vous sûr de vouloir annuler ${selectedCheckboxes.length} commande(s) ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const orderIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                const form = createBatchUpdateForm('{{ route("admin.order.status.update") }}', orderIds, 'canceled');
                form.submit();
            }
        });
    }

    // Cancel single order
    function cancelSingleOrder(orderId) {
        Swal.fire({
            title: 'Annuler la commande',
            text: 'Êtes-vous sûr de vouloir annuler cette commande ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.order.status.update") }}';
                form.style.display = 'none';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add method override
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                // Add order ID
                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'order_id';
                orderIdInput.value = orderId;
                form.appendChild(orderIdInput);

                // Add status
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'order_status';
                statusInput.value = 'canceled';
                form.appendChild(statusInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize checkboxes (initially hidden)
        document.querySelectorAll('.batch-checkbox').forEach(cb => {
            cb.style.display = 'none';
        });

        // Add event listeners to checkboxes
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelection);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + A to select all in batch mode
            if (batchMode && (e.ctrlKey || e.metaKey) && e.key === 'a') {
                e.preventDefault();
                document.getElementById('selectAll').click();
            }
            // Esc to exit batch mode
            if (batchMode && e.key === 'Escape') {
                toggleBatchMode();
            }
            // Ctrl/Cmd + P to print page
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Ctrl/Cmd + Shift + M to toggle batch mode
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'M') {
                e.preventDefault();
                toggleBatchMode();
            }
        });

        // Add click handlers to batch options in modal
        document.querySelectorAll('.batch-option').forEach(option => {
            option.addEventListener('click', function(e) {
                if (!e.target.classList.contains('batch-option')) {
                    this.click();
                }
            });
        });
    });
</script>

@endsection
