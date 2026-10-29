<x-layouts.public>
    <x-slot name="header">
        <nav class="flex mb-4 text-xs font-medium text-slate-500 items-center gap-1.5">
            <a href="{{ route('publico.areas.index') }}" class="text-indigo-600 hover:underline flex items-center gap-1">
                &larr; Volver a Áreas
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-bold">{{ $area->nombre }}</span>
        </nav>

        <div class="flex items-center gap-4 mt-2">
            @if ($area->icono)
                <div class="flex-shrink-0">
                    <img src="{{ asset('storage/' . $area->icono) }}" alt="{{ $area->nombre }}" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-100">
                </div>
            @endif
            <div>
                <h1 class="font-bold text-2xl sm:text-3xl text-gray-800">{{ $area->nombre }}</h1>
                @if ($area->descripcion_corta)
                    <p class="text-sm text-gray-500 mt-1">{{ $area->descripcion_corta }}</p>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- FILTROS EN TIEMPO REAL -->
        <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="input-busqueda" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Buscar por nombre de documento
                    </label>
                    <input 
                        type="text" 
                        id="input-busqueda" 
                        placeholder="Escribe para filtrar en tiempo real..." 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition placeholder-slate-400 text-slate-800"
                    >
                </div>

                <div>
                    <label for="select-categoria" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Filtrar por Categoría
                    </label>
                    <select 
                        id="select-categoria" 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition bg-white text-slate-800"
                    >
                        <option value="">Todas las categorías</option>
                        <option value="General">General</option>
                        <option value="Estructura Orgánica">Estructura Orgánica</option>
                        <option value="Remuneraciones">Remuneraciones</option>
                        <option value="Convocatorias">Convocatorias</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- DATOS OCULTOS PARA JS (FILTRADO STRICTO EN CASCADA DE ANCESTROS Y VISIBILIDAD) -->
        <div id="datos-crudos" class="hidden">
            @foreach ($documentos ?? [] as $doc)
                @php
                    $seccionActual = $doc->seccion;
                    $esVisible = true;

                    // Evaluamos recursivamente 'activo', 'publico' y 'deleted_at' en toda la cadena de padres
                    while ($seccionActual) {
                        $estaActiva = (bool) ($seccionActual->activo ?? true);
                        $esPublica = isset($seccionActual->publico) 
                            ? (bool)$seccionActual->publico 
                            : (isset($seccionActual->is_public) ? (bool)$seccionActual->is_public : true);

                        // Si la sección o un ancestro está desactivado, no visible al público o eliminado
                        if (!$estaActiva || !$esPublica || !is_null($seccionActual->deleted_at)) {
                            $esVisible = false;
                            break;
                        }

                        // Subir al padre
                        $seccionActual = $seccionActual->parentRecursive ?? $seccionActual->parent;
                    }
                @endphp

                @if ($esVisible)
                    @php
                        $docNombre = $doc->nombre ?? $doc->titulo ?? '';
                        $docCategoria = $doc->categoria ?? 'General';
                        $docFecha = $doc->fecha_publicacion ? $doc->fecha_publicacion->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : '');
                        
                        $seccionNombre = $doc->seccion ? $doc->seccion->nombre : 'Documentos Generales';
                        
                        $media = method_exists($doc, 'getFirstMedia') ? $doc->getFirstMedia('archivo') : null;
                        $docFormato = $media ? pathinfo($media->file_name, PATHINFO_EXTENSION) : ($doc->extension ?? 'PDF');
                        $docTamaño = $media ? $media->human_readable_size : ($doc->tamano ?? 'N/A');
                        
                        $docUrl = route('documentos.public.ver', $doc);
                    @endphp
                    <div 
                        class="data-doc"
                        data-nombre="{{ strtolower($docNombre) }}"
                        data-categoria="{{ $docCategoria }}"
                        data-seccion="{{ $seccionNombre }}"
                        data-url="{{ $docUrl }}"
                        data-formato="{{ strtolower($docFormato) }}"
                        data-size="{{ $docTamaño }}"
                        data-raw-nombre="{{ $docNombre }}"
                        data-fecha="{{ $docFecha }}"
                    ></div>
                @endif
            @endforeach
        </div>

        <!-- MODO 1: VISTA EN LISTA (TABLA) -->
        <section id="contenedorLista" class="hidden bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    Documentos Disponibles
                </h2>
                <span id="contador-resultados" class="text-xs bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-full border border-indigo-100">
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-6">Documento</th>
                            <th class="py-3.5 px-6">Sección</th>
                            <th class="py-3.5 px-6">Fecha</th>
                            <th class="py-3.5 px-6">Formato / Peso</th>
                            <th class="py-3.5 px-6 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-lista" class="divide-y divide-slate-200 text-sm">
                    </tbody>
                </table>
            </div>
        </section>

        <!-- MODO 2: VISTA EN CARPETAS (GRID DE SECCIONES) -->
        <section id="contenedorCarpetas" class="hidden">
            <div id="gridCarpetasPadre" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            </div>

            <!-- CONTENIDO DE LA CARPETA SELECCIONADA -->
            <div id="detalleCarpetaHijo" class="hidden mt-6 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-4">
                    <div class="flex items-center gap-3">
                        <button 
                            type="button" 
                            onclick="cerrarDetalleCarpeta()"
                            class="p-2 bg-slate-100 hover:bg-indigo-600 hover:text-white rounded-lg transition text-slate-600 font-bold text-xs flex items-center gap-1"
                        >
                            &larr; Volver a Secciones
                        </button>
                        <h3 id="tituloCarpetaActiva" class="text-base font-bold text-slate-800">
                        </h3>
                    </div>
                    <span id="badgeConteoHijos" class="text-xs bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-full border border-indigo-100">
                    </span>
                </div>

                <div id="gridArchivosHijos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                </div>
            </div>
        </section>

    </div>

    <!-- MODAL VISOR DE DOCUMENTOS -->
    <div id="viewerModal" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="px-6 py-4 bg-slate-800 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
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
                    <a id="downloadFallback" href="#" download class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                        Descargar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $modoVistaActual = $area->modo_vista ?? 'lista';
    @endphp

    <script>
        const MODO_VISTA_ADMIN = "{{ $modoVistaActual }}";
        
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

            const inputBusqueda = document.getElementById('input-busqueda');
            const selectCategoria = document.getElementById('select-categoria');

            if (inputBusqueda && selectCategoria) {
                inputBusqueda.addEventListener('input', aplicarFiltros);
                selectCategoria.addEventListener('change', aplicarFiltros);
            }
        });

        function extraerDatosCrudos() {
            const nodos = document.querySelectorAll('.data-doc');
            documentosGlobal = [];
            nodos.forEach(nodo => {
                documentosGlobal.push({
                    nombreFiltro: nodo.getAttribute('data-nombre'),
                    categoria: nodo.getAttribute('data-categoria'),
                    seccion: nodo.getAttribute('data-seccion'),
                    url: nodo.getAttribute('data-url'),
                    formato: nodo.getAttribute('data-formato'),
                    size: nodo.getAttribute('data-size'),
                    nombre: nodo.getAttribute('data-raw-nombre'),
                    fecha: nodo.getAttribute('data-fecha')
                });
            });
        }

        function renderizarLista(docs = documentosGlobal) {
            const tbody = document.getElementById('tbody-lista');
            const contador = document.getElementById('contador-resultados');
            
            contador.textContent = `Mostrando ${docs.length} documento(s)`;
            
            if (docs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-8 px-6 text-center text-slate-500 text-sm">No se encontraron documentos disponibles.</td></tr>`;
                return;
            }

            let html = '';
            docs.forEach(doc => {
                const nombreEscapado = doc.nombre.replace(/'/g, "\\'");
                html += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-slate-800">${doc.nombre}</td>
                        <td class="py-4 px-6 text-slate-600">
                            <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded border border-slate-200">
                                ${doc.seccion}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-500 text-xs">${doc.fecha}</td>
                        <td class="py-4 px-6 text-slate-500 text-xs">
                            <span class="font-bold text-indigo-600">${doc.formato.toUpperCase()}</span> (${doc.size})
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <button onclick="openDocumentViewer('${doc.url}', '${nombreEscapado}', '${doc.formato}')" class="inline-flex items-center gap-1 text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-2 rounded-lg transition shadow-sm">
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

        function procesarCarpetas(docs = documentosGlobal) {
            gruposCarpetas = {};
            docs.forEach(doc => {
                if (!gruposCarpetas[doc.seccion]) gruposCarpetas[doc.seccion] = [];
                gruposCarpetas[doc.seccion].push(doc);
            });
            
            const gridPadre = document.getElementById('gridCarpetasPadre');
            const claves = Object.keys(gruposCarpetas);
            
            cerrarDetalleCarpeta();

            if (!claves.length) {
                gridPadre.innerHTML = `<div class="col-span-full bg-white p-8 rounded-xl border border-slate-200 text-center text-slate-500">No hay documentos disponibles en secciones activas.</div>`;
                return;
            }

            let html = '';
            claves.forEach(seccionNombre => {
                const nombreEscapado = seccionNombre.replace(/'/g, "\\'");
                html += `
                    <div onclick="abrirDetalleCarpeta('${nombreEscapado}')" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-500 transition-all cursor-pointer group relative flex flex-col justify-between">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📁</div>
                            <span class="text-xs bg-slate-100 font-bold text-slate-600 px-2.5 py-1 rounded-full border border-slate-200">${gruposCarpetas[seccionNombre].length} archivo(s)</span>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">${seccionNombre}</h4>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-600">
                            <span>Ver documentos</span><span>&rarr;</span>
                        </div>
                    </div>`;
            });
            gridPadre.innerHTML = html;
        }

        function abrirDetalleCarpeta(seccionNombre) {
            const listaHijos = gruposCarpetas[seccionNombre] || [];
            document.getElementById('tituloCarpetaActiva').innerHTML = `📁 Sección / ${seccionNombre}`;
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
                            <span class="font-bold text-indigo-600 uppercase text-[10px]">${doc.formato} (${doc.size})</span>
                            <div class="flex items-center gap-1">
                                <button onclick="openDocumentViewer('${doc.url}', '${nombreEscapado}', '${doc.formato}')" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded text-[11px] transition">Ver</button>
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
</x-layouts.public>