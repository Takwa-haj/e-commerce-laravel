@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Ajouter une Nouvelle Bannière</h3>
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
                    <a href="{{ route('admin.top-banners.index') }}">
                        <div class="text-tiny">Top Banners</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouvelle Bannière</div>
                </li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.top-banners.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-6">
                        <h5 class="mb-3">Paramètres du Message</h5>

                        <fieldset class="name mb-3">
                            <div class="body-title">Message de la Bannière <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text"
                                   placeholder="ex: Livraison gratuite pour les commandes de plus de {threshold}€"
                                   name="message" value="{{ old('message', 'Livraison gratuite pour les commandes de plus de {threshold}€') }}"
                                   required>
                            <div class="text-tiny mt-1">Utilisez {threshold} pour afficher le montant de livraison gratuite</div>
                        </fieldset>
                        @error('message') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Seuil de Livraison Gratuite (€)</div>
                            <input class="flex-grow" type="number" step="0.01" min="0"
                                   placeholder="ex: 50.00"
                                   name="free_shipping_threshold" value="{{ old('free_shipping_threshold') }}">
                        </fieldset>
                        @error('free_shipping_threshold') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-6">
                        <h5 class="mb-3">Liens des Réseaux Sociaux</h5>

                        <fieldset class="name mb-3">
                            <div class="body-title">URL Facebook</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://facebook.com/votrepage"
                                   name="facebook_url" value="{{ old('facebook_url') }}">
                        </fieldset>
                        @error('facebook_url') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">URL Instagram</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://instagram.com/votrepage"
                                   name="instagram_url" value="{{ old('instagram_url') }}">
                        </fieldset>
                        @error('instagram_url') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">URL Twitter</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://twitter.com/votrepage"
                                   name="twitter_url" value="{{ old('twitter_url') }}">
                        </fieldset>
                        @error('twitter_url') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">URL Pinterest</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://pinterest.com/votrepage"
                                   name="pinterest_url" value="{{ old('pinterest_url') }}">
                        </fieldset>
                        @error('pinterest_url') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">URL YouTube</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://youtube.com/votrepage"
                                   name="youtube_url" value="{{ old('youtube_url') }}">
                        </fieldset>
                        @error('youtube_url') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">URL TikTok</div>
                            <input class="flex-grow" type="url"
                                   placeholder="https://tiktok.com/@votrepage"
                                   name="tiktok_url" value="{{ old('tiktok_url') }}">
                        </fieldset>
                        @error('tiktok_url') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <h5 class="mb-3">Paramètres d'Apparence</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="name mb-3">
                                    <div class="body-title">Couleur de Fond</div>
                                    <input class="flex-grow" type="color"
                                           name="background_color"
                                           value="{{ old('background_color', '#0a0a0a') }}">
                                </fieldset>
                                @error('background_color') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <fieldset class="name mb-3">
                                    <div class="body-title">Couleur du Texte</div>
                                    <input class="flex-grow" type="color"
                                           name="text_color"
                                           value="{{ old('text_color', '#ffffff') }}">
                                </fieldset>
                                @error('text_color') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <fieldset class="name mb-3">
                            <div class="body-title">Ordre d'Affichage</div>
                            <input class="flex-grow" type="number" min="0"
                                   name="sort_order" value="{{ old('sort_order', 0) }}">
                        </fieldset>
                        @error('sort_order') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-6">
                        <h5 class="mb-3">Statut</h5>

                        <fieldset class="name mb-3">
                            <div class="body-title">Statut Actif</div>
                            <div class="select flex-grow">
                                <select name="is_active" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('is_active') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="bot mt-4 d-flex justify-content-between">
                    <a href="{{ route('admin.top-banners.index') }}" class="tf-button style-3">
                        <i class="icon-arrow-left"></i> Retour à la Liste
                    </a>
                    <button class="tf-button w208" type="submit">
                        <i class="icon-save"></i> Enregistrer la Bannière
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.alert {
    padding: 12px 15px;
    margin: 5px 0;
    border-radius: 4px;
    font-size: 0.875rem;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.wg-box {
    padding: 25px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
</style>
@endpush
