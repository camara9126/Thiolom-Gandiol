<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bon_commande;
use App\Models\Bon_commande_details;
use Illuminate\Http\Request;
use App\Models\Fournisseur;
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
            'articles.*.prix' => 'required|numeric|min:0',
            'note' => 'nullable',
        ]);

        // Création du bon de commande
        $bonCommande = Bon_commande::create([
            'reference' => 'BC-' . strtoupper(Str::random(6)),
            'fournisseur_id' => $request->fournisseur_id,
            'total' => 0,
            'statut' => 'en_attente',
            'date_commande' => now(),
            'note' => $request->note ?? 'null',
        ]);

        $total = 0;

        foreach ($request->articles as $item) {

            $ligneTotal = $item['quantite'] * $item['prix'];

            Bon_commande_details::create([
                'bon_commande_id' => $bonCommande->id,
                'article_id' => $item['article_id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
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
    // Facture
    public function facture($id)
    {
        $articles= Article::latest()->get();

        $bonCommande = Bon_commande::with('fournisseur', 'details')->findOrFail($id);

        $bonCommande->load(['fournisseur', 'details']);
//dd($devis);
        $pdf = Pdf::loadView('dashboard.bonCommandes.facture', compact('bonCommande'));

        return $pdf->stream ('Facture-' . $bonCommande->reference . '.pdf');
    }
}