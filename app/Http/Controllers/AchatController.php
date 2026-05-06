<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Achat_detatils;
use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Depenses;
use App\Models\Entreprise;
use App\Models\Fournisseur;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AchatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $achats = Achat::with('fournisseur')->latest()->get();

        return view('dashboard.achats.index', compact('achats'));
    }


    public function search(Request $request)
    {
        $search = $request->query('search');

        $achats = Achat::with('fournisseur')->when($search, function ($query, $search) {

                $query->where('reference', 'like', "%{$search}%")->orWhereHas('fournisseur', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(50)->withQueryString(); // 🔑 garde ?search=

        return view('dashboard.achats.index', compact('achats','search'));

    }


    // Recherche bon
    public function bonSearch(Request $request)
    {
        $query = $request->q;

        $articles = Article::where('nom', 'LIKE', "%{$query}%")->limit(50)->get();

        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        $magasin= Magasin::all();

        return view('dashboard.achats.create', compact('fournisseurs', 'articles', 'magasin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id' ,
            'articles' => 'required|array',
            'articles.*.article_id' => 'required',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix_vente' => 'required|numeric|min:0',
            'note' => 'nullable',
            'magasin_id' => 'required',
        ]);

         DB::beginTransaction();
    
        try {

            // Création du bon de commande
            $achat = Achat::create([
                'reference' => 'AC-' . strtoupper(Str::random(6)),
                'fournisseur_id' => $request->fournisseur_id,
                'total' => 0,
                'note' => $request->note ?? 'null',
            ]);

            $total = 0;

            foreach ($request->articles as $item) {

                // Récupération de l'article original 
                $article = Article::where('id', $item['article_id'])->lockForUpdate()->first();

                $ligneTotal = $item['quantite'] * $item['prix_vente'];

                Achat_detatils::create([
                    'achat_id' => $achat->id,
                    'article_id' => $item['article_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_vente'],
                    'total' => $ligneTotal,
                ]);

                $total += $ligneTotal;

                // Ajouter la quantité au stock existant
                $ancienStock = $article->stock;
                $nouvelleQuantite = $ancienStock + $item['quantite'];
        
                $article->update([
                    'stock' => $nouvelleQuantite,
                    'prix_achat' => $detail->prix_achat ?? $article->prix_achat,
                    'prix_vente' => $detail->prix_vente ?? $article->prix_vente, 
                    'fournisseur_id' => $achat->fournisseur_id, 
                ]);

                // Mettre à jour le stock dans Article_depot 
                $articleDepot = Article_depot::where('article_id', $item['article_id'])->where('magasin_id', $request->magasin_id)->first();
            
                if ($articleDepot) {
                    $articleDepot->increment('stock', $item['quantite']);
                } else {
                    Article_depot::create([
                        'article_id' => $item['article_id'],
                        'magasin_id' => $request->magasin_id,
                        'stock' => $item['quantite']
                    ]);
                }

                // Mise a jour du stock
                Mouvement_stock::create([
                    'article_id' => $item['article_id'],
                    'type' => 'entree',
                    'quantite' => $item['quantite'],
                    'magasin_id' => $request->magasin_id,
                    'reference' => 'MVT-' . now()->timestamp,
                ]);
                
            }

            // Mise à jour du total
            $achat->update([
                'total' => $total
            ]);

            $entreprise= Entreprise::findOrFail(1); 

            Depenses::create([
                'entreprise_id' => $entreprise->id,
                'user_id' => request()->user()->id,
                'reference' => 'DEP-' . now()->timestamp,
                'libelle' => 'Achat - '. $achat->reference,
                'description' => 'Achat article',
                'montant' => $achat->total,
                'date_depense' => now(),
                'mode_paiement' => 'cash',
            ]);

        DB::commit();

        return redirect()->route('achats.index')->with('success', 'Achat créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la conversion: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $achat = Achat::with('fournisseur', 'details.article')->findOrFail($id);

        return view('dashboard.achats.show', compact('achat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $achat = Achat::findOrFail($id);
        $achat->delete();

        return back()->with('success', 'Achat supprimé');
    }
}
