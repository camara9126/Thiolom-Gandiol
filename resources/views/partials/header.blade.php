<?php

    use App\Models\Article;
    use App\Models\Categorie;
    use App\Models\Client;
    use App\Models\Vente;

    $categories= Categorie::latest()->get();
    $articles= Article::latest()->get();
    $clients= Client::latest()->get();
    $commandes= Vente::latest()->get();
    // Alert sotck
    $alerte = article::produitsEnAlerte()->count();
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
     <!-- Icon Logo -->
     <link rel="shortcut icon" href="{{asset('images/logo-vert.jpeg')}}"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #00457F;
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


         /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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

        .stat-card2 {
            background: var(--primary);
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
</head>
<body>