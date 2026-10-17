@php
    $usuario = $usuario ?? null;
    $esNuevo = !$usuario;
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

    {{-- Contraseña: generada automáticamente para usuarios nuevos, opcional para usuarios existentes --}}
    <div
        x-data="{
            password: '',
            mostrar: false,
            copiado: false,
            generar() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
                const valores = new Uint32Array(12);
                crypto.getRandomValues(valores);
                this.password = Array.from(valores, n => chars[n % chars.length]).join('');
                this.mostrar = true;
            },
            async copiar() {
                if (!this.password) return;
                await navigator.clipboard.writeText(this.password);
                this.copiado = true;
                setTimeout(() => this.copiado = false, 2000);
            }
        }"
        x-init="{{ $esNuevo ? 'generar(); mostrar = false;' : '' }}"
    >
        <label for="password" class="block text-sm font-medium text-gray-700">
            Contraseña
            @if ($usuario)
                <span class="text-xs text-gray-400">— deja vacío para no cambiarla</span>
            @endif
        </label>

        <div class="mt-1 flex gap-2">
            <div class="relative flex-1">
                <input id="password" type="password" name="password" x-model="password"
                       :type="mostrar ? 'text' : 'password'"
                       autocomplete="new-password"
                       @if ($esNuevo) required @endif
                       class="block w-full rounded-md border-gray-300 shadow-sm pr-10 focus:border-[#00664f] focus:ring-[#00664f]">

                {{-- Ojo para ver / ocultar --}}
                <button type="button" @click="mostrar = !mostrar"
                        class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-[#00664f]"
                        :aria-label="mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                    <svg x-show="!mostrar" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="mostrar" style="display:none" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>

            <button type="button" @click="copiar()"
                    class="px-3 rounded-md border border-gray-300 text-sm text-gray-600 hover:bg-gray-50"
                    x-text="copiado ? '¡Copiada!' : 'Copiar'"></button>

            <button type="button" @click="generar()"
                    class="px-3 rounded-md bg-[#00664f] text-white text-sm font-medium hover:bg-[#004d3b]">
                Generar
            </button>
        </div>

        @if ($esNuevo)
            <p class="mt-1 text-xs text-gray-400">
                Copia la contraseña antes de guardar: una vez creado el usuario no se podrá volver a ver.
            </p>
        @endif

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