<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'fournisseur_id',
        'nom',
        'slug',
        'code',
        'prix_achat',
        'prix_vente',
        'reference',
        'description',
        'image',
        //'gal_1',
        //'gal_2',
        'stock',
        'stock_min',
        'categorie_id',
        'statut',
    ];


    public function magasin()
    {
        $this->belongsToMany(Magasin::class, 'article_depots')->withPivot('stock')->withTimestamps();
    }


    public function categorie() {
        return $this->belongsTo(Categorie::class);
    }

     public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function ventes() {
        return $this->hasMany(Vente::class);
    }


    public function mouvements() {
        return $this->hasMany(Mouvement_stock::class);
    }

    // Alerte stock minimum
    public static function produitsEnAlerte()
    {
        return self::whereColumn('stock', '<=', 'stock_min');
    }

    // creation de slug a chaque article
        protected static function boot()
            {
                parent::boot();
            
                static::saving(function ($article) {
                    if (empty($article->slug)) {
                        $slug = Str::slug($article->nom);
                        $originalSlug = $slug;
            
                        // Vérifier l'unicité du slug
                        $count = 1;
                        while (Article::where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $count;
                            $count++;
                        }
            
                        $article->slug = $slug;
                    }
                });
            }
}
