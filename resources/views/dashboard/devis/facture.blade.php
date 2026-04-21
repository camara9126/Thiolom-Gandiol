<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devis</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        .header { display: flex; justify-content: space-between; }
        .title { text-align: center; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        .total { text-align: right; margin-top: 20px; }
    </style>
</head>
<body>

<!-- ENTREPRISE -->
<div class="header">
    <div>
        <h3>{{ $devis->entreprise->nom ?? 'Entreprise' }}</h3>
        <p>Date : {{ $devis->date_devis }}</p>
        <p>Référence : {{ $devis->reference }}</p>
    </div>

    <div>
        <img src="{{ public_path('images/logo-blanc.jpeg') }}" width="100">
    </div>
</div>

<div class="title">
    <h2>DEVIS</h2>
</div>

<!-- CLIENT -->
<h4>Client</h4>
<p>Nom : {{ $devis->client->nom ?? '-' }}</p>
<p>Téléphone : {{ $devis->client->telephone ?? '-' }}</p>

<!-- TABLE -->
<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($devis->details as $detail)
        <tr>
            <td>{{ $detail->article->nom ?? '-' }}</td>
            <td>{{ $detail->quantite }}</td>
            <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($detail->total, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- TOTAL -->
<div class="total">
    <h3>Total : {{ number_format($devis->total, 0, ',', ' ') }} FCFA</h3>
</div>

</body>
</html>