<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magasin extends Model
{
    protected $fillable = [
        'nom',
        'telephone',
        'adresse',
    ];

    public function stock()
    {
        return $this->hasMany(Mouvement_stock::class);
    }

     public function article() {
        return $this->belongsToMany(Article::class, 'article_depots')->withPivot('stock')->withTimestamps();
    }

}
