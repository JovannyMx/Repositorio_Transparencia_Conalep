<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            
            {{-- Enlace de retroceso y Título --}}
            <div>
                <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-conalep-primary transition-colors mb-1.5 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a las áreas
                </a>
                <h2 class="font-garet font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Documentos de: <span class="text-conalep-primary">{{ $area->nombre }}</span>
                </h2>
            </div>
            
            {{-- Botón de Acción Principal --}}
            <a href="{{ route('admin.areas.documentos.create', $area) }}"
               class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Cargar documento
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerta de Éxito Estilizada --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-sm animate-fade-in-down" role="alert">
                    <div class="p-1.5 bg-emerald-100 rounded-full shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Formulario de Filtros Actualizado con selector de Estado --}}
            <form method="GET" action="{{ route('admin.areas.documentos.index', $area) }}" class="bg-white p-5 rounded-2xl shadow-xl shadow-conalep-primary/5 mb-6 flex flex-wrap gap-4 items-end border border-slate-100">
                
                {{-- Selector de Estado (Activos / Papelera) --}}
                <div class="w-full sm:w-44">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Vista</label>
                    <select name="estado" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-conalep-primary focus:ring-conalep-primary text-sm font-semibold text-slate-700">
                        <option value="activos" {{ request('estado', 'activos') == 'activos' ? 'selected' : '' }}>📁 Activos</option>
                        <option value="eliminados" {{ request('estado') == 'eliminados' ? 'selected' : '' }}>🗑️ Papelera</option>
                    </select>
                </div>

                {{-- Buscar por nombre --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Buscar documento</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del documento..."
                               class="pl-9 w-full rounded-xl border-slate-200 shadow-sm focus:border-conalep-primary focus:ring-conalep-primary text-sm">
                    </div>
                </div>

                {{-- Filtro por Sección (Con Subsecciones Jerárquicas) --}}
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sección</label>
                    <select name="seccion_id" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-conalep-primary focus:ring-conalep-primary text-sm">
                        <option value="">Todas las secciones</option>
                        @php
                            $renderSecciones = function ($secciones, $nivel = 0) use (&$renderSecciones) {
                                foreach ($secciones as $seccion) {
                                    $prefijo = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $nivel) . ($nivel > 0 ? '└─ ' : '');
                                    $selected = request('seccion_id') == $seccion->id ? 'selected' : '';
                                    echo '<option value="' . $seccion->id . '" ' . $selected . '>' . $prefijo . e($seccion->nombre) . '</option>';
                                    if ($seccion->childrenRecursive && $seccion->childrenRecursive->isNotEmpty()) {
                                        $renderSecciones($seccion->childrenRecursive, $nivel + 1);
                                    }
                                }
                            };
                        @endphp
                        {!! $renderSecciones($secciones ?? []) !!}
                    </select>
                </div>

                {{-- Filtro por Tipo (Extensión) --}}
                <div class="w-full sm:w-32">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tipo</label>
                    <select name="extension" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-conalep-primary focus:ring-conalep-primary text-sm">
                        <option value="">Todos</option>
                        <option value="pdf" {{ request('extension') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="doc" {{ request('extension') == 'doc' ? 'selected' : '' }}>DOC</option>
                        <option value="docx" {{ request('extension') == 'docx' ? 'selected' : '' }}>DOCX</option>
                        <option value="xls" {{ request('extension') == 'xls' ? 'selected' : '' }}>XLS</option>
                        <option value="xlsx" {{ request('extension') == 'xlsx' ? 'selected' : '' }}>XLSX</option>
                    </select>
                </div>

                {{-- Filtro por Vencimiento --}}
                <div class="w-full sm:w-40">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Vencimiento</label>
                    <select name="vencimiento" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-conalep-primary focus:ring-conalep-primary text-sm">
                        <option value="">Todos</option>
                        <option value="con_fecha" {{ request('vencimiento') == 'con_fecha' ? 'selected' : '' }}>Con vencimiento</option>
                        <option value="sin_fecha" {{ request('vencimiento') == 'sin_fecha' ? 'selected' : '' }}>Sin vencimiento</option>
                    </select>
                </div>

                {{-- Botones de acción --}}
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.areas.documentos.index', $area) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>

            {{-- Tarjeta de Tabla --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl border-t-4 {{ request('estado') == 'eliminados' ? 'border-amber-500' : 'border-conalep-primary' }} overflow-hidden">
                
                @if(request('estado') == 'eliminados')
                    <div class="bg-amber-50 border-b border-amber-100 px-6 py-3 text-amber-800 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Estás viendo los elementos en la papelera. Puedes restaurarlos haciendo clic en el botón de recuperación.
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20">Orden</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sección</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    {{ request('estado') == 'eliminados' ? 'Eliminado el' : 'Fecha' }}
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Vencimiento</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Liga</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="documentos-lista" class="divide-y divide-slate-100 bg-white">
                            
                            @forelse ($documentos as $documento)
                                <tr data-id="{{ $documento->id }}" class="hover:bg-slate-50/80 transition-colors group {{ request('estado') == 'eliminados' ? 'bg-amber-50/30' : '' }}">
                                    
                                    <!-- Controles Visuales de Ordenamiento -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $documento->orden }}
                                        </span>
                                    </td>

                                    <!-- Nombre -->
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                        {{ $documento->nombre }}
                                    </td>
                                    
                                    <!-- Sección (Badge) -->
                                    <td class="px-6 py-4 text-sm">
                                        @if($documento->seccion)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $documento->seccion->nombre }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-xs">— (suelto)</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Tipo de Archivo (Badge Institucional) -->
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[11px] font-bold uppercase bg-conalep-primary/10 text-conalep-primary border border-conalep-primary/20">
                                            {{ $documento->extension }}
                                        </span>
                                    </td>
                                    
                                    <!-- Fecha (Publicación o Eliminación) -->
                                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                        {{ request('estado') == 'eliminados' ? $documento->deleted_at?->format('d/m/Y H:i') : $documento->fecha_publicacion?->format('d/m/Y') }}
                                    </td>
                                    
                                    <!-- Fecha de vencimiento -->
                                    <td class="px-6 py-4 text-sm">
                                        @if($documento->fecha_vencimiento)
                                            <span class="text-slate-600 font-medium">
                                                {{ $documento->fecha_vencimiento->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Sin fecha</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Liga del Archivo -->
                                    <td class="px-6 py-4 text-sm">
                                        @php $mediaUrl = $documento->getFirstMediaUrl('archivo'); @endphp
                                        @if ($mediaUrl)
                                            <a href="{{ $mediaUrl }}" target="_blank" class="inline-flex items-center gap-1.5 text-conalep-primary hover:text-conalep-dark hover:underline font-bold transition-colors">
                                                Ver archivo
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-slate-400 text-xs font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Sin archivo
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Acciones con Iconos o Botón de Restaurar -->
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        @if(request('estado') == 'eliminados')
                                            {{-- Botón para Restaurar --}}
                                            <form action="{{ route('admin.areas.documentos.restore', [$area, $documento->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all" title="Restaurar elemento">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    Restaurar
                                                </button>
                                            </form>
                                        @else
                                            {{-- Acciones normales (Editar / Eliminar) --}}
                                            <div class="flex justify-end gap-3 items-center">
                                                <a href="{{ route('admin.areas.documentos.edit', [$area, $documento]) }}"
                                                   class="text-slate-400 hover:text-conalep-primary transition-colors" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.areas.documentos.destroy', [$area, $documento]) }}" method="POST" class="inline form-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center shadow-sm mb-4 text-slate-400 border border-slate-100">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <h3 class="text-slate-900 font-bold text-lg mb-1">
                                                {{ request('estado') == 'eliminados' ? 'La papelera está vacía' : 'No se encontraron documentos' }}
                                            </h3>
                                            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-5">
                                                {{ request('estado') == 'eliminados' ? 'No hay documentos eliminados pendientes por recuperar en esta área.' : 'No hay registros que coincidan con los filtros aplicados o el área aún no tiene documentos.' }}
                                            </p>
                                            
                                            @if(request('estado') != 'eliminados')
                                                <a href="{{ route('admin.areas.documentos.create', $area) }}" class="inline-flex items-center text-sm font-bold text-conalep-primary hover:text-conalep-dark transition-colors group">
                                                    Cargar un documento
                                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Sortable -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = document.getElementById('documentos-lista');
            
            if (tabla) {
                Sortable.create(tabla, {
                    handle: '.cursor-move',
                    animation: 150,
                    ghostClass: 'bg-conalep-primary/10',
                    
                    onEnd: function (evt) {
                        const ordenNuevo = Array.from(tabla.children).map(fila => fila.dataset.id);
                        console.log("El nuevo orden de IDs es:", ordenNuevo);
                    }
                });
            }
        });
    </script>
</x-app-layout>