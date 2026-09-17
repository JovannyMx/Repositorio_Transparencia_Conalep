<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repositorio de Transparencia - Dirección de Administración')</title>

    <!-- Compilación de Tailwind CSS y JS con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 font-sans flex flex-col justify-between">

    <!-- Encabezado / Header General -->
    <header class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="{{ url('/') }}" class="text-2xl font-bold hover:text-blue-200 transition">
                    Repositorio de Transparencia
                </a>
                <p class="text-sm text-blue-200">Dirección de Administración — CONALEP Jalisco</p>
            </div>
            
            <!-- Botón de Acceso Administrativo -->
            <div>
                <a href="{{ route('login') }}" class="text-sm bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg transition font-medium">
                    Acceso Administrativo
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido Dinámico inyectado desde cada vista -->
    <main class="max-w-7xl mx-auto px-4 py-8 flex-grow w-full">
        @yield('content')
    </main>

    <!-- Pie de Página / Footer General -->
    <footer class="bg-gray-800 text-gray-300 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} Dirección de Administración — Todos los derechos reservados.</p>
            <p class="text-xs text-gray-500 mt-1">Sistema de Repositorio Institucional de Transparencia</p>
        </div>
    </footer>

</body>
</html>