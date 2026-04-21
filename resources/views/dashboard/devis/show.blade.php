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
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Affichage de devis </span>
                        <a href="{{ route('devis.index') }}" class="btn btn-outline-danger">Retour</a>
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


                            <!-- INFOS ENTREPRISE -->
                            <div class="mb-4">
                                <h4>{{ $devis->entreprise->nom ?? 'Entreprise' }}</h4>
                                <p>Date : {{ $devis->date_devis }}</p>
                                <p>Référence : {{ $devis->reference }}</p>

                                <p>
                                    Statut :
                                    @if($devis->statut == 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($devis->statut == 'valide')
                                        <span class="badge bg-success">Validé</span>
                                    @else
                                        <span class="badge bg-danger">Refusé</span>
                                    @endif
                                </p>
                            </div>

                            <!-- CLIENT -->
                            <div class="mb-4">
                                <h5>Client</h5>
                                <p>Nom : {{ $devis->client->nom }}</p>
                                <p>Téléphone : {{ $devis->client->telephone ?? '-' }}</p>
                            </div>

                            <!-- TABLE PRODUITS -->
                            <table class="">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($devis->details as $detail)
                                        <tr>
                                            <td>{{ $detail->article->nom ?? '-' }}</td>
                                            <td>{{ $detail->quantite }}</td>
                                            <td>{{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ number_format($detail->total, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- TOTAL -->
                            <div class="text-end mt-3">
                                <h4>Total : {{ number_format($devis->total, 0, ',', ' ') }} FCFA</h4>
                            </div>

                            <!-- ACTIONS -->
                            <div class="mt-4 d-flex gap-2">

                                @if($devis->statut == 'en_attente')
                                    <a href="{{ route('devis.valider', $devis->id) }}" class="btn btn-success">
                                        Valider
                                    </a>

                                    <a href="{{ route('devis.refuser', $devis->id) }}" class="btn btn-danger">
                                        Refuser
                                    </a>
                                     <a href="{{ route('devis.facture', $devis->id) }}" class="btn btn-info">
                                        Generer la facture
                                    </a>
                                @endif

                                @if($devis->statut == 'valide')
                                    <a href="{{ route('devis.convertir', $devis->id) }}" class="btn btn-info">
                                        Convertir en vente
                                    </a>
                                @elseif($devis->statut == 'refuse')
                                    <form action="{{route('devis.destroy', $devis->id)}}" type="button" method="post" onsubmit="return confirm('Supprimer ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer">
                                            <i class="fa fa-trash"></i>Supprimer le devis
                                        </button>
                                    </form>
                                @endif
                                
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
                            <option value="{{ $article->id }}" data-prix="{{ $article->prix }}">
                                {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="number" name="articles[${index}][prix]" class="form-control prix" readonly>
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

        // Auto remplir prix
        document.addEventListener('change', function(e){
            if(e.target.classList.contains('produit-select')){
                let prix = e.target.selectedOptions[0].dataset.prix;
                let row = e.target.closest('tr');
                row.querySelector('.prix').value = prix;
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
            let prix = row.querySelector('.prix').value || 0;
            let quantite = row.querySelector('.quantite').value || 0;

            let total = prix * quantite;
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