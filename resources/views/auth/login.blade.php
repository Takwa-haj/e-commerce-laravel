@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<main class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Brand Header - Using Settings Logo -->
                <div class="text-center mb-5">
                    <a href="{{ route('home.index') }}" class="d-inline-block mb-4">
                        @if(isset($settings) && $settings->logo)
                            <img src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                 alt="{{ $settings->site_name ?? 'E-commerce' }}"
                                 class="login-logo"
                                 loading="lazy">
                        @else
                            <span class="display-5 fw-bold brand-text" style="color: #2c3e50;">
                                {{ $settings->site_name ?? 'E-commerce' }}
                            </span>
                        @endif
                    </a>
                    <h2 class="h3 mb-3 fw-semibold" style="color: #2c3e50;">Bienvenue</h2>
                    <p class="text-muted">Connectez-vous à votre compte</p>
                </div>

                <div class="card login-card border-0">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Success Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                                        <ul class="mb-0 mt-2 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Google Login Button -->
                        <div class="mb-4">
                            <a href="{{ route('auth.google') }}"
                               class="btn google-login-btn w-100">
                                <svg class="me-3" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span>Continuer avec Google</span>
                            </a>
                        </div>

                        <!-- Separator -->
                        <div class="separator mb-4">
                            <hr class="separator-line">
                            <span class="separator-text">Ou</span>
                            <hr class="separator-line">
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf

                            <!-- Email -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label fw-medium text-secondary">
                                    Adresse e-mail
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email"
                                           type="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autocomplete="email"
                                           autofocus
                                           placeholder="votre@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label fw-medium text-secondary mb-0">
                                        Mot de passe
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                           class="text-decoration-none small" style="color: #d4af37;">
                                            Mot de passe oublié ?
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password"
                                           type="password"
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                           placeholder="••••••••">
                                    <button class="input-group-text bg-light border-start-0 password-toggle"
                                            type="button"
                                            id="togglePassword">
                                        <i class="fas fa-eye" id="passwordEyeIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit"
                                        class="btn btn-lg login-submit-btn">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Vous n'avez pas de compte ?
                                    <a href="{{ route('register') }}"
                                       class="text-decoration-none fw-semibold" style="color: #d4af37;">
                                        Créez votre compte
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Guest Checkout Notice -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Vous souhaitez passer commande sans compte ?
                        <a href="{{ route('cart.checkout') }}"
                           class="text-decoration-none" style="color: #d4af37;">
                            Payer en tant qu'invité
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Login Page Specific Styles */
.login-page {
    padding: 100px 0;
    background: #f8f9fa;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

/* Brand Styles - Matching admin panel */
.brand-text {
    color: #2c3e50;
    font-weight: 700;
    letter-spacing: -0.5px;
    font-size: 2.25rem;
}

.login-logo {
    max-width: 220px;
    max-height: 80px;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.login-logo:hover {
    transform: scale(1.02);
}

/* Login Card */
.login-card {
    background: white;
    border-radius: 1.25rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.login-card:hover {
    box-shadow: 0 20px 60px rgba(212, 175, 55, 0.08);
}

/* Google Login Button */
.google-login-btn {
    border: 2px solid #e9ecef;
    background: white;
    color: #2c3e50;
    border-radius: 0.75rem;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.google-login-btn:hover {
    background-color: #f8f9fa;
    border-color: #d4af37;
    color: #2c3e50;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.15);
}

.google-login-btn svg {
    width: 20px;
    height: 20px;
}

/* Form Controls - Matching admin panel */
.form-control-lg {
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
}

.form-control-lg:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    outline: none;
}

.input-group-text {
    border-radius: 0.75rem;
    padding: 0.875rem 1rem;
    border: 2px solid #e9ecef;
    background: #f8f9fa;
}

.input-group .form-control-lg {
    border-radius: 0 0.75rem 0.75rem 0;
}

.input-group .input-group-text:first-child {
    border-radius: 0.75rem 0 0 0.75rem;
}

/* Password Toggle Button */
.password-toggle {
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 0 0.75rem 0.75rem 0 !important;
}

.password-toggle:hover {
    background-color: #e9ecef;
    color: #d4af37;
}

/* Submit Button */
.login-submit-btn {
    background-color: #d4af37;
    border: none;
    color: white;
    border-radius: 0.75rem;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.login-submit-btn:hover {
    background-color: #b8941f;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.25);
}

/* Separator */
.separator {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
}

.separator-line {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, transparent, #e9ecef, transparent);
}

.separator-text {
    font-size: 0.875rem;
    color: #95a5a6;
    background: white;
    padding: 0 1rem;
    font-weight: 500;
}

/* Checkbox */
.form-check-input {
    width: 1.2rem;
    height: 1.2rem;
    margin-top: 0.15rem;
    border: 2px solid #e9ecef;
    cursor: pointer;
    border-radius: 0.375rem;
}

.form-check-input:checked {
    background-color: #d4af37;
    border-color: #d4af37;
}

.form-check-label {
    font-size: 0.95rem;
    padding-left: 0.25rem;
    cursor: pointer;
}

/* Alerts */
.alert {
    border-radius: 0.75rem;
    border: none;
    padding: 1.25rem 1.5rem;
    font-size: 1rem;
}

.alert-success {
    background-color: #D1FAE5;
    color: #065F46;
    border-left: 4px solid #10B981;
}

.alert-danger {
    background-color: #FEE2E2;
    color: #7F1D1D;
    border-left: 4px solid #EF4444;
}

/* Responsive */
@media (max-width: 768px) {
    .login-page {
        padding: 60px 0;
    }

    .card-body {
        padding: 1.5rem !important;
    }

    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
    }

    .login-logo {
        max-width: 180px;
        max-height: 60px;
    }

    .brand-text {
        font-size: 1.75rem;
    }
}

@media (max-width: 576px) {
    .login-page {
        padding: 40px 0;
    }

    .login-logo {
        max-width: 150px;
        max-height: 50px;
    }

    .brand-text {
        font-size: 1.5rem;
    }

    .separator-text {
        font-size: 0.8rem;
        padding: 0 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('passwordEyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endsection
