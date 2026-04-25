<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .invoice-container { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #ddd; }
        .company-info { margin-bottom: 30px; }
        .invoice-info { margin-bottom: 30px; }
        .customer-info { margin-bottom: 30px; background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background: #f2f2f2; }
        .total-section { text-align: right; margin-top: 30px; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>{{ $settings->site_name ?? 'Votre Boutique' }}</h1>
            <h2>FACTURE #{{ $order->id }}</h2>
            <p>Date: {{ $order->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="company-info">
            <h3>{{ $settings->site_name ?? 'Votre Boutique' }}</h3>
            @if($settings->site_address)<p>{{ $settings->site_address }}</p>@endif
            @if($settings->site_phone)<p>Téléphone: {{ $settings->site_phone }}</p>@endif
            @if($settings->site_email)<p>Email: {{ $settings->site_email }}</p>@endif
        </div>

        <div class="customer-info">
            <h3>Client</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->address }}</p>
            @if($order->locality)<p>{{ $order->locality }}</p>@endif
            <p>{{ $order->zip }} {{ $order->city }}, {{ $order->country }}</p>
            <p>Téléphone: {{ $order->phone }}</p>
            @if($order->email)<p>Email: {{ $order->email }}</p>@endif
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produit' }}</td>
                    <td>{{ number_format($item->price, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <h3>Récapitulatif</h3>
            <p>Sous-total: {{ number_format($order->subtotal, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
            <p>Taxes: {{ number_format($order->tax, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
            @if($order->discount > 0)
            <p>Réduction: -{{ number_format($order->discount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</p>
            @endif
            <h3>Total: {{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</h3>
        </div>

        <div class="footer">
            <p>{{ $settings->site_name ?? 'Votre Boutique' }} - {{ $settings->site_address ?? '' }}</p>
            <p>Facture générée le {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
