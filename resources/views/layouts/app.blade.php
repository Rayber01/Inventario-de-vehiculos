<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Agregar el enlace a los archivos CSS de Material Design -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  </head>
<body>
    <div id="app-normal">
        <!-- Barra de navegación -->
    <nav class="blue-grey darken-4">
      <div class="nav-wrapper container-fluid">
        <a href="/" class="brand-logo">{{ config('app.name', 'Laravel') }}</a>
        <a href="#" data-target="mobile-nav" class="sidenav-trigger"><i class="mdi mdi-menu"></i></a>
        <ul class="right hide-on-med-and-down">
            @auth
                <li><a href="{{ route('home') }}">Tablero</a></li>
            @else
                <li class="@if (Request::is('login')) {{'active'}} @endif"><a href="{{ route('login') }}">Iniciar sesión</a></li>
            @endauth
        </ul>
      </div>
    </nav>
    <!-- Barra de navegación para dispositivos móviles -->
    <ul class="sidenav" id="mobile-nav">
            @auth
                <li><a href="{{ route('home') }}">Tablero</a></li>
            @else
                <li class="@if (Request::is('login')) {{'active'}} @endif"><a href="{{ route('login') }}">Iniciar sesión</a></li>
            @endauth
    </ul>

        
    @yield('content')
        
    </div>
</body>
</html>
