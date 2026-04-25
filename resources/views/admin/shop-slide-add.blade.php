@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Ajouter un Nouveau Slide Boutique</h3>
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
                    <a href="{{ route('admin.shop-slides') }}">
                        <div class="text-tiny">Slides Boutique</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouveau</div>
                </li>
            </ul>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.shop-slide.store') }}" method="POST" enctype="multipart/form-data" id="slideForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Accessoires Femme" name="title"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Slogan</div>
                            <input class="flex-grow" type="text" placeholder="Offre Limitée" name="tagline"
                                value="{{ old('tagline') }}">
                            @error('tagline')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Sous-titre</div>
                            <input class="flex-grow" type="text" placeholder="Collection Or" name="subtitle"
                                value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Texte du Bouton</div>
                            <input class="flex-grow" type="text" placeholder="Acheter maintenant" name="button_text"
                                value="{{ old('button_text') }}">
                            @error('button_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        {{-- IMPORTANT : Le nom doit être 'button_link' et non 'link' --}}
                        <fieldset class="name mb-3">
                            <div class="body-title">Lien du Bouton <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" placeholder="https://example.com/boutique"
                                name="button_link" value="{{ old('button_link') }}" required>
                            @error('button_link')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="category mb-3">
                            <div class="body-title">Position du Texte</div>
                            <div class="select flex-grow">
                                <select name="text_position">
                                    <option value="left" {{ old('text_position') == 'left' ? 'selected' : '' }}>Gauche</option>
                                    <option value="center" {{ old('text_position') == 'center' ? 'selected' : '' }}>Centre</option>
                                    <option value="right" {{ old('text_position') == 'right' ? 'selected' : '' }}>Droite</option>
                                </select>
                            </div>
                            @error('text_position')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <fieldset class="name mb-3">
                            <div class="body-title">Couleur de Fond</div>
                            <input class="flex-grow" type="color" name="background_color"
                                value="{{ old('background_color', '#f5e6e0') }}">
                            @error('background_color')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-4">
                        {{-- IMPORTANT : Le nom doit être 'is_active' et non 'status' --}}
                        <fieldset class="category mb-3">
                            <div class="body-title">Statut <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="is_active" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            @error('is_active')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-4">
                        <fieldset class="name mb-3">
                            <div class="body-title">Ordre</div>
                            <input class="flex-grow" type="number" placeholder="0" name="order"
                                value="{{ old('order', 0) }}">
                            @error('order')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>

                <fieldset class="mb-4">
                    <div class="body-title">Image du Slide <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" class="effect8" alt="Aperçu" style="max-height: 200px;">
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="slideImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez votre image ici ou <span
                                        class="tf-color">cliquez pour parcourir</span></span>
                                <span class="text-tiny">Recommandé : 1260x450 pixels</span>
                                <input type="file" id="slideImage" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="bot mt-4">
                    <a href="{{ route('admin.shop-slides') }}" class="tf-button w208"
                       style="background: #6c757d; margin-right: 10px;">
                        Annuler
                    </a>
                    <button class="tf-button w208" type="submit" id="submitBtn">Enregistrer le Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Shop slide add page loaded');

    // Image preview functionality
    $('#slideImage').on('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imgpreview img').attr('src', e.target.result);
                $('#imgpreview').show();
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
