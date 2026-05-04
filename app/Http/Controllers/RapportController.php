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
    public function rapports()
    {

        $entreprise = Entreprise::findOrFail(1);

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
            $monthTopArticles = DB::table('vente_items')->join('articles', 'vente_items.article_id', '=', 'articles.id')->select('articles.nom as article',
                        DB::raw('SUM(vente_items.total_ttc) as total'))->whereMonth('vente_items.created_at', now()->month)->groupBy('articles.nom')->orderByDesc('total')->limit(10)->get();

                $categories = $monthTopArticles->pluck('article')->toArray();
                $amounts = $monthTopArticles->pluck('total')->toArray();

                
            // Top article annee
            $yearTopArticles = DB::table('vente_items')->join('articles', 'vente_items.article_id', '=', 'articles.id')->select('articles.nom as article', DB::raw('SUM(vente_items.total_ttc) as total'))->whereYear('vente_items.created_at', now()->year)->groupBy('articles.nom')->orderByDesc('total')->limit(10)->get();

            $yearCategories = $yearTopArticles->pluck('article')->toArray();
            $yearAmounts = $yearTopArticles->pluck('total')->toArray();

        return view('dashboard.rapports', compact('entreprise','monthlyData','quarterlyData','yearlyData','categories', 'amounts','yearAmounts','yearCategories'));
    }
}
