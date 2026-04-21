<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article_depot extends Model
{
    protected $fillable = [
        'article_id',
        'magasin_id',
        'stock',
    ];
}
