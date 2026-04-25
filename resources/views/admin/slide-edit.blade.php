@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Modifier le Slide</h3>
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
                    <div class="text-tiny">Modifier le Slide</div>
                </li>
            </ul>
        </div>

        <!-- edit-slide -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.slide.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $slide->id }}">

                <div class="row">
                    <!-- Colonne de Gauche : Champs de Texte -->
                    <div class="col-lg-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Slogan <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" name="tagline"
                                   value="{{ old('tagline', $slide->tagline) }}" required>
                        </fieldset>
                        @error('tagline') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" name="title"
                                   value="{{ old('title', $slide->title) }}" required>
                        </fieldset>
                        @error('title') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Sous-titre <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" name="subtitle"
                                   value="{{ old('subtitle', $slide->subtitle) }}" required>
                        </fieldset>
                        @error('subtitle') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Description</div>
                            <textarea class="flex-grow" name="description" rows="3">{{ old('description', $slide->description) }}</textarea>
                        </fieldset>
                        @error('description') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Texte du Bouton</div>
                            <input class="flex-grow" type="text" name="button_text"
                                   value="{{ old('button_text', $slide->button_text) }}">
                        </fieldset>
                        @error('button_text') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Lien <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" name="link"
                                   value="{{ old('link', $slide->link) }}" required>
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
                                           name="price" value="{{ old('price', $slide->price) }}">
                                </fieldset>
                                @error('price') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Texte d'Affichage du Prix</div>
                                    <input class="flex-grow" type="text" name="price_text"
                                           value="{{ old('price_text', $slide->price_text) }}">
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
                                           value="{{ old('background_color', $slide->background_color) }}">
                                </fieldset>
                                @error('background_color') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <fieldset class="name">
                                    <div class="body-title">Position du Texte</div>
                                    <select class="flex-grow" name="text_position">
                                        <option value="left" @selected(old('text_position', $slide->text_position) == 'left')>Gauche</option>
                                        <option value="center" @selected(old('text_position', $slide->text_position) == 'center')>Centre</option>
                                        <option value="right" @selected(old('text_position', $slide->text_position) == 'right')>Droite</option>
                                    </select>
                                </fieldset>
                                @error('text_position') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <fieldset class="name mb-3">
                            <div class="body-title">Position de l'Ordre</div>
                            <input class="flex-grow" type="number" name="order"
                                   value="{{ old('order', $slide->order) }}" min="0">
                        </fieldset>
                        @error('order') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <fieldset class="name mb-3">
                            <div class="body-title">Statut</div>
                            <div class="select flex-grow">
                                <select name="is_active" required>
                                    <option value="1" @selected(old('is_active', $slide->is_active) == 1)>Actif</option>
                                    <option value="0" @selected(old('is_active', $slide->is_active) == 0)>Inactif</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('is_active') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Image Principale Actuelle -->
                <div class="mb-3">
                    <div class="body-title">Image Principale Actuelle</div>
                    <div class="flex items-center gap-3">
                        @if($slide->image)
                        <div class="item" id="currentImg">
                            <img src="{{ asset('uploads/slides/' . $slide->image) }}"
                                 class="effect8" alt="Actuel" style="max-height: 150px;">
                        </div>
                        @endif
                        <div class="text-tiny text-muted">
                            Téléchargez une nouvelle image pour remplacer l'actuelle
                        </div>
                    </div>
                </div>

                <!-- Téléchargement de l'Image Principale -->
                <fieldset class="mb-4">
                    <div class="body-title">Nouvelle Image Principale</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="#" class="effect8" alt="Aperçu" style="max-height: 150px;">
                            <div class="preview-info">Aperçu de la nouvelle image principale</div>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="mainImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Sélectionnez une nouvelle image pour remplacer l'actuelle</span>
                                <input type="file" id="mainImage" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </fieldset>

                <!-- Image en Vedette Actuelle -->
                <div class="mb-3">
                    <div class="body-title">Image en Vedette Actuelle</div>
                    <div class="flex items-center gap-3">
                        @if($slide->featured_image)
                        <div class="item" id="currentFeatured">
                            <img src="{{ asset('uploads/slides/featured/' . $slide->featured_image) }}"
                                 class="effect8" alt="En Vedette Actuel" style="max-height: 150px;">
                            <div class="mt-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="remove_featured_image" value="1">
                                    <span class="text-tiny text-danger">Supprimer l'image en vedette</span>
                                </label>
                            </div>
                        </div>
                        @else
                        <div class="text-tiny text-muted">Aucune image en vedette téléchargée</div>
                        @endif
                    </div>
                </div>

                <!-- Téléchargement de l'Image en Vedette -->
                <fieldset class="mb-4">
                    <div class="body-title">Nouvelle Image en Vedette</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="featuredpreview" style="display:none">
                            <img src="#" class="effect8" alt="Aperçu en Vedette" style="max-height: 150px;">
                            <div class="preview-info">Aperçu de la nouvelle image en vedette</div>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="featuredImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Téléchargez une nouvelle image en vedette</span>
                                <input type="file" id="featuredImage" name="featured_image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('featured_image') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </fieldset>

                <div class="bot mt-4">
                    <a href="{{ route('admin.slides') }}" class="tf-button style-2 w208">Annuler</a>
                    <button class="tf-button w208" type="submit">Mettre à jour le Slide</button>
                </div>
            </form>
        </div>
        <!-- /edit-slide -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection

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
});
</script>
@endpush
