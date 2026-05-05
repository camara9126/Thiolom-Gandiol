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
                        <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-danger">Retour</a>
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
                                        <!--<th style="background-color: #BAFFAC;">Montant Payer</th>-->
                                        <!--<th style="background-color: #BAFFAC;">Montant Restant</th>-->
                                        <th style="background-color: #BAFFAC;">Date</th>
                                        <th style="background-color: #BAFFAC;">Statut</th>
                                        <!--<th style="background-color: #BAFFAC;">Actions</th>-->
                                        <th style="background-color: #BAFFAC;">Facture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($factures as $f)
                                    <tr>
                                        <td>Facture-{{$f->id}}</td>
                                        <td>{{$f->reference}}</td>
                                        <td>{{$f->client->nom ?? '-'}}</td>
                                        <!--<td>{{number_format($f->total_tva, 0, ',',' ')}} XOF</td>-->
                                        <td>{{number_format($f->total, 0, ',',' ')}} XOF</td>
                                        <!--<td>{{number_format($f->montant_paye, 0, ',', ' ')}} XOF</td>-->
                                        <!--<td>{{number_format($f->montant_restant, 0, ',',' ')}} XOF</td>-->
                                        <td>{{$f->created_at->format('d/m/y')}}</td>
                                        <td>
                                            @if($f->statut == 'en_attente')
                                                <span class="status-badge badge bg-warning">{{$f->statut}}</span>
                                            @elseif($f->statut == 'envoye')
                                                <span class="status-badge badge bg-info">{{$f->statut}}</span>
                                            @else
                                                <span class="status-badge badge bg-success">{{$f->statut}}</span>
                                            @endif
                                        </td>
                                        <!--<td>
                                            @if($f->montant_restant == 0)
                                                <button type="button" class="btn btn-secondary">
                                                        Payée
                                                </button>
                                            @else
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-id="{{$f->id}}" data-bs-target="#paiementModal">Payer
                                            </button>
                                            @endif
                                        </td>-->
                                        <td>
                                            <a href="{{route('fournisseurs.pdf', $f->id)}}" class="btn btn-warning mr-2" title="afficher la facture">
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