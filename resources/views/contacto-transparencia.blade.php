{{-- 
 |--------------------------------------------------------------------------
 | Vista Pública: Contacto y Unidad de Transparencia
 |--------------------------------------------------------------------------
 | Muestra la información de contacto, atención a solicitudes e informes
 | oficiales para Administración CONALEP Jalisco.
 |
 | @author Desarrollo - Administración CONALEP Jalisco
--}}

@extends('layouts.public')

@section('title', 'Contacto y Transparencia - Administración CONALEP Jalisco')

@section('content')

    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-xs font-medium text-slate-500 items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-conalep-green transition flex items-center gap-1">
            &larr; Volver al Inicio
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-conalep-dark font-bold">Unidad de Transparencia</span>
    </nav>

    <!-- Encabezado -->
    <header class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border-l-4 border-l-conalep-green border border-slate-200 mb-8 relative overflow-hidden">
        <span class="inline-block px-3 py-1 bg-conalep-light text-conalep-green text-xs font-bold rounded-full mb-2 uppercase tracking-wider">
            Atención Ciudadana
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            Unidad de Transparencia
        </h1>
        <p class="text-slate-600 text-sm leading-relaxed max-w-3xl">
            Información de contacto y asesoría para la recepción de solicitudes de acceso a la información pública de la Administración CONALEP Jalisco.
        </p>
    </header>

    <!-- Tarjetas Informativas en Lista Horizontal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- Tarjeta: Datos de Contacto -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-conalep-light text-conalep-green flex items-center justify-center font-bold">
                        📍
                    </div>
                    <div>
                        <h3 class="font-bold text-conalep-dark text-base">Ubicación y Atención Directa</h3>
                        <p class="text-xs text-slate-500">Dirección General CONALEP Jalisco</p>
                    </div>
                </div>

                <ul class="space-y-3 text-xs text-slate-600 border-t border-slate-100 pt-4">
                    <li class="flex items-start gap-2">
                        <strong class="text-conalep-dark min-w-[90px]">Titular:</strong>
                        <span>Responsable de la Unidad de Transparencia</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <strong class="text-conalep-dark min-w-[90px]">Dirección:</strong>
                        <span>Oficinas Centrales de Administración CONALEP Jalisco</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <strong class="text-conalep-dark min-w-[90px]">Horario:</strong>
                        <span>Lunes a Viernes de 9:00 hrs. a 15:00 hrs.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tarjeta: Solicitudes de Información -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-conalep-light text-conalep-green flex items-center justify-center font-bold">
                        📄
                    </div>
                    <div>
                        <h3 class="font-bold text-conalep-dark text-base">Solicitudes en Línea</h3>
                        <p class="text-xs text-slate-500">Plataforma Nacional de Transparencia</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 border-t border-slate-100 pt-4 leading-relaxed mb-4">
                    Si requiere solicitar información pública no disponible en este repositorio, puede realizar su solicitud formal a través de los canales oficiales habilitados.
                </p>
            </div>

            <a href="https://www.plataformadetransparencia.org.mx" target="_blank" rel="noopener noreferrer" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-conalep-green hover:bg-conalep-dark text-white text-xs font-bold rounded-lg transition shadow-sm w-full">
                Ir a la Plataforma Nacional de Transparencia &rarr;
            </a>
        </div>

    </div>

@endsection