@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Ajouter un Nouveau Slide</h3>
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
                    <a href="{{ route('admin.slides') }}">
                        <div class="text-tiny">Slides</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouveau Slide</div>
                </li>
            </ul>
        </div>

        <!-- new-slide -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.slide.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Colonne de Gauche : Champs de Texte -->
                    <div class="col-lg-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Slogan <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="ex : Collection Exclusive"
                                   name="tagline" value="{{ old('tagline') }}" required>
                        </fieldset>
                        @error('tagline') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="ex : Bijoux de Luxe"
                                   name="title" value="{{ old('title') }}" required>
                        </fieldset>
                        @error('title') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Sous-titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="ex : Pour les Moments Précieux"
                                   name="subtitle" value="{{ old('subtitle') }}" required>
                        </fieldset>
                        @error('subtitle') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Description</div>
                            <textarea class="flex-grow" name="description" rows="3"
                                      placeholder="Des créations uniques pour des moments précieux...">{{ old('description') }}</textarea>
                        </fieldset>
                        @error('description') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Texte du Bouton</div>
                            <input class="flex-grow" type="text" placeholder="ex : Explorer la collection"
                                   name="button_text" value="{{ old('button_text', 'Explorer la collection') }}">
                        </fieldset>
                        @error('button_text') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Lien <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" placeholder="https://example.com/boutique"
                                   name="link" value="{{ old('link') }}" required>
                        </fieldset>
                        @error('link') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>

                    <!-- Colonne de Droite : Image et Paramètres -->
                    <div class="col-lg-6">
                        <!-- Champs de Prix -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Prix (€)</div>
                                    <input class="flex-grow" type="number" step="0.01" min="0"
                                           name="price" value="{{ old('price') }}" placeholder="299.00">
                                </fieldset>
                                @error('price') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Texte d'Affichage du Prix</div>
                                    <input class="flex-grow" type="text"
                                           name="price_text" value="{{ old('price_text') }}" placeholder="À partir de €299">
                                </fieldset>
                                @error('price_text') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Paramètres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Couleur de Fond</div>
                                    <input class="flex-grow" type="color" name="background_color"
                                           value="{{ old('background_color', '#0a0a0a') }}">
                                </fieldset>
                                @error('background_color') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Position du Texte</div>
                                    <select class="flex-grow" name="text_position">
                                        <option value="left" @selected(old('text_position', 'left') == 'left')>Gauche</option>
                                        <option value="center" @selected(old('text_position') == 'center')>Centre</option>
                                        <option value="right" @selected(old('text_position') == 'right')>Droite</option>
                                    </select>
                                </fieldset>
                                @error('text_position') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <fieldset class="name mb-3">
                            <div class="body-title">Position de l'Ordre</div>
                            <input class="flex-grow" type="number" name="order"
                                   value="{{ old('order', 0) }}" min="0" placeholder="0">
                            <div class="text-tiny text-muted">Les nombres les plus bas apparaissent en premier</div>
                        </fieldset>
                        @error('order') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Statut</div>
                            <div class="select flex-grow">
                                <select name="is_active" required>
                                    <option value="">Sélectionner le Statut</option>
                                    <option value="1" @selected(old('is_active') == '1')>Actif</option>
                                    <option value="0" @selected(old('is_active') == '0')>Inactif</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('is_active') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Image Principale -->
                <fieldset class="mb-4">
                    <div class="body-title">Image Principale du Slide <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="#" class="effect8" alt="Aperçu" style="max-height: 200px;">
                            <div class="preview-info">L'image principale sera utilisée pour l'arrière-plan et le premier plan</div>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="mainImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez votre image de slide ici ou <span class="tf-color">cliquez pour parcourir</span></span>
                                <span class="text-tiny">Recommandé : 1920x1080px ou plus, haute qualité</span>
                                <input type="file" id="mainImage" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                    @error('image') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </fieldset>

                <!-- Image en Vedette -->
                <fieldset class="mb-4">
                    <div class="body-title">Image en Vedette (Optionnel)</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="featuredpreview" style="display:none">
                            <img src="#" class="effect8" alt="Aperçu en Vedette" style="max-height: 200px;">
                            <div class="preview-info">Image produit en vedette (optionnel)</div>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="featuredImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez l'image en vedette ici ou <span class="tf-color">cliquez pour parcourir</span></span>
                                <span class="text-tiny">Optionnel : Image produit en gros plan pour le côté droit</span>
                                <input type="file" id="featuredImage" name="featured_image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('featured_image') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </fieldset>

                <div class="bot mt-4">
                    <div></div>
                    <button class="tf-button w208" type="submit">Enregistrer le Slide</button>
                </div>
            </form>
        </div>
        <!-- /new-slide -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection

@push('styles')
<style>
.preview-info {
    font-size: 0.75rem;
    color: #666;
    margin-top: 5px;
    text-align: center;
}

.upload-image .item {
    margin-bottom: 15px;
}

.text-muted {
    color: #6c757d !important;
}

.alert {
    padding: 8px 12px;
    margin: 5px 0;
    border-radius: 4px;
    font-size: 0.875rem;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Main image preview
    $('#mainImage').on('change', function(e) {
        previewImage(this, '#imgpreview');
    });

    // Featured image preview
    $('#featuredImage').on('change', function(e) {
        previewImage(this, '#featuredpreview');
    });

    function previewImage(input, previewId) {
        const file = input.files[0];
        if (file) {
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size should be less than 5MB');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewId + ' img').attr('src', e.target.result);
                $(previewId).show();
            }
            reader.readAsDataURL(file);
        }
    }

    // Auto-generate suggestions
    $("input[name='title']").on('input', function() {
        const title = $(this).val();
        if (title && !$("input[name='subtitle']").val()) {
            $("input[name='subtitle']").attr('placeholder', 'ex: ' + title + ' Collection');
        }

        if (title && !$("textarea[name='description']").val()) {
            $("textarea[name='description']").attr('placeholder', 'Découvrez notre collection exclusive ' + title.toLowerCase() + '...');
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        const mainImage = $('#mainImage')[0].files[0];
        if (!mainImage) {
            alert('Please select a main slide image');
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
