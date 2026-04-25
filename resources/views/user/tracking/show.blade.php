@extends('layouts.app')
@section('content')
<style>
    .tracking-detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

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

    .account-card-header h4, .account-card-header h5 {
        margin: 0;
        color: var(--text_primary);
        font-weight: 600;
    }

    .account-card-body { padding: 1.5rem; }

    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; }
    .breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 500; }
    .breadcrumb-item.active { color: var(--text_secondary); }

    .page-header-custom { margin-bottom: 1.5rem; }
    .page-header-custom h1 { font-size: 1.75rem; font-weight: 700; color: var(--text_primary); margin-bottom: 0.5rem; }
    .page-header-custom p { color: var(--text_secondary); margin-bottom: 0; }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg_soft);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text_secondary);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 15%, transparent);
    }

    .barcode-section {
        text-align: center;
        background: var(--surface);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
    }

    .barcode {
        font-family: 'Libre Barcode 128', cursive;
        font-size: 3.5rem;
        letter-spacing: 3px;
        margin: 1.5rem 0;
        color: var(--text_primary);
    }

    .barcode-label { font-size: 0.875rem; color: var(--text_secondary); margin-top: 0.5rem; }

    .barcode-code {
        background: var(--bg_soft);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: var(--text_secondary);
        font-size: 0.813rem;
        display: inline-block;
    }

    .timeline-section {
        background: var(--surface);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
    }

    .timeline {
        position: relative;
        padding-left: 40px;
        margin: 2rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child { margin-bottom: 0; padding-bottom: 0; }

    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--surface);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text_secondary);
    }

    .timeline-item.completed .timeline-icon {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-color: var(--primary);
        color: white;
    }

    .timeline-item.current .timeline-icon {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 0 0 5px color-mix(in srgb, var(--primary) 10%, transparent);
    }

    .timeline-content { padding-left: 1rem; }
    .timeline-date { font-size: 0.875rem; color: var(--text_secondary); margin-bottom: 0.25rem; }
    .timeline-title { font-weight: 600; color: var(--text_primary); margin-bottom: 0.25rem; }
    .timeline-description { font-size: 0.875rem; color: var(--text_secondary); }

    .delivery-estimation {
        background: var(--bg_soft);
        border-left: 4px solid var(--primary);
        border-radius: 10px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }

    .delivery-estimation i { color: var(--primary); font-size: 1.25rem; }
    .delivery-estimation h6 { color: var(--text_primary); margin-bottom: 0.25rem; }
    .delivery-estimation p { color: var(--text_secondary); margin-bottom: 0; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px var(--card_shadow);
        border: 1px solid var(--border_soft);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px var(--card_shadow);
    }

    .info-card-title {
        font-weight: 600;
        color: var(--text_primary);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border_soft);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-title i { color: var(--primary); }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin: 0.75rem 0;
        font-size: 0.875rem;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge.bg-warning {
        background: color-mix(in srgb, var(--warning) 15%, transparent) !important;
        color: var(--warning) !important;
    }

    .badge.bg-info {
        background: color-mix(in srgb, var(--info) 15%, transparent) !important;
        color: var(--info) !important;
    }

    .badge.bg-success {
        background: color-mix(in srgb, var(--success) 15%, transparent) !important;
        color: var(--success) !important;
    }

    .actions-section {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .action-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1.25rem 1rem;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        background: var(--surface);
        color: var(--text_secondary);
        border: 1px solid var(--border);
    }

    .action-button i { font-size: 1.25rem; color: var(--primary); transition: all 0.3s ease; }
    .action-button span { font-size: 0.813rem; text-align: center; }

    .action-button:hover {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-color: transparent;
        color: white;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px color-mix(in srgb, var(--primary) 35%, transparent);
    }

    .action-button:hover i { color: white; }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
    }

    .btn-primary i { color: white; }
    .btn-primary:hover { filter: brightness(1.05); }

    .help-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--divider);
    }

    .help-card {
        background: var(--bg_soft);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid var(--border_soft);
        height: 100%;
    }

    .help-card:hover {
        background: var(--surface);
        border-color: color-mix(in srgb, var(--primary) 20%, transparent);
        transform: translateY(-2px);
    }

    .help-card i { color: var(--primary); }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; gap: 1rem; }
        .actions-section { grid-template-columns: repeat(2, 1fr); }
        .barcode-section { padding: 1.5rem; }
        .barcode { font-size: 2.5rem; }
    }

    @media (max-width: 480px) {
        .actions-section { grid-template-columns: 1fr; }
        .timeline { padding-left: 30px; }
        .timeline-icon { left: -30px; width: 30px; height: 30px; font-size: 0.75rem; }
        .timeline::before { left: 15px; }
    }
</style>

