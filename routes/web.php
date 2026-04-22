<?php

use App\Http\Controllers\articleController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\categorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MagasinController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\VenteController;
use App\Models\Article;
use App\Models\Bon_commande;
use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Magasin;
use App\Models\Mouvement_stock;
use App\Models\Vente;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('auth.login');
});

Route::get('/boutique', function () {

    $categories= Categorie::latest()->get();
    $articles= Article::where('statut', true)->latest()->paginate(12);
    $phares= Article::where('etiquette', 'nouveau')->where('statut', true)->latest()->get();

    return view('home.shop', compact('categories','articles','phares'));
})->name('boutique');

Route::get('/contact', function () {

    $categories= Categorie::latest()->get();

    return view('home.contact', compact('categories'));
})->name('contact');

Route::get('/test', function () {
     $clients = Client::latest()->get();
        $articles = Article::where('statut', true)->latest()->get();
        $magasins = Magasin::latest()->get();

    return view('dashboard.test', compact('clients','articles','magasins'));
})->name('test');


// Detail article
Route::get('/article/{slug}', [HomeController::class, 'detail'])->name('detail');

// Detail categorie
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');

// Recherche Client 
Route::get('/recherche', [HomeController::class, 'search'])->name('recherche');

// Route Commande en ligne
Route::post('/commande', [HomeController::class, 'commande'])->name('commande');

// Route Parametre
Route::get('/parametre', function() {
    return view('dashboard.parametre');
})->name('parametre');


// Route dashboard
Route::get('/dashboard', function () {
    $categories= Categorie::latest()->get();
    $articles= Article::latest()->get();
    $article= Article::limit(5)->latest()->get();
    $clients= Client::latest()->get();
    $commandes= Vente::latest()->get();
    $devis= Devis::latest()->get();
    $mouvements= Mouvement_stock::latest()->get();
    $bonCommandes= Bon_commande::latest()->get();

    /* Changement de mois */ 
    $mois = $request->mois ?? now()->month;
    $annee = $request->annee ?? now()->year;


    /* 1️⃣ Commandes par mois */
    $commandesParJour = Vente::selectRaw('DAY(created_at) jour, COUNT(*) total')->whereMonth('created_at', $mois)->whereYear('created_at', $annee)->groupBy('jour')->orderBy('jour')->get();

    $commandesMoisLabels = $commandesParJour->pluck('jour');
    $commandesMoisData = $commandesParJour->pluck('total');

    return view('dashboard.index', compact('articles','categories','article','clients','commandes','devis','mouvements','bonCommandes','commandesMoisLabels','commandesMoisData','annee'));
    
})->middleware(['auth', 'verified'])->name('dashboard');


// Route profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/{entreprise}', [ProfileController::class, 'entrepriseUpdate'])->name('entreprise.eUpdate');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [ProfileController::class, 'user'])->name('users.index');
    Route::post('/users', [ProfileController::class, 'userAdd'])->name('users.add');

});


Route::middleware('auth')->group(function () {
    Route::get('/rapport', [RapportController::class, 'rapports'])->name('rapports');
});


// Routes Articles et Categories
Route::middleware('auth')->group(function () {
    // Route Categorie
    Route::resource('/categorie', categorieController::class);

    // Recherche categorie par Admin
    Route::get('/csearch', [categorieController::class, 'search'])->name('categorie.search');

    // Route Article
    Route::resource('/articles', articleController::class);

    // Recheche article par Admin
    Route::get('/asearch', [articleController::class, 'search'])->name('article.search');

});

// Routes Mouvements et Magasin
Route::middleware('auth')->group(function () {
    Route::get('/mouvements', [MouvementController::class, 'index'])->name('mouvements');
    Route::post('/mouvements', [MouvementController::class, 'stock'])->name('stock');
    Route::get('/mouvementSearch', [MouvementController::class, 'search'])->name('mouvements.search');

    Route::resource('/magasin', MagasinController::class);
    Route::get('/magasinSearch', [MagasinController::class, 'search'])->name('magasins.search');
    Route::get('/magasinListe/{id}', [MagasinController::class, 'liste'])->name('magasin.liste');

});

// Routes Bon_Commande et Fournisseur
Route::middleware('auth')->group(function () {
    Route::resource('/bonCommande', BonCommandeController::class);
    Route::get('/bonCommandeSearch', [BonCommandeController::class, 'search'])->name('bonCommande.search');

    Route::get('bonCommande/{id}/envoyer', [BonCommandeController::class, 'envoyer'])->name('bonCommande.envoyer');
    Route::get('bonCommande/{id}/recevoir', [BonCommandeController::class, 'recevoir'])->name('bonCommande.recevoir');
    Route::get('bonCommande/{id}/facture', [BonCommandeController::class, 'facture'])->name('bonCommande.facture');

    Route::resource('/fournisseurs', FournisseurController::class);
    Route::get('/fournisseurSearch', [FournisseurController::class, 'search'])->name('fournisseurs.search');


});

// Route pour transferer les donnees clients et fournisseurs dans la table Tiers

// Routes Tiers(client/fournisseur), Devis et Commandes
Route::middleware('auth')->group(function () {

    Route::resource('/clients', ClientController::class);
    Route::get('/clientSearch', [ClientController::class, 'search'])->name('clients.search');

    Route::resource('/commandes', VenteController::class);
    Route::get('/commandeSearch', [VenteController::class, 'search'])->name('commandes.search');
    Route::get('/caisse/{id}', [VenteController::class, 'caisse'])->name('commandes.caisse');

    Route::resource('/devis', DevisController::class);
    Route::get('/devisSearch', [DevisController::class, 'search'])->name('devis.search');

    Route::get('/devis/{devis}/facture', [DevisController::class, 'facture'])->name('devis.facture');

    Route::get('/devis/{devis}/valider', [DevisController::class, 'valider'])->name('devis.valider');
    Route::get('/devis/{devis}/refuser', [DevisController::class, 'refuser'])->name('devis.refuser');
    Route::get('/devis/{devis}/convertir', [DevisController::class, 'convertir'])->name('devis.convertir');
    //Route::post('/client', [MouvementController::class, 'stock']);

});


// Routes Comptabilite, Depenses, Recettes et Paiements
Route::middleware('auth')->group(function () {

    Route::resource('/paiements', PaiementController::class);
    Route::get('/paiementSearch', [PaiementController::class, 'search'])->name('paiements.search');

    Route::put('/paiements/{id}/annuler', [PaiementController::class, 'annuler'])->name('paiements.annuler');

    Route::resource('/recettes', RecetteController::class);
    Route::resource('/depenses', DepenseController::class);

});


require __DIR__.'/auth.php';
