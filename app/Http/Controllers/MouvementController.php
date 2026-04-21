<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use Illuminate\Http\Request;

class MouvementController extends Controller
{

    public function index() {
        $mouvements= Mouvement_stock::with('article')->latest()->paginate(10);
        $articles= Article::latest()->get();
        $magasins= Magasin::latest()->get();

        return view('dashboard.mouvementStock.index', compact('mouvements','articles','magasins'));
    }

    public function search(Request $request)
    {
        $search = $request->query('search');
        $articles= Article::latest()->get();
        $magasins= Magasin::latest()->get();

        $mouvements = Mouvement_stock::with('article')->when($search, function ($query, $search) {

                $query->where('reference', 'like', "%{$search}%")->orWhereHas('article', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=

        return view('dashboard.mouvementStock.index', compact('mouvements','articles','search','magasins'));

    }

    
    public function stock(Request $request) {

        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'quantite' => 'required|integer|min:1',
            'type' => 'required',
            'magasin' =>'required',
        ]);

        $article = Article::findOrFail($request->article_id);

        Mouvement_stock::create([
            'article_id' => $article->id,
            'type' => $request->type,
            'quantite' => $request->quantite,
            'reference' => 'MVT-' . now()->timestamp,
        ]);


        if($request->type == 'entree') {

           Article_depot::where('article_id', $article->id)->where('magasin_id', $request->magasin_id)->increment('stock', $request->quantite);

            $article->increment('stock', $request->quantite);

            return back()->with('success', 'Entrée de stock enregistrée');
        } else {
            Article_depot::where('article_id', $article->id)->where('magasin_id', $request->magasin_id)->decrement('stock', $request->quantite);

            $article->decrement('stock', $request->quantite);

            return back()->with('success', 'Sortie de stock enregistrée');
        }
        
    }
}
