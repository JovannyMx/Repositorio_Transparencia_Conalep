@php $area = $area ?? null; @endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre del área</label>
        <input type="text" name="nombre" value="{{ old('nombre', $area->nombre ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Descripción corta</label>
        <textarea name="descripcion_corta" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion_corta', $area->descripcion_corta ?? '') }}</textarea>
        @error('descripcion_corta')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Orden de despliegue</label>
        <input type="number" name="orden" value="{{ old('orden', $area->orden ?? 0) }}" min="0"
               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('orden')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="activo" id="activo" value="1"
               {{ old('activo', $area->activo ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="activo" class="ml-2 text-sm text-gray-700">Área activa (visible al público)</label>
    </div>
</div>