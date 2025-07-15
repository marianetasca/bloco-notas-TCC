<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-RhYwrVqQoy5mKspcZMY5aZsmCLaLXR3RZvxR7xWzYmZkEV2MDmjg9ZuNpA/sPi3Y" crossorigin="anonymous">

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="custom-body">
    <div class="layout-container">
        @include('layouts.navigation')

        <main class="container">
            @yield('slot')
        </main>
    </div>

    <!-- Bootstrap JS (via CDN) + dependências -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Q0zX1IAVqv4O+XU4aLuZsA2F9e3gyOvK5kSn4m+4zWv8z8BwlG1OqvhMxgWwjFtY" crossorigin="anonymous"></script>

    @stack('scripts')
</body>
</html>
