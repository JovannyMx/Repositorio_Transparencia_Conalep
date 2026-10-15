@php $seccion = $seccion ?? null; @endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $seccion->nombre ?? '') }}"
               placeholder="Ej. 2026 (CONAC), Primer Trimestre, Contenido Contable"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tipo (opcional, ayuda a identificar el nivel)</label>
        <select name="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Sin especificar --</option>
            <option value="anio" {{ old('tipo', $seccion->tipo ?? '') == 'anio' ? 'selected' : '' }}>Año</option>
            <option value="periodo" {{ old('tipo', $seccion->tipo ?? '') == 'periodo' ? 'selected' : '' }}>Periodo/Trimestre</option>
            <option value="categoria" {{ old('tipo', $seccion->tipo ?? '') == 'categoria' ? 'selected' : '' }}>Categoría</option>
            <option value="libre" {{ old('tipo', $seccion->tipo ?? '') == 'libre' ? 'selected' : '' }}>Libre</option>
        </select>
        @error('tipo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Sección padre</label>
        <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Ninguna (nivel raíz) --</option>
            @foreach ($todasLasSecciones as $opcion)
                <option value="{{ $opcion->id }}"
                    {{ old('parent_id', $seccion->parent_id ?? '') == $opcion->id ? 'selected' : '' }}>
                    {{ $opcion->nombre }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Orden de despliegue</label>
        <input type="number" name="orden" value="{{ old('orden', $seccion->orden ?? 0) }}" min="0"
               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('orden')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>