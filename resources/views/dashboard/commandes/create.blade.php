@include('partials.header')

<style>
    /* Styles spécifiques à la caisse */
    .pos-dashboard {
        display: flex;
        background: #f0f2f5;
        min-height: 100vh;
    }

    .pos-main {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .pos-content {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* Layout deux colonnes */
    .pos-two-columns {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .pos-left {
        flex: 2;
        min-width: 300px;
    }

    .pos-right {
        flex: 1;
        min-width: 280px;
    }

    /* Cartes */
    .pos-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .pos-card-header {
        background: #2c3e50;
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 18px;
    }

    .pos-card-body {
        padding: 20px;
    }

    /* Catégories produits */
    .category-header {
        background: #e7f3ff;
        padding: 10px 15px;
        border-radius: 8px;
        margin: 15px 0 10px 0;
        font-weight: 600;
        color: #0066cc;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .product-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        min-width: 120px;
    }

    .product-card:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        border-color: #0066cc;
    }

    .product-name {
        font-weight: 600;
        font-size: 14px;
    }

    .product-price {
        font-size: 12px;
        color: #e67e22;
        font-weight: 600;
    }

    .product-ref {
        font-size: 10px;
        color: #6c757d;
    }

    /* Table des articles */
    .articles-table {
        width: 100%;
        border-collapse: collapse;
    }

    .articles-table th,
    .articles-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .articles-table th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 13px;
        color: #495057;
    }

    .form-control-sm {
        padding: 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        width: 100%;
    }

    select.form-control-sm {
        cursor: pointer;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 18px;
        padding: 5px 8px;
        border-radius: 6px;
    }

    .btn-icon:hover {
        background: #fee2e2;
    }

    /* Résumé */
    .summary-line {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-total {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        padding: 15px 0;
        border-top: 2px solid #dee2e6;
        margin-top: 10px;
    }

    .tax-box {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        margin: 15px 0;
    }

    /* Pavé client */
    .client-pad {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
    }

    .numpad {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin: 15px 0;
    }

    .num-btn {
        background: white;
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .num-btn:hover {
        background: #e9ecef;
        border-color: #0066cc;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-custom {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary-custom {
        background: #0066cc;
        color: white;
    }

    .btn-secondary-custom {
        background: #6c757d;
        color: white;
    }

    .text-muted-small {
        font-size: 11px;
        color: #6c757d;
        text-align: center;
        margin-top: 12px;
    }

    .badge-combo {
        background: #e7f3ff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        margin: 3px;
    }

    @media (max-width: 900px) {
        .pos-two-columns {
            flex-direction: column;
        }
    }
</style>
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
                    <!--<i class="fas fa-search"></i>
                    <form method="get" action="{{route('article.search')}}" class="form-inline">
                        
                        <input type="text" name="search"  placeholder="Rechercher une facture...">                                                   
                            
                    </form>-->
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
                            <h2 class="text-center mb-3">Nouvelle vente</h2>
                            <br>
                            <form action="{{ route('commandes.store') }}" method="POST" id="venteForm">
                                @csrf

                                <div class="pos-two-columns">
                                    <!-- COLONNE GAUCHE : Produits + Panier -->
                                    <div class="pos-left">
                                        <div class="pos-card">
                                            <div class="pos-card-header">
                                                <i class="fas fa-box" style="margin-right: 8px;"></i> Caisserie · Thiolom Gandiol
                                            </div>
                                            <div class="pos-card-body">
                                                <!-- Sélection client et dépôt -->
                                                <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                                                    <div style="flex: 1;">
                                                        <label style="font-size: 12px; color: #6c757d;">Client</label>
                                                        <select name="client_id" class="form-control-sm" style="width: 100%;">
                                                            <option value="">-- Sélectionner un client --</option>
                                                            @foreach($clients as $c)
                                                                <option value="{{ $c->id }}">{{ ucfirst($c->nom) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div style="display: flex; align-items: end;">
                                                        <div style="flex: 1;">
                                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#clientModal" style="padding: 6px 12px;">
                                                                + Nouveau client
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <!-- Produits par catégorie (exemple statique, adaptez selon vos données) -->
                                                <div class="category-header">
                                                    <i class="fas fa-box"></i> Articles
                                                </div>
                                                <div class="product-grid" id="productGridShoes">
                                                    @foreach($articles as $article)
                                                        <div class="product-card" data-id="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}" data-nom="{{ $article->nom }}">
                                                            <!--<div class="product-image">
                                                                <img src="{{ asset('storage/'.$article->image) }}" width="20">
                                                            </div>-->
                                                            <div class="product-name">{{ $article->nom }}</div>
                                                            <div class="product-price">{{ number_format($article->prix_vente, 0, ',', ' ') }} CFA</div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="category-header">
                                                    <i class="fas fa-shopping-cart"></i> Panier
                                                </div>

                                                <!-- Tableau des articles du panier -->
                                                <table class="articles-table" id="articlesTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Produit</th>
                                                            <th>prix_vente unit.</th>
                                                            <th>Qté</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="panierRows">
                                                        <tr id="row-0">
                                                            <td>
                                                                <select name="articles[0][article_id]" class="form-control-sm produit-select" style="width: 100%;">
                                                                    <option value="">Choisir un article</option>
                                                                    @foreach($articles as $article)
                                                                        <option value="{{ $article->id }}" data-prix_vente="{{ $article->prix_vente }}" data-nom="{{ $article->nom }}">
                                                                            {{ $article->nom }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="articles[0][prix_vente]" class="form-control-sm prix_vente" step="any" style="width: 100px;">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="articles[0][quantite]" class="form-control-sm quantite" value="1" style="width: 80px;">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control-sm total-ligne" readonly style="width: 100px; background:#f8f9fa;">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn-icon remove-row">🗑️</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <button type="button" id="addRowBtn" class="btn btn-primary" style="margin-top: 15px; width: 100%;">
                                                    + Ajouter un article
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- COLONNE DROITE : Résumé et Paiement -->
                                    <div class="pos-right">
                                        <div class="pos-card">
                                            <div class="pos-card-header">Récapitulatif</div>
                                            <div class="pos-card-body">
                                                <div class="summary-line">
                                                    <span>Sous-total</span>
                                                    <span id="subtotal">0</span> CFA
                                                </div>
                                                <!--<div class="tax-box">
                                                    <div class="summary-line">
                                                        <span>Taxes (TVA 18%)</span>
                                                        <span id="taxAmount">0</span> CFA
                                                    </div>
                                                </div>-->
                                                <div class="summary-total">
                                                    <span>TOTAL TTC</span>
                                                    <span id="totalGlobal">0</span> CFA
                                                </div>

                                                <!-- Suggestions combos -->
                                                <div style="margin-top: 20px;">
                                                    <div class="category-header" style="margin-bottom: 10px;">🔥 Combos populaires</div>
                                                    <div>
                                                        @foreach($articles->take(4) as $combo)
                                                            <span class="badge-combo">{{ $combo->nom }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Module client / Paiement -->
                                                <div class="client-pad">
                                                    <div style="font-weight: 600; margin-bottom: 10px;">👤 Paiement</div>
                                                    <!--<div class="numpad" id="numpad">
                                                        <div class="num-btn" data-value="1">1</div>
                                                        <div class="num-btn" data-value="2">2</div>
                                                        <div class="num-btn" data-value="3">3</div>
                                                        <div class="num-btn" data-value="4">4</div>
                                                        <div class="num-btn" data-value="5">5</div>
                                                        <div class="num-btn" data-value="6">6</div>
                                                        <div class="num-btn" data-value="7">7</div>
                                                        <div class="num-btn" data-value="8">8</div>
                                                        <div class="num-btn" data-value="9">9</div>
                                                        <div class="num-btn" data-value="0">0</div>
                                                        <div class="num-btn" data-value=".">.</div>
                                                        <div class="num-btn" data-value="clear">⌫</div>
                                                    </div>
                                                    <input type="text" id="montantPaye" name="montant" class="form-control-sm" placeholder="Montant reçu" style="width: 100%; margin-bottom: 10px;">-->
                                                    
                                                    <div class="action-buttons">
                                                        <a href="{{ route('commandes.index') }}" class="btn-custom btn-secondary-custom" style="text-align: center; text-decoration: none;">Annuler</a>
                                                        <button type="submit" class="btn-custom btn-primary-custom">Valider ✅</button>
                                                    </div>
                                                    <div class="text-muted-small">
                                                       montant calcule automatiquement
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    let rowIndex = 1;

    // Ajout produit via clic sur carte produit
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            let productId = this.dataset.id;
            let productName = this.dataset.nom;
            let productPrice = this.dataset.prix_vente;

            // Chercher une ligne vide ou ajouter une nouvelle ligne
            let selectElement = document.querySelector('#panierRows select');
            if(selectElement && selectElement.value === "") {
                // Remplir la première ligne vide
                let row = selectElement.closest('tr');
                selectElement.value = productId;
                row.querySelector('.prix_vente').value = productPrice;
                row.querySelector('.quantite').value = 1;
                calculLigne(row);
            } else {
                // Ajouter une nouvelle ligne
                addNewRow(productId, productPrice);
            }
        });
    });

    // Ajouter une nouvelle ligne
    function addNewRow(articleId = null, price = null) {
        let options = `{!! $articles->map(function($a) { return '<option value="'.$a->id.'" data-prix_vente="'.$a->prix_vente.'" data-nom="'.$a->nom.'">'.$a->nom.'</option>'; })->implode('') !!}`;
        
        let rowHtml = `
            <tr id="row-${rowIndex}">
                <td>
                    <select name="articles[${rowIndex}][article_id]" class="form-control-sm produit-select" style="width: 100%;">
                        <option value="">Choisir un article</option>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="articles[${rowIndex}][prix_vente]" class="form-control-sm prix_vente" step="any" style="width: 100px;">
                </td>
                <td>
                    <input type="number" name="articles[${rowIndex}][quantite]" class="form-control-sm quantite" value="1" style="width: 80px;">
                </td>
                <td>
                    <input type="number" class="form-control-sm total-ligne" readonly style="width: 100px; background:#f8f9fa;">
                </td>
                <td>
                    <button type="button" class="btn-icon remove-row">🗑️</button>
                </td>
            </tr>
        `;
        document.querySelector('#panierRows').insertAdjacentHTML('beforeend', rowHtml);
        
        if(articleId && price) {
            let newRow = document.querySelector(`#row-${rowIndex}`);
            let select = newRow.querySelector('.produit-select');
            select.value = articleId;
            newRow.querySelector('.prix_vente').value = price;
            calculLigne(newRow);
        }
        
        rowIndex++;
    }

    // Suppression de ligne
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculTotalGlobal();
        }
    });

    // Auto-remplissage prix_vente
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('produit-select')) {
            let selected = e.target.selectedOptions[0];
            let prix_vente = selected.dataset.prix_vente;
            let row = e.target.closest('tr');
            row.querySelector('.prix_vente').value = prix_vente;
            calculLigne(row);
        }
    });

    // Calcul à la saisie de quantité
    document.addEventListener('input', function(e) {
        if(e.target.classList.contains('quantite')) {
            let row = e.target.closest('tr');
            calculLigne(row);
        }
    });

    function calculLigne(row) {
        let prix_vente = parseFloat(row.querySelector('.prix_vente').value) || 0;
        let qte = parseFloat(row.querySelector('.quantite').value) || 0;
        let total = prix_vente * qte;
        row.querySelector('.total-ligne').value = total.toFixed(0);
        calculTotalGlobal();
    }

    function calculTotalGlobal() {
        let totalHT = 0;
        document.querySelectorAll('.total-ligne').forEach(input => {
            totalHT += parseFloat(input.value) || 0;
        });
        
        let tva = totalHT * 0;
        let totalTTC = totalHT + tva;
        
        document.getElementById('subtotal').innerText = totalHT.toLocaleString();
        document.getElementById('totalGlobal').innerText = totalTTC.toLocaleString();
    }

    // Bouton ajouter ligne
    document.getElementById('addRowBtn').addEventListener('click', function() {
        addNewRow();
    });

    // Numpad virtuel
    let montantInput = document.getElementById('montantPaye');
    document.querySelectorAll('.num-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let value = this.dataset.value;
            if(value === 'clear') {
                montantInput.value = montantInput.value.slice(0, -1);
            } else {
                montantInput.value += value;
            }
        });
    });
</script>

@include('partials.footer')