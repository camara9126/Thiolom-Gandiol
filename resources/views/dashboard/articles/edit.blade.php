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
                    <input type="text" placeholder="Rechercher...">
                </div>

                @include('partials.userMenu')
            </nav>  
   
                <div class="card">
                    <div class="card-header">
                        <span class="badge-success">Formulaire d'edit</span>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-danger">Retour</a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="post" action="{{ route('articles.update', ['article' => $article->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('Put')
                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Nom du produit *</label>
                                            <input type="text" name="nom" value="{{$article->nom}}" placeholder="Ex: Perceuse Bosch GBH 2-26" required>
                                        </div>
                                        <div class="col-6">
                                            <label>Catégorie *</label>
                                            <select name="categorie_id" required>
                                                <option value="{{$article->categorie->id}}">{{$article->categorie->nom}}</option>
                                                @foreach($categorie as $c)
                                                    <option value="{{$c->id}}">{{$c->nom}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label>Fournisseur *</label>
                                                <select name="fournisseur_id" required>
                                                    <option value="{{$article->fournisseur->id}}">{{$article->fournisseur->nom}}</option>
                                                    @foreach($fournisseur as $f)
                                                        <option value="{{$f->id}}">{{$f->nom}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Prix d'achat (FCFA) *</label>
                                            <input type="number" name="prix_achat" value="{{$article->prix_achat}}"  required>
                                        </div>
                                        <div class="col-6">
                                            <label>Prix de vente (FCFA) *</label>
                                            <input type="number" name="prix" value="{{$article->prix}}" placeholder="Ex: 85000" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Stock *</label>
                                            <input type="number" name="stock" value="{{$article->stock}}" placeholder="Ex: 50" required>
                                        </div>
                                        <div class="col-6">
                                            <label>Stock Minimun *</label>
                                            <select name="stock_min">
                                                <option value="{{$article->stock_min}}">{{$article->stock_min}}</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                            </select>
                                        </div>                                    
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label>Étiquettes</label>
                                            <select name="etiquette">
                                                <option value="{{$article->etiquette}}">{{$article->etiquette}}</option>
                                                <option value="promo">Promo</option>
                                                <option value="nouveau">Nouveau</option>
                                                <option value="vedette">Vedette</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label>Designation *</label>
                                            <input type="text" name="designation" value="{{$article->designation}}" placeholder="Ex: Perceuse Bosch GBH 2-26" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 full-width">
                                        <label>Images du produit</label>
                                            <img src="{{asset('storage/'.$article->image)}}" width="100" alt="">
                                            <input type="file" name="image" class="form-file">Cliquez ou glissez-déposez des images ici
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Images galerie 1</label>
                                                <img src="{{asset('storage/'.$article->gal_1)}}" width="100" alt="">
                                                <input type="file" name="gal_1" class="form-file">Cliquez ou glissez-déposez des images ici
                                        </div>
                                        <div class="col-6">
                                            <label>Images galerie 2</label>
                                                <img src="{{asset('storage/'.$article->gal_2)}}" width="100" alt="">
                                                <input type="file" name="gal_2" class="form-file">Cliquez ou glissez-déposez des images ici
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-7 full-width">
                                            <label>Description </label>
                                            <textarea rows="4" name="description" placeholder="Description complète, caractéristiques...">
                                                {{$article->description}}
                                            </textarea>
                                        </div>

                                        <div class="col-5">
                                            <label>Statut</label>
                                            <select name="statut">
                                                <option value="1">Publié</option>
                                                <option value="0">En attente</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                                <a href="{{ route('articles.index') }}" type="button" class="btn-outline">Annuler</a>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer la Modification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

@include('partials.footer')