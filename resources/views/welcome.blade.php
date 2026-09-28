@extends('layouts.public')

@section('title', 'Áreas Generadoras - Portal de Transparencia CONALEP Jalisco')

@section('content')

    <!-- Banner / Encabezado principal -->
    <section class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 mb-8 relative overflow-hidden">
        <!-- Detalle decorativo lateral con color institucional -->
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

    <!-- Listado de Áreas Generadoras -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-conalep-dark flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-conalep-green inline-block"></span>
                Áreas Generadoras
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach ($areas as $area)
                <a 
                    href="{{ route('areas.public.show', $area['slug']) }}" 
                    class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-conalep-green hover:shadow-md transition flex flex-col justify-between group"
                >
                    <div>
                        <div class="flex items-start gap-4 mb-4">
                            <!-- Contenedor del Icono -->
                            <div class="w-12 h-12 rounded-xl bg-conalep-light flex-shrink-0 flex items-center justify-center text-conalep-green group-hover:bg-conalep-green group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area['icono'] }}"></path>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-conalep-dark group-hover:text-conalep-green transition-colors">
                                    {{ $area['nombre'] }}
                                </h3>
                                <!-- Badge de Documentos con Colores Institucionales -->
                                <span class="inline-inline-block text-xs font-bold text-conalep-green bg-conalep-light px-2.5 py-0.5 rounded-md mt-1">
                                    {{ $area['documentos_count'] }} documentos disponibles
                                </span>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                            {{ $area['descripcion'] }}
                        </p>
                    </div>

                    <!-- Enlace / Acción con color institucional destacado -->
                    <div class="flex items-center text-xs font-bold text-conalep-green group-hover:text-conalep-dark group-hover:translate-x-1 transition-all">
                        Consultar documentos <span class="ml-1 text-sm">&rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

@endsection