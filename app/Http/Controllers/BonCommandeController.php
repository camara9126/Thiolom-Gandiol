<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Bon_commande;
use App\Models\Bon_commande_details;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use App\Models\Fournisseur;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use App\Models\Session_caisse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BonCommandeController extends Controller
{
    /**
     * Liste des bons de commande
     */
    public function index()
    {
        $bonCommandes = Bon_commande::with('fournisseur')->latest()->get();

        return view('dashboard.bonCommandes.index', compact('bonCommandes'));
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        $bonCommandes = Bon_commande::with('fournisseur')->when($search, function ($query, $search) {

                $query->where('reference', 'like', "%{$search}%")->orWhereHas('fournisseur', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=

        return view('dashboard.bonCommandes.index', compact('bonCommandes','search'));

    }


    // Recherche bon
    public function bonSearch(Request $request)
    {
        $query = $request->q;

        $articles = Article::where('nom', 'LIKE', "%{$query}%")->limit(50)->get();

        return response()->json($articles);
    }


    /**
     * Formulaire création
     */
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $articles = Article::all();

        return view('dashboard.bonCommandes.create', compact('fournisseurs', 'articles'));
    }

    /**
     * Enregistrer un bon de commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id' => 'required',
            'articles' => 'required|array',
            'articles.*.article_id' => 'required',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix_vente' => 'required|numeric|min:0',
            'note' => 'nullable',
            'nom' => 'nullable',
            'matricule' => 'nullable',
        ]);

        // Création du bon de commande
        $bonCommande = Bon_commande::create([
            'reference' => 'BC-' . strtoupper(Str::random(6)),
            'fournisseur_id' => $request->fournisseur_id,
            'total' => 0,
            'statut' => 'en_attente',
            'date_commande' => now(),
            'note' => $request->note ?? 'null',
            'nom' => $request->nom ?? 'null',
            'matricule' => $request->matricule ?? 'null',
        ]);

        $total = 0;

        foreach ($request->articles as $item) {

            $ligneTotal = $item['quantite'] * $item['prix_vente'];

            Bon_commande_details::create([
                'bon_commande_id' => $bonCommande->id,
                'article_id' => $item['article_id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix_vente'],
                'total' => $ligneTotal,
            ]);

            $total += $ligneTotal;
        }

        // Mise à jour du total
        $bonCommande->update([
            'total' => $total
        ]);

        return redirect()->route('bonCommande.index')->with('success', 'Bon de commande créé avec succès');
    }

    /**
     * Afficher un bon de commande
     */
    public function show($id)
    {
        $bonCommande = Bon_commande::with('fournisseur', 'details.article')->findOrFail($id);

        return view('dashboard.bonCommandes.show', compact('bonCommande'));
    }

    /**
     * Supprimer un bon de commande
     */
    public function destroy($id)
    {
        $bonCommande = Bon_commande::findOrFail($id);
        $bonCommande->delete();

        return back()->with('success', 'Bon de commande supprimé');
    }

    /**
     * Marquer comme envoyé
     */
    public function envoyer($id)
    {
        $bonCommande = Bon_commande::findOrFail($id);

        $bonCommande->update([
            'statut' => 'envoye'
        ]);

        return back()->with('success', 'Bon de commande envoyé');
    }

    /**
     * Marquer comme reçu + mise à jour du stock
     */
    public function recevoir($id)
    {
        $bonCommande = Bon_commande::with('details.article')->findOrFail($id);

        // Mise à jour du stock
        foreach ($bonCommande->details as $detail) {

            $article = $detail->article;

            if ($article) {
                $article->stock += $detail->quantite;
                $article->save();
            }
        }

        // Mise à jour statut
        $bonCommande->update([
            'statut' => 'recu'
        ]);

        return back()->with('success', 'Stock mis à jour, commande reçue');
    }


        /**
     * Convertir bon commande en vente
     */
    public function achat($id)
    {
        $bonCommande = Bon_commande::with('fournisseur', 'details.article')->where('statut', 'recu')->findOrFail($id);
        //dd($bonCommande);
        // Session 
        $session= Session_caisse::where('user_id', request()->user()->id)->whereNull('closed_at')->first();
         
        // Verification Ouverture session
        if(!$session) {
            return redirect()->back()->with('success', 'Vous n\'est pas autorisé !');
        }


        // Ajouter les produits
       foreach ($bonCommande->details as $detail) {
    
            // Récupération du produit original (si vous voulez copier ses propriétés)
            $produitOriginal = Article::find($detail->article_id);
            
            if (!$produitOriginal) {
                continue; // Passer à l'article suivant si non trouvé
            }
            
            $magasin = Magasin::where('id', $produitOriginal->magasin_id)->lockForUpdate()->firstOrFail();
            $entreprise = Entreprise::findOrFail(1);
            
            // Création du nouvel article basé sur le détail du bon de commande
            $article = Article::create([
                'fournisseur_id' => $bonCommande->fournisseur_id,
                'nom' => $detail->nom ?? $produitOriginal->nom, // Utilisez $detail->nom si disponible
                'description' => $detail->description ?? $produitOriginal->description ?? $detail->nom,
                'prix_achat' => $detail->prix_achat ?? $produitOriginal->prix_achat ?? 0,
                'prix_vente' => $detail->prix_vente ?? $produitOriginal->prix_vente ?? 0,
                'code' => $this->generateCode(),
                'reference' => 'REF-' . now()->timestamp . '-' . $detail->id,
                'stock' => $detail->quantite ?? 100,
                'stock_min' => 20,
                'categorie_id' => $detail->categorie_id ?? $produitOriginal->categorie_id ?? 1,
                'magasin_id' => $detail->magasin_id ?? $magasin->id ?? 1,
                'image' => $path ?? $entreprise->logo ?? null,
            ]);
            
            // Si vous voulez créer aussi un mouvement de stock entrant
            Mouvement_stock::create([
                'article_id' => $article->id,
                'type' => 'entree',
                'quantite' => $detail->quantite,
                'magasin_id' => $article->magasin_id,
                'reference' => 'BC-' . $bonCommande->reference . '-ENTREE',
                'date_mouvement' => now(),
            ]);



            // Creation de l'article dans le depot
            Article_depot::create([
                'article_id' => $article->id,
                'magasin_id' => $magasin->id,
                'stock' => $detail['quantite'],
            ]);

            // Enregistrement d'un historique de mouvement
            Mouvement_stock::create([
                'article_id' => $article->id,
                'type' => 'entree',
                'quantite' => $detail['quantite'] ?? 100,
                'magasin_id' => $magasin->id ?? 1,
                'reference' => 'MVT-' . now()->timestamp,
            ]);

     
        }

        return redirect()->route('articles.index')->with('success', 'Bon de commande converti en vente');
    }

    
    // Facture
    public function facture($id)
    {
        $entreprise= Entreprise::findOrFail(1);

        $bonCommande = Bon_commande::with('fournisseur', 'details')->findOrFail($id);

        $bonCommande->load(['fournisseur', 'details']);
        //dd($bonCommande);
        $pdf = Pdf::loadView('dashboard.bonCommandes.facture', compact('bonCommande', 'entreprise'));

        return $pdf->stream ('Facture-' . $bonCommande->reference . '.pdf');
    }

    
    // Generateur de code produit 
    private function generateCode(): string
    {
        $lastProduit = Article::orderBy('id', 'desc')->first();

        $number = $lastProduit ? intval(substr($lastProduit->code, -5)) + 1 : 1;

        return 'PRD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}