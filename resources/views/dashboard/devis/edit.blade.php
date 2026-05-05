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
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Modification de devis </span>
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

<form method="post" action="{{ route('devis.update', $devis) }}">
    @csrf
    @method('PUT')
    
    <!-- CLIENT -->
    <div class="mb-3">
        <label>Client</label>
        <select name="client_id" class="form-control" required>
            <option value="{{$devis->client->id}}">{{$devis->client->nom}}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->nom }}</option>
            @endforeach
        </select>
    </div>

    <!-- PRODUITS -->
    <table class="table table-bordered" id="table-produits">
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
            @foreach($devis->details as $index => $detail)
            <tr id="row-{{ $index }}">
                <td>
                    <select name="articles[{{ $index }}][article_id]" class="form-control produit-select" required>
                        <option value="{{ $detail->article->id }}" data-prix_vente="{{ $detail->article->prix_vente }}" selected>
                            {{ $detail->article->nom }}
                        </option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                                {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="articles[{{ $index }}][prix_vente]" value="{{ $detail->prix_unitaire }}" class="form-control prix_vente" required step="any">
                </td>
                <td>
                    <input type="number" name="articles[{{ $index }}][quantite]" value="{{ $detail->quantite }}" class="form-control quantite" required min="1">
                </td>
                <td>
                    <input type="number" class="form-control total-ligne" value="{{ $detail->total }}" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove">X</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" id="addRow" class="btn btn-primary">+ Ajouter produit</button>

    <!-- TOTAL -->
    <div class="mt-3">
        <h4>Total : <span id="total-global">0</span> FCFA</h4>
    </div>

    <button type="submit" class="btn btn-success mt-3">Modifier</button>
</form>

<script>
    let rowIndex = {{ $devis->details->count() }}; // Commencer après le dernier index existant

    // Ajouter ligne (CORRIGÉ)
    document.getElementById('addRow').addEventListener('click', function () {
        // Générer les options des articles
        let options = '';
        @foreach($articles as $article)
            options += `<option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">{{ $article->nom }}</option>`;
        @endforeach
        
        let row = `
            <tr id="row-new-${rowIndex}">
                <td>
                    <select name="articles[${rowIndex}][article_id]" class="form-control produit-select" required>
                        <option value="">Choisir un article</option>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="articles[${rowIndex}][prix_vente]" class="form-control prix_vente" required step="any">
                </td>
                <td>
                    <input type="number" name="articles[${rowIndex}][quantite]" class="form-control quantite" value="1" required min="1">
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
        rowIndex++;
    });

    // Supprimer ligne
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove')){
            e.target.closest('tr').remove();
            calculTotal();
        }
    });

    // Auto remplir prix_vente et calculer
    document.addEventListener('change', function(e){
        if(e.target.classList.contains('produit-select')){
            let selectedOption = e.target.selectedOptions[0];
            let prix_vente = selectedOption.dataset.prix_vente;
            let row = e.target.closest('tr');
            let prixInput = row.querySelector('.prix_vente');
            
            if(prixInput) {
                prixInput.value = prix_vente;
            }
            calculLigne(row);
        }
    });

    // Calcul ligne (déclenché par quantité ou prix)
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('quantite') || e.target.classList.contains('prix_vente')){
            let row = e.target.closest('tr');
            calculLigne(row);
        }
    });

    function calculLigne(row){
        let prix_vente = parseFloat(row.querySelector('.prix_vente').value) || 0;
        let quantite = parseFloat(row.querySelector('.quantite').value) || 0;

        let total = prix_vente * quantite;
        let totalInput = row.querySelector('.total-ligne');
        
        if(totalInput) {
            totalInput.value = total.toFixed(2);
        }
        
        calculTotal();
    }

    // Calcul global
    function calculTotal(){
        let total = 0;

        document.querySelectorAll('.total-ligne').forEach(function(input){
            let val = parseFloat(input.value);
            if(!isNaN(val)) {
                total += val;
            }
        });

        let totalSpan = document.getElementById('total-global');
        if(totalSpan) {
            totalSpan.innerText = total.toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        }
    }

    // Initialiser le total au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        calculTotal();
    });
</script>
@include('partials.footer')