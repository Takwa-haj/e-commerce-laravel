
@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Modifier la Couleur</h3>
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
                    <a href="{{ route('admin.colors') }}">
                        <div class="text-tiny">Couleurs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Modifier la Couleur</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.color.update') }}" id="colorForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $color->id }}"/>

                <fieldset class="name">
                    <div class="body-title">Nom de la Couleur <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Ex: Or Jaune, Or Rose, Argent, Platine, etc."
                           name="name" value="{{ old('name', $color->name) }}" aria-required="true" required>
                    <div class="text-tiny mt-2">Nom descriptif de la couleur pour bijoux</div>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Code <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Ex: GOLD_YELLOW, GOLD_ROSE, SILVER, PLATINUM"
                           name="code" value="{{ old('code', $color->code) }}" aria-required="true" required>
                    <div class="text-tiny mt-2">Code court pour identifier la couleur (lettres majuscules et underscores)</div>
                </fieldset>
                @error('code') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Sélection de la Couleur <span class="tf-color-1">*</span></div>
                    <div class="color-selection-container">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Color Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Aperçu</label>
                                    <div class="color-preview-box d-flex align-items-center justify-content-center p-4 rounded"
                                         style="background-color: {{ old('hex_code', $color->hex_code) }}; border: 2px solid #ddd; min-height: 120px;">
                                        <div class="color-text text-center p-3 rounded"
                                             style="background: rgba(255, 255, 255, 0.9);">
                                            <div class="body-title mb-2">{{ old('name', $color->name) }}</div>
                                            <code class="text-muted" id="preview-hex-code">{{ old('hex_code', $color->hex_code) }}</code>
                                        </div>
                                    </div>
                                </div>

                                <!-- Color Picker -->
                                <div class="mb-3">
                                    <label class="form-label">Choisissez une couleur</label>
                                    <div class="color-picker-container">
                                        <input type="color" id="colorPicker"
                                               value="{{ old('hex_code', $color->hex_code) }}"
                                               class="form-control form-control-color"
                                               title="Choisissez une couleur">
                                        <div class="d-flex align-items-center mt-2">
                                            <input type="text" id="hexCode" name="hex_code"
                                                   class="form-control flex-grow me-2"
                                                   placeholder="#FFD700"
                                                   value="{{ old('hex_code', $color->hex_code) }}"
                                                   pattern="^#[0-9A-F]{6}$"
                                                   title="Format hexadécimal: #RRGGBB"
                                                   required>
                                            <button type="button" id="colorPipette" class="btn btn-outline-secondary"
                                                    title="Pipette - Choisir une couleur depuis l'écran">
                                                <i class="icon-eyedropper"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Jewelry Color Presets -->
                                <div class="mb-3">
                                    <label class="form-label">Couleurs de bijoux prédéfinies</label>
                                    <div class="jewelry-color-presets">
                                        <div class="row g-2">
                                            <!-- Gold Colors -->
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#FFD700" data-name="Or Jaune 24K">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #FFD700;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Or Jaune 24K</div>
                                                            <small class="text-muted">#FFD700</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#E6BE8A" data-name="Or Rose 18K">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #E6BE8A;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Or Rose 18K</div>
                                                            <small class="text-muted">#E6BE8A</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#C0C0C0" data-name="Argent Sterling">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #C0C0C0;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Argent Sterling</div>
                                                            <small class="text-muted">#C0C0C0</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#E5E4E2" data-name="Platine">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #E5E4E2;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Platine</div>
                                                            <small class="text-muted">#E5E4E2</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#B87333" data-name="Cuivre">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #B87333;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Cuivre</div>
                                                            <small class="text-muted">#B87333</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#8B4513" data-name="Bronze">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #8B4513;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Bronze</div>
                                                            <small class="text-muted">#8B4513</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#D4AF37" data-name="Or Métallisé">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #D4AF37;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Or Métallisé</div>
                                                            <small class="text-muted">#D4AF37</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="color-preset-btn w-100 p-3 border rounded"
                                                        data-color="#AAA9AD" data-name="Acier Inoxydable">
                                                    <div class="d-flex align-items-center">
                                                        <div class="color-circle me-2" style="background-color: #AAA9AD;"></div>
                                                        <div class="text-start">
                                                            <div class="text-tiny">Acier Inoxydable</div>
                                                            <small class="text-muted">#AAA9AD</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-tiny mt-2">Cliquez sur une couleur prédéfinie pour la sélectionner</div>
                                </div>

                                <!-- Current Color Info -->
                                <div class="color-info p-3 rounded" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                    <h6 class="mb-3">Couleur actuelle</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="color-circle me-3" style="background-color: {{ $color->hex_code }}; width: 40px; height: 40px;"></div>
                                        <div>
                                            <div class="body-title">{{ $color->name }}</div>
                                            <code class="text-muted">{{ $color->hex_code }}</code>
                                            <div class="text-tiny mt-1">{{ $color->products_count ?? 0 }} produit(s) utilisent cette couleur</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                @error('hex_code') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="name">
                    <div class="body-title">Ordre d'affichage</div>
                    <input class="flex-grow" type="number" placeholder="Ex: 1, 2, 3..."
                           name="order" value="{{ old('order', $color->order) }}">
                    <div class="text-tiny mt-2">Définit l'ordre d'affichage des couleurs (plus petit = premier)</div>
                </fieldset>
                @error('order') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <fieldset class="category">
                    <div class="body-title">Statut</div>
                    <div class="select flex-grow">
                        <select name="status">
                            <option value="1" {{ old('status', $color->status) == 1 ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ old('status', $color->status) == 0 ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </fieldset>
                @error('status') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .color-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .color-preview-box {
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .color-text {
        transition: all 0.3s ease;
    }

    .color-preset-btn {
        transition: all 0.2s ease;
        background: white;
        cursor: pointer;
    }

    .color-preset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        border-color: #0d6efd !important;
    }

    .color-preset-btn.active {
        border-color: #0d6efd !important;
        background-color: #e8f4fd;
    }

    .form-control-color {
        width: 60px;
        height: 60px;
        padding: 2px;
        border-radius: 8px;
        cursor: pointer;
    }

    #colorPipette {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .jewelry-color-presets {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* Custom scrollbar for jewelry colors */
    .jewelry-color-presets::-webkit-scrollbar {
        width: 6px;
    }

    .jewelry-color-presets::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .jewelry-color-presets::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .jewelry-color-presets::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Elements
    const colorPicker = document.getElementById('colorPicker');
    const hexCodeInput = document.getElementById('hexCode');
    const colorPreview = document.querySelector('.color-preview-box');
    const previewHexCode = document.getElementById('preview-hex-code');
    const colorNameInput = document.querySelector('input[name="name"]');
    const colorCodeInput = document.querySelector('input[name="code"]');
    const colorPipetteBtn = document.getElementById('colorPipette');

    // Update color when color picker changes
    colorPicker.addEventListener('input', function() {
        const color = this.value.toUpperCase();
        hexCodeInput.value = color;
        updateColorPreview(color);
    });

    // Update color when hex code changes
    hexCodeInput.addEventListener('input', function() {
        const color = this.value.toUpperCase();
        if (isValidHex(color)) {
            colorPicker.value = color;
            updateColorPreview(color);
        }
    });

    // Color preset buttons
    $('.color-preset-btn').on('click', function() {
        const color = $(this).data('color');
        const name = $(this).data('name');

        // Update color picker and hex code
        colorPicker.value = color;
        hexCodeInput.value = color;

        // Update preview
        updateColorPreview(color);

        // Update name field if empty
        if (!colorNameInput.value) {
            colorNameInput.value = name;

            // Auto-generate code from name
            if (!colorCodeInput.value) {
                const code = name.toUpperCase().replace(/[^A-Z0-9]+/g, '_');
                colorCodeInput.value = code;
            }
        }

        // Highlight active preset
        $('.color-preset-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Color pipette functionality
    if (typeof EyeDropper !== 'undefined') {
        const eyeDropper = new EyeDropper();

        colorPipetteBtn.addEventListener('click', async function() {
            try {
                const result = await eyeDropper.open();
                const color = result.sRGBHex.toUpperCase();

                colorPicker.value = color;
                hexCodeInput.value = color;
                updateColorPreview(color);

                // Show success message
                showMessage('Couleur sélectionnée avec succès!', 'success');
            } catch (err) {
                console.error('EyeDropper failed:', err);
                showMessage('La pipette n\'est pas disponible dans ce navigateur.', 'error');
            }
        });
    } else {
        // EyeDropper API not supported
        colorPipetteBtn.addEventListener('click', function() {
            showMessage('La pipette n\'est pas supportée par votre navigateur. Utilisez Chrome ou Edge.', 'error');
        });
    }

    // Auto-generate code from name
    colorNameInput.addEventListener('input', function() {
        if (!colorCodeInput.value) {
            const code = this.value.toUpperCase()
                .replace(/[^A-Z0-9\s]+/g, '')
                .replace(/\s+/g, '_');
            colorCodeInput.value = code;
        }
    });

    // Form validation
    $('#colorForm').on('submit', function(e) {
        const hexValue = hexCodeInput.value;

        if (!isValidHex(hexValue)) {
            e.preventDefault();
            showMessage('Veuillez entrer un code HEX valide (format: #RRGGBB)', 'error');
            hexCodeInput.focus();
            return false;
        }

        return true;
    });

    // Helper functions
    function updateColorPreview(color) {
        if (isValidHex(color)) {
            colorPreview.style.backgroundColor = color;
            previewHexCode.textContent = color;

            // Update text color for contrast
            const brightness = getColorBrightness(color);
            const textColor = brightness > 128 ? '#000' : '#fff';
            colorPreview.querySelector('.color-text').style.color = textColor;
        }
    }

    function isValidHex(color) {
        return /^#[0-9A-F]{6}$/i.test(color);
    }

    function getColorBrightness(hexColor) {
        // Convert hex to RGB
        const r = parseInt(hexColor.substr(1, 2), 16);
        const g = parseInt(hexColor.substr(3, 2), 16);
        const b = parseInt(hexColor.substr(5, 2), 16);

        // Calculate brightness (YIQ formula)
        return (r * 299 + g * 587 + b * 114) / 1000;
    }

    function showMessage(message, type = 'info') {
        // Remove existing messages
        $('.alert-message').remove();

        // Create new message
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        const messageHtml = `
            <div class="alert alert-message ${alertClass} alert-dismissible fade show mt-3">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert before form
        $(messageHtml).insertBefore('#colorForm .bot');

        // Auto-remove after 5 seconds
        setTimeout(() => {
            $('.alert-message').alert('close');
        }, 5000);
    }

    // Initialize with current color
    updateColorPreview(hexCodeInput.value);

    // Check if any preset matches current color
    $('.color-preset-btn').each(function() {
        if ($(this).data('color').toUpperCase() === hexCodeInput.value.toUpperCase()) {
            $(this).addClass('active');
        }
    });
});
</script>
@endpush
