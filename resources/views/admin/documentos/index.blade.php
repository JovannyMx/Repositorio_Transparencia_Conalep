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

            {{-- Tarjeta de Tabla --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl border-t-4 border-conalep-primary overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20">Orden</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sección</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Fecha</th>
                                <!-- Nueva columna para la fecha de vencimiento -->
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Vencimiento</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Liga</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="documentos-lista" class="divide-y divide-slate-100 bg-white">
                            
                            @forelse ($documentos as $documento)
                                <tr data-id="{{ $documento->id }}" class="hover:bg-slate-50/80 transition-colors group">
                                    
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
                                    
                                    <!-- Fecha de publicación -->
                                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                        {{ $documento->fecha_publicacion?->format('d/m/Y') }}
                                    </td>
                                    
                                    <!-- NUEVO: Fecha de vencimiento -->
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
                                    
                                    <!-- Acciones con Iconos -->
                                    <td class="px-6 py-4 text-right text-sm font-medium">
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
                                    </td>
                                </tr>
                            @empty
                                <!-- Estado Vacío Mejorado (Se actualizó colspan de 7 a 8) -->
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center shadow-sm mb-4 text-slate-400 border border-slate-100">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <h3 class="text-slate-900 font-bold text-lg mb-1">Área sin documentos</h3>
                                            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-5">No se han cargado documentos en esta área todavía. Comienza agregando el primero.</p>
                                            
                                            <a href="{{ route('admin.areas.documentos.create', $area) }}" class="inline-flex items-center text-sm font-bold text-conalep-primary hover:text-conalep-dark transition-colors group">
                                                Cargar mi primer documento
                                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
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

    <!-- Script para habilitar Drag & Drop (Opcional: Si ya no usarás la función de arrastrar, puedes borrar toda esta etiqueta <script>) -->
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