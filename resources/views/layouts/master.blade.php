<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management - @yield('title')</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        .btn {
            border-radius: 5px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }

        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-1px);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: var(--light-bg);
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .product-image {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .price-tag {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .stock-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .stock-badge.in-stock {
            background-color: #d4edda;
            color: #155724;
        }

        .stock-badge.out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }

        .review-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .search-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .modal-content {
            border: none;
            border-radius: 10px;
        }

        .modal-header {
            background-color: var(--light-bg);
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            background-color: var(--light-bg);
            border-top: 1px solid #dee2e6;
        }

        main {
            flex: 1;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            padding: 0 1.5rem;
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
