@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Modern Header -->
        <div class="modern-page-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="page-title-wrapper">
                    <h1 class="page-title mb-2">
                        <i class="icon-edit-3 me-2" style="color: var(--gold-primary);"></i>
                        Modifier l'article
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Tableau de Bord</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.blogs')}}">Blog</a></li>
                            <li class="breadcrumb-item active">{{ Str::limit($blog->title, 30) }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.blogs') }}" class="btn-outline-modern">
                        <i class="icon-arrow-left"></i>
                        <span>Retour à la liste</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('admin.blog.update') }}" method="POST" enctype="multipart/form-data" id="blogForm" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="id" value="{{ $blog->id }}">

            <div class="row g-4">
                <!-- Left Column - Main Content -->
                <div class="col-lg-8">
                    <!-- Title Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-edit-2"></i>
                            </div>
                            <div>
                                <h5>Informations principales</h5>
                                <p class="text-muted small">Modifiez le titre et le contenu de l'article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Titre de l'article <span class="required-star">*</span>
                                    <span class="label-hint">Maximum 200 caractères</span>
                                </label>
                                <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror"
                                       value="{{ old('title', $blog->title) }}" required
                                       placeholder="Ex: Comment choisir la bague parfaite ?">
                                @error('title')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="titleCounter">{{ strlen(old('title', $blog->title)) }}/200</div>
                            </div>

                            <!-- Slug -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Slug (URL) <span class="required-star">*</span>
                                    <span class="label-hint">Identifiant unique pour l'URL</span>
                                </label>
                                <div class="slug-input-wrapper">
                                    <input type="text" name="slug" class="form-control-modern slug-input @error('slug') is-invalid @enderror"
                                           value="{{ old('slug', $blog->slug) }}" required>
                                    <button type="button" class="btn-generate-slug" title="Générer le slug depuis le titre">
                                        <i class="icon-refresh-cw"></i>
                                    </button>
                                </div>
                                @error('slug')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">https://votresite.com/blog/<span class="slug-preview">{{ old('slug', $blog->slug) }}</span></small>
                            </div>

                            <!-- Excerpt -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Extrait <span class="required-star">*</span>
                                    <span class="label-hint">Court résumé pour les aperçus</span>
                                </label>
                                <textarea name="excerpt" class="form-control-modern @error('excerpt') is-invalid @enderror"
                                          rows="3" required
                                          placeholder="Un résumé captivant de votre article...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                @error('excerpt')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="excerptCounter">{{ strlen(old('excerpt', $blog->excerpt)) }}/500</div>
                            </div>

                            <!-- Content - Summernote -->
                            <div class="mb-3">
                                <label class="form-label-modern">
                                    Contenu de l'article <span class="required-star">*</span>
                                    <span class="label-hint">Utilisez l'éditeur pour formater votre texte</span>
                                </label>
                                <textarea name="content" id="summernote" class="form-control @error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Settings -->
                <div class="col-lg-4">
                    <!-- Current Image Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-image"></i>
                            </div>
                            <div>
                                <h5>Image actuelle</h5>
                                <p class="text-muted small">Image principale de l'article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="current-image-container mb-3">
                                <img src="{{ $blog->featured_image_url }}"
                                     alt="{{ $blog->title }}"
                                     class="current-image"
                                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 16px; border: 2px solid var(--border-color);">
                            </div>
                        </div>
                    </div>

                    <!-- New Image Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-camera"></i>
                            </div>
                            <div>
                                <h5>Changer l'image</h5>
                                <p class="text-muted small">Téléchargez une nouvelle image</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="featured-image-uploader" id="featuredImageUploader">
                                <div class="image-preview-area" id="featuredPreviewArea">
                                    <img id="featuredPreview" src="{{ asset('images/placeholder-image.jpg') }}"
                                         alt="Aperçu" class="preview-image">
                                    <div class="image-overlay">
                                        <button type="button" class="btn-change-image" id="changeFeaturedImage">
                                            <i class="icon-edit"></i>
                                        </button>
                                        <button type="button" class="btn-remove-image" id="removeFeaturedImage">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" name="featured_image" id="featuredImageInput"
                                       accept="image/*" style="display: none;">
                                @error('featured_image')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="image-info mt-3">
                                    <div class="info-item">
                                        <i class="icon-info"></i>
                                        <span>Taille recommandée: 1200 x 630px</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="icon-maximize"></i>
                                        <span>Format: 16:9</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="icon-help-circle"></i>
                                        <span>Laissez vide pour conserver l'image actuelle</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Publication Settings Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-settings"></i>
                            </div>
                            <div>
                                <h5>Paramètres de publication</h5>
                                <p class="text-muted small">Gérez la visibilité de l'article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <!-- Status Toggles -->
                            <div class="settings-group mb-4">
                                <div class="setting-item">
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }}>
                                        <label for="is_published"></label>
                                    </div>
                                    <div class="setting-info">
                                        <strong>Article publié</strong>
                                        <small>Visible sur le site</small>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ $blog->is_featured ? 'checked' : '' }}>
                                        <label for="is_featured"></label>
                                    </div>
                                    <div class="setting-info">
                                        <strong>Article en vedette</strong>
                                        <small>Apparaît dans la section vedette</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Publish Date -->
                            <div class="mb-3">
                                <label class="form-label-modern">
                                    <i class="icon-calendar me-1"></i>
                                    Date de publication
                                </label>
                                <input type="datetime-local" name="published_at" class="form-control-modern"
                                       value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-bar-chart-2"></i>
                            </div>
                            <div>
                                <h5>Statistiques</h5>
                                <p class="text-muted small">Performance de l'article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="stats-grid">
                                <div class="stat-box">
                                    <div class="stat-value">{{ $blog->views }}</div>
                                    <div class="stat-label">Vues</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{ $blog->likes ?? 0 }}</div>
                                    <div class="stat-label">J'aime</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{ $blog->shares ?? 0 }}</div>
                                    <div class="stat-label">Partages</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-search"></i>
                            </div>
                            <div>
                                <h5>Optimisation SEO</h5>
                                <p class="text-muted small">Améliorez le référencement</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control-modern"
                                       value="{{ old('meta_title', $blog->meta_title) }}" placeholder="Titre optimisé SEO">
                                <div class="char-counter" id="metaTitleCounter">{{ strlen(old('meta_title', $blog->meta_title ?? '')) }}/60</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-modern">Meta Description</label>
                                <textarea name="meta_description" class="form-control-modern"
                                          rows="3" placeholder="Description pour les moteurs de recherche">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                <div class="char-counter" id="metaDescCounter">{{ strlen(old('meta_description', $blog->meta_description ?? '')) }}/160</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-modern">Mots-clés</label>
                                <input type="text" name="meta_keywords" class="form-control-modern"
                                       value="{{ old('meta_keywords', $blog->meta_keywords) }}"
                                       placeholder="bijoux, mode, tendances">
                                <small class="text-muted">Séparés par des virgules</small>
                            </div>

                            <!-- SEO Preview -->
                            <div class="seo-preview">
                                <h6>Aperçu Google</h6>
                                <div class="preview-card">
                                    <div class="preview-url">https://votresite.com/blog/<span class="slug-display">{{ $blog->slug }}</span></div>
                                    <div class="preview-title" id="seoTitlePreview">{{ $blog->meta_title ?? $blog->title }}</div>
                                    <div class="preview-description" id="seoDescPreview">{{ $blog->meta_description ?? Str::limit($blog->excerpt, 160) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" name="action" value="update" class="btn-modern-primary w-100 mb-2">
                            <i class="icon-save me-2"></i>
                            Mettre à jour
                        </button>

                        <!-- Danger Zone -->
                        <div class="danger-zone mt-4">
                            <div class="danger-header">
                                <i class="icon-alert-triangle"></i>
                                <span>Zone de danger</span>
                            </div>
                            <button type="button" class="btn-modern-danger w-100" id="deleteButton" data-id="{{ $blog->id }}">
                                <i class="icon-trash-2 me-2"></i>
                                Supprimer l'article
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --gold-primary: #D4AF37;
    --gold-light: #F5E7C8;
    --gold-dark: #AA8C2F;
    --dark-bg: #1A1A1A;
    --card-bg: #FFFFFF;
    --border-color: #E5E7EB;
    --text-primary: #111827;
    --text-secondary: #6B7280;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
    --info: #3B82F6;
}

/* Modern Header - Same as add page */
.modern-page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 20px;
    color: white;
    position: relative;
    overflow: hidden;
}

