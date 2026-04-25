@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Informations sur la marque</h3>
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
                    <a href="{{ route('admin.brands') }}">
                        <div class="text-tiny">Marques</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Modifier la marque</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.brand.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $brand->id }}"/>

                <fieldset class="name">
                    <div class="body-title">Nom de la marque <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Nom de la marque" name="name" tabindex="0" value="{{ $brand->name}}" aria-required="true" required>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Slug de la marque <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Slug de la marque" name="slug" tabindex="0" value="{{ $brand->slug}}" aria-required="true" required>
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset>
                    <div class="body-title">Télécharger des images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        @if ($brand->image)
                         <!-- Aperçu de l'image -->
                         <div class="item preview-container" id="imgpreview" style="">
                            <img src="{{ asset('uploads/brands') }}/{{ $brand->image }}" class="effect8" alt="Aperçu" style="max-width: 124px; max-height: 124px; border: 1px solid #ddd; border-radius: 4px;">
                            <div class="text-tiny" style="margin-top: 5px;">Aperçu de l'image</div>
                        @endif
                        </div>

                        <!-- Zone de téléchargement -->
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile" style="cursor: pointer; border: 2px dashed #ddd; padding: 20px; text-align: center; display: block;">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">
                                    Déposez vos images ici ou <span class="tf-color">cliquez pour parcourir</span>
                                </span>
                                <input type="file" id="myFile" name="image" accept="image/*" style="display: none;">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Enregistrer</button>
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
    $('#myFile').on('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#imgpreview img').attr('src', e.target.result);
                $('#imgpreview').show();
                $('#upload-file').hide(); // Hide upload area when image is selected
            }

            reader.readAsDataURL(file);
        }
    });

    // Auto-generate slug from name
    $("input[name='name']").on('input', function() {
        const slug = stringToSlug($(this).val());
        $("input[name='slug']").val(slug);
    });

    // Allow manual slug editing without auto-overwrite
    $("input[name='slug']").on('focus', function() {
        // Store original event handler
        const nameInput = $("input[name='name']");
        nameInput.off('input.slugGenerator');
    });

    function stringToSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-')
            .replace(/--+/g, '-')
            .trim();
    }

    // Drag and drop functionality
    const uploadArea = $('#upload-file')[0];
    const fileInput = $('#myFile')[0];

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadArea.style.borderColor = '#4CAF50';
        uploadArea.style.backgroundColor = '#f8fff8';
    }

    function unhighlight(e) {
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.backgroundColor = '';
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            $('#myFile').trigger('change');
        }
    }
});
</script>
@endpush
