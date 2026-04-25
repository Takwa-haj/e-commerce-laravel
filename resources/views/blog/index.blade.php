@extends('layouts.app')

@section('content')
<style>
    :root {
        /* ===== Brand Colors ===== */
        --primary: #E8B7B1;        /* Luxury Blush Accent */
        --primary-hover: #DFA39C;   /* Primary Button Hover */
        --secondary: #D8C3A5;       /* Warm Nude / Secondary Button */
        --secondary-hover: #CBB394; /* Secondary Button Hover */

        /* ===== Backgrounds ===== */
        --bg-main: #F7F3EE;         /* Main Background - Soft Cream */
        --bg-soft: #EFE6DC;          /* Section Background */
        --bg-light: #F2E9E4;         /* Alternate Section BG */
        --surface: #FFFFFF;           /* Card / Product Boxes */

        /* ===== Text ===== */
        --text-main: #3E3A37;         /* Primary Text - Luxury Dark */
        --text-secondary: #7A6F66;    /* Secondary Text - Soft Taupe */
        --text-muted: #A89F98;        /* Muted Text / Labels */
        --text-white: #FFFFFF;         /* White Text */

        /* ===== Borders ===== */
        --border-soft: #E6DDD4;        /* Divider / Borders */
        --border-light: #EFE6DC;       /* Light border */
        --divider: #E6DDD4;             /* Divider color */

        /* ===== Status ===== */
        --success-bg: #A8C3A0;          /* Success Message */
        --success-text: #3E3A37;        /* Dark text on success */

        --error-bg: #D88C8C;            /* Error Message */
        --error-text: #3E3A37;          /* Dark text on error */

        --warning-bg: #E5D3B3;           /* Warm Champagne Glow */
        --warning-text: #3E3A37;         /* Dark text on warning */

        --info-bg: #D9CFC6;              /* Info Highlight */
        --info-text: #3E3A37;            /* Dark text on info */

        /* ===== Decorative Highlights ===== */
        --gold-highlight: #C6A77D;       /* Soft Gold Highlight */
        --champagne-glow: #E5D3B3;        /* Warm Champagne Glow */
        --sale-badge: #E8B7B1;            /* Sale Badge Accent */

        /* ===== Effects ===== */
        --card-shadow: rgba(62, 58, 55, 0.08);  /* Based on Luxury Dark */
        --hover-overlay: rgba(232, 183, 177, 0.35); /* Primary with opacity */
    }

    /* Blog Hero Section */
    .blog-hero {
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-soft) 100%);
        padding: 80px 0;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }

    .blog-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: var(--primary);
        opacity: 0.1;
        filter: blur(60px);
    }

    .blog-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: var(--gold-highlight);
        opacity: 0.1;
        filter: blur(50px);
    }

    .blog-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .blog-hero h1 {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        letter-spacing: -0.02em;
    }

    .blog-hero h1 span {
        color: var(--primary);
        position: relative;
        display: inline-block;
    }

    .blog-hero h1 span::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background: var(--primary);
        opacity: 0.3;
        z-index: -1;
    }

    .blog-hero p {
        font-size: 1.25rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .blog-search-form {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }

    .blog-search-form input {
        width: 100%;
        padding: 16px 24px;
        border: 2px solid var(--border-soft);
        border-radius: 50px;
        font-size: 1rem;
        background: var(--surface);
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .blog-search-form input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px var(--hover-overlay);
    }

    .blog-search-form button {
        position: absolute;
        right: 8px;
        top: 8px;
        background: var(--primary);
        border: none;
        color: var(--text-white);
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .blog-search-form button:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    /* Blog Categories */
    .blog-categories {
        margin-bottom: 40px;
    }

    .categories-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        padding: 0;
        list-style: none;
    }

    .category-item {
        margin: 0;
    }

    .category-link {
        display: inline-block;
        padding: 8px 20px;
        background: var(--surface);
        border: 1px solid var(--border-soft);
        border-radius: 30px;
        color: var(--text-secondary);
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .category-link:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--card-shadow);
    }

    .category-link.active {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-white);
    }

    /* Blog Grid */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }

    @media (max-width: 992px) {
        .blog-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .blog-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Blog Card */
    .blog-card {
        background: var(--surface);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px var(--card-shadow);
    }

    .blog-card-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/9;
    }

    .blog-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-card:hover .blog-card-image img {
        transform: scale(1.05);
    }

    .blog-card-category {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--primary);
        color: var(--text-white);
        padding: 5px 15px;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        z-index: 2;
    }

    .blog-card-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .blog-meta i {
        color: var(--primary);
        margin-right: 5px;
    }

    .blog-meta span {
        display: flex;
        align-items: center;
    }

    .blog-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .blog-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .blog-title a:hover {
        color: var(--primary);
    }

    .blog-excerpt {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .blog-read-more {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: auto;
    }

    .blog-read-more:hover {
        color: var(--primary-hover);
        gap: 12px;
    }

    .blog-read-more i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .blog-read-more:hover i {
        transform: translateX(4px);
    }

    /* Featured Post */
    .featured-post {
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-soft) 100%);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 60px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
        border: 1px solid var(--border-soft);
    }

    @media (max-width: 768px) {
        .featured-post {
            grid-template-columns: 1fr;
            padding: 30px;
        }
    }

    .featured-post-image {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px var(--card-shadow);
    }

    .featured-post-image img {
        width: 100%;
        height: auto;
        transition: transform 0.5s ease;
    }

    .featured-post:hover .featured-post-image img {
        transform: scale(1.02);
    }

    .featured-badge {
        display: inline-block;
        background: var(--gold-highlight);
        color: var(--text-white);
        padding: 5px 15px;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .featured-post-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .featured-post-title a {
        color: inherit;
        text-decoration: none;
    }

    .featured-post-title a:hover {
        color: var(--primary);
    }

    .featured-post-excerpt {
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 25px;
        font-size: 1.1rem;
    }

    .featured-post-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        color: var(--text-muted);
    }

    .featured-post-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .featured-post-author img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .featured-post-author span {
        font-weight: 500;
        color: var(--text-main);
    }

    .featured-post-date {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .featured-post-date i {
        color: var(--primary);
    }

    /* Sidebar */
    .blog-sidebar {
        background: var(--surface);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 5px 20px var(--card-shadow);
        position: sticky;
        top: 100px;
    }

    .sidebar-widget {
        margin-bottom: 40px;
    }

    .sidebar-widget:last-child {
        margin-bottom: 0;
    }

    .widget-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-soft);
        position: relative;
    }

    .widget-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: var(--primary);
    }

    /* Recent Posts Widget */
    .recent-posts-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recent-post-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .recent-post-item:last-child {
        margin-bottom: 0;
    }

    .recent-post-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .recent-post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .recent-post-content {
        flex-grow: 1;
    }

    .recent-post-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .recent-post-title a {
        color: var(--text-main);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .recent-post-title a:hover {
        color: var(--primary);
    }

    .recent-post-date {
        font-size: 0.875rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .recent-post-date i {
        color: var(--primary);
        font-size: 12px;
    }

    /* Categories Widget */
    .categories-widget-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .categories-widget-item {
        margin-bottom: 12px;
    }

    .categories-widget-item:last-child {
        margin-bottom: 0;
    }

    .categories-widget-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-secondary);
        text-decoration: none;
        padding: 8px 0;
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--border-soft);
    }

    .categories-widget-link:hover {
        color: var(--primary);
        padding-left: 5px;
    }

    .categories-widget-link span {
        background: var(--bg-light);
        padding: 2px 8px;
        border-radius: 15px;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Tags Widget */
    .tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .tag-link {
        display: inline-block;
        padding: 6px 15px;
        background: var(--bg-light);
        border: 1px solid var(--border-soft);
        border-radius: 25px;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .tag-link:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-white);
        transform: translateY(-2px);
    }

    /* Newsletter Widget */
    .newsletter-widget {
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-soft) 100%);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
    }

    .newsletter-widget h4 {
        color: var(--text-main);
        font-weight: 600;
        margin-bottom: 10px;
    }

    .newsletter-widget p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 20px;
    }

    .newsletter-form input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-soft);
        border-radius: 8px;
        margin-bottom: 12px;
        background: var(--surface);
        color: var(--text-main);
    }

    .newsletter-form input:focus {
        border-color: var(--primary);
        outline: none;
    }

    .newsletter-form button {
        width: 100%;
        padding: 12px;
        background: var(--primary);
        border: none;
        border-radius: 8px;
        color: var(--text-white);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .newsletter-form button:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    /* Pagination */
    .blog-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .pagination-item {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--surface);
        border: 1px solid var(--border-soft);
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination-item:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-white);
    }

    .pagination-item.active {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--text-white);
    }

    /* Loading Spinner */
    .blog-loading {
        text-align: center;
        padding: 50px 0;
    }

    .blog-spinner {
        width: 50px;
        height: 50px;
        border: 3px solid var(--border-soft);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Empty State */
    .blog-empty {
        text-align: center;
        padding: 80px 20px;
        background: var(--surface);
        border-radius: 16px;
        box-shadow: 0 5px 20px var(--card-shadow);
    }

    .blog-empty-icon {
        font-size: 80px;
        color: var(--text-muted);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .blog-empty h3 {
        font-size: 1.75rem;
        color: var(--text-main);
        margin-bottom: 10px;
    }

    .blog-empty p {
        color: var(--text-secondary);
        margin-bottom: 30px;
    }

    .blog-empty-btn {
        display: inline-block;
        padding: 12px 30px;
        background: var(--primary);
        color: var(--text-white);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .blog-empty-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }
</style>

<main>
    <!-- Blog Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <div class="blog-hero-content">
                <h1>Le <span>Blog</span> Bijoux</h1>
                <p>Découvrez nos articles, conseils et inspirations sur le monde des bijoux et de la mode</p>

                <form class="blog-search-form" action="{{ route('blog.search') }}" method="GET">
                    <input type="text" name="q" placeholder="Rechercher un article..." value="{{ request('q') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Categories -->
                @if(isset($categories) && $categories->count() > 0)
                <div class="blog-categories">
                    <ul class="categories-list">
                        <li class="category-item">
                            <a href="{{ route('blog.index') }}" class="category-link {{ !request('category') ? 'active' : '' }}">
                                Tous
                            </a>
                        </li>
                        @foreach($categories as $category)
                        <li class="category-item">
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                               class="category-link {{ request('category') == $category->slug ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Featured Post -->
                @if(isset($featuredPost))
                <div class="featured-post">
                    <div class="featured-post-image">
                        <img src="{{ asset('uploads/blog/' . $featuredPost->image) }}" alt="{{ $featuredPost->title }}">
                    </div>
                    <div class="featured-post-content">
                        <span class="featured-badge">À la une</span>
                        <h2 class="featured-post-title">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}">{{ $featuredPost->title }}</a>
                        </h2>
                        <div class="featured-post-excerpt">
                            {{ Str::limit($featuredPost->excerpt ?? $featuredPost->content, 200) }}
                        </div>
                        <div class="featured-post-meta">
                            <div class="featured-post-author">
                                @if($featuredPost->author && $featuredPost->author->avatar)
                                    <img src="{{ asset('uploads/avatars/' . $featuredPost->author->avatar) }}" alt="{{ $featuredPost->author->name }}">
                                @else
                                    <img src="{{ asset('assets/images/avatar-placeholder.jpg') }}" alt="{{ $featuredPost->author->name ?? 'Auteur' }}">
                                @endif
                                <span>{{ $featuredPost->author->name ?? 'Admin' }}</span>
                            </div>
                            <div class="featured-post-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ $featuredPost->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="blog-read-more">
                            Lire l'article <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Blog Grid -->
                @if(isset($posts) && $posts->count() > 0)
                <div class="blog-grid">
                    @foreach($posts as $post)
                    <div class="blog-card">
                        <div class="blog-card-image">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img src="{{ asset('uploads/blog/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}">
                            </a>
                            @if($post->category)
                            <span class="blog-card-category">{{ $post->category->name }}</span>
                            @endif
                        </div>
                        <div class="blog-card-content">
                            <div class="blog-meta">
                                <span><i class="far fa-user"></i> {{ $post->author->name ?? 'Admin' }}</span>
                                <span><i class="far fa-calendar"></i> {{ $post->created_at->format('d M Y') }}</span>
                                <span><i class="far fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
                            </div>
                            <h3 class="blog-title">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <div class="blog-excerpt">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}
                            </div>
                            <a href="{{ route('blog.show', $post->slug) }}" class="blog-read-more">
                                Lire la suite <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($posts, 'links'))
                <div class="blog-pagination">
                    {{ $posts->links() }}
                </div>
                @endif

                @else
                <!-- Empty State -->
                <div class="blog-empty">
                    <div class="blog-empty-icon">
                        <i class="far fa-newspaper"></i>
                    </div>
                    <h3>Aucun article trouvé</h3>
                    <p>Nous n'avons pas encore publié d'articles dans cette catégorie. Revenez bientôt !</p>
                    <a href="{{ route('blog.index') }}" class="blog-empty-btn">
                        Voir tous les articles
                    </a>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <!-- Recent Posts Widget -->
                    @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Articles Récents</h3>
                        <ul class="recent-posts-list">
                            @foreach($recentPosts as $recent)
                            <li class="recent-post-item">
                                <div class="recent-post-image">
                                    <a href="{{ route('blog.show', $recent->slug) }}">
                                        <img src="{{ asset('uploads/blog/' . ($recent->image ?? 'default.jpg')) }}" alt="{{ $recent->title }}">
                                    </a>
                                </div>
                                <div class="recent-post-content">
                                    <h4 class="recent-post-title">
                                        <a href="{{ route('blog.show', $recent->slug) }}">{{ $recent->title }}</a>
                                    </h4>
                                    <div class="recent-post-date">
                                        <i class="far fa-calendar-alt"></i> {{ $recent->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Categories Widget -->
                    @if(isset($sidebarCategories) && $sidebarCategories->count() > 0)
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Catégories</h3>
                        <ul class="categories-widget-list">
                            @foreach($sidebarCategories as $category)
                            <li class="categories-widget-item">
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="categories-widget-link">
                                    {{ $category->name }}
                                    <span>{{ $category->posts_count ?? $category->posts->count() }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Tags Widget -->
                    @if(isset($tags) && $tags->count() > 0)
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Tags Populaires</h3>
                        <div class="tags-cloud">
                            @foreach($tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="tag-link">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Newsletter Widget -->
                    <div class="sidebar-widget">
                        <div class="newsletter-widget">
                            <h4>Newsletter</h4>
                            <p>Recevez nos derniers articles directement dans votre boîte mail</p>
                            <form class="newsletter-form" action="" method="POST">
                                @csrf
                                <input type="email" name="email" placeholder="Votre email" required>
                                <button type="submit">S'abonner</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for category links
        const categoryLinks = document.querySelectorAll('.category-link');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // The actual navigation is handled by the href
                // This just adds a small visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });

        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = this.querySelector('input[type="email"]').value;
                const button = this.querySelector('button');
                const originalText = button.textContent;

                // Show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inscription...';

                // Simulate AJAX request (replace with actual endpoint)
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-check"></i> Inscrit !';
                    button.style.background = 'var(--success-bg)';
                    button.style.color = 'var(--success-text)';

                    // Reset after 3 seconds
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                        button.style.background = 'var(--primary)';
                        button.style.color = 'var(--text-white)';
                        newsletterForm.reset();

                        // Show success message
                        alert('Merci de votre inscription à notre newsletter !');
                    }, 3000);
                }, 1500);
            });
        }
    });
</script>
@endpush
