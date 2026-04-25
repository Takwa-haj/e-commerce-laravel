@extends('layouts.admin')
@section('content')
@php
    $settings = \App\Models\Setting::getSettings();
    $currencySymbol = $settings->currency_symbol ?? '€';
    $taxRate = $settings->tax_rate ?? 20;
@endphp
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
        --shadow-gold: 0 4px 20px rgba(212, 175, 55, 0.15);
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

    /* Minimal Cards */
    .wg-box {
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .wg-box .flex:first-child {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-card);
    }

    .wg-box h5 {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 18px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Clean Timeline */
    .status-timeline {
        padding: 24px;
        position: relative;
    }

    .timeline-track {
        position: absolute;
        left: 36px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-gold);
    }

    .timeline-step {
        position: relative;
        margin-bottom: 20px;
        padding-left: 60px;
        min-height: 60px;
    }

    .timeline-step .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 2px solid var(--border-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-primary);
        font-size: 14px;
        z-index: 2;
    }

    .timeline-step.completed .timeline-icon {
        background: var(--gold-primary);
        border-color: var(--gold-primary);
        color: white;
    }

    .timeline-step.current .timeline-icon {
        background: white;
        border-color: var(--gold-primary);
        color: var(--gold-primary);
        box-shadow: 0 0 0 4px var(--gold-subtle);
    }

    .timeline-date {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 4px;
        font-weight: 500;
    }

    .timeline-text {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 14px;
    }

    /* Product Cards */
    .product-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        background: var(--bg-card);
        border-radius: 8px;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .product-card:hover {
        border-color: var(--gold-primary);
        box-shadow: var(--shadow-gold);
    }

    .product-image-container {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        overflow: hidden;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-light);
        flex-shrink: 0;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        flex: 1;
        min-width: 0;
    }

    .product-name {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 4px;
        font-size: 14px;
        line-height: 1.4;
    }

    .product-meta {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: var(--text-light);
    }

    .product-price {
        font-weight: 600;
        color: var(--emerald);
        font-size: 14px;
        white-space: nowrap;
    }

    /* Clean Buttons */
    .btn-modern {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        background: white;
    }

    .btn-modern-primary {
        background: var(--gold-primary);
        color: white;
        border-color: var(--gold-primary);
    }

    .btn-modern-primary:hover {
        background: var(--gold-dark);
        border-color: var(--gold-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-modern-secondary {
        background: white;
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .btn-modern-secondary:hover {
        background: var(--bg-light);
        border-color: var(--border-gold);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Info Cards */
    .info-card {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        height: 100%;
        position: relative;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, var(--gold-primary), var(--gold-light));
        border-radius: 12px 12px 0 0;
    }

    .info-card .card-icon {
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

    .info-card .card-title {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .info-card .card-value {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.2;
    }

    /* Form Elements */
    .form-select-modern,
    .form-control-modern {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: var(--bg-card);
        color: var(--text-dark);
        width: 100%;
    }

    .form-select-modern:focus,
    .form-control-modern:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    /* Enhanced Dropdown */
    .print-dropdown .dropdown-toggle {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .print-dropdown .dropdown-toggle:hover {
        border-color: var(--gold-primary);
        background: var(--gold-subtle);
    }

    .print-dropdown .dropdown-menu {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        padding: 8px;
        min-width: 280px;
        background: var(--bg-card);
    }

    .print-dropdown .dropdown-item {
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-medium);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .print-dropdown .dropdown-item:hover {
        background: var(--gold-subtle);
        color: var(--gold-primary);
    }

    /* Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .quick-action-btn {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .quick-action-btn:hover {
        border-color: var(--gold-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .quick-action-btn i {
        font-size: 24px;
        margin-bottom: 12px;
        color: var(--gold-primary);
        display: block;
    }

    .quick-action-btn .btn-text {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 14px;
        margin-bottom: 4px;
    }

    .quick-action-btn .btn-desc {
        font-size: 12px;
        color: var(--text-light);
        line-height: 1.4;
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

    .page-header .breadcrumbs {
        color: var(--text-light);
        font-size: 14px;
    }

    .page-header .breadcrumbs a {
        color: var(--text-medium);
        text-decoration: none;
    }

    .page-header .breadcrumbs a:hover {
        color: var(--gold-primary);
    }

    /* Status Badges */
    .status-badge-modern {
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

    /* Alert */
    .alert-modern {
        border: 1px solid rgba(4, 120, 87, 0.2);
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(4, 120, 87, 0.05);
    }

    .alert-modern-success i {
        color: var(--emerald);
        font-size: 20px;
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

    /* Print Menu */
    .print-menu {
        display: none;
        position: fixed;
        bottom: 90px;
        right: 24px;
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        padding: 15px;
        min-width: 280px;
        z-index: 999;
    }

    .print-menu.show {
        display: block;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .print-menu-header {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .print-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--text-dark);
    }

    .print-menu-item:hover {
        background: var(--gold-subtle);
    }

    .print-menu-item i {
        width: 20px;
        text-align: center;
        color: var(--gold-primary);
    }

    .print-menu-divider {
        height: 1px;
        background: var(--border-light);
        margin: 8px 0;
    }

    /* Print Styles */
    @media print {
        .print-float-btn,
        .print-dropdown,
        .btn-modern,
        .quick-action-btn,
        .alert-modern,
        .print-menu {
            display: none !important;
        }

        .wg-box {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            margin-bottom: 20px !important;
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

        .product-card {
            flex-direction: column;
            text-align: center;
        }

        .product-image-container {
            margin: 0 auto 12px;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }

        .print-float-btn {
            bottom: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            font-size: 18px;
        }

        .print-menu {
            bottom: 80px;
            right: 15px;
            left: 15px;
            min-width: auto;
        }
    }
</style>

@php
    $settings = \App\Models\Setting::getSettings();
    $currencySymbol = $settings->currency_symbol ?? '€';
    $taxRate = $settings->tax_rate ?? 20;
@endphp

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <!-- Modern Header -->
            <div class="page-header">
                <div class="flex items-center flex-wrap justify-between gap20">
                    <div>
                        <h3>Détails de la commande #{{ $order->id }}</h3>
                        <div class="text-tiny mt-2 opacity-90">
                            <i class="far fa-calendar-alt me-1"></i>
                            Commande créée le {{ $order->created_at->format('d/m/Y à H:i') }}
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
                            <a href="{{ route('admin.orders') }}">
                                <div class="text-tiny">Commandes</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right opacity-50"></i>
                        </li>
                        <li>
                            <div class="text-tiny fw-bold">Commande #{{ $order->id }}</div>
                        </li>
                    </ul>
                </div>
            </div>

            @if (Session::has('status'))
            <div class="alert alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                {{ Session::get('status') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Order Header Info -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <span class="status-badge-modern status-{{ $order->status }}">
                                    @if($order->status == 'delivered')
                                        <i class="fas fa-check-circle me-1"></i> Livré
                                    @elseif($order->status == 'confirmed')
                                        <i class="fas fa-check me-1"></i> Confirmé
                                    @elseif($order->status == 'canceled')
                                        <i class="fas fa-times-circle me-1"></i> Annulé
                                    @else
                                        <i class="fas fa-clock me-1"></i> Commandé
                                    @endif
                                </span>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <span class="text-muted me-2">Client:</span>
                                <strong>{{ $order->name }}</strong>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <span class="text-muted me-2">Tél:</span>
                                <strong>{{ $order->phone }}</strong>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <span class="text-muted me-2">Total:</span>
                                <strong class="text-success">{{ number_format($order->total, 2, ',', ' ') }} {{ $currencySymbol }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap10 flex-wrap">
                        <a href="{{ route('admin.orders') }}" class="btn btn-modern btn-modern-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>

                        <div class="dropdown print-dropdown">
                            <button class="btn dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-print me-1"></i> Imprimer / Télécharger
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header">
                                    <i class="fas fa-file-invoice me-2"></i> Documents commerciaux
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.order.print.invoice', ['order_id' => $order->id]) }}" target="_blank">
                                        <i class="fas fa-file-invoice text-primary me-2"></i> Facture PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.order.print.order-summary', ['order_id' => $order->id]) }}" target="_blank">
                                        <i class="fas fa-file-alt text-info me-2"></i> Résumé de commande
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.order.print.delivery-note', ['order_id' => $order->id]) }}" target="_blank">
                                        <i class="fas fa-truck text-success me-2"></i> Bon de livraison
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">
                                    <i class="fas fa-shipping-fast me-2"></i> Logistique
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.order.print.shipping-label', ['order_id' => $order->id]) }}" target="_blank">
                                        <i class="fas fa-tag text-warning me-2"></i> Étiquette d'expédition
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.order.print.packing-slip', ['order_id' => $order->id]) }}" target="_blank">
                                        <i class="fas fa-box-open text-secondary me-2"></i> Bordereau de colisage
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">
                                    <i class="fas fa-envelope me-2"></i> Email
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sendEmailInvoice(); return false;">
                                        <i class="fas fa-paper-plane text-primary me-2"></i> Envoyer facture par email
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sendEmailSummary(); return false;">
                                        <i class="fas fa-envelope text-info me-2"></i> Envoyer résumé par email
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="window.print()">
                                        <i class="fas fa-print me-2"></i> Imprimer cette page
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="wg-box">
                <h5 class="mb-4">
                    <i class="fas fa-history me-2 text-primary"></i>
                    Historique de la commande
                </h5>
                <div class="status-timeline">
                    <div class="timeline-track"></div>

                    <div class="timeline-step completed">
                        <div class="timeline-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Commande passée</div>
                    </div>

                    @if($order->confirmed_date)
                    <div class="timeline-step completed">
                        <div class="timeline-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($order->confirmed_date)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Commande confirmée</div>
                    </div>
                    @else
                    <div class="timeline-step {{ $order->status == 'confirmed' ? 'current' : 'pending' }}">
                        <div class="timeline-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-date">En attente</div>
                        <div class="timeline-text">Confirmation</div>
                    </div>
                    @endif

                    @if($order->status == 'delivered' && $order->delivered_date)
                    <div class="timeline-step completed">
                        <div class="timeline-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Commande livrée</div>
                    </div>
                    @elseif($order->status == 'confirmed')
                    <div class="timeline-step current">
                        <div class="timeline-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="timeline-date">En cours</div>
                        <div class="timeline-text">En préparation</div>
                    </div>
                    @elseif($order->status != 'canceled')
                    <div class="timeline-step pending">
                        <div class="timeline-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-date">À venir</div>
                        <div class="timeline-text">Livraison</div>
                    </div>
                    @endif

                    @if($order->status == 'canceled' && $order->cancelled_date)
                    <div class="timeline-step completed">
                        <div class="timeline-icon">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($order->cancelled_date)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Commande annulée</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Information -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="card-title">Informations Commande</div>
                        <div class="card-value">#{{ $order->id }}</div>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-calendar me-1"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-box me-1"></i>
                                {{ $orderItems->count() }} article(s)
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="card-icon" style="background: linear-gradient(135deg, var(--success-color), #27ae60);">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="card-title">Total Commande</div>
                        <div class="card-value">{{ number_format($order->total, 2, ',', ' ') }} {{ $currencySymbol }}</div>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">
                                Sous-total: {{ number_format($order->subtotal, 2, ',', ' ') }} {{ $currencySymbol }}
                            </small>
                            <small class="text-muted d-block">
                                Taxe: {{ number_format($order->tax, 2, ',', ' ') }} {{ $currencySymbol }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="card-icon" style="background: linear-gradient(135deg, var(--info-color), #2980b9);">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="card-title">Client</div>
                        <div class="card-value">{{ $order->name }}</div>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-phone me-1"></i>
                                {{ $order->phone }}
                            </small>
                            @if($order->user && $order->user->email)
                            <small class="text-muted d-block">
                                <i class="fas fa-envelope me-1"></i>
                                {{ $order->user->email }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ordered Products -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                    <div class="wg-filter flex-grow">
                        <h5>
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            Articles commandés ({{ $orderItems->total() }})
                        </h5>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="btn-modern btn-modern-secondary" onclick="printProducts()">
                            <i class="fas fa-print me-1"></i> Imprimer liste
                        </button>
                        <button class="btn-modern btn-modern-secondary" onclick="exportProductsCSV()">
                            <i class="fas fa-file-csv me-1"></i> Exporter CSV
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-modern" id="productsTable">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Produit</th>
                                <th width="100" class="text-center">Prix</th>
                                <th width="80" class="text-center">Quantité</th>
                                <th width="120" class="text-center">Total</th>
                                <th width="80" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $index => $item)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="product-card">
                                            <div class="product-image-container">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="product-image"
                                                         onerror="this.src='{{ asset('images/default-product.jpg') }}'; this.onerror=null;">
                                                @else
                                                    <i class="fas fa-image text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="product-details">
                                                <div class="product-name">
                                                    @if($item->product)
                                                        {{ $item->product->name }}
                                                    @else
                                                        <span class="text-muted">Produit supprimé</span>
                                                    @endif
                                                </div>
                                                <div class="product-meta">
                                                    @if($item->product && $item->product->SKU)
                                                    <span>SKU: {{ $item->product->SKU }}</span>
                                                    @endif
                                                    @if($item->options)
                                                    <span>Options: {{ $item->options }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ number_format($item->price, 2, ',', ' ') }} {{ $currencySymbol }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-1">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $currencySymbol }}
                                    </td>
                                    <td class="text-center">
                                        @if($item->product)
                                        <a href="{{ route('shop.product.details',['product_slug'=>$item->product->slug]) }}"
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orderItems->hasPages())
                <div class="mt-4">
                    {{ $orderItems->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>

            <!-- Address and Transaction -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="wg-box h-100">
                        <h5 class="mb-4">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            Adresse de livraison
                        </h5>
                        <div class="p-3 bg-light rounded">
                            <address class="mb-0">
                                <strong>{{ $order->name }}</strong><br>
                                {{ $order->address }}<br>
                                @if($order->locality)
                                    {{ $order->locality }}<br>
                                @endif
                                {{ $order->zip }} {{ $order->city }}<br>
                                {{ $order->country }}<br>
                                @if($order->landmark)
                                    <span class="text-muted">Repère: {{ $order->landmark }}</span><br>
                                @endif
                                <br>
                                <i class="fas fa-phone me-1"></i> {{ $order->phone }}
                            </address>
                            <div class="mt-3">
                                <button class="btn-modern btn-modern-secondary btn-sm" onclick="printAddressLabel()">
                                    <i class="fas fa-tag me-1"></i> Imprimer étiquette adresse
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="wg-box h-100">
                        <h5 class="mb-4">
                            <i class="fas fa-credit-card me-2 text-primary"></i>
                            Détails du paiement
                        </h5>
                        <div class="p-3 bg-light rounded">
                            <div class="mb-3">
                                <strong>Méthode de paiement:</strong><br>
                                <span class="badge bg-info">{{ $transaction->mode ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Statut du paiement:</strong><br>
                                @if($transaction)
                                    @if($transaction->status == 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i> Approuvé
                                        </span>
                                    @elseif($transaction->status == 'declined')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i> Refusé
                                        </span>
                                    @elseif($transaction->status == 'refunded')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-undo me-1"></i> Remboursé
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i> En attente
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Non disponible</span>
                                @endif
                            </div>
                            <div>
                                <strong>Montant total:</strong><br>
                                <h4 class="text-success fw-bold mb-0">
                                    {{ number_format($order->total, 2, ',', ' ') }} {{ $currencySymbol }}
                                </h4>
                            </div>
                            <div class="mt-3">
                                <button class="btn-modern btn-modern-secondary btn-sm" onclick="printReceipt()">
                                    <i class="fas fa-receipt me-1"></i> Imprimer reçu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- Email Information -->
<div class="wg-box">
    <h5 class="mb-4">
        <i class="fas fa-history me-2 text-primary"></i>
        Historique des emails envoyés
    </h5>

    @php
        $emailLogs = \App\Models\EmailLog::where('metadata->order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    @endphp

    @if($emailLogs->count() > 0)
    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Sujet</th>
                    <th>Destinataire</th>
                    <th>Statut</th>
                    <th>Envoyé par</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emailLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @switch($log->recipient_type)
                            @case('order_invoice') <span class="badge bg-danger">Facture</span> @break
                            @case('order_summary') <span class="badge bg-info">Résumé</span> @break
                            @case('order_confirmation') <span class="badge bg-success">Confirmation</span> @break
                            @case('order_status_update') <span class="badge bg-warning">Statut</span> @break
                            @default <span class="badge bg-secondary">{{ $log->recipient_type }}</span>
                        @endswitch
                    </td>
                    <td>{{ Str::limit($log->subject, 40) }}</td>
                    <td>
                        @if($log->recipients)
                            @php $recipients = json_decode($log->recipients); @endphp
                            @if(is_array($recipients) && count($recipients) > 0)
                                {{ $recipients[0] }}
                            @endif
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-success">Envoyé</span>
                    </td>
                    <td>{{ $log->sender->name ?? 'Système' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Aucun email n'a encore été envoyé pour cette commande.
    </div>
    @endif

    <div class="mt-3 text-end">
        <a href="{{ route('admin.emails.index') }}" class="btn-modern btn-modern-secondary btn-sm">
            <i class="fas fa-external-link-alt me-1"></i> Voir tous les emails
        </a>
    </div>
</div>
            <!-- Update Status -->
            <div class="wg-box">
                <h5 class="mb-4">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Mettre à jour le statut
                </h5>
                <form action="{{ route('admin.order.status.update') }}" method="POST" id="updateStatusForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Statut actuel</label>
                            <div class="form-control bg-light border-0">
                                <span class="status-badge-modern status-{{ $order->status }}">
                                    @if($order->status == 'delivered') Livré
                                    @elseif($order->status == 'confirmed') Confirmé
                                    @elseif($order->status == 'canceled') Annulé
                                    @else Commandé @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="order_status" class="form-label">Nouveau statut</label>
                            <select name="order_status" id="order_status" class="form-select form-select-modern" required>
                                <option value="ordered" {{ $order->status == 'ordered' ? "selected":"" }}>Commandé</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? "selected":"" }}>Confirmé</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? "selected":"" }}>Livré</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? "selected":"" }}>Annulé</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-modern btn-modern-primary w-100">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>

                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.orders') }}" class="btn btn-modern btn-modern-secondary w-100">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0 p-3 rounded">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>La mise à jour du statut enverra une notification par email au client.</small>
                    </div>
                </form>
            </div>
<!-- Email Actions -->
<div class="wg-box">
    <h5 class="mb-4">
        <i class="fas fa-envelope me-2 text-primary"></i>
        Actions Email Rapides
    </h5>
    <div class="row g-3">
        <div class="col-md-4">
            <button class="btn-modern btn-modern-primary w-100" onclick="sendEmailConfirmation()">
                <i class="fas fa-check-circle me-2"></i> Confirmation
            </button>
            <small class="text-muted d-block text-center mt-2">Confirmer la commande</small>
        </div>
        <div class="col-md-4">
            <button class="btn-modern btn-modern-primary w-100" onclick="sendEmailInvoice()">
                <i class="fas fa-file-invoice me-2"></i> Facture PDF
            </button>
            <small class="text-muted d-block text-center mt-2">Envoyer la facture</small>
        </div>
        <div class="col-md-4">
            <button class="btn-modern btn-modern-primary w-100" onclick="sendEmailSummary()">
                <i class="fas fa-file-alt me-2"></i> Résumé
            </button>
            <small class="text-muted d-block text-center mt-2">Envoyer le résumé</small>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <button class="btn-modern btn-modern-secondary w-100" onclick="updateOrderStatusWithEmail()">
                <i class="fas fa-exchange-alt me-2"></i> Mettre à jour + Email
            </button>
            <small class="text-muted d-block text-center mt-2">Changer statut avec notification</small>
        </div>
        <div class="col-md-6">
            <div class="dropdown">
                <button class="btn-modern btn-modern-secondary w-100 dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog me-2"></i> Plus d'options
                </button>
                <ul class="dropdown-menu w-100">
                    <li>
                        <a class="dropdown-item" href="#" onclick="quickEmailAction('invoice'); return false;">
                            <i class="fas fa-file-pdf text-danger me-2"></i> Facture rapide
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="quickEmailAction('summary'); return false;">
                            <i class="fas fa-file-alt text-info me-2"></i> Résumé rapide
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="quickEmailAction('confirmation'); return false;">
                            <i class="fas fa-check text-success me-2"></i> Confirmation rapide
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.emails.index') }}" target="_blank">
                            <i class="fas fa-cogs me-2"></i> Gestion des emails
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
            <!-- Print Actions -->
            <div class="wg-box">
                <h5 class="mb-4">
                    <i class="fas fa-print me-2 text-primary"></i>
                    Actions d'impression
                </h5>
                <div class="quick-actions-grid">
                    <div class="quick-action-btn" onclick="window.open('{{ route("admin.order.print.invoice", ["order_id" => $order->id]) }}', '_blank')">
                        <i class="fas fa-file-invoice"></i>
                        <div class="btn-text">Facture complète</div>
                        <div class="btn-desc">Document fiscal détaillé</div>
                    </div>

                    <div class="quick-action-btn" onclick="window.open('{{ route("admin.order.print.shipping-label", ["order_id" => $order->id]) }}', '_blank')">
                        <i class="fas fa-tag"></i>
                        <div class="btn-text">Étiquette d'expédition</div>
                        <div class="btn-desc">Format 4x6 pouces</div>
                    </div>

                    <div class="quick-action-btn" onclick="window.open('{{ route("admin.order.print.packing-slip", ["order_id" => $order->id]) }}', '_blank')">
                        <i class="fas fa-box-open"></i>
                        <div class="btn-text">Bordereau de colisage</div>
                        <div class="btn-desc">Liste de contrôle interne</div>
                    </div>

                    <div class="quick-action-btn" onclick="window.open('{{ route("admin.order.print.delivery-note", ["order_id" => $order->id]) }}', '_blank')">
                        <i class="fas fa-truck"></i>
                        <div class="btn-text">Bon de livraison</div>
                        <div class="btn-desc">Avec signature client</div>
                    </div>

                    <div class="quick-action-btn" onclick="window.open('{{ route("admin.order.print.order-summary", ["order_id" => $order->id]) }}', '_blank')">
                        <i class="fas fa-file-alt"></i>
                        <div class="btn-text">Résumé de commande</div>
                        <div class="btn-desc">Vue d'ensemble simplifiée</div>
                    </div>

                    <div class="quick-action-btn" onclick="printOrderDetails()">
                        <i class="fas fa-print"></i>
                        <div class="btn-text">Imprimer cette page</div>
                        <div class="btn-desc">Version imprimable</div>
                    </div>

                    <div class="quick-action-btn" onclick="sendEmailInvoice()">
                        <i class="fas fa-paper-plane"></i>
                        <div class="btn-text">Envoyer par email</div>
                        <div class="btn-desc">Facture au format PDF</div>
                    </div>

                    <div class="quick-action-btn" onclick="printAllDocuments()">
                        <i class="fas fa-print"></i>
                        <div class="btn-text">Tout imprimer</div>
                        <div class="btn-desc">Tous les documents</div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="wg-box">
                <h5 class="mb-4">
                    <i class="fas fa-download me-2 text-primary"></i>
                    Options d'export
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <button class="btn-modern btn-modern-secondary w-100" onclick="exportOrderCSV()">
                            <i class="fas fa-file-csv me-1"></i> Export CSV
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn-modern btn-modern-secondary w-100" onclick="exportOrderJSON()">
                            <i class="fas fa-file-code me-1"></i> Export JSON
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn-modern btn-modern-secondary w-100" onclick="exportOrderExcel()">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn-modern btn-modern-primary w-100" onclick="archiveOrder()">
                            <i class="fas fa-archive me-1"></i> Archiver
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Menu -->
    <div class="print-menu" id="printMenu">
        <div class="print-menu-header">
            <i class="fas fa-print"></i> Options d'impression
        </div>

        <a href="{{ route('admin.order.print.invoice', ['order_id' => $order->id]) }}" target="_blank" class="print-menu-item">
            <i class="fas fa-file-invoice"></i>
            <span>Facture PDF</span>
        </a>

        <a href="{{ route('admin.order.print.shipping-label', ['order_id' => $order->id]) }}" target="_blank" class="print-menu-item">
            <i class="fas fa-tag"></i>
            <span>Étiquette d'expédition</span>
        </a>

        <a href="{{ route('admin.order.print.packing-slip', ['order_id' => $order->id]) }}" target="_blank" class="print-menu-item">
            <i class="fas fa-box-open"></i>
            <span>Bordereau de colisage</span>
        </a>

        <a href="{{ route('admin.order.print.delivery-note', ['order_id' => $order->id]) }}" target="_blank" class="print-menu-item">
            <i class="fas fa-truck"></i>
            <span>Bon de livraison</span>
        </a>

        <div class="print-menu-divider"></div>

        <a href="{{ route('admin.order.print.order-summary', ['order_id' => $order->id]) }}" target="_blank" class="print-menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Résumé de commande</span>
        </a>

        <a href="#" onclick="window.print(); closePrintMenu(); return false;" class="print-menu-item">
            <i class="fas fa-print"></i>
            <span>Imprimer cette page</span>
        </a>

        <div class="print-menu-divider"></div>

        <a href="#" onclick="sendEmailInvoice(); closePrintMenu(); return false;" class="print-menu-item">
            <i class="fas fa-paper-plane"></i>
            <span>Envoyer par email</span>
        </a>
    </div>

    <!-- Floating Print Button -->
    <button onclick="togglePrintMenu()" class="print-float-btn d-print-none" title="Imprimer (Ctrl+P)">
        <i class="fas fa-print"></i>
    </button>

 <script>
    // Get CSRF token from meta tag
    const csrfToken = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', function() {
        // Status form submission
        const statusForm = document.getElementById('updateStatusForm');
        const statusSelect = document.getElementById('order_status');

        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                if (statusSelect.value === '{{ $order->status }}') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Statut identique',
                        text: 'Le statut sélectionné est le même que le statut actuel.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4361ee'
                    });
                }
            });
        }

        // Add hover effects to product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Initialize print functionality
        initializePrintFunctions();
    });

    // Print functions
    function initializePrintFunctions() {
        console.log('Print functions initialized for order #{{ $order->id }}');
    }

    function togglePrintMenu() {
        const menu = document.getElementById('printMenu');
        menu.classList.toggle('show');
    }

    function closePrintMenu() {
        const menu = document.getElementById('printMenu');
        menu.classList.remove('show');
    }

    function printOrderDetails() {
        window.print();
    }

    function printProducts() {
        const printWindow = window.open('', '_blank');
        const orderData = {
            orderId: {{ $order->id }},
            customer: '{{ $order->name }}',
            date: '{{ $order->created_at->format("d/m/Y H:i") }}',
            items: []
        };

        document.querySelectorAll('#productsTable tbody tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 5) {
                const item = {
                    name: cells[1].querySelector('.product-name').textContent.trim(),
                    price: cells[2].textContent.trim(),
                    quantity: cells[3].textContent.trim(),
                    total: cells[4].textContent.trim()
                };
                orderData.items.push(item);
            }
        });

        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Liste des produits - Commande #${orderData.orderId}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f8f9fa; }
                </style>
            </head>
            <body>
                <h1>Commande #${orderData.orderId}</h1>
                <p><strong>Client:</strong> ${orderData.customer}</p>
                <p><strong>Date:</strong> ${orderData.date}</p>

                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orderData.items.map((item, index) => `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.price}</td>
                                <td>${item.quantity}</td>
                                <td>${item.total}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div style="margin-top: 30px; font-size: 12px; color: #666;">
                    Généré le ${new Date().toLocaleDateString('fr-FR')}
                </div>

                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
            </html>
        `;

        printWindow.document.write(html);
        printWindow.document.close();
    }

    function exportProductsCSV() {
        const rows = [];
        const headers = ['Produit', 'SKU', 'Options', 'Prix unitaire', 'Quantité', 'Total'];
        rows.push(headers.join(','));

        document.querySelectorAll('#productsTable tbody tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 5) {
                const productName = cells[1].querySelector('.product-name').textContent.trim();
                const productMeta = cells[1].querySelector('.product-meta');
                const sku = productMeta ? productMeta.querySelector('span:nth-child(1)')?.textContent?.replace('SKU: ', '') || '' : '';
                const options = productMeta ? productMeta.querySelector('span:nth-child(2)')?.textContent?.replace('Options: ', '') || '' : '';
                const price = cells[2].textContent.trim().replace('{{ $currencySymbol }}', '').trim();
                const quantity = cells[3].textContent.trim();
                const total = cells[4].textContent.trim().replace('{{ $currencySymbol }}', '').trim();

                rows.push([
                    `"${productName}"`,
                    `"${sku}"`,
                    `"${options}"`,
                    price,
                    quantity,
                    total
                ].join(','));
            }
        });

        const csvContent = rows.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);

        link.setAttribute('href', url);
        link.setAttribute('download', `produits-commande-{{ $order->id }}-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function printAddressLabel() {
        const printWindow = window.open('', '_blank');
        const address = {
            name: '{{ $order->name }}',
            street: '{{ $order->address }}',
            locality: '{{ $order->locality }}',
            city: '{{ $order->city }}',
            zip: '{{ $order->zip }}',
            country: '{{ $order->country }}',
            phone: '{{ $order->phone }}',
            orderId: {{ $order->id }}
        };

        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Étiquette adresse - Commande #${address.orderId}</title>
                <style>
                    @media print {
                        @page { size: 4in 6in; margin: 0; }
                        body { margin: 0; padding: 10px; }
                    }
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .label { border: 1px solid #000; padding: 15px; width: 4in; height: 6in; }
                    .company { font-weight: bold; margin-bottom: 10px; }
                    .to { font-size: 14px; line-height: 1.4; margin-bottom: 20px; }
                    .barcode { text-align: center; margin: 20px 0; font-family: monospace; font-size: 18px; }
                </style>
            </head>
            <body>
                <div class="label">
                    <div class="company">{{ $settings->site_name ?? 'Bijoux' }}</div>
                    <div style="margin-bottom: 15px; font-size: 12px;">
                        {{ $settings->site_address ?? '' }}<br>
                        Tél: {{ $settings->site_phone ?? '' }}
                    </div>

                    <hr style="border-top: 1px dashed #ccc; margin: 10px 0;">

                    <div class="to">
                        <strong>À:</strong><br>
                        ${address.name}<br>
                        ${address.street}<br>
                        ${address.locality ? address.locality + '<br>' : ''}
                        ${address.zip} ${address.city}<br>
                        ${address.country}<br>
                        <br>
                        Tél: ${address.phone}
                    </div>

                    <div class="barcode">
                        *${address.orderId.toString().padStart(8, '0')}*
                        <div style="font-size: 10px;">Commande #${address.orderId}</div>
                    </div>

                    <div style="font-size: 10px; color: #666; text-align: center;">
                        Généré le ${new Date().toLocaleDateString('fr-FR')}
                    </div>
                </div>

                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
            </html>
        `;

        printWindow.document.write(html);
        printWindow.document.close();
    }

    function printReceipt() {
        window.open('{{ route("admin.order.print.invoice", ["order_id" => $order->id]) }}', '_blank');
    }

    function exportOrderCSV() {
        window.location.href = '{{ route("admin.order.export.csv", ["order_id" => $order->id]) }}';
    }

    function exportOrderJSON() {
        window.location.href = '{{ route("admin.order.export.json", ["order_id" => $order->id]) }}';
    }

    function exportOrderExcel() {
        exportOrderCSV();
        Swal.fire({
            icon: 'info',
            title: 'Export Excel',
            text: 'L\'export Excel est actuellement généré en format CSV.',
            confirmButtonText: 'Compris'
        });
    }

    function archiveOrder() {
        Swal.fire({
            title: 'Archiver la commande',
            text: 'Êtes-vous sûr de vouloir archiver cette commande ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, archiver',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("admin.order.archive", ["order_id" => $order->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Commande archivée',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    function printAllDocuments() {
        Swal.fire({
            title: 'Imprimer tous les documents',
            text: 'Cette action ouvrira tous les documents dans de nouveaux onglets.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Continuer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const documents = [
                    '{{ route("admin.order.print.invoice", ["order_id" => $order->id]) }}',
                    '{{ route("admin.order.print.shipping-label", ["order_id" => $order->id]) }}',
                    '{{ route("admin.order.print.packing-slip", ["order_id" => $order->id]) }}',
                    '{{ route("admin.order.print.delivery-note", ["order_id" => $order->id]) }}',
                    '{{ route("admin.order.print.order-summary", ["order_id" => $order->id]) }}'
                ];

                documents.forEach(url => {
                    window.open(url, '_blank');
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Impression lancée',
                    text: 'Tous les documents sont en cours d\'ouverture.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // ============================================
    // EMAIL FUNCTIONS
    // ============================================

    /**
     * Send invoice email
     */
    function sendEmailInvoice() {
        Swal.fire({
            title: 'Envoyer la facture par email',
            html: `
                <div class="text-start">
                    <p>La facture sera envoyée à :</p>
                    <div class="alert alert-info p-2 mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>{{ $order->user->email ?? $order->email ?? 'Email non disponible' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet personnalisé (optionnel)</label>
                        <input type="text" id="customSubject" class="form-control"
                               placeholder="Facture pour la commande #{{ $order->id }}"
                               value="Facture - Commande #{{ $order->id }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message personnalisé (optionnel)</label>
                        <textarea id="customMessage" class="form-control" rows="3"
                                  placeholder="Bonjour {{ $order->name }},..."></textarea>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const subject = document.getElementById('customSubject').value;
                const message = document.getElementById('customMessage').value;

                return fetch('{{ route("admin.order.send.invoice.email", ["order_id" => $order->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subject: subject,
                        message: message
                    })
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Server returned:', text.substring(0, 200));
                        throw new Error('Le serveur a retourné une erreur.');
                    }
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Erreur de serveur');
                    }
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email envoyé !',
                    text: 'La facture a été envoyée avec succès.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    /**
     * Send summary email
     */
    function sendEmailSummary() {
        Swal.fire({
            title: 'Envoyer le résumé par email',
            html: `
                <div class="text-start">
                    <p>Le résumé sera envoyé à :</p>
                    <div class="alert alert-info p-2 mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>{{ $order->user->email ?? $order->email ?? 'Email non disponible' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet personnalisé (optionnel)</label>
                        <input type="text" id="customSubject" class="form-control"
                               placeholder="Résumé de commande #{{ $order->id }}"
                               value="Résumé de votre commande #{{ $order->id }}">
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const subject = document.getElementById('customSubject').value;

                return fetch('{{ route("admin.order.send.summary.email", ["order_id" => $order->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subject: subject
                    })
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Le serveur a retourné une erreur.');
                    }
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Erreur de serveur');
                    }
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email envoyé !',
                    text: 'Le résumé a été envoyé avec succès.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    /**
     * Send confirmation email
     */
    function sendEmailConfirmation() {
        Swal.fire({
            title: 'Envoyer la confirmation',
            html: `
                <div class="text-start">
                    <p>La confirmation sera envoyée à :</p>
                    <div class="alert alert-info p-2 mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>{{ $order->user->email ?? $order->email ?? 'Email non disponible' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet personnalisé (optionnel)</label>
                        <input type="text" id="customSubject" class="form-control"
                               placeholder="Confirmation de commande #{{ $order->id }}"
                               value="Confirmation de votre commande #{{ $order->id }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message personnalisé (optionnel)</label>
                        <textarea id="customMessage" class="form-control" rows="3"
                                  placeholder="Bonjour {{ $order->name }}, votre commande a été confirmée..."></textarea>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check-circle me-2"></i>Confirmer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const subject = document.getElementById('customSubject').value;
                const message = document.getElementById('customMessage')?.value || '';

                return fetch('{{ route("admin.order.send.confirmation.email", ["order_id" => $order->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subject: subject,
                        message: message
                    })
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Server returned:', text.substring(0, 200));
                        throw new Error('Le serveur a retourné une erreur.');
                    }
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Erreur de serveur');
                    }
                    if (!data.success) {
                        throw new Error(data.message || 'Erreur inconnue');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    icon: 'success',
                    title: 'Confirmation envoyée !',
                    text: result.value.message || 'L\'email de confirmation a été envoyé avec succès.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    /**
     * Quick email action
     */
    function quickEmailAction(action) {
        let title = '';
        let text = '';

        switch(action) {
            case 'invoice':
                title = 'Envoyer la facture';
                text = 'Envoyer la facture PDF par email ?';
                break;
            case 'summary':
                title = 'Envoyer le résumé';
                text = 'Envoyer le résumé de commande par email ?';
                break;
            case 'confirmation':
                title = 'Envoyer la confirmation';
                text = 'Envoyer l\'email de confirmation ?';
                break;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Envoyer',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('{{ route("admin.order.quick.email", ["order_id" => $order->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: action
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email envoyé !',
                    text: result.value?.message || 'L\'email a été envoyé avec succès.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    /**
     * Update order status and send email
     */
    function updateOrderStatusWithEmail() {
        const statusSelect = document.getElementById('order_status');
        const currentStatus = '{{ $order->status }}';
        const newStatus = statusSelect.value;

        if (currentStatus === newStatus) {
            Swal.fire({
                icon: 'info',
                title: 'Statut identique',
                text: 'Le statut sélectionné est le même que le statut actuel.'
            });
            return;
        }

        Swal.fire({
            title: 'Mettre à jour le statut ?',
            text: 'Voulez-vous envoyer un email au client ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui + Email',
            cancelButtonText: 'Non, juste update'
        }).then((result) => {
            let sendEmail = result.isConfirmed;

            fetch('{{ route("admin.order.send.status.email", ["order_id" => $order->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    send_email: sendEmail,
                    message: ''
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Erreur de serveur');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur serveur',
                    text: error.message || 'Vérifiez la console pour plus de détails.'
                });
            });
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            togglePrintMenu();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
            e.preventDefault();
            window.open('{{ route("admin.order.print.invoice", ["order_id" => $order->id]) }}', '_blank');
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
            e.preventDefault();
            window.open('{{ route("admin.order.print.shipping-label", ["order_id" => $order->id]) }}', '_blank');
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            window.open('{{ route("admin.order.print.order-summary", ["order_id" => $order->id]) }}', '_blank');
        }
        if (e.key === 'Escape') {
            closePrintMenu();
        }
    });

    // Close print menu when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('printMenu');
        const button = document.querySelector('.print-float-btn');

        if (menu && menu.classList.contains('show') &&
            !menu.contains(e.target) &&
            button && !button.contains(e.target)) {
            closePrintMenu();
        }
    });
</script>

@endsection
