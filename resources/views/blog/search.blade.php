@extends('layouts.app')

@section('content')
<!-- Search Header - Matching website gold theme -->
<section class="search-header py-5">
    <div class="container text-center">
        <div class="section-label">Recherche</div>
        <h1 class="hero-title mb-4">Résultats de recherche</h1>
        <p class="hero-subtitle mb-4">
            @if($posts->count() > 0)
                {{ $posts->total() }} article(s) trouvé(s) pour "{{ $query }}"
            @else
                Aucun résultat trouvé pour "{{ $query }}"
            @endif
        </p>

        <!-- Modern Search Form - Gold theme -->
        <div class="modern-search" style="max-width: 600px; margin: 30px auto 0;">
            <form action="{{ route('blog.search') }}" method="GET" class="search-form">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="query"
                           class="search-input"
                           placeholder="Rechercher un article..."
                           value="{{ $query }}" required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <div class="search-tags mt-3">
                    <span>Recherches rapides :</span>
                    <a href="{{ route('blog.search') }}?query=bijoux+or">Bijoux Or</a>
                    <a href="{{ route('blog.search') }}?query=mariage">Mariage</a>
                    <a href="{{ route('blog.search') }}?query=tendance+2024">Tendance 2024</a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Search Results -->
<div class="blog-grid-section">
    <div class="container">
        @if($posts->count() > 0)
            <div class="blog-grid" id="blogGrid">
                @foreach($posts as $post)
                <article class="blog-post-card"
                         data-category="{{ $post->is_featured ? 'featured' : 'recent' }}"
                         data-views="{{ $post->views }}">
                    <div class="post-card-inner">
                        <div class="post-image">
                            <a href="{{ route('blog.show', $post->slug) }}" class="image-link">
                                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                                @if($post->is_featured)
                                <span class="featured-tag">
                                    <i class="fas fa-star"></i> Vedette
                                </span>
                                @endif
                            </a>
                            <div class="post-category">Mode & Bijoux</div>
                        </div>

                        <div class="post-content">
                            <div class="post-meta">
                                <time class="post-date">
                                    <i class="far fa-clock"></i>
                                    {{ $post->published_at->diffForHumans() }}
                                </time>
                                <span class="post-read-time">
                                    <i class="far fa-bookmark"></i>
                                    {{ $post->reading_time }}
                                </span>
                            </div>

                            <h3 class="post-title">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>

                            <p class="post-excerpt">{{ Str::limit($post->excerpt, 90) }}</p>

                            <div class="post-stats">
                                <div class="stat-item">
                                    <i class="far fa-eye"></i>
                                    <span>{{ $post->views }} vues</span>
                                </div>
                                <div class="stat-item">
                                    <i class="far fa-comment"></i>
                                    <span>12 commentaires</span>
                                </div>
                            </div>

                            <div class="post-footer">
                                <div class="author">
                                    <div class="author-avatar">
                                        <img src="{{ $post->user->avatar ? asset('uploads/avatars/' . $post->user->avatar) : asset('images/default-avatar.jpg') }}"
                                             alt="{{ $post->user->name }}">
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name">{{ $post->user->name }}</span>
                                        <span class="author-role">Expert Bijoux</span>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="read-more">
                                    Lire
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="modern-pagination mt-5">
                {{ $posts->appends(['query' => $query])->links() }}
            </div>
            @endif

        @else
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-search fa-4x"></i>
                </div>
                <h3 class="mb-3">Aucun résultat trouvé</h3>
                <p class="text-muted mb-4">Aucun article ne correspond à votre recherche "{{ $query }}".</p>
                <div class="suggestions bg-white p-4 rounded-lg shadow-sm mb-4" style="max-width: 500px; margin: 0 auto;">
                    <p class="mb-3 fw-medium">Suggestions :</p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Vérifiez l'orthographe des mots</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Utilisez des termes plus généraux</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Essayez des mots-clés différents</li>
                    </ul>
                </div>
                <div class="cta-buttons mt-4">
                    <a href="{{ route('blog.index') }}" class="btn-primary me-2">
                        <i class="fas fa-home me-2"></i> Retour au blog
                    </a>
                    <button onclick="history.back()" class="btn-outline">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Related Searches -->
