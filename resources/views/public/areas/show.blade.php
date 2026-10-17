<x-layouts.public>
    <x-slot name="header">
        <a href="{{ route('publico.areas.index') }}" class="text-sm text-indigo-600 hover:underline">← Todas las áreas</a>
        <h1 class="font-semibold text-2xl text-gray-800 mt-1">{{ $area->nombre }}</h1>
        @if ($area->descripcion_corta)
            <p class="text-sm text-gray-500 mt-1">{{ $area->descripcion_corta }}</p>
        @endif
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            @forelse ($secciones as $seccion)
                <x-seccion-nodo-publica :seccion="$seccion" />
            @empty
                @if ($documentosSueltos->isEmpty())
                    <p class="text-gray-500 text-center py-8">
                        No hay documentos publicados en esta área todavía.
                    </p>
                @endif
            @endforelse

            @if ($documentosSueltos->isNotEmpty())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    @foreach ($documentosSueltos as $documento)
                        <div class="flex justify-between items-center py-2">
                            <div>
                                <a href="{{ route('publico.documentos.preview', $documento->liga_publica) }}"
                                   target="_blank"
                                   class="text-indigo-600 hover:underline font-medium">
                                    {{ $documento->nombre }}
                                </a>
                                <p class="text-xs text-gray-400">
                                    {{ strtoupper($documento->extension) }} ·
                                    {{ $documento->fecha_publicacion?->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-layouts.public>