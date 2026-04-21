<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'statut',
    ];

    public function article() {
        return $this->hasMany(Article::class);
    }
}
