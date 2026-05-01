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
                        <a href="{{route('articles.create')}}" style="color: var(--primary); text-decoration: none; font-weight: 500;" data-bs-toggle="modal"  data-bs-target="#articleModal">Nouveau article →</a>
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
                                        <th style="background-color: #E5D8FF;">Image</th>
                                        <th style="background-color: #E5D8FF;">Code</th>
                                        <th style="background-color: #E5D8FF;">Produit</th>
                                        <!--<th style="background-color: #E5D8FF;">Catégorie</th>-->
                                        <th style="background-color: #E5D8FF;">Prix</th>
                                        <th style="background-color: #E5D8FF;">Stock</th>
                                        <!--<th style="background-color: #E5D8FF;">Etiquette</th>-->
                                        <th style="background-color: #E5D8FF;">Statut</th>
                                        <th style="background-color: #E5D8FF;">Actions</th>
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
                                        <!--<td>{{$a->categorie->nom}}</td>-->
                                        <td><strong>{{$a->prix_vente}} FCFA</strong></td>
                                        <td>
                                            @if($a->stock_min >= $a->stock)
                                                <span class="badge bg-danger">Stock faible</span>
                                            @else
                                                 <span class="badge-success">{{$a->stock}} en stock</span>
                                            @endif
                                        </td>
                                        <!--<td>{{$a->etiquette ?? 'Pas d"etiquette'}}</td>-->
                                        <td><span class="badge-{{$a->statut ? 'success' : 'warning'}}">{{$a->statut ? 'Publié' : 'En attente'}}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="" class="action-btn" title="Modifier"  data-bs-toggle="modal" data-id="{{ $a->id }}" data-name="{{ $a->nom }}" data-categorie="{{ $a->categorie_id }}" data-magasin="{{ $a->magasin_id }}" data-price="{{ $a->prix_vente }}" data-description="{{ $a->description }}" data-stock="{{ $a->stock }}" data-image="{{ asset('storage/'.$a->image) }}" data-bs-target="#articleEditModal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                        <!-- Nouveau article -->
                        <div class="modal fade" id="articleModal" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="post" action="{{route('articles.store')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nouveau article</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label>Image</label>
                                                <input type="file" name="image" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label>Nom de l'article</label>
                                                <input type="text" name="nom" class="form-control" required>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Categorie</label>
                                                        <select name="article_id" class="form-control">
                                                            @foreach($categorie as $m)
                                                                <option value="{{ $m->id }}">{{ $m->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Depot</label>
                                                        <select name="magasin_id" class="form-control">
                                                            @foreach($magasins as $m)
                                                                <option value="{{ $m->id }}">{{ $m->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>         
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label>Quantite de stock</label>
                                                <input type="number" name="stock" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Prix</label>
                                                <input type="text" name="prix_vente" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description"  class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                            <!-- Edit article -->
                        <div class="modal fade" id="articleEditModal" tabindex="-1">
                            <div class="modal-dialog">

                                <form method="post" id="editArticleForm" action="" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modification article</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden" name="id" id="article_id">
                                            <input type="hidden" name="categorie_id" id="categorie_id">
                                            <input type="hidden" name="magasin_id" id="magasin_id">

                                            <div class="mb-3">
                                                <label>Image</label>
                                                <img src="image" id="image" width="100" alt="">
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label>Nom de l'article</label>
                                                <input type="text" name="nom" id="name" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Quantite de stock</label>
                                                <input type="number" name="stock" id="stock" class="form-control" required>
                                            </div>
                                        
                                            <div class="mb-3">
                                                <label>Prix</label>
                                                <input type="text" name="prix_vente" id="price" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description" id="description" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

     <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('articleEditModal');
            const form = document.getElementById('editArticleForm');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Récupération des données
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const price = button.getAttribute('data-price');
                const description = button.getAttribute('data-description');
                const stock = button.getAttribute('data-stock');
                const image = button.getAttribute('data-image');
                const categorie_id = button.getAttribute('data-categorie');
                const magasin_id = button.getAttribute('data-magasin');
                
                // Remplir le formulaire
                modal.querySelector('#article_id').value = id;
                modal.querySelector('#name').value = name;
                modal.querySelector('#price').value = price;
                modal.querySelector('#description').value = description;
                modal.querySelector('#stock').value = stock;
                modal.querySelector('#image').src = image;
                modal.querySelector('#categorie_id').value = categorie_id;
                modal.querySelector('#magasin_id').value = magasin_id;
                
                // Mettre à jour l'action du formulaire avec l'ID récupéré
                const updateUrl = `/articles/${id}`;
                form.action = updateUrl;
            });
        });
    </script>

@include('partials.footer')