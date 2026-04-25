
@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tailles</h3>
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
                    <div class="text-tiny">Tailles</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.search.sizes') }}" method="POST">
                        @csrf
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher par nom, code ou description..." class="" name="query"
                                value="{{ request()->input('query') }}" required>
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.size.add') }}">
                    <i class="icon-plus"></i>Ajouter
                </a>
            </div>

            @if(request()->has('query'))
            <div class="alert alert-info mb-3">
                Résultats de recherche pour : <strong>{{ request()->input('query') }}</strong>
                <a href="{{ route('admin.sizes') }}" class="float-end">Voir tous</a>
            </div>
            @endif

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if (Session::has('status'))
                    <p class="alert alert-success">{{ session::get('status') }}</p>
                    @endif

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Produits</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sizes->count() > 0)
                                @foreach ($sizes as $size)
                                <tr>
                                    <td>{{ $size->id }}</td>
                                    <td class="pname">
                                        <div class="name">
                                            <a href="{{ route('admin.size.edit', ['id' => $size->id]) }}" class="body-title-2">{{ $size->name }}</a>
                                        </div>
                                    </td>
                                    <td>{{ $size->code ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($size->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('admin.products') }}?sizes={{ $size->id }}" target="_blank">
                                            {{ $size->products_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.size.edit', ['id' => $size->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.size.delete', ['id' => $size->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item text-danger delete"
                                                        style="border: none; background: none; cursor: pointer; padding: 0;"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette taille ?')">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Aucune taille trouvée.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $sizes->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function(){
    $('.delete').on('click', function(e){
        e.preventDefault();
        var deleteUrl = $(this).closest('form').attr('action');

        Swal.fire({
            title: "Êtes-vous sûr ?",
            text: "Vous ne pourrez pas annuler cette action !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a dynamic form to submit
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                // Add CSRF token
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method spoofing for DELETE
                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
