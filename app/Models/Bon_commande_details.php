<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bon_commande_details extends Model
{
     protected $fillable = [
        'bon_commande_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'total',
    ];

    public function article() {
        return $this->belongsTo(Article::class);
    }
}
