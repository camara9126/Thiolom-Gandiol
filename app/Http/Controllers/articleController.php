<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Categorie;
use App\Models\Entreprise;
use App\Models\Fournisseur;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class articleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles= Article::latest()->paginate(50);
        $categorie= categorie::latest()->get();
        $magasins = Magasin::latest()->get();

        return view('dashboard.articles.index', compact('articles','categorie','magasins'));
    }

    /**
     * Recherche article par l'Admin.
     */
    public function search(Request $request)
    {
        $search = $request->query('search');

        $categorie= categorie::latest()->get();
        $magasins = Magasin::latest()->get();

        $articles = Article::with('categorie')->when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%")->orWhereHas('categorie', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(50)->withQueryString(); // 🔑 garde ?search=;

        return view('dashboard.articles.index', compact('articles', 'search','categorie','magasins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorie= categorie::latest()->get();
        $fournisseur= fournisseur::latest()->get();
        $magasins = Magasin::latest()->get();

        return view('dashboard.articles.create', compact('categorie','fournisseur','magasins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'fournisseur_id' => 'exists:fournisseurs,id',
            'nom' => 'required','string',
            'description' => 'nullable',
            'prix_achat' ,
            'prix_vente' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' ,
            'stock_min' ,
            'magasin_id' ,
            'categorie_id' => 'exists:categorie,id',
            
        ]);

        $entreprise= Entreprise::findOrFail(1);
       
        // Gestion de l'images principal
        if ($request->hasFile('image')) {
            $filename = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('imgArticles', $filename, 'public');
            $request['image'] = '/storage/' . $path;
        } else {
            $entreprise->logo;
        }

        // creation de l'article
        $articles= Article::create([
            'fournisseur_id' => 1,
            'nom' => $request->nom,
            'description' => $request->description ?? null,
            'prix_achat' => $request->prix_vente,
            'prix_vente' => $request->prix_vente,
            'code' => $this->generateCode(),
            'reference' => 'REF-' . now()->timestamp,
            'stock' => $request->stock  ?? 100,
            'stock_min' => 20,
            'categorie_id' => 1,
            'magasin_id' => $request->magasin_id ?? 1,
            'image' => $path ?? $entreprise->logo,
        ]);


        // Creation de l'article dans le depot
        $magasin = Magasin::where('id', $request->magasin_id)->lockForUpdate()->firstOrFail(); // verrou stock

        Article_depot::create([
            'article_id' => $articles->id,
            'magasin_id' => $magasin->id,
            'stock' => $request->stock,
        ]);

        // Enregistrement d'un historique de mouvement
        Mouvement_stock::create([
            'article_id' => $articles->id,
            'type' => 'entree',
            'quantite' => $request->stock ?? 100,
            'magasin_id' => $request->magasin_id ?? 1,
            'reference' => 'MVT-' . now()->timestamp,
        ]);

        return redirect()->route('articles.index')->with('success', 'Article crée avec success.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article= Article::findOrfail($id);
        $categorie= categorie::latest()->get();
        $fournisseur= fournisseur::latest()->get();

        return view('dashboard.articles.edit', compact('article', 'categorie','fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article= Article::findorFail($id);

        $request->validate([
            'fournisseur_id' => 'exists:fournisseurs,id',
            'nom' => 'string',
            'description' ,
            'prix_vente',
            'image' ,
            'gal_1' ,
            'gal_2' ,
            'stock' ,
            'statut' ,
            'etiquette' ,
            'categorie_id' ,
            
        ]);
       
        // Gestion de l'images principal
        if ($request->hasFile('image')) {

         // Suppression de l'ancien image gal
            if($article->image){
                Storage::delete('public/storage/imgArticles/'.$article->image);
            }

            $filename = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('imgArticles', $filename, 'public');
            $request['image'] = '/storage/' . $path;

        } else {
            $article->image;
        }

        // Gestion des galeries
        if ($request->hasFile('gal_1')) {

            // Suppression de l'ancien image gal
            if($article->gal_1){
                Storage::delete('public/storage/imgArticles/'.$article->gal_1);
            }

            $filename = time().$request->file('gal_1')->getClientOriginalName();
            $gal_1 = $request->file('gal_1')->storeAs('imgArticles', $filename, 'public');
            $request['gal_1'] = '/storage/' . $gal_1;

        } else {
            $article->gal_1 ?? null;
        }   

        if ($request->hasFile('gal_2')) {

         // Suppression de l'ancien image gal
            if($article->gal_2){
                Storage::delete('public/storage/imgArticles/'.$article->gal_2);
            }

            $filename = time().$request->file('gal_2')->getClientOriginalName();
            $gal_2 = $request->file('gal_2')->storeAs('imgArticles', $filename, 'public');
            $request['gal_2'] = '/storage/' . $gal_2;

        } else {
            $article->gal_2  ?? null;
        }

        //dd($request);
        // mise a jour de l'article
        $article->update([
            'nom' => $request->nom,
            'fournisseur_id' => $request->fournisseur_id ?? 1,
            'description' => $request->description ?? null,
            'prix_achat' => $request->prix_vente,
            'prix_vente' => $request->prix_vente,
            'designation' => $request->designation ?? null,
            'stock' => $request->stock  ?? 100,
            'stock_min' => $request->stock_min  ?? 20,
            'categorie_id' => $request->categorie_id ?? 1,
            'magasin_id' => $request->magasin_id ?? 1,
            'image' => $path ?? $article->image,
        ]);

        return redirect()->route('articles.index')->with('success', 'Article modifiée avec success.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article= Article::findOrFail($id);

        $article->destroy($id);

        return redirect()->route('articles.index')->with('success', 'Article supprimée avec success.');
    }

    // Generateur de code produit 
    private function generateCode(): string
    {
        $lastProduit = Article::orderBy('id', 'desc')->first();

        $number = $lastProduit ? intval(substr($lastProduit->code, -5)) + 1 : 1;

        return 'PRD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
