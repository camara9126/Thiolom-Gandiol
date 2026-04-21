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
                    <form method="get" action="{{route('paiements.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche paiement...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des Paiements</span>
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
                                        <th>Client</th>
                                        <th>Montant</th>
                                        <th>Date de paiement</th>
                                        <!--<th>Mode de paiement</th>-->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paiements as $p)
                                    <tr>
                                        <td>{{$p->reference}}</td>
                                        <td>{{optional($p->vente->client)->nom ?? '-'}}</td>
                                        <td>{{max(0, number_format($p->montant, 0, ',',' '))}} XOF</td>
                                        <td>{{$p->date_paiement}}</td>
                                        <!--<td>{{$p->mode_paiement}}</td>-->
                                        <td>
                                            @if($p->statut === 'valide')
                                                <form action="{{ route('paiements.annuler', $p->id) }}" method="POST" onsubmit="return confirm('Confirmer l’annulation du paiement ?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-outline-danger btn-sm" title="Annuler le paiement">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </form>
                                            @else
                                                    <button class="btn btn-secondary btn-sm" title="Paiement annule">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                            @endif                                    
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
                                {{$paiements->links()}}
                            </div>
                        </div>
                    </div>
                </div>

@include('partials.footer')