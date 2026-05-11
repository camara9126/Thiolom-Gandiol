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
                    <form method="get" action="{{route('mArticle.search')}}" class="form-inline">
                        
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
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des articles du depot ( {{$articles->count()}} )</span>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#transferModal">
                            Transfers
                        </button>
                        <a href="{{ route('magasin.index') }}" type="button" class="btn btn-outline-danger">
                            Retour
                        </a>
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
                        <div class="table-responsive">
                            <div class="d-flex justify-content-center mt-4">
                                {{$articles->links()}}
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <!--<th>Etiquette</th>-->
                                        <!--<th>Statut</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $a)
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <img src="{{asset('storage/'. $a->image)}}" width="50" alt="">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <div style="font-weight: 600;">{{$a->code}}</div>
                                                <!--<div style="font-size: 0.85rem; color: var(--gray-600);">GBH 2-26</div>-->
                                            </div>
                                        </td>
                                        <td>{{$a->nom}}</td>
                                        <td>{{$a->categorie->nom}}</td>
                                        <td><strong>{{number_format($a->prix_vente, 0, ',', ' ')}} FCFA</strong></td>
                                        <td>
                                            @if($a->stock_min >= $a->stock)
                                                <span class="badge bg-danger">Stock faible</span>
                                            @else
                                                 <span class="badge-success">{{$a->pivot->stock}} en stock</span>
                                            @endif
                                        </td>
                                        <!--<td>{{$a->etiquette ?? 'Pas d"etiquette'}}</td>-->
                                        <!--<td><span class="badge-{{$a->statut ? 'success' : 'warning'}}">{{$a->statut ? 'Publié' : 'En attente'}}</span></td>-->
                                        <!--<td>
                                            <div class="action-buttons">
                                                <a href="" class="action-btn" title="Modifier"><i class="fas fa-edit"></i></a>
                                                <form action="" type="button" method="post" onsubmit="return confirm('Supprimer ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="" class="action-btn" title="Dupliquer"><i class="fas fa-copy"></i></a>
                                            </div>
                                        </td>-->
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Transfers article -->
                            <div class="modal fade" id="transferModal" tabindex="-1">
                                <div class="modal-dialog">
                                <form method="post" action="{{route('stock')}}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Transfers Stock</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Produit</label>
                                                <select class="form-control" name="article_id" id="exampleFormControlSelect1">
                                                    <option value="">-- Veuillez choisir un produit --</option>
                                                    @foreach($articles as $a)
                                                    <option value="{{$a->id}}">{{$a->nom}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Magasin</label>
                                                <select class="form-control" name="destination_id" id="exampleFormControlSelect1">
                                                    <option value="">-- Veuillez choisir la destination --</option>
                                                    @foreach($magasins as $m)
                                                    <option value="{{$m->id}}">{{$m->nom}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Quantite</label>
                                                <input type="number" name="quantite" min="1" class="form-control" id="exampleInputquantity1">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@include('partials.footer')