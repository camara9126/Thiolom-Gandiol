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
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Creation de devis </span>
                        <a href="{{ route('devis.index') }}" class="btn btn-outline-danger">Annuler</a>
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

                       <form action="{{ route('devis.store') }}" method="POST">
                            @csrf

                            <!-- CLIENT -->
                             <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Client</label>
                                        <select name="client_id" class="form-control">
                                            <option value="">-- Choisir un client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-4 mb-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#clientModal" style="padding: 6px 12px;">
                                            + Nouveau client
                                        </button>
                                    </div>
                                </div>
                             </div>


                            <!-- PRODUITS -->
                            <table class="" id="table-produits">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="articles[0][article_id]" class="form-control produit-select">
                                                <option value="">Choisir</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                                                        {{ $article->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" name="articles[0][prix_vente]" class="form-control prix_vente" >
                                        </td>

                                        <td>
                                            <input type="number" name="articles[0][quantite]" class="form-control quantite" value="1">
                                        </td>

                                        <td>
                                            <input type="number" class="form-control total-ligne" readonly>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger remove">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <button type="button" id="addRow" class="btn btn-primary">+ Ajouter produit</button>

                            <!-- TOTAL -->
                            <div class="mt-3">
                                <h4>Total : <span id="total-global">0</span> FCFA</h4>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Enregistrer</button>
                        </form>

                        <!-- Nouveau client -->
                        <div class="modal fade" id="clientModal" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="post" action="{{route('clients.store')}}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nouveau client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nom du client</label>
                                                <input type="text" name="nom" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Téléphone</label>
                                                <input type="text" name="telephone" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control">
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
                </div>


    <script>
        let index = 1;

        // Ajouter ligne
        document.getElementById('addRow').addEventListener('click', function () {

            let row = `
            <tr>
                <td>
                    <select name="articles[${index}][article_id]" class="form-control produit-select">
                        <option value="">Choisir</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                                {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="number" name="articles[${index}][prix_vente]" class="form-control prix_vente" >
                </td>

                <td>
                    <input type="number" name="articles[${index}][quantite]" class="form-control quantite" value="1">
                </td>

                <td>
                    <input type="number" class="form-control total-ligne" readonly>
                </td>

                <td>
                    <button type="button" class="btn btn-danger remove">X</button>
                </td>
            </tr>
            `;

            document.querySelector('#table-produits tbody').insertAdjacentHTML('beforeend', row);
            index++;
        });

        // Supprimer ligne
        document.addEventListener('click', function(e){
            if(e.target.classList.contains('remove')){
                e.target.closest('tr').remove();
                calculTotal();
            }
        });

        // Auto remplir prix_vente
        document.addEventListener('change', function(e){
            if(e.target.classList.contains('produit-select')){
                let prix_vente = e.target.selectedOptions[0].dataset.prix_vente;
                let row = e.target.closest('tr');
                row.querySelector('.prix_vente').value = prix_vente;
                calculLigne(row);
            }
        });

        // Calcul ligne
        document.addEventListener('input', function(e){
            if(e.target.classList.contains('quantite')){
                let row = e.target.closest('tr');
                calculLigne(row);
            }
        });

        function calculLigne(row){
            let prix_vente = row.querySelector('.prix_vente').value || 0;
            let quantite = row.querySelector('.quantite').value || 0;

            let total = prix_vente * quantite;
            row.querySelector('.total-ligne').value = total;

            calculTotal();
        }

        // Calcul global
        function calculTotal(){
            let total = 0;

            document.querySelectorAll('.total-ligne').forEach(function(input){
                total += parseFloat(input.value) || 0;
            });

            document.getElementById('total-global').innerText = total.toLocaleString();
        }
    </script>
@include('partials.footer')