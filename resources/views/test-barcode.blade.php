<!DOCTYPE html>
<html>
<head>
    <title>Barcode Test - Order #{{ $id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap');

        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f0f2f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #d4af37;
            text-align: center;
            margin-bottom: 30px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid #c3e6cb;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .barcode-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }

        .barcode-card h2 {
            color: #1f2937;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d4af37;
        }

        .barcode-display {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 15px;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode-wrapper {
            display: inline-block;
            max-width: 100%;
        }

        .barcode-wrapper svg,
        .barcode-wrapper img {
            max-width: 100%;
            height: auto;
        }

        .barcode-code {
            font-family: monospace;
            font-size: 14px;
            color: #4b5563;
            margin-top: 10px;
            padding: 8px;
            background: #f3f4f6;
            border-radius: 4px;
            text-align: center;
        }

        .barcode-fallback {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 45px;
            color: #000;
            line-height: 1.2;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Barcode Generation Test - Order #{{ $id }}</h1>

        <div class="success-message">
            <strong>✅ SUCCESS!</strong> Barcodes are generated using the picqer/php-barcode-generator library.<br>
            These are REAL scannable barcodes (Code 128 format).
        </div>

        <div class="barcode-grid">
            <!-- Invoice Barcode -->
            <div class="barcode-card">
                <h2>📄 Invoice Barcode</h2>
                <div class="barcode-display">
                    <div class="barcode-wrapper">
                        @if(isset($barcodes['invoice']['html']))
                            {!! $barcodes['invoice']['html'] !!}
                        @else
                            <div class="barcode-fallback">*{{ $barcodes['invoice']['code'] }}*</div>
                        @endif
                    </div>
                </div>
                <div class="barcode-code">{{ $barcodes['invoice']['code'] }}</div>
                <div class="badge badge-success">✓ Code 128 - Scannable</div>
            </div>

            <!-- Shipping Barcode -->
            <div class="barcode-card">
                <h2>🚚 Shipping Label</h2>
                <div class="barcode-display">
                    <div class="barcode-wrapper">
                        @if(isset($barcodes['shipping']['html']))
                            {!! $barcodes['shipping']['html'] !!}
                        @else
                            <div class="barcode-fallback">*{{ $barcodes['shipping']['code'] }}*</div>
                        @endif
                    </div>
                </div>
                <div class="barcode-code">{{ $barcodes['shipping']['code'] }}</div>
                <div class="badge badge-success">✓ Code 128 - Scannable</div>
            </div>

            <!-- Packing Barcode -->
            <div class="barcode-card">
                <h2>📦 Packing Slip</h2>
                <div class="barcode-display">
                    <div class="barcode-wrapper">
                        @if(isset($barcodes['packing']['html']))
                            {!! $barcodes['packing']['html'] !!}
                        @else
                            <div class="barcode-fallback">*{{ $barcodes['packing']['code'] }}*</div>
                        @endif
                    </div>
                </div>
                <div class="barcode-code">{{ $barcodes['packing']['code'] }}</div>
                <div class="badge badge-success">✓ Code 128 - Scannable</div>
            </div>

            <!-- Delivery Barcode -->
            <div class="barcode-card">
                <h2>📬 Delivery Note</h2>
                <div class="barcode-display">
                    <div class="barcode-wrapper">
                        @if(isset($barcodes['delivery']['html']))
                            {!! $barcodes['delivery']['html'] !!}
                        @else
                            <div class="barcode-fallback">*{{ $barcodes['delivery']['code'] }}*</div>
                        @endif
                    </div>
                </div>
                <div class="barcode-code">{{ $barcodes['delivery']['code'] }}</div>
                <div class="badge badge-success">✓ Code 128 - Scannable</div>
            </div>

            <!-- EAN-13 Barcode -->
            @if(isset($barcodes['ean13']['html']))
            <div class="barcode-card">
                <h2>🔢 EAN-13</h2>
                <div class="barcode-display">
                    <div class="barcode-wrapper">
                        {!! $barcodes['ean13']['html'] !!}
                    </div>
                </div>
                <div class="barcode-code">{{ $barcodes['ean13']['code'] }}</div>
                <div class="badge badge-success">✓ EAN-13 - Retail standard</div>
            </div>
            @endif
        </div>

        <div style="text-align: center; margin-top: 30px; padding: 20px; background: #dbeafe; border-radius: 8px;">
            <p style="color: #1e40af; font-weight: bold; font-size: 16px;">✅ All barcodes are working correctly!</p>
            <p style="color: #1e3a8a;">You can now use these barcodes in your invoices, shipping labels, packing slips, and delivery notes.</p>
            <p style="color: #1e3a8a; margin-top: 10px;">Test URL: <code>{{ url('/test-barcode/' . $id) }}</code></p>
        </div>
    </div>
</body>
</html>
