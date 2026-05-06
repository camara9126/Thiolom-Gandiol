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
                    <form method="get" action="{{route('categorie.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Rechercher...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>  
   
  
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-tags" style="color: var(--primary); margin-right: 0.5rem;"></i> Catégories</span>
                        <a href="{{ route('categorie.create') }}" class="btn-primary" style="padding: 0.375rem 1rem; font-size: 0.9rem;">
                            <i class="fas fa-plus"></i> Nouvelle catégorie
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
                    <div class="d-flex justify-content-center mt-4">
                        {{$categorie->links()}}
                    </div>  
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Description</th>
                                        <th>Nombre d'articles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorie as $c)
                                        <tr>  
                                            <td><strong>{{$c->nom}}</strong></td>
                                            <td>{{$c->description}}</td>
                                            <td>{{$c->article->count() ?? '0'}}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="" class="action-btn"  data-bs-toggle="modal" data-id="{{ $c->id }}" data-name="{{ $c->nom }}" data-description="{{ $c->description }}" data-image="{{ asset('storage/'.$c->image) }}" data-bs-target="#categorieEditModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{route('categorie.destroy', $c->id)}}" type="button" method="post" onsubmit="return confirm('Supprimer ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete" title="Supprimer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Edit catagorie -->
                            <div class="modal fade" id="categorieEditModal" tabindex="-1">
                                <div class="modal-dialog">

                                    <form method="post" id="editCategorieForm" action="" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modification categorie</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="categorie_id">

                                                <div class="mb-3">
                                                    <label>Image</label>  
                                                    <img src="image" name="image" id="image" width="100" alt="">
                                                    <input type="file" name="image" id="image" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Nom du categorie</label>
                                                    <input type="text" name="nom" id="name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
                </div>


                    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('categorieEditModal');
            const form = document.getElementById('editCategorieForm');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Récupération des données
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                const image = button.getAttribute('data-image');
                
                // Remplir le formulaire
                modal.querySelector('#categorie_id').value = id;
                modal.querySelector('#name').value = name;
                modal.querySelector('#description').value = description;
                modal.querySelector('#image').src = image;
                
                // Mettre à jour l'action du formulaire avec l'ID récupéré
                const updateUrl = `/categorie/${id}`;
                form.action = updateUrl;
            });
        });
    </script>
@include('partials.footer')
