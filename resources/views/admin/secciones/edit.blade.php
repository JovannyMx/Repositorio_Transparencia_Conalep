<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar sección de: {{ $area->nombre }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.areas.secciones.update', [$area, $seccion]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.secciones._form')

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.areas.secciones.index', $area) }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>