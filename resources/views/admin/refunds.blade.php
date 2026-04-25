@extends('layouts.admin')
@section('content')
<!-- Add Bootstrap Icons CDN in the head section -->
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    /* ===== STATUS STYLES ===== */
    .refund-status {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .status-pending {
        background: #ffc107;
        color: #000;
    }
    .status-under_review {
        background: #17a2b8;
        color: #fff;
    }
    .status-approved {
        background: #0d6efd;
        color: #fff;
    }
    .status-rejected {
        background: #dc3545;
        color: #fff;
    }
    .status-processing {
        background: #6c757d;
        color: #fff;
    }
    .status-completed {
        background: #28a745;
        color: #fff;
    }
    .status-cancelled {
        background: #212529;
        color: #fff;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        min-width: 160px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        border: none;
        font-size: 16px;
        position: relative;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Button Colors */
    .btn-view {
        background: #0dcaf0;
        color: white;
        border: 1px solid #0bb6e0;
    }
    .btn-view:hover {
        background: #0bb6e0;
        color: white;
    }

    .btn-edit {
        background: #6c757d;
        color: white;
        border: 1px solid #5c636a;
    }
    .btn-edit:hover {
        background: #5c636a;
        color: white;
    }

    .btn-approve {
        background: #198754;
        color: white;
        border: 1px solid #157347;
    }
    .btn-approve:hover {
        background: #157347;
        color: white;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
        border: 1px solid #bb2d3b;
    }
    .btn-reject:hover {
        background: #bb2d3b;
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: 1px solid #5c636a;
    }
    .btn-cancel:hover {
        background: #5c636a;
        color: white;
    }

    .btn-process {
        background: #0d6efd;
        color: white;
        border: 1px solid #0b5ed7;
    }
    .btn-process:hover {
        background: #0b5ed7;
        color: white;
    }

    .btn-refunded {
        background: #20c997;
        color: white;
        border: 1px solid #1baa7e;
    }
    .btn-refunded:hover {
        background: #1baa7e;
        color: white;
    }

    .btn-details {
        background: #17a2b8;
        color: white;
        border: 1px solid #138496;
    }
    .btn-details:hover {
        background: #138496;
        color: white;
    }

    .btn-more {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }
    .btn-more:hover {
        background: #e9ecef;
        color: #495057;
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-completed {
        background: #28a745;
        color: white;
    }

    /* ===== PRODUCT IMAGES ===== */
    .product-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    .empty-product {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    /* ===== DROPDOWN ACTIONS ===== */
    .dropdown-actions {
        position: relative;
        display: inline-block;
    }

    .dropdown-actions-menu {
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        display: none;
        min-width: 200px;
        padding: 8px 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 5px;
    }

    .dropdown-actions:hover .dropdown-actions-menu {
        display: block;
    }

    .dropdown-item {
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #212529;
        text-decoration: none;
        transition: background 0.2s;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
    }

    .dropdown-item i {
        width: 16px;
        font-size: 14px;
    }

    .dropdown-divider {
        height: 1px;
        background: #dee2e6;
        margin: 8px 0;
    }

    /* ===== ICON ADJUSTMENTS ===== */
    .bi {
        display: inline-block;
        vertical-align: middle;
    }

    /* ===== NOTIFICATION DOT ===== */
    .action-btn.pending::after {
        content: '';
        position: absolute;
        top: -3px;
        right: -3px;
        width: 10px;
        height: 10px;
        background: #ffc107;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* ===== RESPONSIVE ADJUSTMENTS ===== */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            min-width: auto;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .dropdown-actions-menu {
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            right: auto;
            min-width: 180px;
        }
    }

    /* ===== STATUS DROPDOWN ===== */
    .status-dropdown {
        min-width: 150px;
    }

    .status-option {
        padding: 8px 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .status-option:hover {
        background: #f8f9fa;
    }

    .status-option.active {
        background: #e7f1ff;
        color: #0d6efd;
        font-weight: 500;
    }

    /* Status badge in dropdown */
    .status-dropdown-btn {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .status-dropdown-btn:hover {
        background: #f8f9fa;
    }
</style>
@endpush

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>
                <i class="bi bi-arrow-counterclockwise me-2"></i>
                Demandes de Remboursement
            </h3>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-2"
                            type="button"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i>
                        <span>Filtrer</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?status=all"><i class="bi bi-list-check me-2"></i>Tous</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?status=pending"><i class="bi bi-clock me-2"></i>En attente</a></li>
                        <li><a class="dropdown-item" href="?status=under_review"><i class="bi bi-search me-2"></i>En révision</a></li>
                        <li><a class="dropdown-item" href="?status=approved"><i class="bi bi-check-circle me-2"></i>Approuvés</a></li>
                        <li><a class="dropdown-item" href="?status=processing"><i class="bi bi-gear me-2"></i>En traitement</a></li>
                        <li><a class="dropdown-item" href="?status=completed"><i class="bi bi-check2-all me-2"></i>Terminés</a></li>
                        <li><a class="dropdown-item" href="?status=rejected"><i class="bi bi-x-circle me-2"></i>Rejetés</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary d-flex align-items-center gap-2" id="exportBtn">
                    <i class="bi bi-download"></i>
                    <span>Exporter</span>
                </button>
            </div>
        </div>

        <div class="wg-box">
            <div class="wg-table">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Commande</th>
                                <th>Produits</th>
                                <th>Montant</th>
                                <th>Raison</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($refunds as $refund)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">#{{ $refund->id }}</span>
                                </td>
                                <td>
                                    @if($refund->order && $refund->order->user)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <div>{{ $refund->order->user->name }}</div>
                                                <small class="text-muted">{{ $refund->order->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Client non trouvé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($refund->order)
                                        <a href="{{ route('admin.order.details', $refund->order_id) }}"
                                           class="text-primary d-flex align-items-center gap-1">
                                            <i class="bi bi-receipt"></i>
                                            <span>#{{ $refund->order_id }}</span>
                                        </a>
                                    @else
                                        <span class="text-muted d-flex align-items-center gap-1">
                                            <i class="bi bi-receipt"></i>
                                            <span>#{{ $refund->order_id }}</span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php
                                            $orderItems = $refund->orderItems ?? collect();
                                        @endphp
                                        @if($orderItems->count() > 0)
                                            @foreach($orderItems as $item)
                                                <div class="text-center" style="width: 45px;"
                                                     title="{{ $item->product->name ?? 'Produit' }}">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                             alt="Product"
                                                             class="product-image">
                                                    @else
                                                        <div class="empty-product">
                                                            <i class="bi bi-box"></i>
                                                        </div>
                                                    @endif
                                                    <small class="d-block mt-1">{{ $item->pivot->quantity ?? $item->quantity }}x</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Aucun article</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-primary d-flex align-items-center gap-1">
                                            <i class="bi bi-currency-euro"></i>
                                            {{ number_format($refund->requested_amount, 2) }}€
                                        </strong>
                                        @if($refund->refund_amount)
                                            <small class="text-success d-flex align-items-center gap-1 mt-1">
                                                <i class="bi bi-check-circle"></i>
                                                Remboursé: {{ number_format($refund->refund_amount, 2) }}€
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-flex align-items-center gap-1">
                                            <i class="bi bi-chat-dots"></i>
                                            {{ $refund->getReasonTypeText() }}
                                        </strong>
                                        <br>
                                        <small class="text-muted" title="{{ $refund->reason_details }}">
                                            <i class="bi bi-text-paragraph"></i>
                                            {{ Str::limit($refund->reason_details, 50) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle status-dropdown-btn"
                                                type="button"
                                                id="statusDropdown{{ $refund->id }}"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                data-refund-id="{{ $refund->id }}"
                                                data-current-status="{{ $refund->status }}">
                                            <span class="refund-status status-{{ $refund->status }}">
                                                @switch($refund->status)
                                                    @case('pending')
                                                        <i class="bi bi-clock me-1"></i>
                                                    @break
                                                    @case('under_review')
                                                        <i class="bi bi-search me-1"></i>
                                                    @break
                                                    @case('approved')
                                                        <i class="bi bi-check-circle me-1"></i>
                                                    @break
                                                    @case('rejected')
                                                        <i class="bi bi-x-circle me-1"></i>
                                                    @break
                                                    @case('processing')
                                                        <i class="bi bi-gear me-1"></i>
                                                    @break
                                                    @case('completed')
                                                        <i class="bi bi-check2-all me-1"></i>
                                                    @break
                                                    @case('cancelled')
                                                        <i class="bi bi-slash-circle me-1"></i>
                                                    @break
                                                @endswitch
                                                {{ $refund->getStatusLabel() }}
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu status-dropdown" aria-labelledby="statusDropdown{{ $refund->id }}">
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'pending' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="pending">
                                                    <i class="bi bi-clock me-2"></i>En attente
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'under_review' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="under_review">
                                                    <i class="bi bi-search me-2"></i>En révision
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'approved' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="approved">
                                                    <i class="bi bi-check-circle me-2"></i>Approuvé
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'rejected' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="rejected">
                                                    <i class="bi bi-x-circle me-2"></i>Rejeté
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'processing' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="processing">
                                                    <i class="bi bi-gear me-2"></i>En traitement
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'completed' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="completed">
                                                    <i class="bi bi-check2-all me-2"></i>Terminé
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-option {{ $refund->status == 'cancelled' ? 'active' : '' }}"
                                                   href="#"
                                                   data-status="cancelled">
                                                    <i class="bi bi-slash-circle me-2"></i>Annulé
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="d-flex align-items-center gap-1">
                                            <i class="bi bi-calendar"></i>
                                            {{ $refund->getFormattedCreatedAt() }}
                                        </span>
                                        @if($refund->processed_at)
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <i class="bi bi-clock-history"></i>
                                            Traité: {{ $refund->getFormattedProcessedAt() }}
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- View Order Button -->
                                        <a href="{{ route('admin.order.details', $refund->order_id) }}"
                                           class="action-btn btn-view"
                                           title="Voir la commande"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Status-based Actions -->
                                        @if($refund->status == 'pending')
                                            <button class="action-btn btn-approve approve-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    data-refund-amount="{{ $refund->requested_amount }}"
                                                    title="Approuver le remboursement"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="action-btn btn-reject reject-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    title="Rejeter la demande"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <button class="action-btn btn-edit edit-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    title="Modifier la demande"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @elseif($refund->status == 'approved')
                                            <button class="action-btn btn-process process-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    title="Traiter le remboursement via Stripe"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <button class="action-btn btn-cancel cancel-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    title="Annuler la demande"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                        @elseif($refund->status == 'processing')
                                            <button class="action-btn btn-refunded complete-refund"
                                                    data-refund-id="{{ $refund->id }}"
                                                    title="Marquer comme terminé"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        @endif

                                        <!-- Details Button -->
                                        <button class="action-btn btn-details view-refund-details"
                                                data-refund-id="{{ $refund->id }}"
                                                title="Voir les détails"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                            <i class="bi bi-info-circle"></i>
                                        </button>

                                        <!-- More Actions Dropdown -->
                                        <div class="dropdown-actions">
                                            <button class="action-btn btn-more"
                                                    title="Plus d'actions"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-actions-menu">
                                                <button class="dropdown-item refund-history"
                                                        data-refund-id="{{ $refund->id }}">
                                                    <i class="bi bi-clock-history"></i>
                                                    <span>Historique</span>
                                                </button>
                                                @if($refund->stripe_refund_id)
                                                    <a href="https://dashboard.stripe.com/payments/{{ $refund->stripe_refund_id }}"
                                                       target="_blank"
                                                       class="dropdown-item">
                                                        <i class="bi bi-stripe"></i>
                                                        <span>Voir sur Stripe</span>
                                                    </a>
                                                @endif
                                                <button class="dropdown-item copy-refund-id"
                                                        data-refund-id="{{ $refund->id }}">
                                                    <i class="bi bi-clipboard"></i>
                                                    <span>Copier l'ID</span>
                                                </button>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.order.details', $refund->order_id) }}?tab=refunds"
                                                   class="dropdown-item">
                                                    <i class="bi bi-arrow-right-circle"></i>
                                                    <span>Aller à la commande</span>
                                                </a>
                                                <button class="dropdown-item delete-refund text-danger"
                                                        data-refund-id="{{ $refund->id }}"
                                                        data-refund-status="{{ $refund->status }}">
                                                    <i class="bi bi-trash"></i>
                                                    <span>Supprimer</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Indicator -->
                                    @if($refund->status == 'completed')
                                        <div class="mt-2">
                                            <span class="badge-completed d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check2-all"></i>
                                                Terminé
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Stripe Reference -->
                                    @if($refund->stripe_refund_id)
                                        <div class="mt-1">
                                            <small class="text-muted d-flex align-items-center gap-1"
                                                   title="ID Stripe: {{ $refund->stripe_refund_id }}">
                                                <i class="bi bi-stripe"></i>
                                                {{ Str::limit($refund->stripe_refund_id, 10) }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $refunds->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Refund Action Modal -->
<div class="modal fade" id="refundActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalTitle">
                    <i class="bi bi-gear"></i>
                    <span></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="refundActionForm" method="POST">
                @csrf
                <input type="hidden" name="refund_id" id="refundId">
                <input type="hidden" name="action" id="refundAction">

                <div class="modal-body">
                    <div id="refundInfo" class="mb-3"></div>

                    <div class="form-group mb-3">
                        <label for="admin_notes" class="form-label d-flex align-items-center gap-2">
                            <i class="bi bi-pencil"></i>
                            Notes d'administration
                        </label>
                        <textarea name="admin_notes" id="admin_notes"
                                  class="form-control" rows="3"
                                  placeholder="Ajoutez des notes concernant cette demande..."></textarea>
                    </div>

                    <!-- Amount Adjustment -->
                    <div id="amountAdjustmentSection" class="d-none">
                        <div class="form-group mb-3">
                            <label for="refund_amount" class="form-label d-flex align-items-center gap-2">
                                <i class="bi bi-currency-euro"></i>
                                Montant du remboursement
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                                <input type="number"
                                       name="refund_amount"
                                       id="refund_amount"
                                       class="form-control"
                                       step="0.01"
                                       min="0"
                                       value="0">
                                <span class="input-group-text">€</span>
                            </div>
                            <small class="text-muted d-flex align-items-center gap-1 mt-1">
                                <i class="bi bi-info-circle"></i>
                                Montant demandé: <span id="requestedAmount"></span>€
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle"></i>
                            <div>
                                <small>
                                    Les remboursements via Stripe seront traités immédiatement après approbation.
                                    Les fonds apparaîtront sur le compte du client sous 5-10 jours ouvrés.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn d-flex align-items-center gap-2" id="modalSubmitBtn">
                        <i class="bi bi-check-circle"></i>
                        <span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Details Modal -->
<div class="modal fade" id="refundDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle"></i>
                    Détails du Remboursement #<span id="detailsRefundId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="refundDetailsContent">
                <!-- Details loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-clockwise"></i>
                    Mettre à jour le statut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusUpdateForm">
                @csrf
                <input type="hidden" id="statusRefundId">
                <input type="hidden" id="statusNewStatus">

                <div class="modal-body">
                    <div class="mb-3">
                        <p>Vous êtes sur le point de changer le statut de la demande #<span id="statusRefundNumber"></span> à:</p>
                        <div class="alert alert-info" id="statusPreview">
                            <!-- Status preview will be inserted here -->
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="statusAdminNotes" class="form-label">
                            <i class="bi bi-pencil me-1"></i>Notes d'administration (optionnel)
                        </label>
                        <textarea name="admin_notes" id="statusAdminNotes"
                                  class="form-control" rows="3"
                                  placeholder="Ajoutez des notes concernant ce changement de statut..."></textarea>
                    </div>

                    <div class="form-group mb-3" id="amountUpdateSection" style="display: none;">
                        <label for="statusRefundAmount" class="form-label">
                            <i class="bi bi-currency-euro me-1"></i>Montant du remboursement
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number"
                                   name="refund_amount"
                                   id="statusRefundAmount"
                                   class="form-control"
                                   step="0.01"
                                   min="0">
                        </div>
                        <small class="text-muted">Montant demandé: <span id="statusRequestedAmount">0</span>€</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// GLOBAL FUNCTIONS - Defined outside document.ready for onclick handlers

/**
 * Update refund status (global function accessible to onclick handlers)
 */
function updateRefundStatus(refundId, newStatus) {
    // Get status label
    const statusLabels = {
        'pending': 'En attente',
        'under_review': 'En révision',
        'approved': 'Approuvé',
        'rejected': 'Rejeté',
        'processing': 'En traitement',
        'completed': 'Terminé',
        'cancelled': 'Annulé'
    };

    const statusColors = {
        'pending': 'warning',
        'under_review': 'info',
        'approved': 'primary',
        'rejected': 'danger',
        'processing': 'secondary',
        'completed': 'success',
        'cancelled': 'dark'
    };

    // Update modal
    $('#statusNewStatus').val(newStatus);
    $('#statusRefundId').val(refundId);
    $('#statusRefundNumber').text(refundId);

    $('#statusPreview').html(`
        <div class="d-flex align-items-center">
            <span class="badge bg-${statusColors[newStatus]} me-2">
                ${statusLabels[newStatus]}
            </span>
            <span>Cette action notifiera le client par email.</span>
        </div>
    `);

    // Get refund details for amount
    $.ajax({
        url: '/admin/refund/' + refundId + '/details',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#statusRequestedAmount').text(response.refund.requested_amount);
            }
        }
    });

    // Show/hide amount section
    if (newStatus === 'approved') {
        $('#amountUpdateSection').show();
        $('#statusRefundAmount').val($('#statusRequestedAmount').text());
    } else {
        $('#amountUpdateSection').hide();
    }

    // Show modal
    $('#statusUpdateModal').modal('show');
}

/**
 * Process quick action for refund
 */
function processQuickAction(refundId, action) {
    Swal.fire({
        title: 'Traitement en cours...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("admin.refund.quick-action") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            refund_id: refundId,
            action: action
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response.error || 'Une erreur est survenue'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            let errorMessage = 'Une erreur réseau est survenue';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: errorMessage
            });
        }
    });
}

/**
 * Reset submit button text and color
 */
function resetSubmitButton(action, submitBtn) {
    const buttonTexts = {
        'approve': 'Approuver',
        'reject': 'Rejeter la demande',
        'edit': 'Enregistrer',
        'process': 'Traiter',
        'complete': 'Terminer',
        'cancel': 'Annuler'
    };

    const text = buttonTexts[action] || 'Soumettre';
    const color = action === 'approve' ? 'success' : action === 'reject' ? 'danger' : action === 'process' ? 'primary' : action === 'complete' ? 'success' : 'primary';

    submitBtn.removeClass('btn-success btn-danger btn-primary')
        .addClass('btn-' + color)
        .html(`<i class="bi bi-check-circle me-2"></i><span>${text}</span>`);
}

$(document).ready(function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Status dropdown click handler using event delegation
    $(document).on('click', '.status-option', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const status = $(this).data('status');
        const dropdownBtn = $(this).closest('.dropdown').find('.status-dropdown-btn');
        const refundId = dropdownBtn.data('refund-id');

        if (refundId && status) {
            updateRefundStatus(refundId, status);
        }
    });

    // Handle status update form submission
    $('#statusUpdateForm').submit(function(e) {
        e.preventDefault();

        const refundId = $('#statusRefundId').val();
        const newStatus = $('#statusNewStatus').val();
        const adminNotes = $('#statusAdminNotes').val();
        const refundAmount = $('#statusRefundAmount').val();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Traitement...');

        $.ajax({
            url: '/admin/refund/' + refundId + '/update',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus,
                admin_notes: adminNotes,
                refund_amount: refundAmount || null
            },
            success: function(response) {
                $('#statusUpdateModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Statut mis à jour',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage
                });
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Approve refund
    $('.approve-refund').click(function() {
        const refundId = $(this).data('refund-id');
        const refundAmount = $(this).data('refund-amount');

        $('#modalTitle span').text('Approuver le remboursement');
        $('#refundAction').val('approve');
        $('#refundId').val(refundId);
        $('#amountAdjustmentSection').removeClass('d-none');
        $('#requestedAmount').text(refundAmount);
        $('#refund_amount').val(refundAmount);
        $('#refund_amount').attr('max', refundAmount);

        $('#refundInfo').html(`
            <div class="alert alert-success">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    <div>
                        <p class="mb-1">Vous êtes sur le point d'approuver cette demande de remboursement.</p>
                        <p class="mb-1"><strong>Montant demandé:</strong> ${refundAmount}€</p>
                        <p class="mb-0">Vous pouvez ajuster le montant si nécessaire.</p>
                    </div>
                </div>
            </div>
        `);

        $('#modalSubmitBtn').removeClass('btn-danger btn-primary')
            .addClass('btn-success')
            .find('span').text('Approuver');

        $('#refundActionModal').modal('show');
    });

    // Reject refund
    $('.reject-refund').click(function() {
        const refundId = $(this).data('refund-id');

        $('#modalTitle span').text('Rejeter la demande');
        $('#refundAction').val('reject');
        $('#refundId').val(refundId);
        $('#amountAdjustmentSection').addClass('d-none');

        $('#refundInfo').html(`
            <div class="alert alert-warning">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div>
                        <p class="mb-1">Vous êtes sur le point de rejeter cette demande de remboursement.</p>
                        <p class="mb-0">Le client sera notifié par email de votre décision.</p>
                    </div>
                </div>
            </div>
        `);

        $('#modalSubmitBtn').removeClass('btn-success btn-primary')
            .addClass('btn-danger')
            .find('span').text('Rejeter la demande');

        $('#refundActionModal').modal('show');
    });

    // Edit refund
    $('.edit-refund').click(function() {
        const refundId = $(this).data('refund-id');

        $('#modalTitle span').text('Modifier la demande');
        $('#refundAction').val('edit');
        $('#refundId').val(refundId);
        $('#amountAdjustmentSection').addClass('d-none');

        $('#refundInfo').html(`
            <div class="alert alert-info">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>
                    <div>
                        <p class="mb-0">Modifier les informations de cette demande de remboursement.</p>
                    </div>
                </div>
            </div>
        `);

        $('#modalSubmitBtn').removeClass('btn-danger btn-success')
            .addClass('btn-primary')
            .find('span').text('Enregistrer');

        $('#refundActionModal').modal('show');
    });

    // View refund details
    $('.view-refund-details').click(function(e) {
        e.preventDefault();
        const refundId = $(this).data('refund-id');

        // Show loading
        $('#refundDetailsContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p>Chargement des détails...</p>
            </div>
        `);
        $('#detailsRefundId').text(refundId);
        $('#refundDetailsModal').modal('show');

        // Load details via AJAX
        $.ajax({
                url: '{{ route("admin.refund.details", ":id") }}'.replace(':id', refundId),
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
            success: function(response) {
                if (response.success) {
                    const refund = response.refund;
                    const order = refund.order;
                    const user = order.user;

                    let itemsHtml = '';
                    if (response.order_items && response.order_items.length > 0) {
                        itemsHtml = response.order_items.map(item => `
                            <div class="d-flex align-items-center mb-2">
                                <img src="/uploads/products/thumbnails/${item.product.image}"
                                     alt="${item.product.name}"
                                     class="rounded me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <div class="fw-medium">${item.product.name}</div>
                                    <small class="text-muted">${item.quantity} × ${item.price}€ (Total: ${item.total}€)</small>
                                </div>
                            </div>
                        `).join('');
                    }

                    $('#refundDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Informations de base</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>#${refund.id}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut:</strong></td>
                                        <td><span class="badge bg-primary">${refund.getStatusLabel ? refund.getStatusLabel() : refund.status}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Montant demandé:</strong></td>
                                        <td>${refund.requested_amount}€</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Montant approuvé:</strong></td>
                                        <td>${refund.refund_amount ? refund.refund_amount + '€' : 'Non défini'}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de création:</strong></td>
                                        <td>${new Date(refund.created_at).toLocaleDateString('fr-FR')}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de traitement:</strong></td>
                                        <td>${refund.processed_at ? new Date(refund.processed_at).toLocaleDateString('fr-FR') : 'Non traité'}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Informations client</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nom:</strong></td>
                                        <td>${user.name}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>${user.email}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Commande:</strong></td>
                                        <td><a href="/admin/order/details/${order.id}">#${order.id}</a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Raison:</strong></td>
                                        <td>${refund.getReasonTypeText ? refund.getReasonTypeText() : refund.reason_type}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="mb-3">Articles concernés</h6>
                                <div class="bg-light p-3 rounded">
                                    ${itemsHtml || '<p class="text-muted mb-0">Aucun article trouvé</p>'}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="mb-3">Raison du remboursement</h6>
                                <div class="alert alert-light">
                                    <strong>Type:</strong> ${refund.getReasonTypeText ? refund.getReasonTypeText() : refund.reason_type}<br>
                                    <strong>Détails:</strong> ${refund.reason_details}
                                </div>
                            </div>
                        </div>
                    `);
                } else {
                    $('#refundDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Impossible de charger les détails du remboursement.
                        </div>
                    `);
                }
            },
            error: function() {
                $('#refundDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Une erreur est survenue lors du chargement des détails.
                    </div>
                `);
            }
        });
    });

    // Process refund (Stripe)
    $('.process-refund').click(function() {
        const refundId = $(this).data('refund-id');

        Swal.fire({
            title: 'Traiter le remboursement?',
            text: "Cette action déclenchera le remboursement via Stripe.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Traiter',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                processQuickAction(refundId, 'process');
            }
        });
    });

    // Complete refund
    $('.complete-refund').click(function() {
        const refundId = $(this).data('refund-id');

        Swal.fire({
            title: 'Marquer comme terminé?',
            text: "Cette action marquera le remboursement comme terminé.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Terminer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                processQuickAction(refundId, 'complete');
            }
        });
    });

    // Cancel refund
    $('.cancel-refund').click(function() {
        const refundId = $(this).data('refund-id');

        Swal.fire({
            title: 'Annuler la demande?',
            text: "Cette action annulera la demande de remboursement.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Annuler',
            cancelButtonText: 'Non'
        }).then((result) => {
            if (result.isConfirmed) {
                processQuickAction(refundId, 'cancel');
            }
        });
    });

    // Copy refund ID
    $('.copy-refund-id').click(function(e) {
        e.preventDefault();
        const refundId = $(this).data('refund-id');

        navigator.clipboard.writeText(refundId).then(() => {
            const originalText = $(this).find('span').text();
            $(this).find('span').text('Copié!');

            setTimeout(() => {
                $(this).find('span').text(originalText);
            }, 2000);
        });
    });

    // Export button
    $('#exportBtn').click(function() {
        Swal.fire({
            title: 'Exporter les données',
            text: 'Choisissez le format d\'export',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'CSV',
            denyButtonText: 'Excel',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("admin.refund.export") }}?format=csv';
            } else if (result.isDenied) {
                window.location.href = '{{ route("admin.refund.export") }}?format=excel';
            }
        });
    });

    // Handle refund action form submission
    $('#refundActionForm').submit(function(e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitBtn = $('#modalSubmitBtn');
        const action = $('#refundAction').val();

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Traitement...');

        $.ajax({
            url: '{{ route("admin.refund.process") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#refundActionModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.error || 'Une erreur est survenue'
                    });
                    submitBtn.prop('disabled', false);
                    resetSubmitButton(action, submitBtn);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: errorMessage
                });
                submitBtn.prop('disabled', false);
                resetSubmitButton(action, submitBtn);
            }
        });
    });
});

// Delete refund
$(document).on('click', '.delete-refund', function(e) {
    e.preventDefault();
    const refundId = $(this).data('refund-id');
    const status = $(this).data('refund-status');

    Swal.fire({
        title: 'Supprimer la demande?',
        html: `
            <p>Vous êtes sur le point de supprimer cette demande de remboursement.</p>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Attention:</strong> Cette action est irréversible.
                Seules les demandes annulées ou rejetées peuvent être supprimées.
            </div>
            <p>Statut actuel: <strong>${status}</strong></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/refund/' + refundId + '/delete',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Supprimé!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.error
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur est survenue';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorMessage
                    });
                }
            });
        }
    });
});
</script>
@endpush
