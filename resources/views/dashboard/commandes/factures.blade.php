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
                    <form method="get" action="{{route('commandes.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche facture...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->


                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-shopping-cart" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des factures ( {{$factures->count()}} )</span>
                        
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

                @if($user->role == 'administrateur')
                    <h2>Caisse journaliere (administrateur)</h2> 
                    <div class="stats-grid">
                        <div class="stat-card" style="background: linear-gradient(#0081A7, #00AFB9) ;">
                            <div class="stat-info">
                                <h3 class="text-white">Ventes du jour</h3>
                                <div class="number text-white">{{$ventesJour->count()}}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-tags text-info"></i>
                            </div>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(#FF2626, #FFFF26) ;">
                            <div class="stat-info">
                                <h3>Montant Total</h3>
                                <div class="number">{{number_format($total, 0, ',', ' ')}} XOF</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <!--<div class="stat-card" style="background: linear-gradient(#FF95F4, #FF2626) ;">
                            <div class="stat-info">
                                <h3 class="">Montant Encaisse</h3>
                                <div class="number">{{number_format($totalEncaisse, 0, ',', ' ')}} XOF</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave text-warning"></i>
                            </div>
                        </div>-->
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

                        <div class="table-responsive">
                            <table class="">
                                <thead>
                                    <tr>
                                        <th style="background-color: #BAFFAC;">ID Facture</th>
                                        <th style="background-color: #BAFFAC;">Reference</th>
                                        <th style="background-color: #BAFFAC;">Client</th>
                                        <!--<th>Montant TVA</th>-->
                                        <th style="background-color: #BAFFAC;">Montant Total</th>
                                        <th style="background-color: #BAFFAC;">Montant Payer</th>
                                        <th style="background-color: #BAFFAC;">Montant Restant</th>
                                        <th style="background-color: #BAFFAC;">Date</th>
                                        <th style="background-color: #BAFFAC;">Statut</th>
                                        <!--<th style="background-color: #BAFFAC;">Actions</th>-->
                                        <th style="background-color: #BAFFAC;">Facture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($factures as $v)
                                    <tr>
                                        <td>Facture-{{$v->id}}</td>
                                        <td>{{$v->reference}}</td>
                                        <td>{{$v->client->nom ?? '-'}}</td>
                                        <!--<td>{{number_format($v->total_tva, 0, ',',' ')}} XOF</td>-->
                                        <td>{{number_format($v->total_ttc, 0, ',',' ')}} XOF</td>
                                        <td>{{number_format($v->montant_paye, 0, ',', ' ')}} XOF</td>
                                        <td>{{number_format($v->montant_restant, 0, ',',' ')}} XOF</td>
                                        <td>{{$v->created_at->format('d/m/y')}}</td>
                                        <td>
                                            @if($v->statut == 'payee')
                                                <span class="status-badge badge bg-success">{{$v->statut}}</span>
                                            @elseif($v->statut == 'partielle')
                                                <span class="status-badge badge bg-info">{{$v->statut}}</span>
                                            @else
                                                <span class="status-badge badge bg-danger">{{$v->statut}}</span>
                                            @endif
                                        </td>
                                        <!--<td>
                                            @if($v->montant_restant == 0)
                                                <button type="button" class="btn btn-secondary">
                                                        Payée
                                                </button>
                                            @else
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-id="{{$v->id}}" data-bs-target="#paiementModal">Payer
                                            </button>
                                            @endif
                                        </td>-->
                                        <td>
                                            <a href="{{route('commandes.show', $v->id)}}" class="btn btn-warning mr-2" title="afficher la facture">
                                                        <i class="fas fa-file-alt"></i>&nbsp;Afficher
                                            </a>
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
                        
                        </div>
                    </div>
                </div>



@include('partials.footer')