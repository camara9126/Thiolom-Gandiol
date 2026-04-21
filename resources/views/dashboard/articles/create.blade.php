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
                        <span><i class="fas fa-plus-circle" style="color: var(--primary); margin-right: 0.5rem;"></i> Nouveau article</span>
                        <span class="badge-success">Formulaire d'ajout</span>
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

                    <div class="card col-md-10 mx-auto shadow-sm mt-2">
                        <div class="card-body">
                            <form method="post" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Nom du produit *</label>
                                            <input type="text" name="nom" placeholder="Ex: Perceuse Bosch GBH 2-26" required>
                                        </div>
                                        <div class="col-6">
                                            <label>Catégorie *</label>
                                            <select name="categorie_id" required>
                                                <option value="">Sélectionner une catégorie</option>
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
                                                <option value="">Sélectionner le fournisseur</option>
                                                    @foreach($fournisseur as $f)
                                                        <option value="{{$f->id}}">{{$f->nom}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label>Depot *</label>
                                            <select name="magasin_id" required>
                                                <option value="">Sélectionner le depot</option>
                                                    @foreach($magasins as $m)
                                                        <option value="{{$m->id}}">{{$m->nom}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Prix d'achat (FCFA) *</label>
                                            <input type="number" name="prix_achat"  required>
                                        </div>
                                        <div class="col-6">
                                            <label>Prix de vente (FCFA) *</label>
                                            <input type="number" name="prix_vente" placeholder="Ex: 85000" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Stock *</label>
                                            <input type="number" name="stock" placeholder="Ex: 50" required>
                                        </div>
                                        <div class="col-6">
                                            <label>Stock Minimun *</label>
                                            <select name="stock_min">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                            </select>
                                        </div>                                    
                                    </div>
                                    
                                    <div class="col-8 mx-auto full-width">
                                        <label>Images du produit</label>
                                        <div class="image-upload">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <input type="file" name="image" class="form-file">Cliquez ou glissez-déposez des images ici
                                            <p style="font-size: 0.85rem; color: var(--gray-600);">PNG, JPG jusqu'à 5MB</p>
                                        </div>
                                    </div>

                                    <!--<div class="row">
                                        <div class="col-6">
                                            <label>Images galerie 1</label>
                                            <div class="image-upload">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <input type="file" name="gal_1" class="form-file">Cliquez ou glissez-déposez des images ici
                                                <p style="font-size: 0.85rem; color: var(--gray-600);">PNG, JPG jusqu'à 5MB</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Images galerie 2</label>
                                            <div class="image-upload">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <input type="file" name="gal_2" class="form-file">Cliquez ou glissez-déposez des images ici
                                                <p style="font-size: 0.85rem; color: var(--gray-600);">PNG, JPG jusqu'à 5MB</p>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class="row">
                                        <div class="col-7 full-width">
                                            <label>Description </label>
                                            <textarea rows="4" name="description" placeholder="Description complète, caractéristiques..."></textarea>
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
                                        <i class="fas fa-save"></i> Enregistrer l'article
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

@include('partials.footer')