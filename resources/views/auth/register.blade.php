@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<main class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Brand Header - Using Settings Logo -->
                <div class="text-center mb-5">
                    <a href="{{ route('home.index') }}" class="d-inline-block mb-4">
                        @if(isset($settings) && $settings->logo)
                            <img src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                 alt="{{ $settings->site_name ?? 'E-commerce' }}"
                                 class="register-logo"
                                 loading="lazy">
                        @else
                            <span class="display-5 fw-bold brand-text" style="color: #2c3e50;">
                                {{ $settings->site_name ?? 'E-commerce' }}
                            </span>
                        @endif
                    </a>
                    <h2 class="h3 mb-3 fw-semibold" style="color: #2c3e50;">Créer un compte</h2>
                    <p class="text-muted">Rejoignez notre communauté</p>
                </div>

                <div class="card register-card border-0">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                                        <ul class="mb-0 mt-2 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Google Registration Button -->
                        <div class="mb-4">
                            <a href="{{ route('auth.google') }}"
                               class="btn google-register-btn w-100">
                                <svg class="me-3" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span>S'inscrire avec Google</span>
                            </a>
                        </div>

                        <!-- Separator -->
                        <div class="separator mb-4">
                            <hr class="separator-line">
                            <span class="separator-text">Ou inscrivez-vous par email</span>
                            <hr class="separator-line">
                        </div>

                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="row g-4">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-medium text-secondary">
                                            Nom complet *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input id="name"
                                                   type="text"
                                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   required
                                                   autocomplete="name"
                                                   autofocus
                                                   placeholder="Votre nom complet">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-medium text-secondary">
                                            Adresse email *
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
                                                   placeholder="votre@email.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero" class="form-label fw-medium text-secondary">
                                            Téléphone portable *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-phone text-muted"></i>
                                            </span>
                                            <input id="numero"
                                                   type="tel"
                                                   class="form-control form-control-lg @error('numero') is-invalid @enderror"
                                                   name="numero"
                                                   value="{{ old('numero') }}"
                                                   required
                                                   autocomplete="tel"
                                                   placeholder="+33 6 12 34 56 78">
                                            @error('numero')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label fw-medium text-secondary">
                                            Mot de passe *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input id="password"
                                                   type="password"
                                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                   name="password"
                                                   required
                                                   autocomplete="new-password"
                                                   placeholder="Créez un mot de passe sécurisé"
                                                   onkeyup="checkPasswordStrength(this.value)">
                                            <button class="input-group-text bg-light border-start-0 password-toggle"
                                                    type="button"
                                                    id="togglePassword">
                                                <i class="fas fa-eye" id="passwordEyeIcon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Strength Meter -->
                                        <div class="mt-3">
                                            <div class="password-strength">
                                                <div id="passwordStrengthBar" class="password-strength-bar"></div>
                                            </div>
                                            <div id="passwordFeedback" class="mt-2 small fw-medium"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password-confirm" class="form-label fw-medium text-secondary">
                                            Confirmer le mot de passe *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input id="password-confirm"
                                                   type="password"
                                                   class="form-control form-control-lg"
                                                   name="password_confirmation"
                                                   required
                                                   autocomplete="new-password"
                                                   placeholder="Confirmez votre mot de passe">
                                            <button class="input-group-text bg-light border-start-0 password-toggle"
                                                    type="button"
                                                    id="toggleConfirmPassword">
                                                <i class="fas fa-eye" id="confirmPasswordEyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Requirements -->
                                <div class="col-12">
                                    <div class="password-requirements mt-2">
                                        <p class="small text-muted mb-2 fw-medium">Le mot de passe doit contenir :</p>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="password-requirement" id="lengthReq">
                                                    <i class="fas fa-circle me-2"></i>
                                                    <span>8 caractères minimum</span>
                                                </div>
                                                <div class="password-requirement" id="uppercaseReq">
                                                    <i class="fas fa-circle me-2"></i>
                                                    <span>1 lettre majuscule</span>
                                                </div>
                                                <div class="password-requirement" id="lowercaseReq">
                                                    <i class="fas fa-circle me-2"></i>
                                                    <span>1 lettre minuscule</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="password-requirement" id="numberReq">
                                                    <i class="fas fa-circle me-2"></i>
                                                    <span>1 chiffre</span>
                                                </div>
                                                <div class="password-requirement" id="specialReq">
                                                    <i class="fas fa-circle me-2"></i>
                                                    <span>1 caractère spécial</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input @error('terms') is-invalid @enderror"
                                               type="checkbox"
                                               name="terms"
                                               id="terms"
                                               required>
                                        <label class="form-check-label small" for="terms">
                                            J'accepte les
                                            <a href="#" class="text-decoration-none" style="color: #d4af37;">conditions d'utilisation</a>
                                            et la
                                            <a href="#" class="text-decoration-none" style="color: #d4af37;">politique de confidentialité</a>
                                            *
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Marketing Consent -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="marketing"
                                               id="marketing">
                                        <label class="form-check-label small text-muted" for="marketing">
                                            Je souhaite recevoir des offres promotionnelles et des nouveaux produits
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-3">
                                    <div class="d-grid">
                                        <button type="submit"
                                                class="btn btn-lg register-submit-btn">
                                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center mt-4 pt-4 border-top">
                            <p class="text-muted mb-0">
                                Vous avez déjà un compte ?
                                <a href="{{ route('login') }}"
                                   class="text-decoration-none fw-semibold" style="color: #d4af37;">
                                    Connectez-vous
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Register Page Specific Styles */
.register-page {
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

.register-logo {
    max-width: 220px;
    max-height: 80px;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.register-logo:hover {
    transform: scale(1.02);
}

/* Register Card */
.register-card {
    background: white;
    border-radius: 1.25rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.register-card:hover {
    box-shadow: 0 20px 60px rgba(212, 175, 55, 0.08);
}

/* Google Register Button */
.google-register-btn {
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

.google-register-btn:hover {
    background-color: #f8f9fa;
    border-color: #d4af37;
    color: #2c3e50;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.15);
}

.google-register-btn svg {
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
.register-submit-btn {
    background-color: #d4af37;
    border: none;
    color: white;
    border-radius: 0.75rem;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.register-submit-btn:hover {
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

/* Password Strength Meter */
.password-strength {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    width: 0%;
    transition: width 0.3s ease, background-color 0.3s ease;
    border-radius: 3px;
}

/* Password Requirements */
.password-requirements {
    background: #f8f9fa;
    padding: 1.25rem;
    border-radius: 0.75rem;
    border: 2px solid #e9ecef;
}

.password-requirement {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    color: #95a5a6;
    transition: all 0.3s ease;
}

.password-requirement i {
    font-size: 0.5rem;
    vertical-align: middle;
}

.password-requirement.valid {
    color: #10B981;
}

.password-requirement.valid i {
    color: #10B981;
}

.password-requirement.invalid {
    color: #EF4444;
}

.password-requirement.invalid i {
    color: #EF4444;
}

/* Form Check */
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

/* Border Top */
.border-top {
    border-color: #e9ecef !important;
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
    .register-page {
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

    .register-logo {
        max-width: 180px;
        max-height: 60px;
    }

    .brand-text {
        font-size: 1.75rem;
    }

    .password-requirements .row {
        flex-direction: column;
    }

    .password-requirements .col-md-6 {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .register-page {
        padding: 40px 0;
    }

    .register-logo {
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

    .password-requirements {
        padding: 1rem;
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

    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('password-confirm');
            const eyeIcon = document.getElementById('confirmPasswordEyeIcon');

            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }

    // Initialize password strength check on page load
    const passwordInput = document.getElementById('password');
    if (passwordInput && passwordInput.value) {
        checkPasswordStrength(passwordInput.value);
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

    // Real-time password confirmation validation
    const confirmPasswordInput = document.getElementById('password-confirm');
    if (passwordInput && confirmPasswordInput) {
        [passwordInput, confirmPasswordInput].forEach(input => {
            input.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword && password !== confirmPassword) {
                    confirmPasswordInput.classList.add('is-invalid');
                } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                }
            });
        });
    }
});

// Password strength checker
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrengthBar');
    const feedback = document.getElementById('passwordFeedback');

    if (!strengthBar || !feedback) return;

    // Check criteria
    const criteria = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };

    // Update requirement indicators
    updateRequirement('lengthReq', criteria.length);
    updateRequirement('uppercaseReq', criteria.uppercase);
    updateRequirement('lowercaseReq', criteria.lowercase);
    updateRequirement('numberReq', criteria.number);
    updateRequirement('specialReq', criteria.special);

    // Calculate strength
    const strength = Object.values(criteria).filter(Boolean).length;
    const width = (strength / 5) * 100;

    // Update strength bar
    strengthBar.style.width = `${width}%`;

    // Update colors and feedback
    if (strength === 0) {
        strengthBar.style.backgroundColor = '#EF4444';
        feedback.textContent = '';
        feedback.className = 'mt-2 small fw-medium';
    } else if (strength <= 2) {
        strengthBar.style.backgroundColor = '#F59E0B';
        feedback.textContent = 'Mot de passe faible';
        feedback.className = 'mt-2 small fw-medium text-warning';
    } else if (strength <= 4) {
        strengthBar.style.backgroundColor = '#FBBF24';
        feedback.textContent = 'Mot de passe moyen';
        feedback.className = 'mt-2 small fw-medium text-warning';
    } else {
        strengthBar.style.backgroundColor = '#10B981';
        feedback.textContent = 'Mot de passe fort';
        feedback.className = 'mt-2 small fw-medium text-success';
    }
}

function updateRequirement(id, isValid) {
    const element = document.getElementById(id);
    if (element) {
        if (isValid) {
            element.classList.add('valid');
            element.classList.remove('invalid');
            element.querySelector('i')?.classList.remove('fa-circle');
            element.querySelector('i')?.classList.add('fa-check-circle');
        } else {
            element.classList.add('invalid');
            element.classList.remove('valid');
            element.querySelector('i')?.classList.remove('fa-check-circle');
            element.querySelector('i')?.classList.add('fa-circle');
        }
    }
}
</script>
@endsection
