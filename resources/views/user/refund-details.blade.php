@extends('layouts.app')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.refunds') }}">Mes retours</a></li>
                        <li class="breadcrumb-item active">Demande #{{ $refund->id }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Status Banner -->
                <div class="status-banner status-{{ $refund->status }} mb-4">
                    <div class="d-flex align-items-center">
                        <div class="status-icon">
                            @switch($refund->status)
                                @case('pending')
                                    <i class="fas fa-clock"></i>
                                    @break
                                @case('under_review')
                                    <i class="fas fa-search"></i>
                                    @break
                                @case('approved')
                                    <i class="fas fa-check-circle"></i>
                                    @break
                                @case('processing')
                                    <i class="fas fa-sync"></i>
                                    @break
                                @case('completed')
                                    <i class="fas fa-check-double"></i>
                                    @break
                                @case('rejected')
                                    <i class="fas fa-times-circle"></i>
                                    @break
                                @case('cancelled')
                                    <i class="fas fa-ban"></i>
                                    @break
                                @default
                                    <i class="fas fa-clock"></i>
                            @endswitch
                        </div>
                        <div class="status-content">
                            <h5 class="mb-1">{{ $refund->getStatusLabel() ?? $refund->status }}</h5>
                            <p class="mb-0">
                                @if($refund->status == 'pending')
                                    Votre demande est en attente de traitement par notre équipe.
                                @elseif($refund->status == 'under_review')
                                    Notre équipe examine actuellement votre demande.
                                @elseif($refund->status == 'approved')
                                    Votre demande a été approuvée. Le remboursement sera traité sous 24-48h.
                                @elseif($refund->status == 'processing')
                                    Le remboursement est en cours de traitement.
                                @elseif($refund->status == 'completed')
                                    Votre remboursement a été effectué avec succès.
                                @elseif($refund->status == 'rejected')
                                    Votre demande n'a pas pu être acceptée. Consultez les détails ci-dessous.
                                @elseif($refund->status == 'cancelled')
                                    Cette demande a été annulée.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <!-- Refund Details Card -->
                        <div class="account-card">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Détails de la demande</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">N° de demande</span>
                                        <span class="detail-value">#{{ $refund->id }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Commande associée</span>
                                        <span class="detail-value">
                                            <a href="{{ route('user.order.details', $refund->order_id) }}" class="text-decoration-none">
                                                #{{ $refund->order_id }}
                                            </a>
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Date de demande</span>
                                        <span class="detail-value">{{ $refund->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Dernière mise à jour</span>
                                        <span class="detail-value">{{ $refund->updated_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Raison</span>
                                        <span class="detail-value">
                                            <span class="badge bg-light text-dark">
                                                {{ $refund->getReasonTypeText() ?? $refund->reason_type }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Montant demandé</span>
                                        <span class="detail-value fw-bold text-primary">{{ number_format($refund->requested_amount, 2) }}€</span>
                                    </div>
                                    @if($refund->refund_amount)
                                    <div class="detail-item">
                                        <span class="detail-label">Montant remboursé</span>
                                        <span class="detail-value fw-bold text-success">{{ number_format($refund->refund_amount, 2) }}€</span>
                                    </div>
                                    @endif
                                    @if($refund->processed_at)
                                    <div class="detail-item">
                                        <span class="detail-label">Date de traitement</span>
                                        <span class="detail-value">{{ $refund->processed_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-2">Description détaillée</h6>
                                    <div class="detail-description">
                                        {{ $refund->reason_details }}
                                    </div>
                                </div>

                                @if($refund->additional_info)
                                <div class="mt-3">
                                    <h6 class="mb-2">Informations complémentaires</h6>
                                    <div class="detail-description bg-light">
                                        {{ $refund->additional_info }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Products Card -->
                        <div class="account-card mt-4">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-box fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Articles concernés</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="products-list">
                                    @foreach($refund->orderItems as $item)
                                        <div class="product-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="product-image">
                                                    @else
                                                        <div class="product-image-placeholder">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                @if($item->product)
                                                                    <a href="{{ route('shop.product.details', $item->product->slug) }}" class="text-decoration-none">
                                                                        {{ $item->product->name }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Produit non disponible</span>
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted">SKU: {{ $item->product->SKU ?? 'N/A' }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="fw-medium">{{ number_format($item->price, 2) }}€</span>
                                                            <span class="mx-2">×</span>
                                                            <span>{{ $item->pivot->quantity ?? $item->quantity }}</span>
                                                            <div class="mt-1 fw-bold text-primary">
                                                                {{ number_format($item->price * ($item->pivot->quantity ?? $item->quantity), 2) }}€
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Card -->
                        @if(method_exists($refund, 'getTimelineSteps') && count($refund->getTimelineSteps()) > 0)
                        <div class="account-card mt-4">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Suivi de la demande</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="timeline">
                                    @foreach($refund->getTimelineSteps() as $step)
                                        <div class="timeline-item {{ $step['completed'] ? 'completed' : '' }} {{ $step['current'] ? 'current' : '' }}">
                                            <div class="timeline-marker">
                                                <i class="fas fa-{{ $step['icon'] ?? 'circle' }}"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">{{ $step['label'] }}</h6>
                                                @if($step['date'])
                                                    <span class="timeline-date">{{ $step['date']->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Admin Notes Card -->
                        @if($refund->admin_notes)
                        <div class="account-card mt-4">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-comment fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Note de l'administrateur</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="admin-message">
                                    <i class="fas fa-quote-left text-primary opacity-50"></i>
                                    <p class="mb-0">{{ $refund->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Amount Summary Card -->
                        <div class="account-card">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calculator fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Résumé</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="amount-summary">
                                    <div class="summary-item">
                                        <span class="text-muted">Montant demandé</span>
                                        <span class="fw-bold text-primary">{{ number_format($refund->requested_amount, 2) }}€</span>
                                    </div>
                                    @if($refund->refund_amount)
                                    <div class="summary-item">
                                        <span class="text-muted">Montant remboursé</span>
                                        <span class="fw-bold text-success">{{ number_format($refund->refund_amount, 2) }}€</span>
                                    </div>
                                    @endif
                                    @if($refund->stripe_refund_id)
                                    <div class="summary-item border-top mt-2 pt-2">
                                        <span class="text-muted small">Référence Stripe</span>
                                        <span class="small text-muted">{{ $refund->stripe_refund_id }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="account-card mt-4">
                            <div class="account-card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-cog fs-4 me-2 text-primary"></i>
                                    <h5 class="mb-0">Actions</h5>
                                </div>
                            </div>
                            <div class="account-card-body">
                                <div class="actions-list">
                                    <a href="{{ route('user.order.details', $refund->order_id) }}" class="action-button">
                                        <i class="fas fa-receipt"></i>
                                        <span>Voir la commande</span>
                                        <i class="fas fa-chevron-right ms-auto"></i>
                                    </a>

                                    <a href="{{ route('user.refunds') }}" class="action-button">
                                        <i class="fas fa-undo-alt"></i>
                                        <span>Retour aux demandes</span>
                                        <i class="fas fa-chevron-right ms-auto"></i>
                                    </a>

                                    @if($refund->status == 'pending' || $refund->status == 'under_review')
                                        <button type="button" class="action-button text-danger" onclick="confirmCancel({{ $refund->id }})">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Annuler la demande</span>
                                            <i class="fas fa-chevron-right ms-auto"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('home.contact') }}" class="action-button">
                                        <i class="fas fa-headset"></i>
                                        <span>Contacter le support</span>
                                        <i class="fas fa-chevron-right ms-auto"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Information Card -->
                        <div class="info-card mt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Informations utiles
                            </h6>
                            <ul class="info-list">
                                <li>
                                    <i class="fas fa-clock text-muted me-2"></i>
                                    <span>Délai de traitement : 5-7 jours ouvrés</span>
                                </li>
                                <li>
                                    <i class="fas fa-credit-card text-muted me-2"></i>
                                    <span>Remboursement sur le mode de paiement d'origine</span>
                                </li>
                                <li>
                                    <i class="fas fa-calendar text-muted me-2"></i>
                                    <span>Délai bancaire : 2-5 jours supplémentaires</span>
                                </li>
                                <li>
                                    <i class="fas fa-file-alt text-muted me-2"></i>
                                    <span>Conservez vos justificatifs</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Cancel Refund Modal -->
<div class="modal fade" id="cancelRefundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Annuler la demande #{{ $refund->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Êtes-vous sûr de vouloir annuler cette demande de remboursement ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cette action est irréversible.
                </div>
            </div>
            <div class="modal-footer">
                <form id="cancelRefundForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Non, garder
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i>Oui, annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== REFUND DETAILS STYLES - MATCHING ACCOUNT-NAV ===== */
.account-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(44, 62, 80, 0.06);
    border: 1px solid rgba(44, 62, 80, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 24px;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(44, 62, 80, 0.1);
}

.account-card-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #F9FAFB, #FFFFFF);
    border-bottom: 1px solid #F3F4F6;
}

.account-card-header h5 {
    margin: 0;
    color: #1F2937;
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
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

/* Status Banner */
.status-banner {
    border-radius: 16px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}

.status-banner.status-pending {
    background: linear-gradient(135deg, #FEF3C7, #FFFBEB);
    border-left: 4px solid #F59E0B;
}

.status-banner.status-under_review {
    background: linear-gradient(135deg, #DBEAFE, #EFF6FF);
    border-left: 4px solid #3B82F6;
}

.status-banner.status-approved {
    background: linear-gradient(135deg, #D1FAE5, #ECFDF5);
    border-left: 4px solid #10B981;
}

.status-banner.status-processing {
    background: linear-gradient(135deg, #E0E7FF, #EEF2FF);
    border-left: 4px solid #6366F1;
}

.status-banner.status-completed {
    background: linear-gradient(135deg, #D1FAE5, #ECFDF5);
    border-left: 4px solid #10B981;
}

.status-banner.status-rejected {
    background: linear-gradient(135deg, #FEE2E2, #FEF2F2);
    border-left: 4px solid #EF4444;
}

.status-banner.status-cancelled {
    background: linear-gradient(135deg, #F3F4F6, #F9FAFB);
    border-left: 4px solid #6B7280;
}

.status-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.25rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.status-pending .status-icon { color: #F59E0B; }
.status-under_review .status-icon { color: #3B82F6; }
.status-approved .status-icon { color: #10B981; }
.status-processing .status-icon { color: #6366F1; }
.status-completed .status-icon { color: #10B981; }
.status-rejected .status-icon { color: #EF4444; }
.status-cancelled .status-icon { color: #6B7280; }

.status-content {
    flex: 1;
}

.status-content h5 {
    color: #1F2937;
    font-weight: 600;
}

.status-content p {
    color: #4B5563;
    font-size: 0.9375rem;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.75rem;
    color: #6B7280;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-size: 1rem;
    color: #1F2937;
}

.detail-description {
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 10px;
    border: 1px solid #F3F4F6;
    color: #4B5563;
    line-height: 1.6;
}

/* Products */
.products-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.product-item {
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 10px;
    border: 1px solid #F3F4F6;
    transition: all 0.2s ease;
}

.product-item:hover {
    background: white;
    border-color: rgba(102, 126, 234, 0.3);
}

.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #F3F4F6;
    background: white;
}

.product-image-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    background: #F3F4F6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9CA3AF;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #F3F4F6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: white;
    border: 2px solid #F3F4F6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    font-size: 0.875rem;
    z-index: 1;
}

.timeline-item.completed .timeline-marker {
    background: #10B981;
    border-color: #10B981;
    color: white;
}

.timeline-item.current .timeline-marker {
    background: white;
    border-color: #667eea;
    color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.timeline-content {
    padding-left: 1rem;
}

.timeline-title {
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #1F2937;
}

.timeline-date {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Admin Message */
.admin-message {
    padding: 1.25rem;
    background: #FEF3C7;
    border-radius: 10px;
    border-left: 4px solid #F59E0B;
    position: relative;
    color: #92400E;
    line-height: 1.6;
}

/* Amount Summary */
.amount-summary {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

/* Actions */
.actions-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-button {
    display: flex;
    align-items: center;
    padding: 0.875rem 1rem;
    background: #F9FAFB;
    border: 1px solid #F3F4F6;
    border-radius: 10px;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s ease;
    width: 100%;
    text-align: left;
}

.action-button:hover {
    background: white;
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateX(4px);
}

.action-button i:first-child {
    width: 20px;
    color: #667eea;
    margin-right: 0.75rem;
}

.action-button.text-danger i:first-child {
    color: #EF4444;
}

/* Info Card */
.info-card {
    background: #F9FAFB;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    padding: 1.25rem;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    display: flex;
    align-items: flex-start;
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #4B5563;
}

.info-list li i {
    margin-top: 0.125rem;
}

/* Responsive */
@media (max-width: 768px) {
    .status-banner .d-flex {
        flex-direction: column;
        text-align: center;
    }

    .status-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .product-item .d-flex {
        flex-direction: column;
        align-items: flex-start;
    }

    .product-image,
    .product-image-placeholder {
        margin-bottom: 1rem;
    }

    .action-button:hover {
        transform: none;
    }
}
</style>

<script>
function confirmCancel(refundId) {
    Swal.fire({
        title: 'Annuler la demande ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, annuler',
        cancelButtonText: 'Non, garder'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('cancelRefundForm');
            form.action = `/refund/${refundId}/cancel`;
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection
