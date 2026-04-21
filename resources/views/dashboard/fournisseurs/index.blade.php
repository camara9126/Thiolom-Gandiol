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
                    <form method="get" action="{{route('fournisseurs.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche fournisseur...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">

                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @elseif(Session::has('danger'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('danger') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des founisseurs ({{$fournisseurs->count()}})</span>
                       <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#fournisseurModal">
                            Nouveau fournisseur →
                        </button>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                               <table class="">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Telephone</th>
                                            <th>Email</th>
                                            <th>Adresse</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fournisseurs as $f)
                                        <tr>
                                            <td>{{$f->nom}}</td>
                                            <td>{{$f->telephone ?? 'Vide'}}</td>
                                            <td>{{$f->email ?? 'Vide'}}</td>
                                            <td>{{$f->adresse ?? 'Vide'}}</td>
                                            <td>
                                                @if($f->statut)
                                                    <span class="badge bg-success">Activé</span>
                                                    @else
                                                    <span class="badge bg-danger">Desactivé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="" class="action-btn" data-bs-toggle="modal" data-id="{{ $f->id }}" data-name="{{ $f->nom }}" data-phone="{{ $f->telephone }}" data-email="{{ $f->email }}" data-adress="{{$f->adresse }}" data-bs-target="#fournisseurEditModal" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($f->statut)
                                                        <form action="{{route('fournisseurs.destroy', $f->id)}}" type="button" method="post" onsubmit="return confirm('Desactiver ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-btn " title="Desactiver">
                                                                <i class="fa fa-toggle-on"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{route('fournisseurs.destroy', $f->id)}}" type="button" method="post" onsubmit="return confirm('Activer ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-btn delete" title="Activer">
                                                                <i class="fa fa-toggle-off"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <!--<a href="" class="action-btn" title="Dupliquer"><i class="fas fa-copy"></i></a>-->
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" align="center">Donnee vide !</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>  
                            <div class="d-flex justify-content-center mt-4">
                                {{$fournisseurs->links()}}
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

                            <!-- Nouveau fournisseur -->
                            <div class="modal fade" id="fournisseurModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="{{route('fournisseurs.store')}}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nouveau fournisseur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nom du fournisseur</label>
                                                    <input type="text" name="nom" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Téléphone</label>
                                                    <input type="text" name="telephone" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Adresse</label>
                                                    <textarea name="adresse" id=""></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                             <!-- Edit founisseur -->
                            <div class="modal fade" id="fournisseurEditModal" tabindex="-1">
                                <div class="modal-dialog">

                                    <form method="post" id="editFournisseurForm" action="">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modification fournisseur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="founisseur_id">

                                                <div class="mb-3">
                                                    <label>Nom du founisseur</label>
                                                    <input type="text" name="nom" id="name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Téléphone</label>
                                                    <input type="text" name="telephone" id="phone" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" id="email" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Adresse</label>
                                                    <textarea name="adresse" id="adress" class="form-control" rows="3"></textarea>
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

    <!--Recuperation des donnees founisseur pour l'Edit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('fournisseurEditModal');
            const form = document.getElementById('editFournisseurForm');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Récupération des données
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');
                const phone = button.getAttribute('data-phone');
                const adress = button.getAttribute('data-adress');
                
                // Remplir le formulaire
                modal.querySelector('#founisseur_id').value = id;
                modal.querySelector('#name').value = name;
                modal.querySelector('#phone').value = phone;
                modal.querySelector('#email').value = email;
                modal.querySelector('#adress').value = adress;
                
                // Mettre à jour l'action du formulaire avec l'ID récupéré
                const updateUrl = `/fournisseurs/${id}`;
                form.action = updateUrl;
            });
        });
    </script>

@include('partials.footer')