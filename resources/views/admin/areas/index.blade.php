<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
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
                    {{ session('success') }}
                </div>
            @endif

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
                                    <form action="{{ route('admin.areas.destroy', $area) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta área? Esto también eliminará sus secciones y documentos.');">
                                        @csrf
                                        @method('DELETE')
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
            </div>
        </div>
    </div>
</x-app-layout>