<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal de Transparencia - CONALEP Jalisco')</title>
    
    <!-- CDN de Tailwind CSS con paleta institucional y fuente de respaldo -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'garet': ['Garet', 'Segoe UI', 'Roboto', 'sans-serif'],
                    },
                    colors: {
                        'conalep-green': '#007D69',
                        'conalep-dark': '#004D40',
                        'conalep-light': '#E6F2F0',
                        'conalep-mint': '#A2D5C6',
                        'conalep-magenta': '#C20E4D',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen font-garet antialiased">

    <!-- Header / Barra de Navegación -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                <img 
                    src="{{ asset('images/Conalep-logo.png') }}" 
                    alt="Logo CONALEP Jalisco" 
                    class="h-12 w-auto object-contain"
                >
                <div class="border-l border-slate-200 pl-3">
                    <span class="block font-extrabold text-conalep-dark leading-none text-base group-hover:text-conalep-green transition-colors">
                        Jalisco
                    </span>
                    <span class="text-xs text-conalep-green font-semibold tracking-tight">
                        Portal de Transparencia
                    </span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-700">
                <a href="{{ route('home') }}" class="hover:text-conalep-green transition py-1 border-b-2 border-transparent hover:border-conalep-green">
                    Áreas Generadoras
                </a>
                <a href="{{ route('contacto.public') }}" class="hover:text-conalep-green transition py-1 border-b-2 border-transparent hover:border-conalep-green">
                    Unidad de Transparencia
                </a>
            </nav>
        </div>
    </header>

    <!-- Contenido dinámico -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer Institucional -->
    <footer class="bg-conalep-dark text-white border-t-4 border-conalep-green py-8 mt-12 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div>
                <p class="font-bold text-sm text-white">
                    Colegio de Educación Profesional Técnica del Estado de Jalisco
                </p>
                <p class="text-conalep-mint mt-1">
                    Cumplimiento con la Ley de Transparencia y Acceso a la Información Pública del Estado de Jalisco.
                </p>
            </div>
            <div class="text-slate-300">
                &copy; {{ date('Y') }} <span class="font-bold text-white">CONALEP Jalisco</span>. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>