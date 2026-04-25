@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Ajouter une Nouvelle Bannière Boutique</h3>
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
                    <a href="{{ route('admin.shop-banners') }}">
                        <div class="text-tiny">Bannières Boutique</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouvelle</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.shop-banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Collection Femme"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Sous-titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="ACCESSOIRES" name="subtitle"
                                value="{{ old('subtitle') }}" required>
                            @error('subtitle')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Slogan (Optionnel)</div>
                            <input class="flex-grow" type="text" placeholder="Nouvelle Collection" name="tagline"
                                value="{{ old('tagline') }}">
                            @error('tagline')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Texte Alternatif pour l'Image</div>
                            <input class="flex-grow" type="text" placeholder="Accessoires femme" name="alt_text"
                                value="{{ old('alt_text') }}">
                            @error('alt_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Texte du Bouton <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Acheter maintenant" name="button_text"
                                value="{{ old('button_text', 'Acheter maintenant') }}" required>
                            @error('button_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Lien du Bouton <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" placeholder="https://example.com/boutique"
                                name="button_link" value="{{ old('button_link') }}" required>
                            @error('button_link')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Couleur de Fond</div>
                            <div class="d-flex align-items-center">
                                <input class="flex-grow" type="text" placeholder="#f5e6e0" name="background_color"
                                    value="{{ old('background_color', '#f5e6e0') }}">
                                <input type="color" class="ms-2" id="colorPicker"
                                    value="{{ old('background_color', '#f5e6e0') }}"
                                    style="width: 40px; height: 38px; border-radius: 4px;">
                            </div>
                            @error('background_color')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

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

                        <fieldset class="name mb-3">
                            <div class="body-title">Ordre de Tri</div>
                            <input class="flex-grow" type="number" placeholder="0" name="sort_order"
                                value="{{ old('sort_order', 0) }}">
                            @error('sort_order')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>

                <fieldset class="mb-4">
                    <div class="body-title">Description (Optionnel)</div>
                    <textarea class="flex-grow" name="description" rows="3"
                        placeholder="Les accessoires sont le meilleur moyen de mettre à jour votre look...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <fieldset class="mb-4">
                    <div class="body-title">Image de la Bannière <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" class="effect8" alt="Aperçu" style="max-height: 200px;">
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="bannerImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez votre image ici ou <span
                                        class="tf-color">cliquez pour parcourir</span></span>
                                <span class="text-tiny">Recommandé : 630x450 pixels</span>
                                <input type="file" id="bannerImage" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="bot mt-4">
                    <a href="{{ route('admin.shop-banners') }}" class="tf-button w208"
                       style="background: #6c757d; margin-right: 10px;">
                        Annuler
                    </a>
                    <button class="tf-button w208" type="submit">Enregistrer la Bannière</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Image preview functionality
    $('#bannerImage').on('change', function(e) {
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

    // Color picker sync
    const colorInput = $('input[name="background_color"]');
    const colorPicker = $('#colorPicker');

    colorPicker.on('change', function() {
        colorInput.val($(this).val());
    });

    colorInput.on('input', function() {
        colorPicker.val($(this).val());
    });

    // Auto-fill alt text if empty
    $('input[name="title"]').on('blur', function() {
        const title = $(this).val();
        const subtitle = $('input[name="subtitle"]').val();
        const altTextInput = $('input[name="alt_text"]');

        if (title && subtitle && !altTextInput.val()) {
            altTextInput.val(title + ' ' + subtitle);
        }
    });
});
</script>
@endpush
