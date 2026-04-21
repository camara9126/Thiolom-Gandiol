<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiements extends Model
{
     use HasFactory;

    protected $fillable = [
        'vente_id',
        'entreprise_id',
        'montant',
        'mode_paiement',
        'reference',
        'date_paiement',
        'statut',
        'motif',
        'annule_par',
        'annule_le',
    ];

    
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public function recette()
    {
        return $this->hasOne(Recettes::class, 'paiement_id');
    }

}
