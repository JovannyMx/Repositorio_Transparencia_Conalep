<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Repositorio de Transparencia - CONALEP' }}</title>

    <!-- Favicon CONALEP -->
    <link rel="icon" type="image/png" href="{{ asset('Conalep-logo.png') }}?v=12">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('publico.areas.index') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('Conalep-logo.png') }}" alt="CONALEP Logo" class="h-9 w-auto object-contain">
                    <span class="font-semibold text-gray-800">Repositorio de Transparencia</span>
                </a>

                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">
                    Acceso administrativo &rarr;
                </a>
            </div>
        </div>
    </nav>

    @isset($header)
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-16 py-8 text-center text-sm text-gray-400">
        Dirección de Administración &mdash; Repositorio de Transparencia
    </footer>
</body>
</html>