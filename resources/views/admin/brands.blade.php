@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
     <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Marques</h3>
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
                    <div class="text-tiny">Marques</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.search.brands') }}" method="POST">
                        @csrf
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher par nom ou slug..." class="" name="query"
                                value="{{ request()->input('query') ?? '' }}" required>
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.brand.add') }}">
                    <i class="icon-plus"></i>Ajouter
                </a>
            </div>

            @if(request()->filled('query'))
            <div class="alert alert-info mb-3">
                Résultats de recherche pour : <strong>{{ request()->input('query') }}</strong>
                <a href="{{ route('admin.brands') }}" class="float-end">Voir tous</a>
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
                                <th>Slug</th>
                                <th>Produits</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($brands->count() > 0)
                                @foreach ($brands as $brand)
                                <tr>
                                    <td>{{$brand->id}}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{asset('uploads/brands')}}/{{$brand->image}}" alt="{{$brand->name}}" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('admin.brand.edit',['id'=>$brand->id]) }}" class="body-title-2">{{$brand->name }}</a>
                                        </div>
                                    </td>
                                    <td>{{$brand->slug}}</td>
                                    <td>
                                        <a href="{{ route('admin.products') }}?brands={{ $brand->id }}" target="_blank">
                                            {{ $brand->products_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.brand.edit',['id'=>$brand->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.brand.delete',['id' =>$brand->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item text-danger delete" style="border: none; background: none; cursor: pointer; padding: 0;">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        @if(request()->filled('query'))
                                            Aucune marque trouvée pour "{{ request()->input('query') }}".
                                        @else
                                            Aucune marque disponible.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $brands->links('pagination::bootstrap-5') }}
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
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
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
