<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfers_stock extends Model
{
    protected $fillable = [
        'article_id',
        'source_id',
        'destination_id',
        'quantite',
    ];

    public function article() {
        return $this->hasMany(Article::class);
    }
}
