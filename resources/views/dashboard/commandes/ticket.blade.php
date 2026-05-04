<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de caisse - Thiolom Gandiol</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Courier New', 'Monaco', 'Lucida Console', monospace;
            padding: 3px;
        }

        .ticket {
            max-width: 360px;
            width: 100%;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            position: relative;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .ticket {
                box-shadow: none;
               
            }
            .no-print {
                display: none;
            }
        }

        /* Tirets pour effet ticket */
        .dashed-line {
            border-top: 1px dashed #333;
            margin: 12px 0;
        }

        .dotted-line {
            border-top: 1px dotted #999;
            margin: 8px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-small {
            font-size: 10px;
        }

        .text-xxs {
            font-size: 8px;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo-img {
            max-width: 70px;
            height: auto;
        }

        .store-name-main {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .store-brand {
            font-size: 13px;
            font-weight: bold;
            margin: 4px 0;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .info-line-center {
            text-align: center;
            font-size: 11px;
            margin: 5px 0;
        }

        /* Tableau des articles */
        .items-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
            padding: 3px 0;
        }

        .items-table th, 
        .items-table td {
            text-align: left;
            padding: 4px 0;
        }

        .items-table td:last-child {
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 13px;
            margin: 8px 0;
        }

        .payment-method {
            font-size: 11px;
            margin: 8px 0;
        }

        .footer-message {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="ticket" id="ticket-print">
        
        <!-- Logo -->
        <div class="logo-area">
            <img src="{{ public_path('storage/'.$entreprise->logo) }}" alt="Logo boutique" class="logo-img" id="shopLogo" 
                 onerror="this.style.display='none';">
        </div>

        <!-- En-tête -->
        <div class="text-center">
            <div class="text-small">BOUTIQUE THIOLOM GANDIOL</div>
            <div class="text-small">Tel: {{ $entreprise->telephone }} | Ninea : {{ $entreprise->ninea }}</div>
            <div class="dotted-line"></div>
            <div class="text-small text-bold">Servi par: {{strtoupper($vente->user->name)}}</div>
        </div>

        <div class="dashed-line"></div>

        <!-- SECTION PRODUITS (exemple) -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <!--<th>Quantité</th>-->
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vente->items as $item)
                    <tr>
                        <td>{{ $item->article->nom }}</td>
                        <!--<td>{{ $item->quantite }}</td>-->
                        <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }} CFA</td>
                        <td>{{ number_format($item->total_ttc, 0, ',', ' ') }} CFA</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="1" style="text-align:right; font-weight:bold;">SOUS-TOTAL</td>
                    <td style="font-weight:bold;">{{ number_format($vente->total_ttc, 0, ',', ' ') }} CFA</td>
                </tr>
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <!-- Montants -->
        <!--<div class="info-line">
            <span>Transport</span>
            <span>SOMME</span>
            <span>1 CFA</span>
        </div>
        <div class="info-line">
            <span>Espèces</span>
            <span>RENDU</span>
            <span>1 CFA</span>
        </div>-->
        <div class="dotted-line"></div>
        <div class="text-center text-small">Total des taxes : 0 CFA</div>

        <div class="dashed-line"></div>

        <!-- Infos commande -->
        <div class="text-center">
            <div class="text-bold">Commande <b>{{ $vente->reference }}</b></div>
            <div class="text-small">{{ date('d/m/Y H:i:s') }}</div>
        </div>

       

        <!-- Pied de page -->
        <div class="footer-message">
            <div class="dashed-line"></div>
            <p>MERCI DE VOTRE VISITE</p>
            <!--<p class="text-xxs" style="margin-top: 8px;">Ticket généré le {{ date('d/m/Y H:i:s') }}</p>-->
        </div>
    </div>

</body>
</html>