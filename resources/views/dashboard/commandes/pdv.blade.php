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
                        <span><i class="fas fa-shop" style="color: var(--primary); margin-right: 0.5rem;"></i>Point De Vente </span>
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

                        <div class="row">
                            @foreach($users as $u)
                                <div class="stat-card mb-3"> 
                                   
                                    <div class="px-2 py-2 mt-0">
                                        <h2 class="fw-bold mb-3">Caisse n {{$u->id}}</h2>
                                        <ul class="nav flex-column mb-3">
                                            <li><b>Nom</b> : {{$u->name}}</li>
                                            <li><b>Date</b> : {{$u->created_at}}</li>
                                            <li><b>Statut</b> : {{$u->role}}</li>
                                        </ul>
                                        @if($u->id == request()->user()->id  && $u->role !== 'administrateur')
                                            @if(!$session)
                                                <a href="{{ route('ouvrirCaisse') }}" class="btn btn-success">Ouvrir la session</a> 
                                            @else
                                                <a href="{{ route('fermerCaisse') }}" class="btn btn-danger">Fermer la session</a>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="px-2 py-2 mt-0">
                                        
                                        @if($u->id == request()->user()->id && $session)
                                            <h2 class="fw-bold mb-3">Session du jour</h2>
                                            <ul class="nav flex-column">
                                                <li><b>Nombre de vente</b>  : {{$session->nombre_ventes}} vente(s)</li>
                                                <li><b>Total ventes</b>  : {{number_format($session->total_ventes, 0, ',', ' ')}} XOF</li>
                                                <li><b>Total encaisse</b>  : {{number_format($session->total_encaisse, 0, ',', ' ')}} XOF</li>
                                                <hr>
                                                <li><b>Ouverture</b> : {{$session->opened_at}}</li>
                                                <li><b>Fermeture</b> : {{$session->closed_at}}</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


@include('partials.footer')