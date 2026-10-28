{{-- 
 |--------------------------------------------------------------------------
 | Vista Pública: Detalle de Área y Visor de Documentos
 |--------------------------------------------------------------------------
 | Layout tradicional en formato de Lista (Tabla) con buscador en tiempo real.
 |
 | @author Desarrollo - Administración CONALEP Jalisco
--}}

@extends('layouts.public')

@section('title', ($area['nombre'] ?? $area->nombre) . ' - Administración CONALEP Jalisco')

@section('content')

    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-xs font-medium text-slate-500 items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-conalep-green transition flex items-center gap-1">
            &larr; Volver a Áreas
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-conalep-dark font-bold">{{ $area['nombre'] ?? $area->nombre }}</span>
    </nav>

    <!-- Encabezado del Área -->
    <header class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border-l-4 border-l-conalep-green border border-slate-200 mb-6 relative overflow-hidden">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            {{ $area['nombre'] ?? $area->nombre }}
        </h1>
        <p class="text-slate-600 text-sm leading-relaxed max-w-4xl">
            {{ $area['descripcion'] ?? $area->descripcion_corta ?? 'Sin descripción disponible.' }}
        </p>
    </header>

    <!-- Filtros de Búsqueda -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            
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

    <section id="contenedorLista" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
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
                        @php
                            $docId = is_array($doc) ? ($doc['id'] ?? null) : ($doc->id ?? null);
                            $docNombre = is_array($doc) ? ($doc['nombre'] ?? $doc['titulo'] ?? '') : ($doc->nombre ?? $doc->titulo ?? '');
                            $docCategoria = is_array($doc) ? ($doc['categoria'] ?? 'General') : ($doc->categoria ?? 'General');
                            $docFecha = is_array($doc) ? ($doc['fecha_publicacion'] ?? '') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : '');
                            
                            $media = (is_object($doc) && method_exists($doc, 'getFirstMedia')) ? $doc->getFirstMedia('archivo') : null;
                            $docFormato = $media ? pathinfo($media->file_name, PATHINFO_EXTENSION) : (is_array($doc) ? ($doc['formato'] ?? 'PDF') : ($doc->formato ?? 'PDF'));
                            $docTamaño = $media ? $media->human_readable_size : (is_array($doc) ? ($doc['tamaño'] ?? 'N/A') : ($doc->tamaño ?? 'N/A'));
                            $docUrl = $docId ? route('documentos.public.ver', $docId) : '#';
                        @endphp
                        <tr 
                            class="fila-documento hover:bg-conalep-light/40 transition-colors"
                            data-nombre="{{ strtolower($docNombre) }}"
                            data-categoria="{{ $docCategoria }}"
                        >
                            <td class="py-4 px-6 font-semibold text-slate-800 col-nombre">
                                {{ $docNombre }}
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="bg-slate-100 text-conalep-dark text-xs font-semibold px-2.5 py-1 rounded border border-slate-200">
                                    {{ $docCategoria }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap text-xs">
                                {{ $docFecha }}
                            </td>
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap text-xs">
                                <span class="font-bold text-conalep-magenta">{{ strtoupper($docFormato) }}</span> ({{ $docTamaño }})
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <button 
                                        type="button"
                                        onclick="openDocumentViewer('{{ $docUrl }}', '{{ addslashes($docNombre) }}', '{{ strtolower($docFormato) }}')"
                                        class="inline-flex items-center gap-1 text-xs bg-conalep-green hover:bg-conalep-dark text-white font-bold px-3 py-2 rounded-lg transition shadow-sm"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Visualizar
                                    </button>

                                    <a 
                                        href="{{ $docUrl }}" 
                                        target="_blank"
                                        download
                                        class="inline-flex items-center gap-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-2 rounded-lg transition border border-slate-300"
                                    >
                                        Descargar
                                    </a>
                                </div>
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

    <!-- Modal Visor de Documentos -->
    <div id="viewerModal" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="px-6 py-4 bg-conalep-dark text-white flex items-center justify-between border-b border-slate-700">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-conalep-green"></span>
                    <h3 id="viewerTitle" class="text-sm font-bold truncate max-w-2xl">Visualizador de Documentos</h3>
                </div>
                <button onclick="closeDocumentViewer()" class="text-slate-300 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
            </div>
            
            <div class="flex-1 bg-slate-100 p-2 overflow-hidden relative">
                <iframe id="pdfFrame" class="w-full h-full border-0 hidden rounded" src=""></iframe>
                <div id="imageViewer" class="w-full h-full hidden flex items-center justify-center overflow-auto p-4">
                    <img id="imgFrame" src="" class="max-w-full max-h-full object-contain shadow-lg rounded" alt="Documento" />
                </div>
                <div id="unsupportedViewer" class="w-full h-full hidden flex flex-col items-center justify-center text-center p-6">
                    <p class="text-slate-600 mb-4 font-medium">Este formato no admite previsualización en vivo.</p>
                    <a id="downloadFallback" href="#" download class="px-4 py-2 bg-conalep-green hover:bg-conalep-dark text-white text-xs font-bold rounded-lg transition">
                        Descargar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE LÓGICA DE FILTRADO Y VISOR -->
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

            if (inputBusqueda && selectCategoria) {
                inputBusqueda.addEventListener('input', filtrar);
                selectCategoria.addEventListener('change', filtrar);
            }
        });

        function openDocumentViewer(url, title, extension) {
            const modal = document.getElementById('viewerModal');
            const titleElem = document.getElementById('viewerTitle');
            const pdfFrame = document.getElementById('pdfFrame');
            const imageViewer = document.getElementById('imageViewer');
            const imgFrame = document.getElementById('imgFrame');
            const unsupportedViewer = document.getElementById('unsupportedViewer');
            const downloadFallback = document.getElementById('downloadFallback');

            titleElem.textContent = title;

            pdfFrame.classList.add('hidden');
            imageViewer.classList.add('hidden');
            unsupportedViewer.classList.add('hidden');

            const ext = extension.toLowerCase();

            if (ext === 'pdf' || url.includes('/documentos/') || url.endsWith('.pdf')) {
                pdfFrame.src = url;
                pdfFrame.classList.remove('hidden');
            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
                imgFrame.src = url;
                imageViewer.classList.remove('hidden');
            } else {
                downloadFallback.href = url;
                unsupportedViewer.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeDocumentViewer() {
            const modal = document.getElementById('viewerModal');
            const pdfFrame = document.getElementById('pdfFrame');
            const imgFrame = document.getElementById('imgFrame');

            modal.classList.add('hidden');
            pdfFrame.src = '';
            imgFrame.src = '';
        }
    </script>

@endsection