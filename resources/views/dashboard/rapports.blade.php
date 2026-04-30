<?php

    use App\Models\Article;
    use App\Models\Categorie;
    use App\Models\Entreprise;
    use App\Models\Recettes;

    $categories= Categorie::latest()->get();
    $articles= Article::latest()->get();

    // Alert sotck
    $alerte = Article::produitsEnAlerte()->count();

     // chiffre d'affaire mois actuel ttc
    $caMoisActuel = Recettes::where('statut', 'recu')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('montant');

    //Chiffre d'affaire HT + montant TVA
    $entreprise = Entreprise::findOrFail(1);
    $montant_tva = $caMoisActuel * ($entreprise->taux_tva / 100) /(1 + ($entreprise->taux_tva / 100));
    $ca_ht = $caMoisActuel - $montant_tva;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
     <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
     <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #00778B;
            --primary-dark: #2F8EE0;
            --primary-light: #EDF5FA;
            --white: #ffffff;
            --gray-100: #f5f7fb;
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
            color:#FF9D1B;
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
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--white);
            font-weight: 500;
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
            grid-template-columns: repeat(1, 1fr);
            gap: 1.1rem;
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
            font-weight: 600;
            font-size: 1.0rem;
        }

        input, select, textarea {
            width: 100%;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
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
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .image-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .image-upload i {
            font-size: 1rem;
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
    <style>
        
        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Table Styles */
        .data-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .data-table th {
            background: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: #495057;
        }
        
        .data-table td {
            padding: 15px;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        /* Badge Styles */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .badge-paid {
            background: #d4edda;
            color: #00457F;
        }
        
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-canceled {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Button Styles */
        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        
        /* Footer */
        .footer {
            background: white;
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid #e9ecef;
        }

        /* rapport */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e1e5eb;
        }

        h1 {
            color: #2c3e50;
            font-size: 28px;
        }

        .period-selector {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .period-selector select {
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: white;
            font-weight: 500;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }

        .orders .stat-icon {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .revenue .stat-icon {
            background-color: #e8f5e9;
            color: #00457F;
        }

        .products .stat-icon {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .customers .stat-icon {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .stat-info h3 {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 13px;
        }

        .positive {
            color: #00457F;
        }

        .negative {
            color: #e74c3c;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        @media (max-width: 1100px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h2 {
            font-size: 18px;
            color: #2c3e50;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
            color: #7f8c8d;
            font-size: 14px;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #ecf0f1;
            color: #2c3e50;
        }

        .btn-secondary:hover {
            background-color: #d5dbdb;
        }
                /* Dashboard */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 18px;
            color: var(--gray-color);
            font-weight: 500;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .icon-income {
            background: linear-gradient(135deg, #00457F, #2F8EE0);
        }
        
        .icon-expense {
            background: linear-gradient(135deg, #f44336, #ff9800);
        }
        
        .icon-profit {
            background: linear-gradient(135deg, #2196f3, #03a9f4);
        }
        
        .icon-cash {
            background: linear-gradient(135deg, #9c27b0, #673ab7);
        }
        
        .card-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .card-value.positive {
            color: var(--success-color);
        }
        
        .card-value.negative {
            color: var(--danger-color);
        }
        
        .card-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .trend-up {
            color: var(--success-color);
        }
        
        .trend-down {
            color: var(--danger-color);
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .chart-card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .chart-title {
            font-size: 20px;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .period-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .period-btn {
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .period-btn.active {
            background-color: var(--secondary-color);
            color: green;
            border-color: var(--secondary-color);
        }
        
        .chart-container {
            height: 300px;
            position: relative;
        }
        
        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        
        @media (max-width: 992px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

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
                    <h1>Tableau de bord</h1>
                    <!--<div>
                        <button class="btn-outline" style="margin-right: 0.5rem;">
                            <i class="fas fa-download"></i> Exporter
                        </button>
                        <a href="" class="btn-primary">
                            <i class="fas fa-shop"></i>Boutique
                        </a>
                    </div>-->
                </div>

                            <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card" style="background-color: #3BC72F;">
                        <div class="stat-info">
                            <h3 class="text-white">Total Recettes</h3>
                            <div class="card-value" id="total-revenus"></div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="stat-card" style="background-color: red;">
                        <div class="stat-info">
                            <h3 class="text-white">Total Depenses</h3>
                            <div class="card-value" id="total-depenses">8</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                    <div class="stat-card" style="background-color: #1C8FED;">
                        <div class="stat-info">
                            <h3 class="text-white">Résultat Net <br> (Bénéfice ce mois)</h3>
                            <div class="card-value positive" id="resultat-net">48</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="stat-card" style="background-color: #DC1CED;">
                        <div class="stat-info">
                            <h3 class="text-white">Trésorerie Actuelle <br> (Solde disponible)</h3>
                            <div class="card-value" id="tresorerie">324</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="container-fluid p-3 p-md-4" id="contentArea">
                    <!-- Dashboard Section -->
                    <section id="dashboard" class="content-section">
                      
                        @if($entreprise->taux_tva == 18)
                            <div class="row mt-2 mb-3">
                                <div class="col-md-12">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="text-muted mb-1">Montant TVA</p>
                                                <h3 class="value fw-bold">{{ number_format($montant_tva, 0, ',', ' ') }} XOF</h3>
                                            </div>
                                            <div class="icon bg-primary bg-opacity-10 text-primary">
                                                <!--<i class="fas fa-franc-sign"></i>-->
                                                <span>💰</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Charts Section avec Graphiques -->
                        <section class="charts-section">
                            <!-- Graphique 1: Évolution des Recettes et Dépenses -->
                            <div class="chart-card">
                                <div class="chart-header">
                                    <h3 class="chart-title">Évolution des Recettes et Dépenses</h3>
                                    <div class="period-selector" id="period-selector-1">
                                        <button class="period-btn active" data-period="mensuel">Mensuel</button>
                                        <button class="period-btn" data-period="trimestriel">Trimestriel</button>
                                        <button class="period-btn" data-period="annuel">Annuel</button>
                                    </div>
                                </div>
                                <div class="chart-container">
                                    <canvas id="evolutionChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Graphique 2: Répartition des Dépenses -->
                            <div class="chart-card">
                                <div class="chart-header">
                                    <h3 class="chart-title">Répartition des Top articles</h3>
                                    <div class="period-selector" id="period-selector-2">
                                        <button class="period-btn active" data-period="mois">Ce mois</button>
                                        <button class="period-btn" data-period="annee">Cette année</button>
                                    </div>
                                </div>
                                <div class="chart-container">
                                    <canvas id="repartitionChart"></canvas>
                                </div>
                            </div>
                        </section>  

                        <div class="stat-card">
                            <div class="col-12">
                            <h1 class="mb-2">Solvabilité de l’entreprise</h1>
                            
                                @if( $entreprise->statut_solvabilite == 'solvable')
                                    <h4><span class="text-success" style="text-decoration: underline;">NB</span> : Votre entreprise est solvable .</h4>
                                @else
                                    <h4><span class="text-danger"style="text-decoration: underline;">NB</span> : Votre entreprise est insolvable .</h4>
                                @endif
                            </div>
                        </div>
                    </section>

                </div>

            </div>
        </main>
    </div>

     <!-- Donnee graphique recette, depense et benefice -->
    <script>

        // ============================================
        // DONNÉES COMPTABLES POUR LES GRAPHIQUES
        // ============================================

        // Configuration des couleurs
        const colors = {
            primary: '#3949ab',
            secondary: '#5c6bc0',
            success: '#4caf50',
            danger: '#f44336',
            warning: '#ff9800',
            info: '#2196f3',
            purple: '#9c27b0',
            teal: '#009688',
            orange: '#ff5722',
            pink: '#e91e63',
            categories: [
                '#4caf50', '#f44336', '#2196f3', '#ff9800', 
                '#9c27b0', '#009688', '#ff5722', '#e91e63',
                '#3f51b5', '#00bcd4', '#8bc34a', '#ffc107'
            ]
        };

        // ============================================
        // DONNÉES MENSUELLES - ANNÉE EN COURS
        // ============================================
        const monthlyData = @json($monthlyData);
        

        // ============================================
        // DONNÉES TRIMESTRIELLES
        // ============================================
        const quarterlyData = @json($quarterlyData);


        // ============================================
        // DONNÉES ANNUELLES
        // ============================================

        const yearlyData = @json($yearlyData);

        // ============================================
        // DONNÉES DE RÉPARTITION DES DÉPENSES - MOIS
        // ============================================
        
        const expensesDistributionMonth = {
            categories: @json($categories),
            amounts: @json($amounts),
            colors: ['#4caf50', '#2196f3', '#ff9800', '#f44336', 
                     '#9c27b0', '#009688', '#ff5722', '#e91e63'],
        };

        // ============================================
        // DONNÉES DE RÉPARTITION DES DÉPENSES - ANNÉE
        // ============================================
        
       const expensesDistributionYear = {
            categories: @json($yearCategories),
            amounts: @json($yearAmounts),
             colors: ['#4caf50', '#2196f3', '#ff9800', '#f44336', 
                     '#9c27b0', '#009688', '#ff5722', '#e91e63'],
        };

        // ============================================
        // INITIALISATION DES GRAPHIQUES
        // ============================================
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les graphiques
            initEvolutionChart('mensuel');
            initRepartitionChart('mois');
            
            // Mettre à jour les valeurs du dashboard
            updateDashboardValues();
            
            // Gestionnaires d'événements pour les boutons de période
            setupPeriodButtons();
        });

        // ============================================
        // GRAPHIQUE 1: ÉVOLUTION DES RECETTES ET DÉPENSES
        // ============================================
        
        let evolutionChart;
        
        function initEvolutionChart(period) {
            const ctx = document.getElementById('evolutionChart').getContext('2d');
            
            let labels, revenueData, expenseData, profitData, title;
            
            switch(period) {
                case 'mensuel':
                    labels = monthlyData.months;
                    revenueData = monthlyData.revenues;
                    expenseData = monthlyData.expenses;
                    profitData = monthlyData.profits;
                    title = 'Évolution mensuelle <?= now()->year ?>';
                    break;
                case 'trimestriel':
                    labels = quarterlyData.quarters;
                    revenueData = quarterlyData.revenues;
                    expenseData = quarterlyData.expenses;
                    profitData = quarterlyData.profits;
                    title = 'Évolution trimestrielle <?= now()->year ?>';
                    break;
                case 'annuel':
                    labels = yearlyData.years;
                    revenueData = yearlyData.revenues;
                    expenseData = yearlyData.expenses;
                    profitData = yearlyData.profits;
                    title = 'Évolution annuelle de 2 ans à <?= now()->year ?>';
                    break;
            }
            
            // Détruire le graphique existant s'il y en a un
            if (evolutionChart) {
                evolutionChart.destroy();
            }
            
            evolutionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Recettes',
                            data: revenueData,
                            borderColor: colors.success,
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: colors.success,
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Dépenses',
                            data: expenseData,
                            borderColor: colors.danger,
                            backgroundColor: 'rgba(244, 67, 54, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: colors.danger,
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Bénéfice',
                            data: profitData,
                            borderColor: colors.info,
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointBackgroundColor: colors.info,
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            tension: 0.3,
                            fill: false,
                            hidden: false // Caché par défaut, peut être activé
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: title,
                            font: {
                                size: 14,
                                weight: '500'
                            },
                            padding: {
                                bottom: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.raw || 0;
                                    return `${label}: ${value.toLocaleString('fr-FR')} XOF`;
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return  value.toLocaleString('fr-FR') + 'XOF ';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        // GRAPHIQUE 2: RÉPARTITION DES DÉPENSES
        // ============================================
        
        let repartitionChart;
        
        function initRepartitionChart(period) {
            const ctx = document.getElementById('repartitionChart').getContext('2d');
            
            let labels, data, backgroundColors, title;
            
            if (period === 'mois') {
                labels = expensesDistributionMonth.categories;
                data = expensesDistributionMonth.amounts;
                backgroundColors = expensesDistributionMonth.colors;
                title =  new Date().toLocaleDateString('fr-FR', { month: 'long' });
            } else {
                labels = expensesDistributionYear.categories;
                data = expensesDistributionYear.amounts;
                backgroundColors = colors.categories;
                title = 'Dépenses annuelles - <?= now()->year ?>';
            }
            
            // Détruire le graphique existant
            if (repartitionChart) {
                repartitionChart.destroy();
            }
            
            repartitionChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: 'white',
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: title,
                            font: {
                                size: 14,
                                weight: '500'
                            },
                            padding: {
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: XOF ${value.toLocaleString('fr-FR')} (${percentage} %)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    cutout: '60%',
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 20
                        }
                    }
                }
            });
        }

        // ============================================
        // GESTIONNAIRES DE PÉRIODE
        // ============================================
        
        function setupPeriodButtons() {
            // Boutons pour le graphique d'évolution
            document.querySelectorAll('#period-selector-1 .period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Retirer la classe active de tous les boutons
                    document.querySelectorAll('#period-selector-1 .period-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    // Ajouter la classe active au bouton cliqué
                    this.classList.add('active');
                    
                    // Mettre à jour le graphique avec la période sélectionnée
                    const period = this.getAttribute('data-period');
                    initEvolutionChart(period);
                    
                    // Mettre à jour les valeurs du dashboard en fonction de la période
                    updateDashboardValuesForPeriod(period);
                });
            });
            
            // Boutons pour le graphique de répartition
            document.querySelectorAll('#period-selector-2 .period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('#period-selector-2 .period-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    const period = this.getAttribute('data-period');
                    initRepartitionChart(period);
                });
            });
        }

        // ============================================
        // MISE À JOUR DES VALEURS DU DASHBOARD
        // ============================================
        
        function updateDashboardValues() {
            // Utiliser les données du mois actuel (index 10)
            const currentMonth = new Date().getMonth(); // Mois (0-indexed)
            
            document.getElementById('total-revenus').textContent = 
                `${monthlyData.revenues[currentMonth].toLocaleString('fr-FR')} XOF`;
            
            document.getElementById('total-depenses').textContent = 
                `${monthlyData.expenses[currentMonth].toLocaleString('fr-FR')} XOF`;
            
            document.getElementById('resultat-net').textContent = 
                `${monthlyData.profits[currentMonth].toLocaleString('fr-FR')} XOF`;
            
            // Trésorerie (cumul des bénéfices)
            let tresorerie = 0;
            for(let i = 0; i <= currentMonth; i++) {
                tresorerie += monthlyData.profits[i];
            }
            document.getElementById('tresorerie').textContent = 
                `${tresorerie.toLocaleString('fr-FR')} XOF`;
        }
        
        function updateDashboardValuesForPeriod(period) {
            if (period === 'mensuel') {
                updateDashboardValues();
            } else if (period === 'trimestriel') {
                // Utiliser les données du T4
                document.getElementById('total-revenus').textContent = 
                    `${quarterlyData.revenues[3].toLocaleString('fr-FR')} XOF`;
                document.getElementById('total-depenses').textContent = 
                    `${quarterlyData.expenses[3].toLocaleString('fr-FR')} XOF`;
                document.getElementById('resultat-net').textContent = 
                    `${quarterlyData.profits[3].toLocaleString('fr-FR')} XOF`;
                document.getElementById('tresorerie').textContent = 
                    `${quarterlyData.profits[3].toLocaleString('fr-FR')} XOF`;
            } else if (period === 'annuel') {
                // Utiliser les données actuelles
                document.getElementById('total-revenus').textContent = 
                    `${yearlyData.revenues[4].toLocaleString('fr-FR')} XOF`;
                document.getElementById('total-depenses').textContent = 
                    `${yearlyData.expenses[4].toLocaleString('fr-FR')} XOF`;
                document.getElementById('resultat-net').textContent = 
                    `${yearlyData.profits[4].toLocaleString('fr-FR')} XOF`;
                document.getElementById('tresorerie').textContent = 
                    `${yearlyData.profits[4].toLocaleString('fr-FR')} XOF`;
            }
        }

        // ============================================
        // FONCTIONS D'EXPORT POUR LES GRAPHIQUES
        // ============================================
        
        function exportChartAsImage(chartId, fileName) {
            const canvas = document.getElementById(chartId);
            const link = document.createElement('a');
            link.download = `${fileName}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // ============================================
        // SIMULATION DE DONNÉES EN TEMPS RÉEL
        // ============================================
        
        // Fonction pour générer des données aléatoires (démonstration)
        function generateRandomData() {
            const newRevenue = monthlyData.revenues[10] + (Math.random() * 2000 - 1000);
            const newExpense = monthlyData.expenses[10] + (Math.random() * 1000 - 500);
            
            document.getElementById('total-revenus').textContent = 
                `XOF ${Math.round(newRevenue).toLocaleString('fr-FR')}.00`;
            document.getElementById('total-depenses').textContent = 
                `XOF ${Math.round(newExpense).toLocaleString('fr-FR')}.00`;
            document.getElementById('resultat-net').textContent = 
                `XOF ${Math.round(newRevenue - newExpense).toLocaleString('fr-FR')}.00`;
        }

        // ============================================
        // EXPORT DES DONNÉES AU FORMAT CSV
        // ============================================
        
        function exportMonthlyDataToCSV() {
            let csv = "Mois,Recettes,Dépenses,Bénéfice\n";
            
            monthlyData.months.forEach((month, index) => {
                csv += `${month},${monthlyData.revenues[index]},${monthlyData.expenses[index]},${monthlyData.profits[index]}\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'donnees_comptables_2023.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Ajouter des écouteurs pour les boutons d'export
        document.addEventListener('DOMContentLoaded', function() {
            // Exemple d'utilisation
            const exportBtn = document.querySelector('.btn-primary i.fa-file-export').parentElement;
            if (exportBtn) {
                exportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    exportMonthlyDataToCSV();
                });
            }
        });

 
        // Exécuter les calculs
        const yearlyStats = calculateYearlyStats();
    </script>

@include('partials.footer')