<main class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tracking.index') }}">Suivi de commande</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Commande #{{ $order->id }}</li>
                    </ol>
                </nav>
                <div class="page-header-custom">
                    <h1>Suivi de commande #{{ $order->id }}</h1>
                </div>
                <a href="{{ route('tracking.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Retour au suivi
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Barcode -->
                <div class="barcode-section">
                    <h5 class="mb-3" style="color: #1F2937; font-weight: 600;">
                        <i class="fas fa-barcode me-2" style="color: #667eea;"></i>Code de suivi
                    </h5>
                    <div class="barcode">*{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}*</div>
                    <div class="barcode-label">Scannez ce code pour accéder au suivi</div>
                    <div class="mt-3">
                        <span class="barcode-code">
                            <i class="fas fa-lock me-1"></i>
                            Code unique : {{ substr(md5($order->id . $order->created_at), 0, 8) }}...
                        </span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="timeline-section">
                    <h5 class="mb-4" style="color: #1F2937; font-weight: 600;">
                        <i class="fas fa-history me-2" style="color: #667eea;"></i>Historique de la commande
                    </h5>

                    <div class="timeline">
                        @foreach($timeline as $event)
                        <div class="timeline-item {{ $event['completed'] ? 'completed' : '' }} {{ $event['current'] ? 'current' : '' }}">
                            <div class="timeline-icon">
                                <i class="fas fa-{{ $event['icon'] }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-date">
                                    @if($event['date'])
                                        {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                    @else
                                        À venir
                                    @endif
                                </div>
                                <div class="timeline-title">{{ $event['title'] }}</div>
                                <div class="timeline-description">{{ $event['description'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Delivery Estimation -->
                    @if($estimatedDelivery)
                    <div class="delivery-estimation">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-calendar-alt me-3 mt-1"></i>
                            <div>
                                <h6>Informations de livraison</h6>
                                <p class="mb-0">{{ $estimatedDelivery['message'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Information Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <h6 class="info-card-title">
                            <i class="fas fa-info-circle"></i>
                            Détails de la commande
                        </h6>
                        <div class="info-row">
                            <span class="text-muted">N° de commande</span>
                            <span class="fw-medium">#{{ $order->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Date</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Statut</span>
                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'confirmed' ? 'info' : 'warning') }}">
                                @if($order->status == 'delivered') Livré
                                @elseif($order->status == 'confirmed') Confirmé
                                @elseif($order->status == 'shipped') Expédié
                                @else Commandé @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Montant total</span>
                            <span class="fw-bold" style="color: #667eea;">{{ number_format($order->total, 2) }}€</span>
                        </div>
                        @if($order->tracking_number)
                        <div class="info-row">
                            <span class="text-muted">N° de suivi transporteur</span>
                            <span class="fw-medium font-monospace">{{ $order->tracking_number }}</span>
                        </div>
                        @endif
                        @if($order->carrier)
                        <div class="info-row">
                            <span class="text-muted">Transporteur</span>
                            <span>{{ $order->carrier }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="info-card">
                        <h6 class="info-card-title">
                            <i class="fas fa-user"></i>
                            Informations client
                        </h6>
                        <div class="info-row">
                            <span class="text-muted">Nom</span>
                            <span>{{ $order->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Téléphone</span>
                            <span>{{ $order->phone }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Email</span>
                            <span>{{ $order->user->email ?? $order->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Adresse</span>
                            <span class="text-end">{{ $order->address }}, {{ $order->zip }} {{ $order->city }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-section">
                    <a href="{{ route('tracking.print', ['order_id' => $order->id]) }}"
                       target="_blank"
                       class="action-button btn-primary">
                        <i class="fas fa-print"></i>
                        <span>Imprimer le suivi</span>
                    </a>

                    <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}"
                       class="action-button">
                        <i class="fas fa-receipt"></i>
                        <span>Détails de la commande</span>
                    </a>

                    <a href="{{ route('user.order.invoice', ['order_id' => $order->id]) }}"
                       target="_blank"
                       class="action-button">
                        <i class="fas fa-file-invoice"></i>
                        <span>Télécharger la facture</span>
                    </a>

                    <button onclick="shareTracking()" class="action-button">
                        <i class="fas fa-share-alt"></i>
                        <span>Partager le suivi</span>
                    </button>
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <h5 class="mb-4" style="color: #1F2937; font-weight: 600;">
                        <i class="fas fa-question-circle me-2" style="color: #667eea;"></i>Besoin d'aide ?
                    </h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Commande en retard ?</strong>
                                </div>
                                <p class="small">Contactez notre service client pour vérifier le statut de votre livraison.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-truck me-2"></i>
                                    <strong>Problème de livraison ?</strong>
                                </div>
                                <p class="small">Contactez-nous dans les 48h suivant la réception.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="help-card">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exchange-alt me-2"></i>
                                    <strong>Retour ou échange ?</strong>
                                </div>
                                <p class="small">Vous avez 14 jours pour effectuer un retour.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function shareTracking() {
    const trackingUrl = '{{ route("public.order.tracking", ["order_id" => $order->id, "token" => md5($order->id . $order->created_at)]) }}';
    const trackingText = `Suivez ma commande #{{ $order->id }} sur {{ config('app.name') }}`;

    if (navigator.share) {
        navigator.share({
            title: 'Suivi de commande #{{ $order->id }}',
            text: trackingText,
            url: trackingUrl,
        })
        .then(() => console.log('Partage réussi'))
        .catch((error) => console.log('Erreur de partage:', error));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(`${trackingText}\n${trackingUrl}`)
            .then(() => {
                alert('Lien de suivi copié dans le presse-papier !');
            })
            .catch(err => {
                console.error('Erreur de copie:', err);
                prompt('Copiez ce lien :', trackingUrl);
            });
    }
}
</script>
@endsection
