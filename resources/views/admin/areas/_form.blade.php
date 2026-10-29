@php
    $area = $area ?? null;
    $iconoUrl = $area?->icono_url;
    $iconoPath = $area?->icono_svg_path;
    $rutaDefault = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4';
@endphp

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

    {{-- Vista por defecto (Lista vs Carpetas) --}}
    <div>
        <label for="modo_vista" class="block text-sm font-medium text-gray-700">Vista por defecto</label>
        <select name="modo_vista" id="modo_vista"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="lista" {{ old('modo_vista', $area->modo_vista ?? 'lista') === 'lista' ? 'selected' : '' }}>
                Vista de Lista (Tabla)
            </option>
            <option value="carpetas" {{ old('modo_vista', $area->modo_vista ?? '') === 'carpetas' ? 'selected' : '' }}>
                Vista de Carpetas (Cuadrícula por Secciones)
            </option>
        </select>
        <p class="text-xs text-gray-400 mt-1">
            Determina cómo se presentarán los documentos al ciudadano al entrar a esta área.
        </p>
        @error('modo_vista')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Icono / imagen del área --}}
    <div x-data="{
            preview: null,
            elegir(e) {
                const f = e.target.files[0];
                this.preview = f ? URL.createObjectURL(f) : null;
            }
         }">
        <label class="block text-sm font-medium text-gray-700">Icono o imagen del área</label>
        <p class="text-xs text-gray-400 mt-0.5">
            Se muestra en la tarjeta del sitio público. PNG, JPG, WEBP o SVG, máximo 1 MB.
        </p>

        <div class="mt-3 flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-conalep-light border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                {{-- Vista previa de la imagen recién elegida --}}
                <img x-show="preview" :src="preview" alt="" class="w-12 h-12 object-contain" style="display:none">

                {{-- Icono actual --}}
                <div x-show="!preview">
                    @if ($iconoUrl)
                        <img src="{{ $iconoUrl }}" alt="Icono actual" class="w-12 h-12 object-contain">
                    @else
                        <svg class="w-8 h-8 text-conalep-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconoPath ?? $rutaDefault }}"></path>
                        </svg>
                    @endif
                </div>
            </div>

            <input type="file" name="icono" accept=".png,.jpg,.jpeg,.webp,.svg,image/*"
                   @change="elegir($event)"
                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-conalep-light file:text-conalep-primary file:font-semibold hover:file:bg-conalep-mint/40">
        </div>

        @error('icono')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($iconoUrl)
            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="quitar_icono" value="1"
                       class="rounded border-gray-300 text-conalep-primary focus:ring-conalep-primary">
                Quitar la imagen actual (volver al icono por defecto)
            </label>
        @endif
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