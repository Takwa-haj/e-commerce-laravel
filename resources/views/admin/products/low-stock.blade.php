@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Stock Faible <span class="text-muted">(< 10 unités)</span></h3>
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
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Produits</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Stock Faible</div>
                </li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="wg-box">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <i class="icon-alert"></i>
                        <strong>Attention :</strong> Les produits avec un stock inférieur à 10 unités nécessitent un réapprovisionnement.
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs mb-4" id="stockTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">
                        Produits sans variantes
                        @if($lowStockProducts->total() > 0)
                            <span class="badge bg-warning ms-2">{{ $lowStockProducts->total() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab" aria-controls="variants" aria-selected="false">
                        Variantes
                        @if($lowStockVariants->total() > 0)
                            <span class="badge bg-warning ms-2">{{ $lowStockVariants->total() }}</span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="stockTabsContent">
                {{-- Tab Produits sans variantes --}}
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <h4 class="mb-3">Produits avec stock faible</h4>
                    @if($lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Produit</th>
                                        <th>SKU</th>
                                        <th>Catégorie</th>
                                        <th>Stock actuel</th>
                                        <th>Seuil</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.edit', $product->id) }}" class="fw-bold">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $product->SKU }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark p-2">{{ $product->quantity }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">10</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                   class="btn btn-sm btn-primary" title="Modifier">
                                                    <i class="icon-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#restockModal{{ $product->id }}"
                                                        title="Réapprovisionner">
                                                    <i class="icon-refresh"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de réapprovisionnement --}}
                                            <div class="modal fade" id="restockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Réapprovisionner : {{ $product->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Stock actuel</label>
                                                                    <input type="number" class="form-control" value="{{ $product->quantity }}" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nouvelle quantité</label>
                                                                    <input type="number" name="quantity" class="form-control" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $lowStockProducts->links() }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="icon-check"></i> Aucun produit avec stock faible trouvé.
                        </div>
                    @endif
                </div>

                {{-- Tab Variantes --}}
                <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                    <h4 class="mb-3">Variantes avec stock faible (< 5 unités)</h4>
                    @if($lowStockVariants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Variante</th>
                                        <th>SKU</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockVariants as $variant)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.product.edit', $variant->product_id) }}">
                                                {{ $variant->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($variant->color)
                                                    <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background-color: {{ $variant->color->hex_code }}; border: 1px solid #ddd;"></span>
                                                @endif
                                                <span>{{ $variant->display_name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark p-2">{{ $variant->quantity }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.edit', $variant->product_id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="icon-edit"></i> Gérer
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $lowStockVariants->links() }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="icon-check"></i> Aucune variante avec stock faible trouvée.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge.bg-warning {
        color: #000 !important;
        font-weight: 500;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
