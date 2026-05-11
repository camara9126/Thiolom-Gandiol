<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Article_depot;
use App\Models\Magasin;
use App\Models\Transfers_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransfersStockController extends Controller
{
    /**
     * Liste des transferts
     */
    public function index()
    {
        $transferts = Transfers_stock::with(['article'])->latest()->get();

        return view('dashboard.transferts.index', compact('transferts'));
    }

    /**
     * Formulaire création transfert
     */
    public function create()
    {
        $articles = Article::all();

        $magasins = Magasin::all();

        return view('dashboard.transferts.create', compact('articles','magasins'));
    }

    /**
     * Enregistrer transfert
     */
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'source_id' => 'exists:magasins,id',
            'destination_id' => 'required|exists:magasins,id|different:magasin_source_id',
            'quantite' => 'required|numeric|min:1',
        ]);
//dd($request->all());
        DB::beginTransaction();

        try {

            /**
             * Vérifier stock source
             */
            $stockSource = Article_depot::where('article_id', $request->article_id)->where('magasin_id', $request->source_id)->first();

            if (!$stockSource) {

                return back()->with('success','article introuvable dans le dépôt source');
            }

            if ($stockSource->stock < $request->quantite) {

                return back()->with('success','Stock insuffisant dans le dépôt source');
            }

            /**
             * Retirer stock dépôt source
             */
            $stockSource->decrement('stock', $request->quantite);
            /**
             * Vérifier si article existe déjà dans dépôt destination
             */
            $destination = Article_depot::where('article_id', $request->article_id)->where('magasin_id', $request->destination_id)->first();

            if ($destination) {

                /**
                 * Ajouter stock destination
                 */
                $destination->increment('stock', $request->quantite);

            } else {

                /**
                 * Créer nouvelle ligne stock
                 */
                Article_depot::create([
                    'article_id' => $request->article_id,
                    'magasin_id' => $request->destination_id,
                    'stock' => $request->quantite,
                ]);
            }

            /**
             * Historique transfert
             */
            Transfers_stock::create([
                'article_id' => $request->article_id,
                'source_id' => $request->source_id,
                'destination_id' => $request->destination_id,
                'quantite' => $request->quantite,
            ]);

            DB::commit();

            return redirect()->route('magasin.index')->with('success','Transfert effectué avec succès');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error','Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Détails transfert
     */
    public function show($id)
    {
        $transfert = Transfers_stock::with(['article','source','destination'])->findOrFail($id);

        return view('dashboard.transferts.show', compact('transfert'));
    }

    /**
     * Supprimer transfert
     */
    public function destroy($id)
    {
        $transfert = Transfers_stock::findOrFail($id);

        DB::beginTransaction();

        try {

            /**
             * Retour stock destination
             */
            DB::table('article_depot')->where('article_id', $transfert->article_id)->where('magasin_id', $transfert->destination_id)->decrement('stock', $transfert->quantite);

            /**
             * Remettre stock source
             */
            DB::table('article_depot')->where('article_id', $transfert->article_id)->where('magasin_id', $transfert->source_id)->increment('stock', $transfert->quantite);

            /**
             * Supprimer historique
             */
            $transfert->delete();

            DB::commit();

            return back()->with('success','Transfert annulé avec succès'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error','Erreur : ' . $e->getMessage());
        }
    }
}
