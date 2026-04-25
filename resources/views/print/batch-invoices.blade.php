<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factures Batch - {{ date('d/m/Y') }}</title>
    <style>

    @import url('https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap');

    /* Barcode container styles */
    .barcode-wrapper {
        text-align: center;
        margin: 10px 0;
        padding: 5px;
        background: #fff;
        border-radius: 4px;
    }

    .barcode-fallback {
        font-family: 'Libre Barcode 128', cursive;
        font-size: 40px;
        color: #000;
        margin: 5px 0;
        line-height: 1.2;
    }

    .barcode-number {
        font-family: 'Courier New', monospace;
        font-size: 10px;
        color: #666;
        letter-spacing: 2px;
        margin-top: 2px;
    }

    /* Ensure barcodes are visible in PDF */
    .invoice-barcode,
    .shipping-barcode-html,
    .packing-barcode,
    .delivery-barcode {
        display: inline-block;
        max-width: 100%;
    }

        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .batch-header {
            text-align: center;
            border-bottom: 3px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-container {
            page-break-after: always;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #ddd;
        }

        .invoice-container:last-child {
            page-break-after: avoid;
            border-bottom: none;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .invoice-info {
            text-align: right;
            flex: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .summary {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .grand-total {
            font-weight: bold;
            color: #d4af37;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
            text-align: center;
        }

        .barcode {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .barcode-container {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 40px;
            margin-bottom: 5px;
        }

        .barcode-line {
            display: inline-block;
            background: #000;
            margin: 0 1px;
        }

        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            letter-spacing: 2px;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 10px;
        }

        .status-delivered { background: #d4edda; color: #155724; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-ordered { background: #fff3cd; color: #856404; }
        .status-canceled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="batch-header">
        <h1 style="color: #d4af37; margin-bottom: 5px;">FACTURES PAR LOT</h1>
        <p style="margin: 5px 0;">Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p style="margin: 5px 0;">Nombre de factures: {{ count($orders) }}</p>
        <p style="margin: 5px 0;">{{ $settings->site_name ?? 'Bijouterie' }}</p>
    </div>

    @foreach($orders as $index => $order)
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <h2 style="color: #d4af37; margin: 0 0 10px 0;">{{ $settings->site_name ?? 'Bijouterie' }}</h2>
                <p style="margin: 2px 0;">{{ $settings->site_address ?? '' }}</p>
                <p style="margin: 2px 0;">Tél: {{ $settings->site_phone ?? '' }}</p>
            </div>

            <div class="invoice-info">
                <h3 style="margin: 0 0 10px 0; color: #333;">FACTURE</h3>
                <p style="margin: 2px 0;"><strong>N°:</strong> {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p style="margin: 2px 0;"><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <p style="margin: 2px 0;">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $order->status }}">
                        @if($order->status == 'delivered') Livré
                        @elseif($order->status == 'confirmed') Confirmé
                        @elseif($order->status == 'canceled') Annulé
                        @else Commandé @endif
                    </span>
                </p>

                <!-- Professional Barcode -->
                <div class="barcode-wrapper">
        @if(isset($order->barcodeHTML) && !empty($order->barcodeHTML))
            {!! $order->barcodeHTML !!}
        @elseif(isset($order->fallbackBarcode) && !empty($order->fallbackBarcode))
            {!! $order->fallbackBarcode !!}
        @else
            <div class="barcode-fallback">
                *{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}*
            </div>
        @endif
    </div>
            </div>
        </div>

        <div style="margin: 15px 0;">
            <p><strong>Client:</strong> {{ $order->name }}</p>
            <p><strong>Adresse:</strong> {{ $order->address }}, {{ $order->city }} {{ $order->zip }}</p>
            <p><strong>Tél:</strong> {{ $order->phone }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Produit</th>
                    <th width="60" style="text-align: center;">Qté</th>
                    <th width="80" style="text-align: right;">Prix</th>
                    <th width="80" style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $itemIndex => $item)
                    <tr>
                        <td>{{ $itemIndex + 1 }}</td>
                        <td>{{ $item->product ? $item->product->name : 'Produit supprimé' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">
                            {{ number_format($item->price, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                        </td>
                        <td style="text-align: right;">
                            {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row">
                <span>Sous-total:</span>
                <span>{{ number_format($order->subtotal, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
            </div>

            @if($order->discount > 0)
            <div class="summary-row">
                <span>Remise:</span>
                <span>- {{ number_format($order->discount, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
            </div>
            @endif

            <div class="summary-row">
                <span>Livraison:</span>
                <span>{{ number_format($order->shipping, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
            </div>

            <div class="summary-row">
                <span>TVA ({{ $settings->tax_rate ?? 20 }}%):</span>
                <span>{{ number_format($order->tax, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
            </div>

            <div class="summary-row grand-total">
                <span>TOTAL TTC:</span>
                <span>{{ number_format($order->total, 2, ',', ' ') }} {{ $settings->currency_symbol ?? '€' }}</span>
            </div>
        </div>

        <div class="footer">
            <p>{{ $settings->site_name ?? 'Bijouterie' }} | Page {{ $index + 1 }} sur {{ count($orders) }}</p>
        </div>
    </div>
    @endforeach
</body>
</html>
