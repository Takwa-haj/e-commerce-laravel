@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Bannières Boutique</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Tableau de bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Bannières Boutique</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher ici..." class="" name="search"
                                tabindex="2" value="{{ request('search') }}" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.shop-banner.add') }}">
                    <i class="icon-plus"></i>Ajouter une Nouvelle Bannière
                </a>
            </div>

            <div class="wg-table table-all-user">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Texte du Bouton</th>
                            <th>Ordre de Tri</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/shop-banners') }}/{{ $banner->image }}"
                                         alt="{{ $banner->alt_text }}"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <strong>{{ $banner->title }}</strong>
                                @if($banner->subtitle)
                                <div class="text-tiny text-secondary">{{ $banner->subtitle }}</div>
                                @endif
                            </td>
                            <td>{{ $banner->button_text }}</td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>
                                <span class="badge bg-{{ $banner->is_active ? 'success' : 'danger' }}">
                                    {{ $banner->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.shop-banner.edit', ['id' => $banner->id]) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form id="delete-shop-banner-form-{{ $banner->id }}"
                                          action="{{ route('admin.shop-banner.delete', ['id' => $banner->id]) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete-shop-banner-btn"
                                                style="background: none; border: none; padding: 0; cursor: pointer;">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-shop-banner-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var form = this.closest('form');
            var bannerId = form.id.split('-')[4];

            Swal.fire({
                title: 'Delete Shop Banner?',
                text: "This banner will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545'
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
