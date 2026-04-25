
@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Modifier l'Utilisateur</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Tableau de bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}">
                        <div class="text-tiny">Utilisateurs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Modifier l'Utilisateur</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-style-1" method="POST" action="{{ route('admin.user.update-route') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $user->id }}"/>

                @if(session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <fieldset class="name">
                    <div class="body-title mb-10">Nom Complet <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Entrez le nom complet" name="name"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-tiny text-danger mt-2">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="email">
                        <div class="body-title mb-10">Adresse Email <span class="tf-color-1">*</span></div>
                        <input type="email" placeholder="Entrez l'email" name="email"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-tiny text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="phone">
                        <div class="body-title mb-10">Numéro de Téléphone <span class="tf-color-1">*</span></div>
                        <input type="text" placeholder="Entrez le numéro de téléphone" name="numero"
                               value="{{ old('numero', $user->numero) }}" required>
                        @error('numero')
                            <div class="text-tiny text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="password">
                        <div class="body-title mb-10">Nouveau Mot de Passe</div>
                        <input type="password" placeholder="Laissez vide pour conserver le mot de passe actuel"
                               name="password">
                        @error('password')
                            <div class="text-tiny text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="password_confirmation">
                        <div class="body-title mb-10">Confirmer le Mot de Passe</div>
                        <input type="password" placeholder="Confirmez le nouveau mot de passe"
                               name="password_confirmation">
                    </fieldset>
                </div>

                <fieldset class="utype">
                    <div class="body-title mb-10">Type d'Utilisateur <span class="tf-color-1">*</span></div>
                    <div class="select">
                        <select name="utype" required>
                            <option value="USR" {{ $user->utype == 'USR' ? 'selected' : '' }}>Utilisateur</option>
                            <option value="ADM" {{ $user->utype == 'ADM' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                    @error('utype')
                        <div class="text-tiny text-danger mt-2">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="bot mt-30">
                    <a href="{{ route('admin.users') }}" class="tf-button w208 outline">
                        Annuler
                    </a>
                    <button class="tf-button w208" type="submit">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Ajoutez tout JavaScript nécessaire ici
});
</script>
@endpush
