@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Premium Header with Static Gradient -->
        <div class="premium-header mb-4">
            <div class="header-content">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h1 class="header-title">Gestion des Slides & Bannières</h1>
                        <p class="header-subtitle">Gérez tous vos éléments visuels en un seul endroit</p>
                    </div>
                </div>

                <!-- Quick Stats Pills -->
                <div class="quick-stats">
                    <div class="stat-pill">
                        <span class="stat-pill-value">{{ $stats['total'] }}</span>
                        <span class="stat-pill-label">Total</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-pill-value">{{ $stats['active'] }}</span>
                        <span class="stat-pill-label">Actifs</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-pill-value">{{ $stats['home_slides'] }}</span>
                        <span class="stat-pill-label">Slides</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="premium-stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Éléments</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="premium-stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $stats['active'] }}</div>
                        <div class="stat-label">Éléments Actifs</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="premium-stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $stats['home_slides'] }}</div>
                        <div class="stat-label">Slides Accueil</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="premium-stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $stats['shop_banners'] }}</div>
                        <div class="stat-label">Bannières Boutique</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="premium-stat-card stat-secondary">
                    <div class="stat-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $stats['category_banners'] }}</div>
                        <div class="stat-label">Bannières Catégorie</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Content Card -->
        <div class="premium-content-card">
            <!-- Premium Toolbar -->
            <div class="premium-toolbar">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <form method="GET" action="{{ route('admin.slides.manage') }}" class="search-form">
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text"
                                       class="search-input"
                                       placeholder="Rechercher par titre..."
                                       name="search"
                                       value="{{ $search ?? '' }}">
                                @if($search)
                                <a href="{{ route('admin.slides.manage', ['type' => $type]) }}" class="search-clear">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="toolbar-actions">
                            <!-- Type Filter -->
                            <div class="filter-tabs">
                                <a href="{{ route('admin.slides.manage') }}" class="filter-tab {{ $type == 'all' ? 'active' : '' }}">
                                    Tous
                                </a>
                                <a href="{{ route('admin.slides.manage', ['type' => 'home_slides', 'search' => $search]) }}" class="filter-tab {{ $type == 'home_slides' ? 'active' : '' }}">
                                    Slides
                                </a>
                                <a href="{{ route('admin.slides.manage', ['type' => 'shop_banners', 'search' => $search]) }}" class="filter-tab {{ $type == 'shop_banners' ? 'active' : '' }}">
                                    Boutique
                                </a>
                                <a href="{{ route('admin.slides.manage', ['type' => 'category_banners', 'search' => $search]) }}" class="filter-tab {{ $type == 'category_banners' ? 'active' : '' }}">
                                    Catégories
                                </a>
                            </div>

                            <!-- Add Button -->
                            <div class="add-dropdown">
                                <button class="add-btn" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-plus-circle"></i>
                                    Nouvel élément
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.slide.add') }}">
                                        <i class="fas fa-home dropdown-icon text-primary"></i>Slide Accueil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.shop-banner.add') }}">
                                        <i class="fas fa-tag dropdown-icon text-warning"></i>Bannière Boutique
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.category-banner.add') }}">
                                        <i class="fas fa-folder dropdown-icon text-info"></i>Bannière Catégorie
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="premium-alert premium-alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span>{{ session('status') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Premium Table -->
            <div class="premium-table-container">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="80">Image</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th width="80">Ordre</th>
                            <th width="100">Statut</th>
                            <th width="100">Date</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                        <tr class="premium-row">
                            <td>
                                <span class="id-badge">#{{ $slide->id }}</span>
                            </td>
                            <td>
                                <div class="table-image">
                                    <img src="{{ asset($slide->image_path) }}"
                                         alt="{{ $slide->display_title }}">
                                </div>
                            </td>
                            <td>
                                <div class="item-title">{{ $slide->display_title }}</div>
                                @if($slide->display_subtitle)
                                    <div class="item-subtitle">{{ Str::limit($slide->display_subtitle, 30) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="type-badge type-{{ $slide->item_type }}">
                                    <i class="{{ $slide->type_icon }}"></i>
                                    {{ $slide->type_label }}
                                </span>
                            </td>
                            <td>
                                <span class="order-badge">
                                    <i class="fas fa-sort me-1"></i>{{ $slide->order_value }}
                                </span>
                            </td>
                            <td>
                                <label class="premium-switch">
                                    <input type="checkbox"
                                           class="status-toggle"
                                           data-type="{{ $slide->item_type }}"
                                           data-id="{{ $slide->id }}"
                                           {{ isset($slide->display_status) && $slide->display_status ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="date-badge">
                                    <div class="date-day">{{ $slide->created_at->format('d') }}</div>
                                    <div class="date-month">{{ $slide->created_at->format('M') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ $slide->edit_route }}" class="action-btn edit-btn" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="action-btn delete-btn"
                                            data-type="{{ $slide->item_type }}"
                                            data-id="{{ $slide->id }}"
                                            data-title="{{ $slide->display_title }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <a href="{{ $slide->edit_route }}" class="action-btn view-btn" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-content">
                                    <div class="empty-icon">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <h3>Aucun élément trouvé</h3>
                                    <p>Commencez par créer votre premier slide ou bannière</p>
                                    <div class="empty-actions">
                                        <a href="{{ route('admin.slide.add') }}" class="btn btn-primary me-2">
                                            <i class="fas fa-plus-circle me-2"></i>Nouveau Slide
                                        </a>
                                        <a href="{{ route('admin.shop-banner.add') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-tag me-2"></i>Bannière Boutique
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Premium Pagination -->
            @if($slides->hasPages())
            <div class="premium-pagination">
                <div class="pagination-info">
                    Affichage de {{ $slides->firstItem() ?? 0 }} à {{ $slides->lastItem() ?? 0 }} sur {{ $slides->total() }} éléments
                </div>
                <div class="pagination-links">
                    {{ $slides->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Premium Header */
    .premium-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 25px 30px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-right: 20px;
    }

    .header-title {
        font-size: 26px;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .quick-stats {
        display: flex;
        gap: 15px;
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 40px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-pill-value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-pill-label {
        font-size: 13px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Premium Stat Cards */
    .premium-stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.02);
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .premium-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 15px;
    }

    .stat-primary .stat-icon {
        background: linear-gradient(135deg, #667eea 20%, #764ba2 100%);
        color: white;
    }

    .stat-success .stat-icon {
        background: linear-gradient(135deg, #34b1aa 0%, #2c8f8b 100%);
        color: white;
    }

    .stat-info .stat-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-warning .stat-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .stat-secondary .stat-icon {
        background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);
        color: white;
    }

    .stat-details {
        flex: 1;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Premium Content Card */
    .premium-content-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Premium Toolbar */
    .premium-toolbar {
        padding: 20px 25px;
        border-bottom: 1px solid #edf2f7;
        background: #fafbfc;
    }

    .search-form {
        max-width: 400px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 16px;
    }

    .search-input {
        width: 100%;
        height: 45px;
        padding: 0 45px 0 45px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-clear {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        text-decoration: none;
    }

    .search-clear:hover {
        color: #e53e3e;
    }

    .toolbar-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 20px;
    }

    .filter-tabs {
        display: flex;
        gap: 5px;
        background: #edf2f7;
        padding: 4px;
        border-radius: 12px;
    }

    .filter-tab {
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 500;
        color: #718096;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .filter-tab.active {
        background: white;
        color: #667eea;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .add-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        padding: 10px;
        margin-top: 10px;
    }

    .dropdown-item {
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .dropdown-item:hover {
        background: #f7fafc;
        transform: translateX(5px);
    }

    .dropdown-icon {
        width: 20px;
        margin-right: 10px;
    }

    /* Premium Alert */
    .premium-alert {
        margin: 20px 25px;
        padding: 15px 20px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-alert-success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c8f8b 100%);
        color: white;
    }

    .alert-icon {
        font-size: 20px;
    }

    .alert-close {
        margin-left: auto;
        background: none;
        border: none;
        color: white;
        opacity: 0.8;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Premium Table */
    .premium-table-container {
        padding: 0 25px;
        overflow-x: auto;
    }

    .premium-table {
        width: 100%;
        border-collapse: collapse;
    }

    .premium-table thead th {
        text-align: left;
        padding: 20px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edf2f7;
    }

    .premium-row {
        transition: background-color 0.3s;
        border-bottom: 1px solid #edf2f7;
    }

    .premium-row:hover {
        background-color: #fafbfc;
    }

    .premium-row td {
        padding: 15px 10px;
        vertical-align: middle;
    }

    .id-badge {
        background: #edf2f7;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
    }

    .table-image {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .item-subtitle {
        font-size: 12px;
        color: #718096;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .type-home_slide {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .type-shop_banner {
        background: rgba(250, 112, 154, 0.1);
        color: #fa709a;
    }

    .type-category_banner {
        background: rgba(95, 44, 130, 0.1);
        color: #5f2c82;
    }

    .order-badge {
        background: #edf2f7;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        color: #4a5568;
        display: inline-flex;
        align-items: center;
    }

    .date-badge {
        background: linear-gradient(135deg, #667eea 10%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 5px 10px;
        display: inline-block;
        text-align: center;
        min-width: 50px;
    }

    .date-day {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.2;
    }

    .date-month {
        font-size: 11px;
        opacity: 0.9;
        text-transform: uppercase;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .edit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .delete-btn {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .view-btn {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    /* Premium Switch */
    .premium-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
        margin: 0;
    }

    .premium-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e53e3e;
        transition: .3s;
        border-radius: 34px;
    }

    .switch-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .switch-slider {
        background-color: #48bb78;
    }

    input:checked + .switch-slider:before {
        transform: translateX(24px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-content {
        max-width: 400px;
        margin: 0 auto;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        color: white;
    }

    .empty-state h3 {
        color: #2d3748;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 20px;
    }

    /* Premium Pagination */
    .premium-pagination {
        padding: 20px 25px;
        border-top: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        background: #fafbfc;
    }

    .pagination-info {
        color: #718096;
        font-size: 13px;
    }

    .pagination-links .pagination {
        margin: 0;
        gap: 5px;
    }

    .pagination-links .page-link {
        border: none;
        border-radius: 10px;
        color: #4a5568;
        font-size: 14px;
        padding: 8px 12px;
        transition: all 0.3s;
    }

    .pagination-links .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .pagination-links .page-link:hover {
        background: #edf2f7;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .quick-stats {
            width: 100%;
            justify-content: space-between;
        }

        .toolbar-actions {
            margin-top: 15px;
            justify-content: flex-start;
        }

        .premium-table td {
            padding: 12px 8px;
        }

        .action-buttons {
            gap: 4px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Status toggle with simple confirmation
    $('.status-toggle').on('change', function() {
        const $this = $(this);
        const type = $this.data('type');
        const id = $this.data('id');
        const status = $this.is(':checked');

        // Simple confirmation without loading states
        if (confirm(status ? 'Activer cet élément?' : 'Désactiver cet élément?')) {
            $.ajax({
                url: '{{ route("admin.slides.toggle-status") }}',
                method: 'POST',
                data: {
                    type: type,
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Simple feedback
                        alert('Statut mis à jour avec succès');
                    } else {
                        $this.prop('checked', !status);
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function() {
                    $this.prop('checked', !status);
                    alert('Une erreur est survenue');
                }
            });
        } else {
            $this.prop('checked', !status);
        }
    });

    // Delete with SweetAlert2 (kept for better UX)
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();

        const $this = $(this);
        const type = $this.data('type');
        const id = $this.data('id');
        const title = $this.data('title');

        Swal.fire({
            title: 'Confirmer la suppression',
            text: `Voulez-vous supprimer "${title}" ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.slides.quick-delete") }}',
                    method: 'POST',
                    data: {
                        type: type,
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $this.closest('tr').fadeOut(300, function() {
                                $(this).remove();
                                Swal.fire('Supprimé!', response.message, 'success');

                                if ($('tbody tr').length === 0) {
                                    setTimeout(() => location.reload(), 500);
                                }
                            });
                        } else {
                            Swal.fire('Erreur', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue', 'error');
                    }
                });
            }
        });
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.premium-alert').fadeOut(300);
    }, 3000);
});
</script>
@endpush
