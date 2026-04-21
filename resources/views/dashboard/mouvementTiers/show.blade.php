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
                        <h3>Relevé de compte - {{ $tiers->nom }}</h3>

                        <!-- RÉCAP -->
                        <div class="row mb-3">

                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Total Débit</h5>
                                    <h4 class="text-danger">{{ number_format($debit, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Total Crédit</h5>
                                    <h4 class="text-success">{{ number_format($credit, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3">
                                    <h5>Solde</h5>
                                    <h4>{{ number_format($solde, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>

                        </div>

                        <!-- TABLE -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Sens</th>
                                    <th>Description</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($mouvements as $m)
                                    <tr>
                                        <td>{{ $m->date_operation ?? $m->created_at }}</td>
                                        <td>{{ ucfirst($m->type) }}</td>
                                        <td>{{ number_format($m->montant, 0, ',', ' ') }}</td>
                                        <td>
                                            @if($m->sens == 'debit')
                                                <span class="text-danger">Débit</span>
                                            @else
                                                <span class="text-success">Crédit</span>
                                            @endif
                                        </td>
                                        <td>{{ $m->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </main>
    </div>

@include('partials.footer')