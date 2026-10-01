@extends('layouts.public')

@section('title', 'Áreas Generadoras - Portal de Transparencia CONALEP Jalisco')

@section('content')

    <!-- Barra de Acceso de Usuarios (Login / Registro / Dashboard) -->
    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
        <span class="text-xs text-slate-500 font-medium">
            Portal Oficial de Transparencia
        </span>

        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-xs font-bold text-white bg-conalep-green hover:bg-conalep-dark rounded-lg transition shadow-sm">
                        Ir al Panel (Dashboard) &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-bold text-conalep-dark hover:text-conalep-green border border-slate-300 rounded-lg transition">
                        Iniciar Sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 text-xs font-bold text-white bg-conalep-green hover:bg-conalep-dark rounded-lg transition">
                            Registrarse
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <!-- Banner / Encabezado principal -->
    <section class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 mb-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2 h-full bg-conalep-green"></div>

        <span class="inline-block px-3 py-1 bg-conalep-light text-conalep-green text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
            Información Pública de Oficio
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            Portal de Transparencia y Acceso a la Información
        </h1>
        <p class="text-slate-600 text-sm max-w-3xl leading-relaxed">
            Consulta la información pública de oficio de cada una de las áreas generadoras del Colegio de Educación Profesional Técnica del Estado de Jalisco.
        </p>
    </section>

    <!-- Listado de Áreas Generadoras desde la BD -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-conalep-dark flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-conalep-green inline-block"></span>
                Áreas Generadoras
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @forelse ($areas as $area)
                <a 
                    href="{{ route('areas.public.show', is_array($area) ? $area['slug'] : $area->slug) }}" 
                    class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-conalep-green hover:shadow-md transition flex flex-col justify-between group"
                >
                    <div>
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-conalep-light flex-shrink-0 flex items-center justify-center text-conalep-green group-hover:bg-conalep-green group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ is_array($area) ? $area['icono'] : ($area->icono ?? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4') }}"></path>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-conalep-dark group-hover:text-conalep-green transition-colors">
                                    {{ is_array($area) ? $area['nombre'] : $area->nombre }}
                                </h3>
                                <span class="inline-block text-xs font-bold text-conalep-green bg-conalep-light px-2.5 py-0.5 rounded-md mt-1">
                                    {{ is_array($area) ? ($area['documentos_count'] ?? 0) : ($area->documentos_count ?? $area->documentos()->count()) }} documentos
                                </span>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                            {{ is_array($area) ? $area['descripcion'] : ($area->descripcion_corta ?? 'Información y normatividad correspondiente a esta área.') }}
                        </p>
                    </div>

                    <div class="flex items-center text-xs font-bold text-conalep-green group-hover:text-conalep-dark group-hover:translate-x-1 transition-all">
                        Consultar documentos <span class="ml-1 text-sm">&rarr;</span>
                    </div>
                </a>
            @empty
                <div class="col-span-2 bg-white p-8 rounded-xl border border-dashed border-slate-300 text-center text-slate-500">
                    No hay áreas generadoras registradas en el sistema por el momento.
                </div>
            @endforelse
        </div>
    </section>

@endsection