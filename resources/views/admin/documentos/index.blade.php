<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.areas.index') }}" class="text-sm text-indigo-600 hover:underline">← Volver a áreas</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Documentos de: {{ $area->nombre }}
                </h2>
            </div>
            <a href="{{ route('admin.areas.documentos.create', $area) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Cargar documento
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Liga</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($documentos as $documento)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $documento->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $documento->seccion->nombre ?? '— (suelto)' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 uppercase">{{ $documento->extension }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $documento->fecha_publicacion?->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php $mediaUrl = $documento->getFirstMediaUrl('archivo'); @endphp
                                    @if ($mediaUrl)
                                        <a href="{{ $mediaUrl }}" target="_blank" class="text-indigo-600 hover:underline">Ver archivo</a>
                                    @else
                                        <span class="text-gray-400">Sin archivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-2">
                                    <a href="{{ route('admin.areas.documentos.edit', [$area, $documento]) }}"
                                       class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    <form action="{{ route('admin.areas.documentos.destroy', [$area, $documento]) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar este documento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No hay documentos cargados todavía en esta área.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>