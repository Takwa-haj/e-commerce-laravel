
@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Profil Utilisateur</h3>
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

        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="wg-box">
                    <!-- Carte de Profil Utilisateur -->
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('uploads/avatars/' . $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="user-avatar-large">
                        @else
                            <div class="avatar-placeholder-large">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $user->name }}</h4>
                        <span class="badge {{ $user->utype == 'ADM' ? 'bg-success' : 'bg-info' }}">
                            {{ $user->utype == 'ADM' ? 'Administrateur' : 'Client' }}
                        </span>
                    </div>

                    <!-- Informations Utilisateur -->
                    <div class="user-info mb-4">
                        <div class="info-item">
                            <span class="label">Email:</span>
                            <span class="value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Téléphone:</span>
                            <span class="value">{{ $user->numero ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Inscrit le:</span>
                            <span class="value">{{ $user->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Dernière mise à jour:</span>
                            <span class="value">{{ $user->updated_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email vérifié:</span>
                            <span class="value">
                                @if($user->email_verified_at)
                                    {{ $user->email_verified_at->format('d M Y') }}
                                @else
                                    <span class="text-danger">Non vérifié</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Boutons d'Action -->
                    <div class="action-buttons">
                        <a href="{{ route('admin.user-edit', $user->id) }}" class="tf-button w-full mb-2">
                            <i class="icon-edit"></i> Modifier l'Utilisateur
                        </a>

                        @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="tf-button w-full btn-delete delete-user-btn" style="background-color: #dc3545;">
                                    <i class="icon-trash-2"></i> Supprimer l'Utilisateur
                                </button>
                            </form>
                        @else
                            <button class="tf-button w-full" disabled style="background-color: #6c757d;">
                                <i class="icon-user"></i> Utilisateur actuel
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Statistiques Rapides -->
                <div class="wg-box mt-4">
                    <h5 class="mb-3">Statistiques Utilisateur</h5>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ $user->orders_count ?? 0 }}</div>
                            <div class="stat-label">Commandes totales</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ config('settings.currency_symbol', '$') }}{{ number_format($total_spent ?? 0, 2) }}</div>
                            <div class="stat-label">Total dépensé</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $reviews_count ?? 0 }}</div>
                            <div class="stat-label">Avis</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Commandes Récentes -->
                <div class="wg-box mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4>Commandes Récentes</h4>
                        <a href="{{ route('admin.orders') }}?user={{ $user->id }}" class="text-primary">
                            Voir toutes les commandes
                        </a>
                    </div>

                    @if($orders->count() > 0)
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
                                    @foreach($orders as $order)
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

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="mt-3">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="icon-shopping-cart" style="font-size: 48px; color: #ddd;"></i>
                            <p class="mt-3 text-muted">Aucune commande trouvée pour cet utilisateur</p>
                        </div>
                    @endif
                </div>

                <!-- Avis de l'Utilisateur -->
                <div class="wg-box">
                    <div class="flex items-center justify-between mb-4">
                        <h4>Avis de l'Utilisateur</h4>
                        <span class="badge bg-info">{{ $reviews_count ?? 0 }} avis</span>
                    </div>

                    @if($reviews && $reviews->count() > 0)
                        <div class="reviews-list">
                            @foreach($reviews->take(5) as $review)
                                <div class="review-item mb-3 p-3 border rounded">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <strong>{{ $review->product->name ?? 'Produit' }}</strong>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="icon-star text-warning"></i>
                                                    @else
                                                        <i class="icon-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-muted text-tiny">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                    <p class="mb-0">{{ Str::limit($review->comment, 150) }}</p>
                                    <span class="badge {{ $review->approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $review->approved ? 'Approuvé' : 'En attente' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if($reviews->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.reviews') }}?user={{ $user->id }}" class="text-primary">
                                    Voir tous les avis
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="icon-message-square" style="font-size: 48px; color: #ddd;"></i>
                            <p class="mt-3 text-muted">Aucun avis trouvé pour cet utilisateur</p>
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
.user-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #f0f0f0;
    object-fit: cover;
}

.avatar-placeholder-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(45deg, #4CAF50, #2196F3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    border: 3px solid #f0f0f0;
    margin: 0 auto;
}

.user-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-weight: 600;
    color: #666;
}

.info-item .value {
    color: #333;
}

.action-buttons .tf-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    text-align: center;
}

.stat-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    color: #4CAF50;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.review-item {
    background: #f8f9fa;
}

.rating {
    color: #ffc107;
}

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

.table th, .table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 3px 8px;
    font-size: 12px;
}

.alert {
    padding: 12px 20px;
    border-radius: 4px;
    margin-bottom: 20px;
    border: 1px solid transparent;
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

.text-primary {
    color: #007bff !important;
    text-decoration: none;
}

.text-primary:hover {
    text-decoration: underline;
}

.text-danger {
    color: #dc3545;
}

.text-muted {
    color: #6c757d !important;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Confirmation de suppression d'utilisateur
    $('.delete-user-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const userName = "{{ $user->name }}";

        Swal.fire({
            title: 'Êtes-vous sûr?',
            html: `Vous êtes sur le point de supprimer l'utilisateur <strong>${userName}</strong>. Cette action est irréversible!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
