@extends('layouts.app')

@section('title', 'Détails du Compte')

@section('content')
<main class="py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Détails du compte</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Account Details Card -->
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle fs-4 me-2 text-primary"></i>
                            <h5 class="mb-0">Informations personnelles</h5>
                        </div>
                    </div>
                    <div class="account-card-body">
                        <form action="{{ route('user.account.details.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Avatar Section -->
                            <div class="avatar-section mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-auto">
                                        <div class="current-avatar">
                                            @if($user->avatar)
                                                <img src="{{ asset('uploads/avatars/' . $user->avatar) }}"
                                                     id="avatarPreview"
                                                     class="rounded-circle"
                                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"
                                                     alt="{{ $user->name }}">
                                            @else
                                                <div class="avatar-placeholder"
                                                     style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 600; border: 3px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="avatar-actions">
                                            <h6 class="mb-2">Photo de profil</h6>
                                            <p class="text-muted small mb-2">JPG, PNG ou GIF. Taille max: 2MB</p>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                                    <i class="fas fa-upload me-1"></i>Changer
                                                </button>
                                                @if($user->avatar)
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAvatar()">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                                @endif
                                            </div>
                                            <input type="file"
                                                   id="avatar"
                                                   name="avatar"
                                                   accept="image/*"
                                                   class="d-none"
                                                   onchange="previewAvatar(this)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Avatar Preview Info -->
                                <div id="avatarPreviewInfo" class="avatar-preview-info mt-3 d-none">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="me-3">
                                            <img id="newAvatarPreview"
                                                 class="rounded"
                                                 width="60"
                                                 height="60"
                                                 style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <p class="mb-1 fw-medium" id="avatarFileName"></p>
                                            <p class="mb-0 small text-muted" id="avatarFileSize"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet *</label>
                                    <input type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <div class="input-group">
                                        <input type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $user->email) }}"
                                            required>
                                        @if($user->hasVerifiedEmail())
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @endif
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if(!$user->hasVerifiedEmail())
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Email non vérifié.
                                            <a href="#" class="text-decoration-none">Renvoyer le lien</a>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $user->numero ?? $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date d'inscription</label>
                                    <div class="form-control bg-light">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password Section -->
                            <div class="password-section mt-5 pt-4 border-top">
                                <div class="d-flex align-items-center mb-4">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    <h6 class="mb-0">Changer le mot de passe</h6>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                id="current_password"
                                                name="current_password">
                                            <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="togglePassword('current_password', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                id="new_password"
                                                name="new_password"
                                                onkeyup="checkPasswordStrength(this.value)">
                                            <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="togglePassword('new_password', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <div class="progress" style="height: 4px;">
                                                <div id="passwordStrengthBar"
                                                    class="progress-bar"
                                                    role="progressbar"
                                                    style="width: 0%"></div>
                                            </div>
                                            <small id="passwordFeedback" class="text-muted"></small>
                                        </div>
                                        @error('new_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control"
                                                id="new_password_confirmation"
                                                name="new_password_confirmation">
                                            <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="togglePassword('new_password_confirmation', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-5 pt-4 border-top">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-5 ms-2">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* ===== PROFILE PAGE STYLES - WITH DYNAMIC COLORS ===== */
.account-card {
    background: var(--surface);
    border-radius: 16px;
    box-shadow: 0 2px 12px var(--card_shadow);
    border: 1px solid var(--border_soft);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 24px;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--card_shadow);
}

.account-card-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, var(--bg_soft), var(--surface));
    border-bottom: 1px solid var(--border_soft);
}

.account-card-header h5 {
    margin: 0;
    color: var(--text_primary);
    font-weight: 600;
}

.account-card-body {
    padding: 1.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0.5rem;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item.active {
    color: var(--text_secondary);
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text_secondary);
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 0.625rem 1rem;
    transition: all 0.2s ease;
    font-size: 0.9375rem;
    background: var(--surface);
    color: var(--text_primary);
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 15%, transparent);
    outline: none;
}

.form-control.bg-light {
    background: var(--bg_soft) !important;
    color: var(--text_secondary);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    border-radius: 10px;
    padding: 0.625rem 1.5rem;
    font-weight: 500;
    color: white;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
    filter: brightness(1.05);
}

.btn-outline-secondary {
    border: 1px solid var(--border);
    color: var(--text_secondary);
    border-radius: 10px;
    padding: 0.625rem 1.5rem;
    font-weight: 500;
    background: transparent;
    transition: all 0.2s ease;
}

.btn-outline-secondary:hover {
    background: var(--text_secondary);
    border-color: var(--text_secondary);
    color: white;
    transform: translateY(-1px);
}

