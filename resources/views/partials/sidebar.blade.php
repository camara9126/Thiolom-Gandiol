<?php

    use App\Models\Article;
    use App\Models\Bon_commande;
    use App\Models\Categorie;
    use App\Models\Client;
    use App\Models\Devis;
    use App\Models\Entreprise;
    use App\Models\Fournisseur;
    use App\Models\Vente;

    $entreprise= Entreprise::findOrFail(1);
    $categories= Categorie::latest()->get();
    $articles= Article::latest()->get();
    $clients= Client::latest()->get();
    $fournisseurs= Fournisseur::latest()->get();
    $commandes= Vente::latest()->get();
    $devis= Devis::latest()->get();
    $bonCommandes= Bon_commande::latest()->get();

?>
       <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>
                    @if($entreprise->logo)
                        <img src="{{asset('storage/'.$entreprise->logo)}}" width="100" alt="">
                    @else
                        {{$entreprise->nom}}
                    @endif
                </h2>
                <p>Dashboard d'administration</p>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="">
                            <i class="fas fa-chart-pie"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="{{ route('articles.index') }}">
                            <i class="fas fa-box"></i>
                            <span>Articles</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categorie.index') }}">
                            <i class="fas fa-tags"></i>
                            <span>Catégories</span>
                            <span class="badge"></span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('fournisseurs.index') }}">
                            <i class="fas fa-truck"></i>
                            <span>Fournisseurs</span>
                            <span class="badge"></span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="{{ route('clients.index') }}">
                           <i class="fas fa-users"></i>
                            <span>Clients</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('commandes.index') }}">
                           <i class="fas fa-shopping-cart"></i>
                            <span>Vente</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('commandes.factures') }}">
                           <i class="fas fa-file-invoice"></i>
                            <span>Factures</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('commandes.pdv') }}">
                            <i class="fas fa-shop"></i>
                            <span>Point de vente</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    
                    <div class="sidebar-divider"></div>
                
                    <li>
                        <a href="{{ route('mouvements') }}">
                            <i class="fas fa-bars-staggered"></i>
                            <span>Mouvement stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bonCommande.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Bon de commande</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('devis.index') }}">
                            <i class="fas fa-receipt"></i>
                            <span>Devis</span>
                            <span class="badge"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('magasin.index') }}">
                            <i class="fas fa-building"></i>
                            <span>Magasins</span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>
                    @if(request()->user()->role == 'administrateur')
                        <li>
                            <a href="{{ route('paiements.index') }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Paiements</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('recettes.index') }}">
                                <i class="fas fa-right-left"></i>
                                <span>Recettes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('depenses.index') }}">
                                <i class="fas fa-arrow-right-from-bracket"></i>
                                <span>Depenses</span>
                            </a>
                        </li>
                    @endif

                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="{{ route('rapports') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Rapports</span>
                        </a>
                    </li>

                    

                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="{{ route('parametre') }}">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    
                    @if(request()->user()->role == 'administrateur')
                        <li>
                            <a href="{{ route('users.index') }}">
                                <i class="fas fa-user"></i>
                                <span>Utilisateur</span>
                            </a>
                        </li>
                    @endif
                  
                </ul>
            </nav>
        </aside>
