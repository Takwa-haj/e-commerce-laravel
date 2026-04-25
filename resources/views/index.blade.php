@extends('layouts.app')
@section('content')
<style>
/* ===== LOAD MORE BUTTON ===== */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

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
    color: var(--primary);
}

#load-more-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ===== SLIDESHOW HERO ===== */
.slideshow-hero {
    height: 90vh;
    min-height: 700px;
    position: relative;
    overflow: hidden;
    background: #0a0a0a;
}

.slideshow-hero__slide {
    position: relative;
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
}

/* Blurry background */
.slideshow-hero__blur-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    overflow: hidden;
}

.slideshow-hero__blur-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(30px) brightness(0.4);
    transform: scale(1.1);
    opacity: 0.6;
}

/* Content wrapper */
.slideshow-hero__content-wrapper {
    position: relative;
    z-index: 3;
    padding: 4rem 0;
    margin-left: 5%;
}

.slideshow-hero__content {
    position: relative;
    max-width: 520px;
}

/* Tagline */
.slideshow-hero__tagline {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.slideshow-hero__tagline-text {
    color: var(--gold_highlight);
    font-size: 0.875rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 500;
}

.slideshow-hero__tagline-line {
    width: 60px;
    height: 1px;
    background: color-mix(in srgb, var(--gold_highlight) 50%, transparent);
}

/* Titles */
.slideshow-hero__titles {
    margin-bottom: 1.5rem;
}

.slideshow-hero__main-title {
    font-size: 1.25rem;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.8);
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
}

.slideshow-hero__subtitle {
    font-size: 4rem;
    font-weight: 300;
    color: #fff;
    line-height: 1.1;
    margin: 0;
}

.slideshow-hero__subtitle strong {
    font-weight: 600;
    background: linear-gradient(135deg, #fff 0%, var(--gold_highlight) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Description */
.slideshow-hero__description {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.125rem;
    line-height: 1.8;
    margin-bottom: 2.5rem;
    max-width: 500px;
}

/* CTA Button */
.slideshow-hero__cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    background: color-mix(in srgb, var(--gold_highlight) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--gold_highlight) 30%, transparent);
    color: var(--gold_highlight);
    padding: 1rem 2rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.slideshow-hero__cta-btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background: linear-gradient(90deg, color-mix(in srgb, var(--gold_highlight) 20%, transparent) 0%, color-mix(in srgb, var(--gold_highlight) 10%, transparent) 100%);
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: -1;
}

.slideshow-hero__cta-btn:hover {
    color: #fff;
    border-color: var(--gold_highlight);
    transform: translateY(-2px);
    box-shadow: 0 10px 30px color-mix(in srgb, var(--gold_highlight) 20%, transparent);
}

.slideshow-hero__cta-btn:hover:before {
    width: 100%;
}

.slideshow-hero__cta-icon {
    transition: transform 0.3s ease;
}

.slideshow-hero__cta-btn:hover .slideshow-hero__cta-icon {
    transform: translateX(5px);
}

/* Main product image */
.slideshow-hero__image-container {
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.slideshow-hero__image-wrapper {
    position: relative;
    width: 600px;
    height: 600px;
    margin-right: 10%;
}

.slideshow-hero__main-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    filter: drop-shadow(0 40px 60px rgba(0, 0, 0, 0.5));
    animation: float 6s ease-in-out infinite;
}

/* Floating animation */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

/* Price tag */
.slideshow-hero__price-tag {
    position: absolute;
    bottom: 40px;
    right: -30px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem 1.5rem;
    border-radius: 20px;
    text-align: center;
    animation: float 6s ease-in-out infinite 1s;
}

.slideshow-hero__price-text {
    display: block;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.slideshow-hero__price-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gold_highlight);
}

/* Decorative elements */
.slideshow-hero__deco-1,
.slideshow-hero__deco-2,
.slideshow-hero__deco-3 {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, color-mix(in srgb, var(--gold_highlight) 10%, transparent) 0%, transparent 70%);
    filter: blur(10px);
    z-index: -1;
}

.slideshow-hero__deco-1 {
    width: 300px; height: 300px;
    top: -100px; right: -100px;
    animation: pulse 4s ease-in-out infinite;
}

.slideshow-hero__deco-2 {
    width: 200px; height: 200px;
    bottom: 100px; left: -50px;
    animation: pulse 5s ease-in-out infinite 1s;
}

.slideshow-hero__deco-3 {
    width: 150px; height: 150px;
    top: 200px; left: -30px;
    animation: pulse 6s ease-in-out infinite 2s;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.1); }
}

