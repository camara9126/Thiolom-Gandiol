@include('partials.header')
    <div class="dashboard">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <form method="get" action="{{route('article.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Rechercher...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Affichage du bon de commande </span>
                        <a href="{{ route('achats.index') }}" class="btn btn-outline-danger">Retour</a>
                    </div>
                    
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @elseif(Session::has('danger'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('danger') }}
                        </div>
                    @endif

                    <div class="card-body">
                        @if ($errors->any())
                            <div style="color: red; margin-bottom: 10px;">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif


                            <!-- INFOS ENTREPRISE -->
                            <div class="mb-4">
                                <h4>{{ $achat->entreprise->nom ?? 'Entreprise' }}</h4>
                                <p>Date : {{ $achat->date_commande }}</p>
                                <p>Référence : {{ $achat->reference }}</p>

                                <p>
                                    Statut :
                                    @if($achat->statut == 'en_attente')
                                        <span class="badge bg-info">Partiel</span>
                                    @elseif($achat->statut == 'annule')
                                        <span class="badge bg-danger">Impayé</span>
                                    @else
                                        <span class="badge bg-success">Payé</span>
                                    @endif
                                </p>
                            </div>
                            <hr>
                            <!-- FOURNISSEUUR - CHAUFFEUR -->
                             <div class="row">
                                <div class="col-10">
                                    <div class="mb-4">
                                        <h5>Fournisseur</h5>
                                        <p>Nom Complet : {{ strtoupper($achat->fournisseur->nom) }}</p>
                                        <p>Téléphone : {{ $achat->fournisseur->telephone ?? '-' }}</p>
                                    </div>
                                </div>
                             </div>
                            <hr>

                            <!-- TABLE PRODUITS -->
                            <table class="">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($achat->details as $detail)
                                        <tr>
                                            <td>{{ $detail->article->nom ?? '-' }}</td>
                                            <td>{{ $detail->quantite }}</td>
                                            <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ number_format($detail->total, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- TOTAL -->
                            <div class="text-end mt-3">
                                <h4>Total : {{ number_format($achat->total, 0, ',', ' ') }} FCFA</h4>
                            </div>

                    </div>
                </div>


    
@include('partials.footer')