.btn-outline-primary {
    border: 1px solid color-mix(in srgb, var(--primary) 40%, transparent);
    color: var(--primary);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    background: transparent;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-outline-danger {
    border: 1px solid color-mix(in srgb, var(--error) 40%, transparent);
    color: var(--error);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    background: transparent;
    transition: all 0.2s ease;
}

.btn-outline-danger:hover {
    background: var(--error);
    border-color: var(--error);
    color: white;
}

.input-group-text.bg-success {
    background: var(--success) !important;
    color: white;
    border: none;
}

.current-avatar {
    position: relative;
    display: inline-block;
}

.current-avatar img {
    border: 3px solid var(--surface);
    box-shadow: 0 4px 12px var(--card_shadow);
}

.avatar-placeholder {
    border: 3px solid var(--surface);
    box-shadow: 0 4px 12px var(--card_shadow);
    background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
}

.avatar-actions {
    padding: 0.5rem 0;
}

.avatar-preview-info {
    animation: fadeIn 0.3s ease;
}

.avatar-preview-info .bg-light {
    background: var(--bg_soft) !important;
    border-radius: 10px;
}

.text-primary {
    color: var(--primary) !important;
}

.text-muted {
    color: var(--text_secondary) !important;
}

.text-warning {
    color: var(--warning) !important;
}

.progress {
    background-color: var(--bg_soft);
    border-radius: 2px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    transition: width 0.3s ease;
}

.alert-success {
    background: color-mix(in srgb, var(--success) 15%, transparent);
    border-color: color-mix(in srgb, var(--success) 25%, transparent);
    color: var(--success);
}

.alert-danger {
    background: var(--error_bg);
    border-color: color-mix(in srgb, var(--error) 25%, transparent);
    color: var(--error);
}

.invalid-feedback {
    color: var(--error);
}

.form-control.is-invalid {
    border-color: var(--error);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--error) 15%, transparent);
}

.border-top {
    border-color: var(--divider) !important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .account-card-body {
        padding: 1.25rem;
    }

    .avatar-section .row {
        flex-direction: column;
        text-align: center;
    }

    .current-avatar {
        margin-bottom: 1rem;
    }

    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .btn + .btn {
        margin-left: 0 !important;
    }
}
</style>

<script>
// Avatar preview functionality
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Erreur', 'Le fichier est trop volumineux. Taille maximale : 2MB.', 'error');
            input.value = '';
            return;
        }

        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            Swal.fire('Erreur', 'Format de fichier non supporté. Formats acceptés : JPG, PNG, GIF, WEBP.', 'error');
            input.value = '';
            return;
        }

        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('newAvatarPreview').src = e.target.result;
            document.getElementById('avatarPreviewInfo').classList.remove('d-none');
            document.getElementById('avatarFileName').textContent = file.name;
            document.getElementById('avatarFileSize').textContent = 'Taille : ' + formatFileSize(file.size);
        };

        reader.readAsDataURL(file);
    }
}

function removeAvatar() {
    Swal.fire({
        title: 'Supprimer la photo ?',
        text: "Voulez-vous vraiment supprimer votre photo de profil ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            let removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_avatar';
            removeInput.value = '1';
            document.querySelector('form').appendChild(removeInput);
            document.getElementById('avatarPreview').src = '{{ asset("images/default-avatar.png") }}';
            document.getElementById('avatarPreviewInfo').classList.add('d-none');
            document.getElementById('avatar').value = '';
            Swal.fire('Succès', 'Votre photo sera supprimée après la sauvegarde.', 'success');
        }
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrengthBar');
    const feedback = document.getElementById('passwordFeedback');

    const hasMinLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

    let strength = 0;
    if (hasMinLength) strength++;
    if (hasUppercase) strength++;
    if (hasLowercase) strength++;
    if (hasNumber) strength++;
    if (hasSpecial) strength++;

    const width = (strength / 5) * 100;
    strengthBar.style.width = `${width}%`;

    if (strength === 0) {
        strengthBar.className = 'progress-bar bg-danger';
        feedback.textContent = '';
    } else if (strength <= 2) {
        strengthBar.className = 'progress-bar bg-danger';
        feedback.textContent = 'Mot de passe faible';
    } else if (strength <= 4) {
        strengthBar.className = 'progress-bar bg-warning';
        feedback.textContent = 'Mot de passe moyen';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedback.textContent = 'Mot de passe fort';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const avatarContainer = document.querySelector('.current-avatar');
    const avatarInput = document.getElementById('avatar');

    if (avatarContainer) {
        avatarContainer.addEventListener('click', function() {
            avatarInput.click();
        });
        avatarContainer.style.cursor = 'pointer';
    }

    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        if (confirmPassword && password !== confirmPassword) {
            document.getElementById('new_password_confirmation').classList.add('is-invalid');
        } else {
            document.getElementById('new_password_confirmation').classList.remove('is-invalid');
        }
    });

    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        if (password && confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        if (password && confirmPassword && password !== confirmPassword) {
            e.preventDefault();
            Swal.fire('Erreur', 'Les mots de passe ne correspondent pas.', 'error');
        }
    });
});
</script>
@endsection
