@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- En-tête de page -->
        <div class="page-header">
            <div class="page-title">
                <h1 class="text-3xl font-bold text-gray-900">Paramètres</h1>
                <p class="text-lg text-gray-600 mt-2">Gérez la configuration de votre site et vos paramètres personnels</p>
            </div>
            <div class="breadcrumbs">
                <a href="{{ route('admin.index') }}" class="breadcrumb-link">
                    Tableau de bord
                </a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">Paramètres</span>
            </div>
        </div>

        <!-- Messages de session -->
        @if(session('status'))
            <div class="alert alert-success">
                <div class="flex items-center">
                    <i class="icon-check-circle mr-3 text-xl"></i>
                    <span class="text-base">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <div class="flex items-center">
                    <i class="icon-alert-circle mr-3 text-xl"></i>
                    <span class="text-base">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Conteneur des paramètres -->
        <div class="settings-container">
            <!-- Navigation par onglets -->
            <div class="settings-tabs">
                <button class="settings-tab active" data-tab="general">
                    <i class="icon-settings mr-3 text-lg"></i>
                    <span class="text-base font-medium">Paramètres Généraux</span>
                </button>
                <button class="settings-tab" data-tab="profile">
                    <i class="icon-user mr-3 text-lg"></i>
                    <span class="text-base font-medium">Profil</span>
                </button>
                <button class="settings-tab" data-tab="password">
                    <i class="icon-lock mr-3 text-lg"></i>
                    <span class="text-base font-medium">Mot de Passe</span>
                </button>
            </div>

            <!-- Contenu des onglets -->
            <div class="settings-content">
                <!-- Onglet Paramètres Généraux -->
                <div class="settings-tab-content active" id="general-tab">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2 class="settings-card-title">
                                <i class="icon-settings text-primary-600 mr-3 text-xl"></i>
                                <span class="text-xl font-semibold">Paramètres Généraux</span>
                            </h2>
                            <p class="settings-card-description text-lg">Configurez les informations de base et l'identité de votre site web</p>
                        </div>

                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
                            @csrf
                            @method('PUT')

                            <!-- Section 1 : Informations de base -->
                            <div class="form-section">
                                <h3 class="form-section-title text-lg font-semibold">Informations de Base</h3>
                                <div class="form-grid">
                                    <!-- Nom du site -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Nom du Site <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               name="site_name"
                                               value="{{ old('site_name', $settings->site_name) }}"
                                               class="form-control text-base @error('site_name') border-error @enderror"
                                               placeholder="Entrez le nom de votre site"
                                               required>
                                        @error('site_name')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email du site -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Email du Site</label>
                                        <input type="email"
                                               name="site_email"
                                               value="{{ old('site_email', $settings->site_email) }}"
                                               class="form-control text-base @error('site_email') border-error @enderror"
                                               placeholder="contact@exemple.com">
                                        @error('site_email')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Téléphone du site -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Téléphone du Site</label>
                                        <input type="text"
                                               name="site_phone"
                                               value="{{ old('site_phone', $settings->site_phone) }}"
                                               class="form-control text-base @error('site_phone') border-error @enderror"
                                               placeholder="+33 1 23 45 67 89">
                                        @error('site_phone')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Adresse du site -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Adresse du Site</label>
                                        <input type="text"
                                               name="site_address"
                                               value="{{ old('site_address', $settings->site_address) }}"
                                               class="form-control text-base @error('site_address') border-error @enderror"
                                               placeholder="123 Rue Principale, Ville, Pays">
                                        @error('site_address')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Texte du pied de page -->
                                    <div class="form-group col-span-2">
                                        <label class="form-label text-base">Texte du Pied de Page</label>
                                        <textarea name="footer_text"
                                                  rows="3"
                                                  class="form-control text-base @error('footer_text') border-error @enderror"
                                                  placeholder="Informations de copyright ou texte de pied de page">{{ old('footer_text', $settings->footer_text) }}</textarea>
                                        @error('footer_text')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2 : Paramètres financiers -->
                            <div class="form-section">
                                <h3 class="form-section-title text-lg font-semibold">Paramètres Financiers</h3>
                                <div class="form-grid">
                                    <!-- Devise -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Devise <span class="text-red-500">*</span>
                                        </label>
                                        <select name="currency"
                                                class="form-control text-base @error('currency') border-error @enderror"
                                                required>
                                            <option value="">Sélectionnez une devise</option>
                                            <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
                                            <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                            <option value="GBP" {{ old('currency', $settings->currency) == 'GBP' ? 'selected' : '' }}>Livre sterling (GBP)</option>
                                            <option value="MAD" {{ old('currency', $settings->currency) == 'MAD' ? 'selected' : '' }}>Dirham marocain (MAD)</option>
                                        </select>
                                        @error('currency')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Symbole de devise -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Symbole de Devise <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               name="currency_symbol"
                                               value="{{ old('currency_symbol', $settings->currency_symbol) }}"
                                               class="form-control text-base @error('currency_symbol') border-error @enderror"
                                               placeholder="€"
                                               maxlength="5"
                                               required>
                                        @error('currency_symbol')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Taux de taxe -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Taux de Taxe (%)</label>
                                        <div class="input-with-suffix">
                                            <input type="number"
                                                   name="tax_rate"
                                                   value="{{ old('tax_rate', $settings->tax_rate) }}"
                                                   class="form-control text-base @error('tax_rate') border-error @enderror"
                                                   placeholder="0"
                                                   min="0"
                                                   max="100"
                                                   step="0.01">
                                            <span class="input-suffix text-base">%</span>
                                        </div>
                                        @error('tax_rate')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Coût de livraison -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Coût de Livraison</label>
                                        <div class="input-with-prefix">
                                            <span class="input-prefix text-base">{{ $settings->currency_symbol }}</span>
                                            <input type="number"
                                                   name="shipping_cost"
                                                   value="{{ old('shipping_cost', $settings->shipping_cost) }}"
                                                   class="form-control text-base @error('shipping_cost') border-error @enderror"
                                                   placeholder="0.00"
                                                   min="0"
                                                   step="0.01">
                                        </div>
                                        @error('shipping_cost')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3 : Médias et Identité visuelle -->
                            <div class="form-section">
                                <h3 class="form-section-title text-lg font-semibold">Médias et Identité Visuelle</h3>
                                <div class="form-grid">
                                    <!-- Logo -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Logo du Site</label>
                                        <div class="file-upload-area">
                                            @if($settings->logo)
                                                <div class="current-file">
                                                    <img src="{{ asset('uploads/settings/' . $settings->logo) }}"
                                                         alt="Logo actuel"
                                                         class="current-file-preview">
                                                    <div class="current-file-info">
                                                        <span class="current-file-name text-base">{{ $settings->logo }}</span>
                                                        <button type="button"
                                                                class="remove-file-btn text-base"
                                                                onclick="removeFile('logo')">
                                                            <i class="icon-trash"></i> Supprimer
                                                        </button>
                                                        <input type="hidden" name="remove_logo" id="remove-logo" value="0">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="file-upload-wrapper">
                                                <input type="file"
                                                       name="logo"
                                                       id="logo-input"
                                                       accept="image/*"
                                                       class="file-input"
                                                       onchange="previewFile(this, 'logo-preview')">
                                                <div class="file-upload-placeholder">
                                                    <i class="icon-upload text-3xl mb-3"></i>
                                                    <span class="file-upload-text text-base">Cliquez pour télécharger le logo</span>
                                                    <span class="file-upload-hint text-base">PNG, JPG jusqu'à 2MB</span>
                                                </div>
                                            </div>
                                            <div id="logo-preview" class="file-preview hidden">
                                                <img class="preview-image">
                                                <button type="button" class="remove-preview-btn" onclick="removePreview('logo')">
                                                    <i class="icon-x text-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('logo')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Favicon -->
                                    <div class="form-group">
                                        <label class="form-label text-base">Favicon</label>
                                        <div class="file-upload-area">
                                            @if($settings->favicon)
                                                <div class="current-file">
                                                    <img src="{{ asset('uploads/settings/' . $settings->favicon) }}"
                                                         alt="Favicon actuel"
                                                         class="current-file-preview favicon">
                                                    <div class="current-file-info">
                                                        <span class="current-file-name text-base">{{ $settings->favicon }}</span>
                                                        <button type="button"
                                                                class="remove-file-btn text-base"
                                                                onclick="removeFile('favicon')">
                                                            <i class="icon-trash"></i> Supprimer
                                                        </button>
                                                        <input type="hidden" name="remove_favicon" id="remove-favicon" value="0">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="file-upload-wrapper">
                                                <input type="file"
                                                       name="favicon"
                                                       id="favicon-input"
                                                       accept="image/*"
                                                       class="file-input"
                                                       onchange="previewFile(this, 'favicon-preview')">
                                                <div class="file-upload-placeholder">
                                                    <i class="icon-image text-3xl mb-3"></i>
                                                    <span class="file-upload-text text-base">Cliquez pour télécharger le favicon</span>
                                                    <span class="file-upload-hint text-base">ICO, PNG jusqu'à 1MB</span>
                                                </div>
                                            </div>
                                            <div id="favicon-preview" class="file-preview hidden">
                                                <img class="preview-image">
                                                <button type="button" class="remove-preview-btn" onclick="removePreview('favicon')">
                                                    <i class="icon-x text-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('favicon')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Actions du formulaire -->
                            <div class="form-actions">
                                <button type="reset" class="btn-secondary text-base">
                                    <i class="icon-refresh-cw mr-3"></i>
                                    Réinitialiser
                                </button>
                                <button type="submit" class="btn-primary text-base">
                                    <i class="icon-save mr-3"></i>
                                    Enregistrer les Paramètres
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Onglet Profil -->
                <div class="settings-tab-content" id="profile-tab">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2 class="settings-card-title">
                                <i class="icon-user text-primary-600 mr-3 text-xl"></i>
                                <span class="text-xl font-semibold">Profil Utilisateur</span>
                            </h2>
                            <p class="settings-card-description text-lg">Gérez vos informations personnelles et votre photo de profil</p>
                        </div>

                        <div class="profile-content">
                            <!-- Photo de profil -->
                            <div class="profile-picture-section">
                                <div class="profile-picture-wrapper">
                                    <img src=""
                                         alt="{{ $user->name }}"
                                         class="profile-picture"
                                         id="profile-picture-preview">
                                    <div class="profile-picture-actions">
                                        <label for="avatar-upload" class="btn-icon">
                                            <i class="icon-camera text-lg"></i>
                                            <input type="file"
                                                   id="avatar-upload"
                                                   name="avatar"
                                                   accept="image/*"
                                                   class="hidden"
                                                   onchange="previewFile(this, 'profile-picture-preview')">
                                        </label>
                                        <button type="button" class="btn-icon" onclick="removeProfilePicture()">
                                            <i class="icon-trash text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="profile-picture-info">
                                    <h4 class="profile-name text-xl font-semibold">{{ $user->name }}</h4>
                                    <p class="profile-email text-lg">{{ $user->email }}</p>
                                    <p class="profile-hint text-base">Cliquez sur l'icône appareil photo pour télécharger une nouvelle photo de profil</p>
                                </div>
                            </div>

                            <!-- Informations du profil -->
                            <div class="profile-info-grid">
                                <div class="info-card">
                                    <div class="info-card-icon">
                                        <i class="icon-user text-2xl"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label class="info-card-label text-base">Nom Complet</label>
                                        <p class="info-card-value text-lg font-medium">{{ $user->name }}</p>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-card-icon">
                                        <i class="icon-mail text-2xl"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label class="info-card-label text-base">Adresse Email</label>
                                        <p class="info-card-value text-lg font-medium">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-card-icon">
                                        <i class="icon-phone text-2xl"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label class="info-card-label text-base">Numéro de Téléphone</label>
                                        <p class="info-card-value text-lg font-medium">{{ $user->numero ?? 'Non renseigné' }}</p>
                                    </div>
                                </div>

                                <div class="info-card">
                                    <div class="info-card-icon">
                                        <i class="icon-shield text-2xl"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <label class="info-card-label text-base">Rôle</label>
                                        <div class="role-badge {{ $user->utype == 'ADM' ? 'badge-admin' : 'badge-user' }} text-base">
                                            {{ $user->utype == 'ADM' ? 'Administrateur' : 'Utilisateur' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="profile-note">
                                <div class="note-icon">
                                    <i class="icon-info text-2xl"></i>
                                </div>
                                <div class="note-content">
                                    <p class="note-title text-lg font-semibold">Information</p>
                                    <p class="note-text text-base">Pour mettre à jour vos informations personnelles, veuillez contacter l'administrateur système.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Mot de Passe -->
                <div class="settings-tab-content" id="password-tab">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2 class="settings-card-title">
                                <i class="icon-lock text-primary-600 mr-3 text-xl"></i>
                                <span class="text-xl font-semibold">Changer le Mot de Passe</span>
                            </h2>
                            <p class="settings-card-description text-lg">Mettez à jour votre mot de passe pour sécuriser votre compte</p>
                        </div>

                        <form action="{{ route('admin.settings.change.password') }}" method="POST" class="settings-form password-form">
                            @csrf

                            <div class="form-section">
                                <div class="form-grid">
                                    <!-- Mot de passe actuel -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Mot de Passe Actuel <span class="text-red-500">*</span>
                                        </label>
                                        <div class="password-input-wrapper">
                                            <input type="password"
                                                   name="current_password"
                                                   id="current-password"
                                                   class="form-control text-base @error('current_password') border-error @enderror"
                                                   placeholder="Entrez votre mot de passe actuel"
                                                   required>
                                            <button type="button"
                                                    class="password-toggle"
                                                    onclick="togglePassword('current-password', this)">
                                                <i class="icon-eye text-lg"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nouveau mot de passe -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Nouveau Mot de Passe <span class="text-red-500">*</span>
                                        </label>
                                        <div class="password-input-wrapper">
                                            <input type="password"
                                                   name="new_password"
                                                   id="new-password"
                                                   class="form-control text-base @error('new_password') border-error @enderror"
                                                   placeholder="Entrez votre nouveau mot de passe"
                                                   required
                                                   onkeyup="checkPasswordStrength(this.value)">
                                            <button type="button"
                                                    class="password-toggle"
                                                    onclick="togglePassword('new-password', this)">
                                                <i class="icon-eye text-lg"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength-meter">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strength-fill"></div>
                                            </div>
                                            <span class="strength-text text-base" id="strength-text">Très Faible</span>
                                        </div>
                                        <div class="password-hints" id="password-hints">
                                            <p class="hint-title text-base">Le mot de passe doit contenir :</p>
                                            <ul class="hint-list">
                                                <li class="hint-item text-base" data-rule="length">Au moins 8 caractères</li>
                                                <li class="hint-item text-base" data-rule="uppercase">Une lettre majuscule</li>
                                                <li class="hint-item text-base" data-rule="lowercase">Une lettre minuscule</li>
                                                <li class="hint-item text-base" data-rule="number">Un chiffre</li>
                                                <li class="hint-item text-base" data-rule="special">Un caractère spécial</li>
                                            </ul>
                                        </div>
                                        @error('new_password')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirmer le mot de passe -->
                                    <div class="form-group">
                                        <label class="form-label text-base">
                                            Confirmer le Mot de Passe <span class="text-red-500">*</span>
                                        </label>
                                        <div class="password-input-wrapper">
                                            <input type="password"
                                                   name="new_password_confirmation"
                                                   id="confirm-password"
                                                   class="form-control text-base @error('new_password_confirmation') border-error @enderror"
                                                   placeholder="Confirmez votre nouveau mot de passe"
                                                   required
                                                   onkeyup="checkPasswordMatch()">
                                            <button type="button"
                                                    class="password-toggle"
                                                    onclick="togglePassword('confirm-password', this)">
                                                <i class="icon-eye text-lg"></i>
                                            </button>
                                        </div>
                                        <div class="password-match hidden text-base" id="password-match">
                                            <i class="icon-check-circle text-green-500"></i>
                                            <span class="match-text">Les mots de passe correspondent</span>
                                        </div>
                                        @error('new_password_confirmation')
                                            <div class="error-message text-base">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Note des exigences -->
                            <div class="requirements-note">
                                <div class="note-icon">
                                    <i class="icon-shield text-2xl"></i>
                                </div>
                                <div class="note-content">
                                    <p class="note-title text-lg font-semibold">Exigences du Mot de Passe</p>
                                    <p class="note-text text-base">
                                        Pour des raisons de sécurité, votre mot de passe doit comporter au moins 8 caractères et contenir un mélange de lettres majuscules, minuscules, chiffres et caractères spéciaux.
                                    </p>
                                </div>
                            </div>

                            <!-- Actions du formulaire -->
                            <div class="form-actions">
                                <button type="button" class="btn-secondary text-base" onclick="resetPasswordForm()">
                                    <i class="icon-refresh-cw mr-3"></i>
                                    Effacer le Formulaire
                                </button>
                                <button type="submit" class="btn-primary text-base" id="submit-password">
                                    <i class="icon-lock mr-3"></i>
                                    Changer le Mot de Passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Changement d'onglet
    $('.settings-tab').on('click', function() {
        const tabId = $(this).data('tab');

        // Mettre à jour l'onglet actif
        $('.settings-tab').removeClass('active');
        $(this).addClass('active');

        // Afficher le contenu actif
        $('.settings-tab-content').removeClass('active');
        $(`#${tabId}-tab`).addClass('active');
    });

    // Validation du formulaire
    $('.settings-form').on('submit', function(e) {
        const form = $(this);
        let isValid = true;

        // Vérifier les champs requis
        form.find('[required]').each(function() {
            const field = $(this);
            if (!field.val().trim()) {
                field.addClass('border-error');
                isValid = false;
            }
        });

        // Vérifier la correspondance des mots de passe
        if (form.hasClass('password-form')) {
            const newPass = $('#new-password').val();
            const confirmPass = $('#confirm-password').val();

            if (newPass !== confirmPass) {
                $('#confirm-password').addClass('border-error');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            showNotification('Veuillez remplir tous les champs requis correctement.', 'error');
        }
    });

    // Masquer automatiquement les alertes après 5 secondes
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
});

// Fonction de prévisualisation de fichier
function previewFile(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(file);

        // Afficher le conteneur de prévisualisation
        const previewContainer = preview.parentElement;
        previewContainer.classList.remove('hidden');
    }
}

