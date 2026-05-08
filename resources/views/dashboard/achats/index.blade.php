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
                    <form method="get" action="{{route('achats.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Recherche bon...">                                                   
                            
                    </form>
                </div>

                @include('partials.userMenu')
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->

                <div class="card">
                   
                    
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


                        <div class="d-flex justify-content-between mb-3">
                            <h3>Achats</h3>

                            <a href="{{ route('achats.create') }}" class="btn btn-primary">
                                + Nouveau
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-body">

                                <table class="table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #11C6FF;" class="text-white">Référence</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Fournisseur</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Date</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Total</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Reste</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Statut</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Actions</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Details</th>
                                            <th style="background-color: #11C6FF;" class="text-white">Facture</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($achats as $a)
                                            <tr>
                                                <td>{{ $a->reference }}</td>

                                                <td>{{ $a->fournisseur->nom ?? '-' }}</td>

                                                <td>{{ $a->created_at->format('d/m/y') }}</td>

                                                <td>{{ number_format($a->total, 0, ',', ' ') }} FCFA</td>
                                                <td>{{ number_format($a->montant_restant, 0, ',', ' ') }} FCFA</td>

                                                <td>
                                                    @if($a->statut == 'annule')
                                                        <span class="badge bg-danger">Impayé</span>
                                                    @elseif($a->statut == 'recu')
                                                        <span class="badge bg-success">Payé</span>
                                                    @else
                                                        <span class="badge bg-info">Partiel</span>
                                                    @endif
                                                </td>

                                                <td class="">

                                                    @if($a->montant_restant == 0)
                                                        <span class="badge" style="background-color: gray;">
                                                                Payée
                                                        </span>
                                                    @else
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-id="{{$a->id}}" data-bs-target="#paiementModal">Payer
                                                        </button>
                                                    @endif
                                                   

                                                </td>
                                                <td>
                                                     <!-- Voir -->
                                                    <a href="{{ route('achats.show', $a->id) }}" 
                                                    class="btn btn-sm btn-info">
                                                        &nbsp;Voir&nbsp;
                                                    </a>

                                                    <!-- Supprimer 
                                                    <form action="{{ route('achats.destroy', $a->id) }}" 
                                                        method="POST" 
                                                        onsubmit="return confirm('Supprimer ?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger">
                                                            Supprimer
                                                        </button>
                                                    </form>-->
                                                </td>
                                                <td>
                                                    <a href="{{route('achats.factures', $a->id)}}" class="btn btn-warning mr-2" title="afficher la facture">
                                                        <i class="fas fa-file-alt"></i>&nbsp;Afficher
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Aucun bon de commande trouvé
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>

                                <!-- Modal paiement -->
                                <div class="modal fade" id="paiementModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('achats.paiement') }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Paiement</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="achat_id" id="achat_id">

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


    <!-- Recuperation de l'ID de l'achat-->
    <script>
        
        document.addEventListener('DOMContentLoaded', function () {

            const modal = document.getElementById('paiementModal');

            modal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');

                modal.querySelector('#achat_id').value = id;
            });
        });

        
    </script>
@include('partials.footer')
