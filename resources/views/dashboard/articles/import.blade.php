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

                    @if(session('success'))
                            <div class="alert alert-success mt-2">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger mt-2">
                                {{ session('error') }}
                            </div>
                        @endif


                        <div class="card-body">
                           <form action="{{ route('articles.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label>Fichier Excel</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Importer
                                </button>
                            </form>
   
                        </div>
                </div>

@include('partials.footer')