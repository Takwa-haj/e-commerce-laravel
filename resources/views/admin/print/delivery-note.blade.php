<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de livraison #{{ $order->id }} - {{ $settings->site_name ?? config('app.name') }}</title>
    <style>
        /* Professional Delivery Note Design */
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #2d3748;
            background: #ffffff;
            padding: 0;
            margin: 0;
        }

        .delivery-note {
            max-width: 297mm;
            margin: 0 auto;
            border: 2pt solid #e2e8f0;
            border-radius: 8mm;
            overflow: hidden;
            box-shadow: 0 4mm 12mm rgba(0,0,0,0.1);
            position: relative;
        }

        /* Header */
        .delivery-header {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
            padding: 8mm 12mm;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .delivery-header::before {
            content: '🚚 LIVRAISON';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40mm;
            font-weight: 900;
            color: rgba(255,255,255,0.03);
            letter-spacing: 4mm;
            white-space: nowrap;
        }

        .delivery-title {
            font-size: 24pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            margin-bottom: 2mm;
            position: relative;
            z-index: 1;
        }

        .delivery-subtitle {
            font-size: 12pt;
            font-weight: 300;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            margin-bottom: 4mm;
        }

        .delivery-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2mm 8mm;
            border-radius: 20mm;
            font-size: 14pt;
            font-weight: 700;
            position: relative;
            z-index: 1;
            border: 2pt solid rgba(255,255,255,0.2);
            box-shadow: 0 2mm 8mm rgba(102, 126, 234, 0.3);
        }

        /* Company & Client Info */
        .info-dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6mm;
            padding: 8mm 12mm;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-bottom: 2pt solid #e2e8f0;
        }

        .info-panel {
            background: white;
            border-radius: 6mm;
            padding: 6mm 8mm;
            box-shadow: 0 2mm 6mm rgba(0,0,0,0.05);
            border: 1pt solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .info-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4mm;
            height: 100%;
            background: linear-gradient(to bottom, #667eea, #764ba2);
        }

        .panel-title {
            font-size: 12pt;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4mm;
            padding-bottom: 2mm;
            border-bottom: 1pt solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            display: flex;
            align-items: center;
            gap: 3mm;
        }

        .panel-title::before {
            font-size: 14pt;
        }

        .company-panel .panel-title::before {
            content: '🏢';
        }

        .client-panel .panel-title::before {
            content: '👤';
        }

        .info-details {
            display: grid;
            grid-template-columns: 30mm 1fr;
            gap: 2mm 4mm;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.2pt;
        }

        .detail-value {
            color: #2d3748;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Delivery Dashboard */
        .delivery-dashboard {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 3mm;
            padding: 6mm 12mm;
            background: linear-gradient(135deg, #fffaf0, #feebc8);
            border-bottom: 2pt solid #f6ad55;
        }

        .delivery-card {
            background: white;
            border-radius: 4mm;
            padding: 4mm;
            text-align: center;
            border: 1pt solid #f6ad55;
            box-shadow: 0 1mm 3mm rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .delivery-card:hover {
            transform: translateY(-1mm);
            box-shadow: 0 2mm 6mm rgba(0,0,0,0.1);
        }

        .card-icon {
            font-size: 16pt;
            margin-bottom: 2mm;
            color: #dd6b20;
        }

        .card-label {
            font-size: 8pt;
            color: #744210;
            text-transform: uppercase;
            letter-spacing: 0.2pt;
            font-weight: 500;
            margin-bottom: 1mm;
        }

        .card-value {
            font-size: 12pt;
            font-weight: 700;
            color: #2d3748;
        }

        /* Products Table */
        .products-section {
            padding: 8mm 12mm;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6mm;
        }

        .section-title {
            font-size: 14pt;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 3mm;
        }

        .section-title::before {
            content: '📦';
            font-size: 16pt;
        }

        .delivery-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 6mm;
            overflow: hidden;
            box-shadow: 0 2mm 6mm rgba(0,0,0,0.05);
        }

        .delivery-table thead {
            background: linear-gradient(135deg, #2d3748, #1a202c);
        }

        .delivery-table th {
            padding: 3mm 4mm;
            font-weight: 600;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
            color: white;
            text-align: left;
            border: none;
        }

        .delivery-table tbody tr {
            border-bottom: 1pt solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .delivery-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .delivery-table tbody tr:hover {
            background-color: #edf2f7;
        }

        .delivery-table td {
            padding: 3mm 4mm;
            vertical-align: middle;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 3mm;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5mm;
        }

        .product-sku {
            font-size: 8pt;
            color: #718096;
            font-family: 'Courier New', monospace;
        }

        .quantity-input {
            width: 15mm;
            padding: 1mm 2mm;
            border: 1pt solid #cbd5e0;
            border-radius: 2mm;
            text-align: center;
            font-size: 10pt;
            font-weight: 600;
            color: #2d3748;
            background: white;
            transition: all 0.2s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2pt rgba(102, 126, 234, 0.1);
        }

        .status-select {
            width: 100%;
            padding: 1mm 2mm;
            border: 1pt solid #cbd5e0;
            border-radius: 2mm;
            font-size: 9pt;
            color: #2d3748;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .status-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2pt rgba(102, 126, 234, 0.1);
        }

        .total-row {
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
            font-weight: 600;
        }

        .total-label {
            text-align: right;
            padding-right: 4mm !important;
            color: #4a5568;
        }

        /* Checklist */
        .checklist-section {
            padding: 6mm 12mm;
            background: linear-gradient(135deg, #f0fff4, #c6f6d5);
            border-top: 2pt solid #9ae6b4;
            border-bottom: 2pt solid #9ae6b4;
        }

        .checklist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3mm;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 2mm;
            padding: 2mm 3mm;
            background: white;
            border-radius: 3mm;
            border: 1pt solid #9ae6b4;
            font-size: 9pt;
            color: #2d3748;
            transition: all 0.2s ease;
        }

        .checklist-item:hover {
            background: #f0fff4;
            transform: translateY(-1mm);
        }

        .check-item {
            width: 5mm;
            height: 5mm;
            border: 1.5pt solid #cbd5e0;
            border-radius: 1mm;
            flex-shrink: 0;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .check-item:hover {
            border-color: #4c51bf;
            background: #f7fafc;
        }

        .check-item.checked {
            background: #48bb78;
            border-color: #48bb78;
        }

        .check-item.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 8pt;
            font-weight: bold;
        }

        /* Signatures */
        .signature-section {
            padding: 8mm 12mm;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8mm;
        }

        .signature-card {
            padding: 6mm;
            border: 2pt solid #e2e8f0;
            border-radius: 6mm;
            background: #f8fafc;
            text-align: center;
            position: relative;
        }

        .signature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4mm;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 6mm 6mm 0 0;
        }

        .signature-title {
            font-size: 11pt;
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 6mm;
            padding-bottom: 2mm;
            border-bottom: 1pt solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }

        .signature-area {
            margin-top: 20mm;
            padding-top: 4mm;
            border-top: 1pt solid #4a5568;
            min-height: 30mm;
            position: relative;
        }

        .signature-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #a0aec0;
            font-size: 10pt;
            font-style: italic;
        }

        .signature-details {
            margin-top: 4mm;
            font-size: 9pt;
            color: #718096;
            line-height: 1.4;
        }

        /* Footer */
        .delivery-footer {
            padding: 6mm 12mm;
            background: #2d3748;
            color: #cbd5e0;
            text-align: center;
            font-size: 9pt;
            line-height: 1.4;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3mm;
        }

        .footer-logo {
            max-height: 10mm;
            opacity: 0.7;
        }

        .legal-notice {
            font-size: 8pt;
            color: #a0aec0;
            line-height: 1.4;
            max-width: 200mm;
            margin: 0 auto;
            padding-top: 3mm;
            border-top: 1pt solid #4a5568;
        }

        /* Print Optimizations */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .delivery-note {
                border: 1pt solid #ddd;
                box-shadow: none;
                max-width: 100%;
            }

            .delivery-table {
                page-break-inside: avoid;
            }

            .checklist-section {
                page-break-inside: avoid;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="delivery-note">
        <!-- Header -->
        <div class="delivery-header">
            <div class="delivery-title">BON DE LIVRAISON</div>
            <div class="delivery-subtitle">Document d'accompagnement de marchandises</div>
            <div class="delivery-badge">N° {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Company & Client Info -->
        <div class="info-dashboard">
            <div class="info-panel company-panel">
                <div class="panel-title">Société Expéditrice</div>
                <div class="info-details">
                    <div class="detail-label">Raison sociale</div>
                    <div class="detail-value">{{ $settings->site_name ?? config('app.name') }}</div>

                    <div class="detail-label">Adresse</div>
                    <div class="detail-value">{{ $settings->site_address ?? '' }}</div>

                    <div class="detail-label">Téléphone</div>
                    <div class="detail-value">{{ $settings->site_phone ?? '' }}</div>

                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $settings->site_email ?? '' }}</div>

                    <div class="detail-label">SIRET</div>
                    <div class="detail-value">{{ $settings->siret ?? 'Non défini' }}</div>

                    <div class="detail-label">TVA</div>
                    <div class="detail-value">{{ $settings->vat_number ?? 'Non défini' }}</div>
                </div>
            </div>

            <div class="info-panel client-panel">
                <div class="panel-title">Client Destinataire</div>
                <div class="info-details">
                    <div class="detail-label">Nom / Société</div>
                    <div class="detail-value">{{ $order->name }}</div>

                    <div class="detail-label">Adresse</div>
                    <div class="detail-value">
                        {{ $order->address }}<br>
                        @if($order->locality){{ $order->locality }}<br>@endif
                        {{ $order->zip }} {{ $order->city }}<br>
                        {{ $order->country }}
                    </div>

                    <div class="detail-label">Téléphone</div>
                    <div class="detail-value">{{ $order->phone }}</div>

                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $order->user->email ?? 'Non renseigné' }}</div>

                    <div class="detail-label">Date commande</div>
                    <div class="detail-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>

                    <div class="detail-label">N° commande</div>
                    <div class="detail-value">{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        <!-- Delivery Dashboard -->
        <div class="delivery-dashboard">
            <div class="delivery-card">
                <div class="card-icon">📅</div>
                <div class="card-label">Date de livraison</div>
                <div class="card-value">{{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y') : 'À livrer' }}</div>
            </div>

            <div class="delivery-card">
                <div class="card-icon">⏰</div>
                <div class="card-label">Heure prévue</div>
                <div class="card-value">{{ $order->delivery_time ?? '9h-18h' }}</div>
            </div>

            <div class="delivery-card">
                <div class="card-icon">🚚</div>
                <div class="card-label">Transporteur</div>
                <div class="card-value">{{ $order->carrier ?? $settings->default_carrier ?? 'Standard' }}</div>
            </div>

            <div class="delivery-card">
                <div class="card-icon">🔢</div>
                <div class="card-label">N° de suivi</div>
                <div class="card-value">{{ $order->tracking_number ?? 'À générer' }}</div>
            </div>

            <div class="delivery-card">
                <div class="card-icon">⚖️</div>
                <div class="card-label">Poids total</div>
                <div class="card-value">{{ $order->weight ?? '0.5' }} kg</div>
            </div>

            <div class="delivery-card">
                <div class="card-icon">📦</div>
                <div class="card-label">Nombre de colis</div>
                <div class="card-value">{{ $order->packages ?? 1 }}/1</div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="products-section">
            <div class="section-header">
                <div class="section-title">MARCHANDISES À LIVRER</div>
                <div style="font-size: 10pt; color: #4a5568; font-weight: 500;">
                    Total: {{ $orderItems->sum('quantity') }} article(s)
                </div>
            </div>

            <table class="delivery-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="35%">Désignation</th>
                        <th width="15%">Référence</th>
                        <th width="10%" class="text-center">Qté commandée</th>
                        <th width="10%" class="text-center">Qté livrée</th>
                        <th width="10%" class="text-center">Unité</th>
                        <th width="15%" class="text-center">État</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalDelivered = 0;
                    @endphp
                    @foreach($orderItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="product-cell">
                                <div class="product-info">
                                    <div class="product-name">{{ $item->product->name ?? 'Produit supprimé' }}</div>
                                    @if($item->options)
                                    <div style="font-size: 8pt; color: #718096;">{{ $item->options }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->product->SKU ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">
                            <input type="number"
                                   class="quantity-input"
                                   value="{{ $item->quantity }}"
                                   min="0"
                                   max="{{ $item->quantity }}"
                                   onchange="updateTotalDelivered()">
                        </td>
                        <td class="text-center">Pièce</td>
                        <td class="text-center">
                            <select class="status-select">
                                <option value="ok">✅ Conforme</option>
                                <option value="damaged">⚠️ Endommagé</option>
                                <option value="missing">❌ Manquant</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach

                    <!-- Total row -->
                    <tr class="total-row">
                        <td colspan="3" class="total-label">TOTAL:</td>
                        <td class="text-center" id="total-ordered">{{ $orderItems->sum('quantity') }}</td>
                        <td class="text-center" id="total-delivered">{{ $orderItems->sum('quantity') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Checklist -->
        <div class="checklist-section">
            <div class="checklist-grid">
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Colis en bon état extérieur</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Adresse du destinataire vérifiée</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Pièce d'identité contrôlée</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Signature obtenue sur le PDA</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Photo de preuve de livraison prise</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Aucune remarque du client</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Heure de livraison notée</span>
                </div>
                <div class="checklist-item">
                    <div class="check-item"></div>
                    <span>Code de sécurité vérifié</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-card">
                    <div class="signature-title">Pour le client</div>
                    <div class="signature-area">
                        <div class="signature-placeholder">Signature, date et cachet</div>
                    </div>
                    <div class="signature-details">
                        Je soussigné(e), reconnais avoir reçu en bon état et conformément à ma commande les marchandises désignées ci-dessus.<br><br>
                        Nom: _________________________<br>
                        Date: _________________________<br>
                        Heure: _________________________
                    </div>
                </div>

                <div class="signature-card">
                    <div class="signature-title">Pour le transporteur</div>
                    <div class="signature-area">
                        <div class="signature-placeholder">Signature du livreur</div>
                    </div>
                    <div class="signature-details">
                        Certifie avoir effectué la livraison dans les conditions indiquées ci-dessus.<br><br>
                        Nom: _________________________<br>
                        Société: ______________________<br>
                        Véhicule: _____________________
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="delivery-footer">
            <div class="footer-content">
                <div>
                    <strong>{{ $settings->site_name ?? config('app.name') }}</strong> |
                    {{ $settings->site_address ?? '' }} |
                    Tél: {{ $settings->site_phone ?? '' }}
                </div>
                @if($settings->logo && file_exists(public_path('uploads/settings/' . $settings->logo)))
                <img src="{{ public_path('uploads/settings/' . $settings->logo) }}"
                     alt="{{ $settings->site_name ?? config('app.name') }}"
                     class="footer-logo">
                @endif
            </div>
            <div class="legal-notice">
                Document à conserver pendant 10 ans - En cas de litige, ce document fait foi<br>
                Bon de livraison N° {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} | Émis le {{ $date }}
            </div>
        </div>
    </div>

    <script>
        // Update total delivered quantity
        function updateTotalDelivered() {
            let total = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.getElementById('total-delivered').textContent = total;
        }

        // Initialize checkbox functionality
        document.querySelectorAll('.check-item').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('checked');
            });
        });

        // Initialize total
        updateTotalDelivered();
    </script>
</body>
</html>
