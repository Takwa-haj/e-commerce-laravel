@extends('layouts.app')

@section('content')
<main class="min-vh-100" style="background: var(--surface);">
    <!-- Section Hero -->
    <section class="py-5 position-relative overflow-hidden">
        <div class="container position-relative z-2">
            <div class="row justify-content-center py-5">
                <div class="col-lg-8 text-center">
                    <div class="mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm mb-4 p-4"
                             style="border: 1px solid var(--border-soft); background: var(--surface) !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--primary)" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                            </svg>
                        </div>
                        <h1 class="display-5 fw-bold mb-3" style="color: var(--text-main);">Contactez-nous</h1>
                        <p class="lead" style="color: var(--text-secondary);">Une question ? Un projet ? Notre équipe est à votre écoute et vous répondra dans les plus brefs délais.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section class="py-5" style="background: var(--bg-main);">
        <div class="container">
            <div class="row g-5">
                <!-- Informations de contact -->
                <div class="col-lg-4">
                    <div class="card contact-info-card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5" style="background: var(--surface);">
                            <h2 class="h3 fw-bold mb-4" style="color: var(--text-main);">Nos Coordonnées</h2>
                            <div class="mb-5">
                                <div class="d-flex align-items-start gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary)" viewBox="0 0 16 16">
                                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="h6 fw-semibold mb-1" style="color: var(--text-main);">Adresse</h5>
                                        <p class="mb-0" style="color: var(--text-secondary);">{{ $settings->site_address ?? '123 Rue Principale, 75000 Paris, France' }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary)" viewBox="0 0 16 16">
                                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="h6 fw-semibold mb-1" style="color: var(--text-main);">Téléphone</h5>
                                        <p class="mb-0" style="color: var(--text-secondary);">{{ $settings->site_phone ?? '+33 1 23 45 67 89' }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary)" viewBox="0 0 16 16">
                                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="h6 fw-semibold mb-1" style="color: var(--text-main);">Email</h5>
                                        <p class="mb-0" style="color: var(--text-secondary);">{{ $settings->site_email ?? 'contact@boutique.com' }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary)" viewBox="0 0 16 16">
                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="h6 fw-semibold mb-1" style="color: var(--text-main);">Horaires</h5>
                                        <p class="mb-0" style="color: var(--text-secondary);">Lun - Ven : 9h00 - 18h00</p>
                                        <p class="mb-0" style="color: var(--text-secondary);">Sam : 10h00 - 16h00</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-4" style="border-color: var(--border-soft) !important;">
                                <h5 class="h6 fw-semibold mb-3" style="color: var(--text-main);">Suivez-nous</h5>
                                <div class="d-flex gap-3">
                                    <a href="#" class="social-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="social-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact -->
                <div class="col-lg-8">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert" style="background-color: var(--success-bg); border-left: 4px solid var(--success-text); color: var(--success-text);">
                        <div class="d-flex align-items-center">
                            <svg class="bi flex-shrink-0 me-2" width="20" height="20" fill="var(--success-text)">
                                <use xlink:href="#check-circle-fill"/>
                            </svg>
                            <div class="small">{{ Session::get('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="card contact-form-card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5" style="background: var(--surface);">
                            <div class="text-center mb-5">
                                <h2 class="h3 fw-bold mb-2" style="color: var(--text-main);">Envoyez-nous un message</h2>
                                <p style="color: var(--text-secondary);">Remplissez le formulaire ci-dessous, nous vous répondrons sous 24 heures.</p>
                            </div>

                            <form id="contact-form" action="{{ route('home.contact.store') }}" method="POST" novalidate>
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label fw-semibold small text-uppercase" style="color: var(--text-main);">
                                                Nom complet <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0 ps-0" style="border-color: var(--border-soft);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                                    </svg>
                                                </span>
                                                <input type="text"
                                                       class="form-control contact-input @error('name') is-invalid @enderror"
                                                       id="name"
                                                       name="name"
                                                       value="{{ old('name') }}"
                                                       placeholder="Votre nom complet"
                                                       style="border-color: var(--border-soft);"
                                                       required>
                                                @error('name')
                                                <div class="invalid-feedback small" style="color: var(--error-text);">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label fw-semibold small text-uppercase" style="color: var(--text-main);">
                                                Téléphone <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0 ps-0" style="border-color: var(--border-soft);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                                    </svg>
                                                </span>
                                                <input type="tel"
                                                       class="form-control contact-input @error('phone') is-invalid @enderror"
                                                       id="phone"
                                                       name="phone"
                                                       value="{{ old('phone') }}"
                                                       placeholder="Votre numéro de téléphone"
                                                       style="border-color: var(--border-soft);"
                                                       required>
                                                @error('phone')
                                                <div class="invalid-feedback small" style="color: var(--error-text);">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-semibold small text-uppercase" style="color: var(--text-main);">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0 ps-0" style="border-color: var(--border-soft);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                                    </svg>
                                                </span>
                                                <input type="email"
                                                       class="form-control contact-input @error('email') is-invalid @enderror"
                                                       id="email"
                                                       name="email"
                                                       value="{{ old('email') }}"
                                                       placeholder="votre.email@exemple.com"
                                                       style="border-color: var(--border-soft);"
                                                       required>
                                                @error('email')
                                                <div class="invalid-feedback small" style="color: var(--error-text);">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="comment" class="form-label fw-semibold small text-uppercase" style="color: var(--text-main);">
                                                Message <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent border-end-0 align-items-start ps-0 pt-3" style="border-color: var(--border-soft);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--text-secondary)" viewBox="0 0 16 16">
                                                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                                    </svg>
                                                </span>
                                                <textarea class="form-control contact-input @error('comment') is-invalid @enderror"
                                                          id="comment"
                                                          name="comment"
                                                          rows="6"
                                                          placeholder="Décrivez votre projet ou posez votre question..."
                                                          style="border-color: var(--border-soft);"
                                                          required>{{ old('comment') }}</textarea>
                                                @error('comment')
                                                <div class="invalid-feedback small" style="color: var(--error-text);">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-text text-end mt-2 small" style="color: var(--text-muted);">
                                                <span id="char-count">0</span>/500 caractères
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="privacyPolicy" required style="border-color: var(--border-soft);">
                                            <label class="form-check-label" for="privacyPolicy" style="color: var(--text-secondary);">
                                                J'accepte la <a href="#" class="text-decoration-none" style="color: var(--text-main);">politique de confidentialité</a> et
                                                les <a href="#" class="text-decoration-none" style="color: var(--text-main);">conditions d'utilisation</a>.
                                            </label>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn contact-submit-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                                </svg>
                                                Envoyer le message
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Carte -->
    <section class="py-5" style="background: var(--surface);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h2 class="h3 fw-bold mb-3" style="color: var(--text-main);">Notre localisation</h2>
                        <p style="color: var(--text-secondary);">Retrouvez-nous facilement grâce à notre carte interactive</p>
                    </div>

                    <div class="map-container border-0 shadow-sm" style="background: var(--surface);">
                        <div class="ratio ratio-21x9">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937595!2d2.292292615509614!3d48.85837007928746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1638359945155!5m2!1sfr!2sfr"
                                width="100%"
                                height="450"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                class="rounded-3">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('styles')
<style>
    /* Custom styles for contact page */
    .contact-info-card,
    .contact-form-card,
    .map-container {
        background: var(--surface);
        border-radius: 8px;
        box-shadow: 0 2px 20px var(--card-shadow);
        border: none;
        transition: all 0.3s ease;
    }

    .contact-info-card:hover,
    .contact-form-card:hover,
    .map-container:hover {
        box-shadow: 0 4px 25px rgba(90,70,52,0.12);
        transform: translateY(-2px);
    }

    .contact-input {
        border: 1px solid var(--border-soft);
        border-radius: 6px;
        padding: 12px 16px;
        background: var(--surface);
        transition: all 0.3s ease;
        font-size: 14px;
        color: var(--text-main);
    }

    .contact-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--hover-overlay);
        background: var(--surface);
    }

    .input-group-text {
        background: var(--surface);
        border: 1px solid var(--border-soft);
        border-right: none;
        padding: 12px 16px;
        transition: all 0.3s ease;
        border-radius: 6px 0 0 6px;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--primary);
    }

    .contact-submit-btn {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--text-main);
        border-radius: 6px;
        padding: 14px 2rem;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .contact-submit-btn:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px var(--card-shadow);
    }

    .social-icon {
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg-soft);
    }

    .social-icon:hover {
        transform: translateY(-2px);
        background: var(--primary);
    }

    .social-icon:hover svg {
        fill: var(--text-main);
    }

    .alert-success {
        background-color: var(--success-bg);
        border: none;
        color: var(--success-text);
        border-radius: 6px;
        border-left: 4px solid var(--success-text);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const svgDefs = `
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
            </svg>
        `;

        document.body.insertAdjacentHTML('afterbegin', svgDefs);

        const textarea = document.getElementById('comment');
        const charCount = document.getElementById('char-count');

        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;

            if (length > 500) {
                charCount.classList.add('text-danger');
                this.classList.add('is-invalid');
            } else {
                charCount.classList.remove('text-danger');
                this.classList.remove('is-invalid');
            }
        });

        charCount.textContent = textarea.value.length;

        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 10);
                value = value.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
            }
            e.target.value = value;
        });
    });
</script>
@endpush
@endsection
