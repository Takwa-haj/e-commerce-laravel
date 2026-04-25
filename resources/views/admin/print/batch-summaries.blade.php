<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résumés de commandes par lot</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .order-container {
            margin-bottom: 40px;
            page-break-after: always;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }

        .order-container:last-child {
            page-break-after: auto;
            margin-bottom: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #666;
        }

        .info-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .card-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 10px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .products-table th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #454d55;
        }

        .products-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }

        .totals-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #333;
            border-radius: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .grand-total {
            font-weight: bold;
            font-size: 12px;
            color: #333;
            padding-top: 8px;
            margin-top: 8px;
            border-top: 2px solid #333;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Batch Header -->
    <div style="text-align: center; margin-bottom: 30px; border-bottom: 3px double #333; padding-bottom: 15px;">
        <h1 style="font-size: 22px;">LOT DE RÉSUMÉS DE COMMANDES</h1>
        <div style="color: #666;">
            Généré le {{ $date }} | {{ $orders->count() }} commandes
        </div>
    </div>

    @foreach($orders as $order)
    <div class="order-container">
        <!-- Header -->
        <div class="header">
            <h1>RÉSUMÉ DE COMMANDE #{{ $order->id }}</h1>
            <h2>{{ $order->created_at->format('d/m/Y H:i') }}</h2>
        </div>

        <!-- Information Cards -->
        <div class="info-cards">
            <div class="card">
                <div class="card-title">INFORMATIONS CLIENT</div>
                <div class="info-grid">
                    <div class="info-label">Nom:</div>
                    <div>{{ $order->name }}</div>

                    <div class="info-label">Tél:</div>
                    <div>{{ $order->phone }}</div>

                    <div class="info-label">Email:</div>
                    <div>{{ $order->user->email ?? 'Non renseigné' }}</div>

                    <div class="info-label">Adresse:</div>
                    <div>
                        {{ $order->address }}<br>
                        {{ $order->zip }} {{ $order->city }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">INFORMATIONS COMMANDE</div>
                <div class="info-grid">
                    <div class="info-label">N°:</div>
                    <div>{{ $order->id }}</div>

                    <div class="info-label">Date:</div>
                    <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>

                    <div class="info-label">Statut:</div>
                    <div>
                        @if($order->status == 'delivered')
                            <span style="color: #28a745;">Livré</span>
                        @elseif($order->status == 'confirmed')
                            <span style="color: #17a2b8;">Confirmé</span>
                        @elseif($order->status == 'canceled')
                            <span style="color: #dc3545;">Annulé</span>
                        @else
                            <span style="color: #ffc107;">Commandé</span>
                        @endif
                    </div>

                    <div class="info-label">Articles:</div>
                    <div>{{ $order->orderItems->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="products-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="60%">Produit</th>
                    <th width="15%" class="text-center">Qté</th>
                    <th width="20%" class="text-right">Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $itemIndex => $item)
                <tr>
                    <td>{{ $itemIndex + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity, 2) }}€</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>Sous-total:</span>
                <span>{{ number_format($order->subtotal, 2) }}€</span>
            </div>

            @if($order->tax > 0)
            <div class="total-row">
                <span>TVA:</span>
                <span>{{ number_format($order->tax, 2) }}€</span>
            </div>
            @endif

            <div class="total-row grand-total">
                <span>Total:</span>
                <span>{{ number_format($order->total, 2) }}€</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ $settings->site_name ?? 'Bijoux' }} - Commande #{{ $order->id }}</p>
            <p>Page {{ $loop->iteration }} sur {{ $orders->count() }}</p>
        </div>
    </div>
    @endforeach
</body>
</html>
