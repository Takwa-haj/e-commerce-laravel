
@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Détails de l'Utilisateur</h3>
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
                    <a href="{{ route('admin.users') }}">
                        <div class="text-tiny">Utilisateurs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">{{ $user->name }}</div>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="wg-box">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/avatar') }}" alt="{{ $user->name }}"
                             style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid #f0f0f0;">
                        <h4 class="mt-3">{{ $user->name }}</h4>
                        <span class="badge {{ $user->utype == 'ADM' ? 'bg-success' : 'bg-info' }}">
                            {{ $user->utype == 'ADM' ? 'Administrateur' : 'Client' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.user.update-route') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $user->id }}">

                        <div class="mb-3">
                            <label class="body-title mb-2">Nom Complet <span class="tf-color-1">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="body-title mb-2">Adresse Email <span class="tf-color-1">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="body-title mb-2">Numéro de Téléphone <span class="tf-color-1">*</span></label>
                            <input type="text" class="form-control" name="numero" value="{{ $user->numero }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="body-title mb-2">Type d'Utilisateur <span class="tf-color-1">*</span></label>
                            <select class="form-control" name="utype" required>
                                <option value="USR" {{ $user->utype == 'USR' ? 'selected' : '' }}>Utilisateur/Client</option>
                                <option value="ADM" {{ $user->utype == 'ADM' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </div>

                        <button type="submit" class="tf-button w-full">Mettre à jour l'Utilisateur</button>
                    </form>

                    <div class="mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Informations du Compte</h6>
                                <p class="text-muted mb-1"><small>Inscrit le:</small> {{ $user->created_at->format('d M Y, h:i A') }}</p>
                                <p class="text-muted mb-1"><small>Dernière mise à jour:</small> {{ $user->updated_at->format('d M Y, h:i A') }}</p>
                                <p class="text-muted mb-0"><small>Email vérifié:</small>
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Non vérifié' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="wg-box">
                    <h4 class="mb-4">Commandes de l'Utilisateur ({{ $user->orders->count() }})</h4>

                    @if($user->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Commande</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Total</th>
                                    <th>Articles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($order->status == 'delivered') bg-success
                                            @elseif($order->status == 'ordered') bg-warning
                                            @elseif($order->status == 'canceled') bg-danger
                                            @else bg-secondary @endif">
                                            @if($order->status == 'delivered') Livrée
                                            @elseif($order->status == 'ordered') Commandée
                                            @elseif($order->status == 'canceled') Annulée
                                            @else {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ config('settings.currency_symbol', '$') }}{{ number_format($order->total, 2) }}</td>
                                    <td>{{ $order->orderItems->count() }}</td>
                                    <td>
                                        <a href="{{ route('admin.order.details', $order->id) }}" class="btn btn-sm btn-info">
                                            <i class="icon-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="icon-shopping-cart" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-3 text-muted">Aucune commande trouvée pour cet utilisateur</p>
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
.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.bg-success { background-color: #28a745; color: white; }
.bg-info { background-color: #17a2b8; color: white; }
.bg-warning { background-color: #ffc107; color: black; }
.bg-danger { background-color: #dc3545; color: white; }
.bg-secondary { background-color: #6c757d; color: white; }
</style>
@endpush
