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
                    <form method="get" action="{{route('comptabilite.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche stock...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Mouvements Tiers</h1>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#exampleModal">
                            Nouveau mouvement →
                        </button>
                        
                    </div>
                </div>

                <!-- Recent Products Table -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-list" style="color: var(--primary); margin-right: 0.5rem;"></i> Liste des mouvements tiers</span>
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
                        <h3>Mouvements des tiers</h3>
                        <!-- FILTRE -->
                        <form method="GET" class="mb-3">
                            <select name="tiers_id" class="form-control">
                                <option value="">-- Filtrer par tiers --</option>
                                @foreach($tiers as $t)
                                    <option value="{{ $t->id }}" {{ $tiers_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->nom }} ({{$t->type}})
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary mt-2">Filtrer</button>
                        </form>

                        <!-- SOLDE -->
                        @if($tiers_id)
                            <div class="alert alert-info">
                                <strong>Solde :</strong> {{ number_format($solde, 0, ',', ' ') }} FCFA
                            </div>
                        @endif

                        <!-- TABLE -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Tiers</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Sens</th>
                                    <th>Description</th>
                                    <!--<th>Action</th>-->
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($mouvements as $m)
                                    <tr>
                                        <td>{{ $m->date_operation ?? $m->created_at }}</td>
                                        <td>{{ $m->tiers->nom ?? '-' }}</td>
                                        <td>{{ ucfirst($m->type) }}</td>
                                        <td>{{ number_format($m->montant, 0, ',', ' ') }}XOF</td>
                                        <td>
                                            @if($m->sens == 'debit')
                                                <span class="text-danger">Débit</span>
                                            @else
                                                <span class="text-success">Crédit</span>
                                            @endif
                                        </td>
                                        <td>{{ $m->description ?? 'Vide' }}</td>
                                        <!--<td>
                                            <a href="{{route('comptabilite.show', $m->id)}}" class="btn btn-outline-warning mr-2" title="afficher le mouvement">
                                                <i class="fa fa-eye text-warning"></i>
                                            </a>
                                        </td>-->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Aucun mouvement trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                        {{ $mouvements->links() }}

                    </div>
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
                <!-- Modal Nouveau mouvement stck-->
                    <div class="modal fade" id="exampleModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="post" action="{{route('comptabilite.store')}}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mouvement Tiers</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Tiers</label>
                                            <select class="form-control" name="tiers_id" id="exampleFormControlSelect1">
                                                <option value="">-- Veuillez choisir le tiers --</option>
                                                @foreach($tiers as $t)
                                                <option value="{{$t->id}}">{{$t->nom}} ({{$t->type}})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Type de mouvement</label>
                                            <select class="form-control" name="type" id="exampleFormControlSelect1">
                                                <option value="vente">Vente</option>
                                                <option value="achat">Achat</option>
                                                <!--<option value="paiement">Paiement</option>
                                                <option value="compensation">Compensation</option>-->
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label>Montant</label>
                                                <input type="number" name="montant" min="1" class="form-control" id="exampleInputquantity1">
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label>Sens</label>
                                                <select class="form-control" name="sens" id="exampleFormControlSelect1">
                                                    <option value="debit">Debit</option>
                                                    <option value="credit">Credit</option>
                                                    <option value="neutre">Neutre</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="date_operation" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Description (optionnelle)</label>
                                            <textarea name="description" class="form-control" rows="3" placeholder="Détails supplémentaires..."></textarea>
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
        </main>
    </div>

@include('partials.footer')