@extends('layouts.app')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Mes commandes</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.order.details', $order->id) }}">Commande #{{ $order->id }}</a></li>
                        <li class="breadcrumb-item active">Suivi de commande</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Tracking Header -->
                <div class="tracking-header mb-4">
                    <div class="d-flex align-items-center">
                        <div class="tracking-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h1 class="h3 mb-1">Suivi de commande</h1>
                            <p class="mb-0 text-white-80">Commande #{{ $order->id }} • {{ $order->created_at instanceof \Carbon\Carbon ? $order->created_at->format('d/m/Y') : \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Tracking Card -->
                <div class="account-card">
                    <div class="account-card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marked-alt fs-4 me-2 text-primary"></i>
                                <h5 class="mb-0">Suivi en temps réel</h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="order-status status-{{ $order->status }} me-3">
                                    {{ $order->status }}
                                </span>
                                @if($order->tracking_number)
                                    <span class="tracking-number-badge">
                                        <i class="fas fa-barcode me-1"></i>
                                        {{ $order->tracking_number }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="account-card-body">
                        <!-- Progress Tracker -->
                        <div class="tracking-progress mb-5">
                            <div class="progress-steps">
                                @php
                                    $steps = [
                                        'ordered' => ['label' => 'Commande validée', 'icon' => 'fa-check-circle', 'date' => $order->created_at],
                                        'confirmed' => ['label' => 'Confirmation', 'icon' => 'fa-check-double', 'date' => $order->confirmed_at ?? null],
                                        'processing' => ['label' => 'Préparation', 'icon' => 'fa-box', 'date' => $order->processing_at ?? null],
                                        'shipped' => ['label' => 'Expédition', 'icon' => 'fa-truck', 'date' => $order->shipped_at ?? null],
                                        'delivered' => ['label' => 'Livraison', 'icon' => 'fa-home', 'date' => $order->delivered_date],
                                    ];

                                    $currentStep = 0;
                                    foreach(array_keys($steps) as $index => $step) {
                                        if($order->status == $step) {
                                            $currentStep = $index;
                                            break;
                                        }
                                    }
                                @endphp

                                @foreach($steps as $key => $step)
                                    @php
                                        $stepCompleted = $step['date'] !== null;
                                        $stepActive = $order->status == $key;
                                        $stepIndex = $loop->index;
                                    @endphp
                                    <div class="progress-step {{ $stepCompleted ? 'completed' : '' }} {{ $stepActive ? 'active' : '' }} {{ $stepIndex <= $currentStep ? 'passed' : '' }}">
                                        <div class="step-icon">
                                            <i class="fas {{ $step['icon'] }}"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-label">{{ $step['label'] }}</h6>
                                            @if($step['date'])
                                                <span class="step-date">
                                                    @if($step['date'] instanceof \Carbon\Carbon)
                                                        {{ $step['date']->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($step['date'])->format('d/m/Y') }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="step-date text-muted">En attente</span>
                                            @endif
                                        </div>
                                        @if(!$loop->last)
                                            <div class="step-connector {{ $stepIndex < $currentStep ? 'completed' : '' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tracking Timeline -->
                        <div class="tracking-timeline">
                            <h6 class="mb-3">
                                <i class="fas fa-history me-2 text-primary"></i>
                                Historique de suivi
                            </h6>

                            <div class="timeline">
                                @forelse($tracking_events ?? [] as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                <div>
                                                    <h6 class="timeline-title">{{ $event['title'] ?? 'Mise à jour' }}</h6>
                                                    <p class="timeline-description">{{ $event['description'] ?? '' }}</p>
                                                    @if(!empty($event['location']))
                                                        <span class="timeline-location">
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            {{ $event['location'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="timeline-date">
                                                    @if($event['date'] instanceof \Carbon\Carbon)
                                                        {{ $event['date']->format('d/m/Y H:i') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <!-- Default tracking timeline based on order status -->
                                    @if($order->status == 'ordered' || $order->status == 'confirmed')
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="timeline-title">Commande confirmée</h6>
                                                        <p class="timeline-description">Votre commande a été confirmée et est en cours de traitement.</p>
                                                    </div>
                                                    <span class="timeline-date">
                                                        @if($order->created_at instanceof \Carbon\Carbon)
                                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status == 'shipped' || $order->status == 'delivered')
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="timeline-title">Colis expédié</h6>
                                                        <p class="timeline-description">Votre colis a été expédié et est en route.</p>
                                                        @if($order->tracking_number)
                                                            <span class="timeline-tracking">
                                                                <i class="fas fa-barcode me-1"></i>
                                                                Numéro de suivi: {{ $order->tracking_number }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="timeline-date">
                                                        @if($order->shipped_at instanceof \Carbon\Carbon)
                                                            {{ $order->shipped_at->format('d/m/Y H:i') }}
                                                        @elseif($order->updated_at)
                                                            {{ $order->updated_at instanceof \Carbon\Carbon ? $order->updated_at->format('d/m/Y H:i') : \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status == 'delivered')
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="timeline-title">Colis livré</h6>
                                                        <p class="timeline-description">Votre commande a été livrée avec succès.</p>
                                                    </div>
                                                    <span class="timeline-date">
                                                        @if($order->delivered_date instanceof \Carbon\Carbon)
                                                            {{ $order->delivered_date->format('d/m/Y H:i') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status == 'canceled')
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="timeline-title">Commande annulée</h6>
                                                        <p class="timeline-description">Cette commande a été annulée.</p>
                                                    </div>
                                                    <span class="timeline-date">
                                                        @if($order->updated_at instanceof \Carbon\Carbon)
                                                            {{ $order->updated_at->format('d/m/Y H:i') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforelse
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        @if($order->status != 'canceled')
                            <div class="shipping-info mt-5">
                                <h6 class="mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    Informations de livraison
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="info-box">
                                            <span class="info-label">Adresse de livraison</span>
                                            <p class="info-value mb-0">
                                                {{ $order->name }}<br>
                                                {{ $order->address }}<br>
                                                @if($order->locality){{ $order->locality }}<br>@endif
                                                {{ $order->city }}, {{ $order->zip }}<br>
                                                {{ $order->country }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-box">
                                            <span class="info-label">Contact</span>
                                            <p class="info-value mb-0">
                                                <i class="fas fa-phone me-2"></i>{{ $order->phone }}<br>
                                                <i class="fas fa-envelope me-2"></i>{{ $order->email ?? $order->user->email ?? 'Non renseigné' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($order->estimated_delivery)
                                    <div class="col-md-6 mb-3">
                                        <div class="info-box">
                                            <span class="info-label">Livraison estimée</span>
                                            <p class="info-value mb-0">
                                                <i class="fas fa-calendar me-2"></i>
                                                @if($order->estimated_delivery instanceof \Carbon\Carbon)
                                                    {{ $order->estimated_delivery->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($order->carrier)
                                    <div class="col-md-6 mb-3">
                                        <div class="info-box">
                                            <span class="info-label">Transporteur</span>
                                            <p class="info-value mb-0">
                                                <i class="fas fa-truck me-2"></i>{{ $order->carrier }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tracking Actions -->
                <div class="tracking-actions mt-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.order.tracking.print', $order->id) }}"
                               target="_blank"
                               class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Imprimer le suivi</h6>
                                    <p class="small text-muted mb-0">Version PDF imprimable</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="javascript:void(0)"
                               onclick="shareTracking('{{ route('public.order.tracking', ['order_id' => $order->id, 'token' => md5($order->id . $order->created_at)]) }}')"
                               class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Partager le suivi</h6>
                                    <p class="small text-muted mb-0">Envoyer à un proche</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.order.details', $order->id) }}"
                               class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Détails commande</h6>
                                    <p class="small text-muted mb-0">Voir les articles</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Carrier Tracking Link -->
                @if($order->tracking_number && $order->carrier)
                    <div class="carrier-tracking mt-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="carrier-icon">
                                    @switch(strtolower($order->carrier))
                                        @case('colissimo')
                                        @case('laposte')
                                            <i class="fas fa-envelope"></i>
                                            @break
                                        @case('chronopost')
                                            <i class="fas fa-rocket"></i>
                                            @break
                                        @case('dhl')
                                            <i class="fas fa-plane"></i>
                                            @break
                                        @case('ups')
                                            <i class="fas fa-box"></i>
                                            @break
                                        @default
                                            <i class="fas fa-truck"></i>
                                    @endswitch
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Suivi {{ $order->carrier }}</h6>
                                    <p class="small text-muted mb-0">N° {{ $order->tracking_number }}</p>
                                </div>
                            </div>
                            <a href="{{ getCarrierTrackingUrl($order->carrier, $order->tracking_number) }}"
                               target="_blank"
                               class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Suivre sur le site du transporteur
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Need Help -->
                <div class="help-card mt-4">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="help-icon me-3">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="flex-grow-1 mb-2 mb-md-0">
                            <h6 class="mb-1">Un problème avec votre livraison ?</h6>
                            <p class="small text-muted mb-0">Notre équipe support est là pour vous aider.</p>
                        </div>
                        <a href="{{ route('contact') }}?subject=Suivi commande #{{ $order->id }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>
                            Contacter le support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .account-card {
        background: var(--surface);
        border-radius: 16px;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 24px;
    }

    .account-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .account-card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--bg_soft), var(--surface));
        border-bottom: 1px solid var(--border_soft);
    }

    .account-card-header h5 { margin: 0; color: var(--text_primary); font-weight: 600; }
    .account-card-body { padding: 1.5rem; }
    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; }
    .breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 500; }

    .tracking-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 16px;
        padding: 1.75rem 2rem;
        color: white;
    }

    .tracking-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-right: 1.5rem;
    }

    .text-white-80 { color: rgba(255, 255, 255, 0.9); }

    .order-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-ordered, .status-pending {
        background: color-mix(in srgb, var(--warning) 20%, transparent);
        color: var(--warning);
    }

    .status-confirmed {
        background: color-mix(in srgb, var(--info) 20%, transparent);
        color: var(--info);
    }

    .status-shipped {
        background: color-mix(in srgb, var(--info) 20%, transparent);
        color: var(--info);
    }

    .status-delivered {
        background: color-mix(in srgb, var(--success) 20%, transparent);
        color: var(--success);
    }

    .status-canceled {
        background: color-mix(in srgb, var(--error) 20%, transparent);
        color: var(--error);
    }

    .tracking-number-badge {
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text_primary);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .progress-steps {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0;
    }

    .progress-step { position: relative; flex: 1; text-align: center; z-index: 1; }

    .step-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--surface);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--text_secondary);
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .progress-step.completed .step-icon {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .progress-step.active .step-icon {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .progress-step.passed .step-icon {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .step-content { text-align: center; }
    .step-label { font-size: 0.9375rem; font-weight: 600; color: var(--text_primary); margin-bottom: 0.25rem; }
    .step-date { font-size: 0.75rem; color: var(--text_secondary); }

    .step-connector {
        position: absolute;
        top: 28px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: var(--border);
        z-index: 0;
    }

    .step-connector.completed { background: var(--success); }

    .timeline { position: relative; padding-left: 2rem; }
    .timeline::before { content: ''; position: absolute; left: 1rem; top: 0; bottom: 0; width: 2px; background: var(--border_soft); }

    .timeline-item { position: relative; margin-bottom: 1.5rem; }
    .timeline-item:last-child { margin-bottom: 0; }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: var(--surface);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text_secondary);
        font-size: 0.75rem;
        z-index: 1;
    }

    .timeline-item:first-child .timeline-marker {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .timeline-content { padding-left: 1rem; padding-bottom: 1rem; }
    .timeline-title { margin-bottom: 0.25rem; font-weight: 600; color: var(--text_primary); }
    .timeline-description { margin-bottom: 0.5rem; color: var(--text_secondary); font-size: 0.9375rem; }
    .timeline-date { font-size: 0.75rem; color: var(--text_secondary); white-space: nowrap; }

    .timeline-location, .timeline-tracking {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: var(--bg_soft);
        border-radius: 20px;
        font-size: 0.75rem;
        color: var(--text_secondary);
        margin-top: 0.5rem;
    }

    .info-box {
        background: var(--bg_soft);
        border: 1px solid var(--border_soft);
        border-radius: 12px;
        padding: 1.25rem;
        height: 100%;
    }

    .info-label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text_secondary);
        margin-bottom: 0.5rem;
    }

    .info-value { color: var(--text_primary); font-size: 0.9375rem; line-height: 1.6; }

    .action-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border_soft);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        height: 100%;
    }

    .action-card:hover {
        background: var(--bg_soft);
        border-color: color-mix(in srgb, var(--primary) 30%, transparent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 15%, transparent);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--primary);
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon {
        background: var(--primary);
        color: white;
    }

    .action-content { flex: 1; }
    .action-content h6 { margin-bottom: 0.25rem; color: var(--text_primary); font-weight: 600; }

    .carrier-tracking {
        background: var(--bg_soft);
        border: 1px solid var(--border_soft);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .carrier-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--surface);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
    }

    .help-card {
        background: var(--bg_soft);
        border: 1px solid var(--border_soft);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .help-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
    }

    .btn-outline-primary {
        border: 1px solid color-mix(in srgb, var(--primary) 30%, transparent);
        color: var(--primary);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    @media (max-width: 992px) {
        .progress-steps { flex-direction: column; align-items: flex-start; gap: 2rem; }
        .progress-step { display: flex; width: 100%; text-align: left; gap: 1rem; }
        .step-icon { margin: 0; width: 48px; height: 48px; font-size: 1rem; }
        .step-content { text-align: left; }
        .step-connector { display: none; }
    }
</style>
<script>
function shareTracking(url) {
    if (navigator.share) {
        navigator.share({
            title: 'Suivi de commande #{{ $order->id }}',
            text: 'Suivez ma commande en temps réel',
            url: url
        }).catch(console.error);
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Lien copié !',
                text: 'Le lien de suivi a été copié dans le presse-papier.',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Impossible de copier le lien.'
            });
        });
    }
}

// Auto-refresh tracking every 30 seconds for orders in transit
document.addEventListener('DOMContentLoaded', function() {
    const orderStatus = '{{ $order->status }}';
    if (['confirmed', 'processing', 'shipped'].includes(orderStatus)) {
        setTimeout(function() {
            location.reload();
        }, 30000);
    }
});
</script>
@endsection
