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
                            @method('Put')
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
                                    @foreach($devis->details as $index => $detail)
                                    <tr>
                                        <td>
                                            <select name="articles[{{$index}}][article_id]" class="form-control produit-select">
                                                <option value="{{$detail->article->id}}" data-prix="{{ $detail->article->prix }}">{{$detail->article->nom}}</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" data-prix="{{ $article->prix }}">
                                                        {{ $article->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" name="articles[{{$index}}][prix]" value="{{$detail->prix_unitaire}}" class="form-control prix" >
                                        </td>

                                        <td>
                                            <input type="number" name="articles[{{$index}}][quantite]" value="{{$detail->quantite}}" class="form-control quantite" value="1">
                                        </td>

                                        <td>
                                            <input type="number" value="{{$detail->total}}" class="form-control total-ligne" readonly>
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
                    </div>
                </div>


    <script>
        let index = 1;

        // Ajouter ligne
        document.getElementById('addRow').addEventListener('click', function () {

            let row = `
            @foreach($devis->details as $detail)
            <tr>
                <td>
                    <select name="articles[${index}][article_id]" class="form-control produit-select">
                        <option value="{{$detail->article->id}}" data-prix="{{ $detail->article->prix }}>{{$detail->article->nom}}</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" data-prix="{{ $article->prix }}">
                                {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="number" name="articles[${index}][prix]" value="{{$detail->prix_unitaire}}" class="form-control prix" >
                </td>

                <td>
                    <input type="number" name="articles[${index}][quantite]" value="{{$detail->quantite}}" class="form-control quantite" value="1">
                </td>

                <td>
                    <input type="number" value="{{$detail->total}}" class="form-control total-ligne" readonly>
                </td>

                <td>
                    <button type="button" class="btn btn-danger remove">X</button>
                </td>
            </tr>
            @endforeach
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