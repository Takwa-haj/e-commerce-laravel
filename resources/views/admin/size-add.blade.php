@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Nouvelle Taille</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Tableau de Bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.sizes') }}">
                        <div class="text-tiny">Tailles</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouvelle Taille</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.size.store') }}" method="POST">
                @csrf

                <fieldset class="name">
                    <div class="body-title">Nom de la Taille <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Ex: Petit, Moyen, Grand, etc."
                           name="name" value="{{ old('name') }}" aria-required="true" required>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Code <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Ex: S, M, L, XL"
                           name="code" value="{{ old('code') }}" aria-required="true" required>
                    <div class="text-tiny mt-2">Code court pour identifier la taille</div>
                </fieldset>
                @error('code') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Ordre d'affichage</div>
                    <input class="flex-grow" type="number" placeholder="Ex: 1, 2, 3..."
                           name="order" value="{{ old('order', 0) }}">
                    <div class="text-tiny mt-2">Définit l'ordre d'affichage des tailles</div>
                </fieldset>
                @error('order') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Description</div>
                    <textarea class="flex-grow" name="description" rows="3"
                              placeholder="Description optionnelle de la taille">{{ old('description') }}</textarea>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="category">
                    <div class="body-title">Statut</div>
                    <div class="select flex-grow">
                        <select name="status">
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </fieldset>
                @error('status') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
