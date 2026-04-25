@extends('layouts.app')

@section('title', 'Vérification Email')

@section('content')
<main class="verify-email-page" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card verify-card shadow border-0">
                    <div class="card-body p-5 text-center">
                        <!-- Logo -->
                        <div class="mb-5">
                            <a href="{{ route('home.index') }}" class="d-inline-block">
                                @if($settings->logo ?? false)
                                    <img src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                         alt="{{ $settings->site_name ?? 'E-commerce' }}"
                                         class="verify-logo" style="height: 60px;">
                                @else
                                    <h1 class="h3 fw-bold text-primary">
                                        {{ $settings->site_name ?? 'E-commerce' }}
                                    </h1>
                                @endif
                            </a>
                        </div>

                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="verify-icon">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2 class="h3 mb-3 fw-bold">Vérifiez votre adresse email</h2>

                        <!-- Message -->
                        <p class="text-muted mb-4">
                            Nous avons envoyé un lien de vérification à l'adresse :
                            <strong class="text-dark">{{ $email }}</strong>
                        </p>

                        <p class="text-muted mb-5">
                            Veuillez vérifier votre boîte de réception (et vos spams) et cliquer sur le lien
                            pour activer votre compte. Ce lien expirera dans 60 minutes.
                        </p>

                        <!-- Steps -->
                        <div class="steps mb-5">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h6 class="fw-semibold">Vérifiez votre email</h6>
                                    <p class="text-muted small">Cliquez sur le lien dans l'email que nous vous avons envoyé</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h6 class="fw-semibold">Accédez à votre compte</h6>
                                    <p class="text-muted small">Profitez de toutes les fonctionnalités de votre compte</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h6 class="fw-semibold">Commencez à acheter</h6>
                                    <p class="text-muted small">Explorez nos produits et faites vos premiers achats</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-3">
                            @if($canResend)
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                        <i class="fas fa-paper-plane me-2"></i>Renvoyer l'email de vérification
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg w-100 py-3" disabled>
                                    <i class="fas fa-clock me-2"></i>Attendez avant de renvoyer (5 min)
                                </button>
                            @endif

                            <a href="{{ route('user.account.details') }}"
                               class="btn btn-outline-primary btn-lg w-100 py-3">
                                <i class="fas fa-user-edit me-2"></i>Modifier mon email
                            </a>

                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="btn btn-outline-dark btn-lg w-100 py-3">
                                <i class="fas fa-sign-out-alt me-2"></i>Me déconnecter
                            </a>
                        </div>

                        <!-- Support -->
                        <div class="mt-5 pt-4 border-top">
                            <p class="text-muted small">
                                Vous n'avez pas reçu l'email ? Vérifiez vos spams ou
                                <a href="{{ route('support') }}" class="text-decoration-none">contactez notre support</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<style>
    .verify-email-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
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
        width: 100px;
        height: 100px;
        margin: 0 auto;
        background: linear-gradient(135deg, #6e6e6e, #8b8f70);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        box-shadow: 0 10px 20px rgba(106, 110, 81, 0.2);
    }

    .steps {
        text-align: left;
        max-width: 400px;
        margin: 0 auto;
    }

    .step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .step:not(:last-child):after {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        bottom: -1.5rem;
        width: 2px;
        background: #dee2e6;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #6e6e6e;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
        flex-shrink: 0;
        z-index: 1;
    }

    .step-content h6 {
        margin-bottom: 0.25rem;
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

    .btn-outline-primary {
        color: #6e6e6e;
        border-color: #6e6e6e;
    }

    .btn-outline-primary:hover {
        background-color: #6e6e6e;
        border-color: #6e6e6e;
        color: white;
    }

    @media (max-width: 768px) {
        .verify-email-page {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .verify-card {
            padding: 2rem !important;
        }

        .verify-icon {
            width: 80px;
            height: 80px;
            font-size: 36px;
        }

        .steps {
            max-width: 100%;
        }
    }
</style>
@endsection
