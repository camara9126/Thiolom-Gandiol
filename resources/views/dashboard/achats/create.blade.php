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
                        <span><i class="fas fa-file-invoice" style="color: var(--primary); margin-right: 0.5rem;"></i>Nouvelle Achat </span>
                        <a href="{{ route('achats.index') }}" class="btn btn-outline-danger">Annuler</a>
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


                        <div class="container">
                            <h3 class="mb-3" style="text-align: center;">Nouvelle achat</h3>

                            <form action="{{ route('achats.store') }}" method="POST">
                                @csrf

                                <!-- RECHERCHE -->
                                 <div class="row">
                                    <div class="col-10 mb-3">
                                        <input type="text" id="search" class="form-control" placeholder="rechercher article...">
                                    </div>
                                 </div>
                                <!-- FOURNISSEUR -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label>Fournisseur</label>
                                            <select name="fournisseur_id" class="form-control" required>
                                                <option value="">-- Choisir un fournisseur --</option>
                                                @foreach($fournisseurs as $fournisseur)
                                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-2">
                                        <div class="mt-4 mb-2">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fournisseurModal" style="padding: 6px 12px;">
                                                + Nouveau fournisseur
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label>Magasin</label>
                                            <select name="magasin_id" class="form-control">
                                                <option value="">-- Choisir un magasin --</option>
                                                @foreach($magasin as $m)
                                                    <option value="{{ $m->id }}">{{ $m->nom }} - {{ $m->type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="list-group" id="results">
                                                    
                                    </div>
                                </div>
                                
                                <!-- TABLE articles -->
                                <table class="" id="table-articles">
                                    <thead>
                                        <tr>
                                            <th>article</th>
                                            <th>Prix</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="articles[0][article_id]" class="form-control article-select">
                                                    <option value="">Choisir</option>
                                                    @foreach($articles as $article)
                                                        <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                                                            {{ $article->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="articles[0][prix_vente]" class="form-control prix_vente">
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

                                <button type="button" id="addRow" class="btn btn-primary">+ Ajouter article</button>

                                <!-- TOTAL GLOBAL -->
                                <div class="mt-3 text-end">
                                    <h4>Total : <span id="total-global">0</span> FCFA</h4>
                                </div>

                                <!-- NOTE -->
                                <div class="mt-3">
                                    <label>Note</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success mt-3">
                                    Enregistrer
                                </button>
                            </form>

                            <!-- Nouveau fournisseur -->
                            <div class="modal fade" id="fournisseurModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="{{route('fournisseurs.store')}}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nouveau fournisseur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nom du fournisseur</label>
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

                                                <div class="mb-3">
                                                    <label>Adresse</label>
                                                    <textarea name="adresse" id=""></textarea>
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


<script>

    let index = 1;

    // Ajouter ligne
    document.getElementById('addRow').addEventListener('click', function () {

        let row = `
        <tr>
            <td>
                <select name="articles[${index}][article_id]" class="form-control article-select">
                    <option value="">Choisir</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                            {{ $article->nom }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td>
                <input type="number" name="articles[${index}][prix_vente]" class="form-control prix_vente">
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

        document.querySelector('#table-articles tbody').insertAdjacentHTML('beforeend', row);
        index++;
    });

    // Supprimer ligne
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove')){
            e.target.closest('tr').remove();
            calculTotal();
        }
    });

    // Auto prix_vente
    document.addEventListener('change', function(e){
        if(e.target.classList.contains('article-select')){
            let prix_vente = e.target.selectedOptions[0].dataset.prix_vente || 0;
            let row = e.target.closest('tr');

            row.querySelector('.prix_vente').value = prix_vente;
            calculLigne(row);
        }
    });

    // Calcul ligne
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('quantite') || e.target.classList.contains('prix_vente')){
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

<!-- Recherche article -->
<script>
    document.getElementById('search').addEventListener('keyup', function() {

        let query = this.value;

        if (query.length < 2) return;

        fetch(`/bonSearch?q=${query}`)
            .then(res => res.json())
            .then(data => {

                let results = document.getElementById('results');
                results.innerHTML = '';

                data.forEach(article => {
                    results.innerHTML += `
                        <a href="#" class="list-group-item" onclick="selectArticle(${article.id}, '${article.nom}', ${article.prix_vente})">
                            ${article.nom} - ${article.prix_vente} FCFA
                        </a>
                    `;
                });
            });
    });
</script>

<script>
    function selectArticle(id, nom, prix_vente) {
        console.log("Produit sélectionné :", nom);
        
        // Chercher une ligne vide
        let selectElement = document.querySelector('#table-articles select');
        
        if(selectElement && selectElement.value === "") {
            // Remplir la première ligne vide
            let row = selectElement.closest('tr');
            selectElement.value = id;
            row.querySelector('.prix_vente').value = prix_vente;
            row.querySelector('.quantite').value = 1;
            calculLigne(row);
        } else {
            // Vérifier si le produit existe déjà
            let existingProductRow = null;
            document.querySelectorAll('.article-select').forEach(select => {
                if(select.value == id) {
                    existingProductRow = select.closest('tr');
                }
            });
            
            if(existingProductRow) {
                // Augmenter la quantité
                let qteInput = existingProductRow.querySelector('.quantite');
                let nouvelleQte = (parseFloat(qteInput.value) || 0) + 1;
                qteInput.value = nouvelleQte;
                calculLigne(existingProductRow);
            } else {
                // Ajouter une nouvelle ligne manuellement
                let row = `
                    <tr>
                        <td>
                            <select name="articles[${index}][article_id]" class="form-control article-select">
                                <option value="">Choisir</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}">
                                        {{ $article->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="articles[${index}][prix_vente]" class="form-control prix_vente" value="${prix_vente}">
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
                
                document.querySelector('#table-articles tbody').insertAdjacentHTML('beforeend', row);
                
                let newRow = document.querySelector('#table-articles tbody tr:last-child');
                let select = newRow.querySelector('.article-select');
                select.value = id;
                calculLigne(newRow);
                
                index++;
            }
        }
        
        // Nettoyer
        document.getElementById('results').innerHTML = '';
        document.getElementById('search').value = '';
    }
</script>
@include('partials.footer')
