<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class categorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorie= Categorie::latest()->simplePaginate(10);
        return view('dashboard.categories.index', compact('categorie'));
    }


        /**
     * Recherche article.
     */
    public function search(Request $request)
    {
        $search = $request->query('search');

        $categorie = Categorie::when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%");

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=;

        return view('dashboard.categories.index', compact('categorie', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required','string',
            'description' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion des l'images
        if ($request->hasFile('image')) {
            $filename = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('imgCategories', $filename, 'public');
            $request['image'] = '/storage/' . $path;
        } else {
            dd('Aucun fichier image reçu');
        }

        // creation du categorie
        $categories= Categorie::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'image' => $path,
        ]);
        // dd($categories);
        return redirect()->route('categorie.index', compact('categories'))->with('success', 'Categorie crée avec success.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $categorie= Categorie::findOrFail($id);

        $categorie->destroy($id);

        return redirect()->route('categorie.index')->with('success', 'Categorie supprimée avec success.');
    }
}
