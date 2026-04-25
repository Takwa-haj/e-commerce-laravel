@extends('layouts.app')

@section('content')
<!-- Blog Post Detail -->
<div class="container py-5" style="padding-top: 100px;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="blog-post-detail">
                <!-- Breadcrumb - Gold theme -->
                <nav class="breadcrumb mb-5" style="background: white; padding: 15px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                    <ol class="breadcrumb list-unstyled d-flex flex-wrap mb-0" style="gap: 10px;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home.index') }}" class="text-decoration-none" style="color: var(--gold-primary);">
                                <i class="fas fa-home me-1"></i> Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('blog.index') }}" class="text-decoration-none" style="color: var(--gold-primary);">
                                Blog
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #666;">
                            {{ Str::limit($post->title, 40) }}
                        </li>
                    </ol>
                </nav>

                <!-- Featured Image -->
                <div class="post-featured-image mb-4">
                    <img src="{{ $post->featured_image_url }}"
                         alt="{{ $post->title }}"
                         class="img-fluid rounded-lg"
                         style="width: 100%; height: 400px; object-fit: cover; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
                </div>

                <!-- Post Header -->
                <header class="post-header mb-5">
                    <!-- Post Meta - Gold accents -->
                    <div class="post-meta d-flex flex-wrap align-items-center mb-4" style="gap: 20px;">
                        <div class="post-date">
                            <i class="far fa-calendar me-2" style="color: var(--gold-primary);"></i>
                            <span style="color: #666; font-weight: 500;">{{ $post->published_at->format('d F Y') }}</span>
                        </div>
                        <div class="post-author">
                            <i class="far fa-user me-2" style="color: var(--gold-primary);"></i>
                            <span style="color: #666; font-weight: 500;">{{ $post->user->name }}</span>
                        </div>
                        <div class="post-views">
                            <i class="far fa-eye me-2" style="color: var(--gold-primary);"></i>
                            <span style="color: #666; font-weight: 500;">{{ $post->views }} vues</span>
                        </div>
                        <div class="post-read-time">
                            <i class="far fa-clock me-2" style="color: var(--gold-primary);"></i>
                            <span style="color: #666; font-weight: 500;">{{ $post->reading_time }}</span>
                        </div>
                    </div>

                    <!-- Post Title -->
                    <h1 class="post-title mb-4" style="font-size: 3rem; font-weight: 800; color: #1a1a1a; line-height: 1.2; font-family: 'Jost', sans-serif;">
                        {{ $post->title }}
                    </h1>

                    <!-- Excerpt -->
                    <div class="post-excerpt lead mb-4" style="color: #666; font-size: 1.25rem; line-height: 1.6; font-family: 'Jost', sans-serif;">
                        {{ $post->excerpt }}
                    </div>

                    <!-- Tags (if any) - Gold theme -->
                    @if($post->tags && $post->tags->count() > 0)
                    <div class="post-tags mb-4 d-flex flex-wrap" style="gap: 10px;">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                           class="badge bg-light text-dark border me-2 px-3 py-2"
                           style="border-radius: 50px; text-decoration: none; transition: all 0.3s ease; border-color: var(--gold-primary) !important; color: var(--gold-primary) !important;">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </header>

                <!-- Post Content -->
                <div class="post-content mb-5" style="font-size: 1.125rem; line-height: 1.8; color: #333; font-family: 'Jost', sans-serif;">
                    {!! $post->formatted_content !!}
                </div>

                <!-- Share Buttons - Gold theme -->
                <div class="post-share mb-5">
                    <h5 class="mb-4" style="font-weight: 700; color: #1a1a1a; font-family: 'Jost', sans-serif;">Partager cet article</h5>
                    <div class="share-buttons d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm d-flex align-items-center"
                           style="border-radius: 8px; padding: 10px 20px; border: 2px solid var(--gold-primary); color: var(--gold-primary); text-decoration: none; transition: all 0.3s ease;">
                            <i class="fab fa-facebook-f me-2"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm d-flex align-items-center"
                           style="border-radius: 8px; padding: 10px 20px; border: 2px solid var(--gold-primary); color: var(--gold-primary); text-decoration: none; transition: all 0.3s ease;">
                            <i class="fab fa-twitter me-2"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm d-flex align-items-center"
                           style="border-radius: 8px; padding: 10px 20px; border: 2px solid var(--gold-primary); color: var(--gold-primary); text-decoration: none; transition: all 0.3s ease;">
                            <i class="fab fa-linkedin-in me-2"></i> LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="author-bio mb-5" style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="author-avatar">
                                <img src="{{ $post->user->avatar ? asset('uploads/avatars/' . $post->user->avatar) : asset('images/default-avatar.jpg') }}"
                                     alt="{{ $post->user->name }}"
                                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--gold-primary);">
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-2" style="font-weight: 700; color: #1a1a1a; font-family: 'Jost', sans-serif;">{{ $post->user->name }}</h5>
                            <p class="mb-0" style="color: #666; line-height: 1.6; font-family: 'Jost', sans-serif;">
                                Auteur passionné de bijoux et d'accessoires de mode. Partage avec vous les dernières tendances et conseils pour sublimer votre style.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="related-posts mb-5">
                    <h4 class="mb-4" style="font-weight: 700; color: #1a1a1a; font-family: 'Jost', sans-serif;">Articles similaires</h4>
                    <div class="row">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none">
                                <div class="related-post-card d-flex align-items-center p-3"
                                     style="background: white; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 3px 15px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
                                    <div class="related-post-img flex-shrink-0 me-3">
                                        <img src="{{ $relatedPost->thumbnail_url }}"
                                             alt="{{ $relatedPost->title }}"
                                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px;">
                                    </div>
                                    <div class="related-post-content">
                                        <h6 class="related-post-title mb-2" style="font-weight: 600; color: #1a1a1a; font-size: 1rem; font-family: 'Jost', sans-serif;">
                                            {{ Str::limit($relatedPost->title, 60) }}
                                        </h6>
                                        <small class="d-block mb-1" style="color: #666;">
                                            {{ $relatedPost->published_at->format('d M Y') }}
                                        </small>
                                        <small class="d-block" style="color: #666;">
                                            <i class="far fa-eye me-1" style="color: var(--gold-primary);"></i> {{ $relatedPost->views }} vues
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Navigation -->
                <div class="post-navigation d-flex justify-content-between align-items-center border-top pt-4">
                    @if($prevPost)
                    <div class="prev-post">
                        <a href="{{ route('blog.show', $prevPost->slug) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-arrow-left me-3" style="color: var(--gold-primary); font-size: 1.2rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Article précédent</small>
                                    <span class="fw-medium" style="color: #1a1a1a;">{{ Str::limit($prevPost->title, 40) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    <a href="{{ route('blog.index') }}" class="btn btn-outline-primary px-4 py-2"
                       style="border-radius: 50px; border: 2px solid var(--gold-primary); color: var(--gold-primary); background: white; transition: all 0.3s ease;">
                        <i class="fas fa-list me-2"></i> Tous les articles
                    </a>

                    @if($nextPost)
                    <div class="next-post">
                        <a href="{{ route('blog.show', $nextPost->slug) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="text-end me-3">
                                    <small class="text-muted d-block">Article suivant</small>
                                    <span class="fw-medium" style="color: #1a1a1a;">{{ Str::limit($nextPost->title, 40) }}</span>
                                </div>
                                <i class="fas fa-arrow-right" style="color: var(--gold-primary); font-size: 1.2rem;"></i>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </article>
        </div>

        <!-- Sidebar - Gold theme -->
        <div class="col-lg-4">
            <div class="blog-sidebar sticky-top" style="top: 100px;">
                <!-- Search Widget -->
                <div class="sidebar-widget mb-5" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                    <h5 class="widget-title mb-4" style="font-weight: 700; color: #1a1a1a; font-size: 1.25rem; font-family: 'Jost', sans-serif;">Rechercher</h5>
                    <form action="{{ route('blog.search') }}" method="GET" class="search-form">
                        <div class="input-group" style="border: 2px solid #e5e7eb; border-radius: 50px; overflow: hidden;">
                            <input type="text" name="query" class="form-control border-0"
                                   placeholder="Rechercher..." required
                                   style="padding: 15px; font-size: 1rem; font-family: 'Jost', sans-serif;">
                            <button class="btn btn-primary border-0" type="submit"
                                    style="background: var(--gold-primary); padding: 15px 20px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recent Posts Widget -->
                <div class="sidebar-widget mb-5" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                    <h5 class="widget-title mb-4" style="font-weight: 700; color: #1a1a1a; font-size: 1.25rem; font-family: 'Jost', sans-serif;">Articles récents</h5>
                    <div class="recent-posts-list">
                        @foreach($recentPosts as $recentPost)
                        <a href="{{ route('blog.show', $recentPost->slug) }}" class="text-decoration-none">
                            <div class="recent-post-item d-flex mb-3 p-3"
                                 style="border-radius: 12px; transition: all 0.3s ease; border: 1px solid #e5e7eb; background: white;">
                                <div class="recent-post-img flex-shrink-0 me-3">
                                    <img src="{{ $recentPost->thumbnail_url }}"
                                         alt="{{ $recentPost->title }}"
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                </div>
                                <div class="recent-post-content">
                                    <h6 class="recent-post-title mb-1" style="font-weight: 600; color: #1a1a1a; font-size: 0.95rem; font-family: 'Jost', sans-serif;">
                                        {{ Str::limit($recentPost->title, 40) }}
                                    </h6>
                                    <small class="text-muted" style="color: #666 !important;">
                                        {{ $recentPost->published_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter Widget - Gold gradient -->
                <div class="sidebar-widget mb-5" style="background: var(--gold-gradient); padding: 30px; border-radius: 20px;">
                    <h5 class="widget-title mb-3 text-white" style="font-weight: 700; font-size: 1.25rem; font-family: 'Jost', sans-serif;">Newsletter</h5>
                    <p class="mb-4 text-white-80" style="opacity: 0.9; color: white !important;">Inscrivez-vous pour recevoir nos derniers articles</p>
                    <form action="#" method="POST" class="newsletter-form">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Votre email" required
                                   style="border-radius: 50px 0 0 50px; border: none; padding: 15px; font-family: 'Jost', sans-serif;">
                            <button class="btn btn-light" type="submit"
                                    style="border-radius: 0 50px 50px 0; padding: 15px 20px; background: white; color: var(--gold-primary); border: none;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section - Gold gradient -->
<section class="blog-post-cta py-5" style="background: var(--gold-gradient);">
    <div class="container text-center">
        <h3 class="mb-4" style="font-weight: 800; color: white; font-size: 2.5rem; font-family: 'Jost', sans-serif;">Vous avez aimé cet article ?</h3>
        <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.25rem; font-family: 'Jost', sans-serif;">Découvrez nos autres articles sur les bijoux et la mode</p>
        <a href="{{ route('blog.index') }}" class="btn btn-light btn-lg px-5 py-3"
           style="background: white; color: var(--gold-primary); border: none; border-radius: 50px; font-weight: 600; font-family: 'Jost', sans-serif; transition: all 0.3s ease;">
            <i class="fas fa-book-open me-2"></i> Explorer tous les articles
        </a>
    </div>
</section>

<style>
:root {
    --gold-primary: #D4AF37;
    --gold-light: #F5E7C8;
    --gold-dark: #AA8C2F;
    --gold-gradient: linear-gradient(135deg, #D4AF37 0%, #AA8C2F 100%);
}

/* Blog Post Styles */
.post-content h2 {
    font-size: 1.8rem;
    margin-top: 2.5rem;
    margin-bottom: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    font-family: 'Jost', sans-serif;
}

.post-content h3 {
    font-size: 1.5rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #1a1a1a;
    font-family: 'Jost', sans-serif;
}

.post-content p {
    margin-bottom: 1.75rem;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: 2rem 0;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.post-content ul, .post-content ol {
    margin-bottom: 1.75rem;
    padding-left: 2rem;
}

.post-content li {
    margin-bottom: 0.75rem;
    color: #333;
}

.post-content blockquote {
    border-left: 4px solid var(--gold-primary);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #666;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
}

/* Hover Effects */
.related-post-card:hover,
.recent-post-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.15) !important;
    border-color: var(--gold-primary) !important;
}

.share-buttons .btn:hover {
    background: var(--gold-primary) !important;
    color: white !important;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
}

.post-navigation .btn-outline-primary:hover {
    background: var(--gold-primary);
    color: white !important;
}

/* Responsive */
@media (max-width: 768px) {
    .post-title {
        font-size: 2rem !important;
    }

    .post-featured-image img {
        height: 250px !important;
    }

    .post-content {
        font-size: 1rem !important;
    }

    .share-buttons {
        flex-wrap: wrap;
    }

    .share-buttons .btn {
        margin-bottom: 0.5rem;
        flex: 1;
        min-width: 120px;
        justify-content: center;
    }

    .post-navigation {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }

    .post-navigation .prev-post,
    .post-navigation .next-post {
        width: 100%;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .blog-post-cta {
        background: var(--gold-gradient) !important;
    }

    .author-bio,
    .sidebar-widget,
    .breadcrumb,
    .related-post-card,
    .recent-post-item {
        background: #2d2d2d !important;
        border-color: #404040 !important;
    }

    .post-title,
    .widget-title {
        color: #f5f5f5 !important;
    }

    .post-excerpt,
    .post-content {
        color: #b0b0b0 !important;
    }

    .post-content h2,
    .post-content h3 {
        color: #f5f5f5 !important;
    }

    .post-content blockquote {
        background: #1a1a1a;
        color: #b0b0b0;
    }

    .breadcrumb-item.active {
        color: #b0b0b0 !important;
    }

    .breadcrumb-item a {
        color: var(--gold-primary) !important;
    }

    .post-meta span {
        color: #b0b0b0 !important;
    }

    .recent-post-title {
        color: #f5f5f5 !important;
    }

    .related-post-title {
        color: #f5f5f5 !important;
    }

    .recent-post-item small {
        color: #b0b0b0 !important;
    }
}
</style>

<style>
/* Override for share buttons hover */
.btn-outline-primary:hover {
    background: var(--gold-primary) !important;
    border-color: var(--gold-primary) !important;
    color: white !important;
}

/* Newsletter widget text */
.text-white-80 {
    color: rgba(255, 255, 255, 0.9);
}

/* Blog post CTA button hover */
.blog-post-cta .btn-light:hover {
    background: var(--gold-dark) !important;
    color: white !important;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
</style>
@endsection
