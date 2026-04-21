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
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des articles ( {{$articles->count()}} )</span>
                        <a href="{{route('articles.create')}}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Nouveau article →</a>
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
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Etiquette</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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
                                        <td><strong>{{$a->prix}} FCFA</strong></td>
                                        <td>
                                            @if($a->stock_min >= $a->stock)
                                                <span class="badge bg-danger">Stock faible</span>
                                            @else
                                                 <span class="badge-success">{{$a->stock}} en stock</span>
                                            @endif
                                        </td>
                                        <td>{{$a->etiquette ?? 'Pas d"etiquette'}}</td>
                                        <td><span class="badge-{{$a->statut ? 'success' : 'warning'}}">{{$a->statut ? 'Publié' : 'En attente'}}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('articles.edit', $a->id) }}" class="action-btn" title="Modifier"><i class="fas fa-edit"></i></a>
                                                <form action="{{route('articles.destroy', $a->id)}}" type="button" method="post" onsubmit="return confirm('Supprimer ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <!--<a href="" class="action-btn" title="Dupliquer"><i class="fas fa-copy"></i></a>-->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{$articles->links()}}
                            </div>
                        </div>
                    </div>
                </div>

@include('partials.footer')