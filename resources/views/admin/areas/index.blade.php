<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
<<<<<<< HEAD
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Áreas de la administración
            </h2>
            <a href="{{ route('admin.areas.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Nueva área
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
=======
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
            
            <!-- Mensaje de éxito de la versión original -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-[#00664f] text-green-800 px-4 py-3 rounded-md shadow-sm">
>>>>>>> origin/main
                    {{ session('success') }}
                </div>
            @endif

<<<<<<< HEAD
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($areas as $area)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $area->orden }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $area->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $area->slug }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($area->activo)
                                        <span class="text-green-700 bg-green-100 px-2 py-1 rounded-full text-xs">Activo</span>
                                    @else
                                        <span class="text-gray-600 bg-gray-100 px-2 py-1 rounded-full text-xs">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-2">
                                    <a href="{{ route('admin.areas.secciones.index', $area) }}"
                                     class="text-gray-600 hover:text-gray-900">Secciones</a>
                                    <a href="{{ route('admin.areas.documentos.index', $area) }}"
                                         class="text-gray-600 hover:text-gray-900">Documentos</a>
                                    <a href="{{ route('admin.areas.edit', $area) }}"
                                         class="text-indigo-600 hover:text-indigo-900">Editar</a>
=======
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($areas as $area)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group flex flex-col justify-between">
                        <!-- Barra lateral decorativa -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $area->activo ? 'bg-[#00664f]' : 'bg-gray-400' }} group-hover:w-2 transition-all duration-300"></div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 pl-3">{{ $area->nombre }}</h3>
                            
                            <!-- Badges de información (Activo/Orden) integrados del original -->
                            <div class="pl-3 flex items-center gap-2 mb-3">
                                @if ($area->activo)
                                    <span class="text-green-700 bg-green-100 px-3 py-1 rounded-full text-xs font-bold tracking-wide">Activo</span>
                                @else
                                    <span class="text-gray-600 bg-gray-100 px-3 py-1 rounded-full text-xs font-bold tracking-wide">Inactivo</span>
                                @endif
                                <span class="text-[#00664f] bg-green-50 px-3 py-1 rounded-full text-xs font-bold tracking-wide">Orden: {{ $area->orden }}</span>
                            </div>
                            
                            <!-- Slug integrado del original -->
                            <p class="text-xs text-gray-400 mb-4 pl-3 font-mono">Slug: {{ $area->slug }}</p>
                        </div>
                        
                        <div class="flex flex-col gap-3 mt-4">
                            <!-- Enlaces a Secciones y Documentos (Rejilla de 2 columnas) -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('admin.areas.secciones.index', $area) }}" 
                                   class="text-center bg-gray-50 text-gray-700 border border-gray-200 px-3 py-2 rounded-lg text-sm font-bold hover:bg-gray-100 hover:text-[#00664f] hover:border-[#00664f] transition-all">
                                    Secciones
                                </a>
                                <a href="{{ route('admin.areas.documentos.index', $area) }}" 
                                   class="text-center bg-gray-50 text-gray-700 border border-gray-200 px-3 py-2 rounded-lg text-sm font-bold hover:bg-gray-100 hover:text-[#00664f] hover:border-[#00664f] transition-all">
                                    Documentos
                                </a>
                            </div>

                            <!-- Acciones de Edición/Eliminación solo para Admins -->
                            @role('admin')
                                <div class="flex justify-end gap-2 border-t border-gray-50 pt-4 mt-2">
                                    <a href="{{ route('admin.areas.edit', $area) }}" 
                                       class="text-[#00664f] bg-green-50 px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-[#00664f] hover:text-white transition-colors">
                                        Editar
                                    </a>
>>>>>>> origin/main
                                    <form action="{{ route('admin.areas.destroy', $area) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta área? Esto también eliminará sus secciones y documentos.');">
                                        @csrf
                                        @method('DELETE')
<<<<<<< HEAD
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No hay áreas registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
=======
                                        <button type="submit" class="text-red-600 bg-red-50 px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-600 hover:text-white transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            @endrole
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-dashed border-gray-300">
                        <p class="text-gray-500 text-lg">No hay áreas registradas todavía.</p>
                    </div>
                @endforelse
>>>>>>> origin/main
            </div>
        </div>
    </div>
</x-app-layout>