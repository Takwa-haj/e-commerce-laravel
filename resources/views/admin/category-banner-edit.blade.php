@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Modifier la bannière de catégorie</h3>
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
                    <a href="{{ route('admin.category-banners') }}">
                        <div class="text-tiny">Bannières de catégories</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Modifier la bannière</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.category-banner.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $banner->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Collection ou" name="title"
                                value="{{ old('title', $banner->title) }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Sous-titre</div>
                            <input class="flex-grow" type="text" placeholder="Collection Dorée" name="subtitle"
                                value="{{ old('subtitle', $banner->subtitle) }}">
                            @error('subtitle')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Texte alternatif pour l'image</div>
                            <input class="flex-grow" type="text" placeholder="Collection ou" name="alt_text"
                                value="{{ old('alt_text', $banner->alt_text) }}">
                            @error('alt_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Prix à partir de (€)</div>
                            <input class="flex-grow" type="number" step="0.01" min="0" placeholder="99.00"
                                name="price_from" value="{{ old('price_from', $banner->price_from) }}">
                            @error('price_from')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Texte d'affichage du prix (Optionnel)</div>
                            <input class="flex-grow" type="text" placeholder="À partir de €99"
                                name="price_text" value="{{ old('price_text', $banner->price_text) }}">
                            @error('price_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Texte du bouton <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Découvrir" name="button_text"
                                value="{{ old('button_text', $banner->button_text) }}" required>
                            @error('button_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Lien du bouton <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" placeholder="https://exemple.com/boutique"
                                name="button_link" value="{{ old('button_link', $banner->button_link) }}" required>
                            @error('button_link')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="category mb-3">
                            <div class="body-title">Position <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="position" required>
                                    <option value="">Sélectionner la position</option>
                                    <option value="left" {{ (old('position', $banner->position) == 'left') ? 'selected' : '' }}>Colonne gauche</option>
                                    <option value="right" {{ (old('position', $banner->position) == 'right') ? 'selected' : '' }}>Colonne droite</option>
                                </select>
                            </div>
                            @error('position')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="category mb-3">
                            <div class="body-title">Statut <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="is_active" required>
                                    <option value="1" {{ (old('is_active', $banner->is_active) == '1') ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ (old('is_active', $banner->is_active) == '0') ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            @error('is_active')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Ordre de tri</div>
                            <input class="flex-grow" type="number" placeholder="0" name="sort_order"
                                value="{{ old('sort_order', $banner->sort_order) }}">
                            @error('sort_order')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>

                <fieldset class="mb-4">
                    <div class="body-title">Description (Optionnelle)</div>
                    <textarea class="flex-grow" name="description" rows="3"
                        placeholder="Description optionnelle...">{{ old('description', $banner->description) }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <fieldset class="mb-4">
                    <div class="body-title">Image de la bannière</div>
                    <div class="upload-image flex-grow">
                        @if($banner->image)
                            <div class="item" id="imgpreview">
                                <img src="{{ asset('uploads/category-banners/' . $banner->image) }}"
                                     class="effect8" alt="Bannière actuelle" style="max-height: 200px;">
                                <div class="mt-2 text-tiny">
                                    Image actuelle : {{ $banner->image }}
                                </div>
                            </div>
                        @else
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="" class="effect8" alt="Aperçu" style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="item up-load mt-3">
                            <div class="body-title mb-2">Télécharger une nouvelle image (Optionnel)</div>
                            <label class="uploadfile" for="bannerImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez une nouvelle image ici ou <span
                                        class="tf-color">cliquez pour parcourir</span></span>
                                <span class="text-tiny">Recommandé : 690x665 pixels</span>
                                <input type="file" id="bannerImage" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="bot mt-4">
                    <a href="{{ route('admin.category-banners') }}" class="tf-button w208"
                       style="background: #6c757d; margin-right: 10px;">
                        Annuler
                    </a>
                    <button class="tf-button w208" type="submit">Mettre à jour la bannière</button>
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

    // Auto-fill price text if price_from is entered
    $('input[name="price_from"]').on('input', function() {
        const price = $(this).val();
        if (price && !$('input[name="price_text"]').val()) {
            $('input[name="price_text"]').val('À partir de €' + parseInt(price));
        }
    });
});
</script>
@endpush
