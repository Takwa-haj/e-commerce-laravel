<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étiquette d'Expédition #{{ $order->id }}</title>
    <style>
        @page {
            margin: 0;
            size: 4in 6in;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 10px;
            width: 4in;
            height: 6in;
            box-sizing: border-box;
        }

        .label-container {
            border: 2px solid #000;
            padding: 15px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .header {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
        }

        .company-address {
            font-size: 9px;
            text-align: center;
            margin: 3px 0;
        }

        .shipping-info {
            margin: 10px 0;
            flex-grow: 1;
        }

        .shipping-label {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background: #000;
            color: #fff;
            padding: 3px;
            margin: 5px 0;
        }

        .address-to {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px 0;
        }

        .address-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .barcode-section {
            text-align: center;
            margin: 10px 0;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 28px;
            margin: 5px 0;
        }

        .order-info {
            font-size: 9px;
            margin: 5px 0;
        }

        .tracking-info {
            background: #f5f5f5;
            padding: 8px;
            margin: 10px 0;
            border: 1px dashed #000;
        }

        .tracking-number {
            font-family: monospace;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
        }

        .delivery-instructions {
            font-size: 8px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .footer {
            font-size: 7px;
            text-align: center;
            margin-top: 10px;
            color: #666;
        }

        .warning {
            color: #d9534f;
            font-weight: bold;
        }

        .priority {
            background: #ffc107;
            color: #000;
            padding: 2px 5px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
        }

        .package-weight {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
        }

        .fragile {
            border: 2px solid #d9534f;
            color: #d9534f;
            padding: 3px;
            text-align: center;
            font-weight: bold;
            margin: 5px 0;
            transform: rotate(-2deg);
        }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="header">
            <div class="company-name">{{ $settings->site_name ?? 'BIJOUTERIE' }}</div>
            <div class="company-address">{{ $settings->site_address ?? '' }}</div>
            <div class="company-address">Tél: {{ $settings->site_phone ?? '' }}</div>
        </div>

        <div class="shipping-label">EXPÉDITION</div>

        <div class="priority">LIVRAISON STANDARD</div>

        @if(in_array($order->id, [1, 5, 10]))
        <div class="fragile">⚠ FRAGILE - NE PAS JETER</div>
        @endif

        <div class="address-to">
            <div class="address-title">À:</div>
            <div style="font-weight: bold;">{{ strtoupper($order->name) }}</div>
            <div>{{ $order->address }}</div>
            @if($order->locality)
                <div>{{ $order->locality }}</div>
            @endif
            <div>{{ $order->city }}, {{ $order->zip }}</div>
            <div>{{ $order->country }}</div>
            <div>Tél: {{ $order->phone }}</div>
        </div>

        <div class="shipping-info">
            <div class="barcode-section">
                <div class="barcode">
                    *{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}*
                </div>
                <div class="order-info">
                    Commande #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <div class="tracking-info">
                <div style="font-weight: bold; text-align: center; margin-bottom: 3px;">SUIVI</div>
                <div class="tracking-number">
                    @if($order->tracking_number)
                        {{ $order->tracking_number }}
                    @else
                        {{ strtoupper(substr(md5($order->id), 0, 12)) }}
                    @endif
                </div>
                <div style="text-align: center; font-size: 8px; margin-top: 3px;">
                    Scan pour suivre votre colis
                </div>
            </div>

            <div class="order-info">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <strong>Colis:</strong> 1/1
                    </div>
                    <div>
                        <strong>Poids:</strong> {{ rand(1, 5) }} kg
                    </div>
                    <div>
                        <strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d/m') }}
                    </div>
                </div>
                <div style="margin-top: 5px;">
                    <strong>Transporteur:</strong> {{ $order->carrier ?? 'STANDARD' }}
                </div>
                <div>
                    <strong>Service:</strong> GROUND
                </div>
            </div>
        </div>

        <div class="delivery-instructions">
            <div class="warning">NE PAS LAISSER SANS SIGNATURE</div>
            @if($order->landmark)
                <div><strong>Repère:</strong> {{ $order->landmark }}</div>
            @endif
            <div><strong>Heure:</strong> 9h-18h</div>
        </div>

        <div class="package-weight">
            {{ rand(1, 5) }} kg
        </div>

        <div class="footer">
            {{ $settings->site_name ?? 'Bijouterie' }} • www.{{ strtolower(str_replace(' ', '', $settings->site_name ?? 'bijouterie')) }}.com
        </div>
    </div>
</body>
</html>
