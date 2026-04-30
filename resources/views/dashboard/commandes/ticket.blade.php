<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de caisse</title>
    <style>
    body { font-size: 12px; }
    </style>
</head>
<body>
    <h4>Ticket de caisse</h4>

    <p>Date : {{ $vente->created_at }}</p>

    @foreach($vente->items as $produit)
        <p>
            {{ $produit->article->nom }} x {{ $produit->quantite }}
            = {{ number_format($produit->prix_unitaire, 0, ',', ' ') }} FCFA
        </p>
    @endforeach

    <hr>

    <p>Total : {{ number_format($vente->total_ttc, 0, ',', ' ') }} FCFA</p>
    <p>Payé : {{ number_format($vente->montant_paye, 0, ',', ' ') }} FCFA</p>

    <p>Merci pour votre achat 🙏</p>

</body>
</html>


