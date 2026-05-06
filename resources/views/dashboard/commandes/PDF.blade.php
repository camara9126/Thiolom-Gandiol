<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $vente->reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .facture-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        /* En-tête */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .entreprise-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .entreprise-slogan {
            font-size: 11px;
            font-style: italic;
            margin-bottom: 5px;
        }

        .entreprise-infos {
            font-size: 10px;
            line-height: 1.3;
        }

        /* Infos facture */
        .info-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .info-left {
            text-align: left;
        }

        .info-right {
            text-align: right;
        }

        .facture-number {
            font-weight: bold;
            font-size: 14px;
        }

        /* Client */
        .client-section {
            margin-bottom: 25px;
            text-align: right;
        }

        .client-label {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Tableau */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }

        .items-table td:first-child,
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: right;
        }

        .items-table td:nth-child(2) {
            text-align: left;
        }

        /* Totaux */
        .totals-section {
            margin-top: 20px;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .total-line {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .total-ttc {
            font-size: 16px;
            font-weight: bold;
            color: red;
            margin-top: 10px;
        }

        /* Signature */
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .signature-left {
            text-align: left;
        }

        .signature-right {
            text-align: right;
        }

        /* Pied de page */
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9px;
            text-align: center;
            color: #777;
        }

        .payment-info {
            margin-top: 15px;
            font-size: 11px;
            text-align: right;
        }

        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .original-badge {
            font-size: 10px;
            color: #999;
            text-align: right;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="facture-container">
        <!-- Badge Original -->
        <div class="original-badge">
            Original
        </div>

        <!-- En-tête entreprise -->
        <div class="header">
            @if($entreprise->logo)
                <img src="{{ public_path('storage/'.$entreprise->logo) }}" style="width: 120px; height: auto; margin-bottom: 10px;" alt="Logo">
            @endif
            <div class="entreprise-name">{{ strtoupper($entreprise->nom) }}</div>
            @if($entreprise->slogan)
                <div class="entreprise-slogan">{{ $entreprise->slogan }}</div>
            @endif
            <div class="entreprise-infos">
                NINEA: {{ $entreprise->ninea ?? '------------' }} | 
                RCCM: {{ $entreprise->rccm ?? '------------' }}<br>
                Tél: {{ $entreprise->telephone ?? '------------' }} | 
                Fax: {{ $entreprise->fax ?? '' }}<br>
                {{ $entreprise->adresse ?? '------------' }}
            </div>
        </div>

        <!-- Date et Numéro Facture -->
        <div class="info-bar">
            <div class="info-left">
                le {{ date('d/m/Y') }}
            </div>
            <div class="info-right">
                <span class="facture-number">Facture N° {{ $vente->reference }}</span>
            </div>
        </div>

        <!-- Client -->
        <div class="client-section">
            <div class="client-label">Client :</div>
            <div>{{ $vente->client->nom }}</div>
            @if($vente->client->telephone)
                <div>Tél: {{ $vente->client->telephone }}</div>
            @endif
            @if($vente->client->adresse)
                <div>{{ $vente->client->adresse }}</div>
            @endif
        </div>

        <!-- Tableau des articles -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>QTE</th>
                    <th>DESIGNATION</th>
                    <th>P.U</th>
                    <th>MONTANT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalGeneral = 0;
                @endphp
                @foreach($vente->items as $item)
                    @php
                        $montant = $item->quantite * $item->prix_unitaire;
                        $totalGeneral += $montant;
                    @endphp
                    <tr>
                        <td>{{ $item->quantite }}</td>
                        <td style="text-align: left;">{{ $item->article->nom }}</td>
                        <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                        <td>{{ number_format($montant, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals-section">
            @if($entreprise->taux_tva > 0)
                <div class="total-line">
                    TVA ({{ $entreprise->taux_tva }}%) : {{ number_format($vente->total_tva ?? 0, 0, ',', ' ') }} FCFA
                </div>
                <div class="total-line">
                    Total HT : {{ number_format($vente->total ?? $totalGeneral, 0, ',', ' ') }} FCFA
                </div>
                <div class="total-ttc">
                    TOTAL TTC : {{ number_format($vente->total_ttc ?? ($totalGeneral * (1 + $entreprise->taux_tva/100)), 0, ',', ' ') }} FCFA
                </div>
            @else
                <div class="total-ttc">
                    TOTAL : {{ number_format($vente->total ?? $totalGeneral, 0, ',', ' ') }} FCFA
                </div>
            @endif
        </div>

        <!-- Information de paiement -->
        <div class="payment-info">
            @php
                $dernierPaiement = $vente->paiements->last();
            @endphp
            @if($dernierPaiement)
                Payée le <strong>{{ \Carbon\Carbon::parse($dernierPaiement->date_paiement)->format('d/m/Y') }}</strong>
            @elseif($vente->statut == 'payee')
                Payée le <strong>{{ now()->format('d/m/Y') }}</strong>
            @endif
        </div>

        <!-- Signature / Arrêtée -->
        <div class="signature">
            <div class="signature-left">
                Par: {{ $vente->user->name ?? '_______________' }}
            </div>
            <div class="signature-right">
                Arrêtée la présente facture à la somme de : <strong>{{ number_format($vente->total_ttc ?? $totalGeneral, 0, ',', ' ') }} FCFA</strong>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div class="row">
                <p style="text-align: left;">_________________________________________ <br>Caissier</p>
                <p style="text-align: right;">_________________________________________ <br>Livreur</p>
            </div>    
        </div>
    </div>
</body>
</html>