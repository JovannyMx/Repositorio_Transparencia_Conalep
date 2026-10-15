@props(['seccion'])

<div class="border-b border-gray-100 last:border-0" x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center text-left w-full py-3">
        <span class="mr-2 text-gray-400" x-text="open ? '▾' : '▸'"></span>
        <span class="font-medium text-gray-800">{{ $seccion->nombre }}</span>
    </button>

    <div x-show="open" class="pl-6 pb-2">
        @foreach ($seccion->childrenRecursive as $hijo)
            <x-seccion-nodo-publica :seccion="$hijo" />
        @endforeach

        @foreach ($seccion->documentos as $documento)
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

        @if ($seccion->childrenRecursive->isEmpty() && $seccion->documentos->isEmpty())
            <p class="text-xs text-gray-400 italic py-2">Sin contenido.</p>
        @endif
    </div>
</div>