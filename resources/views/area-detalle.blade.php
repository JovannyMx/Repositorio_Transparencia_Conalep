@extends('layouts.public')

@section('title', $area['nombre'] . ' - Repositorio de Transparencia')

@section('content')

    <!-- Navegación tipo Miga de Pan (Breadcrumbs) -->
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-blue-900 transition">&larr; Volver a Áreas</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">{{ $area['nombre'] }}</span>
    </nav>

    <!-- Encabezado del Área -->
    <header class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <h1 class="text-2xl font-bold text-blue-900 mb-2">{{ $area['nombre'] }}</h1>
        <p class="text-gray-600">{{ $area['descripcion'] }}</p>
    </header>

    <!-- Controles de Filtro y Búsqueda (RF-05 / RF-07) -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Buscador por texto -->
            <div class="md:col-span-2">
                <label for="input-busqueda" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Buscar por nombre de documento
                </label>
                <input 
                    type="text" 
                    id="input-busqueda" 
                    placeholder="Escribe para filtrar (ej. Organigrama, Tabulador)..." 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition"
                >
            </div>

            <!-- Filtro por Categoría -->
            <div>
                <label for="select-categoria" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Filtrar por Categoría
                </label>
                <select 
                    id="select-categoria" 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition bg-white"
                >
                    <option value="">Todas las categorías</option>
                    <option value="Estructura Orgánica">Estructura Orgánica</option>
                    <option value="Remuneraciones">Remuneraciones</option>
                    <option value="Convocatorias">Convocatorias</option>
                </select>
            </div>

        </div>
    </section>

    <!-- Tabla de Documentos de Transparencia (RF-02, RF-03, RF-06) -->
    <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-lg font-bold text-gray-900">Documentos Publicados</h2>
            <span id="contador-resultados" class="text-xs bg-blue-50 text-blue-800 font-semibold px-3 py-1 rounded-full">
                Mostrando {{ count($documentos) }} documento(s)
            </span>
        </div>

        <!-- Tabla Responsiva -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabla-documentos">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3 px-6">Documento</th>
                        <th class="py-3 px-6">Categoría</th>
                        <th class="py-3 px-6">Fecha</th>
                        <th class="py-3 px-6">Formato / Peso</th>
                        <th class="py-3 px-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($documentos as $doc)
                        <tr 
                            class="fila-documento hover:bg-gray-50 transition"
                            data-nombre="{{ strtolower($doc['nombre']) }}"
                            data-categoria="{{ $doc['categoria'] }}"
                        >
                            <td class="py-4 px-6 font-medium text-gray-900 col-nombre">
                                {{ $doc['nombre'] }}
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $doc['categoria'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                                {{ $doc['fecha_publicacion'] }}
                            </td>
                            <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                                <span class="font-semibold text-red-600">{{ $doc['formato'] }}</span> ({{ $doc['tamaño'] }})
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a 
                                    href="{{ $doc['url'] }}" 
                                    class="inline-flex items-center gap-1 text-sm bg-blue-900 hover:bg-blue-800 text-white font-medium px-3 py-1.5 rounded transition shadow-sm"
                                >
                                    Descargar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                No se encontraron documentos registrados en esta área.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Mensaje cuando la búsqueda no arroje coincidencias -->
                    <tr id="sin-coincidencias" class="hidden">
                        <td colspan="5" class="py-8 px-6 text-center text-gray-500">
                            No se encontraron documentos que coincidan con la búsqueda o filtro seleccionado.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Script de Filtrado en Tiempo Real -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputBusqueda = document.getElementById('input-busqueda');
            const selectCategoria = document.getElementById('select-categoria');
            const filas = document.querySelectorAll('.fila-documento');
            const mensajeVacio = document.getElementById('sin-coincidencias');
            const contador = document.getElementById('contador-resultados');

            function filtrar() {
                const texto = inputBusqueda.value.toLowerCase().trim();
                const categoriaSeleccionada = selectCategoria.value;
                let visibles = 0;

                filas.forEach(fila => {
                    const nombre = fila.getAttribute('data-nombre');
                    const categoria = fila.getAttribute('data-categoria');

                    const coincideTexto = nombre.includes(texto);
                    const coincideCategoria = categoriaSeleccionada === '' || categoria === categoriaSeleccionada;

                    if (coincideTexto && coincideCategoria) {
                        fila.style.display = '';
                        visibles++;
                    } else {
                        fila.style.display = 'none';
                    }
                });

                // Mostrar u ocultar mensaje de "sin resultados"
                if (visibles === 0 && filas.length > 0) {
                    mensajeVacio.classList.remove('hidden');
                } else {
                    mensajeVacio.classList.add('hidden');
                }

                // Actualizar el contador dinámico
                contador.textContent = `Mostrando ${visibles} documento(s)`;
            }

            // Escuchar eventos en los controles
            inputBusqueda.addEventListener('input', filtrar);
            selectCategoria.addEventListener('change', filtrar);
        });
    </script>

@endsection