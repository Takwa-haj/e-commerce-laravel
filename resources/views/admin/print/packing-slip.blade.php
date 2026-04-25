<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bordereau de Colisage #{{ $order->id }}</title>
    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }

        .packing-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #6f42c1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .packing-title {
            font-size: 22px;
            color: #6f42c1;
            font-weight: bold;
            margin: 0;
        }

        .packing-number {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }

        .packing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .packing-box {
            border: 2px solid #6f42c1;
            padding: 12px;
            border-radius: 5px;
            background: #fff;
        }

        .packing-box h3 {
            margin-top: 0;
            color: #6f42c1;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .checklist-table th {
            background: #6f42c1;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }

        .checklist-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .check-column {
            width: 60px;
            text-align: center;
        }

        .checkbox {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #333;
            border-radius: 3px;
            margin: 0 auto;
        }

        .checklist-section {
            margin: 20px 0;
        }

        .checklist-section h4 {
            color: #6f42c1;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        .checklist-items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .checklist-item .checkbox {
            margin-right: 8px;
        }

        .packing-notes {
            background: #e9ecef;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #6f42c1;
        }

        .signature-area {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }

        .signature-line {
            width: 300px;
            margin-top: 40px;
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 5px;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 25px;
            text-align: center;
            margin: 10px 0;
        }

        .priority-badge {
            background: #ffc107;
            color: #000;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-left: 10px;
        }

        .packing-details {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .packing-details-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="packing-header">
        <div>
            <h1 class="packing-title">BORDEREAU DE COLISAGE</h1>
            <p class="packing-number">N° PC-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p>Commande N°: {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p>Date: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        <div style="text-align: right;">
            <div class="barcode">
                *PC-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}*
            </div>
            <div class="priority-badge">STANDARD</div>
        </div>
    </div>

    <div class="packing-grid">
        <div class="packing-box">
            <h3>INFORMATIONS CLIENT</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->address }}</p>
            <p>{{ $order->city }}, {{ $order->zip }}</p>
            <p>{{ $order->country }}</p>
            <p>Tél: {{ $order->phone }}</p>
            @if($order->user && $order->user->email)
                <p>Email: {{ $order->user->email }}</p>
            @endif
        </div>

        <div class="packing-box">
            <h3>INFORMATIONS COLIS</h3>
            <div class="packing-details">
                <div class="packing-details-row">
                    <span>Transporteur:</span>
                    <span>{{ $order->carrier ?? 'STANDARD' }}</span>
                </div>
                <div class="packing-details-row">
                    <span>Service:</span>
                    <span>Livraison standard</span>
                </div>
                <div class="packing-details-row">
                    <span>Nombre de colis:</span>
                    <span>1/1</span>
                </div>
                <div class="packing-details-row">
                    <span>Poids estimé:</span>
                    <span>{{ rand(1, 5) }} kg</span>
                </div>
                @if($order->tracking_number)
                <div class="packing-details-row">
                    <span>Numéro de suivi:</span>
                    <span>{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <h3 style="color: #6f42c1; margin: 20px 0 10px 0;">LISTE DES ARTICLES À COLISER</h3>

    <table class="checklist-table">
        <thead>
            <tr>
                <th width="40">#</th>
                <th>ARTICLE</th>
                <th width="80">RÉFÉRENCE</th>
                <th width="60" style="text-align: center;">QTÉ</th>
                <th width="60" class="check-column">COLISÉ</th>
                <th width="60" class="check-column">VÉRIFIÉ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product ? $item->product->name : 'Produit supprimé' }}
                        @if($item->options)
                            <br><small style="color: #666;">{{ $item->options }}</small>
                        @endif
                    </td>
                    <td>
                        {{ $item->product ? $item->product->SKU : 'N/A' }}
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="check-column"><div class="checkbox"></div></td>
                    <td class="check-column"><div class="checkbox"></div></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="checklist-section">
        <h4>VÉRIFICATION AVANT COLISAGE</h4>
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Produits vérifiés et complets</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Documents inclus (facture)</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Emballage adapté au produit</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Étiquette d'expédition imprimée</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Adresse de livraison vérifiée</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Poids du colis vérifié</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Colis scellé correctement</span>
            </div>
            <div class="checklist-item">
                <div class="checkbox"></div>
                <span>Marquage FRAGILE si nécessaire</span>
            </div>
        </div>
    </div>

    <div class="packing-notes">
        <h4 style="margin-top: 0;">NOTES DE COLISAGE</h4>
        <p><strong>Instructions spéciales:</strong>
            @if($order->delivery_notes)
                {{ $order->delivery_notes }}
            @else
                Aucune instruction spéciale
            @endif
           </p>
    <p><strong>Type d'emballage:</strong> Carton standard avec protection bulle</p>
    <p><strong>Documents à inclure:</strong> Facture + bon de livraison</p>
    <p><strong>Mention spéciale:</strong>
        @php
            use Illuminate\Support\Str;
        @endphp

        @foreach($order->orderItems as $item)
            @if($item->product && Str::contains(strtolower($item->product->name), ['verre', 'porcelaine', 'cristal']))
                FRAGILE - À manipuler avec soin
                @break
            @endif
        @endforeach
    </p>
</div>

<div class="signature-area">
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px;">
        <div>
            <h4>RÉALISÉ PAR</h4>
                <p>Nom: ___________________</p>
                <p>Poste: Préparateur de commandes</p>
                <p>Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <div class="signature-line">
                    Signature du préparateur
                </div>
            </div>

            <div>
                <h4>CONTRÔLE QUALITÉ</h4>
                <p>Nom: ___________________</p>
                <p>Poste: Contrôleur qualité</p>
                <p>Date: ____/____/________</p>
                <div class="signature-line">
                    Signature du contrôleur
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Ce bordereau doit accompagner la commande jusqu'à son expédition.</p>
        <p>Conservez une copie dans les archives du service logistique.</p>
        <p>{{ $settings->site_name ?? 'Bijouterie' }} - Service Logistique</p>
    </div>
</body>
</html>
