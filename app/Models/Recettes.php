<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recettes extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'libelle',
        'description',
        'montant',
        'date_recette',
        'paiement_id',
        'mode_paiement',
        'statut'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiement()
    {
        return $this->belongsTo(Paiements::class, 'paiement_id');
    }
}
