<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bloco de Notas') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/borboleta.png') }}" type="image/png">

    <!-- O config('app.name') pega o nome definido no arquivo config/app.php. -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="custom-body">
    <div class="layout-container">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <!--para evitar erro-->
            <header class="page-header">
                <div class="header-inner">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
