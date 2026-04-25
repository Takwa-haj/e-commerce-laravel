@extends('layouts.app')

@section('content')
<style>
    .payment-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    .payment-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    .payment-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    .payment-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
    }
    .order-summary {
        background: #f9fafb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .order-summary h4 {
        margin-bottom: 1rem;
        color: #374151;
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .order-total {
        font-size: 1.25rem;
        font-weight: 600;
        color: #059669;
    }
    #payment-form {
        margin-top: 2rem;
    }
    .card-element-container {
        padding: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        margin-bottom: 1.5rem;
    }
    #card-errors {
        color: #dc2626;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    .payment-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn-pay {
        flex: 1;
        background: linear-gradient(to right, #d4af37, #b8941f);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-pay:hover:not(:disabled) {
        background: linear-gradient(to right, #b8941f, #a3821b);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
    }
    .btn-pay:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .btn-cancel {
        background: #6b7280;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #4b5563;
    }
    .payment-processing {
        text-align: center;
        padding: 2rem;
    }
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #d4af37;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .payment-methods {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .payment-method {
        flex: 1;
        text-align: center;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method.active {
        border-color: #d4af37;
        background: #fefce8;
    }
    .payment-method i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #666;
    }
    .payment-method.active i {
        color: #d4af37;
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <div class="payment-container">
        <div class="payment-card">
            <div class="payment-header">
                <h1 class="payment-title">Paiement Sécurisé</h1>
                <p class="text-muted">Commande #{{ $order->id }}</p>
            </div>

            <div class="order-summary">
                <h4>Récapitulatif de la commande</h4>
                <div class="order-item">
                    <span>Sous-total</span>
                    <span>{{ $order->currency_symbol ?? '€' }}{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="order-item">
                    <span>Réduction</span>
                    <span class="text-danger">-{{ $order->currency_symbol ?? '€' }}{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="order-item">
                    <span>Livraison</span>
                    <span>{{ $order->currency_symbol ?? '€' }}{{ number_format($order->shipping, 2) }}</span>
                </div>
                <div class="order-item">
                    <span>TVA ({{ $order->tax_rate ?? 0 }}%)</span>
                    <span>{{ $order->currency_symbol ?? '€' }}{{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="order-item order-total">
                    <span>Total</span>
                    <span>{{ $order->currency_symbol ?? '€' }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div id="payment-methods" class="payment-methods">
                <div class="payment-method active" data-method="card">
                    <i class="bi bi-credit-card"></i>
                    <div>Carte bancaire</div>
                </div>
                <div class="payment-method" data-method="apple_pay">
                    <i class="bi bi-apple"></i>
                    <div>Apple Pay</div>
                </div>
                <div class="payment-method" data-method="google_pay">
                    <i class="bi bi-google"></i>
                    <div>Google Pay</div>
                </div>
            </div>

            <div id="card-payment-section">
                <div id="payment-processing" class="payment-processing" style="display: none;">
                    <div class="spinner"></div>
                    <p>Traitement du paiement...</p>
                </div>

                <form id="payment-form">
                    <div class="card-element-container">
                        <div id="card-element"></div>
                    </div>
                    <div id="card-errors" role="alert"></div>

                    <div class="payment-actions">
                        <button type="button" id="btn-cancel" class="btn-cancel">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </button>
                        <button type="submit" id="btn-pay" class="btn-pay">
                            <i class="bi bi-lock-fill me-2"></i>Payer {{ $order->currency_symbol ?? '€' }}{{ number_format($order->total, 2) }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i>
                    Paiement 100% sécurisé par Stripe. Vos données bancaires sont cryptées.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-2">
                    <img src="https://stripe.com/img/v3/home/twitter.png" alt="Stripe" height="30">
                    <img src="https://www.visa.com/dam/VCOM/regional/ve/romania/blogs/hero-image/visa-logo-800x450.jpg" alt="Visa" height="30">
                    <img src="https://brand.mastercard.com/content/dam/mccom/brandcenter/thumbnails/mastercard_vrt_rev_92px_2x.png" alt="Mastercard" height="30">
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        let elements;
        let paymentIntentClientSecret;

        // Initialize Stripe Elements
        initialize();

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('active');
                });
                this.classList.add('active');

                // Show/hide card payment section
                const methodType = this.dataset.method;
                if (methodType === 'card') {
                    document.getElementById('card-payment-section').style.display = 'block';
                } else {
                    document.getElementById('card-payment-section').style.display = 'none';
                    // Implement Apple/Google Pay here
                }
            });
        });

        async function initialize() {
            try {
                // Create Payment Intent
                const response = await fetch('{{ route("stripe.create.payment.intent") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: '{{ $order->id }}'
                    })
                });

                const { clientSecret, error } = await response.json();

                if (error) {
                    showError(error);
                    return;
                }

                paymentIntentClientSecret = clientSecret;

                // Initialize Elements
                const appearance = {
                    theme: 'stripe',
                    variables: {
                        colorPrimary: '#d4af37',
                        colorBackground: '#ffffff',
                        colorText: '#1f2937',
                        colorDanger: '#dc2626',
                        fontFamily: 'Inter, system-ui, sans-serif',
                        spacingUnit: '4px',
                        borderRadius: '8px'
                    }
                };

                elements = stripe.elements({
                    appearance,
                    clientSecret: paymentIntentClientSecret
                });

                // Create card element
                const cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#1f2937',
                            '::placeholder': {
                                color: '#9ca3af'
                            }
                        }
                    },
                    hidePostalCode: true
                });

                cardElement.mount('#card-element');

                // Handle real-time validation errors
                cardElement.on('change', function(event) {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });

                // Handle form submission
                const form = document.getElementById('payment-form');
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    await handlePayment();
                });

            } catch (error) {
                console.error('Initialization error:', error);
                showError('Erreur lors de l\'initialisation du paiement.');
            }
        }

        async function handlePayment() {
            const btnPay = document.getElementById('btn-pay');
            const btnCancel = document.getElementById('btn-cancel');
            const processingDiv = document.getElementById('payment-processing');

            // Show processing, disable buttons
            processingDiv.style.display = 'block';
            btnPay.disabled = true;
            btnCancel.disabled = true;
            btnPay.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement...';

            try {
                // Confirm payment
                const { error, paymentIntent } = await stripe.confirmCardPayment(
                    paymentIntentClientSecret,
                    {
                        payment_method: {
                            card: elements.getElement('card'),
                            billing_details: {
                                name: '{{ $order->name }}',
                                email: '{{ $order->user->email }}',
                                phone: '{{ $order->phone }}',
                                address: {
                                    line1: '{{ $order->address }}',
                                    city: '{{ $order->city }}',
                                    postal_code: '{{ $order->zip }}',
                                    country: '{{ $order->country }}'
                                }
                            }
                        },
                        return_url: '{{ route("cart.order.confirmation") }}'
                    }
                );

                if (error) {
                    throw new Error(error.message);
                }

                if (paymentIntent.status === 'succeeded') {
                    // Send confirmation to server
                    const response = await fetch('{{ route("stripe.process.payment") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_intent_id: paymentIntent.id,
                            order_id: '{{ $order->id }}'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Redirect to confirmation page
                        window.location.href = result.redirect;
                    } else {
                        throw new Error(result.error || 'Erreur lors du traitement du paiement.');
                    }
                }

            } catch (error) {
                console.error('Payment error:', error);
                showError(error.message);

                // Reset UI
                processingDiv.style.display = 'none';
                btnPay.disabled = false;
                btnCancel.disabled = false;
                btnPay.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Payer {{ $order->currency_symbol ?? "€" }}{{ number_format($order->total, 2) }}';
            }
        }

        // Cancel payment
        document.getElementById('btn-cancel').addEventListener('click', async function() {
            if (confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')) {
                try {
                    const response = await fetch('{{ route("stripe.cancel.payment") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: '{{ $order->id }}'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        window.location.href = result.redirect;
                    } else {
                        throw new Error(result.error || 'Erreur lors de l\'annulation.');
                    }
                } catch (error) {
                    showError(error.message);
                }
            }
        });

        function showError(message) {
            const errorDiv = document.getElementById('card-errors');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';

            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        // Check payment status periodically
        function checkPaymentStatus() {
            fetch('{{ route("stripe.check.payment.status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: '{{ $order->id }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'succeeded') {
                    window.location.href = '{{ route("cart.order.confirmation") }}';
                }
            });
        }

        // Check every 10 seconds
        setInterval(checkPaymentStatus, 10000);
    });
</script>
@endpush