/* Slide indicator */
.slideshow-hero__indicator {
    position: absolute;
    bottom: 60px;
    left: 5%;
    display: flex;
    align-items: center;
    gap: 1rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    letter-spacing: 2px;
    z-index: 3;
}

.slideshow-hero__indicator-line {
    width: 40px;
    height: 1px;
    background: rgba(255, 255, 255, 0.3);
}

.slideshow-hero__indicator-current {
    color: var(--gold_highlight);
    font-weight: 600;
    font-size: 1.25rem;
}

/* Navigation */
.slideshow-hero__nav-container {
    position: absolute;
    bottom: 60px;
    right: 5%;
    display: flex;
    align-items: center;
    gap: 2rem;
    z-index: 4;
}

.slideshow-hero__nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.slideshow-hero__nav-btn:hover {
    background: color-mix(in srgb, var(--gold_highlight) 10%, transparent);
    border-color: color-mix(in srgb, var(--gold_highlight) 30%, transparent);
    color: var(--gold_highlight);
    transform: scale(1.1);
}

.slideshow-hero__pagination-dots {
    display: flex;
    gap: 0.5rem;
}

.slideshow-bullet {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slideshow-bullet-active {
    background: var(--gold_highlight);
    transform: scale(1.2);
}

/* ===== SECTION TITLES ===== */
.section-title {
    color: var(--text_primary);
}

/* ===== PRODUCT CARDS ===== */
.product-card {
    background: var(--surface);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 4px 20px var(--card_shadow);
}

.pc__title a {
    color: var(--text_primary);
    text-decoration: none;
}

.pc__title a:hover {
    color: var(--primary);
}

.product-card__price .money {
    color: var(--primary) !important;
}

.product-card__price s {
    color: var(--text_secondary);
}

/* ===== CATEGORY BANNER ===== */
.category-banner__item {
    position: relative;
    overflow: hidden;
}

.category-banner__item-content h3 {
    color: var(--text_primary);
}

.category-banner__item-content .btn-link {
    color: var(--primary);
}

.category-banner__item-mark {
    background: var(--primary);
    color: white;
}

/* ===== BUTTONS & LINKS ===== */
.btn-link {
    color: var(--primary);
    text-decoration: none;
}

.btn-link:hover {
    color: var(--primary);
}

.default-underline {
    position: relative;
}

.default-underline:after {
    background: var(--primary);
}

/* ===== TEXT COLORS ===== */
.text-secondary {
    color: var(--text_secondary) !important;
}

.text-muted {
    color: var(--text_secondary) !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
    .slideshow-hero {
        height: 80vh;
        min-height: 600px;
    }
    .slideshow-hero__image-wrapper {
        width: 500px;
        height: 500px;
        margin-right: 5%;
    }
    .slideshow-hero__subtitle {
        font-size: 3.5rem;
    }
}

@media (max-width: 992px) {
    .slideshow-hero {
        height: auto;
        min-height: auto;
        padding: 4rem 0;
    }
    .slideshow-hero__content-wrapper {
        margin-left: 0;
        padding: 2rem 0;
        text-align: center;
    }
    .slideshow-hero__content {
        max-width: 100%;
    }
    .slideshow-hero__image-wrapper {
        width: 400px;
        height: 400px;
        margin: 2rem auto;
    }
    .slideshow-hero__tagline {
        justify-content: center;
    }
    .slideshow-hero__description {
        margin-left: auto;
        margin-right: auto;
    }
    .slideshow-hero__price-tag {
        right: 20px;
        bottom: 20px;
    }
}

@media (max-width: 768px) {
    .slideshow-hero__subtitle {
        font-size: 2.5rem;
    }
    .slideshow-hero__image-wrapper {
        width: 300px;
        height: 300px;
    }
    .slideshow-hero__price-tag {
        padding: 0.75rem 1rem;
        right: 10px;
        bottom: 10px;
    }
    .slideshow-hero__price-amount {
        font-size: 1.25rem;
    }
    .slideshow-hero__indicator {
        left: 2rem;
        bottom: 2rem;
    }
    .slideshow-hero__nav-container {
        right: 2rem;
        bottom: 2rem;
    }
}

@media (max-width: 576px) {
    .slideshow-hero {
        padding: 3rem 0;
    }
    .slideshow-hero__subtitle {
        font-size: 2rem;
    }
    .slideshow-hero__description {
        font-size: 1rem;
    }
    .slideshow-hero__image-wrapper {
        width: 250px;
        height: 250px;
    }
    .slideshow-hero__cta-btn {
        padding: 0.875rem 1.5rem;
        font-size: 0.75rem;
    }
    .slideshow-hero__nav-btn {
        width: 40px;
        height: 40px;
    }
}
</style>
  <main>

<!-- Replace the entire slideshow section with this: -->
<section class="swiper-container js-swiper-slider slideshow-hero" data-settings='{
    "autoplay": {
      "delay": 5000
    },
    "slidesPerView": 1,
    "effect": "creative",
    "creativeEffect": {
      "prev": {
        "opacity": 0,
        "translate": [0, 0, -400]
      },
      "next": {
        "opacity": 0,
        "translate": ["100%", 0, 0]
      }
    },
    "loop": true,
    "speed": 1200,
    "pagination": {
      "el": ".slideshow-pagination",
      "clickable": true,
      "type": "bullets",
      "bulletClass": "slideshow-bullet",
      "bulletActiveClass": "slideshow-bullet-active"
    },
    "navigation": {
      "nextEl": ".slideshow-next",
      "prevEl": ".slideshow-prev"
    }
  }'>
  <div class="swiper-wrapper">
    @foreach ($slides as $slide)
    <div class="swiper-slide">
      <div class="slideshow-hero__slide">
        <!-- Blurred background image -->
        <div class="slideshow-hero__blur-bg">
          <img loading="lazy"
               src="{{ asset('uploads/slides') }}/{{ $slide->image }}"
               alt="{{ $slide->title }}"
               class="slideshow-hero__blur-img">
        </div>

        <!-- Content container with glass morphism effect -->
        <div class="container h-100">
          <div class="row align-items-center h-100">
            <!-- Text content column -->
            <div class="col-lg-6 col-xl-5">
              <div class="slideshow-hero__content-wrapper">
                <div class="slideshow-hero__content">
                  <!-- Tagline -->
                  <div class="slideshow-hero__tagline animate animate_fade animate_btt animate_delay-2">
                    <span class="slideshow-hero__tagline-text">{{ $slide->tagline }}</span>
                    <span class="slideshow-hero__tagline-line"></span>
                  </div>

                  <!-- Main title -->
                  <div class="slideshow-hero__titles animate animate_fade animate_btt animate_delay-4">
                    <h2 class="slideshow-hero__main-title">{{ $slide->title }}</h2>
                    <h1 class="slideshow-hero__subtitle">{{ $slide->subtitle }}</h1>
                  </div>

                  <!-- Description -->
                  @if($slide->description)
                  <p class="slideshow-hero__description animate animate_fade animate_btt animate_delay-6">
                    {{ $slide->description }}
                  </p>
                  @endif

                  <!-- CTA Button -->
                  <div class="animate animate_fade animate_btt animate_delay-8">
                    <a href="{{ $slide->link }}" class="slideshow-hero__cta-btn">
                      <span class="slideshow-hero__cta-text">{{ $slide->button_text }}</span>
                      <span class="slideshow-hero__cta-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M13.75 6.75L19.25 12L13.75 17.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M19 12H4.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Product image column -->
            <div class="col-lg-6 col-xl-7">
              <div class="slideshow-hero__image-container">
                <div class="slideshow-hero__image-wrapper animate animate_fade animate_rtt animate_delay-5">
                  <img loading="lazy"
                       src="{{ asset('uploads/slides') }}/{{ $slide->image }}"
                       alt="{{ $slide->title }}"
                       class="slideshow-hero__main-image">

                  <!-- Floating price tag -->
                  @if($slide->price || $slide->price_text)
                  <div class="slideshow-hero__price-tag">
                    <span class="slideshow-hero__price-text">À partir de</span>
                    <span class="slideshow-hero__price-amount">
                      @if($slide->price_text)
                        {{ $slide->price_text }}
                      @else
                        €{{ number_format($slide->price, 2) }}
                      @endif
                    </span>
                  </div>
                  @endif

                  <!-- Decorative elements -->
                  <div class="slideshow-hero__deco-1"></div>
                  <div class="slideshow-hero__deco-2"></div>
                  <div class="slideshow-hero__deco-3"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide indicator -->
        <div class="slideshow-hero__indicator">
          <span class="slideshow-hero__indicator-current">0{{ $loop->iteration }}</span>
          <div class="slideshow-hero__indicator-line"></div>
          <span class="slideshow-hero__indicator-total">0{{ count($slides) }}</span>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Navigation -->
  <div class="slideshow-hero__nav-container">
    <button class="slideshow-prev slideshow-hero__nav-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
    </button>

    <div class="slideshow-hero__pagination-dots">
      <div class="slideshow-pagination"></div>
    </div>

    <button class="slideshow-next slideshow-hero__nav-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 18l6-6-6-6"/>
      </svg>
    </button>
  </div>
