<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $fillable = [
        'client_id',
        'entreprise_id',
        'reference',
        'total',
        'date_devis',
        'date_expiration',
        'note',
        'statut',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function details()
    {
        return $this->hasMany(Devis_details::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

}
