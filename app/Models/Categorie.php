<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image',
    ];


    public function article() {
        return $this->hasMany(Article::class);
    }


    // creation de slug a chaque categorie
        protected static function boot()
            {
                parent::boot();
            
                static::saving(function ($categorie) {
                    if (empty($categorie->slug)) {
                        $slug = Str::slug($categorie->nom);
                        $originalSlug = $slug;
            
                        // Vérifier l'unicité du slug
                        $count = 1;
                        while (Categorie::where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $count;
                            $count++;
                        }
            
                        $categorie->slug = $slug;
                    }
                });
            }
}
