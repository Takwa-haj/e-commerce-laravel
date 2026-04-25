@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<main class="py-8">
    <div class="container mx-auto px-4">
        <!-- En-tête de Bienvenue - Modern Header -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-gray-600 text-sm">Bienvenue sur votre tableau de bord</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 text-xs">
                        <i class="fas fa-crown text-indigo-500 mr-1.5 text-xs"></i>
                        <span class="font-medium text-indigo-700">Membre Premium</span>
                    </span>
                    <span class="text-xs text-gray-500 hidden md:inline">
                        Dernière connexion :
                        @if(Auth::user()->last_login_at)
                            {{ Auth::user()->last_login_at->diffForHumans() }}
                        @else
                            Première connexion
                        @endif
                    </span>
                </div>
            </div>
            <div class="h-1 w-20 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne de Gauche - Statistiques Rapides -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cartes de Statistiques - Minimalist -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Carte Commandes -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                                <i class="fas fa-box-open text-lg text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Commandes</p>
                                <p class="text-xl font-bold text-gray-900">{{ $user->orders->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                @php
                                    $pendingOrders = $user->orders()->whereIn('status', ['ordered', 'confirmed'])->count();
                                @endphp
                                <span class="font-medium text-blue-600">{{ $pendingOrders }}</span> en attente
                            </p>
                        </div>
                    </div>

                    <!-- Carte Favoris -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
                                <i class="fas fa-heart text-lg text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Favoris</p>
                                <p class="text-xl font-bold text-gray-900">{{ $user->wishlist->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('wishlist.index') }}" class="text-xs text-red-600 hover:text-red-500 font-medium flex items-center">
                                Voir ma liste
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Carte Avis -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                                <i class="fas fa-star text-lg text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Avis</p>
                                <p class="text-xl font-bold text-gray-900">{{ $user->reviews->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('user.reviews') }}" class="text-xs text-green-600 hover:text-green-500 font-medium flex items-center">
                                Voir mes avis
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Commandes Récentes - Clean Design -->
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-history text-indigo-500 mr-2.5"></i>
                                <h2 class="text-base font-semibold text-gray-900">Dernières commandes</h2>
                            </div>
                            <a href="{{ route('user.orders') }}"
                               class="text-sm text-indigo-600 hover:text-indigo-500 font-medium flex items-center">
                                Voir tout
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                            <div class="px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2.5 mb-1">
                                            <p class="font-medium text-gray-900 text-sm">
                                                Commande #{{ $order->order_number }}
                                            </p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($order->status == 'delivered') bg-green-50 text-green-700 border border-green-100
                                                @elseif($order->status == 'confirmed') bg-blue-50 text-blue-700 border border-blue-100
                                                @elseif($order->status == 'canceled') bg-red-50 text-red-700 border border-red-100
                                                @elseif($order->status == 'shipped') bg-purple-50 text-purple-700 border border-purple-100
                                                @else bg-gray-50 text-gray-700 border border-gray-100
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $order->items->count() }} article(s)
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            {{ number_format($order->total, 2) }} €
                                        </p>
                                        <div class="mt-2 flex space-x-3 justify-end">
                                            <a href="{{ route('user.order.details', $order->id) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-500 font-medium flex items-center">
                                                <i class="fas fa-eye mr-1 text-xs"></i>
                                                Détails
                                            </a>
                                            @if($order->status == 'ordered')
                                                <form action="{{ route('user.order.cancel') }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <button type="submit"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')"
                                                            class="text-xs text-red-600 hover:text-red-500 font-medium flex items-center">
                                                        <i class="fas fa-times mr-1 text-xs"></i>
                                                        Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($order->tracking_number)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center text-xs text-gray-600">
                                        <i class="fas fa-shipping-fast text-indigo-500 mr-1.5"></i>
                                        <span>Suivi :</span>
                                        <span class="font-medium ml-1">{{ $order->tracking_number }}</span>
                                        <a href="{{ route('tracking.index') }}" class="ml-2.5 text-indigo-600 hover:text-indigo-500 text-xs">
                                            Suivre
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center">
                                <div class="mx-auto w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mb-3.5">
                                    <i class="fas fa-box-open text-xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 mb-1.5">Aucune commande récente</p>
                                <p class="text-xs text-gray-400 mb-4">Commencez votre première commande</p>
                                <a href="{{ route('shop.index') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 font-medium text-sm">
                                    <i class="fas fa-shopping-bag mr-1.5 text-xs"></i>
                                    Découvrir la collection
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Colonne de Droite - Actions Rapides & Profil -->
            <div class="space-y-6">
                <!-- Résumé Profil - Minimal -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center space-x-3.5">
                        <div class="relative">
                            <img class="h-16 w-16 rounded-full object-cover border-2 border-white shadow"
                                 src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=667eea&color=fff&size=64' }}"
                                 alt="{{ Auth::user()->name }}">
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 border-2 border-white shadow-xs"></div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-base">{{ Auth::user()->name }}</h3>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            @if(Auth::user()->numero)
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-phone-alt text-gray-400 mr-1 text-xs"></i>
                                    {{ Auth::user()->numero }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 space-y-2">
                        <a href="{{ route('user.account.details') }}"
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-all duration-150 group">
                            <div class="flex items-center">
                                <div class="p-2 rounded-md bg-indigo-50 group-hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-user-circle text-indigo-600 text-sm"></i>
                                </div>
                                <span class="ml-2.5 text-gray-700 text-sm font-medium">Mon profil</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-500 transition-colors text-xs"></i>
                        </a>
                        <a href="{{ route('user.addresses') }}"
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-all duration-150 group">
                            <div class="flex items-center">
                                <div class="p-2 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
                                </div>
                                <span class="ml-2.5 text-gray-700 text-sm font-medium">Mes adresses</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors text-xs"></i>
                        </a>
                        <a href="{{ route('wishlist.index') }}"
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-all duration-150 group">
                            <div class="flex items-center">
                                <div class="p-2 rounded-md bg-red-50 group-hover:bg-red-100 transition-colors">
                                    <i class="fas fa-heart text-red-600 text-sm"></i>
                                </div>
                                <span class="ml-2.5 text-gray-700 text-sm font-medium">Mes favoris</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-red-500 transition-colors text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Statut Sécurité - Clean Design -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-shield-alt text-indigo-500 mr-2.5"></i>
                        <h3 class="font-semibold text-gray-900 text-sm">Sécurité du compte</h3>
                    </div>
                    <div class="space-y-3">
                        <!-- Vérification Email -->
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50">
                            <div class="flex items-center">
                                <div class="p-1.5 rounded-md {{ Auth::user()->hasVerifiedEmail() ? 'bg-green-100' : 'bg-red-100' }}">
                                    <i class="fas {{ Auth::user()->hasVerifiedEmail() ? 'fa-check-circle text-green-600' : 'fa-exclamation-circle text-red-600' }} text-xs"></i>
                                </div>
                                <div class="ml-2.5">
                                    <p class="text-xs font-medium text-gray-700">Email vérifié</p>
                                    @if(Auth::user()->hasVerifiedEmail())
                                        <p class="text-xs text-green-600">✓ Vérifié</p>
                                    @else
                                        <p class="text-xs text-red-600">Non vérifié</p>
                                    @endif
                                </div>
                            </div>
                            @unless(Auth::user()->hasVerifiedEmail())
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1 rounded font-medium transition-colors">
                                        Renvoyer
                                    </button>
                                </form>
                            @endunless
                        </div>

                        <!-- Statut 2FA -->
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50">
                            <div class="flex items-center">
                                <div class="p-1.5 rounded-md {{ Auth::user()->hasTwoFactorEnabled() ? 'bg-green-100' : 'bg-yellow-100' }}">
                                    <i class="fas {{ Auth::user()->hasTwoFactorEnabled() ? 'fa-lock text-green-600' : 'fa-unlock text-yellow-600' }} text-xs"></i>
                                </div>
                                <div class="ml-2.5">
                                    <p class="text-xs font-medium text-gray-700">2FA</p>
                                    @if(Auth::user()->hasTwoFactorEnabled())
                                        <p class="text-xs text-green-600">✓ Activée</p>
                                    @else
                                        <p class="text-xs text-yellow-600">Non activée</p>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('two-factor.index') }}"
                               class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1 rounded font-medium transition-colors">
                                Gérer
                            </a>
                        </div>

                        <!-- Mots de passe -->
                        <div class="p-2.5 rounded-lg bg-gray-50">
                            <p class="text-xs font-medium text-gray-700 mb-1.5">Mots de passe</p>
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-indigo-600 hover:text-indigo-500 font-medium flex items-center">
                                <i class="fas fa-key mr-1 text-xs"></i>
                                Changer mon mot de passe
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides - Minimal -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-5 border border-indigo-100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-bolt text-indigo-500 mr-2.5 text-sm"></i>
                        <h3 class="font-semibold text-indigo-900 text-sm">Actions rapides</h3>
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('shop.index') }}"
                           class="flex items-center p-2.5 bg-white rounded-lg hover:shadow transition-all duration-150 group border border-indigo-100 hover:border-indigo-200">
                            <div class="p-1.5 rounded-md bg-indigo-50 group-hover:bg-indigo-100 transition-colors">
                                <i class="fas fa-shopping-bag text-indigo-600 text-xs"></i>
                            </div>
                            <span class="ml-2.5 text-indigo-800 text-sm font-medium">Continuer mes achats</span>
                            <i class="fas fa-chevron-right ml-auto text-indigo-300 group-hover:text-indigo-500 transition-colors text-xs"></i>
                        </a>
                        <a href="{{ route('user.addresses.create') }}"
                           class="flex items-center p-2.5 bg-white rounded-lg hover:shadow transition-all duration-150 group border border-indigo-100 hover:border-indigo-200">
                            <div class="p-1.5 rounded-md bg-blue-50 group-hover:bg-blue-100 transition-colors">
                                <i class="fas fa-plus text-blue-600 text-xs"></i>
                            </div>
                            <span class="ml-2.5 text-indigo-800 text-sm font-medium">Ajouter une adresse</span>
                            <i class="fas fa-chevron-right ml-auto text-indigo-300 group-hover:text-blue-500 transition-colors text-xs"></i>
                        </a>
                        <a href="{{ route('user.refunds') }}"
                           class="flex items-center p-2.5 bg-white rounded-lg hover:shadow transition-all duration-150 group border border-indigo-100 hover:border-indigo-200">
                            <div class="p-1.5 rounded-md bg-green-50 group-hover:bg-green-100 transition-colors">
                                <i class="fas fa-undo-alt text-green-600 text-xs"></i>
                            </div>
                            <span class="ml-2.5 text-indigo-800 text-sm font-medium">Mes retours</span>
                            <i class="fas fa-chevron-right ml-auto text-indigo-300 group-hover:text-green-500 transition-colors text-xs"></i>
                        </a>
                        <a href="{{ route('support.index') }}"
                           class="flex items-center p-2.5 bg-white rounded-lg hover:shadow transition-all duration-150 group border border-indigo-100 hover:border-indigo-200">
                            <div class="p-1.5 rounded-md bg-purple-50 group-hover:bg-purple-100 transition-colors">
                                <i class="fas fa-headset text-purple-600 text-xs"></i>
                            </div>
                            <span class="ml-2.5 text-indigo-800 text-sm font-medium">Support client</span>
                            <i class="fas fa-chevron-right ml-auto text-indigo-300 group-hover:text-purple-500 transition-colors text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Offres Spéciales - Dark Minimal -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-5 text-white">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-gem text-indigo-300 mr-2.5 text-sm"></i>
                        <h3 class="font-semibold text-sm">Avantages Membre</h3>
                    </div>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-400 mr-1.5 text-xs"></i>
                            <span>Livraison gratuite dès 50€</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-400 mr-1.5 text-xs"></i>
                            <span>Retours gratuits 30 jours</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-400 mr-1.5 text-xs"></i>
                            <span>Accès anticipé</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-400 mr-1.5 text-xs"></i>
                            <span>5% de réduction permanente</span>
                        </li>
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('shop.index') }}"
                           class="inline-flex items-center justify-center w-full px-3 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 font-medium text-xs">
                            <i class="fas fa-gift mr-1.5 text-xs"></i>
                            Profiter de mes avantages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Produits Recommandés - Clean Grid -->
        @if($recommendedProducts && $recommendedProducts->count() > 0)
        <div class="mt-10">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Produits recommandés</h2>
                    <p class="text-gray-600 mt-0.5 text-sm">Basé sur vos préférences</p>
                </div>
                <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium flex items-center text-sm">
                    Voir toute la collection
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($recommendedProducts as $product)
                <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                        <div class="relative h-40 overflow-hidden bg-gray-100">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            <div class="absolute top-2.5 right-2.5">
                                <button onclick="event.preventDefault(); addToWishlist({{ $product->id }})"
                                        class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="fas fa-heart text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 text-sm line-clamp-1">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-bold text-gray-900">{{ number_format($product->price, 2) }} €</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                                    {{ $product->category->name ?? 'Bijoux' }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <button onclick="event.preventDefault(); addToCart({{ $product->id }})"
                                        class="w-full py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 font-medium text-xs">
                                    <i class="fas fa-shopping-cart mr-1.5 text-xs"></i>
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes au chargement
        const statCards = document.querySelectorAll('.grid-cols-3 > div');
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(10px)';

            setTimeout(() => {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });

        // Fonction pour ajouter un produit aux favoris
        window.addToWishlist = function(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Produit ajouté à vos favoris', 'success');
                updateWishlistCount();
            })
            .catch(error => {
                showNotification('Une erreur est survenue', 'error');
            });
        };

        // Fonction pour ajouter au panier
        window.addToCart = function(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Produit ajouté au panier', 'success');
                updateCartCount();
            })
            .catch(error => {
                showNotification('Une erreur est survenue', 'error');
            });
        };

        function updateWishlistCount() {
            fetch('/api/user/wishlist-count')
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('.wishlist-count').forEach(el => {
                        el.textContent = data.count;
                    });
                });
        }

        function updateCartCount() {
            // Mettre à jour le compteur du panier dans le header
            const cartBadge = document.querySelector('.cart-count-badge');
            if (cartBadge) {
                const current = parseInt(cartBadge.textContent) || 0;
                cartBadge.textContent = current + 1;
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-3 border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'} animate-slide-in`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'} text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Vérification des notifications de remboursement
        function checkRefundNotifications() {
            fetch('/api/user/refund-notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.has_updates) {
                        showRefundNotification(data);
                    }
                })
                .finally(() => {
                    // Vérifier à nouveau toutes les 5 minutes
                    setTimeout(checkRefundNotifications, 300000);
                });
        }

        function showRefundNotification(data) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 border border-gray-200 animate-slide-in';
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="p-2 rounded-md bg-indigo-100">
                            <i class="fas fa-undo-alt text-indigo-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-gray-900">Mise à jour remboursement</h4>
                        <p class="text-xs text-gray-600 mt-1">
                            Votre demande #${data.refund_id} : <span class="font-medium text-indigo-600">${data.new_status}</span>
                        </p>
                        <div class="mt-2.5 flex space-x-2.5">
                            <a href="/user/refunds" class="text-xs text-indigo-600 hover:text-indigo-500 font-medium">
                                Voir détails
                            </a>
                            <button onclick="this.closest('div').remove()" class="text-xs text-gray-500 hover:text-gray-700">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 8000);
        }

        // Démarrer la vérification des notifications
        setTimeout(checkRefundNotifications, 2000);

        // Ajouter des styles d'animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slide-in {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }

            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
