<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenteItem extends Model
{
    protected $fillable = [
        'vente_id',
        'article_id',
        'magasin_id',
        'quantite',
        'prix_unitaire',
        'taux_tva',
        'montant_tva',
        'total_ttc',
        'total',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

   
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
