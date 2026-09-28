<x-layouts.public>
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800">Publicaciones</h1>
        <p class="text-sm text-gray-500 mt-1">Consulta los documentos de transparencia organizados por área.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($areas as $area)
                <a href="{{ route('publico.areas.show', $area) }}"
                   class="block bg-white rounded-lg shadow hover:shadow-md transition p-6 border border-gray-100">
                    <h2 class="font-semibold text-lg text-gray-800">{{ $area->nombre }}</h2>
                    @if ($area->descripcion_corta)
                        <p class="text-sm text-gray-500 mt-2 line-clamp-3">{{ $area->descripcion_corta }}</p>
                    @endif
                    <p class="text-xs text-indigo-600 mt-4 font-medium">
                        {{ $area->documentos_count }} {{ $area->documentos_count == 1 ? 'documento' : 'documentos' }} →
                    </p>
                </a>
            @empty
                <p class="text-gray-500 col-span-full text-center py-12">
                    No hay áreas publicadas todavía.
                </p>
            @endforelse
        </div>
    </div>
</x-layouts.public>