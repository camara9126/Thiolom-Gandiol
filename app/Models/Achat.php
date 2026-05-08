<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    protected $fillable = [
        'fournisseur_id',
        'reference',
        'total',
        'note',
        'statut',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }


    public function details()
    {
        return $this->hasMany(Achat_detatils::class);
    }

     public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }


     public function paiements()
    {
        return $this->hasMany(Paiements::class);
    }

        //calcule montant payee
    public function getMontantPayeAttribute()
    {
        return $this->paiements()->where('statut', 'valide')->sum('montant');
    }

    // calcule montant restant
    public function getMontantRestantAttribute()
    {
        return max(0, $this->total - $this->montant_paye);
    }
}
