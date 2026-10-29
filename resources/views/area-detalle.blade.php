{{-- 
 |--------------------------------------------------------------------------
 | Vista Pública: Detalle de Área y Visor de Documentos
 |--------------------------------------------------------------------------
 | Diseño estricto basado en la configuración del administrador.
 |
 | @author Desarrollo - Administración CONALEP Jalisco
--}}

@extends('layouts.public')

@section('title', ($area['nombre'] ?? $area->nombre ?? 'Área') . ' - Administración CONALEP Jalisco')

@section('content')

    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-xs font-medium text-slate-500 items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-conalep-green transition flex items-center gap-1">
            &larr; Volver a Áreas
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-conalep-dark font-bold">{{ $area['nombre'] ?? $area->nombre ?? 'Detalle' }}</span>
    </nav>

    <!-- Encabezado del Área -->
    <header class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border-l-4 border-l-conalep-green border border-slate-200 mb-6 relative overflow-hidden">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            {{ $area['nombre'] ?? $area->nombre ?? 'Detalle de Área' }}
        </h1>
        <p class="text-slate-600 text-sm leading-relaxed max-w-4xl">
            {{ $area['descripcion'] ?? $area->descripcion_corta ?? 'Sin descripción disponible.' }}
        </p>
    </header>

    <!-- Filtro (Solo Búsqueda y Categoría) -->
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

    <!-- DATOS OCULTOS PARA PROCESAMIENTO JS (Siempre se imprimen para alimentar las carpetas si aplica) -->
    <div id="datos-crudos" class="hidden">
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
                $yearFecha = is_object($doc) && isset($doc->created_at) ? $doc->created_at->format('Y') : (substr($docFecha, -4) ?: 'General');
            @endphp
            <div 
                class="data-doc"
                data-nombre="{{ strtolower($docNombre) }}"
                data-categoria="{{ $docCategoria }}"
                data-year="{{ $yearFecha }}"
                data-url="{{ $docUrl }}"
                data-formato="{{ strtolower($docFormato) }}"
                data-size="{{ $docTamaño }}"
                data-raw-nombre="{{ $docNombre }}"
                data-fecha="{{ $docFecha }}"
            ></div>
        @empty
        @endforelse
    </div>

    <!-- =================================--------------------- -->
    <!-- MODO 1: VISTA EN LISTA TRADICIONAL (TABLA) -->
    <!-- =================================--------------------- -->
    <section id="contenedorLista" class="hidden bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-base font-bold text-conalep-dark flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-conalep-green"></span>
                Documentos Disponibles
            </h2>
            <span id="contador-resultados" class="text-xs bg-conalep-light text-conalep-dark font-bold px-3 py-1 rounded-full border border-conalep-mint/40">
                <!-- Se llena con JS -->
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
                <tbody id="tbody-lista" class="divide-y divide-slate-200 text-sm">
                    <!-- Se renderiza vía JS -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- =================================--------------------- -->
    <!-- MODO 2: VISTA EN CARPETAS (DISEÑO VISUAL DE GRID/TARJETAS) -->
    <!-- =================================--------------------- -->
    <section id="contenedorCarpetas" class="hidden">
        <!-- Tarjetas principales estilo explorador de archivos -->
        <div id="gridCarpetasPadre" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Renderizado dinámico vía JavaScript -->
        </div>

        <!-- Vista detallada del contenido de la carpeta seleccionada -->
        <div id="detalleCarpetaHijo" class="hidden mt-6 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        onclick="cerrarDetalleCarpeta()"
                        class="p-2 bg-slate-100 hover:bg-conalep-green hover:text-white rounded-lg transition text-slate-600 font-bold text-xs flex items-center gap-1"
                    >
                        &larr; Volver a Carpetas
                    </button>
                    <h3 id="tituloCarpetaActiva" class="text-base font-bold text-conalep-dark">
                        <!-- Nombre carpeta activa -->
                    </h3>
                </div>
                <span id="badgeConteoHijos" class="text-xs bg-conalep-light text-conalep-dark font-bold px-3 py-1 rounded-full border border-conalep-mint/40">
                    <!-- Conteo de archivos -->
                </span>
            </div>

            <!-- Grid de archivos hijos -->
            <div id="gridArchivosHijos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Archivos de la carpeta -->
            </div>
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

    <!-- PREPARAMOS LA VARIABLE EN PHP FUERA DEL SCRIPT PARA EVITAR ERRORES DEL EDITOR -->
    @php
        $modoVistaActual = 'lista'; // Por defecto
        if(isset($area)) {
            $modoVistaActual = is_array($area) ? ($area['modo_vista'] ?? 'lista') : ($area->modo_vista ?? 'lista');
        } elseif(isset($seccion)) {
            $modoVistaActual = is_array($seccion) ? ($seccion['modo_vista'] ?? 'lista') : ($seccion->modo_vista ?? 'lista');
        }
    @endphp

    <!-- SCRIPT DE LÓGICA -->
    <script>
        // Leemos la variable que ya procesó PHP arriba
        const MODO_VISTA_ADMIN = "{{ $modoVistaActual }}";
        console.log("Modo de vista cargado desde la BD:", MODO_VISTA_ADMIN);
        
        let documentosGlobal = [];
        let gruposCarpetas = {};

        document.addEventListener('DOMContentLoaded', function () {
            extraerDatosCrudos();

            if (MODO_VISTA_ADMIN === 'carpetas') {
                document.getElementById('contenedorCarpetas').classList.remove('hidden');
                procesarCarpetas();
            } else {
                document.getElementById('contenedorLista').classList.remove('hidden');
                renderizarLista();
            }

            // Filtros en tiempo real
            const inputBusqueda = document.getElementById('input-busqueda');
            const selectCategoria = document.getElementById('select-categoria');

            if (inputBusqueda && selectCategoria) {
                inputBusqueda.addEventListener('input', aplicarFiltros);
                selectCategoria.addEventListener('change', aplicarFiltros);
            }
        });

        function extraerDatosCrudos() {
            const nodos = document.querySelectorAll('.data-doc');
            nodos.forEach(nodo => {
                documentosGlobal.push({
                    nombreFiltro: nodo.getAttribute('data-nombre'),
                    categoria: nodo.getAttribute('data-categoria'),
                    year: nodo.getAttribute('data-year'),
                    url: nodo.getAttribute('data-url'),
                    formato: nodo.getAttribute('data-formato'),
                    size: nodo.getAttribute('data-size'),
                    nombre: nodo.getAttribute('data-raw-nombre'),
                    fecha: nodo.getAttribute('data-fecha')
                });
            });
        }

        /* ---------------- LÓGICA VISTA LISTA ---------------- */
        function renderizarLista(docs = documentosGlobal) {
            const tbody = document.getElementById('tbody-lista');
            const contador = document.getElementById('contador-resultados');
            
            contador.textContent = `Mostrando ${docs.length} documento(s)`;
            
            if (docs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-8 px-6 text-center text-slate-500 text-sm">No se encontraron documentos.</td></tr>`;
                return;
            }

            let html = '';
            docs.forEach(doc => {
                const nombreEscapado = doc.nombre.replace(/'/g, "\\'");
                html += `
                    <tr class="hover:bg-conalep-light/40 transition-colors">
                        <td class="py-4 px-6 font-semibold text-slate-800">${doc.nombre}</td>
                        <td class="py-4 px-6 text-slate-600">
                            <span class="bg-slate-100 text-conalep-dark text-xs font-semibold px-2.5 py-1 rounded border border-slate-200">
                                ${doc.categoria}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-500 text-xs">${doc.fecha}</td>
                        <td class="py-4 px-6 text-slate-500 text-xs">
                            <span class="font-bold text-conalep-magenta">${doc.formato.toUpperCase()}</span> (${doc.size})
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <button onclick="openDocumentViewer('${doc.url}', '${nombreEscapado}', '${doc.formato}')" class="inline-flex items-center gap-1 text-xs bg-conalep-green hover:bg-conalep-dark text-white font-bold px-3 py-2 rounded-lg transition shadow-sm">
                                    Visualizar
                                </button>
                                <a href="${doc.url}" target="_blank" download class="inline-flex items-center gap-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-2 rounded-lg transition border border-slate-300">
                                    Descargar
                                </a>
                            </div>
                        </td>
                    </tr>`;
            });
            tbody.innerHTML = html;
        }

        /* ---------------- LÓGICA VISTA CARPETAS ---------------- */
        function procesarCarpetas(docs = documentosGlobal) {
            gruposCarpetas = {};
            docs.forEach(doc => {
                if (!gruposCarpetas[doc.year]) gruposCarpetas[doc.year] = [];
                gruposCarpetas[doc.year].push(doc);
            });
            
            const gridPadre = document.getElementById('gridCarpetasPadre');
            const claves = Object.keys(gruposCarpetas).sort().reverse();
            
            // Si hay filtros activos, cerrar la vista de detalle
            cerrarDetalleCarpeta();

            if (!claves.length) {
                gridPadre.innerHTML = `<div class="col-span-full bg-white p-8 rounded-xl border border-slate-200 text-center text-slate-500">No hay documentos con estos filtros.</div>`;
                return;
            }

            let html = '';
            claves.forEach(year => {
                html += `
                    <div onclick="abrirDetalleCarpeta('${year}')" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-conalep-green transition-all cursor-pointer group relative flex flex-col justify-between">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-conalep-light rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📁</div>
                            <span class="text-xs bg-slate-100 font-bold text-slate-600 px-2.5 py-1 rounded-full border border-slate-200">${gruposCarpetas[year].length} archivo(s)</span>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg group-hover:text-conalep-green transition-colors">Carpeta ${year}</h4>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-conalep-green">
                            <span>Ver archivos</span><span>&rarr;</span>
                        </div>
                    </div>`;
            });
            gridPadre.innerHTML = html;
        }

        function abrirDetalleCarpeta(year) {
            const listaHijos = gruposCarpetas[year] || [];
            document.getElementById('tituloCarpetaActiva').innerHTML = `📁 Carpeta / Periodo ${year}`;
            document.getElementById('badgeConteoHijos').textContent = `${listaHijos.length} documento(s)`;

            let html = '';
            listaHijos.forEach(doc => {
                const nombreEscapado = doc.nombre.replace(/'/g, "\\'");
                html += `
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-between hover:bg-white hover:shadow-sm transition">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="text-2xl">📄</span>
                            <div class="overflow-hidden">
                                <h5 class="font-bold text-slate-800 text-xs truncate" title="${doc.nombre}">${doc.nombre}</h5>
                                <span class="inline-block mt-1 text-[10px] bg-slate-200 text-slate-700 px-2 py-0.5 rounded font-semibold">${doc.categoria}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200 text-xs">
                            <span class="font-bold text-conalep-magenta uppercase text-[10px]">${doc.formato} (${doc.size})</span>
                            <div class="flex items-center gap-1">
                                <button onclick="openDocumentViewer('${doc.url}', '${nombreEscapado}', '${doc.formato}')" class="px-2.5 py-1 bg-conalep-green hover:bg-conalep-dark text-white font-bold rounded text-[11px] transition">Ver</button>
                                <a href="${doc.url}" target="_blank" download class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded text-[11px] transition">&darr;</a>
                            </div>
                        </div>
                    </div>`;
            });
            document.getElementById('gridArchivosHijos').innerHTML = html;
            document.getElementById('gridCarpetasPadre').classList.add('hidden');
            document.getElementById('detalleCarpetaHijo').classList.remove('hidden');
        }

        function cerrarDetalleCarpeta() {
            document.getElementById('gridCarpetasPadre').classList.remove('hidden');
            document.getElementById('detalleCarpetaHijo').classList.add('hidden');
        }

        /* ---------------- FILTROS COMPARTIDOS ---------------- */
        function aplicarFiltros() {
            const texto = document.getElementById('input-busqueda').value.toLowerCase().trim();
            const categoria = document.getElementById('select-categoria').value;

            const docsFiltrados = documentosGlobal.filter(doc => {
                const matchTexto = doc.nombreFiltro.includes(texto);
                const matchCat = categoria === '' || doc.categoria === categoria;
                return matchTexto && matchCat;
            });

            if (MODO_VISTA_ADMIN === 'carpetas') {
                procesarCarpetas(docsFiltrados);
            } else {
                renderizarLista(docsFiltrados);
            }
        }

        /* ---------------- VISUALIZADOR ---------------- */
        function openDocumentViewer(url, title, extension) {
            const modal = document.getElementById('viewerModal');
            document.getElementById('viewerTitle').textContent = title;
            const pdfFrame = document.getElementById('pdfFrame');
            const imageViewer = document.getElementById('imageViewer');
            const imgFrame = document.getElementById('imgFrame');
            const unsupportedViewer = document.getElementById('unsupportedViewer');
            const downloadFallback = document.getElementById('downloadFallback');

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
            document.getElementById('viewerModal').classList.add('hidden');
            document.getElementById('pdfFrame').src = '';
            document.getElementById('imgFrame').src = '';
        }
    </script>
@endsection