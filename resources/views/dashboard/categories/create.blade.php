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
                        <span><i class="fas fa-plus-circle" style="color: var(--primary); margin-right: 0.5rem;"></i> Ajouter un nouvel article</span>
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
                    
                    <div class="card-body">
                        <form method="post" action="{{ route('categorie.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nom du categorie *</label>
                                    <input type="text" name="nom" placeholder="Ex: Perceuse Bosch GBH 2-26" required>
                                </div>

                                <div class="form-group full-width">
                                    <label>Description </label>
                                    <textarea rows="4" name="description" placeholder="Description complète, caractéristiques..."></textarea>
                                </div>

                                <div class="form-group full-width">
                                    <label>Images du produit</label>
                                    <div class="image-upload">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <input type="file" name="image" class="form-file">Cliquez ou glissez-déposez des images ici</p>
                                        <p style="font-size: 0.85rem; color: var(--gray-600);">PNG, JPG jusqu'à 5MB</p>
                                    </div>
                                </div>

                            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                                <a href="{{ route('categorie.index') }}" type="button" class="btn-outline">Annuler</a>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer l'article
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

@include('partials.footer')