@extends('layouts.app')

@section('title', 'Vérification Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="fas fa-envelope me-2 text-primary"></i>Vérification de l'email</h4>
                </div>
                <div class="card-body">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </div>
                    @endif

                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="fas fa-envelope-open-text fa-4x text-primary"></i>
                        </div>

                        <h5 class="mb-3">Vérifiez votre adresse email</h5>

                        <p class="text-muted mb-4">
                            Avant de continuer, veuillez vérifier votre email en cliquant sur le lien que nous vous avons envoyé.
                            Si vous n'avez pas reçu l'email, cliquez sur le bouton ci-dessous pour en recevoir un nouveau.
                        </p>

                        <div class="d-flex justify-content-center gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Renvoyer l'email de vérification
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('user.index') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
