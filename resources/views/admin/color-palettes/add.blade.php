@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>{{ isset($palette) ? 'Modifier la Palette' : 'Créer une Nouvelle Palette' }}</h3>
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
                    <a href="{{ route('admin.color-palettes') }}">
                        <div class="text-tiny">Palettes de Couleurs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">{{ isset($palette) ? 'Modifier' : 'Nouvelle' }}</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1"
                  action="{{ isset($palette) ? route('admin.color-palette.update') : route('admin.color-palette.store') }}"
                  method="POST">
                @csrf
                @if(isset($palette))
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $palette->id }}">
                @endif

                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-md-5">
                        <fieldset class="name">
                            <div class="body-title">Nom de la Palette <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" name="name"
                                   value="{{ old('name', $palette->name ?? '') }}"
                                   placeholder="Ex: Collection Été 2024, Thème Mariage..."
                                   required>
                        </fieldset>
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <fieldset class="name">
                            <div class="body-title">Type <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="type" required>
                                    <option value="default" @selected(old('type', $palette->type ?? '') == 'default')>
                                        🏠 Défaut
                                    </option>
                                    <option value="custom" @selected(old('type', $palette->type ?? '') == 'custom')>
                                        🎨 Personnalisée
                                    </option>
                                    <option value="seasonal" @selected(old('type', $palette->type ?? '') == 'seasonal')>
                                        🌸 Saisonnière
                                    </option>
                                </select>
                            </div>
                        </fieldset>
                        @error('type')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <fieldset class="name">
                            <div class="body-title">Activer cette palette ?</div>
                            <div class="select flex-grow">
                                <select name="is_active">
                                    <option value="0" @selected(!old('is_active', $palette->is_active ?? false))>
                                        ❌ Non, garder inactive
                                    </option>
                                    <option value="1" @selected(old('is_active', $palette->is_active ?? false))>
                                        ✅ Oui, activer maintenant
                                    </option>
                                </select>
                            </div>
                        </fieldset>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Une seule palette peut être active à la fois
                        </small>
                    </div>
                </div>

                <!-- Live Preview Section -->
                <div class="live-preview mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-eye me-2"></i>Aperçu en Direct
                    </h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div id="preview-primary" class="preview-block">
                                <span>Primaire</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div id="preview-secondary" class="preview-block">
                                <span>Secondaire</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div id="preview-accent" class="preview-block">
                                <span>Accent</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div id="preview-background" class="preview-block" style="border: 2px solid #e5e7eb;">
                                <span>Fond</span>
                            </div>
                        </div>
                    </div>

                    <div id="preview-card" class="p-4 mt-3" style="border-radius: 12px; border: 1px solid #e5e7eb;">
                        <h5 id="preview-title" class="mb-2">Titre d'Exemple</h5>
                        <p id="preview-text" class="mb-3">Ceci est un texte de démonstration pour visualiser l'apparence de votre palette de couleurs sur le site.</p>
                        <button id="preview-button" class="btn btn-sm" style="color: white; border: none; padding: 8px 20px; border-radius: 6px;">
                            Bouton Exemple
                        </button>
                    </div>
                </div>

                <!-- Color Groups -->
                @php
                    $colorGroups = [
                        '🎨 Couleurs Principales' => [
                            'primary' => 'Couleur principale (boutons, liens, éléments importants)',
                            'secondary' => 'Couleur secondaire (arrière-plans alternatifs)',
                            'accent' => 'Couleur d\'accent (badges, surbrillances)'
                        ],
                        '📄 Arrière-plans' => [
                            'background' => 'Fond principal du site',
                            'surface' => 'Surface des cartes et conteneurs',
                            'bg_main' => 'Fond général',
                            'bg_soft' => 'Fond doux (zones secondaires)',
                            'bg_light' => 'Fond clair (cartes, modales)'
                        ],
                        '✏️ Textes' => [
                            'text_primary' => 'Texte principal (titres, paragraphes)',
                            'text_secondary' => 'Texte secondaire (sous-titres, descriptions)',
                            'text_main' => 'Texte général du site'
                        ],
                        '📏 Bordures & Séparateurs' => [
                            'border' => 'Bordure standard',
                            'border_soft' => 'Bordure légère',
                            'divider' => 'Séparateur de sections'
                        ],
                        '🏷️ Statuts' => [
                            'success' => 'Succès (validation, confirmation)',
                            'warning' => 'Attention (alertes)',
                            'error' => 'Erreur (problèmes)',
                            'info' => 'Information (notifications)'
                        ],
                        '✨ Éléments Spéciaux' => [
                            'card_shadow' => 'Ombre des cartes',
                            'hover_overlay' => 'Superposition au survol',
                            'gold_highlight' => 'Surlignage doré (luxe)',
                            'champagne_glow' => 'Lueur champagne',
                            'sale_badge' => 'Badge de vente/promotion',
                            'error_bg' => 'Fond d\'erreur'
                        ]
                    ];

                    $existingColors = $palette->colors ?? [];
                @endphp

                @foreach($colorGroups as $groupName => $colors)
                    <div class="color-group mb-4">
                        <h5 class="group-title">
                            {{ $groupName }}
                        </h5>
                        <div class="row">
                            @foreach($colors as $key => $description)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="color-input-wrapper">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="color-picker-container">
                                                <input type="color"
                                                       class="color-picker-input"
                                                       id="picker_{{ $key }}"
                                                       value="{{ old('colors.'.$key, $existingColors[$key] ?? '#000000') }}"
                                                       onchange="updateColorValue('{{ $key }}', this.value)">
                                            </div>
                                            <div class="flex-grow-1">
                                                <label class="color-label">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </label>
                                                <input type="text"
                                                       class="color-text-input"
                                                       id="text_{{ $key }}"
                                                       name="colors[{{ $key }}]"
                                                       value="{{ old('colors.'.$key, $existingColors[$key] ?? '#000000') }}"
                                                       oninput="updateColorPicker('{{ $key }}', this.value)"
                                                       pattern="^#[0-9A-Fa-f]{6}$"
                                                       placeholder="#000000">
                                                <small class="color-description">{{ $description }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @error('colors')
                    <div class="alert alert-danger mb-4">{{ $message }}</div>
                @enderror

                <!-- Submit Buttons -->
                <div class="form-actions mt-4 pt-4" style="border-top: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.color-palettes') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux palettes
                        </a>
                        <button type="submit" class="tf-button w208">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($palette) ? 'Mettre à Jour la Palette' : 'Enregistrer la Palette' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Live Preview */
.live-preview {
    background: #f9fafb;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.preview-block {
    height: 60px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

/* Color Groups */
.color-group {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.group-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
    color: #374151;
}

/* Color Input */
.color-input-wrapper {
    background: #f9fafb;
    border-radius: 10px;
    padding: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.color-input-wrapper:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-color: #d1d5db;
}

.color-picker-container {
    position: relative;
}

.color-picker-input {
    width: 48px;
    height: 48px;
    border: 2px solid #d1d5db;
    border-radius: 10px;
    cursor: pointer;
    padding: 2px;
    transition: all 0.2s ease;
}

.color-picker-input:hover {
    border-color: #9ca3af;
}

.color-picker-input::-webkit-color-swatch-wrapper {
    padding: 0;
}

.color-picker-input::-webkit-color-swatch {
    border: none;
    border-radius: 6px;
}

.color-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.color-text-input {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    transition: border-color 0.2s ease;
}

.color-text-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.color-description {
    display: block;
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
}

/* Buttons */
.btn-cancel {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #f3f4f6;
    color: #374151;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #e5e7eb;
    color: #1f2937;
}

/* Responsive */
@media (max-width: 768px) {
    .preview-block {
        height: 45px;
        font-size: 12px;
    }

    .color-group {
        padding: 15px;
    }

    .color-input-wrapper {
        padding: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Update text field and live preview
function updateColorValue(key, color) {
    // Update text field
    document.getElementById('text_' + key).value = color;

    // Update live preview
    updateLivePreview(key, color);
}

// Update color picker from text field
function updateColorPicker(key, value) {
    if (/^#[0-9A-F]{6}$/i.test(value)) {
        document.getElementById('picker_' + key).value = value;
        updateLivePreview(key, value);
    }
}

// Update live preview elements
function updateLivePreview(key, color) {
    const previewMap = {
        'primary': () => {
            document.getElementById('preview-primary').style.backgroundColor = color;
            document.getElementById('preview-button').style.backgroundColor = color;
        },
        'secondary': () => {
            document.getElementById('preview-secondary').style.backgroundColor = color;
        },
        'accent': () => {
            document.getElementById('preview-accent').style.backgroundColor = color;
        },
        'background': () => {
            document.getElementById('preview-background').style.backgroundColor = color;
            document.getElementById('preview-card').style.backgroundColor = color;
        },
        'text_primary': () => {
            document.getElementById('preview-title').style.color = color;
        },
        'text_secondary': () => {
            document.getElementById('preview-text').style.color = color;
        },
        'border': () => {
            document.getElementById('preview-card').style.borderColor = color;
        }
    };

    if (previewMap[key]) {
        previewMap[key]();
    }
}

// Initialize all values on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.color-picker-input').forEach(function(picker) {
        const key = picker.id.replace('picker_', '');
        const textInput = document.getElementById('text_' + key);
        if (textInput && /^#[0-9A-F]{6}$/i.test(textInput.value)) {
            picker.value = textInput.value;
            updateLivePreview(key, textInput.value);
        }
    });
});
</script>
@endpush
