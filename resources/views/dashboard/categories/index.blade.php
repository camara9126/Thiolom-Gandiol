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
                                            <td>{{$c->article->count()}}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('categorie.show', $c->id) }}" class="action-btn"><i class="fas fa-edit"></i></a>
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
                            <div class="d-flex justify-content-center mt-4">
                                {{$categorie->links()}}
                            </div>                            
                        </div>
                    </div>
                </div>

@include('partials.footer')
