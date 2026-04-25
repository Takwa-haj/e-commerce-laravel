@extends('layouts.app')

@section('title', 'Vérification 2FA')

@section('content')
<main class="two-factor-verify-page" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card verify-card shadow border-0">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <a href="{{ route('home.index') }}" class="d-inline-block">
                                @if($settings->logo ?? false)
                                    <img src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                         alt="{{ $settings->site_name ?? 'E-commerce' }}"
                                         class="verify-logo" style="height: 50px;">
                                @else
                                    <h1 class="h4 fw-bold text-primary">
                                        {{ $settings->site_name ?? 'E-commerce' }}
                                    </h1>
                                @endif
                            </a>
                        </div>

                        <!-- Icon -->
                        <div class="text-center mb-4">
                            <div class="verify-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2 class="h4 text-center fw-bold mb-3">Vérification en deux étapes</h2>
                        <p class="text-muted text-center mb-4">
                            Entrez le code de vérification de votre application d'authentification
                        </p>

                        <!-- Form -->
                        <form method="POST" action="{{ route('two-factor.verify') }}" class="needs-validation" novalidate>
                            @csrf

                            <!-- Code Input -->
                            <div class="mb-4">
                                <label for="code" class="form-label fw-semibold">
                                    Code de vérification
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
                                <div class="form-text text-center mt-2">
                                    Code à 6 chiffres de votre application d'authentification
                                </div>
                                @error('code')
                                    <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit"
                                        class="btn btn-primary btn-lg py-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Vérifier et continuer
                                </button>
                            </div>

                            <!-- Recovery Link -->
                            <div class="text-center">
                                <a href="{{ route('two-factor.recovery') }}"
                                   class="text-decoration-none text-primary">
                                    <i class="fas fa-key me-1"></i>
                                    Utiliser un code de récupération
                                </a>
                            </div>

                            <!-- Support -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="text-muted small mb-0">
                                    Vous avez besoin d'aide ?
                                    <a href="{{ route('support') }}" class="text-decoration-none">Contactez le support</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .two-factor-verify-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .verify-card {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .verify-logo {
        transition: transform 0.3s ease;
    }

    .verify-logo:hover {
        transform: scale(1.05);
    }

    .verify-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto;
        background: linear-gradient(135deg, #6e6e6e, #8b8f70);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        box-shadow: 0 8px 16px rgba(106, 110, 81, 0.2);
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

    #code {
        letter-spacing: 10px;
        font-size: 1.5rem;
        font-weight: bold;
        height: 60px;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
    }

    @media (max-width: 768px) {
        .two-factor-verify-page {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .verify-card {
            padding: 2rem !important;
        }

        .verify-icon {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }

        #code {
            letter-spacing: 5px;
            font-size: 1.25rem;
            height: 50px;
        }
    }

    /* Animation for code input */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(106, 110, 81, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(106, 110, 81, 0); }
        100% { box-shadow: 0 0 0 0 rgba(106, 110, 81, 0); }
    }

    #code:focus {
        animation: pulse 2s infinite;
        border-color: #6e6e6e;
    }
</style>

<script>
    // Auto-focus and format code input
    document.getElementById('code').addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to 6 digits
        if (this.value.length > 6) {
            this.value = this.value.substring(0, 6);
        }

        // Auto-submit when 6 digits are entered
        if (this.value.length === 6) {
            // Wait a moment for better UX
            setTimeout(() => {
                this.form.submit();
            }, 300);
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

    // Auto-tab simulation (optional feature)
    document.getElementById('code').addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].includes(e.keyCode) ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }

        // Ensure it's a number
        if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // Add subtle animation
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('code');
        input.style.transform = 'scale(0.95)';

        setTimeout(() => {
            input.style.transition = 'transform 0.3s ease';
            input.style.transform = 'scale(1)';
        }, 100);
    });
</script>
@endsection
