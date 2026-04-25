@extends('layouts.app')

@section('content')
<style>
    .review-card {
        background: var(--surface);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        transition: all 0.3s ease;
    }

    .review-card:hover {
        box-shadow: 0 4px 20px var(--card_shadow);
        transform: translateY(-2px);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--divider);
    }

    .review-product { display: flex; align-items: center; gap: 16px; }
    .product-image { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; background: var(--bg_soft); }

    .product-info h4 { margin: 0 0 8px 0; font-size: 16px; font-weight: 600; }
    .product-info h4 a { color: var(--text_primary); text-decoration: none; }
    .product-info h4 a:hover { color: var(--primary); }
    .review-date { font-size: 13px; color: var(--text_secondary); }

    .review-rating { display: flex; align-items: center; gap: 4px; }
    .review-rating .star { color: var(--warning); font-size: 18px; }
    .review-rating .star.far { color: var(--border); }
    .review-rating .rating-text { margin-left: 8px; font-weight: 600; color: var(--text_primary); }

    .review-content { margin-bottom: 16px; }
    .review-comment { line-height: 1.6; color: var(--text_secondary); font-size: 15px; }

    .review-actions { display: flex; gap: 12px; justify-content: flex-end; }

    .btn-view, .btn-edit, .btn-delete {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-view { background: var(--bg_soft); color: var(--primary); border: 1px solid var(--border_soft); }
    .btn-view:hover { background: var(--border_soft); }
    .btn-edit { background: var(--bg_soft); color: var(--text_primary); border: 1px solid var(--border); }
    .btn-edit:hover { background: var(--border); }
    .btn-delete { background: color-mix(in srgb, var(--error) 10%, transparent); color: var(--error); border: 1px solid color-mix(in srgb, var(--error) 20%, transparent); }
    .btn-delete:hover { background: color-mix(in srgb, var(--error) 20%, transparent); }

    .review-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-approved { background: color-mix(in srgb, var(--success) 15%, transparent); color: var(--success); }
    .status-pending { background: color-mix(in srgb, var(--warning) 15%, transparent); color: var(--warning); }

    .filter-bar {
        background: var(--surface);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px var(--card_shadow);
    }

    .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }

    .filter-tab {
        padding: 10px 20px;
        border-radius: 8px;
        background: var(--bg_soft);
        color: var(--text_secondary);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .filter-tab:hover { background: var(--border_soft); }
    .filter-tab.active { background: var(--primary); color: white; border-color: var(--primary); }

    .filter-controls { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }

    .search-box { flex: 1; min-width: 300px; position: relative; }

    .search-box input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: var(--surface);
        color: var(--text_primary);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 15%, transparent);
    }

    .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text_secondary); }

    .sort-select {
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--surface);
        color: var(--text_primary);
        min-width: 200px;
        cursor: pointer;
    }

    .sort-select:focus { outline: none; border-color: var(--primary); }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--surface);
        border-radius: 12px;
        box-shadow: 0 2px 8px var(--card_shadow);
    }

    .empty-state-icon { font-size: 64px; color: var(--primary); margin-bottom: 20px; opacity: 0.6; }
    .empty-state h3 { font-size: 20px; font-weight: 600; color: var(--text_primary); margin-bottom: 12px; }
    .empty-state p { color: var(--text_secondary); max-width: 400px; margin: 0 auto 24px; line-height: 1.6; }

    .btn-primary {
        background: var(--primary);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover { filter: brightness(0.9); transform: translateY(-1px); }

    /* Modal */
    .review-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: var(--hover_overlay);
        z-index: 99999;
        overflow-y: auto;
    }

    .review-modal.show { display: block; }

    .review-modal-content {
        background-color: var(--surface);
        margin: 30px auto;
        padding: 0;
        width: 90%;
        max-width: 700px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: modalFadeIn 0.2s;
    }

    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .review-modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--divider);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg_soft);
        border-radius: 16px 16px 0 0;
    }

    .review-modal-header h3 { margin: 0; font-size: 20px; font-weight: 600; color: var(--text_primary); }
    .review-modal-close { font-size: 32px; font-weight: 500; color: var(--text_secondary); cursor: pointer; }
    .review-modal-close:hover { color: var(--error); }
    .review-modal-body { padding: 25px; max-height: 70vh; overflow-y: auto; }

    .review-modal-footer {
        padding: 20px 25px;
        border-top: 1px solid var(--divider);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background: var(--bg_soft);
        border-radius: 0 0 16px 16px;
    }

    .modal-product-info {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding: 15px;
        background: var(--bg_soft);
        border-radius: 12px;
    }

    .modal-product-img { width: 90px; height: 90px; border-radius: 10px; object-fit: cover; border: 2px solid var(--surface); }
    .modal-product-details h4 { margin: 0 0 10px 0; font-size: 18px; font-weight: 600; }
    .modal-product-details a { color: var(--text_primary); text-decoration: none; }
    .modal-product-details a:hover { color: var(--primary); }

    .modal-rating-box {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding: 15px;
        background: color-mix(in srgb, var(--warning) 10%, transparent);
        border-radius: 12px;
    }

    .modal-stars { display: flex; gap: 5px; }
    .modal-stars i { font-size: 22px; color: var(--warning); }
    .modal-stars i.far { color: var(--border); }
    .modal-rating-label { font-size: 16px; font-weight: 600; color: var(--text_primary); }

    .modal-review-text { margin-bottom: 25px; }
    .modal-review-text h4 { font-size: 16px; font-weight: 600; color: var(--text_primary); margin-bottom: 12px; }

    .modal-review-text p {
        background: var(--bg_soft);
        padding: 20px;
        border-radius: 12px;
        line-height: 1.7;
        color: var(--text_secondary);
        font-size: 15px;
        border-left: 4px solid var(--primary);
        margin: 0;
    }

    .modal-meta-info {
        display: flex;
        gap: 20px;
        color: var(--text_secondary);
        font-size: 14px;
        padding: 15px;
        background: var(--bg_soft);
        border-radius: 12px;
    }

    .modal-meta-info i { margin-right: 6px; color: var(--primary); }

    .btn-secondary {
        background: var(--text_secondary);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-secondary:hover { filter: brightness(0.9); }

    @media (max-width: 768px) {
        .review-header { flex-direction: column; gap: 16px; }
        .review-product { width: 100%; }
        .filter-controls { flex-direction: column; }
        .search-box { min-width: 100%; }
        .sort-select { width: 100%; }
        .review-modal-content { width: 95%; margin: 20px auto; }
        .modal-product-info { flex-direction: column; text-align: center; }
        .review-modal-footer { flex-direction: column; }
        .review-modal-footer button, .review-modal-footer a { width: 100%; justify-content: center; }
    }
</style>

<main class="pt-90">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                <div class="mb-4">
                    <h1 class="h2 mb-2">Mes Avis</h1>
                    <p class="text-muted">Consultez et gérez tous vos avis sur les produits</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-tabs">
                        <a href="{{ route('user.reviews.index') }}"
                           class="filter-tab {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">
                            Tous ({{ $totalReviews ?? 0 }})
                        </a>
                        <a href="{{ route('user.reviews.index', ['status' => 'approved']) }}"
                           class="filter-tab {{ request('status') == 'approved' ? 'active' : '' }}">
                            Approuvés ({{ $approvedReviews ?? 0 }})
                        </a>
                        <a href="{{ route('user.reviews.index', ['status' => 'pending']) }}"
                           class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                            En attente ({{ $pendingReviews ?? 0 }})
                        </a>
                    </div>

                    <form method="GET" action="{{ route('user.reviews.index') }}" class="filter-controls">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                            <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Note + haute</option>
                            <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Note - haute</option>
                        </select>
                    </form>
                </div>

                <!-- Reviews List -->
                @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-product">
                                    <img src="{{ asset('uploads/products/thumbnails/' . $review->product->image) }}"
                                         alt="{{ $review->product->name }}"
                                         class="product-image">
                                    <div class="product-info">
                                        <h4>
                                            <a href="{{ route('shop.product.details', ['product_slug' => $review->product->slug]) }}">
                                                {{ $review->product->name }}
                                            </a>
                                        </h4>
                                        <div class="review-date">
                                            {{ $review->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="review-status status-{{ $review->approved ? 'approved' : 'pending' }}">
                                        {{ $review->approved ? 'Approuvé' : 'En attente' }}
                                    </span>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star star"></i>
                                        @endfor
                                        <span class="rating-text">{{ $review->rating }}/5</span>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-comment">{{ Str::limit($review->comment, 150) }}</p>
                            </div>
                            <div class="review-actions">
                                <!-- SIMPLE BUTTON WITH ONCLICK -->
                                <button type="button" class="btn-view"
                                        onclick="showReviewModal(
                                            '{{ $review->id }}',
                                            '{{ addslashes($review->product->name) }}',
                                            '{{ $review->product->slug }}',
                                            '{{ $review->product->image }}',
                                            '{{ $review->rating }}',
                                            '{{ addslashes($review->comment) }}',
                                            '{{ $review->created_at->format("d/m/Y à H:i") }}',
                                            '{{ $review->updated_at->format("d/m/Y à H:i") }}',
                                            '{{ $review->approved ? "Approuvé" : "En attente" }}',
                                            '{{ $review->approved ? "approved" : "pending" }}'
                                        )">
                                    <i class="fas fa-eye me-1"></i> Détails
                                </button>
                                <a href="{{ route('user.reviews.edit', $review->id) }}" class="btn-edit">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                                <form action="{{ route('user.reviews.destroy', $review->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cet avis ?');"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $reviews->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>Aucun avis</h3>
                        <p>
                            @if(request('search') || request('status') !== 'all')
                                Aucun résultat.
                                <br>
                                <a href="{{ route('user.reviews.index') }}">Voir tous</a>
                            @else
                                Vous n'avez pas encore donné d'avis.
                            @endif
                        </p>
                        <a href="{{ route('shop.index') }}" class="btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Parcourir
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- SIMPLE MODAL - DIRECT HTML -->
<div id="reviewDetailModal" class="review-modal">
    <div class="review-modal-content">
        <div class="review-modal-header">
            <h3>Détail de l'avis</h3>
            <span class="review-modal-close" onclick="closeReviewModal()">&times;</span>
        </div>
        <div class="review-modal-body" id="modalBodyContent">
            <!-- DYNAMIC CONTENT WILL BE INSERTED HERE -->
        </div>
        <div class="review-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeReviewModal()">
                <i class="fas fa-times me-1"></i> Fermer
            </button>
            <a href="#" id="modalEditButton" class="btn-edit" style="display: inline-flex;">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
        </div>
    </div>
</div>

<script>
// GLOBAL FUNCTIONS - SIMPLE AND DIRECT
const ratingMessages = {
    1: 'Mauvais',
    2: 'Moyen',
    3: 'Bon',
    4: 'Très bon',
    5: 'Excellent'
};

function showReviewModal(id, productName, productSlug, productImage, rating, comment, date, updatedAt, status, statusClass) {
    // Generate stars HTML
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= parseInt(rating)) {
            starsHtml += '<i class="fas fa-star"></i>';
        } else {
            starsHtml += '<i class="far fa-star"></i>';
        }
    }

    // Build modal content
    const modalContent = `
        <div class="modal-product-info">
            <img src="{{ asset('uploads/products/thumbnails') }}/${productImage}"
                 alt="${productName}"
                 class="modal-product-img"
                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
            <div class="modal-product-details">
                <h4>
                    <a href="{{ route('shop.product.details', '') }}/${productSlug}" target="_blank">
                        ${productName}
                    </a>
                </h4>
                <span class="review-status status-${statusClass}">
                    ${status}
                </span>
            </div>
        </div>

        <div class="modal-rating-box">
            <div class="modal-stars">
                ${starsHtml}
            </div>
            <span class="modal-rating-label">
                ${rating}/5 - ${ratingMessages[parseInt(rating)] || ''}
            </span>
        </div>

        <div class="modal-review-text">
            <h4>
                <i class="fas fa-comment-alt"></i>
                Votre commentaire
            </h4>
            <p>${comment.replace(/\n/g, '<br>')}</p>
        </div>

        <div class="modal-meta-info">
            <div>
                <i class="fas fa-calendar-alt"></i>
                Posté: ${date}
            </div>
            <div>
                <i class="fas fa-clock"></i>
                Modifié: ${updatedAt}
            </div>
        </div>
    `;

    // Insert content
    document.getElementById('modalBodyContent').innerHTML = modalContent;

    // Set edit button link
    document.getElementById('modalEditButton').href = `/user/reviews/${id}/edit`;

    // Show modal
    const modal = document.getElementById('reviewDetailModal');
    modal.style.display = 'block';
    modal.classList.add('show');

    // Block body scroll
    document.body.style.overflow = 'hidden';
}

function closeReviewModal() {
    const modal = document.getElementById('reviewDetailModal');
    modal.style.display = 'none';
    modal.classList.remove('show');

    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reviewDetailModal');
    if (event.target === modal) {
        closeReviewModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('reviewDetailModal');
        if (modal.style.display === 'block') {
            closeReviewModal();
        }
    }
});

// Ensure modal is hidden on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reviewDetailModal').style.display = 'none';
    document.body.style.overflow = '';

    // Animate cards
    document.querySelectorAll('.review-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Search on Enter
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
});
</script>
@endsection
