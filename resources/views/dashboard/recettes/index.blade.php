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
                    <form method="get" action="{{route('article.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Rechercher...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i>Liste des recettes</span>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"  data-bs-target="#recetteModal">
                            Nouveau recette →
                        </button>
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
                                        <th>Date</th>
                                        <th>Libelle</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recettes as $d)
                                    <tr>
                                        <td>{{$d->reference}}</td>
                                        <td>{{$d->date_recette}}</td>
                                        <td>{{$d->libelle}}</td>
                                        <td>{{number_format($d->montant, 0, ',',' ')}} XOF</td>
                                        <td>
                                            <span class="badge bg-{{ $d->statut == 'recu' ? 'success' : 'danger' }}">
                                                {{ ucfirst($d->statut) }}
                                            </span>
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
                        <div class="d-flex justify-content-center mt-4">
                            {{$recettes->links()}}
                        </div>
                        <!-- Modal paiement -->
                        <div class="modal fade" id="recetteModal" tabindex="-1">
                            <div class="modal-dialog">
                               <form action="{{ route('recettes.store') }}" method="POST" class="contact-form">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">recette</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">

                                                <!-- Libellé -->
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Libellé de la dépense</label>
                                                    <input type="text" name="libelle" class="form-control" placeholder="Ex : Achat marchandises" required>
                                                </div>

                                                <!-- Montant -->
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Montant (FCFA)</label>
                                                    <input type="number" name="montant" class="form-control" step="0.01" required>
                                                </div>

                                                <!-- Date -->
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Date de la dépense</label>
                                                    <input type="date" name="date_recette" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <!-- Mode de paiement -->
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Mode de paiement</label>
                                                    <select name="mode_paiement" class="form-control" required>
                                                        <option value="">-- Choisir --</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="mobile_money">Mobile Money</option>
                                                        <option value="virement">Virement</option>
                                                        <option value="cheque">Cheque</option>
                                                        <option value="autre">Autre</option>
                                                    </select>
                                                </div>

                                                <!-- Description -->
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Description (optionnelle)</label>
                                                    <textarea name="description" class="form-control" rows="3" placeholder="Détails supplémentaires..."></textarea>
                                                </div>

                                            </div>
                                            <!-- Bouton -->
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    💾 Enregistrer la recette
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>

@include('partials.footer')