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
                    <form method="get" action="{{route('achats.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche bon...">                                                   
                            
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

                    <div class="card-body">
                        @if ($errors->any())
                            <div style="color: red; margin-bottom: 10px;">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif


                        <div class="d-flex justify-content-between mb-3">
                            <h3>Achats</h3>

                            <a href="{{ route('achats.create') }}" class="btn btn-primary">
                                + Nouveau
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-body">

                                <table class="table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #11C6FF;" class="text-white">Référence</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Fournisseur</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Date</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Total</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Statut</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($achats as $a)
                                            <tr>
                                                <td>{{ $a->reference }}</td>

                                                <td>{{ $a->fournisseur->nom ?? '-' }}</td>

                                                <td>{{ $a->created_at->format('d/m/y') }}</td>

                                                <td>{{ number_format($a->total, 0, ',', ' ') }} FCFA</td>

                                                <td>
                                                    @if($a->statut == 'en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @elseif($a->statut == 'envoye')
                                                        <span class="badge bg-info">Envoyé</span>
                                                    @elseif($a->statut == 'recu')
                                                        <span class="badge bg-success">Reçu</span>
                                                    @endif
                                                </td>

                                                <td class="d-flex gap-1">

                                                    <!-- Voir -->
                                                    <a href="{{ route('achats.show', $a->id) }}" 
                                                    class="btn btn-sm btn-info">
                                                        &nbsp;Voir&nbsp;
                                                    </a>

                                                    <!-- Supprimer -->
                                                    <form action="{{ route('achats.destroy', $a->id) }}" 
                                                        method="POST" 
                                                        onsubmit="return confirm('Supprimer ?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger">
                                                            Supprimer
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Aucun bon de commande trouvé
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>

                            </div>
                        </div>


@include('partials.footer')