.modern-page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.page-title-wrapper {
    position: relative;
    z-index: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: white;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item,
.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.9rem;
}

.breadcrumb-item.active {
    color: white;
}

.btn-outline-modern {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    color: white;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-outline-modern:hover {
    background: white;
    color: #667eea;
    border-color: white;
    transform: translateY(-2px);
}

/* Modern Cards - Same as add page */
.modern-card {
    background: var(--card-bg);
    border-radius: 24px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.modern-card:hover {
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.card-header-modern {
    padding: 20px 24px;
    background: linear-gradient(to right, #f9fafb, #ffffff);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.card-body-modern {
    padding: 24px;
}

/* Form Elements - Same as add page */
.form-label-modern {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.required-star {
    color: var(--danger);
    margin-left: 3px;
}

.label-hint {
    font-weight: normal;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.form-control-modern {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid var(--border-color);
    border-radius: 16px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-modern:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control-modern.is-invalid {
    border-color: var(--danger);
}

/* Slug Input */
.slug-input-wrapper {
    display: flex;
    gap: 10px;
}

.slug-input {
    flex: 1;
}

.btn-generate-slug {
    width: 50px;
    height: 50px;
    border: 2px solid var(--border-color);
    border-radius: 16px;
    background: white;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-generate-slug:hover {
    border-color: #667eea;
    color: #667eea;
    transform: rotate(180deg);
}

.slug-preview {
    color: #667eea;
    font-weight: 500;
}

/* Char Counter */
.char-counter {
    text-align: right;
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 5px;
}

/* Featured Image Uploader */
.featured-image-uploader {
    border: 2px dashed var(--border-color);
    border-radius: 20px;
    padding: 20px;
    transition: all 0.3s ease;
}

.featured-image-uploader:hover {
    border-color: #667eea;
    background: #f8fafc;
}

.image-preview-area {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 15px;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    opacity: 0;
    transition: all 0.3s ease;
}

.image-preview-area:hover .image-overlay {
    opacity: 1;
}

.btn-change-image,
.btn-remove-image {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: none;
    background: white;
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.btn-change-image:hover {
    background: #667eea;
    color: white;
    transform: scale(1.1);
}

.btn-remove-image:hover {
    background: var(--danger);
    color: white;
    transform: scale(1.1);
}

.image-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.info-item:last-child {
    margin-bottom: 0;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    width: 60px;
    height: 30px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-switch label {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e8f0;
    border-radius: 30px;
    cursor: pointer;
    transition: 0.3s;
}

.toggle-switch label:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    border-radius: 50%;
    transition: 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.toggle-switch input:checked + label {
    background-color: #667eea;
}

.toggle-switch input:checked + label:before {
    transform: translateX(30px);
}

.setting-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info {
    flex: 1;
}

.setting-info strong {
    display: block;
    color: var(--text-primary);
    margin-bottom: 3px;
}

.setting-info small {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    text-align: center;
}

.stat-box {
    padding: 15px;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    border-radius: 16px;
    border: 1px solid var(--border-color);
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    line-height: 1.2;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* SEO Preview */
.seo-preview {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

.seo-preview h6 {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 10px;
}

.preview-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 15px;
    border-left: 3px solid #667eea;
}

.preview-url {
    color: #006621;
    font-size: 0.85rem;
    margin-bottom: 5px;
}

.preview-title {
    color: #1a0dab;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 5px;
    cursor: pointer;
}

.preview-title:hover {
    text-decoration: underline;
}

.preview-description {
    color: #4d5156;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Action Buttons */
.action-buttons {
    position: sticky;
    top: 20px;
}

.btn-modern-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
}

.btn-modern-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

/* Danger Zone */
.danger-zone {
    border: 2px solid rgba(239, 68, 68, 0.2);
    border-radius: 16px;
    padding: 20px;
    background: rgba(239, 68, 68, 0.02);
}

.danger-header {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--danger);
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(239, 68, 68, 0.2);
}

.danger-header i {
    font-size: 1.2rem;
}

.btn-modern-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    background: white;
    border: 2px solid var(--danger);
    border-radius: 12px;
    color: var(--danger);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-modern-danger:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
}

/* Error Message */
.error-message {
    color: var(--danger);
    font-size: 0.9rem;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.error-message::before {
    content: '⚠';
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-page-header {
        padding: 20px;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .card-header-modern {
        flex-direction: column;
        text-align: center;
    }

    .setting-item {
        flex-direction: column;
        text-align: center;
    }

    .action-buttons {
        position: static;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: slideIn 0.5s ease forwards;
}
</style>

@push('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-fr-FR.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Summernote
    if (document.getElementById('summernote')) {
        $('#summernote').summernote({
            height: 400,
            lang: 'fr-FR',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i = 0; i < files.length; i++) {
                        uploadSummernoteImage(files[i]);
                    }
                }
            }
        });
    }

    // Title character counter
    const titleInput = document.querySelector('input[name="title"]');
    const titleCounter = document.getElementById('titleCounter');

    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const count = this.value.length;
            titleCounter.textContent = `${count}/200`;
            updateSEOPreview();
        });
    }

    // Excerpt character counter
    const excerptInput = document.querySelector('textarea[name="excerpt"]');
    const excerptCounter = document.getElementById('excerptCounter');

    if (excerptInput) {
        excerptInput.addEventListener('input', function() {
            const count = this.value.length;
            excerptCounter.textContent = `${count}/500`;
        });
    }

    // Meta title counter
    const metaTitleInput = document.querySelector('input[name="meta_title"]');
    const metaTitleCounter = document.getElementById('metaTitleCounter');

    if (metaTitleInput) {
        metaTitleInput.addEventListener('input', function() {
            const count = this.value.length;
            metaTitleCounter.textContent = `${count}/60`;
            document.getElementById('seoTitlePreview').textContent = this.value || document.querySelector('input[name="title"]').value;
        });
    }

    // Meta description counter
    const metaDescInput = document.querySelector('textarea[name="meta_description"]');
    const metaDescCounter = document.getElementById('metaDescCounter');

    if (metaDescInput) {
        metaDescInput.addEventListener('input', function() {
            const count = this.value.length;
            metaDescCounter.textContent = `${count}/160`;
            document.getElementById('seoDescPreview').textContent = this.value || document.querySelector('textarea[name="excerpt"]').value;
        });
    }

    // Auto-generate slug
    const generateSlug = (text) => {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
    };

    const slugInput = document.querySelector('input[name="slug"]');
    const slugPreview = document.querySelector('.slug-preview');

    document.querySelector('.btn-generate-slug')?.addEventListener('click', function() {
        if (titleInput.value) {
            const slug = generateSlug(titleInput.value);
            slugInput.value = slug;
            slugPreview.textContent = slug;
            document.querySelector('.slug-display').textContent = slug;
        }
    });

    // New image preview
    const featuredImageInput = document.getElementById('featuredImageInput');
    const featuredPreview = document.getElementById('featuredPreview');

    document.getElementById('changeFeaturedImage')?.addEventListener('click', function() {
        featuredImageInput.click();
    });

    featuredImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                featuredPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('removeFeaturedImage')?.addEventListener('click', function() {
        featuredPreview.src = '{{ asset('images/placeholder-image.jpg') }}';
        featuredImageInput.value = '';
    });

    // Form validation
    const form = document.getElementById('blogForm');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();

            // Scroll to first error
            const firstError = form.querySelector('.is-invalid, :invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        form.classList.add('was-validated');
    });

    // Delete functionality
const deleteButton = document.getElementById('deleteButton');
if (deleteButton) {
    deleteButton.addEventListener('click', function(e) {
        e.preventDefault();
        const deleteUrl = this.href;

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible! L'article sera définitivement supprimé.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            background: '#ffffff',
            customClass: {
                popup: 'modern-swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    });
}
    // Update SEO preview
    function updateSEOPreview() {
        const title = titleInput?.value;
        document.getElementById('seoTitlePreview').textContent = metaTitleInput?.value || title || 'Titre de l\'article';

        const slug = slugInput?.value || generateSlug(title || 'titre-article');
        const slugDisplay = document.querySelector('.slug-display');
        if (slugDisplay) {
            slugDisplay.textContent = slug;
        }
    }

    // Function to handle summernote image upload
    function uploadSummernoteImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("admin.blog.upload.image") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#summernote').summernote('insertImage', data.url);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message || 'Erreur lors du téléchargement de l\'image'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Erreur lors du téléchargement de l\'image'
            });
        });
    }
});
</script>
@endpush
@endsection
