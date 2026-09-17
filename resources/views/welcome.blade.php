@extends('layouts.public')

@section('title', 'Inicio - Repositorio de Transparencia')

@section('content')

    <!-- Sección del Buscador (RF-05) -->
    <section class="mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                Buscar documentos o áreas
            </label>
            <input 
                type="text" 
                id="buscar" 
                placeholder="Ejemplo: Presupuesto, Adquisiciones, Organigrama..." 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
        </div>
    </section>

    <!-- Listado de Áreas Dinámico (RF-01) -->
    <section>
        <h2 class="text-xl font-bold text-gray-900 mb-4">Áreas Administrativas</h2>
        <p class="text-gray-600 mb-6">Selecciona un área para consultar los documentos oficiales disponibles.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($areas as $area)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-lg text-blue-900">{{ $area['nombre'] }}</h3>
                            <span class="text-xs bg-blue-100 text-blue-800 font-medium px-2.5 py-0.5 rounded-full">
                                {{ $area['documentos_count'] }} docs
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-4">{{ $area['descripcion'] }}</p>
                    </div>
                    
                    <div>
                        <a href="{{ route('areas.public.show', $area['slug']) }}" class="inline-block text-sm font-medium text-blue-600 hover:text-blue-800 font-semibold">
                             Ver documentos &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg text-center">
                    No hay áreas administrativas disponibles por el momento.
                </div>
            @endforelse
        </div>
    </section>

@endsection