// Supprimer la prévisualisation de fichier
function removePreview(type) {
    const previewId = type + '-preview';
    const inputId = type + '-input';

    document.getElementById(previewId).classList.add('hidden');
    document.getElementById(previewId).querySelector('img').src = '';
    document.getElementById(inputId).value = '';
}

// Supprimer le fichier existant
function removeFile(type) {
    const removeInput = document.getElementById('remove-' + type);
    const currentFile = document.querySelector(`[onclick="removeFile('${type}')"]`).closest('.current-file');

    removeInput.value = '1';
    currentFile.remove();
}

// Basculer la visibilité du mot de passe
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('icon-eye');
        icon.classList.add('icon-eye-off');
    } else {
        input.type = 'password';
        icon.classList.remove('icon-eye-off');
        icon.classList.add('icon-eye');
    }
}

// Vérifier la force du mot de passe
function checkPasswordStrength(password) {
    const hints = document.querySelectorAll('.hint-item');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    const rules = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };

    // Mettre à jour les indications
    hints.forEach(hint => {
        const rule = hint.dataset.rule;
        if (rules[rule]) {
            hint.classList.add('valid');
            hint.classList.remove('invalid');
        } else {
            hint.classList.remove('valid');
            hint.classList.add('invalid');
        }
    });

    // Calculer le score de force
    const score = Object.values(rules).filter(Boolean).length;
    const percent = (score / 5) * 100;

    // Mettre à jour la jauge de force
    strengthFill.style.width = percent + '%';

    // Mettre à jour le texte et la couleur de force
    let text = '';
    let color = '';

    switch(score) {
        case 0:
        case 1:
            text = 'Très Faible';
            color = 'bg-red-500';
            break;
        case 2:
            text = 'Faible';
            color = 'bg-orange-500';
            break;
        case 3:
            text = 'Moyen';
            color = 'bg-yellow-500';
            break;
        case 4:
            text = 'Bon';
            color = 'bg-blue-500';
            break;
        case 5:
            text = 'Fort';
            color = 'bg-green-500';
            break;
    }

    strengthFill.className = 'strength-fill ' + color;
    strengthText.textContent = text;
    strengthText.className = 'strength-text ' + color.replace('bg-', 'text-');
}

