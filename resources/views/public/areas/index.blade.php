<x-layouts.public>
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800">Publicaciones</h1>
        <p class="text-sm text-gray-500 mt-1">Consulta los documentos de transparencia organizados por área.</p>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($areas as $area)
                <a href="{{ route('publico.areas.show', $area) }}"
                   class="block bg-white rounded-lg shadow hover:shadow-md transition p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative w-14 h-14 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                {{-- LÍNEA DE DEPURACIÓN --}}
                                <span class="absolute top-0 left-0 bg-red-100 text-[8px] text-red-600 px-1 rounded-br z-10">{{ $area->icono ?? 'VACÍO' }}</span>

                                @if ($area->icono_url)
                                    <img src="{{ $area->icono_url }}" alt="{{ $area->nombre }}" class="w-10 h-10 object-contain">
                                @else
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area->icono_svg_path }}"></path>
                                    </svg>
                                @endif
                            </div>
                            <h2 class="font-semibold text-lg text-gray-800">{{ $area->nombre }}</h2>
                        </div>

                        @if ($area->descripcion_corta)
                            <p class="text-sm text-gray-500 mt-2 line-clamp-3">{{ $area->descripcion_corta }}</p>
                        @endif
                    </div>

                    <p class="text-xs text-indigo-600 mt-4 font-medium">
                        {{ $area->documentos_count ?? 0 }} {{ ($area->documentos_count ?? 0) == 1 ? 'documento' : 'documentos' }} →
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