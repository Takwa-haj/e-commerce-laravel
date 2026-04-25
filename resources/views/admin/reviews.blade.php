@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Avis sur les Produits</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Tableau de bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Avis</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.reviews') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher des avis..." class="" name="search"
                                   value="{{ request('search') }}" tabindex="2">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>

                <div class="btn-group">
                    <a href="{{ route('admin.reviews', ['status' => 'all']) }}"
                       class="tf-button {{ request('status') == 'all' || !request('status') ? 'style-1' : 'style-2' }}">
                        Tous ({{ $totalReviews }})
                    </a>
                    <a href="{{ route('admin.reviews', ['status' => 'pending']) }}"
                       class="tf-button {{ request('status') == 'pending' ? 'style-1' : 'style-2' }}">
                        En attente ({{ $pendingReviews }})
                    </a>
                    <a href="{{ route('admin.reviews', ['status' => 'approved']) }}"
                       class="tf-button {{ request('status') == 'approved' ? 'style-1' : 'style-2' }}">
                        Approuvés ({{ $approvedReviews }})
                    </a>
                    <a href="{{ route('admin.reviews', ['status' => 'declined']) }}"
                       class="tf-button {{ request('status') == 'declined' ? 'style-1' : 'style-2' }}">
                        Refusés ({{ $declinedReviews }})
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if($reviews->count() > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produit</th>
                                <th>Client</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    @if($review->product)
                                        <div class="d-flex align-items-center">
                                            @if($review->product->image)
                                            <img src="{{ asset('uploads/products/thumbnails/' . $review->product->image) }}"
                                                 alt="{{ $review->product->name }}"
                                                 class="product-thumb me-2">
                                            @endif
                                            <div>
                                                <a href="{{ route('shop.product.details', ['product_slug' => $review->product->slug]) }}"
                                                   target="_blank" class="product-name">
                                                    {{ Str::limit($review->product->name, 30) }}
                                                </a>
                                                @if($review->product->category)
                                                <div class="text-muted text-tiny">
                                                    {{ $review->product->category->name }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-danger d-flex align-items-center">
                                            <i class="icon-trash-2 me-2"></i>
                                            <span>Produit supprimé</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="fw-medium">{{ $review->user->name }}</div>
                                        <div class="text-muted text-tiny">{{ $review->user->email }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="icon-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-comment">
                                        <p class="mb-0 comment-preview">{{ Str::limit($review->comment, 50) }}</p>
                                        @if(strlen($review->comment) > 50)
                                            <a href="javascript:void(0);" class="read-more-link"
                                               onclick="toggleFullComment({{ $review->id }})">
                                                Lire plus
                                            </a>
                                            <div class="full-comment" id="full-comment-{{ $review->id }}" style="display: none;">
                                                {{ $review->comment }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($review->status === 'approved')
                                        <span class="badge status-badge bg-success">
                                            <i class="icon-check me-1"></i>Approuvé
                                        </span>
                                    @elseif($review->status === 'declined')
                                        <span class="badge status-badge bg-danger">
                                            <i class="icon-x me-1"></i>Refusé
                                        </span>
                                    @else
                                        <span class="badge status-badge bg-warning text-dark">
                                            <i class="icon-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $review->created_at->format('d M, Y') }}</div>
                                        <div class="text-muted text-tiny">{{ $review->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="list-icon-function">
                                        <!-- View Details Button -->
                                        <button type="button" class="item text-primary"
                                                data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}"
                                                title="Voir les détails">
                                            <i class="icon-eye"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.review.delete', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="item text-danger"
                                                    title="Supprimer définitivement"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet avis ?')">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info text-center py-4">
                        <i class="icon-search display-4 text-muted mb-3"></i>
                        <h5 class="mb-2">Aucun avis trouvé</h5>
                        <p class="text-muted mb-0">Aucun avis ne correspond à votre recherche.</p>
                    </div>
                    @endif
                </div>

                @if($reviews->hasPages())
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals for each review -->
@foreach($reviews as $review)
<div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'Avis #{{ $review->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Product & Customer Info Row -->
                <div class="row mb-4">
                    <!-- Product Info -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="icon-package me-2"></i>Produit</h6>
                            </div>
                            <div class="card-body">
                                @if($review->product)
                                    <div class="d-flex">
                                        @if($review->product->image)
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('uploads/products/thumbnails/' . $review->product->image) }}"
                                                 alt="{{ $review->product->name }}"
                                                 class="modal-product-img">
                                        </div>
                                        @endif
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-2">{{ $review->product->name }}</h6>
                                            @if($review->product->category)
                                            <p class="text-muted mb-2">
                                                <i class="icon-tag me-1"></i>
                                                {{ $review->product->category->name }}
                                            </p>
                                            @endif
                                            <div class="mt-3">
                                                <a href="{{ route('shop.product.details', ['product_slug' => $review->product->slug]) }}"
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="icon-external-link me-1"></i>Voir le produit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="icon-trash-2 text-danger display-4 mb-3"></i>
                                        <p class="text-danger mb-0">Ce produit a été supprimé</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="icon-user me-2"></i>Client</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="customer-avatar me-3">
                                        <i class="icon-user-circle display-4 text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $review->user->name }}</h6>
                                        <p class="text-muted mb-1">
                                            <i class="icon-mail me-1"></i>{{ $review->user->email }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="icon-calendar me-1"></i>
                                            Inscrit le {{ $review->user->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Details -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="icon-message-square me-2"></i>Évaluation</h6>
                            </div>
                            <div class="card-body">
                                <!-- Status & Rating -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rating-display-large me-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="icon-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"
                                                   style="font-size: 28px;"></i>
                                            @endfor
                                        </div>
                                        <div>
                                            <div class="h3 mb-0">{{ $review->rating }}/5</div>
                                            <small class="text-muted">Note attribuée</small>
                                        </div>
                                    </div>
                                    <div>
                                        @if($review->status === 'approved')
                                            <span class="badge status-badge-lg bg-success">
                                                <i class="icon-check me-1"></i>Approuvé
                                            </span>
                                        @elseif($review->status === 'declined')
                                            <span class="badge status-badge-lg bg-danger">
                                                <i class="icon-x me-1"></i>Refusé
                                            </span>
                                        @else
                                            <span class="badge status-badge-lg bg-warning text-dark">
                                                <i class="icon-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="mb-3">
                                    <h6 class="mb-3">Commentaire complet :</h6>
                                    <div class="border rounded p-4 bg-light">
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>

                                <!-- Review Metadata -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm mb-0">
                                            <tr>
                                                <td width="40%"><strong><i class="icon-hash me-1"></i>ID :</strong></td>
                                                <td>{{ $review->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="icon-calendar me-1"></i>Créé le :</strong></td>
                                                <td>{{ $review->created_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm mb-0">
                                            <tr>
                                                <td width="40%"><strong><i class="icon-refresh-cw me-1"></i>Mis à jour :</strong></td>
                                                <td>{{ $review->updated_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                            @if($review->status === 'approved' && $review->approved_at)
                                            <tr>
                                                <td><strong><i class="icon-check-circle me-1"></i>Approuvé le :</strong></td>
                                                <td>{{ $review->approved_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                            @endif
                                            @if($review->status === 'declined' && $review->declined_at)
                                            <tr>
                                                <td><strong><i class="icon-x-circle me-1"></i>Refusé le :</strong></td>
                                                <td>{{ $review->declined_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <!-- Left side: Delete Button -->
                    <div>
                        <form action="{{ route('admin.review.delete', $review->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet avis ?')">
                                <i class="icon-trash-2 me-1"></i> Supprimer
                            </button>
                        </form>
                    </div>

                    <!-- Right side: Action Buttons -->
                    <div class="d-flex gap-2">
                        @if($review->status === 'pending')
                            <!-- Approve Button -->
                            <form action="{{ route('admin.review.approve', $review->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Approuver cet avis ? Il sera visible sur le site.')">
                                    <i class="icon-check me-1"></i> Approuver
                                </button>
                            </form>

                            <!-- Decline Button -->
                            <form action="{{ route('admin.review.decline', $review->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning"
                                        onclick="return confirm('Refuser cet avis ? Il ne sera pas visible sur le site.')">
                                    <i class="icon-x me-1"></i> Refuser
                                </button>
                            </form>
                        @elseif($review->status === 'declined')
                            <!-- Restore Button -->
                            <form action="{{ route('admin.review.restore', $review->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info"
                                        onclick="return confirm('Restaurer cet avis à l\'état en attente ?')">
                                    <i class="icon-refresh-cw me-1"></i> Restaurer
                                </button>
                            </form>
                        @elseif($review->status === 'approved')
                            <!-- Unapprove Button -->
                            <form action="{{ route('admin.review.unapprove', $review->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning"
                                        onclick="return confirm('Retirer l\'approbation de cet avis ? Il ne sera plus visible sur le site.')">
                                    <i class="icon-x me-1"></i> Désapprouver
                                </button>
                            </form>
                        @endif

                        <!-- Close Button -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="icon-x me-1"></i> Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Table styling */
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    .product-thumb {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .product-name {
        color: #333;
        font-weight: 500;
        text-decoration: none;
    }

    .product-name:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    .customer-info {
        min-width: 150px;
    }

    .rating-stars .icon-star {
        font-size: 14px;
        margin-right: 1px;
    }

    .rating-value {
        font-weight: 500;
        color: #333;
    }

    .comment-preview {
        max-width: 250px;
    }

    .read-more-link {
        font-size: 12px;
        cursor: pointer;
        color: #0d6efd;
        text-decoration: none;
    }

    .read-more-link:hover {
        text-decoration: underline;
    }

    .full-comment {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px;
        margin-top: 5px;
        font-size: 14px;
    }

    .status-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
    }

    .status-badge-lg {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
    }

    .date-info {
        min-width: 100px;
    }

    .text-tiny {
        font-size: 12px;
    }

    /* Modal styling */
    .modal-product-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .rating-display-large .icon-star {
        margin-right: 4px;
    }

    .card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Button styling */
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #000;
    }

    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #000;
    }

    .list-icon-function {
        display: flex;
        gap: 8px;
    }

    .list-icon-function .item {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .list-icon-function .item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .list-icon-function .item.text-primary:hover {
        background-color: rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }

    .list-icon-function .item.text-danger:hover {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: #dc3545;
    }

    /* Modal footer layout */
    .modal-footer .d-flex {
        width: 100%;
    }
</style>

<script>
// Toggle full comment in table
function toggleFullComment(reviewId) {
    const fullComment = document.getElementById(`full-comment-${reviewId}`);
    const link = event.target;

    if (fullComment.style.display === 'none') {
        fullComment.style.display = 'block';
        link.textContent = 'Montrer moins';
    } else {
        fullComment.style.display = 'none';
        link.textContent = 'Lire plus';
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips for action buttons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
