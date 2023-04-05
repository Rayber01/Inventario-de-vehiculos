<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de vehículos</title>
    <!-- Agregar el enlace a los archivos CSS de Material Design -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

  </head>
  <body>
    <!-- Barra de navegación -->
    <nav class="blue-grey darken-4">
      <div class="nav-wrapper container-fluid">
        <a href="#" class="brand-logo">{{ config('app.name', 'Laravel') }}</a>
        <a href="#" data-target="mobile-nav" class="sidenav-trigger"><i class="mdi mdi-menu"></i></a>
        <ul class="right hide-on-med-and-down">
            @auth
                <li><a href="{{ route('home') }}">Tablero</a></li>
            @else
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                <li><a href="{{ route('register') }}">Registrarse</a></li>
            @endauth
        </ul>
      </div>
    </nav>
    <!-- Barra de navegación para dispositivos móviles -->
    <ul class="sidenav" id="mobile-nav">
            @auth
                <li><a href="{{ route('home') }}">Tablero</a></li>
            @else
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                <li><a href="{{ route('register') }}">Registrarse</a></li>
            @endauth
    </ul>
    <!-- Encabezado -->
    <div class="container-fluid my-4">
      <h1 class="center-align">Bienvenido al inventario de vehículos</h1>
      <p class="lead center-align">Aquí podrás llevar un control detallado de los vehículos de la empresa</p>
    </div>
    <!-- Contenido principal -->
    <div class="container-fluid">
      <div class="row">
        <div class="col s12 m6">
          <h3>Últimos vehículos agregados</h3>
          <div class="row">
            <div class="col s12 m6">
              <div class="card">
                <div class="card-image">
                  <img src="https://via.placeholder.com/300x200.png?text=Imagen+de+ejemplo" alt="Imagen de ejemplo">
                </div>
                <div class="card-content">
                  <span class="card-title">Vehículo 1</span>
                  <p>Descripción breve del vehículo 1.</p>
                </div>
                <div class="card-action">
                  <a href="#">Ver detalles</a>
                </div>
              </div>
            </div>
            <div class="col s12 m6">
              <div class="card">
                <div class="card-image">
                  <img src="https://via.placeholder.com/300x200.png?text=Imagen+de+ejemplo" alt="Imagen de ejemplo">
                </div>
                <div class="card-content">
                  <span class="card-title">Vehículo 2</span>
                  <p>Descripción breve del vehículo 2.</p>
                </div>
                <div class="card-action">
                  <a href="#">Ver detalles</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m6">
          <h3>Estadísticas del inventario</h3>
          <div style="height: 400px; width: 100%;">
            <canvas id="chart" style="height: 100%; width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('.sidenav');
                var instances = M.Sidenav.init(elems);
            });

            // Obtener el contexto del canvas
            var ctx = document.getElementById('chart').getContext('2d');

            // Configuración de la gráfica
            var config = {
              type: 'bar',
              data: {
                labels: ['Vendidos', 'Disponibles'],
                datasets: [{
                  label: 'Cantidad de vehículos',
                  data: [10, 25],
                  backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero: true
                    }
                  }]
                }
              }
            };

            // Crear la gráfica
            var chart = new Chart(ctx, config);

        </script>

        <!-- Agregar el enlace a los archivos JavaScript de Material Design -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
</html>