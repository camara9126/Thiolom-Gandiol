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
                        <div class="stat-card" style="background: linear-gradient(#FF95F4, #FF2626) ;">
                            <div class="stat-info">
                                <h3 class="">Montant Encaisse</h3>
                                <div class="number">{{number_format($totalEncaisse, 0, ',', ' ')}} XOF</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave text-warning"></i>
                            </div>
                        </div>
                    </div>                
                @else
                    <h2>Caisse journaliere (caissier(e))</h2> 
                    <div class="stats-grid">
                        <div class="stat-card" style="background: linear-gradient(#0081A7, #00AFB9) ;">
                            <div class="stat-info">
                                <h3 class="text-white">Ventes du jour</h3>
                                <div class="number text-white">{{$session->nombre_ventes}}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-tags text-info"></i>
                            </div>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(#FF2626, #FFFF26) ;">
                            <div class="stat-info">
                                <h3>Montant Total</h3>
                                <div class="number">{{number_format($session->total_ventes, 0, ',', ' ')}} XOF</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(#FF95F4, #FF2626) ;">
                            <div class="stat-info">
                                <h3 class="">Montant Encaisse</h3>
                                <div class="number">{{number_format($session->total_encaisse, 0, ',', ' ')}} XOF</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave text-warning"></i>
                            </div>
                        </div>
                    </div>
                @endif                    

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-shopping-cart" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des factures ( {{$ventes->count()}} )</span>
                        <a href="{{ route('commandes.create') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Nouvelle vente →</a>
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
                                        <th style="background-color: #BAFFAC;">Reference</th>
                                        <!--<th style="background-color: #BAFFAC;">Client</th>-->
                                        <!--<th>Montant TVA</th>-->
                                        <th style="background-color: #BAFFAC;">Montant Total</th>
                                        <th style="background-color: #BAFFAC;">Montant Payer</th>
                                        <th style="background-color: #BAFFAC;">Montant Restant</th>
                                        <th style="background-color: #BAFFAC;">Date</th>
                                        <th style="background-color: #BAFFAC;">Statut</th>
                                        <!--<th style="background-color: #BAFFAC;">Actions</th>-->
                                        <th style="background-color: #BAFFAC;">Ticket de caisse</th>
                                        <th style="background-color: #BAFFAC;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($user->role == 'administrateur')
                                        @forelse($ventes as $v)
                                            <tr>
                                                <td>{{$v->reference}}</td>
                                                <!--<td>{{$v->client->nom ?? 'Client supprimee'}}</td>-->
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
                                                    <a href="{{route('commandes.ticket', $v->id)}}" class="btn btn-warning" title="Imprimer le ticket de caisse">
                                                        Imprimer
                                                    </a>
                                                </td>
                                                <td>
                                                    <form action="{{route('commandes.destroy', $v->id)}}" type="button" method="post" onsubmit="return confirm   ('Supprimer ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" align="center">Donnee vide !</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        @forelse($ventes->where('user_id', $user->id) as $v)
                                            <tr>
                                                <td>{{$v->reference}}</td>
                                                <!--<td>{{$v->client->nom ?? 'Client supprimee'}}</td>-->
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
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="{{route('commandes.ticket', $v->id)}}" class="btn btn-warning mr-2" title="Imprimer le ticket de caisse">
                                                                Imprimer
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form action="{{route('commandes.destroy', $v->id)}}" type="button" method="post" onsubmit="return confirm   ('Supprimer ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" align="center">Donnee vide !</td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-4">
                                {{$ventes->links()}}
                            </div>
                             <!-- Modal paiement -->
                        <div class="modal fade" id="paiementModal" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('paiements.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Paiement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="vente_id" id="vente_id">

                                            <div class="mb-3">
                                                <label>Montant à payer</label>
                                                <input type="number" name="montant" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Date du paiement</label>
                                                <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Mode de paiement</label>
                                                <select name="mode_paiement" class="form-select" required>
                                                    <option value="cash">Cash</option>
                                                    <option value="wave">Wave</option>
                                                    <option value="orange_money">Orange Money</option>
                                                    <option value="autre">Autre</option>
                                                </select>
                                            </div>

                                            <button class="btn btn-success">
                                                Enregistrer le paiement
                                            </button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>  
                        </div>
                    </div>
                </div>



    <!-- Recuperation de l'ID de la vente-->
    <script>
        
        document.addEventListener('DOMContentLoaded', function () {

            const modal = document.getElementById('paiementModal');

            modal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');

                modal.querySelector('#vente_id').value = id;
            });
        });

        
    </script>

@include('partials.footer')