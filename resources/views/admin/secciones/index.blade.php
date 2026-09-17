<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.areas.index') }}" class="text-sm text-indigo-600 hover:underline">← Volver a áreas</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Secciones de: {{ $area->nombre }}
                </h2>
            </div>
            <a href="{{ route('admin.areas.secciones.create', $area) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Nueva sección
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                @forelse ($secciones as $seccion)
                    <x-seccion-nodo :seccion="$seccion" :area="$area" />
                @empty
                    <p class="text-gray-500 text-center py-8">
                        No hay secciones todavía. Crea la primera (ej. un año) para empezar a organizar los documentos.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>