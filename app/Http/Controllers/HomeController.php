<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    //recherche client 
    public function search(Request $request)
    {
        $search = $request->query('search');
        $categories= Categorie::latest()->get();

        $articles = Article::with('categorie')->where('statut', true)->when($search, function ($query, $search) {

                $query->where('nom', 'like', "%{$search}%")->orWhereHas('categorie', function ($q) use ($search) {

                        $q->where('nom', 'like', "%{$search}%");
                });

        })->latest()->paginate(10)->withQueryString(); // 🔑 garde ?search=;

        return view('home.recherche', compact('search','articles','categories'));
    }


    // Detail article
    public function detail($slug) {

        $categories= Categorie::latest()->get();
        $article= Article::where('slug', $slug)->where('statut', true)->firstOrfail();
        $phares= Article::where('categorie_id', $article->categorie_id)->where('statut', true)->latest()->get();

        return view('home.detail', compact('article','categories','phares'));
    }


    // Detail categorie
    public function category($slug) {

        $categorie= Categorie::where('slug', $slug)->firstOrfail();
        $article= Article::where('categorie_id', $categorie->id)->where('statut', true)->latest()->get();
        $categories= Categorie::latest()->get();

        return view('home.catProduit', compact('categorie','article','categories'));
    }


    // Commande en ligne
    public function commande(Request $request)
    {
        $message = "🛒Bonjour ! Je veux cette article\n\n ";
         $request->validate([
            'nom' => 'required|string',
            'prix' => 'required|string|max:20',
            'categorie' => 'required',
            'image' ,
         ]);

         $imageUrl= asset('storage/'.$request->image);

         //dd($imageUrl);
         // Numero whatsapp
         $whatsapp = '221771764106'; 

         // message commande
         $message .= "{$imageUrl}\n\n";
         $message .= "Nom : {$request->nom}\n\n";
         $message .= "Categorie : {$request->categorie}\n\n";
         $message .= "Prix : {$request->prix} FCFA\n\n";
         

         $url = "https://wa.me/{$whatsapp}?text=" . urlencode($message);

         return redirect()->away($url);

    }

}
