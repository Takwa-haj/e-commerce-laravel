@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Modern Header -->
        <div class="modern-page-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="page-title-wrapper">
                    <h1 class="page-title mb-2">
                        <i class="icon-file-text me-2" style="color: var(--gold-primary);"></i>
                        Créer un nouvel article
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Tableau de Bord</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.blogs')}}">Blog</a></li>
                            <li class="breadcrumb-item active">Nouvel article</li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.blogs') }}" class="btn-outline-modern">
                        <i class="icon-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ Session::get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
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
                                <p class="text-muted small">Titre et contenu de l'article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Titre de l'article <span class="required-star">*</span>
                                    <span class="label-hint">Maximum 200 caractères</span>
                                </label>
                                <input type="text" name="title" class="form-control-modern"
                                       value="{{ old('title') }}" required
                                       placeholder="Ex: Comment choisir la bague parfaite ?">
                                @error('title')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="titleCounter">0/200</div>
                            </div>

                            <!-- Slug -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Slug (URL)
                                    <span class="label-hint">Laissez vide pour génération automatique</span>
                                </label>
                                <div class="slug-input-wrapper">
                                    <input type="text" name="slug" class="form-control-modern slug-input"
                                           value="{{ old('slug') }}" placeholder="auto-généré">
                                    <button type="button" class="btn-generate-slug" title="Générer le slug">
                                        <i class="icon-refresh-cw"></i>
                                    </button>
                                </div>
                                <small class="text-muted">https://votresite.com/blog/<span class="slug-preview">{{ old('slug') ?: 'titre-de-larticle' }}</span></small>
                            </div>

                            <!-- Excerpt -->
                            <div class="mb-4">
                                <label class="form-label-modern">
                                    Extrait <span class="required-star">*</span>
                                    <span class="label-hint">Court résumé pour les aperçus</span>
                                </label>
                                <textarea name="excerpt" class="form-control-modern" rows="3" required
                                          placeholder="Un résumé captivant de votre article...">{{ old('excerpt') }}</textarea>
                                <div class="char-counter" id="excerptCounter">0/500</div>
                            </div>

                            <!-- Content - Summernote -->
                            <div class="mb-3">
                                <label class="form-label-modern">
                                    Contenu de l'article <span class="required-star">*</span>
                                    <span class="label-hint">Utilisez l'éditeur pour formater votre texte</span>
                                </label>
                                <textarea name="content" id="summernote" class="form-control">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Media Gallery Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-image"></i>
                            </div>
                            <div>
                                <h5>Galerie d'images</h5>
                                <p class="text-muted small">Ajoutez plusieurs images à votre article</p>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="media-upload-area" id="mediaGallery">
                                <div class="gallery-grid" id="galleryGrid">
                                    <!-- Images will be added here dynamically -->
                                </div>
                                <div class="upload-zone" id="uploadZone">
                                    <i class="icon-upload-cloud upload-icon"></i>
                                    <h6>Glissez vos images ici</h6>
                                    <p class="text-muted small mb-3">ou</p>
                                    <button type="button" class="btn-modern-outline" id="selectImages">
                                        <i class="icon-image me-2"></i>
                                        Sélectionner des images
                                    </button>
                                    <input type="file" name="gallery_images[]" id="galleryInput"
                                           multiple accept="image/*" style="display: none;">
                                    <p class="text-muted small mt-3">
                                        Formats acceptés: JPG, PNG, GIF • Max 5 Mo par image
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Settings -->
                <div class="col-lg-4">
                    <!-- Featured Image Card -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="icon-camera"></i>
                            </div>
                            <div>
                                <h5>Image à la une</h5>
                                <p class="text-muted small">Image principale de l'article</p>
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
                                <div class="image-info mt-3">
                                    <div class="info-item">
                                        <i class="icon-info"></i>
                                        <span>Taille recommandée: 1200 x 630px</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="icon-maximize"></i>
                                        <span>Format: 16:9</span>
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
                                        <input type="checkbox" name="is_published" id="is_published" value="1" checked>
                                        <label for="is_published"></label>
                                    </div>
                                    <div class="setting-info">
                                        <strong>Publier immédiatement</strong>
                                        <small>L'article sera visible sur le site</small>
                                    </div>
                                </div>

                                <div class="setting-item">
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1">
                                        <label for="is_featured"></label>
                                    </div>
                                    <div class="setting-info">
                                        <strong>Article en vedette</strong>
                                        <small>Apparaîtra dans la section vedette</small>
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
                                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                            </div>

                            <!-- Categories -->
                            <div class="mb-3">
                                <label class="form-label-modern">
                                    <i class="icon-tag me-1"></i>
                                    Catégories
                                </label>
                                <select name="categories[]" class="form-control-modern select2" multiple>
                                    <option value="mode">Mode & Tendances</option>
                                    <option value="conseils">Conseils</option>
                                    <option value="entretien">Entretien</option>
                                    <option value="savoir-faire">Savoir-faire</option>
                                </select>
                            </div>

                            <!-- Tags -->
                            <div class="mb-3">
                                <label class="form-label-modern">
                                    <i class="icon-hash me-1"></i>
                                    Tags
                                </label>
                                <input type="text" name="tags" class="form-control-modern tags-input"
                                       placeholder="Ajouter des tags...">
                                <small class="text-muted">Séparez les tags par des virgules</small>
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
                                       value="{{ old('meta_title') }}" placeholder="Titre optimisé SEO">
                                <div class="char-counter" id="metaTitleCounter">0/60</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-modern">Meta Description</label>
                                <textarea name="meta_description" class="form-control-modern"
                                          rows="3" placeholder="Description pour les moteurs de recherche">{{ old('meta_description') }}</textarea>
                                <div class="char-counter" id="metaDescCounter">0/160</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-modern">Mots-clés</label>
                                <input type="text" name="meta_keywords" class="form-control-modern tags-input"
                                       value="{{ old('meta_keywords') }}"
                                       placeholder="bijoux, mode, tendances">
                                <small class="text-muted">Séparés par des virgules</small>
                            </div>

                            <!-- SEO Preview -->
                            <div class="seo-preview">
                                <h6>Aperçu Google</h6>
                                <div class="preview-card">
                                    <div class="preview-url">https://votresite.com/blog/<span class="slug-display">titre-article</span></div>
                                    <div class="preview-title" id="seoTitlePreview">Titre de l'article</div>
                                    <div class="preview-description" id="seoDescPreview">Description de l'article...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" class="btn-modern-primary w-100 mb-2">
                            <i class="icon-save me-2"></i>
                            Publier l'article
                        </button>
                        <button type="button" class="btn-modern-secondary w-100" id="saveDraft">
                            <i class="icon-file me-2"></i>
                            Enregistrer comme brouillon
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modern CSS -->
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

