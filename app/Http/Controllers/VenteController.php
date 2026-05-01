<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Client;
use App\Models\Depenses;
use App\Models\Entreprise;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use App\Models\Paiements;
use App\Models\Recettes;
use App\Models\Session_caisse;
use App\Models\User;
use App\Models\Vente;
use App\Models\VenteItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $user= request()->user();

        // Session Administrateur
        $today = now()->toDateString();
        
        $ventes = Vente::with('client')->latest()->simplePaginate(10); 

        $total = Vente::whereDate('created_at', $today)->sum('total');

        $depensesJour = Depenses::where('statut', 'payee')->whereDate('created_at', $today)->sum('montant');

        $totalEncaisse = Vente::with('client')->get()->sum('montant_paye');
        
        $ventesJour = Vente::whereDate('created_at', $today)->get();

        // Session Caisse
        $session= Session_caisse::where('user_id', $user->id)->whereNull('closed_at')->first();

        // Verification Ouverture session
        if(!$session) {
            return redirect()->route('commandes.pdv')->with('success', 'Aucun session ouverte');
        }

        $vente= Vente::where('session_caisse_id', $session->id)->get();

        $session->update([
            'nombre_ventes' => $vente->count(),
            'total_ventes' => $vente->sum('total'),
            'total_encaisse' => $vente->sum('montant_paye'),
        ]);

        return view('dashboard.commandes.index', compact('ventes','ventesJour','total','totalEncaisse','depensesJour','user','session'));
    }


    public function search(Request $request)
    {
        $user= request()->user();

        $search = $request->query('search');

        $today = now()->toDateString();

        $total = Vente::whereDate('created_at', $today)->sum('total');

        $depensesJour = Depenses::where('statut', 'payee')->whereDate('created_at', $today)->sum('montant');

        $totalEncaisse = ((Paiements::with('vente')->where('statut', 'valide')->whereDate('created_at', $today)->sum('montant')) - ($depensesJour));

        $totalReste = $totalEncaisse - $depensesJour;
        
        $ventesJour = Vente::whereDate('created_at', $today)->get();

        $ventes = Vente::when($search, function ($query, $search) {

                $query->where('reference', 'like', "%{$search}%")->orWhereHas('client', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=


        return view('dashboard.commandes.index', compact('ventes', 'search', 'ventesJour','total','totalEncaisse','totalReste','depensesJour','user'));
    }


    // Liste de factures
    public function facture()
    {
        $factures = Vente::with('client')->latest()->simplePaginate(10); 

        return view('dashboard.commandes.factures', compact('factures'));
    }


    public function create(Request $request)
    {
        $clients = Client::latest()->get();
        $articles = Article::where('statut', true)->latest()->get();

        $article= $request->pdvSearch;

        return view('dashboard.commandes.create', compact('clients', 'articles', 'article'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'client_id' ,
            'articles' => 'required|array|min:1',
            'statut' ,
            'articles.*.article_id' => 'required',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix_vente' => 'required|numeric|min:0',
            'montant' => 'numeric|min:0'
        ]);
       
//dd($request->all());
        foreach ($request->articles as $item) {

           
             if (empty($item['article_id'])) {
                continue;
            }

            $produit = Article::where('id', $item['article_id'])->lockForUpdate()->firstOrFail(); // verrou stock
            $magasin = Magasin::where('id', $produit->magasin_id)->lockForUpdate()->firstOrFail(); // verrou stock
            //dd($magasin);
            // Verification de la disponibilite de l'article dans le magasin
            $stock = Article_depot::where('article_id', $produit->id)->where('magasin_id', $magasin->id)->first();

            if($stock) {
                if ($stock->stock < $item['quantite']) {
                    return redirect()->back()->with('danger', 'Stock insuffisant dans ce dépôt');
                }

                // 🔥 Déduire le stock
                Article_depot::where('article_id', $produit->id)->where('magasin_id', $magasin->id)->decrement('stock', $item['quantite']);
            } else {
                return redirect()->back()->with('danger', 'Stock introuvable dans ce dépôt');
            }
            

            // Verification stock mouvement
            if ($produit->stock == 0) {

                 return redirect()->back()->with('danger','Vous devez enregister un mouvement d"abord');
            }

            // Alert stock minimum depasse
            if ($produit->stock <= $produit->stock_min) {
                return redirect()->back()->with('danger','Votre stock minimum est depasse');
            }


            // Verification quantite de stock
            if ($produit->stock < $item['quantite']) {
                
                return redirect()->back()->with('danger','Stock insuffisant pour cette article ');
            }

            // Session 
            $session= Session_caisse::where('user_id', request()->user()->id)->whereNull('closed_at')->first();

            //dd($request->montant);
            $vente = Vente::create([
                'client_id' =>  $request->client_id ?? 2,
                'session_caisse_id' => $session->id,
                'reference' => 'VNT-' . time(),
                'date' => now(),
                'total' => 0,
                'total_tva' => 0,
                'total_ttc' => 0,
                'statut' => 'impayee',
                'user_id' => $request->user()->id,
            ]);

            $total = 0;
            $total_tva = 0;
            $total_ttc = 0;

            // Creation vente item
            $entreprise= Entreprise::findOrFail(1); // Recuperation de la TVA de l'entreprise

            
            VenteItem::create([
                'vente_id' => $vente->id,
                'article_id' => $item['article_id'],
                'magasin_id' => $produit->magasin_id,
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix_vente'],
                'taux_tva' => $entreprise->taux_tva,
                'montant_tva' => ($item['quantite'] * $item['prix_vente']) * ($entreprise->taux_tva /100 ),
                'total_ttc' => ($item['quantite'] * $item['prix_vente']) + (($item['quantite'] * $item['prix_vente']) * ($entreprise->taux_tva /100 )),
                'total' => $item['quantite'] * $item['prix_vente'],
            ]);

            // Mise a jour stock
            $produit->decrement('stock', $item['quantite']);

            // Enregistrememt historique stock
                Mouvement_stock::create([
                    'article_id' => $produit->id,
                    'type' => 'sortie',
                    'quantite' => $item['quantite'],
                    'magasin_id' => $produit->magasin_id,
                    'reference' => 'MVT-' . now()->timestamp,
                ]);

            // Calcule total + total_tva + total_ttc
            $total += $item['quantite'] *  $item['prix_vente'];
            $total_tva += ($item['quantite'] * $item['prix_vente']) * ($entreprise->taux_tva /100 );
            $total_ttc += ($item['quantite'] * $item['prix_vente']) + (($item['quantite'] * $item['prix_vente']) * ($entreprise->taux_tva /100 ));
            
            // Mise a jour total + total_tva + total_ttc
            $vente->update([
                'total' => $total,
                'total_tva' => $total_tva,
                'total_ttc' => $total_ttc,
            ]);
            
        }
        
            // creation paiement
            $paiement = $vente;

            $totalPaye = $paiement->paiements()->where('statut','valide')->sum('montant');

            $paiements= Paiements::create([
                'vente_id' => $vente->id,
                'user_id' => request()->user()->id,
                'montant' => $vente->total_ttc,
                'mode_paiement' => 'cash',
                'date_paiement' => now(),
                'statut' => 'valide',
                'reference' => 'PAY-' . time()
            ]);


            // Mise à jour du statut de la vente
            $vente = $paiements->vente;

            $totalPaye = $vente->paiements()->where('statut','valide')->sum('montant');

            $vente->statut = $totalPaye == 0 ? 'impayee' : ($totalPaye < $vente->total_ttc ? 'partielle' : 'payee');

            $vente->save();


            // 2. Création automatique de la recette
            if($vente->statut == 'payee') {
                 Recettes::create([
                    'user_id' => $request->user()->id,
                    'paiement_id' => $paiements->id,
                    'reference' => 'REC-' . now()->timestamp,
                    'libelle' => 'Paiement vente ' . $vente->reference,
                    'montant' => $vente->total_ttc,
                    'date_recette' => now(),
                    'mode_paiement' => 'cash',
                    'statut' => 'recu',
                ]);
            }
           

            return redirect()->route('commandes.index')->with('success', 'Vente effectuée avec succès');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vente= Vente::findOrFail($id);        
        $vente->destroy($id);


        return redirect()->route('commandes.index')->with('success', ' vente supprimé avec succès');        
    }

    
    // Facture PDF
    public function show($id)
    {

        $entreprise= Entreprise::findOrFail(1);
        $vente= Vente::with('client', 'items', 'paiements')->findOrFail($id);
//dd($vente);
        $vente->load(['client', 'items', 'paiements']);

        $pdf = Pdf::loadView('dashboard.commandes.PDF', compact('vente', 'entreprise'));

        return $pdf->stream('Facture-' . $vente->reference . '.pdf');
    }

    // Ticket de caisse
    public function ticket($id)
    {

        $entreprise= Entreprise::findOrFail(1);
        $vente= Vente::with('client', 'items', 'paiements')->findOrFail($id);
        //dd($vente);
        $vente->load(['client', 'items', 'paiements']);

        $pdf = Pdf::loadView('dashboard.commandes.ticket', compact('vente', 'entreprise'))
                ->setPaper([0, 0, 226.77, 600]);

        return $pdf->stream('Ticket-' . $vente->reference . '.pdf');
    }

}
