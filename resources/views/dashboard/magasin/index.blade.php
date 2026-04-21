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
                    <form method="get" action="{{route('magasins.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche magasin...">                                                   
                            
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
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des magasins ( {{$magasins->count()}} )</span>
                       <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#magasinModal">
                            Nouveau magasin →
                        </button>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                               <table class="">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Telephone</th>
                                            <th>Adresse</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($magasins as $m)
                                        <tr>
                                            <td>{{$m->nom}}</td>
                                            <td>{{$m->telephone ?? 'Vide'}}</td>
                                            <td>{{$m->adresse ?? 'Vide'}}</td>
                                            <td>
                                            <div class="action-buttons">
                                                <a href="" class="action-btn" data-bs-toggle="modal" data-id="{{ $m->id }}" data-name="{{ $m->nom }}" data-phone="{{ $m->telephone }}" data-email="{{ $m->email }}" data-adress="{{$m->adresse }}" data-bs-target="#magasinEditModal" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!--<form action="{{route('magasin.destroy', $m->id)}}" type="button" method="post" onsubmit="return confirm('Supprimer ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>-->
                                                <a href="{{route('magasin.liste', $m->id)}}" class="action-btn" title="afficher les articles"><i class="fas fa-list"></i></a>
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
                                {{$magasins->links()}}
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

                            <!-- Nouveau magasin -->
                            <div class="modal fade" id="magasinModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="{{route('magasin.store')}}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nouveau magasin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nom du magasin</label>
                                                    <input type="text" name="nom" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Téléphone</label>
                                                    <input type="text" name="telephone" class="form-control">
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

                             <!-- Edit magasin -->
                            <div class="modal fade" id="magasinEditModal" tabindex="-1">
                                <div class="modal-dialog">

                                    <form method="post" id="editmagasinForm" action="">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modification magasin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="magasin_id">

                                                <div class="mb-3">
                                                    <label>Nom du magasin</label>
                                                    <input type="text" name="nom" id="name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Téléphone</label>
                                                    <input type="text" name="telephone" id="phone" class="form-control">
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

    <!--Recuperation des donnees magasin pour l'Edit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('magasinEditModal');
            const form = document.getElementById('editmagasinForm');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                // Récupération des données
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');
                const phone = button.getAttribute('data-phone');
                const adress = button.getAttribute('data-adress');
                
                // Remplir le formulaire
                modal.querySelector('#magasin_id').value = id;
                modal.querySelector('#name').value = name;
                modal.querySelector('#phone').value = phone;
                modal.querySelector('#email').value = email;
                modal.querySelector('#adress').value = adress;
                
                // Mettre à jour l'action du formulaire avec l'ID récupéré
                const updateUrl = `/magasin/${id}`;
                form.action = updateUrl;
            });
        });
    </script>

@include('partials.footer')