<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a áreas
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    Documentos de: {{ $area->nombre }}
                </h2>
            </div>
            <a href="{{ route('admin.areas.documentos.create', $area) }}"
               class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Cargar documento
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <!-- Columna de Orden agregada para cumplir RF-12 -->
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Orden</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sección</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Liga</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="documentos-lista" class="divide-y divide-gray-100 bg-white">
                            
                            @forelse ($documentos as $documento)
                                <tr data-id="{{ $documento->id }}" class="hover:bg-indigo-50/30 transition-colors group">
                                    
                                    <!-- Controles Visuales de Ordenamiento -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-300 cursor-move hover:text-indigo-500 transition-colors" title="Arrastrar para reordenar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                            </span>
                                            <div class="flex flex-col">
                                                <button class="text-gray-400 hover:text-indigo-600 transition-colors p-0.5" title="Subir posición">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-indigo-600 transition-colors p-0.5" title="Bajar posición">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Nombre -->
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $documento->nombre }}
                                    </td>
                                    
                                    <!-- Sección (Badge) -->
                                    <td class="px-6 py-4 text-sm">
                                        @if($documento->seccion)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $documento->seccion->nombre }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">— (suelto)</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Tipo de Archivo (Badge) -->
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $documento->extension }}
                                        </span>
                                    </td>
                                    
                                    <!-- Fecha -->
                                    <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                        {{ $documento->fecha_publicacion?->format('d/m/Y') }}
                                    </td>
                                    
                                    <!-- Liga del Archivo -->
                                    <td class="px-6 py-4 text-sm">
                                        @php $mediaUrl = $documento->getFirstMediaUrl('archivo'); @endphp
                                        @if ($mediaUrl)
                                            <a href="{{ $mediaUrl }}" target="_blank" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 hover:underline font-medium">
                                                Ver archivo
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Sin archivo
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Acciones con Iconos -->
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3 items-center">
                                            <a href="{{ route('admin.areas.documentos.edit', [$area, $documento]) }}"
                                               class="text-gray-400 hover:text-indigo-600 transition-colors" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.areas.documentos.destroy', [$area, $documento]) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <!-- Estado Vacío Mejorado -->
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin documentos</h3>
                                        <p class="mt-1 text-sm text-gray-500">No se han cargado documentos en esta área todavía.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.areas.documentos.create', $area) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Cargar el primer documento
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
<!-- Script para habilitar Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = document.getElementById('documentos-lista');
            
            if (tabla) {
                Sortable.create(tabla, {
                    handle: '.cursor-move', // Solo permite arrastrar si el usuario toma el ícono
                    animation: 150,         // Animación fluida al mover
                    ghostClass: 'bg-indigo-50', // Color de fondo mientras se arrastra
                    
                    // Esta función se ejecuta cuando el usuario suelta la fila
                    onEnd: function (evt) {
                        // Creamos un array con el nuevo orden de los IDs
                        const ordenNuevo = Array.from(tabla.children).map(fila => fila.dataset.id);
                        
                        console.log("El nuevo orden de IDs es:", ordenNuevo);

                        // AQUÍ COMIENZA LA CONEXIÓN CON EL BACKEND
                        // Erick ya esta el array listo para enviar.
                        // Cuando él tenga la ruta lista, descomentaré este código:
                        
                        /*
                        fetch('/admin/areas/{{ $area->id }}/documentos/reordenar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de seguridad de Laravel
                            },
                            body: JSON.stringify({ orden: ordenNuevo })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Mostrar alerta de éxito usando una librería como Toastr o SweetAlert
                            console.log('Orden guardado con éxito');
                        });
                        */
                    }
                });
            }
        });
    </script>

</x-app-layout>