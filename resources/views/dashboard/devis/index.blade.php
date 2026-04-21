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
                    <form method="get" action="{{route('devis.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche devis...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des devis ( {{$devis->count()}} )</span>
                        <a href="{{ route('devis.create') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Nouveau devis →</a>
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
                            <table class="">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Client</th>
                                        <th>Total Devis</th>
                                        <th>Date de devis</th>
                                        <th>Date d'expiration</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($devis as $d)
                                    <tr>
                                        <td>{{$d->reference}}</td>
                                        <td>{{$d->client->nom ?? 'Client supprimee'}}</td>
                                        <td>{{number_format($d->total, 0, ',',' ')}} XOF</td>
                                        <td>{{$d->date_devis}}</td>
                                        <td>{{$d->date_expiration}}</td>
                                        <td>
                                            @if($d->statut == 'valide')
                                                <span class="status-badge badge-success">{{$d->statut}}</span>
                                            @elseif($d->statut == 'en_attente')
                                                <span class="status-badge badge-warning">{{$d->statut}}</span>
                                            @else
                                                <span class="status-badge badge-danger">{{$d->statut}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a href="{{route('devis.show', $d->id)}}" class="btn btn-outline-warning mr-2" title="afficher le devis">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{route('devis.edit', $d->id)}}" class="btn btn-outline-info mr-2" title="modifier le devis">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </div>
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
                            <div class="d-flex justify-content-center mt-4">
                                {{$devis->links()}}
                            </div>
                        </div>
                    </div>
                </div>

@include('partials.footer')