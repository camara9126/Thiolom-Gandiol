<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat_detatils extends Model
{
    protected $fillable = [
        'achat_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'total',
    ];

    public function article() {
        return $this->belongsTo(Article::class);
    }
}
