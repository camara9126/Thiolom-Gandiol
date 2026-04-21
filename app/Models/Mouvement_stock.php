<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement_stock extends Model
{
    protected $fillable = [
        'article_id',
        'type',
        'quantite',
        'reference',
        'magasin_id',
    ];

    public function article()
    {
        return $this->belongsTo((Article::class));
    }
    
    public function magasin()
    {
        return $this->belongsTo((Magasin::class));
    }
}
