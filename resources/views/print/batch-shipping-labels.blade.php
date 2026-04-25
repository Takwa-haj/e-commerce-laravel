<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étiquettes d'expédition par lot</title>
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            background: #fff;
            padding: 10px;
        }

        .labels-grid {
            display: grid;
            grid-template-columns: repeat(2, 4in);
            gap: 10px;
        }

        .label-container {
            width: 4in;
            height: 6in;
            border: 1px solid #000;
            padding: 15px;
            position: relative;
            page-break-inside: avoid;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .from-section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .to-section {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #000;
            background-color: #f8f9fa;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 10px;
            color: #333;
        }

        .shipping-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            background-color: #fff;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            font-size: 9px;
            color: #666;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background: #fff;
        }

        .barcode-bars {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 40px;
            margin: 5px 0;
        }

        .barcode-bar {
            display: inline-block;
            background: #000;
            margin: 0 1px;
        }

        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            letter-spacing: 2px;
            font-weight: bold;
            margin: 5px 0;
        }

        .barcode-label {
            font-size: 9px;
            color: #666;
        }

        .warning {
            font-size: 8px;
            color: #d32f2f;
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #ccc;
        }

        .stamp {
            position: absolute;
            top: 15px;
            right: 15px;
            border: 2px solid #000;
            padding: 5px;
            transform: rotate(15deg);
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="labels-grid">
        @foreach($orders as $order)
        <div class="label-container">
            <!-- Company Stamp -->
            <div class="stamp">
                {{ $settings->site_name ?? 'Bijoux' }}
            </div>

            <!-- Header -->
            <div class="header">
                <div class="company-name">{{ $settings->site_name ?? 'Bijoux' }}</div>
                @if($settings->site_phone)
                <div style="font-size: 9px;">{{ $settings->site_phone }}</div>
                @endif
            </div>

            <!-- From Address -->
            <div class="from-section">
                <div class="section-title">EXPÉDITEUR</div>
                <div style="font-size: 10px;">
                    {{ $settings->site_name ?? 'Bijoux' }}<br>
                    @if($settings->site_address)
                    {{ $settings->site_address }}<br>
                    @endif
                    @if($settings->site_phone)
                    Tél: {{ $settings->site_phone }}
                    @endif
                </div>
            </div>

            <!-- To Address -->
            <div class="to-section">
                <div class="section-title">DESTINATAIRE</div>
                <div style="font-size: 12px; font-weight: bold;">
                    {{ $order->name }}
                </div>
                <div style="font-size: 11px;">
                    {{ $order->address }}<br>
                    @if($order->locality)
                    {{ $order->locality }}<br>
                    @endif
                    {{ $order->zip }} {{ $order->city }}<br>
                    {{ $order->country }}
                </div>
                <div style="font-size: 10px; margin-top: 5px;">
                    <strong>Tél:</strong> {{ $order->phone }}
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="shipping-info">
                <div class="info-item">
                    <div class="info-label">N° COMMANDE</div>
                    <div style="font-weight: bold; font-size: 12px;">{{ $order->id }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">DATE</div>
                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">COLIS</div>
                    <div>1/1</div>
                </div>

                @if($order->tracking_number)
                <div class="info-item" style="grid-column: span 2;">
                    <div class="info-label">N° DE SUIVI</div>
                    <div style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $order->tracking_number }}</div>
                </div>
                @endif
            </div>

            <!-- Barcode -->
            <div class="barcode">
                @if(isset($order->barcodeHTML))
                    {!! $order->barcodeHTML !!}
                @else
                    <!-- Fallback barcode -->
                    <div class="barcode-bars">
                        @php
                            $barcodeNumber = str_pad($order->id, 8, '0', STR_PAD_LEFT);
                            $digits = str_split($barcodeNumber);
                        @endphp

                        <!-- Start character -->
                        <div class="barcode-bar" style="width: 3px; height: 40px; margin: 0 2px;"></div>

                        @foreach($digits as $digit)
                            @php
                                $digitValue = (int)$digit;
                                $barHeight = 20 + ($digitValue * 4);
                            @endphp

                            @for($i = 0; $i < 4; $i++)
                                @php
                                    $currentHeight = $barHeight - ($i * 2);
                                @endphp
                                <div class="barcode-bar" style="width: 2px; height: {{ $currentHeight }}px; margin: 0 1px;"></div>
                            @endfor

                            <!-- Separator -->
                            <div class="barcode-bar" style="width: 1px; height: 10px; background: #fff; margin: 0 1px;"></div>
                        @endforeach

                        <!-- End character -->
                        <div class="barcode-bar" style="width: 3px; height: 40px; margin: 0 2px;"></div>
                    </div>
                    <div class="barcode-number">{{ $barcodeNumber }}</div>
                @endif
                <div class="barcode-label">Commande #{{ $order->id }}</div>
            </div>

            <!-- Final Warning -->
            <div class="warning">
                <strong>IMPORTANT:</strong> Vérifier le contenu avant signature.
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
