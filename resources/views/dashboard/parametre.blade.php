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

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Parametre</h1>
                    <div>
                        <!--<button class="btn-outline" style="margin-right: 0.5rem;">
                            <i class="fas fa-download"></i> Exporter
                        </button>
                        <a href="" class="btn-primary">
                            <i class="fas fa-shop"></i>Boutique
                        </a>-->
                    </div>
                </div>

                <!-- Recent Products Table -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-tools" style="color: var(--primary); margin-right: 0.5rem;"></i> Support client</span>
                    </div>
                    <div class="card-body">
                      <div class="stat-card">         
                        <div class="px-2 py-2 mt-0">
                            <p>N'hésiter pas à nous contacter si vous rencontrez un soucis.</p>  
                            <h2 class="fw-bold mb-3">Nos Contacts</h2>
                            <ul class="nav flex-column">
                                <li>Email : bmanager@bcmgroupe.com</li>
                                <li>Telephone : +221 76 280 88 39</li>
                                <li>Whatsapp : <a href="https://wa.me/+221783739364" class="" target="_blank"><i class="fa-brands fa-whatsapp text-success" ></i>&nbsp;783739364</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

@include('partials.footer')