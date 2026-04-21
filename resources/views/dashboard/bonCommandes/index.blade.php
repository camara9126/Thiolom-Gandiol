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
                    <form method="get" action="{{route('bonCommande.search')}}" class="form-inline">
                        
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
                            <h3>Bons de commande</h3>

                            <a href="{{ route('bonCommande.create') }}" class="btn btn-primary">
                                + Nouveau bon de commande
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-body">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Référence</th>
                                            <th>Fournisseur</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($bonCommandes as $bc)
                                            <tr>
                                                <td>{{ $bc->reference }}</td>

                                                <td>{{ $bc->fournisseur->nom ?? '-' }}</td>

                                                <td>{{ $bc->date_commande }}</td>

                                                <td>{{ number_format($bc->total, 0, ',', ' ') }} FCFA</td>

                                                <td>
                                                    @if($bc->statut == 'en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @elseif($bc->statut == 'envoye')
                                                        <span class="badge bg-info">Envoyé</span>
                                                    @elseif($bc->statut == 'recu')
                                                        <span class="badge bg-success">Reçu</span>
                                                    @endif
                                                </td>

                                                <td class="d-flex gap-1">

                                                    <!-- Voir -->
                                                    <a href="{{ route('bonCommande.show', $bc->id) }}" 
                                                    class="btn btn-sm btn-info">
                                                        Voir
                                                    </a>

                                                    <!-- Envoyer -->
                                                    @if($bc->statut == 'en_attente')
                                                        <a href="{{ route('bonCommande.envoyer', $bc->id) }}" 
                                                        class="btn btn-sm btn-primary">
                                                            Envoyer
                                                        </a>
                                                    @endif

                                                    <!-- Recevoir -->
                                                    @if($bc->statut == 'envoye')
                                                        <a href="{{ route('bonCommande.recevoir', $bc->id) }}" 
                                                        class="btn btn-sm btn-success">
                                                            Reçu
                                                        </a>
                                                    @endif

                                                    <!-- Supprimer -->
                                                    <form action="{{ route('bonCommande.destroy', $bc->id) }}" 
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
