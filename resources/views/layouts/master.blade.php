<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management System - @yield('title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Modern UI CSS -->
    <link href="{{ asset('css/modern-ui.css') }}" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Modern UI JS -->
    <script src="{{ asset('js/modern-ui.js') }}"></script>
</head>
<body>
    @include('layouts.menu')
    <main class="fade-in">
        <div class="container">
            @yield('content')
        </div>
    </main>
    <footer class="mt-5 py-4 text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 Product Management System</p>
        </div>
    </footer>
</body>
</html>
