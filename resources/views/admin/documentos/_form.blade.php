@php $documento = $documento ?? null; @endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre del documento</label>
        <input type="text" name="nombre" value="{{ old('nombre', $documento->nombre ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Sección (opcional)</label>
        <select name="seccion_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Ninguna (documento suelto en el área) --</option>
            @foreach ($secciones as $opcion)
                <option value="{{ $opcion->id }}"
                    {{ old('seccion_id', $documento->seccion_id ?? '') == $opcion->id ? 'selected' : '' }}>
                    {{ $opcion->nombre }}
                </option>
            @endforeach
        </select>
        @error('seccion_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Fecha de publicación</label>
        <input type="date" name="fecha_publicacion"
               value="{{ old('fecha_publicacion', isset($documento) && $documento->fecha_publicacion ? $documento->fecha_publicacion->format('Y-m-d') : now()->format('Y-m-d')) }}"
               class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('fecha_publicacion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Orden de despliegue</label>
        <input type="number" name="orden" value="{{ old('orden', $documento->orden ?? 0) }}" min="0"
               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('orden')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Archivo (PDF, Word o Excel — máx. 10 MB)
            @if ($documento && $documento->getFirstMediaUrl('archivo'))
                <span class="text-xs text-gray-400">— deja vacío para conservar el archivo actual</span>
            @endif
        </label>
        <input type="file" name="archivo" accept=".pdf,.doc,.docx,.xls,.xlsx"
               class="mt-1 block w-full text-sm text-gray-600
                      file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @error('archivo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($documento && $documento->getFirstMediaUrl('archivo'))
            <p class="mt-2 text-sm">
                Archivo actual:
                <a href="{{ $documento->getFirstMediaUrl('archivo') }}" target="_blank" class="text-indigo-600 hover:underline">
                    ver archivo
                </a>
            </p>
        @endif
    </div>
</div>