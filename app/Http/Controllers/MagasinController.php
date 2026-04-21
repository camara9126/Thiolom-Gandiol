<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Magasin;
use Illuminate\Http\Request;

class MagasinController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $magasins= Magasin::with('stock')->latest()->paginate(10);

        return view('dashboard.magasin.index', compact('magasins'));
    }

    /**
     * Recherche article par l'Admin.
     */
    public function search(Request $request)
    {
        $search = $request->query('search');

        $magasins = Magasin::with('stock')->when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%")->orWhereHas('stock', function ($q) use ($search) {

                        $q->where('article_id', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=;

        return view('dashboard.magasin.index', compact('magasins', 'search'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
        ]);

        Magasin::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
        ]);

        return redirect()->route('magasin.index')->with('success', 'Magasin ajouté avec succès');
    }


    public function update(Request $request, Magasin $magasin)
    {

        
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'statut' => 'nullable',
        ]);

        $magasin->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);

        return redirect()->route('magasin.index')->with('success', 'Magasin modifié');
    }

    public function liste($id)
    {
        $magasin= Magasin::findOrFail($id);
        $articles= $magasin->article()->withPivot('stock')->paginate(10);

        return view('dashboard.magasin.listeArticle', compact('magasin','articles'));
    }
}
