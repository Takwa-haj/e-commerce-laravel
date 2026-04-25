@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Top Banners</h3>
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
                    <div class="text-tiny">Top Banners</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            @if (Session::has('status'))
                <div class="alert alert-success mb-4">{{ Session::get('status') }}</div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger mb-4">{{ Session::get('error') }}</div>
            @endif

            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.top-banners.index') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher des bannières..." class="" name="search"
                                value="{{ request('search') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.top-banners.create') }}">
                    <i class="icon-plus"></i>Ajouter une Bannière
                </a>
            </div>

            <div class="wg-table table-all-user">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Message</th>
                            <th>Seuil Livraison</th>
                            <th>Réseaux Sociaux</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td>
                                <div class="font-medium">{{ Str::limit($banner->message, 50) }}</div>
                                <div class="text-tiny text-muted">
                                    Créé le: {{ $banner->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                @if($banner->free_shipping_threshold)
                                    <span class="badge bg-info">€{{ number_format($banner->free_shipping_threshold, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="social-icons-mini">
                                    @if($banner->facebook_url) <i class="fab fa-facebook-f text-primary me-1" title="Facebook"></i> @endif
                                    @if($banner->instagram_url) <i class="fab fa-instagram text-danger me-1" title="Instagram"></i> @endif
                                    @if($banner->twitter_url) <i class="fab fa-twitter text-info me-1" title="Twitter"></i> @endif
                                    @if($banner->pinterest_url) <i class="fab fa-pinterest text-danger me-1" title="Pinterest"></i> @endif
                                    @if($banner->youtube_url) <i class="fab fa-youtube text-danger me-1" title="YouTube"></i> @endif
                                    @if($banner->tiktok_url) <i class="fab fa-tiktok me-1" title="TikTok"></i> @endif
                                </div>
                            </td>
                            <td>
                                @if($banner->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="list-icon-function">
                                    <form action="{{ route('admin.top-banners.toggle-active', $banner->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="item toggle-status"
                                                style="background: none; border: none; padding: 0; cursor: pointer;"
                                                title="{{ $banner->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="icon-power-off" style="color: {{ $banner->is_active ? '#28a745' : '#dc3545' }}; font-size: 18px;"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.top-banners.edit', $banner->id) }}" title="Modifier">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>

                                    <form action="{{ route('admin.top-banners.destroy', $banner->id) }}"
                                          method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete-btn"
                                                style="background: none; border: none; padding: 0; cursor: pointer;"
                                                title="Supprimer">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Aucune bannière trouvée.
                                <a href="{{ route('admin.top-banners.create') }}">Ajoutez votre première bannière</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $banners->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.social-icons-mini i {
    font-size: 16px;
    margin: 0 3px;
}

.list-icon-function {
    display: flex;
    gap: 10px;
    align-items: center;
}

.list-icon-function .item {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.list-icon-function .item:hover {
    background-color: #f0f0f0;
}

.list-icon-function .edit:hover {
    color: #007bff;
}

.list-icon-function .text-danger:hover {
    color: #dc3545 !important;
}

.toggle-status:hover {
    opacity: 0.8;
}

.alert {
    padding: 15px 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.badge {
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 500;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');

            Swal.fire({
                title: 'Supprimer la bannière?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
