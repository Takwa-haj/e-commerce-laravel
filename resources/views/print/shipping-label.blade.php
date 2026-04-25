{{-- resources/views/admin/print/shipping-label.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label #{{ $order->id }}</title>
    {!! $barcodeStyles !!}
    <style>
        @page {
            margin: 0;
            size: 4in 6in;
        }

        body {
            margin: 0;
            padding: 0;
            width: 4in;
            height: 6in;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #fff;
        }

        .label {
            width: 4in;
            height: 6in;
            padding: 0.25in;
            box-sizing: border-box;
            border: 2px solid #000;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .from-section {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .to-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #000;
            background: #fff;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .info-item {
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
        }

        .barcode-section {
            margin: 15px 0;
            padding: 10px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-align: center;
        }

        .tracking-number {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            padding: 5px;
            background: #f8f9fa;
            border: 1px dashed #6c757d;
        }

        .footer {
            margin-top: auto;
            font-size: 8px;
            text-align: center;
            color: #6c757d;
            border-top: 1px dashed #dee2e6;
            padding-top: 10px;
        }

        .fragile {
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            transform: rotate(-2deg);
        }
    </style>
</head>
<body>
    <div class="label">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $settings->site_name ?? 'SHIPPING COMPANY' }}</div>
            <div style="font-size: 9px;">{{ $settings->site_address ?? '' }}</div>
        </div>

        <!-- From Address -->
        <div class="from-section">
            <div class="section-title">FROM:</div>
            <div>{{ $settings->site_name ?? 'Company Name' }}</div>
            <div>{{ $settings->site_address ?? 'Address' }}</div>
            <div>Tel: {{ $settings->site_phone ?? 'Phone' }}</div>
        </div>

        <!-- To Address -->
        <div class="to-section">
            <div class="section-title">SHIP TO:</div>
            <div style="font-size: 14px; font-weight: bold;">{{ strtoupper($order->name) }}</div>
            <div>{{ $order->address }}</div>
            @if($order->locality)<div>{{ $order->locality }}</div>@endif
            <div>{{ $order->zip }} {{ $order->city }}</div>
            <div>{{ $order->country }}</div>
            <div style="margin-top: 5px;">Tel: {{ $order->phone }}</div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Order #</div>
                <div>{{ $order->id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Date</div>
                <div>{{ $order->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Weight</div>
                <div>{{ rand(1, 5) }} kg</div>
            </div>
            <div class="info-item">
                <div class="info-label">Service</div>
                <div>Standard</div>
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode-section">
            <div class="barcode-title">SHIPPING BARCODE</div>
            <div class="barcode-wrapper">
                @if(isset($barcodes['shipping']['html']))
                    {!! $barcodes['shipping']['html'] !!}
                @else
                    <div class="barcode-fallback">*{{ $barcodes['shipping']['code'] }}*</div>
                @endif
            </div>
            <div class="barcode-code">{{ $barcodes['shipping']['code'] }}</div>
        </div>

        <!-- Tracking Number -->
        <div class="tracking-number">
            {{ strtoupper(substr(md5($order->id), 0, 12)) }}
        </div>

        <!-- Fragile Warning (random for demo) -->
        @if($order->id % 3 == 0)
        <div class="fragile">⚠ FRAGILE - HANDLE WITH CARE ⚠</div>
        @endif

        <!-- Footer -->
        <div class="footer">
            {{ $settings->site_name ?? 'Company' }} | {{ date('Y') }} | www.{{ strtolower(str_replace(' ', '', $settings->site_name ?? 'company')) }}.com
        </div>
    </div>
</body>
</html>
