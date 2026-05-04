<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Bon_commande;
use App\Models\Entreprise;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {


        $fournisseurs = Fournisseur::latest()->paginate(10);

        return view('dashboard.fournisseurs.index', compact('fournisseurs'));
    }


    public function search(Request $request)
    {
        $search = $request->query('search');

        $fournisseurs = Fournisseur::with('article')->when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%")->orWhereHas('article', function ($q) use ($search) {

                        $q->where('telephone', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=

        return view('dashboard.fournisseurs.index', compact('fournisseurs','search'));

    }


    public function create()
    {
        return view('dashboard.fournisseurs.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
        ]);

        Fournisseur::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
        ]);

        return redirect()->back()->with('success', 'Fournisseur ajouté avec succès');
    }


    public function edit(Fournisseur $fournisseur)
    {

        return view('dashboard.fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, fournisseur $fournisseur)
    {

        
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
            'statut' => 'nullable',
        ]);

        $fournisseur->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
        ]);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur modifié');
    }


    // Liste des articles venant du fournisseur
    public function show($id)
    {
        //dd($id);
        $articles= Article::where('fournisseur_id', $id)->latest()->paginate(50);

        return view('dashboard.fournisseurs.listeArticles', compact('articles'));
    }


    // Liste des factures venant du fournisseur
    public function factures($id)
    {
        //dd($id);
        $factures= Bon_commande::where('fournisseur_id', $id)->latest()->paginate(50);

        return view('dashboard.fournisseurs.factures', compact('factures'));
    }


    // Facture
    public function pdf($id)
    {
        $entreprise= Entreprise::findOrFail(1);

        $bonCommande = Bon_commande::with('fournisseur', 'details')->findOrFail($id);

        $bonCommande->load(['fournisseur', 'details']);
        //dd($devis);
        $pdf = Pdf::loadView('dashboard.fournisseurs.pdf', compact('bonCommande', 'entreprise'));

        return $pdf->stream ('Facture-' . $bonCommande->reference . '.pdf');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if($fournisseur->statut) {
            $fournisseur->update(['statut' => false]);
            return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur désactivé');
        }
        else {
            $fournisseur->update(['statut' => true]);
            return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur activé');
        }

    }
}
