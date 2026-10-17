<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Repositorio de Transparencia' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('publico.areas.index') }}" class="flex items-center space-x-2">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    <span class="font-semibold text-gray-800">Repositorio de Transparencia</span>
                </a>

                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">
                    Acceso administrativo →
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
        Dirección de Administración — Repositorio de Transparencia
    </footer>
</body>
</html>