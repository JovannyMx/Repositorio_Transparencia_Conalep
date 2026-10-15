<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Repositorio de Transparencia - CONALEP') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-full text-slate-800">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Panel Izquierdo: Identidad Visual --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#003B2D] flex-col justify-between p-12 xl:p-16 overflow-hidden">
            
            {{-- Patrón de fondo (Malla sutil) --}}
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] [background-size:40px_40px]"></div>

            {{-- Espacio superior vacío para centrar el contenido principal --}}
            <div></div>

            {{-- Contenido Central Izquierdo --}}
            <div class="relative z-10 flex flex-col items-center text-center max-w-lg mx-auto">
                
                {{-- Logo CONALEP --}}
               <img src="{{ asset('images/Conalep-logo.png') }}" alt="CONALEP Logo" class="h-20 mb-10 object-contain brightness-0 invert opacity-90">
                {{-- Etiqueta Sistema Institucional --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/5 text-white/90 text-[11px] font-semibold tracking-wider uppercase mb-8">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Sistema Institucional
                </div>
                
                {{-- Títulos --}}
                <h1 class="text-4xl xl:text-5xl font-extrabold text-white mb-6 leading-tight tracking-tight">
                    Repositorio de <br> Transparencia
                </h1>
                
                <p class="text-base text-emerald-50/70 font-normal leading-relaxed px-4">
                    Plataforma centralizada de alta seguridad para la gestión, auditoría y publicación de la información oficial del Estado de Jalisco.
                </p>
            </div>

            {{-- Pie del Panel Izquierdo --}}
            <div class="relative z-10 flex justify-between items-center text-emerald-100/50 text-xs font-medium border-t border-emerald-100/10 pt-6">
                <span>© {{ date('Y') }} CONALEP Jalisco</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Infraestructura Segura
                </span>
            </div>
        </div>

        {{-- Panel Derecho: Interfaz de Formulario --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center bg-white relative p-6 sm:p-12 lg:p-24">
            
            {{-- Botón Volver al portal --}}
            <div class="absolute top-8 left-8 sm:left-12 lg:left-16">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al portal
                </a>
            </div>

            <div class="w-full max-w-[420px] mx-auto mt-16 lg:mt-0">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>