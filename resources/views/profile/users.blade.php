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
                    <form method="get" action="{{route('clients.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Rechercher...">                                                   
                            
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
                        <span><i class="fas fa-user" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des utilisateurs ( {{$users->count()}} )</span>
                       <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#clientModal">
                            Nouveau utilisateur →
                        </button>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                               <table class="">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $u)
                                        <tr>
                                            <td>{{$u->name}}</td>
                                            <td>{{$u->email ?? 'Vide'}}</td>
                                            <td>{{$u->role ?? 'Vide'}}</td>    
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" align="center">Donnee vide !</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>  

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif 

                            <!-- Nouveau utilisateur -->
                            <div class="modal fade" id="clientModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="{{route('users.add')}}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nouveau utilisateur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nom de l'utilisateur</label>
                                                    <input type="text" name="name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Role</label>
                                                    <select class="form-select" name="role" required>
                                                        <option >Selectionner un role</option>
                                                        <option value="admin">Administrateur</option>
                                                        <option value="gestionnaire de stock">Gestionnaire de stock</option>
                                                        <option value="caissier">Caissier</option>
                                                    </select> 
                                                </div>

                                                <div class="mb-3">
                                                    <label>Mot de passe</label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label>Confirmer le mot de passe</label>
                                                    <input type="password" name="password_confirmation" class="form-control">
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


@include('partials.footer')