// Vérifier la correspondance des mots de passe
function checkPasswordMatch() {
    const newPass = document.getElementById('new-password').value;
    const confirmPass = document.getElementById('confirm-password').value;
    const matchDiv = document.getElementById('password-match');

    if (newPass && confirmPass) {
        if (newPass === confirmPass) {
            matchDiv.classList.add('matched');
            matchDiv.classList.remove('mismatched');
            matchDiv.querySelector('.match-text').textContent = 'Les mots de passe correspondent';
        } else {
            matchDiv.classList.remove('matched');
            matchDiv.classList.add('mismatched');
            matchDiv.querySelector('.match-text').textContent = 'Les mots de passe ne correspondent pas';
        }
        matchDiv.classList.remove('hidden');
    } else {
        matchDiv.classList.add('hidden');
    }
}

// Réinitialiser le formulaire de mot de passe
function resetPasswordForm() {
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';

    // Réinitialiser la jauge de force
    document.getElementById('strength-fill').style.width = '0%';
    document.getElementById('strength-text').textContent = 'Très Faible';

    // Réinitialiser les indications
    document.querySelectorAll('.hint-item').forEach(hint => {
        hint.classList.remove('valid', 'invalid');
    });

    // Masquer l'indicateur de correspondance
    document.getElementById('password-match').classList.add('hidden');
}

