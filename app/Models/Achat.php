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
}