</section>


    <div class="container mw-1620 bg-white border-radius-10">
      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
      <section class="category-carousel container">
        <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">Vous Aimerez Aussi</h2>

        <div class="position-relative">
          <div class="swiper-container js-swiper-slider" data-settings='{
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 8,
              "slidesPerGroup": 1,
              "effect": "none",
              "loop": true,
              "navigation": {
                "nextEl": ".products-carousel__next-1",
                "prevEl": ".products-carousel__prev-1"
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "slidesPerGroup": 2,
                  "spaceBetween": 15
                },
                "768": {
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "spaceBetween": 30
                },
                "992": {
                  "slidesPerView": 6,
                  "slidesPerGroup": 1,
                  "spaceBetween": 45,
                  "pagination": false
                },
                "1200": {
                  "slidesPerView": 8,
                  "slidesPerGroup": 1,
                  "spaceBetween": 60,
                  "pagination": false
                }
              }
            }'>
            <div class="swiper-wrapper">
                @foreach ($categories as $category)


              <div class="swiper-slide">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 flex-shrink-0"> <!-- Fixed size container -->
                <img
                    loading="lazy"
                    style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; margin-bottom: 12px;"
                    src="{{ asset('uploads/categories/' . $category->image) }}"
                    alt="{{ $category->name }}"
                />
                </div>
                <div class="text-center">
                <a href="{{ route('shop.index',['categories'=>$category->id]) }}" class="menu-link fw-medium">{{ $category->name }}</a>
                </div>
            </div>
            </div>
               @endforeach

            </div><!-- /.swiper-wrapper -->
          </div><!-- /.swiper-container js-swiper-slider -->

          <div
            class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
            <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_prev_md" />
            </svg>
          </div><!-- /.products-carousel__prev -->
          <div
            class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
            <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_next_md" />
            </svg>
          </div><!-- /.products-carousel__next -->
        </div><!-- /.position-relative -->
      </section>

      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="hot-deals container">
        <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Promotions Exceptionnelles</h2>
        <div class="row">
          <div
            class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
            <h2>Vente d'Été</h2>
            <h2 class="fw-bold">Jusqu'à -60%</h2>

            <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
              data-date="18-3-2024" data-time="06:50">
              <div class="day countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Jours</span>
              </div>

              <div class="hour countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Heures</span>
              </div>

              <div class="min countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Mins</span>
              </div>

              <div class="sec countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Sec</span>
              </div>
            </div>

            <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium mt-3">Voir Tout</a>
          </div>
          <div class="col-md-6 col-lg-8 col-xl-80per">
            <div class="position-relative">
              <div class="swiper-container js-swiper-slider" data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
                <div class="swiper-wrapper">

                    @foreach ($sproducts as $sproduct )

                  <div class="swiper-slide product-card product-card_style3">
                    <div class="pc__img-wrapper">
                      <a href="{{ route('shop.product.details',['product_slug'=>$sproduct->slug ]) }}">
                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $sproduct->image }}" width="258" height="313"
                          alt="{{ $sproduct->name  }}" class="pc__img">
                      </a>
                    </div>

                    <div class="pc__info position-relative">
                      <h6 class="pc__title"><a href="{{ route('shop.product.details',['product_slug'=>$sproduct->slug ]) }}">{{ $sproduct->name  }}</a></h6>
                      <div class="product-card__price d-flex">
                        <span class="money price text-secondary">
                             @if ($sproduct->sale_price)
                                <s> {{ $sproduct->regular_price }} €</s>{{ $sproduct->sale_price }}€
                                @else
                                    {{ $sproduct->regular_price }}€

                                @endif
                         </span>
                      </div>


                    </div>
                  </div>
                    @endforeach

                </div><!-- /.swiper-wrapper -->
              </div><!-- /.swiper-container js-swiper-slider -->
            </div><!-- /.position-relative -->
          </div>
        </div>
      </section>

      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="category-banner container">
    <div class="row">
        @php
            // Get banners by position
            $leftBanner = $categoryBanners->where('position', 'left')->first();
            $rightBanner = $categoryBanners->where('position', 'right')->first();
        @endphp

        {{-- Left Banner --}}
        @if($leftBanner)
        <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
                <img loading="lazy" class="h-auto"
                     src="{{ asset('uploads/category-banners') }}/{{ $leftBanner->image }}"
                     width="690" height="665"
                     alt="{{ $leftBanner->alt_text ?? $leftBanner->title }}" />

                @if($leftBanner->displayPrice)
                <div class="category-banner__item-mark">
                    {{ $leftBanner->displayPrice }}
                </div>
                @endif

                <div class="category-banner__item-content">
                    <h3 class="mb-0">{{ $leftBanner->title }}</h3>
                    @if($leftBanner->subtitle)
                        <p class="text-muted mb-2">{{ $leftBanner->subtitle }}</p>
                    @endif
                    <a href="{{ $leftBanner->button_link }}"
                       class="btn-link default-underline text-uppercase fw-medium">
                        {{ $leftBanner->button_text }}
                    </a>
                </div>
            </div>
        </div>
        @else
        {{-- Fallback if no left banner exists --}}
        <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
                <img loading="lazy" class="h-auto" src="{{ asset('assets/images/img/categories/3.jpg') }}"
                     width="690" height="665" alt="Collection Or" />
                <div class="category-banner__item-mark">
                    À partir de €99
                </div>
                <div class="category-banner__item-content">
                    <h3 class="mb-0">Collection Or</h3>
                    <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium">
                        Découvrir
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Right Banner --}}
        @if($rightBanner)
        <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
                <img loading="lazy" class="h-auto"
                     src="{{ asset('uploads/category-banners') }}/{{ $rightBanner->image }}"
                     width="690" height="665"
                     alt="{{ $rightBanner->alt_text ?? $rightBanner->title }}" />

                @if($rightBanner->displayPrice)
                <div class="category-banner__item-mark">
                    {{ $rightBanner->displayPrice }}
                </div>
                @endif

                <div class="category-banner__item-content">
                    <h3 class="mb-0">{{ $rightBanner->title }}</h3>
                    @if($rightBanner->subtitle)
                        <p class="text-muted mb-2">{{ $rightBanner->subtitle }}</p>
                    @endif
                    <a href="{{ $rightBanner->button_link }}"
                       class="btn-link default-underline text-uppercase fw-medium">
                        {{ $rightBanner->button_text }}
                    </a>
                </div>
            </div>
        </div>
        @else
        {{-- Fallback if no right banner exists --}}
        <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
                <img loading="lazy" class="h-auto" src="{{ asset('assets/images/img/categories/3.jpg') }}"
                     width="690" height="665" alt="Collection Argent" />
                <div class="category-banner__item-mark">
                    À partir de €49
                </div>
                <div class="category-banner__item-content">
                    <h3 class="mb-0">Collection Argent</h3>
                    <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium">
                        Découvrir
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="products-grid container">
    <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Produits Phares</h2>

    <div class="row" id="products-container">
        @foreach ($fproducts as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                <div class="pc__img-wrapper">
                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $product->image }}"
                             width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                    </a>
                </div>

                <div class="pc__info position-relative">
                    <h6 class="pc__title">
                        <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                            {{ $product->name }}
                        </a>
                    </h6>
                    <div class="product-card__price d-flex align-items-center">
                        <span class="money price text-secondary">
                            @if ($product->sale_price)
                                <s>{{ $product->regular_price }} €</s> {{ $product->sale_price }}€
                            @else
                                {{ $product->regular_price }}€
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div><!-- /.row -->

    <div class="text-center mt-2">
        @if($fproducts->hasMorePages())
        <button id="load-more-btn" class="btn-link btn-link_lg default-underline text-uppercase fw-medium"
                data-page="2" data-loading-text="Chargement...">
            Voir Plus
        </button>
        <div id="loading-spinner" class="d-none mt-3">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
        @endif
    </div>
</section>
    </div>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

  </main>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const productsContainer = document.getElementById('products-container');
    const loadingSpinner = document.getElementById('loading-spinner');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const currentPage = parseInt(this.getAttribute('data-page'));
            const originalText = this.innerHTML;

            // Show loading state
            this.innerHTML = this.getAttribute('data-loading-text');
            this.disabled = true;
            loadingSpinner.classList.remove('d-none');

            // Make AJAX request
            fetch(`/load-more-products?page=${currentPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Append new products
                productsContainer.insertAdjacentHTML('beforeend', data.html);

                // Update button state
                if (data.hasMore) {
                    this.setAttribute('data-page', currentPage + 1);
                    this.innerHTML = originalText;
                    this.disabled = false;
                } else {
                    // No more products to load
                    this.style.display = 'none';
                }

                loadingSpinner.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                loadingSpinner.classList.add('d-none');
                alert('Une erreur est survenue lors du chargement des produits.');
            });
        });
    }
});
</script>
@endpush
