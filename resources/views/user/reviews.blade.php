@extends('layouts.app')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mes avis</li>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Reviews Header -->
                <div class="reviews-header mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="reviews-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h1 class="h3 mb-1">Mes avis</h1>
                                <p class="mb-0 text-white-80">Partagez votre expérience et aidez les autres acheteurs</p>
                            </div>
                        </div>
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-light mt-3 mt-md-0">
                            <i class="fas fa-shopping-bag me-2 background: var(--surface)"></i>
                            Continuer mes achats
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                @if($reviews->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(102, 126, 234, 0.1);">
                                    <i class="fas fa-star" style="color: #667eea;"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-value">{{ number_format($averageRating, 1) }}/5</h6>
                                    <p class="stat-label">Note moyenne</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                                    <i class="fas fa-check-circle" style="color: #10B981;"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-value">{{ $approvedCount ?? $reviews->where('approved', true)->count() }}</h6>
                                    <p class="stat-label">Approuvés</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1);">
                                    <i class="fas fa-clock" style="color: #F59E0B;"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-value">{{ $pendingCount ?? $reviews->where('approved', false)->count() }}</h6>
                                    <p class="stat-label">En attente</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1);">
                                    <i class="fas fa-heart" style="color: #EF4444;"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="stat-value">{{ $helpfulCount ?? $reviews->sum('helpful_count') }}</h6>
                                    <p class="stat-label">Utiles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-comment-dots fs-4 me-2 text-primary"></i>
                                <h5 class="mb-0">Tous mes avis</h5>
                            </div>
                            @if($reviews->count() > 0)
                                <span class="badge bg-primary">{{ $reviews->total() ?? $reviews->count() }} avis</span>
                            @endif
                        </div>
                    </div>
                    <div class="account-card-body">
                        @if($reviews->count() > 0)
                            <div class="reviews-list">
                                @foreach($reviews as $review)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="d-flex align-items-start">
                                                <!-- Product Image -->
                                                <div class="review-product-image">
                                                    @if($review->product && $review->product->image)
                                                        <img src="{{ asset('uploads/products/thumbnails/' . $review->product->image) }}"
                                                             alt="{{ $review->product->name }}"
                                                             class="product-img">
                                                    @else
                                                        <div class="product-img-placeholder">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Product Info & Rating -->
                                                <div class="review-product-info flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                        <div>
                                                            @if($review->product)
                                                                <h6 class="product-title mb-1">
                                                                    <a href="{{ route('shop.product.details', $review->product->slug) }}"
                                                                       class="text-decoration-none">
                                                                        {{ $review->product->name }}
                                                                    </a>
                                                                </h6>
                                                                <div class="product-meta mb-2">
                                                                    <span class="text-muted small">
                                                                        SKU: {{ $review->product->SKU ?? 'N/A' }}
                                                                    </span>
                                                                    @if($review->product->category)
                                                                        <span class="mx-2">•</span>
                                                                        <span class="text-muted small">
                                                                            {{ $review->product->category->name }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <h6 class="product-title text-muted mb-1">
                                                                    Produit non disponible
                                                                </h6>
                                                            @endif

                                                            <!-- Rating Stars -->
                                                            <div class="rating-stars mb-2">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                                                                @endfor
                                                                <span class="rating-value ms-2 fw-bold">{{ $review->rating }}/5</span>
                                                            </div>
                                                        </div>

                                                        <!-- Status Badge & Actions -->
                                                        <div class="review-actions text-end">
                                                            <span class="review-status badge bg-{{ $review->approved ? 'success' : 'warning' }} mb-2">
                                                                {{ $review->approved ? 'Approuvé' : 'En attente de modération' }}
                                                            </span>
                                                            <div class="action-buttons">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-info"
                                                                        onclick="openReviewModal({{ $review->toJson() }})"
                                                                        title="Voir l'avis complet">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <form action="{{ route('user.review.delete', $review->id) }}"
                                                                      method="POST"
                                                                      class="d-inline"
                                                                      onsubmit="return confirmDelete(event, this)">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="btn btn-sm btn-outline-danger"
                                                                            title="Supprimer l'avis">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Review Comment Preview -->
                                                    <div class="review-comment mt-2">
                                                        <p class="comment-text mb-1">
                                                            {{ Str::limit($review->comment, 120) }}
                                                            @if(strlen($review->comment) > 120)
                                                                <button type="button"
                                                                        class="btn btn-link btn-sm p-0 ms-1 text-decoration-none"
                                                                        onclick="openReviewModal({{ $review->toJson() }})">
                                                                    Lire la suite
                                                                </button>
                                                            @endif
                                                        </p>
                                                        <div class="review-footer d-flex justify-content-between align-items-center">
                                                            <span class="review-date small text-muted">
                                                                <i class="fas fa-calendar-alt me-1"></i>
                                                                @if($review->created_at instanceof \Carbon\Carbon)
                                                                    {{ $review->created_at->format('d/m/Y') }}
                                                                @else
                                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}
                                                                @endif
                                                                <span class="mx-2">•</span>
                                                                <i class="fas fa-clock me-1"></i>
                                                                @if($review->created_at instanceof \Carbon\Carbon)
                                                                    {{ $review->created_at->format('H:i') }}
                                                                @else
                                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('H:i') }}
                                                                @endif
                                                            </span>
                                                            <span class="helpful-count small text-muted">
                                                                <i class="fas fa-thumbs-up me-1"></i>
                                                                {{ $review->helpful_count ?? 0 }} personnes ont trouvé cet avis utile
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if(method_exists($reviews, 'hasPages') && $reviews->hasPages())
                                <div class="mt-4">
                                    {{ $reviews->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="empty-state text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-star fa-4x"></i>
                                </div>
                                <h4 class="mb-3">Aucun avis pour le moment</h4>
                                <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                                    Vous n'avez pas encore donné votre avis sur nos produits.
                                    Partagez votre expérience et aidez les autres acheteurs à faire leur choix !
                                </p>
                                <div class="empty-state-actions">
                                    <a href="{{ route('user.orders') }}" class="btn btn-primary me-2">
                                        <i class="fas fa-box me-2"></i>
                                        Voir mes commandes
                                    </a>
                                    <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-shopping-bag me-2"></i>
                                        Découvrir nos produits
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips Card -->
                @if($reviews->count() > 0)
                    <div class="tips-card mt-4">
                        <div class="d-flex align-items-start">
                            <div class="tips-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="tips-content">
                                <h6 class="mb-2">Conseils pour rédiger un bon avis</h6>
                                <ul class="tips-list mb-0">
                                    <li>Soyez précis et détaillé sur votre expérience avec le produit</li>
                                    <li>Partagez des photos pour montager le produit en situation</li>
                                    <li>Indiquez les points positifs et négatifs objectivement</li>
                                    <li>Respectez la politique de modération (pas de langage abusif)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- Single Modal for Review Details -->
<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star text-warning me-2"></i>
                    Détail de l'avis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="modalContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
                <a href="#" id="modalProductLink" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Voir le produit
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== REVIEWS PAGE STYLES - DYNAMIC COLORS ===== */
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

/* Reviews Header */
.reviews-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 16px;
    padding: 1.75rem 2rem;
    color: white;
}

.reviews-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-right: 1.5rem;
}