// Supprimer la photo de profil
function removeProfilePicture() {
    const confirmDelete = confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?');
    if (confirmDelete) {
        // Ici, vous devrez ajouter la logique pour supprimer la photo de profil
        // Cela pourrait être une requête AJAX vers votre backend
        console.log('Suppression de la photo de profil');
    }
}

// Afficher une notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="icon-${type === 'success' ? 'check-circle' : 'alert-circle'} text-xl mr-3"></i>
        <span class="text-base">${message}</span>
    `;

    document.body.appendChild(notification);

    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
/* Styles de base */
:root {
    --primary-color: #4F46E5;
    --primary-light: #EEF2FF;
    --success-color: #10B981;
    --danger-color: #EF4444;
    --warning-color: #F59E0B;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --gray-900: #111827;
}

/* Composants de mise en page */
.page-header {
    margin-bottom: 2.5rem;
}

.page-title h1 {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--gray-900);
    line-height: 1.2;
}

.page-title p {
    color: var(--gray-600);
    margin-top: 0.75rem;
    line-height: 1.5;
}

.breadcrumbs {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.5rem;
    font-size: 1.125rem;
}

.breadcrumb-link {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.2s;
    font-weight: 500;
}

.breadcrumb-link:hover {
    color: var(--gray-700);
}

.breadcrumb-separator {
    color: var(--gray-400);
}

.breadcrumb-current {
    color: var(--gray-600);
    font-weight: 500;
}

/* Alertes */
.alert {
    padding: 1.25rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    animation: slideIn 0.3s ease-out;
    font-size: 1.125rem;
}

.alert-success {
    background-color: #D1FAE5;
    color: #065F46;
    border: 2px solid #A7F3D0;
}

.alert-danger {
    background-color: #FEE2E2;
    color: #7F1D1D;
    border: 2px solid #FECACA;
}

.alert i {
    margin-right: 0.75rem;
    font-size: 1.5rem;
}

/* Conteneur des paramètres */
.settings-container {
    background: white;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Onglets */
.settings-tabs {
    display: flex;
    background: var(--gray-50);
    border-bottom: 2px solid var(--gray-200);
    padding: 0 2rem;
}

.settings-tab {
    padding: 1.25rem 2rem;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-600);
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: all 0.2s;
}

.settings-tab:hover {
    color: var(--gray-900);
    background: var(--gray-100);
}

.settings-tab.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: white;
    font-weight: 700;
}

.settings-tab i {
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

/* Contenu des onglets */
.settings-content {
    padding: 2.5rem;
}

.settings-tab-content {
    display: none;
}

.settings-tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease-out;
}

/* Carte des paramètres */
.settings-card {
    background: white;
}

.settings-card-header {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid var(--gray-200);
}

.settings-card-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
}

.settings-card-title i {
    margin-right: 0.75rem;
    font-size: 1.5rem;
}

.settings-card-description {
    color: var(--gray-600);
    font-size: 1.125rem;
    line-height: 1.6;
}

/* Styles de formulaire */
.settings-form {
    margin-top: 2.5rem;
}

.form-section {
    margin-bottom: 3rem;
    padding-bottom: 2.5rem;
    border-bottom: 2px solid var(--gray-100);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--gray-100);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.form-group.col-span-2 {
    grid-column: span 2;
}

.form-label {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid var(--gray-300);
    border-radius: 0.75rem;
    background: white;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.form-control.border-error {
    border-color: var(--danger-color);
}

.form-control.border-error:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

.error-message {
    font-size: 0.9375rem;
    color: var(--danger-color);
    margin-top: 0.5rem;
    font-weight: 500;
}

.form-group {
    margin-bottom: 1.5rem;
}

/* Input avec préfixe/suffixe */
.input-with-prefix,
.input-with-suffix {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix,
.input-suffix {
    position: absolute;
    padding: 0 1rem;
    color: var(--gray-500);
    font-size: 1rem;
    font-weight: 500;
}

.input-prefix {
    left: 0;
}

.input-suffix {
    right: 0;
}

.input-with-prefix .form-control {
    padding-left: 3rem;
}

.input-with-suffix .form-control {
    padding-right: 3rem;
}

/* Téléchargement de fichiers */
.file-upload-area {
    margin-top: 0.75rem;
}

.current-file {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.25rem;
    background: var(--gray-50);
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    border: 2px solid var(--gray-200);
}

.current-file-preview {
    width: 4rem;
    height: 4rem;
    object-fit: contain;
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.current-file-preview.favicon {
    width: 2.5rem;
    height: 2.5rem;
}

.current-file-info {
    flex: 1;
}

.current-file-name {
    display: block;
    font-size: 1rem;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.remove-file-btn {
    background: none;
    border: none;
    font-size: 0.9375rem;
    color: var(--danger-color);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0;
    font-weight: 600;
}

.remove-file-btn:hover {
    color: #DC2626;
}

.file-upload-wrapper {
    position: relative;
    border: 3px dashed var(--gray-300);
    border-radius: 0.75rem;
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.file-upload-wrapper:hover {
    border-color: var(--primary-color);
    background: var(--primary-light);
}

.file-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.file-upload-placeholder {
    pointer-events: none;
}

.file-upload-text {
    display: block;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.file-upload-hint {
    font-size: 1rem;
    color: var(--gray-500);
}

.file-preview {
    position: relative;
    margin-top: 1.5rem;
}

.preview-image {
    max-width: 100%;
    max-height: 12rem;
    object-fit: contain;
    border: 2px solid var(--gray-200);
    border-radius: 0.75rem;
    padding: 0.75rem;
    background: white;
}

.remove-preview-btn {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 2rem;
    height: 2rem;
    background: var(--danger-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.remove-preview-btn:hover {
    background: #DC2626;
}

/* Boutons */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1.5rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid var(--gray-200);
}

.btn-primary,
.btn-secondary {
    padding: 0.875rem 2rem;
    font-size: 1.125rem;
    font-weight: 600;
    border-radius: 0.75rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    border: none;
    min-height: 3.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: #4338CA;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
}

.btn-secondary {
    background: white;
    color: var(--gray-700);
    border: 2px solid var(--gray-300);
}

.btn-secondary:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    transform: translateY(-2px);
}

/* Onglet Profil */
.profile-content {
    margin-top: 2.5rem;
}

.profile-picture-section {
    display: flex;
    align-items: center;
    gap: 3rem;
    margin-bottom: 3.5rem;
    padding-bottom: 2.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.profile-picture-wrapper {
    position: relative;
    width: 10rem;
    height: 10rem;
}

.profile-picture {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.profile-picture-actions {
    position: absolute;
    bottom: 0;
    right: 0;
    display: flex;
    gap: 0.75rem;
}

.btn-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    font-size: 1.25rem;
}

.btn-icon:hover {
    background: #4338CA;
    transform: scale(1.1);
}

.profile-picture-info {
    flex: 1;
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.profile-email {
    color: var(--gray-600);
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.profile-hint {
    font-size: 1rem;
    color: var(--gray-500);
}

/* Grille d'informations du profil */
.profile-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 2.5rem;
}

.info-card {
    background: var(--gray-50);
    border-radius: 1rem;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    border: 2px solid var(--gray-200);
    transition: transform 0.2s;
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.info-card-icon {
    width: 4rem;
    height: 4rem;
    background: white;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--gray-200);
}

.info-card-icon i {
    color: var(--primary-color);
}

.info-card-content {
    flex: 1;
}

.info-card-label {
    display: block;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.info-card-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
}

/* Badge de rôle */
.role-badge {
    display: inline-block;
    padding: 0.5rem 1.25rem;
    font-size: 1rem;
    font-weight: 700;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge-admin {
    background: #EDE9FE;
    color: #7C3AED;
    border: 2px solid #DDD6FE;
}

.badge-user {
    background: #DBEAFE;
    color: #1D4ED8;
    border: 2px solid #BFDBFE;
}

/* Notes */
.profile-note,
.requirements-note {
    background: #F0F9FF;
    border: 2px solid #BAE6FD;
    border-radius: 1rem;
    padding: 2rem;
    display: flex;
    gap: 1.5rem;
}

.note-icon {
    flex-shrink: 0;
    width: 3rem;
    height: 3rem;
    background: white;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #BAE6FD;
}

.note-icon i {
    color: #0284C7;
}

.note-content {
    flex: 1;
}

.note-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #075985;
    margin-bottom: 0.5rem;
}

.note-text {
    font-size: 1rem;
    color: #0C4A6E;
    line-height: 1.6;
}

.requirements-note {
    background: #FEF3C7;
    border-color: #FDE68A;
}

.requirements-note .note-icon {
    border-color: #FDE68A;
}

.requirements-note .note-icon i {
    color: #D97706;
}

.requirements-note .note-title {
    color: #92400E;
}

.requirements-note .note-text {
    color: #78350F;
}

/* Formulaire de mot de passe */
.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-500);
    cursor: pointer;
    padding: 0.5rem;
}

.password-toggle:hover {
    color: var(--gray-700);
}

/* Jauge de force du mot de passe */
.password-strength-meter {
    margin-top: 1rem;
}

.strength-bar {
    height: 0.5rem;
    background: var(--gray-200);
    border-radius: 9999px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.strength-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.3s, background-color 0.3s;
}

.strength-text {
    font-size: 1rem;
    font-weight: 600;
}

/* Indications du mot de passe */
.password-hints {
    margin-top: 1.5rem;
}

.hint-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
}

.hint-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.hint-item {
    font-size: 0.9375rem;
    color: var(--gray-500);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.hint-item:before {
    content: '○';
    margin-right: 0.75rem;
    font-size: 0.75rem;
}

.hint-item.valid {
    color: var(--success-color);
    font-weight: 600;
}

.hint-item.valid:before {
    content: '✓';
    color: var(--success-color);
}

.hint-item.invalid {
    color: var(--danger-color);
}

/* Indicateur de correspondance des mots de passe */
.password-match {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1rem;
    margin-top: 0.75rem;
    font-weight: 600;
}

.password-match.matched {
    color: var(--success-color);
}

.password-match.mismatched {
    color: var(--danger-color);
}

.password-match.mismatched .icon-check-circle {
    display: none;
}

.password-match.mismatched:before {
    content: '✗';
    font-size: 1.125rem;
    font-weight: bold;
}

/* Utilitaires de masquage */
.hidden {
    display: none !important;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Notifications */
.notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    padding: 1.25rem 1.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    animation: slideIn 0.3s ease-out;
    z-index: 9999;
    font-size: 1.125rem;
    font-weight: 500;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.notification-success {
    background: var(--success-color);
    color: white;
}

.notification-error {
    background: var(--danger-color);
    color: white;
}

.notification i {
    margin-right: 0.75rem;
    font-size: 1.5rem;
}

/* Design responsive */
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.col-span-2 {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .settings-tabs {
        flex-direction: column;
        padding: 0;
    }

    .settings-tab {
        padding: 1.5rem;
        border-bottom: 2px solid var(--gray-200);
        border-left: 4px solid transparent;
        justify-content: center;
    }

    .settings-tab.active {
        border-left-color: var(--primary-color);
        border-bottom-color: var(--gray-200);
    }

    .profile-picture-section {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }

    .profile-info-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-primary,
    .btn-secondary {
        width: 100%;
    }
}

@media (max-width: 640px) {
    .settings-content {
        padding: 1.5rem;
    }

    .settings-card-header {
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
    }

    .page-title h1 {
        font-size: 1.875rem;
    }

    .page-title p {
        font-size: 1.125rem;
    }

    .settings-card-title {
        font-size: 1.5rem;
    }

    .settings-card-description {
        font-size: 1rem;
    }

    .form-section-title {
        font-size: 1.125rem;
    }
}

/* Ajustements de taille de police pour les petits écrans */
@media (max-width: 480px) {
    .form-control,
    .form-label,
    .error-message,
    .btn-primary,
    .btn-secondary {
        font-size: 0.9375rem;
    }

    .settings-tab {
        font-size: 1rem;
    }

    .profile-name {
        font-size: 1.5rem;
    }

    .profile-email {
        font-size: 1.125rem;
    }
}
</style>
@endpush
