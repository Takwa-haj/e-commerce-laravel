@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Articles du Blog</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Tableau de Bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Blog</div>
                </li>
            </ul>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Articles -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="icon-file-text"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Articles</span>
                        <span class="stat-value">{{ $totalArticles ?? $blogs->total() }}</span>
                        <span class="stat-trend positive">
                            <i class="icon-arrow-up"></i> {{ $publishedArticles ?? 0 }} publiés
                        </span>
                    </div>
                </div>
            </div>

            <!-- Published Articles -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        <i class="icon-eye"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Articles Publiés</span>
                        <span class="stat-value">{{ $publishedArticles ?? 0 }}</span>
                        <span class="stat-trend positive">
                            <i class="icon-check-circle"></i> Visible sur le site
                        </span>
                    </div>
                </div>
            </div>

            <!-- Draft Articles -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                        <i class="icon-edit-3"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Brouillons</span>
                        <span class="stat-value">{{ $draftArticles ?? 0 }}</span>
                        <span class="stat-trend neutral">
                            <i class="icon-clock"></i> En attente de publication
                        </span>
                    </div>
                </div>
            </div>

            <!-- Featured Articles -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #D4AF37 0%, #AA8C2F 100%);">
                        <i class="icon-star"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Articles Vedettes</span>
                        <span class="stat-value">{{ $featuredArticles ?? 0 }}</span>
                        <span class="stat-trend positive">
                            <i class="icon-star"></i> Mis en avant
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
                        <i class="icon-eye"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Vues Totales</span>
                        <span class="stat-value">{{ number_format($totalViews ?? 0) }}</span>
                        <span class="stat-trend positive">
                            <i class="icon-trending-up"></i> Engagement
                        </span>
                    </div>
                </div>
            </div>

            <!-- Engagement Stats -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);">
                        <i class="icon-heart"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Engagement</span>
                        <span class="stat-value">{{ number_format($totalEngagement ?? 0) }}</span>
                        <span class="stat-trend">
                            <i class="icon-thumbs-up"></i> Likes & Partages
                        </span>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                        <i class="icon-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Ce Mois-ci</span>
                        <span class="stat-value">{{ $thisMonthArticles ?? 0 }}</span>
                        <span class="stat-trend positive">
                            <i class="icon-arrow-up"></i> Nouveaux articles
                        </span>
                    </div>
                </div>
            </div>

            <!-- Avg Views per Article -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);">
                        <i class="icon-bar-chart-2"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Moy. Vues/Article</span>
                        <span class="stat-value">{{ $avgViewsPerArticle ?? 0 }}</span>
                        <span class="stat-trend">
                            <i class="icon-activity"></i> Performance
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.blogs') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher un article..." class="" name="query"
                                value="{{ request()->input('query') ?? '' }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.blog.add') }}">
                    <i class="icon-plus"></i>Ajouter
                </a>
            </div>

            @if(request()->filled('query'))
            <div class="alert alert-info mb-3">
                Résultats de recherche pour : <strong>{{ request()->input('query') }}</strong>
                <a href="{{ route('admin.blogs') }}" class="float-end">Voir tous</a>
            </div>
            @endif

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if (Session::has('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session::get('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th>Auteur</th>
                                <th>Statut</th>
                                <th>Vues</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($blogs->count() > 0)
                                @foreach ($blogs as $blog)
                                <tr>
                                    <td>{{$blog->id}}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ $blog->thumbnail_url }}" alt="{{$blog->title}}"
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="body-title-2">
                                                {{ Str::limit($blog->title, 50) }}
                                            </a>
                                            @if($blog->is_featured)
                                            <span class="badge bg-warning text-dark ms-2">Vedette</span>
                                            @endif
                                            <div class="text-tiny text-muted mt-1">
                                                {{ Str::limit($blog->excerpt, 80) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-tiny">{{ $blog->user->name }}</div>
                                    </td>
                                    <td>
                                        @if($blog->is_published)
                                        <span class="badge bg-success">Publié</span>
                                        @else
                                        <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="icon-eye text-muted"></i>
                                            <span class="text-tiny">{{ $blog->views }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-tiny">{{ $blog->published_at ? $blog->published_at->format('d/m/Y') : 'Non publié' }}</div>
                                    </td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}" title="Éditer">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>

                                            @if($blog->is_featured)
                                            <a href="{{ route('admin.blog.toggle.featured', $blog->id) }}" title="Retirer des vedettes">
                                                <div class="item warning">
                                                    <i class="icon-star"></i>
                                                </div>
                                            </a>
                                            @else
                                            <a href="{{ route('admin.blog.toggle.featured', $blog->id) }}" title="Mettre en vedette">
                                                <div class="item text-muted">
                                                    <i class="icon-star"></i>
                                                </div>
                                            </a>
                                            @endif

                                            @if($blog->is_published)
                                            <a href="{{ route('admin.blog.toggle.published', $blog->id) }}" title="Dépublier">
                                                <div class="item secondary">
                                                    <i class="icon-eye-off"></i>
                                                </div>
                                            </a>
                                            @else
                                            <a href="{{ route('admin.blog.toggle.published', $blog->id) }}" title="Publier">
                                                <div class="item success">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </a>
                                            @endif

                                            <form action="{{ route('admin.blog.delete', $blog->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item text-danger delete"
                                                        style="border: none; background: none; cursor: pointer; padding: 0;"
                                                        title="Supprimer">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        @if(request()->filled('query'))
                                            Aucun article trouvé pour "{{ request()->input('query') }}".
                                        @else
                                            Aucun article disponible.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern CSS for Statistics Cards -->
<style>
.modern-stat-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    transition: all 0.3s ease;
}

.modern-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
}

.stat-content {
    flex: 1;
}

.stat-label {
    display: block;
    font-size: 0.9rem;
    color: #6B7280;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 5px;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    color: #6B7280;
}

.stat-trend.positive {
    color: #10B981;
}

.stat-trend.neutral {
    color: #F59E0B;
}

.stat-trend i {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .modern-stat-card {
        padding: 18px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }
}
</style>

@endsection

@push('scripts')
<script>
$(function(){
    $('.delete').on('click', function(e){
        e.preventDefault();
        var deleteUrl = $(this).closest('form').attr('action');

        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Cet article sera définitivement supprimé!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
