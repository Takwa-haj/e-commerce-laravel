@extends('layouts.app')
@section('content')
<style>
    .payment-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
    }
    .payment-card {
        background: var(--surface);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .payment-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .payment-amount {
        font-size: 32px;
        font-weight: bold;
        color: #d4af37;
        text-align: center;
        margin: 20px 0;
    }
    .stripe-btn {
        background: #6772E5;
        color: white;
        border: none;
        padding: 16px;
        font-weight: 600;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.3s;
    }
    .stripe-btn:hover {
        background: #5469D4;
    }
    .payment-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
    }
    .security-badge {
        text-align: center;
        margin-top: 20px;
        color: #6c757d;
        font-size: 14px;
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="container">
        <div class="payment-container">
            <div class="payment-card">
                <div class="payment-header">
                    <h2>Paiement Sécurisé</h2>
                    <p class="text-muted">Finalisez votre commande</p>
                </div>

                <div class="payment-info">
                    <div class="row">
                        <div class="col-6">
                            <small>Commande</small>
                            <p class="mb-0"><strong>#{{ $order->id }}</strong></p>
                        </div>
                        <div class="col-6 text-end">
                            <small>Date</small>
                            <p class="mb-0">{{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="payment-amount">
                    {{ number_format($order->total, 2, ',', ' ') }} {{ $order->currency_symbol ?? '€' }}
                </div>

                <button id="payButton" class="stripe-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                        <path d="M13.826 10.745c0 .637-.36 1.02-.985 1.02h-.856v-2.04h.856c.626 0 .985.383.985 1.02zm-1.97 3.926h.856c1.134 0 1.82-.702 1.82-1.89 0-1.188-.686-1.89-1.82-1.89h-.856v3.78z"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1zM5 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3H5z"/>
                        <path d="M15.274 13.08c.11.401.17.83.17 1.31 0 1.715-1.126 2.87-2.873 2.87h-3.32V8h3.32c1.657 0 2.724 1.064 2.724 2.476 0 .822-.368 1.505-1.021 1.86v.044z"/>
                    </svg>
                    Payer avec Stripe
                </button>

                <div class="security-badge">
                    <i class="bi bi-shield-check me-1"></i>
                    Paiement sécurisé SSL 256-bit
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('cart.checkout') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.getElementById('payButton').addEventListener('click', function() {
    const button = this;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';

    fetch('{{ route("stripe.create.checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_id: {{ $order->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Erreur: ' + data.error);
            button.disabled = false;
            button.innerHTML = 'Payer avec Stripe';
            return;
        }

        // Redirect to Stripe Checkout
        window.location.href = data.url;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue. Veuillez réessayer.');
        button.disabled = false;
        button.innerHTML = 'Payer avec Stripe';
    });
});
</script>
@endpush
