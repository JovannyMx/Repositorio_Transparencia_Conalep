{{-- 
 |--------------------------------------------------------------------------
 | Vista Pública: Resultados del Buscador Global
 |--------------------------------------------------------------------------
 | Muestra los documentos coincidentes con la consulta del usuario.
 | Permite la previsualización directa (modal) o la descarga del archivo.
 |
 | @author Desarrollo - Administración CONALEP Jalisco
--}}

@extends('layouts.public')

@section('title', 'Resultados de Búsqueda - Administración CONALEP Jalisco')

@section('content')

    <!-- Encabezado de Búsqueda -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-conalep-dark mb-2">Resultados de Búsqueda</h1>
        <p class="text-gray-600 text-sm">
            Mostrando coincidencias para: <span class="font-bold text-gray-900">"{{ $query }}"</span>
        </p>
    </header>

    <!-- Caja de Búsqueda Global -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <form action="{{ route('buscar.public') }}" method="GET" class="flex gap-4">
            <input 
                type="text" 
                name="q" 
                value="{{ $query }}"
                placeholder="Buscar por palabra clave, documento o área..." 
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-conalep-green focus:border-conalep-green text-sm transition text-gray-800"
            >
            <button 
                type="submit" 
                class="bg-conalep-green hover:bg-conalep-dark text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm shadow-sm flex-shrink-0"
            >
                Buscar
            </button>
        </form>
    </section>

    <!-- Lista de Resultados Coincidentes -->
    <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-base font-bold text-conalep-dark">Documentos Coincidentes</h2>
            <span class="text-xs bg-conalep-mint/20 text-conalep-dark font-semibold px-3 py-1 rounded-full border border-conalep-mint/30">
                {{ count($resultados) }} resultado(s)
            </span>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse ($resultados as $doc)
                @php
                    $docNombre = is_array($doc) ? ($doc['nombre'] ?? '') : ($doc->nombre ?? '');
                    $docArea = is_array($doc) ? ($doc['area'] ?? 'General') : ($doc->area->nombre ?? 'General');
                    $docCategoria = is_array($doc) ? ($doc['categoria'] ?? 'Sin Categoria') : ($doc->categoria ?? 'Sin Categoria');
                    $docFecha = is_array($doc) ? ($doc['fecha_publicacion'] ?? '') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : '');
                    $docFormato = is_array($doc) ? ($doc['formato'] ?? 'PDF') : ($doc->formato ?? 'PDF');
                    $docTamaño = is_array($doc) ? ($doc['tamaño'] ?? 'N/A') : ($doc->tamaño ?? 'N/A');
                    $docUrl = is_array($doc) ? ($doc['url'] ?? '#') : (asset('storage/' . $doc->archivo_path));
                @endphp
                <div class="p-6 hover:bg-conalep-light/50 transition flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold bg-gray-100 text-gray-700 px-2 py-0.5 rounded border border-gray-200">
                                {{ $docArea }}
                            </span>
                            <span class="text-xs font-semibold bg-conalep-mint/20 text-conalep-dark px-2 py-0.5 rounded">
                                {{ $docCategoria }}
                            </span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">
                            {{ $docNombre }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            Publicado el {{ $docFecha }} &bull; <span class="font-bold text-conalep-magenta">{{ $docFormato }}</span> ({{ $docTamaño }})
                        </p>
                    </div>

                    <!-- Botones de Acción: Visualizar y Descargar -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button 
                            type="button"
                            onclick="openDocumentViewer('{{ $docUrl }}', '{{ addslashes($docNombre) }}', '{{ strtolower($docFormato) }}')"
                            class="inline-flex items-center gap-1 text-xs bg-conalep-green hover:bg-conalep-dark text-white font-semibold px-3 py-2 rounded-lg transition shadow-sm"
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
                            class="inline-flex items-center gap-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-3 py-2 rounded-lg transition border border-slate-300"
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

    <!-- Modal Visor de Documentos -->
    <div id="viewerModal" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-conalep-dark text-white flex items-center justify-between border-b border-slate-700">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-conalep-green"></span>
                    <h3 id="viewerTitle" class="text-sm font-bold truncate max-w-2xl">Visualizador de Documentos</h3>
                </div>
                <button onclick="closeDocumentViewer()" class="text-slate-300 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="flex-1 bg-slate-100 p-2 overflow-hidden relative">
                <iframe id="pdfFrame" class="w-full h-full border-0 hidden rounded" src=""></iframe>
                <div id="imageViewer" class="w-full h-full hidden flex items-center justify-center overflow-auto p-4">
                    <img id="imgFrame" src="" class="max-w-full max-h-full object-contain shadow-lg rounded" alt="Documento" />
                </div>
                <div id="unsupportedViewer" class="w-full h-full hidden flex flex-col items-center justify-center text-center p-6">
                    <p class="text-slate-600 mb-4 font-medium">Este formato no admite previsualización directa en el navegador.</p>
                    <a id="downloadFallback" href="#" download class="px-4 py-2 bg-conalep-green hover:bg-conalep-dark text-white text-xs font-bold rounded-lg transition">
                        Descargar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script del Visor Modal -->
    <script>
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

            if (ext === 'pdf' || url.endsWith('.pdf')) {
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