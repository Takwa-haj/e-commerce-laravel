@extends('layouts.app')

@section('title', 'Configuration 2FA')

@section('content')
<main class="two-factor-page" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card two-factor-card shadow border-0">
                    <div class="card-body p-5">
                        <!-- Back Button -->
                        <div class="mb-4">
                            <a href="{{ route('user.account.details') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-5">
                            <div class="two-factor-icon mb-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h2 class="h3 fw-bold">Authentification à deux facteurs</h2>
                            <p class="text-muted">
                                Ajoutez une couche de sécurité supplémentaire à votre compte
                            </p>
                        </div>

                        <!-- Setup Steps -->
                        <div class="row g-5">
                            <!-- Step 1: Install App -->
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-number">1</div>
                                    <h5 class="fw-semibold mb-3">Installez une application d'authentification</h5>
                                    <p class="text-muted mb-4">
                                        Téléchargez une application d'authentification comme Google Authenticator,
                                        Authy ou Microsoft Authenticator.
                                    </p>
                                    <div class="app-badges">
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                           target="_blank" class="app-badge">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                                 alt="Google Play" height="40">
                                        </a>
                                        <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                                           target="_blank" class="app-badge">
                                            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                                 alt="App Store" height="40">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Scan QR Code -->
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-number">2</div>
                                    <h5 class="fw-semibold mb-3">Scannez le QR code</h5>
                                    <p class="text-muted mb-4">
                                        Ouvrez votre application d'authentification et scannez ce QR code :
                                    </p>

                                    @if($qrCode)
                                        <div class="text-center mb-4">
                                            <img src="data:image/png;base64,{{ $qrCode }}"
                                                 alt="QR Code"
                                                 class="qr-code img-fluid border p-2">
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-4">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Impossible de générer le QR code. Utilisez le code secret manuellement.
                                        </div>
                                    @endif

                                    <!-- Manual Entry -->
                                    <div class="manual-entry">
                                        <p class="text-muted small mb-2">
                                            <strong>Ou entrez ce code manuellement :</strong>
                                        </p>
                                        <div class="input-group">
                                            <input type="text"
                                                   value="{{ $secret }}"
                                                   readonly
                                                   class="form-control text-center font-monospace"
                                                   id="secretKey">
                                            <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="copySecret()">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Gardez ce code secret en sécurité
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Verify -->
                            <div class="col-12">
                                <div class="step-card">
                                    <div class="step-number">3</div>
                                    <h5 class="fw-semibold mb-3">Vérifiez le code</h5>
                                    <p class="text-muted mb-4">
                                        Entrez le code à 6 chiffres généré par votre application d'authentification :
                                    </p>

                                    <form method="POST" action="{{ route('two-factor.enable') }}" class="needs-validation" novalidate>
                                        @csrf

                                        <input type="hidden" name="secret" value="{{ $secret }}">

                                        <div class="row justify-content-center">
                                            <div class="col-md-6">
                                                <!-- Verification Code -->
                                                <div class="mb-4">
                                                    <label for="code" class="form-label fw-semibold">
                                                        Code de vérification à 6 chiffres
                                                    </label>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-light">
                                                            <i class="fas fa-key text-muted"></i>
                                                        </span>
                                                        <input type="text"
                                                               name="code"
                                                               id="code"
                                                               class="form-control text-center font-monospace"
                                                               placeholder="123456"
                                                               maxlength="6"
                                                               pattern="\d{6}"
                                                               required
                                                               autocomplete="off"
                                                               autofocus>
                                                    </div>
                                                    <div class="form-text">
                                                        Entrez le code actuel affiché dans votre application
                                                    </div>
                                                    @error('code')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="d-grid">
                                                    <button type="submit"
                                                            class="btn btn-primary btn-lg py-3">
                                                        <i class="fas fa-shield-alt me-2"></i>
                                                        Activer l'authentification à deux facteurs
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Notes -->
                        <div class="alert alert-info mt-5">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold">Notes de sécurité :</h6>
                                    <ul class="mb-0">
                                        <li>Gardez votre téléphone sécurisé avec un code PIN ou biométrie</li>
                                        <li>Les codes de récupération seront affichés après l'activation</li>
                                        <li>Gardez vos codes de récupération en lieu sûr</li>
                                        <li>Vous pourrez désactiver le 2FA à tout moment depuis vos paramètres de sécurité</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .two-factor-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .two-factor-card {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .two-factor-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #6e6e6e, #8b8f70);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        box-shadow: 0 8px 16px rgba(106, 110, 81, 0.2);
    }

    .step-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        height: 100%;
        position: relative;
        border: 1px solid #e9ecef;
    }

    .step-number {
        position: absolute;
        top: -15px;
        left: -15px;
        width: 40px;
        height: 40px;
        background: #6e6e6e;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .qr-code {
        max-width: 200px;
        border-radius: 8px;
        background: white;
    }

    .app-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .app-badge img {
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .app-badge:hover img {
        transform: scale(1.05);
    }

    .manual-entry .form-control {
        font-size: 1.1rem;
        letter-spacing: 2px;
        font-weight: 500;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
    }

    .btn-primary {
        background-color: #6e6e6e;
        border-color: #6e6e6e;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #5a5e41;
        border-color: #5a5e41;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(106, 110, 81, 0.3);
    }

    .alert-info {
        background-color: #e8f4fd;
        border-color: #b6e0fe;
        color: #084298;
        border-radius: 10px;
    }

    #code {
        letter-spacing: 10px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .two-factor-page {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .two-factor-card {
            padding: 1.5rem !important;
        }

        .step-card {
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .step-number {
            top: -12px;
            left: -12px;
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }

        .app-badges {
            justify-content: center;
        }

        #code {
            letter-spacing: 5px;
            font-size: 1.25rem;
        }
    }
</style>

<script>
    // Copy secret key to clipboard
    function copySecret() {
        const secretInput = document.getElementById('secretKey');
        secretInput.select();
        secretInput.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(secretInput.value).then(() => {
            // Show success message
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        });
    }

    // Auto-focus code input and format
    document.getElementById('code').addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to 6 digits
        if (this.value.length > 6) {
            this.value = this.value.substring(0, 6);
        }
    });

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
