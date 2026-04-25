@extends('layouts.app')

@section('title', 'Paiement Sécurisé')

@section('content')
<style>
    .checkout-container {
        max-width: 500px;
        margin: 2rem auto;
        padding: 2rem;
        text-align: center;
    }
    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #d4af37;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 2rem auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <div class="checkout-container">
        <h2 class="mb-4">Paiement Sécurisé</h2>
        <p class="text-muted mb-4">Redirection vers Stripe Checkout...</p>

        <div class="loading-spinner"></div>

        <div id="stripe-checkout-container" class="mt-4"></div>

        <p class="text-muted small mt-4">
            <i class="bi bi-shield-check me-1"></i>
            Paiement 100% sécurisé par Stripe
        </p>
    </div>
</main>

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create checkout session via AJAX
        fetch('{{ route("stripe.checkout.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                // Redirect to Stripe Checkout
                window.location.href = data.url;
            } else if (data.error) {
                alert('Erreur: ' + data.error);
                window.location.href = '{{ route("cart.index") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue.');
            window.location.href = '{{ route("cart.index") }}';
        });
    });
</script>
@endsection
