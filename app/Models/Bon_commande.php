<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bon_commande extends Model
{
      protected $fillable = [
        'fournisseur_id',
        'reference',
        'total',
        'date_commande',
        'note',
        'statut',
    ];

     public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }


    public function details()
    {
        return $this->hasMany(Bon_commande_details::class);
    }

     public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

}
