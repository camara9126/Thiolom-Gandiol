<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Facture N° {{ $vente->reference }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:5px; }

        th { background:#f2f2f2; }
        /* Pied de page */
        .invoice-footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        
        .footer-left {
            display: flex;
            flex-direction: column;
        }
        
        .footer-right {
            text-align: right;
        }

        .status-paid {
            background-color: #2ecc71;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            justify-content: center;
        }
        
    </style>
</head>
<body>

        <img src="{{ public_path('images/logo-blanc.jpeg') }}" style="width: 150px; height: 100px;" alt="Logo entreprise" class="">
        <!--<h1 class="mb-0 text-center">Eco Business Distribution</h1>-->
<p>
    Ninea : {{ $entreprise->ninea }} <br>
    Telephone : {{ $entreprise->telephone }} <br>
    Adresse : {{ $entreprise->adresse }}
</p>
<p>
    Facture N° : <b>{{ $vente->reference }}</b><br>
    Date : <?= date('d-m-Y') ?>
</p>

<hr>

<p>
    <b>Type Tiers :</b> {{ strtoupper($vente->tiers->type) ?? '' }}<br>
    {{ $vente->tiers->nom }}<br>
    {{ $vente->tiers->telephone ?? '' }} <br>
    {{ $vente->client->adresse ?? '' }} <br>
</p>

<br>

<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire (XOF)</th>
            <th>Total (XOF)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vente->items as $item)
        <tr>
            <td>{{ $item->article->nom }}</td>
            <td>{{ $item->quantite }}</td>
            <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
            <td>{{ number_format($item->total, 0, ',', ' ') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    @if($entreprise->taux_tva > 1)
        <h4>TVA ({{$item->taux_tva}} %) : {{ number_format($vente->total_tva, 0, ',', ' ') }} XOF</h4>
    @endif
    

    @if($entreprise->taux_tva > 1)
        <h2 style="color: red;">Total-TTC : {{ number_format($vente->total_ttc, 0, ',', ' ') }} XOF</h2>
    @else
        <h2 style="color: red;">Total-HT : {{ number_format($vente->total, 0, ',', ' ') }} XOF</h2>
    @endif
    
        <table>
            <h4>Detail paiements</h4>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vente->paiements as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement }}</td>
                        <td>{{number_format($paiement->montant, 0, ',',' ') }} XOF</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" align="center">Aucun paiement !</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
 
        <br>

        <!-- Pied de page -->
        <div class="invoice-footer">
             @if($vente->montant_restant !== 0)
                <div class="footer-left">
                    <h3>Montant Restant : {{ number_format($vente->montant_restant, 0, ',',' ')}} XOF</h3>
                </div>
            @endif

            <div class="footer-right">
                <div class="status-paid">
                    @if($vente->montant_restant == 0)
                        PAIEMENT COMPLET
                    @else
                        PAIEMENT INCOMPLET
                    @endif
                </div>
                <div style="margin-top: 10px; font-size: 12px;">Date de la facture: {{ $vente->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
<p>
   Facture générée par {{strtoupper($vente->user->name)}}.
</p>

</body>
</html>