/* Modern Header */
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

/* Modern Cards */
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

/* Form Elements */
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

/* Media Gallery */
.media-upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
}

.media-upload-area:hover {
    border-color: #667eea;
    background: #f8fafc;
}

.upload-zone {
    padding: 30px;
}

.upload-icon {
    font-size: 3rem;
    color: var(--text-secondary);
    margin-bottom: 15px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.gallery-item:hover {
    border-color: #667eea;
    transform: scale(1.05);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-item .remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--danger);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.gallery-item:hover .remove-image {
    opacity: 1;
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
}

.btn-modern-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.btn-modern-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 32px;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 16px;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-modern-secondary:hover {
    background: #f8fafc;
    border-color: #667eea;
    transform: translateY(-2px);
}

.btn-modern-outline {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: white;
    border: 2px solid #667eea;
    border-radius: 12px;
    color: #667eea;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-modern-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
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

    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
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

.modern-card:nth-child(2) {
    animation-delay: 0.1s;
}

.modern-card:nth-child(3) {
    animation-delay: 0.2s;
}
</style>
@endsection

@push('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-fr-FR.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

    // Initialize Select2
    if (document.querySelector('.select2')) {
        $('.select2').select2({
            placeholder: "Sélectionnez des catégories",
            allowClear: true,
            width: '100%'
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
            document.getElementById('seoTitlePreview').textContent = this.value || 'Titre de l\'article';
        });
    }

    // Meta description counter
    const metaDescInput = document.querySelector('textarea[name="meta_description"]');
    const metaDescCounter = document.getElementById('metaDescCounter');

    if (metaDescInput) {
        metaDescInput.addEventListener('input', function() {
            const count = this.value.length;
            metaDescCounter.textContent = `${count}/160`;
            document.getElementById('seoDescPreview').textContent = this.value || 'Description de l\'article...';
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

    document.querySelector('.btn-generate-slug').addEventListener('click', function() {
        if (titleInput.value) {
            const slug = generateSlug(titleInput.value);
            slugInput.value = slug;
            slugPreview.textContent = slug;
        }
    });

    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                const slug = generateSlug(this.value);
                slugInput.value = slug;
                slugPreview.textContent = slug;
            }
        });
    }

    // Featured image upload
    const featuredImageInput = document.getElementById('featuredImageInput');
    const featuredPreview = document.getElementById('featuredPreview');

    document.getElementById('changeFeaturedImage').addEventListener('click', function() {
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

    document.getElementById('removeFeaturedImage').addEventListener('click', function() {
        featuredPreview.src = '{{ asset('images/placeholder-image.jpg') }}';
        featuredImageInput.value = '';
    });

    // Gallery upload
    const galleryInput = document.getElementById('galleryInput');
    const galleryGrid = document.getElementById('galleryGrid');

    document.getElementById('selectImages').addEventListener('click', function() {
        galleryInput.click();
    });

    galleryInput.addEventListener('change', function() {
        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'gallery-item';
                galleryItem.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery image ${index + 1}">
                    <button type="button" class="remove-image" data-index="${index}">
                        <i class="icon-x"></i>
                    </button>
                `;
                galleryGrid.appendChild(galleryItem);

                // Add remove functionality
                galleryItem.querySelector('.remove-image').addEventListener('click', function() {
                    galleryItem.remove();
                });
            }
            reader.readAsDataURL(file);
        });
    });

    // Drag and drop for gallery
    const uploadZone = document.getElementById('uploadZone');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        uploadZone.classList.add('highlight');
    }

    function unhighlight() {
        uploadZone.classList.remove('highlight');
    }

    uploadZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        galleryInput.files = files;
        galleryInput.dispatchEvent(new Event('change'));
    }

    // Save draft
    document.getElementById('saveDraft').addEventListener('click', function() {
        document.getElementById('is_published').checked = false;
        document.getElementById('blogForm').submit();
    });

    // Update SEO preview
    function updateSEOPreview() {
        const title = titleInput.value;
        document.getElementById('seoTitlePreview').textContent = title || 'Titre de l\'article';

        const slug = generateSlug(title || 'titre-article');
        document.querySelector('.slug-display').textContent = slug;
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
