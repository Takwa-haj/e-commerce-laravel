@extends('layouts.admin')

@section('content')
<!-- main-content-wrap -->
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Ajouter un Produit</h3>
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
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Produits</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Ajouter un produit</div>
                </li>
            </ul>
        </div>
        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}" id="product-form">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Nom du produit <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Entrez le nom du produit" name="name" tabindex="0" value="{{ old('name') }}" aria-required="true" required="">
                    <div class="text-tiny">Ne pas dépasser 100 caractères pour le nom du produit.</div>
                </fieldset>
                    @error('name')<span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Entrez le slug du produit" name="slug" tabindex="0" value="{{ old('slug') }}" aria-required="true" required="">
                    <div class="text-tiny">Ne pas dépasser 100 caractères pour le slug.</div>
                </fieldset>
                    @error('slug')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Catégorie <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id" required>
                                <option value="">Choisir une catégorie</option>
                                @foreach ($categories as $category )
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                       @error('category_id')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                    </fieldset>
                    <fieldset class="brand">
                        <div class="body-title mb-10">Marque <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id" required>
                                <option value="">Choisir une marque</option>
                                @foreach ($brands as $brand )
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>

                <!-- Size and Color Selection -->
                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Tailles Disponibles</div>
                        <div class="select">
                            <select class="form-select" name="sizes[]" id="sizes-select" multiple style="height: 150px;">
                                @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" {{ in_array($size->id, old('sizes', [])) ? 'selected' : '' }}>
                                    {{ $size->name }} ({{ $size->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-tiny mt-2">Maintenez CTRL (Windows) ou CMD (Mac) pour sélectionner plusieurs tailles</div>
                    </fieldset>
                    @error('sizes')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset class="brand">
                        <div class="body-title mb-10">Couleurs Disponibles</div>
                        <div class="select">
                            <select class="form-select" name="colors[]" id="colors-select" multiple style="height: 150px;">
                                @foreach ($colors as $color)
                                <option value="{{ $color->id }}" {{ in_array($color->id, old('colors', [])) ? 'selected' : '' }}
                                    style="padding-left: 30px; position: relative;">
                                    <span style="position: absolute; left: 5px; top: 50%; transform: translateY(-50%);
                                             width: 15px; height: 15px; border-radius: 50%; background-color: {{ $color->hex_code }}; border: 1px solid #ddd;"></span>
                                    {{ $color->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-tiny mt-2">Maintenez CTRL (Windows) ou CMD (Mac) pour sélectionner plusieurs couleurs</div>
                    </fieldset>
                    @error('colors')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Description courte <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Description courte" tabindex="0" aria-required="true" required="">{{ old('short_description') }}</textarea>
                    <div class="text-tiny">Ne pas dépasser 100 caractères.</div>
                </fieldset>
               @error('short_description')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                    </div>
                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" required="">{{ old('description') }}</textarea>
                    <div class="text-tiny">Description détaillée du produit.</div>
                </fieldset>
                @error('description')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Télécharger l'image principale <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Déposez votre image ici ou <span class="tf-color">cliquez pour parcourir</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                </fieldset>
               @error('image')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset>
                    <div class="body-title mb-10">Télécharger les images de la galerie</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Déposez vos images ici ou <span class="tf-color">cliquez pour parcourir</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
               @error('images')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Prix régulier <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" step="0.01" min="0" placeholder="Prix normal (€)" name="regular_price" tabindex="0" value="{{ old('regular_price') }}" aria-required="true" required>
                    </fieldset>
                    @error('regular_price')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Prix promotionnel <span class="text-tiny">(optionnel)</span></div>
                        <input class="mb-10" type="number" step="0.01" min="0" placeholder="Prix réduit (€)" name="sale_price" tabindex="0" value="{{ old('sale_price') }}">
                        <div class="text-tiny">Laissez vide si pas de promotion</div>
                    </fieldset>
                    @error('sale_price')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Entrez le SKU" name="SKU" id="base-sku" tabindex="0" value="{{ old('SKU') }}" aria-required="true" required="">
                    </fieldset>
                  @error('SKU')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Quantité par défaut <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="number" placeholder="Entrez la quantité par défaut" name="quantity" tabindex="0" value="{{ old('quantity', 0) }}" aria-required="true" required="" min="0">
                        <div class="text-tiny">Quantité par défaut si aucune variante n'est configurée</div>
                    </fieldset>
                   @error('quantity')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Statut du stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock" {{ old('stock_status', 'instock') == 'instock' ? 'selected' : '' }}>En stock</option>
                                <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Rupture de stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">En vedette</div>
                        <div class="select mb-10">
                            <select class="" name="featured">
                                <option value="0" {{ old('featured', 0) == 0 ? 'selected' : '' }}>Non</option>
                                <option value="1" {{ old('featured') == 1 ? 'selected' : '' }}>Oui</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>

                <!-- Variants Section -->
                <div class="wg-box mt-4" id="variants-section" style="display: none;">
                    <h3 class="mb-20">Gestion des Variantes</h3>
                    <div class="text-tiny mb-20">
                        Configurez les quantités et SKUs spécifiques pour chaque combinaison de taille/couleur.
                    </div>

                    <div id="variants-container">
                        <!-- Variants will be dynamically added here -->
                    </div>

                    <button type="button" id="generate-variants-btn" class="tf-button style-2 mt-20">
                        <i class="icon-plus"></i> Générer toutes les combinaisons
                    </button>

                    <div class="alert alert-info mt-3">
                        <i class="icon-info"></i> Si vous ne configurez pas de variantes, la quantité par défaut sera utilisée.
                    </div>
                </div>

                <div class="cols gap10 mt-4">
                    <button class="tf-button w-full" type="submit">Ajouter le produit</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection

@push('styles')
<style>
    /* Style for better multi-select appearance */
    select[multiple] {
        min-height: 150px;
        padding: 8px;
    }

    select[multiple] option {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 4px;
    }

    select[multiple] option:checked {
        background-color: #e8f4fd;
        color: #0d6efd;
    }

    select[multiple] option:hover {
        background-color: #f8f9fa;
    }

    .variant-row {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
    }

    .variant-row .form-control-sm {
        height: 38px;
        padding: 8px 12px;
    }

    .variant-row label {
        font-weight: 500;
        font-size: 13px;
        color: #555;
        margin-bottom: 5px;
    }

    .variant-row .row {
        align-items: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    /** ===========================
     *   PRÉVISUALISATION IMAGE PRINCIPALE
     * =========================== */
    $('#myFile').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imgpreview img').attr('src', e.target.result);
                $('#imgpreview').show();
                $('#upload-file').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    /** ===========================
     *   PRÉVISUALISATION GALERIE D'IMAGES
     * =========================== */
    $('#gFile').on('change', function() {
        $("#galUpload .gitems").remove(); // Effacer les prévisualisations précédentes

        const files = this.files;

        $.each(files, function(i, file) {
            const url = URL.createObjectURL(file);
            $("#galUpload").prepend(`
                <div class="item gitems">
                    <img src="${url}" class="effect8" style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px;" />
                </div>
            `);
        });
    });

    /** ===========================
     *   GÉNÉRATEUR AUTOMATIQUE DE SLUG
     * =========================== */
    $("input[name='name']").on('input.slugGenerator', function() {
        const slug = stringToSlug($(this).val());
        $("input[name='slug']").val(slug);
    });

    function stringToSlug(text) {
        return text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-')
            .replace(/--+/g, '-')
            .trim();
    }

    // Permettre l'édition manuelle du slug
    $("input[name='slug']").on('focus', function() {
        $("input[name='name']").off('input.slugGenerator');
    });

    /** ===========================
     *   TÉLÉCHARGEMENT PAR GLISSER-DÉPOSER
     * =========================== */
    const uploadArea = $('#upload-file')[0];
    const fileInput = $('#myFile')[0];

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
        uploadArea.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    uploadArea.addEventListener('dragover', () => {
        uploadArea.style.borderColor = '#4CAF50';
        uploadArea.style.background = '#f8fff8';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.background = '';
    });

    uploadArea.addEventListener('drop', e => {
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.background = '';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            $('#myFile').trigger('change');
        }
    });

    /** ===========================
     *   VARIANT MANAGEMENT
     * =========================== */
    const sizesSelect = $('#sizes-select');
    const colorsSelect = $('#colors-select');
    const variantsContainer = $('#variants-container');
    const variantsSection = $('#variants-section');
    const baseSKUInput = $('#base-sku');

    let variantsData = {};

    // Store size and color data
    const sizesData = {
        @foreach($sizes as $size)
        {{ $size->id }}: { id: {{ $size->id }}, name: "{{ $size->name }}", code: "{{ $size->code }}" },
        @endforeach
    };

    const colorsData = {
        @foreach($colors as $color)
        {{ $color->id }}: { id: {{ $color->id }}, name: "{{ $color->name }}", code: "{{ $color->code }}" },
        @endforeach
    };

    // Function to update variants visibility
    function updateVariantsSection() {
        const selectedSizes = sizesSelect.val() || [];
        const selectedColors = colorsSelect.val() || [];

        if (selectedSizes.length > 0 || selectedColors.length > 0) {
            variantsSection.show();
        } else {
            variantsSection.hide();
            variantsContainer.empty();
            variantsData = {};
        }
    }

    // Function to generate all variant combinations
    function generateAllVariants() {
        const selectedSizes = sizesSelect.val() || [];
        const selectedColors = colorsSelect.val() || [];

        variantsContainer.empty();
        variantsData = {};

        // If no sizes or colors selected, show base variant
        if (selectedSizes.length === 0 && selectedColors.length === 0) {
            const baseVariant = createBaseVariant();
            variantsContainer.append(baseVariant);
            return;
        }

        // Generate all combinations
        // If no sizes selected, add null for base size variant
        const sizeIds = selectedSizes.length > 0 ? selectedSizes : [null];
        const colorIds = selectedColors.length > 0 ? selectedColors : [null];

        sizeIds.forEach(sizeId => {
            colorIds.forEach(colorId => {
                // Don't create both null variant (handled separately)
                if (sizeId === null && colorId === null) return;

                const variantKey = getVariantKey(sizeId, colorId);
                const variantRow = createVariantRow(sizeId, colorId);
                variantsContainer.append(variantRow);
                variantsData[variantKey] = {
                    size_id: sizeId,
                    color_id: colorId
                };
            });
        });
    }

    // Function to create base variant (no size or color)
    function createBaseVariant() {
        const variantKey = getVariantKey(null, null);
        return `
            <div class="variant-row mb-3 p-3 border rounded" data-key="${variantKey}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <strong>Variante de base</strong><br>
                        <small class="text-muted">Aucune taille ou couleur spécifique</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="variants[${variantKey}][quantity]"
                               class="form-control form-control-sm variant-quantity"
                               value="0" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SKU Variant</label>
                        <input type="text" name="variants[${variantKey}][sku]"
                               class="form-control form-control-sm variant-sku"
                               placeholder="Auto-généré"
                               value="${baseSKUInput.val()}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix spécifique (optionnel)</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantKey}][price]"
                               class="form-control form-control-sm"
                               placeholder="Prix spécifique">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix promo (optionnel)</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantKey}][sale_price]"
                               class="form-control form-control-sm"
                               placeholder="Prix promo">
                    </div>
                </div>
                <input type="hidden" name="variants[${variantKey}][size_id]" value="">
                <input type="hidden" name="variants[${variantKey}][color_id]" value="">
            </div>
        `;
    }

    // Function to create a variant row
    function createVariantRow(sizeId, colorId) {
        const variantKey = getVariantKey(sizeId, colorId);
        const size = sizeId ? sizesData[sizeId] : null;
        const color = colorId ? colorsData[colorId] : null;

        let description = '';
        if (size && color) {
            description = `Taille: ${size.name} | Couleur: ${color.name}`;
        } else if (size) {
            description = `Taille: ${size.name}`;
        } else if (color) {
            description = `Couleur: ${color.name}`;
        }

        // Generate SKU
        let sku = baseSKUInput.val();
        if (size) sku += '-' + size.code;
        if (color) sku += '-' + color.code;

        return `
            <div class="variant-row mb-3 p-3 border rounded" data-key="${variantKey}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <strong>${description}</strong>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="variants[${variantKey}][quantity]"
                               class="form-control form-control-sm variant-quantity"
                               value="0" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SKU Variant</label>
                        <input type="text" name="variants[${variantKey}][sku]"
                               class="form-control form-control-sm variant-sku"
                               placeholder="Auto-généré"
                               value="${sku}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix spécifique (optionnel)</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantKey}][price]"
                               class="form-control form-control-sm"
                               placeholder="Prix spécifique">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix promo (optionnel)</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantKey}][sale_price]"
                               class="form-control form-control-sm"
                               placeholder="Prix promo">
                    </div>
                </div>
                <input type="hidden" name="variants[${variantKey}][size_id]" value="${sizeId || ''}">
                <input type="hidden" name="variants[${variantKey}][color_id]" value="${colorId || ''}">
            </div>
        `;
    }

    // Helper function to generate variant key
    function getVariantKey(sizeId, colorId) {
        return `${sizeId || '0'}-${colorId || '0'}`;
    }

    // Update SKUs when base SKU changes
    baseSKUInput.on('input', function() {
        $('.variant-sku').each(function() {
            const $input = $(this);
            const $row = $input.closest('.variant-row');
            const sizeId = $row.find('input[name*="[size_id]"]').val();
            const colorId = $row.find('input[name*="[color_id]"]').val();

            let newSKU = baseSKUInput.val();
            if (sizeId && sizesData[sizeId]) {
                newSKU += '-' + sizesData[sizeId].code;
            }
            if (colorId && colorsData[colorId]) {
                newSKU += '-' + colorsData[colorId].code;
            }

            $input.val(newSKU);
        });
    });

    // Event listeners
    sizesSelect.on('change', function() {
        updateVariantsSection();
    });

    colorsSelect.on('change', function() {
        updateVariantsSection();
    });

    $('#generate-variants-btn').on('click', function() {
        generateAllVariants();
    });

    // Form submission validation
    $('#product-form').on('submit', function(e) {
        const hasVariants = variantsContainer.children().length > 0;
        const defaultQuantity = parseInt($('input[name="quantity"]').val()) || 0;

        if (hasVariants) {
            // Check if at least one variant has quantity > 0
            let totalVariantQuantity = 0;
            $('.variant-quantity').each(function() {
                totalVariantQuantity += parseInt($(this).val()) || 0;
            });

            if (totalVariantQuantity === 0) {
                e.preventDefault();
                alert('Veuillez définir au moins une quantité pour les variantes ou utiliser la quantité par défaut.');
                return false;
            }
        } else if (defaultQuantity === 0) {
            e.preventDefault();
            alert('Veuillez définir une quantité pour le produit.');
            return false;
        }
    });

    // Initialize
    updateVariantsSection();

    // If there are old variant values (form validation failed), restore them
    @if(old('variants'))
        setTimeout(() => {
            const oldVariants = @json(old('variants', []));
            if (Object.keys(oldVariants).length > 0) {
                variantsSection.show();
                generateAllVariants();

                // Restore values
                Object.keys(oldVariants).forEach(key => {
                    const variant = oldVariants[key];
                    const $row = $(`[data-key="${key}"]`);

                    if ($row.length) {
                        $row.find('input[name$="[quantity]"]').val(variant.quantity || 0);
                        $row.find('input[name$="[sku]"]').val(variant.sku || '');
                        $row.find('input[name$="[price]"]').val(variant.price || '');
                        $row.find('input[name$="[sale_price]"]').val(variant.sale_price || '');
                    }
                });
            }
        }, 100);
    @endif
});
</script>
@endpush
