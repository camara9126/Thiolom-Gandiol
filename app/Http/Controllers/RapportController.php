<?php

namespace App\Http\Controllers;

use App\Models\Depenses;
use App\Models\Entreprise;
use App\Models\Recettes;
use App\Models\Vente;
use App\Models\VenteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
     // Calcule des rapport
    public function rapports(Request $request)
    {

        $entreprise = Entreprise::findOrFail(1);

        /* Changement de mois */ 
        $mois = $request->mois ?? now()->month;
        $annee = $request->annee ?? now()->year;


        /* 1️⃣ Commandes par mois */
        $commandesParJour = Vente::selectRaw('DAY(created_at) jour, COUNT(*) total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('jour')->orderBy('jour')->get();

        $commandesMoisLabels = $commandesParJour->pluck('jour');
        $commandesMoisData = $commandesParJour->pluck('total');


        /* 2️⃣ Chiffre d’affaires par mois */
        $caParMois = Recettes::selectRaw('MONTH(created_at) as mois, SUM(montant) as total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->where('statut', 'recu')->groupBy('mois')->orderBy('mois')->get();

        $caLabels = $caParMois->pluck('mois')->map(fn ($m)=>
            Carbon::create()->month($m)->translatedFormat('M')
        );
        $caData = $caParMois->pluck('total');


        /* 3️⃣ Top articles du mois */
        $toparticles = VenteItem::selectRaw('article_id, SUM(quantite) as total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('article_id')->orderByDesc('total')->with('article:id,nom')->limit(5)->get();

        $toparticlesLabels = $toparticles->pluck('article.nom');
        $toparticlesData = $toparticles->pluck('total');


        /* 4️⃣ Statut des commandes */
        $statutCommandes = Vente::selectRaw('statut, COUNT(*) as total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('statut')->get();

        $statutLabels = $statutCommandes->pluck('statut');
        $statutData = $statutCommandes->pluck('total');


               /* Changement de mois */ 
        $mois = $request->mois ?? now()->month;
        $annee = $request->annee ?? now()->year;


        /* 1️⃣ Commandes par mois */
        $commandesParJour = Vente::selectRaw('DAY(created_at) jour, COUNT(*) total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('jour')->orderBy('jour')->get();

        $commandesMoisLabels = $commandesParJour->pluck('jour');
        $commandesMoisData = $commandesParJour->pluck('total');

        /* 2️⃣ Top articles du mois */
        $toparticles = VenteItem::selectRaw('article_id, SUM(quantite) as total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('article_id')->orderByDesc('total')->with('article:id,nom')->limit(5)->get();

        $toparticlesLabels = $toparticles->pluck('article.nom');
        $toparticlesData = $toparticles->pluck('total');


        // ===== 2em SECTION SUR LES DEPENSES ET RECETTES =====

            // ===== MENSUEL =====

            $months = [];
            $revenues = [];
            $expenses = [];
            $profits = [];

            for ($i = 1; $i <= 12; $i++) {

                $recette = Recettes::whereMonth('created_at', $i)->where('statut', 'recu')->whereYear('created_at', now()->year)->sum('montant');

                $depense = Depenses::whereMonth('created_at', $i)->where('statut', 'payee')->whereYear('created_at', now()->year)->sum('montant');

                $months[] = Carbon::create()->month($i)->translatedFormat('F');
                $revenues[] = round($recette, 2);
                $expenses[] = round($depense, 2);
                $profits[] = round($recette - $depense, 2);
            }

            $monthlyData = [
                'months' => $months,
                'revenues' => $revenues,
                'expenses' => $expenses,
                'profits' => $profits,
            ];

            // ===== TRIMESTRIEL =====

            $quarterlyData = [
                'quarters' => ['T1', 'T2', 'T3', 'T4'],
                'revenues' => [],
                'expenses' => [],
                'profits' => []
            ];

            for ($q = 1; $q <= 4; $q++) {

                $recette = Recettes::where('statut', 'recu')->whereBetween(DB::raw('MONTH(created_at)'), [($q-1)*3+1, $q*3])->sum('montant');

                $depense = Depenses::where('statut', 'payee')->whereBetween(DB::raw('MONTH(created_at)'), [($q-1)*3+1, $q*3])->sum('montant');

                $quarterlyData['revenues'][] = $recette;
                $quarterlyData['expenses'][] = $depense;
                $quarterlyData['profits'][] = $recette - $depense;
            }

            // ===== ANNUEL (3 dernières années) =====

            $years = [];
            $yearRevenue = [];
            $yearExpense = [];
            $yearProfit = [];

            for ($y = now()->year - 2; $y <= now()->year; $y++) {

                $r = Recettes::where('statut', 'recu')->whereYear('created_at', $y)->sum('montant');

                $d = Depenses::where('statut', 'payee')->whereYear('created_at', $y)->sum('montant');

                $years[] = $y;
                $yearRevenue[] = $r;
                $yearExpense[] = $d;
                $yearProfit[] = $r - $d;
            }

            $yearlyData = [
                'years' => $years,
                'revenues' => $yearRevenue,
                'expenses' => $yearExpense,
                'profits' => $yearProfit,
            ];


            // Top article mois
            $monthToparticles = DB::table('vente_items')->join('articles', 'vente_items.article_id', '=', 'articles.id')->select('articles.nom as article',
                        DB::raw('SUM(vente_items.total_ttc) as total'))->whereMonth('vente_items.created_at', now()->month)->groupBy('articles.nom')->orderByDesc('total')->limit(10)->get();

                $categories = $monthToparticles->pluck('article');
                $amounts = $monthToparticles->pluck('total');

                
            // Top article annee
            $yearToparticles = DB::table('vente_items')->join('articles', 'vente_items.article_id', '=', 'articles.id')->select('articles.nom as article', DB::raw('SUM(vente_items.total_ttc) as total'))->whereYear('vente_items.created_at', now()->year)->groupBy('articles.nom')->orderByDesc('total')->limit(10)->get();

            $yearCategories = $yearToparticles->pluck('articles');
            $yearAmounts = $yearToparticles->pluck('total');

        return view('dashboard.rapports', compact('entreprise','monthlyData','quarterlyData','yearlyData','categories', 'amounts','yearAmounts','yearCategories'));
    }
}
