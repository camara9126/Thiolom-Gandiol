<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Depenses;
use Illuminate\Http\Request;

class DepenseController extends Controller
{

    public function index(Request $request)
    {

        $depenses = Depenses::latest()->simplePaginate(10);

        return view('dashboard.depenses.index', compact('depenses'));
    }


     public function search(Request $request)
    {
        $search = $request->query('search');

        $depenses = Depenses::when($search, function ($query, $search) {

                $query->where('reference', 'like', "%{$search}%");

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=;

        return view('dashboard.depenses.index', compact('categories','depenses', 'search'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'mode_paiement' => 'required'
        ]);

        Depenses::create([
            'entreprise_id' => $request->user()->entreprise_id,
            'user_id' => $request->user()->id,
            'reference' => 'DEP-' . now()->timestamp,
            'libelle' => $request->libelle,
            'description' => $request->description,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'categorie_depense_id' => $request->categorie_depense_id,
            'mode_paiement' => $request->mode_paiement,
        ]);

        return redirect()->back()->with('success', 'Dépense enregistrée avec succès');
    }


    public function annuler($id)
    {
        $depense = Depenses::findOrFail($id);
        $depense->update(['statut' => 'annulee']);

        return back()->with('success', 'Dépense annulée avec succès');
    }
}
