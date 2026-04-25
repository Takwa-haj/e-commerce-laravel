@extends('layouts.app')
@section('content')
<style>
    .public-tracking-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .info-alert {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .tracking-content {
        background: var(--surface);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
</style>

<main class="py-8">
    <div class="public-tracking-container">
        <!-- Information Alert -->
        <div class="info-alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle me-3 mt-1" style="color: #d4af37; font-size: 1.25rem;"></i>
                <div>
                    <h5 class="mb-2" style="color: #2c3e50;">Suivi de commande public</h5>
                    <p class="mb-0">Vous consultez le suivi d'une commande via un lien partagé. Pour gérer toutes vos commandes, <a href="{{ route('login') }}">connectez-vous à votre compte</a>.</p>
                </div>
            </div>
        </div>

        <!-- Tracking Content -->
        <div class="tracking-content">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 style="color: #d4af37;">
                    <i class="fas fa-truck-tracking me-2"></i>
                    Suivi de commande #{{ $order->id }}
                </h2>
                <p class="text-muted mb-0">Partagé par le client</p>
            </div>

            <!-- Status -->
            <div class="text-center mb-4">
                @php
                    $statusColors = [
                        'ordered' => 'warning',
                        'confirmed' => 'info',
                        'delivered' => 'success',
                        'canceled' => 'danger'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} px-3 py-2 fs-6">
                    @if($order->status == 'delivered')
                        <i class="fas fa-check-circle me-2"></i> Livrée
                    @elseif($order->status == 'confirmed')
                        <i class="fas fa-check me-2"></i> Confirmée
                    @elseif($order->status == 'canceled')
                        <i class="fas fa-times-circle me-2"></i> Annulée
                    @else
                        <i class="fas fa-clock me-2"></i> Commandée
                    @endif
                </span>
            </div>

            <!-- Order Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #d4af37;">
                                <i class="fas fa-info-circle me-2"></i>Détails de la commande
                            </h5>
                            <div class="mb-2">
                                <small class="text-muted">Numéro de commande</small>
                                <div class="fw-bold">#{{ $order->id }}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Date de commande</small>
                                <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Montant total</small>
                                <div class="fw-bold text-success">{{ number_format($order->total, 2) }} €</div>
                            </div>
                            @if($order->tracking_number)
                            <div class="mb-2">
                                <small class="text-muted">Numéro de suivi</small>
                                <div class="fw-bold font-monospace">{{ $order->tracking_number }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #d4af37;">
                                <i class="fas fa-user me-2"></i>Informations client
                            </h5>
                            <div class="mb-2">
                                <small class="text-muted">Nom</small>
                                <div class="fw-bold">{{ $order->name }}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Ville</small>
                                <div>{{ $order->city }}, {{ $order->zip }}</div>
                            </div>
                            @if($order->estimated_delivery)
                            <div class="mb-2">
                                <small class="text-muted">Livraison estimée</small>
                                <div>{{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title" style="color: #d4af37;">
                        <i class="fas fa-history me-2"></i>Historique de la commande
                    </h5>

                    <div class="timeline" style="position: relative; padding-left: 40px; margin: 2rem 0;">
                        <div style="position: absolute; left: 20px; top: 0; bottom: 0; width: 2px; background: rgba(212, 175, 55, 0.3);"></div>

                        <!-- Order Placed -->
                        <div style="position: relative; margin-bottom: 2rem; padding-bottom: 2rem;">
                            <div style="position: absolute; left: -40px; top: 0; width: 40px; height: 40px; border-radius: 50%; background: #d4af37; border: 2px solid #d4af37; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div style="padding-left: 1rem;">
                                <div style="font-size: 0.875rem; color: #6c757d; margin-bottom: 0.25rem;">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">
                                    Commande passée
                                </div>
                                <div style="font-size: 0.875rem; color: #6c757d;">
                                    Votre commande a été enregistrée
                                </div>
                            </div>
                        </div>

                        <!-- Additional timeline steps based on order status -->
                        @if($order->status == 'confirmed' || $order->status == 'delivered')
                        <div style="position: relative; margin-bottom: 2rem; padding-bottom: 2rem;">
                            <div style="position: absolute; left: -40px; top: 0; width: 40px; height: 40px; border-radius: 50%; background: #d4af37; border: 2px solid #d4af37; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div style="padding-left: 1rem;">
                                <div style="font-size: 0.875rem; color: #6c757d; margin-bottom: 0.25rem;">
                                    @if($order->confirmed_date)
                                        {{ \Carbon\Carbon::parse($order->confirmed_date)->format('d/m/Y H:i') }}
                                    @else
                                        En attente
                                    @endif
                                </div>
                                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">
                                    Commande confirmée
                                </div>
                                <div style="font-size: 0.875rem; color: #6c757d;">
                                    En préparation pour l'expédition
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'delivered')
                        <div style="position: relative;">
                            <div style="position: absolute; left: -40px; top: 0; width: 40px; height: 40px; border-radius: 50%; background: #d4af37; border: 2px solid #d4af37; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div style="padding-left: 1rem;">
                                <div style="font-size: 0.875rem; color: #6c757d; margin-bottom: 0.25rem;">
                                    @if($order->delivered_date)
                                        {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">
                                    Commande livrée
                                </div>
                                <div style="font-size: 0.875rem; color: #6c757d;">
                                    Livraison effectuée avec succès
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="text-center mt-4 pt-4 border-top">
                <p class="text-muted mb-3">Pour toute question concernant cette commande, veuillez contacter le client directement.</p>
                <p class="small text-muted mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Ce lien de suivi est sécurisé et ne contient pas d'informations personnelles sensibles.
                </p>
            </div>
        </div>
    </div>
</main>
@endsection
