@props(['seccion', 'area'])

<div class="border-b border-gray-100 last:border-0" x-data="{ open: false }">
    <div class="flex items-center justify-between py-3">
        <button @click="open = !open" class="flex items-center text-left flex-1">
            <span class="mr-2 text-gray-400" x-text="open ? '▾' : '▸'"></span>
            <span class="font-medium text-gray-800">{{ $seccion->nombre }}</span>
            @if ($seccion->tipo)
                <span class="ml-2 text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $seccion->tipo }}</span>
            @endif
        </button>

        <div class="space-x-3 text-sm">
            <a href="{{ route('admin.areas.secciones.edit', [$area, $seccion]) }}"
               class="text-indigo-600 hover:text-indigo-900">Editar</a>
            <form action="{{ route('admin.areas.secciones.destroy', [$area, $seccion]) }}" method="POST" class="inline"
                  onsubmit="return confirm('¿Eliminar esta sección? Se eliminarán también sus subsecciones y documentos.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
            </form>
        </div>
    </div>

    <div x-show="open" class="pl-6 pb-2">
        @foreach ($seccion->childrenRecursive as $hijo)
            <x-seccion-nodo :seccion="$hijo" :area="$area" />
        @endforeach

        @if ($seccion->childrenRecursive->isEmpty())
            <p class="text-xs text-gray-400 italic py-2">Sin subsecciones.</p>
        @endif
    </div>
</div>