.text-white-80 {
    color: rgba(255, 255, 255, 0.9);
}

.btn-outline-light {
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: var(--surface);
    border-color: white;
    color: var(--primary);
    transform: translateY(-1px);
}

/* Statistics Cards */
.stat-card {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    background: var(--surface);
    border-radius: 12px;
    border: 1px solid var(--border_soft);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    border-color: color-mix(in srgb, var(--primary) 30%, transparent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 15%, transparent);
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

.stat-icon i {
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text_primary);
    margin-bottom: 0.25rem;
    line-height: 1;
}

.stat-label {
    margin-bottom: 0;
    color: var(--text_secondary);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Reviews List */
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.review-item {
    padding: 1.5rem;
    background: var(--bg_soft);
    border: 1px solid var(--border_soft);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.review-item:hover {
    background: var(--surface);
    border-color: color-mix(in srgb, var(--primary) 30%, transparent);
    transform: translateX(4px);
    box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 15%, transparent);
}

/* Review Product */
.review-product-image {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border_soft);
    background: var(--surface);
}

.product-img-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    background: var(--bg_soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text_secondary);
}

.product-title {
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.product-title a {
    color: var(--text_primary);
    font-weight: 600;
}

.product-title a:hover {
    color: var(--primary);
}

/* Rating Stars */
.rating-stars i {
    font-size: 0.875rem;
}

.star-filled {
    color: var(--warning);
}

.star-empty {
    color: var(--border);
}

.rating-value {
    font-size: 0.875rem;
    color: var(--text_secondary);
}

/* Review Status */
.review-status {
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge.bg-success {
    background: var(--success) !important;
    color: white;
}

.badge.bg-warning {
    background: var(--warning) !important;
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-outline-info {
    border: 1px solid color-mix(in srgb, var(--info) 30%, transparent);
    color: var(--info);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    background: transparent;
}

.btn-outline-info:hover {
    background: var(--info);
    border-color: var(--info);
    color: white;
    transform: translateY(-1px);
}

.btn-outline-danger {
    border: 1px solid color-mix(in srgb, var(--error) 30%, transparent);
    color: var(--error);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    background: transparent;
}

.btn-outline-danger:hover {
    background: var(--error);
    border-color: var(--error);
    color: white;
    transform: translateY(-1px);
}

/* Review Comment */
.review-comment {
    background: var(--surface);
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid var(--border_soft);
}

.comment-text {
    color: var(--text_secondary);
    font-size: 0.9375rem;
    line-height: 1.6;
}

.review-footer {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--divider);
}

.helpful-count {
    display: inline-flex;
    align-items: center;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, var(--bg_soft), var(--surface));
    border-bottom: 1px solid var(--border_soft);
    padding: 1.25rem 1.5rem;
}

.modal-product-info {
    padding: 1rem;
    background: var(--bg_soft);
    border-radius: 12px;
    height: 100%;
}

.modal-product-image {
    width: 100%;
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-product-image img {
    max-width: 100%;
    max-height: 180px;
    object-fit: contain;
}

.modal-image-placeholder {
    width: 100%;
    height: 150px;
    background: var(--bg_soft);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-review-details {
    padding: 1rem;
}

.rating-display i {
    margin-right: 0.25rem;
}

.rating-display .text-warning {
    color: var(--warning) !important;
}

.rating-display .text-muted {
    color: var(--text_secondary) !important;
}

.review-full-content {
    margin-top: 1.5rem;
}

.review-full-content .bg-light {
    background: var(--bg_soft) !important;
    border: 1px solid var(--border_soft);
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--bg_soft);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: var(--text_secondary);
}

.empty-state-icon i {
    font-size: 2.5rem;
}

/* Tips Card */
.tips-card {
    background: var(--bg_soft);
    border: 1px solid var(--border_soft);
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid var(--warning);
}

.tips-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: color-mix(in srgb, var(--warning) 10%, transparent);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--warning);
    margin-right: 1rem;
    flex-shrink: 0;
}

