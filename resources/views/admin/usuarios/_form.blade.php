@php
    $usuario = $usuario ?? null;
    $rolActual = $usuario?->roles->first()?->name;
    $areasActuales = $usuario?->areas->pluck('id')->toArray() ?? [];
@endphp

<div class="space-y-4" x-data="{ rol: '{{ old('role', $rolActual ?? 'editor') }}' }">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="name" value="{{ old('name', $usuario->name ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Correo</label>
        <input type="email" name="email" value="{{ old('email', $usuario->email ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Contraseña
            @if ($usuario)
                <span class="text-xs text-gray-400">— deja vacío para no cambiarla</span>
            @endif
        </label>
        <input type="password" name="password"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Rol</label>
        <select name="role" x-model="rol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach ($roles as $rolOpcion)
                <option value="{{ $rolOpcion->name }}">{{ ucfirst($rolOpcion->name) }}</option>
            @endforeach
        </select>
        @error('role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div x-show="rol === 'editor'">
        <label class="block text-sm font-medium text-gray-700 mb-2">Áreas asignadas</label>
        <div class="space-y-2 border border-gray-200 rounded-md p-3">
            @foreach ($areas as $areaOpcion)
                <label class="flex items-center">
                    <input type="checkbox" name="areas[]" value="{{ $areaOpcion->id }}"
                           {{ in_array($areaOpcion->id, old('areas', $areasActuales)) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">{{ $areaOpcion->nombre }}</span>
                </label>
            @endforeach
        </div>
        @error('areas')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>