@if($posts->count() > 0)
<div class="container pb-5">
    <div class="related-searches bg-white p-4 rounded-lg shadow-sm">
        <h4 class="mb-4">Recherches associées</h4>
        <div class="d-flex flex-wrap gap-2">
            @php
                $relatedTerms = ['bijoux tendance', 'conseils bijoux', 'entretien bijoux', 'mode accessoires', 'colliers argent'];
            @endphp
            @foreach($relatedTerms as $term)
            <a href="{{ route('blog.search') }}?query={{ urlencode($term) }}"
               class="badge bg-light text-dark border p-2 text-decoration-none"
               style="border-radius: 20px; transition: all 0.3s ease;">
                {{ $term }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<style>
/* Search Header - Matching website theme */
.search-header {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 80px 0 60px;
    color: white;
}

.section-label {
    display: inline-block;
    background: var(--gold-primary);
    color: white;
    padding: 6px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 15px;
    font-family: 'Jost', sans-serif;
}

.hero-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* Modern Search - from index page */
.modern-search .search-input-wrapper {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 60px;
    padding: 12px 20px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.modern-search .search-input-wrapper:focus-within {
    border-color: var(--gold-primary);
    box-shadow: 0 10px 40px rgba(212, 175, 55, 0.2);
}

.modern-search .search-icon {
    color: var(--gold-primary);
    font-size: 1.2rem;
    margin-right: 15px;
}

.modern-search .search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 1rem;
    padding: 5px 0;
    background: transparent;
    font-family: 'Jost', sans-serif;
}

.modern-search .search-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--gold-primary);
    border: none;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modern-search .search-btn:hover {
    background: var(--gold-dark);
    transform: translateX(5px);
}

.modern-search .search-tags {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
}

.modern-search .search-tags a {
    padding: 5px 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.modern-search .search-tags a:hover {
    background: var(--gold-primary);
    color: white;
}

/* Blog Grid Section */
.blog-grid-section {
    background: #f8fafc;
    padding: 80px 0;
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 30px;
    padding: 60px 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.empty-state-icon {
    color: var(--gold-primary);
    opacity: 0.5;
}

.suggestions {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    background: var(--gold-primary);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    font-family: 'Jost', sans-serif;
}

.btn-primary:hover {
    background: var(--gold-dark);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
    color: white;
}

.btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 12px 30px;
    border: 2px solid var(--gold-primary);
    border-radius: 50px;
    color: var(--gold-primary);
    background: white;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-outline:hover {
    background: var(--gold-primary);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.2);
}

/* Related Searches */
.related-searches {
    border: 1px solid #e2e8f0;
    border-radius: 20px;
}

.related-searches .badge {
    transition: all 0.3s ease;
}

.related-searches .badge:hover {
    background: var(--gold-primary) !important;
    color: white !important;
    border-color: transparent !important;
    transform: translateY(-2px);
}

/* Mobile */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .cta-buttons {
        flex-direction: column;
        gap: 15px;
    }

    .cta-buttons a,
    .cta-buttons button {
        width: 100%;
        justify-content: center;
    }

    .search-header {
        padding: 60px 0 40px;
    }

    .modern-search .search-tags {
        flex-wrap: wrap;
        justify-content: center;
    }
}

/* Dark mode */
@media (prefers-color-scheme: dark) {
    .blog-grid-section {
        background: #1a1a1a;
    }

    .empty-state {
        background: #2d2d2d;
    }

    .suggestions {
        background: #2d2d2d !important;
        border-color: #404040;
    }

    .related-searches {
        background: #2d2d2d !important;
        border-color: #404040;
    }

    .related-searches h4 {
        color: #f5f5f5;
    }
}
</style>
@endsection
