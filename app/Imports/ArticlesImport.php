<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Entreprise;
use App\Models\Mouvement_stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticlesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $entreprise= Entreprise::findOrFail(1);

            $articles= Article::create([
                'image' => $entreprise->logo,
                'code' => $this->generateCode(),
                'reference' => $this->generateReference(),
                'nom' => trim($row['nom']),
                'fournisseur_id' => $row['fournisseur_id'],
                'prix_vente' => $row['prix_vente'],
                'prix_achat' => $row['prix_achat'],
                'stock' => $row['stock'],
                'stock_min' => $row['stock_min'],
                'magasin_id' => $row['magasin_id'],
                'categorie_id' => $row['categorie_id'],
            ]);

            Article_depot::create([
                'article_id' => $articles->id,
                'magasin_id' => $row['magasin_id'],
                'stock' => $row['stock'],
            ]);

            // Enregistrement d'un historique de mouvement
            Mouvement_stock::create([
                'article_id' => $articles->id,
                'type' => 'entree',
                'quantite' => $row['stock'] ?? 100,
                'magasin_id' => $row['magasin_id'] ?? 1,
                'reference' => 'MVT-' . now()->timestamp,
            ]);

        
        }
    
    }


    // Generateur de code produit 
    private function generateCode(): string
    {
        $lastProduit = Article::orderBy('id', 'desc')->first();

        $number = $lastProduit ? intval(substr($lastProduit->code, -5)) + 1 : 1;

        return 'PRD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    private function generateReference()
    {
        return 'REF-' . strtoupper(uniqid());
    }
}
