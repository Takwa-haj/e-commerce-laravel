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
                        <li class="breadcrumb-item active">Demande de remboursement</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                @if($order->status != 'delivered')
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">Remboursement non disponible</h6>
                                <p class="mb-0">Les demandes de remboursement ne sont disponibles que pour les commandes livrées.</p>
                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-outline-warning mt-3">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la commande
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($days_since_delivery > 14)
                    <div class="alert alert-danger">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">Délai de remboursement expiré</h6>
                                <p class="mb-0">La période de remboursement de 14 jours est dépassée. Vous ne pouvez plus demander de remboursement pour cette commande.</p>
                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-outline-danger mt-3">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la commande
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Refund Header -->
                    <div class="refund-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h3 mb-2">Demander un remboursement</h1>
                                <p class="mb-0 text-white-80">
                                    Commande #{{ $order->id }} •
                                    Livrée le
                                    @if($order->delivered_date instanceof \Carbon\Carbon)
                                        {{ $order->delivered_date->format('d/m/Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="refund-deadline">
                                    <small>Délai restant</small>
                                    <div class="deadline-timer">
                                        {{ max(0, 14 - $days_since_delivery) }} jour(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Main Form Column -->
                        <div class="col-lg-8">
                            <form id="refundForm" action="{{ route('refund.request.submit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <!-- Order Summary Card -->
                                <div class="account-card">
                                    <div class="account-card-header">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-receipt fs-4 me-2 text-primary"></i>
                                            <h5 class="mb-0">Récapitulatif de la commande</h5>
                                        </div>
                                    </div>
                                    <div class="account-card-body">
                                        <div class="order-summary">
                                            <div class="summary-row">
                                                <span>N° de commande</span>
                                                <span class="fw-medium">#{{ $order->id }}</span>
                                            </div>
                                            <div class="summary-row">
                                                <span>Date de commande</span>
                                                <span>
                                                    @if($order->created_at instanceof \Carbon\Carbon)
                                                        {{ $order->created_at->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="summary-row">
                                                <span>Date de livraison</span>
                                                <span>
                                                    @if($order->delivered_date instanceof \Carbon\Carbon)
                                                        {{ $order->delivered_date->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="summary-row">
                                                <span>Total de la commande</span>
                                                <span class="fw-bold">{{ number_format($order->total, 2) }}€</span>
                                            </div>
                                            <div class="summary-row highlight">
                                                <span>Montant maximum remboursable</span>
                                                <span class="fw-bold text-primary">{{ number_format($refundable_amount, 2) }}€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Selection Card -->
                                <div class="account-card mt-4">
                                    <div class="account-card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-box fs-4 me-2 text-primary"></i>
                                                <h5 class="mb-0">Sélectionner les articles</h5>
                                            </div>
                                            <span class="badge bg-primary" id="selectedCount">0 article(s) sélectionné(s)</span>
                                        </div>
                                    </div>
                                    <div class="account-card-body">
                                        <p class="text-muted mb-4">
                                            Cochez les articles pour lesquels vous souhaitez demander un remboursement.
                                            Vous pouvez choisir la quantité à rembourser pour chaque produit.
                                        </p>

                                        <div class="products-list">
                                            @foreach($order->orderItems as $item)
                                                @if($item->refund_status == 'none')
                                                    <div class="product-item" data-product-id="{{ $item->id }}">
                                                        <div class="product-checkbox">
                                                            <input type="checkbox"
                                                                   class="product-select"
                                                                   name="order_items[]"
                                                                   value="{{ $item->id }}"
                                                                   id="product_{{ $item->id }}"
                                                                   data-price="{{ $item->price }}"
                                                                   data-max="{{ $item->quantity }}">
                                                        </div>
                                                        <div class="product-image">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                                     alt="{{ $item->product->name }}">
                                                            @else
                                                                <div class="image-placeholder">
                                                                    <i class="fas fa-image"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="product-details">
                                                            <h6 class="product-name">
                                                                @if($item->product)
                                                                    {{ $item->product->name }}
                                                                    <small class="text-muted">SKU: {{ $item->product->SKU ?? 'N/A' }}</small>
                                                                @else
                                                                    <span class="text-muted">Produit non disponible</span>
                                                                @endif
                                                            </h6>
                                                            <div class="product-price">
                                                                <span class="current-price">{{ number_format($item->price, 2) }}€</span>
                                                                <span class="quantity-label">× {{ $item->quantity }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="product-quantity">
                                                            <label for="quantity_{{ $item->id }}" class="form-label small">Quantité</label>
                                                            <select name="quantities[{{ $item->id }}]"
                                                                    id="quantity_{{ $item->id }}"
                                                                    class="form-select form-select-sm quantity-select"
                                                                    disabled>
                                                                @for($i = 1; $i <= $item->quantity; $i++)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="product-total">
                                                            <small class="text-muted d-block">Total</small>
                                                            <span class="item-total fw-bold">0,00€</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="product-item already-refunded">
                                                        <div class="product-checkbox">
                                                            <input type="checkbox" disabled>
                                                        </div>
                                                        <div class="product-image opacity-50">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                                     alt="{{ $item->product->name }}">
                                                            @endif
                                                        </div>
                                                        <div class="product-details">
                                                            <h6 class="product-name text-muted">
                                                                {{ $item->product->name ?? 'Produit' }}
                                                            </h6>
                                                            <div class="product-price">
                                                                <span class="text-muted">{{ number_format($item->price, 2) }}€</span>
                                                            </div>
                                                        </div>
                                                        <div class="product-status">
                                                            <span class="badge bg-{{ $item->refund_status == 'completed' ? 'success' : 'warning' }}">
                                                                {{ $item->refund_status }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Selected Total -->
                                        <div class="selected-total mt-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Montant total sélectionné</h6>
                                                    <small class="text-muted">TVA incluse</small>
                                                </div>
                                                <div>
                                                    <span class="total-amount" id="totalRefundAmount">0,00€</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Refund Reason Card -->
                                <div class="account-card mt-4">
                                    <div class="account-card-header">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-question-circle fs-4 me-2 text-primary"></i>
                                            <h5 class="mb-0">Raison du remboursement</h5>
                                        </div>
                                    </div>
                                    <div class="account-card-body">
                                        <div class="reasons-grid">
                                            <div class="reason-option" data-reason="defective">
                                                <div class="reason-icon defective">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="reason-content">
                                                    <h6>Produit défectueux</h6>
                                                    <p class="small text-muted mb-0">Ne fonctionne pas, pièce manquante, problème de qualité</p>
                                                </div>
                                                <div class="reason-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>

                                            <div class="reason-option" data-reason="wrong">
                                                <div class="reason-icon wrong">
                                                    <i class="fas fa-box-open"></i>
                                                </div>
                                                <div class="reason-content">
                                                    <h6>Mauvais article</h6>
                                                    <p class="small text-muted mb-0">Article différent de ma commande</p>
                                                </div>
                                                <div class="reason-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>

                                            <div class="reason-option" data-reason="damaged">
                                                <div class="reason-icon damaged">
                                                    <i class="fas fa-truck"></i>
                                                </div>
                                                <div class="reason-content">
                                                    <h6>Article endommagé</h6>
                                                    <p class="small text-muted mb-0">Cassé pendant le transport, emballage abîmé</p>
                                                </div>
                                                <div class="reason-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>

                                            <div class="reason-option" data-reason="not_satisfied">
                                                <div class="reason-icon not-satisfied">
                                                    <i class="fas fa-frown"></i>
                                                </div>
                                                <div class="reason-content">
                                                    <h6>Non satisfait(e)</h6>
                                                    <p class="small text-muted mb-0">Ne correspond pas à mes attentes</p>
                                                </div>
                                                <div class="reason-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>

                                            <div class="reason-option" data-reason="other">
                                                <div class="reason-icon other">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </div>
                                                <div class="reason-content">
                                                    <h6>Autre raison</h6>
                                                    <p class="small text-muted mb-0">Précisez dans les détails</p>
                                                </div>
                                                <div class="reason-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="reason_type" id="reasonType" required>

                                        <div class="mt-4">
                                            <label for="reason_details" class="form-label fw-medium">
                                                Détails de votre demande <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="reason_details"
                                                      id="reason_details"
                                                      rows="4"
                                                      class="form-control"
                                                      placeholder="Veuillez décrire en détail le problème rencontré. Soyez le plus précis possible pour faciliter le traitement de votre demande."
                                                      required></textarea>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">Minimum 10 caractères</small>
                                                <small id="charCounter" class="text-muted">500 caractères max</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attachments Card -->
                                <div class="account-card mt-4">
                                    <div class="account-card-header">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-paperclip fs-4 me-2 text-primary"></i>
                                            <h5 class="mb-0">Pièces jointes</h5>
                                        </div>
                                    </div>
                                    <div class="account-card-body">
                                        <p class="text-muted small mb-3">
                                            Ajoutez des photos ou documents pour appuyer votre demande (format JPG, PNG, PDF - max 5MB par fichier).
                                            Ces pièces justificatives nous aideront à traiter votre demande plus rapidement.
                                        </p>

                                        <div class="attachments-grid">
                                            <div class="attachment-item">
                                                <div class="attachment-preview" onclick="document.getElementById('attachment1').click()">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Ajouter une photo</span>
                                                    <small>Photo 1</small>
                                                </div>
                                                <input type="file" name="attachments[]" id="attachment1" class="d-none" accept="image/*,.pdf">
                                                <div id="attachmentPreview1" class="attachment-preview-container"></div>
                                            </div>

                                            <div class="attachment-item">
                                                <div class="attachment-preview" onclick="document.getElementById('attachment2').click()">
                                                    <i class="fas fa-file-alt"></i>
                                                    <span>Ajouter un document</span>
                                                    <small>Photo 2 / Document</small>
                                                </div>
                                                <input type="file" name="attachments[]" id="attachment2" class="d-none" accept="image/*,.pdf">
                                                <div id="attachmentPreview2" class="attachment-preview-container"></div>
                                            </div>

                                            <div class="attachment-item">
                                                <div class="attachment-preview" onclick="document.getElementById('attachment3').click()">
                                                    <i class="fas fa-plus-circle"></i>
                                                    <span>Fichier supplémentaire</span>
                                                    <small>Optionnel</small>
                                                </div>
                                                <input type="file" name="attachments[]" id="attachment3" class="d-none" accept="image/*,.pdf">
                                                <div id="attachmentPreview3" class="attachment-preview-container"></div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label for="additional_info" class="form-label fw-medium">Informations complémentaires</label>
                                            <textarea name="additional_info"
                                                      id="additional_info"
                                                      rows="3"
                                                      class="form-control"
                                                      placeholder="Toute information supplémentaire qui pourrait nous aider (numéro de suivi, date de réception, etc.)"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Column -->
                            <div class="col-lg-4">
                                <!-- Refund Summary Card -->
                                <div class="account-card sticky-sidebar">
                                    <div class="account-card-header">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calculator fs-4 me-2 text-primary"></i>
                                            <h5 class="mb-0">Résumé</h5>
                                        </div>
                                    </div>
                                    <div class="account-card-body">
                                        <div class="refund-summary">
                                            <div class="summary-item">
                                                <span>Articles sélectionnés</span>
                                                <span id="sidebarSelectedCount">0</span>
                                            </div>
                                            <div class="summary-item">
                                                <span>Sous-total</span>
                                                <span id="sidebarSubtotal">0,00€</span>
                                            </div>
                                            <div class="summary-item">
                                                <span>TVA (20%)</span>
                                                <span id="sidebarTax">0,00€</span>
                                            </div>
                                            <div class="summary-item border-top">
                                                <span class="fw-bold">Total remboursement</span>
                                                <span class="fw-bold text-primary" id="sidebarTotal">0,00€</span>
                                            </div>
                                        </div>

                                        <div class="refund-policy mt-4">
                                            <h6 class="mb-3">
                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                Politique de remboursement
                                            </h6>
                                            <ul class="policy-list">
                                                <li>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    Délai de 14 jours après livraison
                                                </li>
                                                <li>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    Remboursement sous 5-7 jours ouvrés
                                                </li>
                                                <li>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    Sur le mode de paiement d'origine
                                                </li>
                                                <li>
                                                    <i class="fas fa-info-circle text-info"></i>
                                                    Produits personnalisés non remboursables
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="refund-timeline mt-4">
                                            <h6 class="mb-3">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                Délais de traitement
                                            </h6>
                                            <div class="timeline-steps">
                                                <div class="step">
                                                    <span class="step-number">1</span>
                                                    <span class="step-label">Examen de la demande</span>
                                                    <span class="step-duration">24-48h</span>
                                                </div>
                                                <div class="step">
                                                    <span class="step-number">2</span>
                                                    <span class="step-label">Validation</span>
                                                    <span class="step-duration">2-3 jours</span>
                                                </div>
                                                <div class="step">
                                                    <span class="step-number">3</span>
                                                    <span class="step-label">Remboursement</span>
                                                    <span class="step-duration">5-7 jours</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary w-100 py-3" id="submitBtn">
                                                <i class="fas fa-paper-plane me-2"></i>
                                                Soumettre la demande
                                            </button>
                                            <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-outline-secondary w-100 mt-2">
                                                <i class="fas fa-arrow-left me-2"></i>
                                                Annuler
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Need Help Card -->
                                <div class="help-card mt-4">
                                    <div class="d-flex">
                                        <div class="help-icon">
                                            <i class="fas fa-headset"></i>
                                        </div>
                                        <div class="help-content">
                                            <h6>Besoin d'aide ?</h6>
                                            <p class="small mb-2">Notre équipe support est là pour vous aider.</p>
                                            <a href="{{ route('home.contact') }}" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="fas fa-envelope me-2"></i>Contacter le support
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                <!-- Existing Refunds -->
                @if(isset($existing_refunds) && $existing_refunds->count() > 0)
                    <div class="account-card mt-4">
                        <div class="account-card-header">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-history fs-4 me-2 text-primary"></i>
                                <h5 class="mb-0">Demandes existantes</h5>
                            </div>
                        </div>
                        <div class="account-card-body">
                            <div class="existing-refunds">
                                @foreach($existing_refunds as $existing)
                                    <div class="existing-refund-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="fw-medium me-3">Demande #{{ $existing->id }}</span>
                                                    <span class="refund-status status-{{ $existing->status }}">
                                                        {{ $existing->getStatusLabel() ?? $existing->status }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    Soumise le
                                                    @if($existing->created_at instanceof \Carbon\Carbon)
                                                        {{ $existing->created_at->format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($existing->created_at)->format('d/m/Y') }}
                                                    @endif
                                                    • Montant: {{ number_format($existing->requested_amount, 2) }}€
                                                </small>
                                            </div>
                                            <a href="{{ route('user.refund.details', $existing->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<style>
/* ===== REFUND REQUEST STYLES - MATCHING ACCOUNT-NAV ===== */
.account-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(44, 62, 80, 0.06);
    border: 1px solid rgba(44, 62, 80, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 24px;
}

.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(44, 62, 80, 0.1);
}

.account-card-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #F9FAFB, #FFFFFF);
    border-bottom: 1px solid #F3F4F6;
}

.account-card-header h5 {
    margin: 0;
    color: #1F2937;
    font-weight: 600;
}

.account-card-body {
    padding: 1.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0.5rem;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

/* Refund Header */
.refund-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    color: white;
}

.refund-header h1 {
    color: white;
    font-weight: 700;
}

.text-white-80 {
    color: rgba(255, 255, 255, 0.9);
}

.refund-deadline {
    background: rgba(255, 255, 255, 0.15);
    padding: 1rem;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.deadline-timer {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-top: 0.25rem;
}

/* Order Summary */
.order-summary {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #F3F4F6;
    color: #4B5563;
}

.summary-row.highlight {
    background: #F9FAFB;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    margin-top: 0.5rem;
    border-bottom: none;
    font-size: 1.1rem;
}

/* Products List */
.products-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 12px;
    border: 1px solid #F3F4F6;
    transition: all 0.2s ease;
    gap: 1rem;
}

.product-item:hover {
    background: white;
    border-color: rgba(102, 126, 234, 0.3);
}

.product-item.selected {
    border-color: #667eea;
    background: linear-gradient(to right, rgba(102, 126, 234, 0.02), white);
}

.product-item.already-refunded {
    opacity: 0.7;
    background: #F3F4F6;
}

.product-checkbox {
    flex-shrink: 0;
}

.product-checkbox .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #D1D5DB;
    border-radius: 6px;
}

.product-checkbox .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.product-image {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #F3F4F6;
    background: white;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F3F4F6;
    color: #9CA3AF;
}

.product-details {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-size: 0.9375rem;
    margin-bottom: 0.25rem;
    display: flex;
    flex-direction: column;
}

.product-name small {
    font-size: 0.75rem;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.current-price {
    font-weight: 600;
    color: #1F2937;
}

.quantity-label {
    color: #6B7280;
    font-size: 0.875rem;
}

.product-quantity {
    flex-shrink: 0;
    width: 100px;
}

.product-quantity .form-select-sm {
    padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.product-total {
    flex-shrink: 0;
    text-align: right;
    min-width: 100px;
}

.item-total {
    font-size: 1rem;
    color: #1F2937;
}

.product-status {
    flex-shrink: 0;
}

/* Selected Total */
.selected-total {
    padding: 1.25rem;
    background: #F9FAFB;
    border-radius: 12px;
    border: 1px solid #F3F4F6;
}

.total-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

/* Reasons Grid */
.reasons-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.reason-option {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.reason-option:hover {
    border-color: rgba(102, 126, 234, 0.5);
    background: #F9FAFB;
}

.reason-option.selected {
    border-color: #667eea;
    background: linear-gradient(to right, rgba(102, 126, 234, 0.05), white);
}

.reason-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

.reason-icon.defective {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.reason-icon.wrong {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.reason-icon.damaged {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.reason-icon.not-satisfied {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.reason-icon.other {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

.reason-content {
    flex: 1;
}

.reason-content h6 {
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
}

.reason-check {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    color: #667eea;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.reason-option.selected .reason-check {
    opacity: 1;
}

/* Attachments */
.attachments-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.attachment-item {
    position: relative;
}

.attachment-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 0.5rem;
    background: #F9FAFB;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.attachment-preview:hover {
    border-color: #667eea;
    background: #F3F4F6;
}

.attachment-preview i {
    font-size: 2rem;
    color: #6B7280;
    margin-bottom: 0.5rem;
}

.attachment-preview span {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.attachment-preview small {
    font-size: 0.75rem;
    color: #6B7280;
    margin-top: 0.25rem;
}

.attachment-preview-container {
    margin-top: 0.5rem;
}

.file-preview {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    position: relative;
}

.file-preview img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
}

.file-preview.pdf {
    background: #FEF2F2;
    border-color: #FECACA;
}

.file-preview.pdf i {
    font-size: 24px;
    color: #EF4444;
    margin-right: 0.5rem;
}

.file-name {
    font-size: 0.75rem;
    color: #374151;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btn-remove {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #FEE2E2;
    border: none;
    color: #EF4444;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-left: 0.5rem;
}

.btn-remove:hover {
    background: #EF4444;
    color: white;
}

/* Sidebar */
.sticky-sidebar {
    position: sticky;
    top: 90px;
}

.refund-summary {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    color: #4B5563;
}

.summary-item.border-top {
    border-top: 1px solid #F3F4F6;
    padding-top: 1rem;
    margin-top: 0.5rem;
}

/* Policy List */
.policy-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.policy-list li {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    padding: 0.375rem 0;
    font-size: 0.875rem;
    color: #4B5563;
}

.policy-list li i {
    width: 16px;
    font-size: 0.875rem;
}

/* Timeline Steps */
.timeline-steps {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.step {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 8px;
}

.step-number {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    margin-right: 0.75rem;
}

.step-label {
    flex: 1;
    font-size: 0.875rem;
    color: #1F2937;
}

.step-duration {
    font-size: 0.75rem;
    color: #6B7280;
    font-weight: 500;
}

/* Help Card */
.help-card {
    background: #F9FAFB;
    border: 1px solid #F3F4F6;
    border-radius: 12px;
    padding: 1.25rem;
}

.help-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.help-content {
    flex: 1;
}

/* Existing Refunds */
.existing-refunds {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.existing-refund-item {
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 10px;
    border: 1px solid #F3F4F6;
    transition: all 0.2s ease;
}

.existing-refund-item:hover {
    background: white;
    border-color: rgba(102, 126, 234, 0.3);
}

/* Refund Status Badges */
.refund-status {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-under_review {
    background: #DBEAFE;
    color: #1E40AF;
}

.status-approved {
    background: #D1FAE5;
    color: #065F46;
}

.status-processing {
    background: #E0E7FF;
    color: #3730A3;
}

.status-completed {
    background: #D1FAE5;
    color: #065F46;
}

.status-rejected {
    background: #FEE2E2;
    color: #991B1B;
}

.status-cancelled {
    background: #F3F4F6;
    color: #4B5563;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8, #6b4390);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.3);
}

.btn-outline-primary {
    border: 1px solid rgba(102, 126, 234, 0.3);
    color: #667eea;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 1px solid #D1D5DB;
    color: #6B7280;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-secondary:hover {
    background: #6B7280;
    border-color: #6B7280;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-warning {
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #F59E0B;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-warning:hover {
    background: #F59E0B;
    border-color: #F59E0B;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-danger {
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #EF4444;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-danger:hover {
    background: #EF4444;
    border-color: #EF4444;
    color: white;
    transform: translateY(-1px);
}

/* Form Controls */
.form-control, .form-select {
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    padding: 0.625rem 1rem;
    transition: all 0.2s ease;
    font-size: 0.9375rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #4B5563;
    margin-bottom: 0.375rem;
}

/* Alerts */
.alert {
    border-radius: 12px;
    border: none;
    padding: 1.25rem;
}

.alert-warning {
    background: linear-gradient(135deg, #FEF3C7, #FFFBEB);
    border-left: 4px solid #F59E0B;
    color: #92400E;
}

.alert-danger {
    background: linear-gradient(135deg, #FEE2E2, #FEF2F2);
    border-left: 4px solid #EF4444;
    color: #991B1B;
}

.alert-success {
    background: linear-gradient(135deg, #D1FAE5, #ECFDF5);
    border-left: 4px solid #10B981;
    color: #065F46;
}

.alert-info {
    background: linear-gradient(135deg, #DBEAFE, #EFF6FF);
    border-left: 4px solid #3B82F6;
    color: #1E40AF;
}

/* Badge */
.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.75rem;
}

.badge.bg-primary {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    color: white;
}

.badge.bg-success {
    background: #10B981 !important;
    color: white;
}

.badge.bg-warning {
    background: #F59E0B !important;
    color: white;
}

/* Responsive */
@media (max-width: 992px) {
    .sticky-sidebar {
        position: static;
    }

    .reasons-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .refund-header .row {
        flex-direction: column;
    }

    .refund-header .col-md-8,
    .refund-header .col-md-4 {
        width: 100%;
    }

    .refund-deadline {
        margin-top: 1rem;
        text-align: left;
    }

    .product-item {
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .product-checkbox {
        order: 1;
    }

    .product-image {
        order: 2;
    }

    .product-details {
        order: 3;
        width: calc(100% - 180px);
    }

    .product-quantity {
        order: 4;
        width: 100px;
    }

    .product-total {
        order: 5;
        width: 100%;
        text-align: left;
        margin-top: 0.5rem;
        padding-left: 2rem;
    }

    .attachments-grid {
        grid-template-columns: 1fr;
    }

    .attachment-item {
        width: 100%;
    }

    .existing-refund-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }

    .existing-refund-item .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .product-details {
        width: 100% !important;
        margin-left: 0 !important;
    }

    .product-quantity {
        width: 100%;
    }

    .product-quantity select {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== PRODUCT SELECTION =====
    const productCheckboxes = document.querySelectorAll('.product-select');
    const quantitySelects = document.querySelectorAll('.quantity-select');
    const selectedCountSpan = document.getElementById('selectedCount');
    const sidebarSelectedCount = document.getElementById('sidebarSelectedCount');
    const totalRefundAmount = document.getElementById('totalRefundAmount');
    const sidebarSubtotal = document.getElementById('sidebarSubtotal');
    const sidebarTax = document.getElementById('sidebarTax');
    const sidebarTotal = document.getElementById('sidebarTotal');

    // Tax rate (20%)
    const TAX_RATE = 0.20;

    function updateSelection() {
        let selectedItems = 0;
        let subtotal = 0;

        productCheckboxes.forEach(checkbox => {
            const productItem = checkbox.closest('.product-item');
            if (!productItem) return;

            const quantitySelect = productItem.querySelector('.quantity-select');
            const itemTotalSpan = productItem.querySelector('.item-total');

            if (checkbox.checked) {
                selectedItems++;
                const price = parseFloat(checkbox.dataset.price) || 0;
                const quantity = parseInt(quantitySelect?.value) || 1;
                const total = price * quantity;
                subtotal += total;

                // Update item total display
                if (itemTotalSpan) {
                    itemTotalSpan.textContent = total.toFixed(2).replace('.', ',') + '€';
                }

                // Enable quantity select
                if (quantitySelect) {
                    quantitySelect.disabled = false;
                }
                productItem.classList.add('selected');
            } else {
                // Disable quantity select
                if (quantitySelect) {
                    quantitySelect.disabled = true;
                }
                productItem.classList.remove('selected');

                // Reset item total
                if (itemTotalSpan) {
                    itemTotalSpan.textContent = '0,00€';
                }
            }
        });

        // Calculate tax and total
        const tax = subtotal * TAX_RATE;
        const total = subtotal + tax;

        // Update UI with French formatting
        if (selectedCountSpan) {
            selectedCountSpan.textContent = `${selectedItems} article${selectedItems > 1 ? 's' : ''} sélectionné${selectedItems > 1 ? 's' : ''}`;
        }
        if (sidebarSelectedCount) sidebarSelectedCount.textContent = selectedItems;
        if (sidebarSubtotal) sidebarSubtotal.textContent = subtotal.toFixed(2).replace('.', ',') + '€';
        if (sidebarTax) sidebarTax.textContent = tax.toFixed(2).replace('.', ',') + '€';
        if (sidebarTotal) sidebarTotal.textContent = total.toFixed(2).replace('.', ',') + '€';
        if (totalRefundAmount) totalRefundAmount.textContent = total.toFixed(2).replace('.', ',') + '€';
    }

    // Add event listeners
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    quantitySelects.forEach(select => {
        select.addEventListener('change', updateSelection);
    });

    // ===== REASON SELECTION =====
    const reasonOptions = document.querySelectorAll('.reason-option');
    const reasonTypeInput = document.getElementById('reasonType');

    if (reasonOptions.length > 0 && reasonTypeInput) {
        reasonOptions.forEach(option => {
            option.addEventListener('click', function() {
                reasonOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                reasonTypeInput.value = this.dataset.reason;
            });
        });
    }

    // ===== CHARACTER COUNTER =====
    const reasonDetails = document.getElementById('reason_details');
    const charCounter = document.getElementById('charCounter');

    if (reasonDetails && charCounter) {
        reasonDetails.addEventListener('input', function() {
            const remaining = 500 - this.value.length;
            charCounter.textContent = remaining + ' caractères restants';
            charCounter.style.color = remaining < 50 ? '#EF4444' : '#6B7280';
        });
    }

    // ===== FILE ATTACHMENTS =====
    function handleFileAttachment(inputId, previewId) {
        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewId);

        if (!input || !previewContainer) return;

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Erreur', 'Le fichier ne doit pas dépasser 5MB', 'error');
                this.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                Swal.fire('Erreur', 'Type de fichier non supporté. Utilisez JPG, PNG ou PDF.', 'error');
                this.value = '';
                return;
            }

            // Show preview
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="file-preview">
                            <img src="${e.target.result}" alt="Aperçu">
                            <span class="file-name">${file.name}</span>
                            <button type="button" class="btn-remove" onclick="removeFile('${inputId}', '${previewId}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = `
                    <div class="file-preview pdf">
                        <i class="fas fa-file-pdf"></i>
                        <span class="file-name">${file.name}</span>
                        <button type="button" class="btn-remove" onclick="removeFile('${inputId}', '${previewId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            }
        });
    }

    // Initialize file handlers
    handleFileAttachment('attachment1', 'attachmentPreview1');
    handleFileAttachment('attachment2', 'attachmentPreview2');
    handleFileAttachment('attachment3', 'attachmentPreview3');

    // ===== FORM SUBMISSION =====
    const refundForm = document.getElementById('refundForm');
    const submitBtn = document.getElementById('submitBtn');

    if (refundForm && submitBtn) {
        refundForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validation
            const selectedProducts = document.querySelectorAll('.product-select:checked');
            if (selectedProducts.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Aucun article sélectionné',
                    text: 'Veuillez sélectionner au moins un article à rembourser.'
                });
                return;
            }

            if (!reasonTypeInput || !reasonTypeInput.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Raison non spécifiée',
                    text: 'Veuillez sélectionner une raison pour le remboursement.'
                });
                return;
            }

            if (!reasonDetails || !reasonDetails.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Détails manquants',
                    text: 'Veuillez décrire votre demande en détail.'
                });
                return;
            }

            if (reasonDetails.value.length < 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'Description trop courte',
                    text: 'Veuillez fournir plus de détails (minimum 10 caractères).'
                });
                return;
            }

            // Confirmation dialog
            const total = sidebarTotal ? sidebarTotal.textContent : '0,00€';
            const count = selectedProducts.length;

            Swal.fire({
                title: 'Confirmer la demande',
                html: `
                    <p>Vous êtes sur le point de soumettre une demande de remboursement pour :</p>
                    <div class="alert alert-info mt-3">
                        <strong>${count} article${count > 1 ? 's' : ''}</strong><br>
                        <span class="fw-bold text-primary">${total}</span>
                    </div>
                    <p class="small text-muted mt-3">Cette demande sera examinée par notre équipe sous 24-48h.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, soumettre',
                cancelButtonText: 'Vérifier à nouveau',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement en cours...';

                    // Create FormData
                    const formData = new FormData(refundForm);

                    // Submit via AJAX
                    fetch(refundForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Demande soumise !',
                                html: `
                                    <p>Votre demande de remboursement a été enregistrée.</p>
                                    <div class="alert alert-success mt-3">
                                        <strong>Référence :</strong> #${data.refund_id}<br>
                                        <strong>Montant :</strong> ${data.amount}€
                                    </div>
                                    <p class="small">Vous recevrez un email de confirmation.</p>
                                `,
                                confirmButtonText: 'Voir le suivi'
                            }).then(() => {
                                window.location.href = data.redirect_url || '{{ route("user.refunds") }}';
                            });
                        } else {
                            throw new Error(data.error || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: error.message
                        });
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Soumettre la demande';
                    });
                }
            });
        });
    }
});

// Global function for removing files
window.removeFile = function(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (input) input.value = '';
    if (preview) preview.innerHTML = '';
};
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection
