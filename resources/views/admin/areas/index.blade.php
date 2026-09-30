<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#00664f] leading-tight flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Áreas de la administración
            </h2>
            @role('admin')
                <a href="{{ route('admin.areas.create') }}"
                 class="bg-[#00664f] text-white px-6 py-2.5 rounded-full font-semibold shadow-lg hover:bg-[#004d3b] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                 + Nueva área
                </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-[#00664f] text-green-800 px-4 py-3 rounded-md shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Contenedor Grid (Reemplaza a la etiqueta table) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @forelse ($areas as $area)
                    <!-- Estructura de la Tarjeta (Card) -->
                    <div class="bg-white rounded-2xl shadow-sm border-l-4 border-[#00664f] p-6 flex flex-col">
                        
                        <!-- Título -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $area->nombre }}</h3>

                        <!-- Indicadores (Activo y Orden) -->
                        <div class="flex items-center gap-3 mb-4 text-xs">
                            @if($area->activo)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded font-medium">Activo</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded font-medium">Inactivo</span>
                            @endif
                            <span class="text-[#00664f] font-medium">Orden: {{ $area->orden }}</span>
                        </div>

                        <!-- Slug -->
                        <p class="text-gray-400 text-xs mb-6 font-mono">Slug: {{ $area->slug }}</p>

                        <!-- Botones Intermedios (Secciones y Documentos) -->
                        <div class="flex gap-3 mb-6">
                            <a href="{{ route('admin.areas.secciones.index', $area) }}" 
                               class="flex-1 text-center bg-gray-50 border border-gray-100 rounded-lg py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                                Secciones
                            </a>
                            <a href="{{ route('admin.areas.documentos.index', $area) }}" 
                               class="flex-1 text-center bg-gray-50 border border-gray-100 rounded-lg py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                                Documentos
                            </a>
                        </div>

                        <!-- Acciones de Edición/Eliminación solo para Admins -->
                        @role('admin')
                        <div class="flex justify-center gap-3 mt-auto pt-2">
                            <a href="{{ route('admin.areas.edit', $area) }}" 
                               class="text-sm font-medium text-green-600 bg-green-50 px-6 py-1.5 rounded-lg hover:bg-green-100 transition-colors">
                                Editar
                            </a>
                            <form action="{{ route('admin.areas.destroy', $area) }}" method="POST" class="inline-block" 
                                  onsubmit="return confirm('¿Eliminar esta área? Esto también eliminará sus secciones y documentos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-sm font-medium text-red-600 bg-red-50 px-6 py-1.5 rounded-lg hover:bg-red-100 transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                        @endrole
                        
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-dashed border-gray-300">
                        <p class="text-gray-500 text-lg">No hay áreas registradas todavía.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>