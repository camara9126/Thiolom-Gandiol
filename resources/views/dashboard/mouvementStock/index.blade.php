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
                    <form method="get" action="{{route('mouvements.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche stock...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Mouvements Stock</h1>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#exampleModal">
                            Nouveau mouvement →
                        </button>
                        
                    </div>
                </div>

                <!-- Recent Products Table -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-list" style="color: var(--primary); margin-right: 0.5rem;"></i> Liste des mouvements</span>
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
                            <table>
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Produit</th>
                                        <th>Type</th>
                                        <th>Quantite</th>
                                        <th>Magasin</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mouvements as $m)
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <div>
                                                    <div style="font-weight: 600;">{{$m->reference}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$m->article->nom}}</td>
                                        <td>{{$m->type}}</td>
                                        <td><strong>{{$m->quantite}}</strong></td>
                                        <td>{{ $m->magasin->nom }}</td>
                                        <td>{{$m->created_at->format('d/m/Y')}}</td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" align="center">Donnee vide !</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> 
                         @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif 
                        <!-- Modal Nouveau mouvement stck-->
                        <div class="modal fade" id="exampleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="post" action="{{route('stock')}}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Mouvement Stock</h5>
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
                                                <select class="form-control" name="magasin_id" id="exampleFormControlSelect1">
                                                    <option value="">-- Veuillez choisir un magasin --</option>
                                                    @foreach($magasins as $m)
                                                    <option value="{{$m->id}}">{{$m->nom}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Type</label>
                                                <select class="form-control" name="type" id="exampleFormControlSelect1">
                                                    <option value="">-- Veuillez choisir le type de mouvement --</option>
                                                    <option value="entree">Entree</option>
                                                    <option value="sortie">Sortie</option>
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
        </main>
    </div>

@include('partials.footer')