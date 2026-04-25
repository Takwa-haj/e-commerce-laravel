@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Palettes de Couleurs</h3>
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
                    <div class="text-tiny">Palettes de Couleurs</div>
                </li>
            </ul>
        </div>

        <!-- Alerts -->
        @if (Session::has('status'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>
                {{ Session::get('status') }}
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ Session::get('error') }}
            </div>
        @endif

        <!-- Header Actions -->
        <div class="wg-box mb-4">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.color-palettes') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher une palette..." name="query"
                                value="{{ request('query') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.color-palette.add') }}">
                    <i class="icon-plus"></i> Nouvelle Palette
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $palettes->total() }}</h3>
                        <p>Total Palettes</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $palettes->where('is_active', true)->count() }}</h3>
                        <p>Active</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $palettes->where('type', 'custom')->count() }}</h3>
                        <p>Personnalisées</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $palettes->where('type', 'seasonal')->count() }}</h3>
                        <p>Saisonnières</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== COLOR PALETTES GRID ===== -->
        <div class="wg-box">
            <h5 class="mb-4">
                <i class="fas fa-palette me-2" style="color: var(--primary);"></i>
                Choisir une Palette de Couleurs
            </h5>

            <div class="row">
                @forelse ($palettes as $palette)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="palette-card {{ $palette->is_active ? 'active' : '' }}"
                         style="border: 2px solid {{ $palette->is_active ? $palette->colors['primary'] : '#e5e7eb' }};
                                border-radius: 16px;
                                overflow: hidden;
                                background: white;
                                transition: all 0.3s ease;
                                {{ $palette->is_active ? 'box-shadow: 0 8px 25px rgba(0,0,0,0.12);' : '' }}
                                {{ $palette->is_active ? 'transform: translateY(-2px);' : '' }}">

                        <!-- Color Preview Strip -->
                        <div class="palette-strip" style="display: flex; height: 70px;">
                            <div style="flex: 1; background-color: {{ $palette->colors['primary'] ?? '#ccc' }};"></div>
                            <div style="flex: 1; background-color: {{ $palette->colors['secondary'] ?? '#ccc' }};"></div>
                            <div style="flex: 1; background-color: {{ $palette->colors['accent'] ?? '#ccc' }};"></div>
                            <div style="flex: 1; background-color: {{ $palette->colors['text_primary'] ?? '#ccc' }};"></div>
                            <div style="flex: 1; background-color: {{ $palette->colors['background'] ?? '#ccc' }}; border-left: 2px solid rgba(0,0,0,0.1);"></div>
                            <div style="flex: 1; background-color: {{ $palette->colors['gold_highlight'] ?? '#ccc' }};"></div>
                        </div>

                        <!-- Active Badge -->
                        @if($palette->is_active)
                        <div class="active-badge" style="background: {{ $palette->colors['primary'] ?? '#4F46E5' }};">
                            <i class="fas fa-star me-1"></i> Palette Active
                        </div>
                        @endif

                        <!-- Card Body -->
                        <div class="p-4">
                            <!-- Name & Type -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1" style="font-weight: 600; font-size: 16px;">
                                        {{ $palette->name }}
                                    </h5>
                                    <span class="badge" style="
                                        background: {{ $palette->type == 'default' ? '#3B82F6' : ($palette->type == 'custom' ? '#8B5CF6' : '#F59E0B') }};
                                        color: white;
                                        font-size: 11px;
                                        padding: 4px 10px;
                                    ">
                                        {{ $palette->type == 'default' ? 'Défaut' : ($palette->type == 'custom' ? 'Personnalisé' : 'Saisonnier') }}
                                    </span>
                                </div>

                                @if($palette->is_active)
                                    <span class="badge bg-success" style="font-size: 11px;">
                                        <i class="fas fa-check me-1"></i>Actif
                                    </span>
                                @endif
                            </div>

                            <!-- Color Dots -->
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Couleurs clés:</small>
                                <div class="d-flex gap-2 align-items-center">
                                    @foreach(['primary', 'secondary', 'accent', 'text_primary', 'gold_highlight', 'success', 'warning', 'error'] as $colorKey)
                                        @if(isset($palette->colors[$colorKey]))
                                        <div class="color-dot"
                                             style="background-color: {{ $palette->colors[$colorKey] }};
                                                    width: 24px;
                                                    height: 24px;
                                                    border-radius: 50%;
                                                    border: 2px solid white;
                                                    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
                                                    cursor: pointer;
                                                    transition: transform 0.2s;"
                                             title="{{ ucwords(str_replace('_', ' ', $colorKey)) }}: {{ $palette->colors[$colorKey] }}"
                                             onmouseover="this.style.transform='scale(1.3)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Created Date -->
                            <div class="text-muted mb-3" style="font-size: 12px;">
                                <i class="far fa-clock me-1"></i>
                                Créée le {{ $palette->created_at->format('d/m/Y') }}
                                @if($palette->updated_at != $palette->created_at)
                                    <span class="ms-2">
                                        <i class="far fa-edit me-1"></i>
                                        Modifiée le {{ $palette->updated_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                @if(!$palette->is_active)
                                    <form action="{{ route('admin.color-palette.activate', $palette->id) }}"
                                          method="POST" class="flex-grow-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-activate w-100"
                                                style="background: {{ $palette->colors['primary'] ?? '#4F46E5' }};
                                                       color: white;
                                                       border: none;
                                                       padding: 10px 16px;
                                                       border-radius: 8px;
                                                       font-size: 14px;
                                                       font-weight: 500;
                                                       cursor: pointer;
                                                       transition: all 0.3s ease;"
                                                onmouseover="this.style.opacity='0.85'; this.style.transform='translateY(-1px)';"
                                                onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
                                            <i class="fas fa-play-circle me-2"></i>Activer cette palette
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-active w-100 flex-grow-1" disabled
                                            style="background: #10B981;
                                                   color: white;
                                                   border: none;
                                                   padding: 10px 16px;
                                                   border-radius: 8px;
                                                   font-size: 14px;
                                                   font-weight: 500;
                                                   opacity: 0.85;
                                                   cursor: not-allowed;">
                                        <i class="fas fa-check-circle me-2"></i>Palette active
                                    </button>
                                @endif

                                <a href="{{ route('admin.color-palette.edit', $palette->id) }}"
                                   class="btn-icon-action"
                                   style="background: #f3f4f6;
                                          color: #374151;
                                          border: 1px solid #d1d5db;
                                          padding: 10px 14px;
                                          border-radius: 8px;
                                          text-decoration: none;
                                          transition: all 0.3s ease;
                                          display: flex;
                                          align-items: center;
                                          justify-content: center;"
                                   title="Modifier la palette"
                                   onmouseover="this.style.background='#e5e7eb';"
                                   onmouseout="this.style.background='#f3f4f6';">
                                    <i class="icon-edit-3"></i>
                                </a>

                                @if(!$palette->is_active)
                                <form action="{{ route('admin.color-palette.delete', $palette->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la palette \'{{ $palette->name }}\' ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-icon-action"
                                            style="background: #fef2f2;
                                                   color: #dc2626;
                                                   border: 1px solid #fecaca;
                                                   padding: 10px 14px;
                                                   border-radius: 8px;
                                                   cursor: pointer;
                                                   transition: all 0.3s ease;
                                                   display: flex;
                                                   align-items: center;
                                                   justify-content: center;"
                                            title="Supprimer la palette"
                                            onmouseover="this.style.background='#fee2e2';"
                                            onmouseout="this.style.background='#fef2f2';">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-palette fa-5x" style="color: #d1d5db;"></i>
                        </div>
                        <h4 style="color: #6b7280;">Aucune palette trouvée</h4>
                        <p class="text-muted mb-4">Commencez par créer votre première palette de couleurs pour votre site</p>
                        <a href="{{ route('admin.color-palette.add') }}" class="tf-button style-1 w208">
                            <i class="icon-plus me-2"></i>Créer ma première palette
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($palettes->hasPages())
            <div class="divider mt-4"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination mt-3">
                {{ $palettes->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
        <!-- ===== END COLOR PALETTES GRID ===== -->

    </div>
</div>
@endsection

@push('styles')
<style>
/* Statistics Cards */
.stat-card {
    border-radius: 12px;
    padding: 20px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-info h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.stat-info p {
    margin: 0;
    font-size: 13px;
    opacity: 0.9;
}

/* Palette Cards */
.palette-card {
    position: relative;
    transition: all 0.3s ease;
}

.palette-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}

.palette-card.active {
    border-width: 3px !important;
}

.palette-strip {
    position: relative;
}

.active-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 2;
}

.color-dot {
    transition: all 0.2s ease;
}

.color-dot:hover {
    transform: scale(1.3);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
    z-index: 10;
}

/* Buttons */
.btn-activate,
.btn-active,
.btn-icon-action {
    transition: all 0.2s ease;
}

.btn-activate:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-icon-action:hover {
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        padding: 15px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }

    .stat-info h3 {
        font-size: 22px;
    }

    .palette-card .p-4 {
        padding: 15px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Palette card hover effects
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.palette-card').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.1)';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.boxShadow = 'none';
            }
        });
    });

    // Show success message animation
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(function() {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    }
});
</script>
@endpush
