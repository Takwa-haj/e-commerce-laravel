
@extends('layouts.admin')
@section('content')
<style>

</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tous les Produits</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Tableau de bord</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Tous les Produits</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.products') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Rechercher par nom, SKU, catégorie ou marque..."
                                   class="" name="query" value="{{ request()->input('query') ?? '' }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}">
                    <i class="icon-plus"></i>Ajouter
                </a>
            </div>

            @if(request()->filled('query'))
            <div class="alert alert-info mb-3">
                Résultats de recherche pour : <strong>{{ request()->input('query') }}</strong>
                <a href="{{ route('admin.products') }}" class="float-end">Voir tous</a>
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
                                <th>Prix</th>
                                <th>Prix de vente</th>
                                <th>SKU</th>
                                <th>Catégorie</th>
                                <th>Marque</th>
                                <th>En vedette</th>
                                <th>Stock</th>
                                <th>Quantité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($products->count() > 0)
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}"
                                                 alt="{{ $product->name}}" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('admin.product.edit',['id'=>$product->id]) }}"
                                               class="body-title-2">{{ $product->name }}</a>
                                            <div class="text-tiny mt-3">{{ $product->slug }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $product->regular_price }}€</td>
                                    <td>
                                        @if($product->sale_price && $product->sale_price < $product->regular_price)
                                            <span class="text-decoration-line-through text-muted">{{ $product->regular_price }}€</span>
                                            <br>
                                            <span class="tf-color-1 fw-bold">{{ $product->sale_price }}€</span>
                                            <br>
                                            <small class="badge bg-danger">
                                                -{{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}%
                                            </small>
                                        @else
                                            <span class="fw-bold">{{ $product->regular_price }}€</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->SKU }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>{{ $product->featured == 0 ? "non" : "oui" }}</td>
                                    <td>{{ $product->stock_status }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.product.edit',['id'=>$product->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.product.delete',['id'=>$product->id]) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item text-danger delete"
                                                        style="border: none; background: none; cursor: pointer; padding: 0;"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        @if(request()->filled('query'))
                                            Aucun produit trouvé pour "{{ request()->input('query') }}".
                                        @else
                                            Aucun produit disponible.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
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
<style>
    
</style>
@endpush
