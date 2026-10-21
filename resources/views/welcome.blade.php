{{-- 
 |--------------------------------------------------------------------------
 | Vista Pública: Portada Principal (Home)
 |--------------------------------------------------------------------------
 | Muestra las Áreas Generadoras distribuidas en una sola fila horizontal
 | donde todas las tarjetas son visibles simultáneamente sin scrollbar.
 |
 | @author Desarrollo - Administración CONALEP Jalisco
--}}

@extends('layouts.public')

@section('title', 'Áreas Generadoras - Administración CONALEP Jalisco')

@section('content')
    <!-- Banner / Encabezado principal -->
    <section class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 mb-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2 h-full bg-conalep-green"></div>

        <span class="inline-block px-3 py-1 bg-conalep-light text-conalep-green text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
            Información Institucional
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            Administración CONALEP Jalisco
        </h1>
        <p class="text-slate-600 text-sm max-w-3xl leading-relaxed">
            Consulta los documentos, normatividad e información oficial de cada una de las áreas generadoras del Colegio de Educación Profesional Técnica del Estado de Jalisco.
        </p>
    </section>

    <!-- Listado de Áreas Generadoras: Todas Visibles de Izquierda a Derecha -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-conalep-dark flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-conalep-green inline-block"></span>
                Áreas Generadoras
            </h2>
        </div>

        <!-- Contenedor adaptable que distribuye todas las tarjetas a lo ancho sin scroll -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($areas as $area)
                <a 
                    href="{{ route('areas.public.show', is_array($area) ? $area['slug'] : $area->slug) }}" 
                    class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 border-t-4 border-t-conalep-green hover:border-conalep-green hover:shadow-md transition flex flex-col justify-between group h-full"
                >
                    <div>
                        <!-- Ícono y Nombre del Área -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-conalep-light flex-shrink-0 flex items-center justify-center text-conalep-green group-hover:bg-conalep-green group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ is_array($area) ? $area['icono'] : ($area->icono ?? 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4') }}"></path>
                                </svg>
                            </div>

                            <h3 class="text-sm font-bold text-conalep-dark group-hover:text-conalep-green transition-colors leading-snug line-clamp-2">
                                {{ is_array($area) ? $area['nombre'] : $area->nombre }}
                            </h3>
                        </div>

                        <!-- Número de Documentos -->
                        <div class="mb-3">
                            <span class="inline-block text-[11px] font-bold text-conalep-green bg-conalep-light px-2 py-0.5 rounded">
                                {{ is_array($area) ? ($area['documentos_count'] ?? 0) : ($area->documentos_count ?? $area->documentos()->count()) }} documentos
                            </span>
                        </div>

                        <!-- Descripción Breve -->
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed line-clamp-3">
                            {{ is_array($area) ? $area['descripcion'] : ($area->descripcion_corta ?? 'Información y normatividad correspondiente a esta área.') }}
                        </p>
                    </div>

                    <!-- Pie de la Tarjeta -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-conalep-green group-hover:text-conalep-dark">
                        <span>Consultar</span>
                        <span class="text-sm group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white p-8 rounded-xl border border-dashed border-slate-300 text-center text-slate-500">
                    No hay áreas generadoras registradas en el sistema por el momento.
                </div>
            @endforelse
        </div>
    </section>

@endsection