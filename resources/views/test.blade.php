<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BTP Matériaux</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #198754;
            --primary-dark: #146c43;
            --primary-light: #d1e7dd;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --gray-800: #343a40;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.5;
        }

        /* Layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--primary);
            color: var(--white);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
        }

        .sidebar-header h2 i {
            font-size: 2rem;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 0.25rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-left-color: var(--white);
        }

        .sidebar-nav a i {
            width: 24px;
            font-size: 1.2rem;
        }

        .sidebar-nav .badge {
            margin-left: auto;
            background: var(--white);
            color: var(--primary);
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: all 0.3s ease;
        }

        /* Top Navigation */
        .top-nav {
            background: var(--white);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: var(--gray-100);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            width: 300px;
        }

        .search-bar i {
            color: var(--gray-600);
            margin-right: 0.5rem;
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.95rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-menu i {
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
            transition: color 0.2s;
        }

        .user-menu i:hover {
            color: var(--primary);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
        }

        .user-info {
            display: none;
        }

        /* Content Area */
        .content {
            padding: 2rem;
        }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 0.95rem;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-info h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0.25rem 0 0;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.75rem;
        }

        /* Tables and Cards */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--white);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 1rem 1.5rem;
            background: var(--gray-100);
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-600);
            border-bottom: 2px solid var(--gray-300);
        }

        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-img {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .badge-success {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            transition: color 0.2s;
            font-size: 1rem;
        }

        .action-btn:hover {
            color: var(--primary);
        }

        .action-btn.delete:hover {
            color: #dc3545;
        }

        /* Form */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
        }

        .image-upload {
            border: 2px dashed var(--gray-300);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .image-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .image-upload i {
            font-size: 2rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .search-bar {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .top-nav {
                padding: 1rem;
            }

            .search-bar {
                display: none;
            }

            .content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .user-info {
                display: none;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .user-menu {
                gap: 1rem;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>
                    <i class="fas fa-tools"></i>
                    BTP Matériaux
                </h2>
                <p>Dashboard d'administration</p>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="#" class="active">
                            <i class="fas fa-chart-pie"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-box"></i>
                            <span>Articles</span>
                            <span class="badge">12</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-plus-circle"></i>
                            <span>Ajouter un article</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-tags"></i>
                            <span>Catégories</span>
                            <span class="badge">6</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-list"></i>
                            <span>Sous-catégories</span>
                        </a>
                    </li>
                    
                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="#">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Commandes</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-users"></i>
                            <span>Clients</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-star"></i>
                            <span>Avis</span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>

                    <li>
                        <a href="#">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

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

                <div class="user-menu">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-envelope"></i>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <span>AD</span>
                        </div>
                        <div class="user-info">
                            <div style="font-weight: 600;">Admin</div>
                            <div style="font-size: 0.85rem; color: var(--gray-600);">admin@btpmateriaux.sn</div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1>Tableau de bord</h1>
                    <div>
                        <button class="btn-outline" style="margin-right: 0.5rem;">
                            <i class="fas fa-download"></i> Exporter
                        </button>
                        <button class="btn-primary">
                            <i class="fas fa-plus"></i> Nouvel article
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Articles</h3>
                            <div class="number">156</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Catégories</h3>
                            <div class="number">12</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Commandes</h3>
                            <div class="number">48</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Clients</h3>
                            <div class="number">324</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Add Product Form -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-plus-circle" style="color: var(--primary); margin-right: 0.5rem;"></i> Ajouter un nouvel article</span>
                        <span class="badge-success">Formulaire d'ajout</span>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nom du produit *</label>
                                    <input type="text" placeholder="Ex: Perceuse Bosch GBH 2-26" required>
                                </div>
                                <div class="form-group">
                                    <label>Catégorie *</label>
                                    <select required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option>Outillage électrique</option>
                                        <option>Outillage manuel</option>
                                        <option>Électricité</option>
                                        <option>Plomberie</option>
                                        <option>Matériaux construction</option>
                                        <option>Peinture</option>
                                        <option>Équipement de protection</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sous-catégorie</label>
                                    <select>
                                        <option value="">Sélectionner (optionnel)</option>
                                        <option>Perceuses</option>
                                        <option>Meuleuses</option>
                                        <option>Visseuses</option>
                                        <option>Scies</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Prix (FCFA) *</label>
                                    <input type="number" placeholder="Ex: 85000" required>
                                </div>
                                <div class="form-group">
                                    <label>Prix barré (optionnel)</label>
                                    <input type="number" placeholder="Ex: 95000">
                                </div>
                                <div class="form-group">
                                    <label>Stock *</label>
                                    <input type="number" placeholder="Ex: 50" required>
                                </div>
                                <div class="form-group full-width">
                                    <label>Description courte *</label>
                                    <textarea rows="2" placeholder="Brève description du produit..." required></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>Description détaillée</label>
                                    <textarea rows="4" placeholder="Description complète, caractéristiques..."></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>Images du produit</label>
                                    <div class="image-upload">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Cliquez ou glissez-déposez des images ici</p>
                                        <p style="font-size: 0.85rem; color: var(--gray-600);">PNG, JPG jusqu'à 5MB</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Statut</label>
                                    <select>
                                        <option>Publié</option>
                                        <option>Brouillon</option>
                                        <option>En attente</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Étiquettes</label>
                                    <input type="text" placeholder="Ex: promotion, nouveau, vedette">
                                </div>
                            </div>
                            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                                <button type="button" class="btn-outline">Annuler</button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer l'article
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Products Table -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-box" style="color: var(--primary); margin-right: 0.5rem;"></i> Articles récents</span>
                        <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Voir tout →</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-img">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;">Perceuse Bosch Pro</div>
                                                    <div style="font-size: 0.85rem; color: var(--gray-600);">GBH 2-26</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Outillage électrique</td>
                                        <td><strong>85 000 FCFA</strong></td>
                                        <td><span class="badge-success">45 en stock</span></td>
                                        <td><span class="badge-success">Publié</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn" title="Modifier"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn" title="Dupliquer"><i class="fas fa-copy"></i></button>
                                                <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-img">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;">Meuleuse Dewalt</div>
                                                    <div style="font-size: 0.85rem; color: var(--gray-600);">125mm 850W</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Outillage électrique</td>
                                        <td><strong>65 000 FCFA</strong></td>
                                        <td><span class="badge-success">32 en stock</span></td>
                                        <td><span class="badge-success">Publié</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn"><i class="fas fa-copy"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-img">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;">Ciment Dangote</div>
                                                    <div style="font-size: 0.85rem; color: var(--gray-600);">42.5R (sac 50kg)</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Matériaux</td>
                                        <td><strong>5 000 FCFA</strong></td>
                                        <td><span class="badge-warning">8 en stock</span></td>
                                        <td><span class="badge-success">Publié</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn"><i class="fas fa-copy"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-img">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;">Échelle télescopique</div>
                                                    <div style="font-size: 0.85rem; color: var(--gray-600);">3.8m aluminium</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Équipement</td>
                                        <td><strong>39 900 FCFA</strong></td>
                                        <td><span class="badge-success">15 en stock</span></td>
                                        <td><span class="badge-success">Publié</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn"><i class="fas fa-copy"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-tags" style="color: var(--primary); margin-right: 0.5rem;"></i> Catégories</span>
                        <button class="btn-primary" style="padding: 0.375rem 1rem; font-size: 0.9rem;">
                            <i class="fas fa-plus"></i> Nouvelle catégorie
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Description</th>
                                        <th>Nombre d'articles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Outillage électrique</strong></td>
                                        <td>Perceuses, meuleuses, visseuses...</td>
                                        <td>24</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Outillage manuel</strong></td>
                                        <td>Marteaux, tournevis, clés...</td>
                                        <td>18</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Électricité</strong></td>
                                        <td>Disjoncteurs, câbles, prises...</td>
                                        <td>32</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Plomberie</strong></td>
                                        <td>Tuyaux, raccords, robinets...</td>
                                        <td>27</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Matériaux construction</strong></td>
                                        <td>Ciment, fer à béton, parpaings...</td>
                                        <td>15</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Menu toggle pour mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Fermer le menu si on clique en dehors (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Simuler des actions
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (this.classList.contains('delete')) {
                    if (confirm('Supprimer cet élément ?')) {
                        alert('Élément supprimé (simulation)');
                    }
                } else {
                    alert('Action : ' + (this.querySelector('i')?.className || 'modification'));
                }
            });
        });

        document.querySelector('.btn-primary[type="submit"]')?.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Article ajouté avec succès ! (simulation)');
        });

        document.querySelector('.btn-outline')?.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.textContent.includes('Annuler')) {
                if (confirm('Annuler la saisie ?')) {
                    document.querySelector('form').reset();
                }
            }
        });
    </script>
</body>
</html>