<?php

namespace App\Imports;

use App\Models\Article;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ArticlesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as  $row) {

            Article::firstOrCreate([
                'nom' => $row['nom'],
                'fournisseur_id' => $row['founisseur_id'],
                'prix_vente' => $row['prix_vente'],
                'prix_achat' => $row['prix_achat'],
                'stock' => $row['stock'],
                'stock_min' => $row['stock_min'],
                'magasin_id' => $row['magasin_id'],
                'categorie_id' => $row['categorie_id'],
            ]);
        }
    }
}
