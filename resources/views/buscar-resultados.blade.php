@extends('layouts.public')

@section('title', 'Resultados de Búsqueda - CONALEP Jalisco')

@section('content')

    <header class="mb-8">
        <h1 class="text-2xl font-bold text-conalep-dark mb-2">Resultados de Búsqueda</h1>
        <p class="text-gray-600 text-sm">
            Mostrando coincidencias para: <span class="font-bold text-gray-900">"{{ $query }}"</span>
        </p>
    </header>

    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <form action="{{ route('buscar.public') }}" method="GET" class="flex gap-4">
            <input 
                type="text" 
                name="q" 
                value="{{ $query }}"
                placeholder="Buscar por palabra clave, documento o área..." 
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-conalep-green focus:border-conalep-green text-sm transition"
            >
            <button 
                type="submit" 
                class="bg-conalep-green hover:bg-conalep-dark text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm shadow-sm"
            >
                Buscar
            </button>
        </form>
    </section>

    <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-base font-bold text-conalep-dark">Documentos Coincidentes</h2>
            <span class="text-xs bg-conalep-mint/20 text-conalep-dark font-semibold px-3 py-1 rounded-full">
                {{ count($resultados) }} resultado(s)
            </span>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse ($resultados as $doc)
                <div class="p-6 hover:bg-conalep-light/50 transition flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                                {{ $doc['area'] }}
                            </span>
                            <span class="text-xs font-semibold bg-conalep-mint/20 text-conalep-dark px-2 py-0.5 rounded">
                                {{ $doc['categoria'] }}
                            </span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">
                            {{ $doc['nombre'] }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            Publicado el {{ $doc['fecha_publicacion'] }} &bull; <span class="font-bold text-conalep-magenta">{{ $doc['formato'] }}</span> ({{ $doc['tamaño'] }})
                        </p>
                    </div>

                    <div>
                        <a 
                            href="{{ $doc['url'] }}" 
                            class="inline-flex items-center gap-1 text-xs bg-conalep-green hover:bg-conalep-dark text-white font-semibold px-4 py-2 rounded-lg transition shadow-sm"
                        >
                            Descargar
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    No se encontraron documentos que coincidan con la búsqueda.
                </div>
            @endforelse
        </div>
    </section>

@endsection