.tips-content {
    flex: 1;
}

.tips-list {
    list-style: none;
    padding-left: 0;
}

.tips-list li {
    position: relative;
    padding-left: 1.25rem;
    margin-bottom: 0.5rem;
    color: var(--text_secondary);
    font-size: 0.875rem;
}

.tips-list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--warning);
    font-weight: bold;
}

/* Badge */
.badge.bg-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

/* Buttons */
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
    filter: brightness(1.05);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
}

.btn-outline-primary {
    border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
    color: var(--primary);
    border-radius: 8px;
    font-weight: 500;
    background: transparent;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-1px);
}

/* Alert Styles */
.alert-success {
    background: color-mix(in srgb, var(--success) 15%, transparent);
    border-color: color-mix(in srgb, var(--success) 25%, transparent);
    color: var(--success);
}

.alert-danger {
    background: var(--error_bg);
    border-color: color-mix(in srgb, var(--error) 25%, transparent);
    color: var(--error);
}

/* Text Colors */
.text-muted {
    color: var(--text_secondary) !important;
}

.text-primary {
    color: var(--primary) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .reviews-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .reviews-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .review-header .d-flex {
        flex-direction: column;
    }

    .review-product-image {
        margin-bottom: 1rem;
    }

    .review-product-info {
        margin-left: 0 !important;
        width: 100%;
    }

    .review-actions {
        width: 100%;
        margin-top: 1rem;
    }

    .action-buttons {
        justify-content: flex-start;
    }

    .modal-body .row {
        flex-direction: column;
    }

    .modal-product-info {
        margin-bottom: 1.5rem;
    }

    .stat-card {
        padding: 1rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        margin-right: 0.75rem;
    }

    .stat-value {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .review-footer {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }

    .empty-state-actions .btn {
        display: block;
        width: 100%;
        margin: 0.5rem 0;
    }

    .empty-state-actions .btn.me-2 {
        margin-right: 0 !important;
    }
}
</style>

<script>
function confirmDelete(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'Supprimer l\'avis ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}

function openReviewModal(review) {
    // Parse review data if it's a string
    if (typeof review === 'string') {
        review = JSON.parse(review);
    }

    const modalContent = document.getElementById('modalContent');
    const productLink = document.getElementById('modalProductLink');

    // Format date
    const date = new Date(review.created_at);
    const formattedDate = date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Build modal content
    let content = '';

    if (review.product) {
        content += `
            <div class="col-md-4">
                <div class="modal-product-info text-center">
                    <div class="modal-product-image mb-3">
                        ${review.product.image ?
                            `<img src="/uploads/products/${review.product.image}" alt="${review.product.name}" class="img-fluid rounded">` :
                            `<div class="modal-image-placeholder"><i class="fas fa-image fa-3x text-muted"></i></div>`
                        }
                    </div>
                    <h6 class="product-name mb-2">${review.product.name}</h6>
                    ${review.product.category ? `<p class="product-category small text-muted mb-2">${review.product.category.name}</p>` : ''}
                    <div class="product-price mb-3">
                        ${review.product.sale_price ?
                            `<span class="text-danger fw-bold">${parseFloat(review.product.sale_price).toFixed(2)}€</span>
                             <small class="text-muted ms-2"><s>${parseFloat(review.product.regular_price).toFixed(2)}€</s></small>` :
                            `<span class="fw-bold">${parseFloat(review.product.regular_price).toFixed(2)}€</span>`
                        }
                    </div>
                    <a href="/product/${review.product.slug}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-eye me-2"></i>Voir le produit
                    </a>
                </div>
            </div>
            <div class="col-md-8">
        `;
    } else {
        content += `<div class="col-12">`;
    }

    content += `
        <div class="modal-review-details">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="rating-display mb-2">
                        ${Array(5).fill().map((_, i) =>
                            `<i class="fas fa-star ${i < review.rating ? 'text-warning' : 'text-muted'} fa-lg"></i>`
                        ).join('')}
                        <span class="ms-2 fw-bold h5">${review.rating}/5</span>
                    </div>
                    <span class="badge bg-${review.approved ? 'success' : 'warning'}">
                        ${review.approved ? 'Avis approuvé' : 'En attente de modération'}
                    </span>
                </div>
                <span class="text-muted small">
                    <i class="fas fa-calendar me-1"></i>
                    ${formattedDate}
                </span>
            </div>

            <div class="review-full-content">
                <h6 class="mb-2">Votre avis :</h6>
                <div class="p-4 bg-light rounded">
                    <p class="mb-0">${review.comment}</p>
                </div>
            </div>

            ${review.helpful_count > 0 ? `
                <div class="helpful-stats mt-3">
                    <small class="text-muted">
                        <i class="fas fa-thumbs-up text-success me-1"></i>
                        ${review.helpful_count} personne(s) ont trouvé cet avis utile
                    </small>
                </div>
            ` : ''}
        </div>
    </div>`;

    modalContent.innerHTML = content;

    // Update product link
    if (review.product) {
        productLink.href = `/product/${review.product.slug}`;
        productLink.style.display = 'inline-block';
    } else {
        productLink.style.display = 'none';
    }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('reviewDetailModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
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
