@extends('layouts.app')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mes retours</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-3">Mes Demandes de Remboursement</h1>
                <p class="text-muted">Suivez l'état de vos demandes de remboursement</p>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-undo-alt fs-4 me-2 text-primary"></i>
                                <h5 class="mb-0">Historique des remboursements</h5>
                            </div>
                            @if($refunds->count() > 0)
                                <span class="badge bg-primary">{{ $refunds->count() }} demande(s)</span>
                            @endif
                        </div>
                    </div>
                    <div class="account-card-body">
                        @if($refunds->count() > 0)
                            <div class="refunds-list">
                                @foreach($refunds as $refund)
                                    <div class="refund-item">
                                        <div class="refund-header">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0 me-3">Demande #{{ $refund->id }}</h6>
                                                        <span class="refund-status status-{{ $refund->status }}">
                                                            {{ $refund->getStatusLabel() ?? $refund->status }}
                                                        </span>
                                                    </div>
                                                    <p class="mb-2">
                                                        <a href="{{ route('user.order.details', $refund->order_id) }}" class="text-decoration-none">
                                                            <i class="fas fa-receipt me-1"></i>Commande #{{ $refund->order_id }}
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="refund-amount text-end">
                                                    <small class="text-muted d-block">Montant demandé</small>
                                                    <span class="fw-bold text-primary">{{ number_format($refund->requested_amount, 2) }}€</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="refund-details">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="refund-reason mb-3">
                                                        <small class="text-muted d-block mb-1">Raison</small>
                                                        <span class="badge bg-light text-dark mb-2">{{ $refund->getReasonTypeText() ?? $refund->reason_type }}</span>
                                                        <p class="mb-0 small">{{ Str::limit($refund->reason_details, 120) }}</p>
                                                    </div>

                                                    @if($refund->admin_notes)
                                                        <div class="admin-note">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="fas fa-comment me-1"></i>Note de l'administrateur
                                                            </small>
                                                            <div class="bg-light p-2 rounded small">
                                                                {{ $refund->admin_notes }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Affected Products -->
                                                    @if(isset($refund->orderItems) && $refund->orderItems->count() > 0)
                                                        <div class="refund-products mt-3">
                                                            <small class="text-muted d-block mb-2">Articles concernés</small>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @foreach($refund->orderItems as $item)
                                                                    @if($item->product)
                                                                        <div class="product-badge">
                                                                            <img src="{{ asset('uploads/products/thumbnails/' . ($item->product->image ?? 'default.jpg')) }}"
                                                                                 alt="{{ $item->product->name }}"
                                                                                 class="product-thumb">
                                                                            <span class="product-name">{{ Str::limit($item->product->name, 20) }}</span>
                                                                            <span class="product-qty">x{{ $item->pivot->quantity ?? $item->quantity }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="refund-meta">
                                                        <div class="meta-item">
                                                            <small class="text-muted">Soumis le</small>
                                                            <p class="mb-1">{{ $refund->created_at->format('d/m/Y') }}</p>
                                                            <small class="text-muted">{{ $refund->created_at->format('H:i') }}</small>
                                                        </div>

                                                        @if($refund->processed_at)
                                                            <div class="meta-item">
                                                                <small class="text-muted">Traité le</small>
                                                                <p class="mb-1">{{ $refund->processed_at->format('d/m/Y') }}</p>
                                                            </div>
                                                        @endif

                                                        @if($refund->refund_amount)
                                                            <div class="meta-item">
                                                                <small class="text-muted">Montant remboursé</small>
                                                                <p class="fw-bold text-success mb-0">{{ number_format($refund->refund_amount, 2) }}€</p>
                                                            </div>
                                                        @endif

                                                        <div class="refund-actions mt-3">
                                                            <a href="{{ route('user.refund.details', $refund->id) }}"
                                                               class="btn btn-sm btn-outline-primary w-100 mb-2">
                                                                <i class="fas fa-eye me-1"></i>Voir détails
                                                            </a>

                                                            @if($refund->status == 'pending' || $refund->status == 'under_review')
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-danger w-100 cancel-refund"
                                                                        data-refund-id="{{ $refund->id }}">
                                                                    <i class="fas fa-times-circle me-1"></i>Annuler la demande
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        @if(method_exists($refund, 'progress_percentage'))
                                        <div class="refund-progress mt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Progression</small>
                                                <small class="fw-medium">{{ $refund->progress_percentage ?? 0 }}%</small>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar"
                                                     role="progressbar"
                                                     style="width: {{ $refund->progress_percentage ?? 0 }}%"
                                                     aria-valuenow="{{ $refund->progress_percentage ?? 0 }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($refunds->hasPages())
                                <div class="mt-4">
                                    {{ $refunds->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-undo-alt fa-3x text-muted"></i>
                                </div>
                                <h6 class="mb-2">Aucune demande de remboursement</h6>
                                <p class="text-muted mb-3">
                                    Vous n'avez pas encore soumis de demande de remboursement.<br>
                                    Les demandes peuvent être effectuées dans les 14 jours suivant la livraison.
                                </p>
                                <a href="{{ route('user.orders') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>Voir mes commandes
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Refund Information Card -->
                @if($refunds->count() > 0)
                    <div class="info-card mt-4">
                        <div class="d-flex">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="mb-2">Informations sur les remboursements</h6>
                                <p class="mb-1 small">Les remboursements sont traités sous 5-7 jours ouvrés après approbation.</p>
                                <p class="mb-0 small">Le délai d'apparition sur votre compte bancaire peut varier selon votre établissement (2-5 jours ouvrés supplémentaires).</p>
                            </div>
                        </div>
                    </div>
                @endif
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
                    Annuler la demande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Êtes-vous sûr de vouloir annuler cette demande de remboursement ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cette action est irréversible. Vous devrez soumettre une nouvelle demande si nécessaire.
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
/* ===== REFUNDS PAGE STYLES - MATCHING ACCOUNT-NAV ===== */
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

/* Refund Items */
.refunds-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.refund-item {
    background: white;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.refund-item:hover {
    border-color: rgba(102, 126, 234, 0.3);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.refund-header {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #F3F4F6;
}

/* Status Badges */
.refund-status {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-under_review {
    background: #DBEAFE;
    color: #1E40AF;
}

.status-approved {
    background: #D1FAE5;
    color: #065F46;
}

.status-processing {
    background: #E0E7FF;
    color: #3730A3;
}

.status-completed {
    background: #D1FAE5;
    color: #065F46;
}

.status-rejected {
    background: #FEE2E2;
    color: #991B1B;
}

.status-cancelled {
    background: #F3F4F6;
    color: #4B5563;
}

/* Refund Details */
.refund-reason {
    background: #F9FAFB;
    padding: 1rem;
    border-radius: 10px;
}

.admin-note {
    background: #FEF3C7;
    padding: 0.75rem;
    border-radius: 8px;
    border-left: 3px solid #F59E0B;
}

/* Product Badges */
.product-badge {
    display: inline-flex;
    align-items: center;
    background: white;
    border: 1px solid #F3F4F6;
    border-radius: 20px;
    padding: 0.25rem 0.75rem 0.25rem 0.25rem;
    gap: 0.5rem;
}

.product-thumb {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #E5E7EB;
}

.product-name {
    font-size: 0.75rem;
    color: #374151;
}

.product-qty {
    font-size: 0.7rem;
    color: #6B7280;
    font-weight: 600;
}

/* Meta Information */
.refund-meta {
    background: #F9FAFB;
    padding: 1rem;
    border-radius: 10px;
    height: 100%;
}

.meta-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.meta-item:last-child {
    border-bottom: none;
}

/* Progress Bar */
.progress {
    height: 6px;
    background-color: #F3F4F6;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 3px;
    transition: width 0.5s ease;
}

/* Action Buttons */
.btn-outline-primary {
    border: 1px solid rgba(102, 126, 234, 0.3);
    color: #667eea;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-danger {
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #EF4444;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-danger:hover {
    background: #EF4444;
    border-color: #EF4444;
    color: white;
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8, #6b4390);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.3);
}

/* Empty State */
.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #F9FAFB;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Info Card */
.info-card {
    background: #F9FAFB;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    padding: 1.25rem;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #667eea;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-header {
    background: linear-gradient(135deg, #F9FAFB, #FFFFFF);
    border-bottom: 1px solid #F3F4F6;
    padding: 1.25rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #F3F4F6;
    padding: 1.25rem 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .refund-item .row {
        flex-direction: column;
    }

    .refund-meta {
        margin-top: 1rem;
    }

    .refund-amount {
        margin-top: 0.5rem;
    }

    .refund-actions {
        display: flex;
        gap: 0.5rem;
    }

    .refund-actions .btn {
        flex: 1;
    }

    .product-badge {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel refund handler
    const cancelButtons = document.querySelectorAll('.cancel-refund');
    const cancelForm = document.getElementById('cancelRefundForm');
    const cancelModal = new bootstrap.Modal(document.getElementById('cancelRefundModal'));

    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const refundId = this.dataset.refundId;
            cancelForm.action = `/refund/${refundId}/cancel`; // Adjust URL as needed
            cancelModal.show();
        });
    });

    // Form submission with confirmation
    cancelForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Annulation...';

        fetch(this.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cancelModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Demande annulée',
                    text: data.message || 'Votre demande de remboursement a été annulée.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                throw new Error(data.error || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: error.message
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

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
