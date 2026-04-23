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
                                    @if($u->id == request()->user()->id)
                                        <a href="{{ route('commandes.create') }}"> 
                                        <div class="px-2 py-2 mt-0">
                                            <h2 class="fw-bold mb-3">Caisse n {{$u->id}}</h2>
                                            <ul class="nav flex-column">
                                                <li>Nom : {{$u->name}}</li>
                                                <li>Date : {{$u->created_at}}</li>
                                                <li>Role : {{$u->role}}</li>
                                            </ul>
                                        </div>
                                        </a>
                                    @else  
                                        <div class="px-2 py-2 mt-0">
                                            <h2 class="fw-bold mb-3">Caisse n {{$u->id}}</h2>
                                            <ul class="nav flex-column">
                                                <li>Nom : {{$u->name}}</li>
                                                <li>Date : {{$u->created_at}}</li>
                                                <li>Role : {{$u->role}}</li>
                                            </ul>
                                        </div>
                                    @endif
                                    <!--<div class="px-2 py-2 mt-0">
                                        <ul class="nav flex-column">
                                            <li>Vente : {{$ventesJour->count()}}</li>
                                            <li>Montant Total : {{$total}} XOF</li>
                                            <li>Montant Recu : {{$totalEncaisse}} XOF</li>
                                        </ul>
                                    </div>-->
                                </div>
                            @endforeach
                        </div>
                    
                </div>


@include('partials.footer')