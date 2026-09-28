@extends('layouts.public')

@section('title', $area['nombre'] . ' - CONALEP Jalisco')

@section('content')

    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-xs font-medium text-slate-500 items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-conalep-green transition flex items-center gap-1">
            &larr; Volver a Áreas
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-conalep-dark font-bold">{{ $area['nombre'] }}</span>
    </nav>

    <!-- Encabezado del Área -->
    <header class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border-l-4 border-l-conalep-green border border-slate-200 mb-6 relative overflow-hidden">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            {{ $area['nombre'] }}
        </h1>
        <p class="text-slate-600 text-sm leading-relaxed max-w-4xl">
            {{ $area['descripcion'] }}
        </p>
    </header>

    <!-- Filtro y Buscador JS -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div class="md:col-span-2">
                <label for="input-busqueda" class="block text-xs font-bold text-conalep-dark uppercase tracking-wider mb-2">
                    Buscar por nombre de documento
                </label>
                <input 
                    type="text" 
                    id="input-busqueda" 
                    placeholder="Escribe para filtrar en tiempo real..." 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-conalep-green focus:border-conalep-green text-sm transition placeholder-slate-400 text-slate-800"
                >
            </div>

            <div>
                <label for="select-categoria" class="block text-xs font-bold text-conalep-dark uppercase tracking-wider mb-2">
                    Filtrar por Categoría
                </label>
                <select 
                    id="select-categoria" 
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-conalep-green focus:border-conalep-green text-sm transition bg-white text-slate-800"
                >
                    <option value="">Todas las categorías</option>
                    <option value="Estructura Orgánica">Estructura Orgánica</option>
                    <option value="Remuneraciones">Remuneraciones</option>
                    <option value="Convocatorias">Convocatorias</option>
                </select>
            </div>

        </div>
    </section>

    <!-- Tabla de Documentos -->
    <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-base font-bold text-conalep-dark flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-conalep-green"></span>
                Documentos Disponibles
            </h2>
            <span id="contador-resultados" class="text-xs bg-conalep-light text-conalep-dark font-bold px-3 py-1 rounded-full border border-conalep-mint/40">
                Mostrando {{ count($documentos ?? []) }} documento(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabla-documentos">
                <thead>
                    <tr class="bg-conalep-light text-conalep-dark text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-6">Documento</th>
                        <th class="py-3.5 px-6">Categoría</th>
                        <th class="py-3.5 px-6">Fecha</th>
                        <th class="py-3.5 px-6">Formato / Peso</th>
                        <th class="py-3.5 px-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse ($documentos ?? [] as $doc)
                        <tr 
                            class="fila-documento hover:bg-conalep-light/40 transition-colors"
                            data-nombre="{{ strtolower($doc['nombre']) }}"
                            data-categoria="{{ $doc['categoria'] }}"
                        >
                            <td class="py-4 px-6 font-semibold text-slate-800 col-nombre">
                                {{ $doc['nombre'] }}
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="bg-slate-100 text-conalep-dark text-xs font-semibold px-2.5 py-1 rounded border border-slate-200">
                                    {{ $doc['categoria'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap text-xs">
                                {{ $doc['fecha_publicacion'] }}
                            </td>
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap text-xs">
                                <span class="font-bold text-conalep-magenta">{{ $doc['formato'] }}</span> ({{$doc['tamaño'] }})
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a 
                                    href="{{ $doc['url'] }}" 
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-xs bg-conalep-green hover:bg-conalep-dark text-white font-bold px-4 py-2 rounded-lg transition shadow-sm"
                                >
                                    Descargar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-500 text-sm">
                                No se encontraron documentos registrados en esta área.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="sin-coincidencias" class="hidden">
                        <td colspan="5" class="py-8 px-6 text-center text-slate-500 text-sm">
                            No se encontraron documentos que coincidan con la búsqueda o filtro seleccionado.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Script de Búsqueda y Filtrado en Tiempo Real -->
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

                if (visibles === 0 && filas.length > 0) {
                    mensajeVacio.classList.remove('hidden');
                } else {
                    mensajeVacio.classList.add('hidden');
                }

                contador.textContent = `Mostrando ${visibles} documento(s)`;
            }

            inputBusqueda.addEventListener('input', filtrar);
            selectCategoria.addEventListener('change', filtrar);
        });
    </script>

@endsection