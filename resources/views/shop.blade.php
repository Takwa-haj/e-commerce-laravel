@extends('layouts.app')
@section('content')
    <style>
        

        /* CSS amélioré et organisé */
        .brand-list li,
        .category-list li,
        .size-list li,
        .color-list li {
            line-height: 40px;
            transition: background-color 0.2s ease;
        }

        .brand-list li:hover,
        .category-list li:hover,
        .size-list li:hover,
        .color-list li:hover {
            background-color: var(--hover-overlay);
        }

        .brand-list li .chk-brand,
        .category-list li .chk-category,
        .size-list li .chk-size,
        .color-list li .chk-color {
            width: 1.1rem;
            height: 1.1rem;
            color: var(--text-secondary);
            border: 2px solid currentColor;
            border-radius: 3px;
            margin-right: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .brand-list li .chk-brand:checked,
        .category-list li .chk-category:checked,
        .size-list li .chk-size:checked,
        .color-list li .chk-color:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .filled-heart {
            color: var(--error-bg);
            animation: heartBeat 0.3s ease;
        }

        .menu-link {
            cursor: pointer;
            padding: 0.25rem 0;
            display: inline-block;
            width: calc(100% - 3rem);
            color: var(--text-main);
        }

        .menu-link:hover {
            color: var(--primary);
        }

        .list-item {
            border-bottom: 1px solid var(--border-soft);
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .accordion-button {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.75rem 0;
            color: var(--text-main);
            background-color: transparent;
        }

        .accordion-button:not(.collapsed) {
            color: var(--primary);
        }

        .accordion-button__icon {
            transition: transform 0.3s ease;
            fill: var(--text-secondary);
        }

        .accordion-button:not(.collapsed) .accordion-button__icon {
            transform: rotate(180deg);
        }

        .product-card-wrapper {
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .product-card-wrapper:hover {
            transform: translateY(-5px);
        }

        .pc__img-wrapper {
            overflow: hidden;
            border-radius: 8px;
            background: var(--bg-soft);
        }

        .pc__img {
            transition: transform 0.5s ease;
        }

        .pc__img-wrapper:hover .pc__img {
            transform: scale(1.05);
        }

        .pc__atc {
            width: calc(100% - 2rem);
            left: 1rem;
            background: var(--primary) !important;
            color: var(--text-white) !important;
            padding: 0.75rem !important;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
            border-radius: 4px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            border: none;
        }

        .product-card:hover .pc__atc {
            opacity: 1;
            transform: translateY(0);
        }

        .pc__atc:hover {
            background: var(--primary-hover) !important;
        }

        .btn-warning {
            background: var(--secondary) !important;
            color: var(--text-main) !important;
            border: none;
        }

        .btn-warning:hover {
            background: var(--secondary-hover) !important;
        }

        .pc__info {
            padding: 1rem 0.5rem;
        }

        .pc__category {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pc__title a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .pc__title a:hover {
            color: var(--primary);
        }

        .price {
            font-weight: 600;
            color: var(--text-main);
            font-size: 1.125rem;
        }

        .price s {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-right: 0.5rem;
            font-weight: normal;
        }

        .review-star {
            width: 16px;
            height: 16px;
            fill: var(--text-muted);
        }

        .review-star.filled {
            fill: var(--gold-highlight);
        }

        .pc__btn-wl {
            padding: 0.5rem;
            transition: all 0.2s ease;
            border-radius: 50%;
            color: var(--text-secondary);
            background: transparent;
            border: none;
        }

        .pc__btn-wl:hover {
            background-color: var(--hover-overlay);
            transform: scale(1.1);
            color: var(--primary);
        }

        .price-range-slider {
            margin: 1rem 0;
        }

        .shop-acs__select {
            border: 1px solid var(--border-soft) !important;
            border-radius: 4px !important;
            padding: 0.5rem 2rem 0.5rem 0.75rem !important;
            background-color: var(--surface);
            color: var(--text-main);
            transition: border-color 0.2s ease;
        }

        .shop-acs__select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.2rem var(--hover-overlay);
            outline: none;
        }

        .btn-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .btn-link:hover {
            color: var(--primary);
        }

        .btn-link_f svg {
            stroke: currentColor;
        }

        .color-chip {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 1px 3px var(--card-shadow);
        }

        .wgp-pagination nav {
            padding: 2rem 0;
        }

        .page-link {
            color: var(--text-main);
            border: 1px solid var(--border-soft);
            padding: 0.5rem 1rem;
            background-color: var(--surface);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--text-white);
        }

        .page-link:hover {
            background-color: var(--bg-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        @keyframes heartBeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Améliorations responsive */
        @media (max-width: 768px) {
            .pc__atc {
                opacity: 1;
                transform: translateY(0);
            }

            .shop-sidebar {
                box-shadow: 0 0 20px var(--card-shadow);
            }

            .product-card-wrapper {
                margin-bottom: 1rem;
            }
        }

        /* État de chargement pour les images */
        .pc__img {
            background: linear-gradient(90deg, var(--bg-soft) 25%, var(--bg-light) 50%, var(--bg-soft) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Barre de défilement personnalisée pour les sections de filtre */
        .accordion-body {
            max-height: 300px;
            overflow-y: auto;
        }

        .accordion-body::-webkit-scrollbar {
            width: 4px;
        }

        .accordion-body::-webkit-scrollbar-track {
            background: var(--bg-soft);
        }

        .accordion-body::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 2px;
        }

        .accordion-body::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Améliorations du fil d'Ariane */
        .breadcrumb {
            padding: 0.5rem 0;
        }

        .breadcrumb-separator {
            color: var(--text-muted);
        }

        /* Améliorations de la bannière */
        .slideshow-text {
            padding: 3rem !important;
        }

        .slideshow-bg__img {
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .swiper-slide:hover .slideshow-bg__img {
            transform: scale(1.05);
        }

        /* Shop sidebar styling */
        .shop-sidebar {
            background-color: var(--surface);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 15px var(--card-shadow);
        }

        .aside-header {
            padding: 15px 0;
            border-bottom: 1px solid var(--border-soft);
        }

        /* Accordion styling */
        .accordion-item {
            border: none;
            border-bottom: 1px solid var(--border-soft);
        }

        .accordion-item:last-child {
            border-bottom: none;
        }

        .accordion-button {
            box-shadow: none !important;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: var(--border-soft);
        }

        .accordion-body {
            padding-top: 0;
            padding-bottom: 1rem;
        }

        /* Filter checkboxes */
        input[type="checkbox"] {
            accent-color: var(--primary);
        }

        /* Slideshow navigation */
        .slideshow-pagination .swiper-pagination-bullet {
            background: var(--text-muted);
            opacity: 0.5;
        }

        .slideshow-pagination .swiper-pagination-bullet-active {
            background: var(--primary);
            opacity: 1;
        }

        /* Product card styling */
        .product-card {
            background: var(--surface);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px var(--card-shadow);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 8px 25px var(--card-shadow);
        }

        /* Wishlist button styling */
        .filled-heart {
            color: var(--error-bg) !important;
        }

        /* Price range slider */
        .slider.slider-horizontal .slider-track {
            background: var(--bg-soft);
        }

        .slider-selection {
            background: var(--primary);
        }

        .slider-handle {
            background: var(--primary);
        }

        /* Slide split styling */
        .slide-split_text {
            background-color: var(--bg-light) !important;
        }

        .slide-split_media {
            background-color: var(--bg-soft) !important;
        }

        /* Secondary navigation */
        .secondary-navigation {
            background: var(--surface);
            border-radius: 8px;
            padding: 10px 0;
            margin: 15px 0;
            box-shadow: 0 2px 10px var(--card-shadow);
        }

        /* Button styling */
        .btn-dark {
            background-color: var(--text-main);
            border-color: var(--text-main);
            color: var(--text-white);
        }

        .btn-dark:hover {
            background-color: var(--text-secondary);
            border-color: var(--text-secondary);
        }

        /* Price range info */
        .price-range__info {
            color: var(--text-secondary);
        }

        .price-range__min,
        .price-range__max {
            color: var(--text-main);
            font-weight: 600;
        }

        /* Loading spinner */
        .spinner-border {
            color: var(--primary) !important;
        }

        /* Load more button */
        #load-more-btn {
            transition: all 0.3s ease;
            cursor: pointer;
            background: none;
            border: none;
            font: inherit;
            color: var(--primary);
            padding: 0;
        }

        #load-more-btn:hover {
            color: var(--primary-hover);
        }

        #load-more-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            color: var(--text-muted);
        }
    </style>

<main class="pt-90">
    <section class="shop-main container d-flex pt-4 pt-xl-5">
      <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
        <div class="aside-header d-flex d-lg-none align-items-center">
          <h3 class="text-uppercase fs-6 mb-0" style="color: var(--text-main);">Filtrer par</h3>
          <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
        </div>
        <div class="pt-4 pt-lg-0"></div>

        <!-- Catégories -->
        <div class="accordion" id="categories-list">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-1">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
                Catégories de Produits
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
              <div class="accordion-body px-0 pb-0 pt-3 category-list ">
                <ul class="list list-inline mb-0">
                    @foreach ($categories as $category )
                  <li class="list-item">
                    <span class="menu-link py-1">
                        <input type="checkbox" class="chk-category" name="categories" value="{{$category->id }}"
                            @if (in_array($category->id,explode(',',$f_categories))) checked="checked" @endif
                        />
                         {{ $category->name }}
                    </span>
                    <span class="text-right float-end" style="color: var(--text-secondary);">{{ $category->products->count() }}</span>
                  </li>
                    @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres de Taille -->
        <div class="accordion" id="size-filters">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-size">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-size" aria-expanded="true" aria-controls="accordion-filter-size">
                Tailles
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-size" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-size" data-bs-parent="#size-filters">
              <div class="accordion-body px-0 pb-0 pt-3 size-list">
                <ul class="list list-inline mb-0">
                  @foreach ($sizeOptions as $size)
                  <li class="list-item">
                    <span class="menu-link py-1">
                      <input type="checkbox" class="chk-size" name="sizes" value="{{ $size->id }}"
                        @if (in_array($size->id, explode(',', $f_sizes ?? ''))) checked="checked" @endif />
                      {{ $size->name }} ({{ $size->code }})
                    </span>
                    <span class="text-right float-end" style="color: var(--text-secondary);">{{ $size->products()->count() ?? 0 }}</span>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres de Couleur -->
        <div class="accordion" id="color-filters">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-color">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-color" aria-expanded="true" aria-controls="accordion-filter-color">
                Couleurs
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-color" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-color" data-bs-parent="#color-filters">
              <div class="accordion-body px-0 pb-0 pt-3 color-list">
                <ul class="list list-inline mb-0">
                  @foreach ($colors as $color)
                  <li class="list-item">
                    <span class="menu-link py-1 d-flex align-items-center">
                      <input type="checkbox" class="chk-color" name="colors" value="{{ $color->id }}"
                        @if (in_array($color->id, explode(',', $f_colors ?? ''))) checked="checked" @endif />
                      <div class="d-flex align-items-center ms-2">
                        <div class="color-chip" style="background-color: {{ $color->hex_code }};"></div>
                        {{ $color->name }}
                      </div>
                    </span>
                    <span class="text-right float-end" style="color: var(--text-secondary);">{{ $color->products_count ?? 0 }}</span>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres de Marque -->
        <div class="accordion" id="brand-filters">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-brand">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-brand" aria-expanded="true" aria-controls="accordion-filter-brand">
                Marques
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
              <div class="search-field multi-select accordion-body px-0 pb-0">
                <ul class="list list-inline mb-0 brand-list">
                  @foreach ($brands as $brand )
                  <li class="list-item">
                    <span class="menu-link py-1">
                      <input type="checkbox" name="brands" value="{{ $brand->id }}" class="chk-brand"
                        @if (in_array($brand->id,explode(',',$f_brands))) checked="checked" @endif>
                      {{ $brand->name }}
                    </span>
                    <span class="text-right float-end" style="color: var(--text-secondary);">
                      {{ $brand->products->count() }}
                    </span>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres de Prix -->
        <div class="accordion" id="price-filters">
          <div class="accordion-item mb-4">
            <h5 class="accordion-header mb-2" id="accordion-heading-price">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
                Prix
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
              <input class="price-range-slider" type="text" name="price_range" value="" data-slider-min="1"
                data-slider-max="500" data-slider-step="5" data-slider-value="[{{ $min_price }},{{ $max_price }}]" data-currency="$" />
              <div class="price-range__info d-flex align-items-center mt-2">
                <div class="me-auto">
                  <span class="text-secondary">Prix Min: </span>
                  <span class="price-range__min">1€</span>
                </div>
                <div>
                  <span class="text-secondary">Prix Max: </span>
                  <span class="price-range__max">500€</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="shop-list flex-grow-1">
        <!-- Bannière de présentation -->
        <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split" data-settings='{
          "autoplay": {
            "delay": 5000
          },
          "slidesPerView": 1,
          "effect": "fade",
          "loop": true,
          "pagination": {
            "el": ".slideshow-pagination",
            "type": "bullets",
            "clickable": true
          }
        }'>
          <div class="swiper-wrapper">
            @if($shopBanners && $shopBanners->count() > 0)
              @foreach($shopBanners as $banner)
              <div class="swiper-slide">
                <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                  <div class="slide-split_text position-relative d-flex align-items-center"
                    style="background-color: {{ $banner->background_color ?? 'var(--bg-light)' }};">
                    <div class="slideshow-text container p-3 p-xl-5">
                      @if($banner->tagline)
                      <h6 class="text-uppercase mb-2 animate animate_fade animate_btt animate_delay-1" style="color: var(--text-secondary);">
                        {{ $banner->tagline }}
                      </h6>
                      @endif

                      <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2" style="color: var(--text-main);">
                        {{ $banner->title }}<br />
                        <strong style="color: var(--primary);">{{ $banner->subtitle }}</strong>
                      </h2>

                      @if($banner->description)
                      <p class="mb-4 animate animate_fade animate_btt animate_delay-4" style="color: var(--text-secondary);">
                        {{ $banner->description }}
                      </p>
                      @endif

                      @if($banner->button_link && $banner->button_text)
                      <a href="{{ $banner->button_link }}"
                        class="btn btn-dark animate animate_fade animate_btt animate_delay-5" style="background-color: var(--primary); border-color: var(--primary); color: var(--text-white);">
                        {{ $banner->button_text }}
                      </a>
                      @endif
                    </div>
                  </div>
                  <div class="slide-split_media position-relative">
                    <div class="slideshow-bg" style="background-color: {{ $banner->background_color ?? 'var(--bg-soft)' }};">
                      <img loading="lazy"
                        src="{{ asset('uploads/shop-banners/' . $banner->image) }}"
                        width="630"
                        height="450"
                        alt="{{ $banner->alt_text ?? 'Bannière de la Boutique' }}"
                        class="slideshow-bg__img object-fit-cover" />
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="swiper-slide">
                <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                  <div class="slide-split_text position-relative d-flex align-items-center"
                    style="background-color: var(--bg-light);">
                    <div class="slideshow-text container p-3 p-xl-5">
                      <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2" style="color: var(--text-main);">
                        Accessoires <br /><strong style="color: var(--primary);">FEMMES</strong>
                      </h2>
                      <p class="mb-0 animate animate_fade animate_btt animate_delay-5" style="color: var(--text-secondary);">
                        Les accessoires sont le meilleur moyen de mettre à jour votre look. Ajoutez une touche d'élégance avec de nouveaux styles et de nouvelles couleurs, ou optez pour des pièces intemporelles.
                      </p>
                    </div>
                  </div>
                  <div class="slide-split_media position-relative">
                    <div class="slideshow-bg" style="background-color: var(--bg-soft);">
                      <img loading="lazy" src="{{ asset('assets/images/shop/shop_banner3.jpg') }}" width="630" height="450"
                        alt="Accessoires femmes" class="slideshow-bg__img object-fit-cover" />
                    </div>
                </div>
                </div>
              </div>
            @endif
          </div>

          <div class="container p-3 p-xl-5">
            <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-4 pb-xl-2"></div>
          </div>
        </div>

        <div class="mb-3 pb-2 pb-xl-3"></div>

        <!-- Contrôles de la Boutique -->
        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium" style="color: var(--text-secondary);">Accueil</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium" style="color: var(--text-main);">La Boutique</a>
          </div>

          <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Taille de Page" id="pagesize" name="pagesize" style="margin-right:20px;">
              <option value="12" {{ $pageSize == 12 ? 'selected': '' }}>AFFICHER 12</option>
              <option value="24" {{ $pageSize == 24 ? 'selected': '' }}>AFFICHER 24</option>
              <option value="48" {{ $pageSize == 48 ? 'selected': '' }}>AFFICHER 48</option>
              <option value="102" {{ $pageSize == 102 ? 'selected': '' }}>AFFICHER 102</option>
            </select>
            <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Trier les Articles" name="orderby" id="orderby">
              <option value="-1" {{ $order == -1 ? 'selected':'' }}>Par défaut</option>
              <option value="1" {{ $order == 1 ? 'selected':'' }}>Date, Du plus récent au plus ancien</option>
              <option value="2" {{ $order == 2 ? 'selected':'' }}>Date, Du plus ancien au plus récent</option>
              <option value="3" {{ $order == 3 ? 'selected':'' }}>Prix, Du plus bas au plus élevé</option>
              <option value="4" {{ $order == 4 ? 'selected':'' }}>Prix, Du plus élevé au plus bas</option>
            </select>

            <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0" style="background-color: var(--border-soft) !important;"></div>

            <div class="col-size align-items-center order-1 d-none d-lg-flex">
              <span class="text-uppercase fw-medium me-2" style="color: var(--text-secondary);">Vue</span>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="2" style="color: var(--text-secondary);">2</button>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="3" style="color: var(--text-secondary);">3</button>
              <button class="btn-link fw-medium js-cols-size" data-target="products-grid" data-cols="4" style="color: var(--text-secondary);">4</button>
            </div>

            <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
              <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside" data-aside="shopFilter">
                <svg class="d-inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10" fill="none"
                  xmlns="http://www.w3.org/2000/svg" style="color: var(--text-secondary);">
                  <use href="#icon_filter" />
                </svg>
                <span class="text-uppercase fw-medium d-inline-block align-middle" style="color: var(--text-secondary);">Filtrer</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Grille de Produits -->
        <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
          @foreach ($products as $product)
          <div class="product-card-wrapper">
            <div class="product-card mb-3 mb-md-4 mb-xxl-5">
              <div class="pc__img-wrapper">
                <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                  <div class="swiper-wrapper">
                    <div class="swiper-slide">
                      <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}"><img loading="lazy" src="{{ asset('uploads/products') }}/{{ $product->image }}" width="330" height="400" alt="{{ $product->name }}" class="pc__img"></a>
                    </div>
                    <div class="swiper-slide">
                      @foreach (explode(",", $product->images) as $gimg)
                      <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}"><img loading="lazy" src="{{ asset('uploads/products') }}/{{ $gimg }}" width="330" height="400" alt="C{{ $product->name }}" class="pc__img"></a>
                      @endforeach
                    </div>
                  </div>
                  <span class="pc__img-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg" style="color: var(--text-secondary);">
                    <use href="#icon_prev_sm" />
                  </svg></span>
                  <span class="pc__img-next"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg" style="color: var(--text-secondary);">
                    <use href="#icon_next_sm" />
                  </svg></span>
                </div>
                @if (Cart::instance('cart')->content()->where('id',$product->id)->count()>0)
                <a href="{{ route('cart.index') }}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning">Voir le Panier</a>
                @else
                <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $product->id }}"/>
                  <input type="hidden" name="quantity" value="1"/>
                  <input type="hidden" name="name" value="{{ $product->name }}"/>
                  <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price}}"/>
                  <button type="submit" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium"
                    data-aside="cartDrawer" title="Ajouter au Panier">Ajouter au Panier</button>
                </form>
                @endif
              </div>

              <div class="pc__info position-relative">
                <p class="pc__category">{{ $category->name }}</p>
                <h6 class="pc__title"><a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">{{ $product->name }}</a></h6>
                <div class="product-card__price d-flex">
                  <span class="money price">
                    @if ($product->sale_price)
                    <s> {{ $product->regular_price }} €</s> <span style="color: var(--primary);">{{ $product->sale_price }}€</span>
                    @else
                    {{ $product->regular_price }}€
                    @endif
                  </span>
                </div>
                <div class="product-card__review d-flex align-items-center">
                  <div class="reviews-group d-flex">
                    @php
                      $averageRating = $product->getAverageRatingAttribute();
                      $fullStars = floor($averageRating);
                      $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                      @if($i <= $fullStars)
                      <svg class="review-star filled" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_star" />
                      </svg>
                      @elseif($i == $fullStars + 1 && $hasHalfStar)
                      <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: var(--gold-highlight);">
                        <use href="#icon_star_half" />
                      </svg>
                      @else
                      <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_star" />
                      </svg>
                      @endif
                    @endfor
                  </div>
                  <span class="reviews-note text-lowercase text-secondary ms-1"></span>
                </div>

                @if (Cart::instance ('wishlist')->content()->where('id',$product->id)->count()>0)
                <form action="{{ route('wishlist.item.remove',['rowId'=>Cart::instance ('wishlist')->content()->where('id',$product->id)->first()->rowId])}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist filled-heart" title="Retirer de la Liste de Souhaits">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_heart" />
                    </svg>
                  </button>
                </form>
                @else
                <form action="{{ route('wishlist.add') }}" method="POST">
                  @csrf
                  <input type="hidden" name="id" value="{{ $product->id }}">
                  <input type="hidden" name="name" value="{{ $product->name }}">
                  <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price:$product->sale_price }}">
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                    title="Ajouter à la Liste de Souhaits">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_heart" />
                    </svg>
                  </button>
                </form>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="divider"></div>

        <!-- Pagination -->
        <div class="flex items-center justify-between flex-wrap gap-10 wgp-pagination">
          {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </section>
  </main>

  <!-- Formulaire de Filtre -->
  <form id="frmfilter" method="GET" action="{{ route('shop.index') }}">
    <input type="hidden" name="page" value="{{ $products->currentPage() }}">
    <input type="hidden" name="size" value="{{ $pageSize  }}">
    <input type="hidden" name="order" value="{{ $order }}">
    <input type="hidden" name="brands" id="hdnBrands" value="{{ $f_brands ?? '' }}">
    <input type="hidden" name="categories" id="hdnCategories" value="{{ $f_categories ?? '' }}">
    <input type="hidden" name="sizes" id="hdnSizes" value="{{ $f_sizes ?? '' }}">
    <input type="hidden" name="colors" id="hdnColors" value="{{ $f_colors ?? '' }}">
    <input type="hidden" name="min" id="hdnMinPrice" value="{{ $min_price }}">
    <input type="hidden" name="max" id="hdnMaxPrice" value="{{ $max_price }}">
  </form>
@endsection

@push('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Handle page size change
        document.getElementById('pagesize').addEventListener('change', function() {
            document.querySelector('#frmfilter input[name="size"]').value = this.value;
            document.querySelector('#frmfilter input[name="page"]').value = 1;
            document.getElementById('frmfilter').submit();
        });

        // 2. Handle order change - FIXED
        document.getElementById('orderby').addEventListener('change', function() {
            const selectedValue = this.value;
            document.querySelector('#frmfilter input[name="order"]').value = selectedValue;
            document.querySelector('#frmfilter input[name="page"]').value = 1;
            document.getElementById('frmfilter').submit();
        });

        // 3. Handle brand filters
        document.querySelectorAll('input[name="brands"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const brands = Array.from(document.querySelectorAll('input[name="brands"]:checked'))
                    .map(cb => cb.value)
                    .join(',');
                document.getElementById('hdnBrands').value = brands;
                document.querySelector('#frmfilter input[name="page"]').value = 1;
                document.getElementById('frmfilter').submit();
            });
        });

        // 4. Handle category filters
        document.querySelectorAll('input[name="categories"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const categories = Array.from(document.querySelectorAll('input[name="categories"]:checked'))
                    .map(cb => cb.value)
                    .join(',');
                document.getElementById('hdnCategories').value = categories;
                document.querySelector('#frmfilter input[name="page"]').value = 1;
                document.getElementById('frmfilter').submit();
            });
        });

        // 5. Handle size filters
        document.querySelectorAll('input[name="sizes"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const sizes = Array.from(document.querySelectorAll('input[name="sizes"]:checked'))
                    .map(cb => cb.value)
                    .join(',');
                document.getElementById('hdnSizes').value = sizes;
                document.querySelector('#frmfilter input[name="page"]').value = 1;
                document.getElementById('frmfilter').submit();
            });
        });

        // 6. Handle color filters
        document.querySelectorAll('input[name="colors"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const colors = Array.from(document.querySelectorAll('input[name="colors"]:checked'))
                    .map(cb => cb.value)
                    .join(',');
                document.getElementById('hdnColors').value = colors;
                document.querySelector('#frmfilter input[name="page"]').value = 1;
                document.getElementById('frmfilter').submit();
            });
        });

        // 7. Handle price range (if you have a slider)
        const priceSlider = document.querySelector('[name="price_range"]');
        if (priceSlider) {
            priceSlider.addEventListener('change', function() {
                const values = this.value.split(',');
                document.getElementById('hdnMinPrice').value = values[0];
                document.getElementById('hdnMaxPrice').value = values[1];
                document.querySelector('#frmfilter input[name="page"]').value = 1;
                setTimeout(() => {
                    document.getElementById('frmfilter').submit();
                }, 500);
            });
        }
    });
</script>
@endpush
