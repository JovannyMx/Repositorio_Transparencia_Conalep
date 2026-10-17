<x-app-layout>
    <x-slot name="header">
        <h2 class="font-garet font-bold text-2xl text-gray-800 leading-tight">
            Editar sección de: <span class="text-conalep-primary">{{ $area->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta Principal --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl p-6 lg:p-8 border-t-4 border-conalep-primary">
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Modificar sección</h3>
                    <p class="text-sm text-gray-500">Actualiza los datos de esta sección. Los cambios se reflejarán inmediatamente en la estructura del área.</p>
                </div>

                <form action="{{ route('admin.areas.secciones.update', [$area, $seccion]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Formulario extraído --}}
                    <div class="space-y-6">
                        @include('admin.secciones._form')
                    </div>

                    {{-- Barra de acciones --}}
                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        
                        <a href="{{ route('admin.areas.secciones.index', $area) }}"
                           class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-conalep-wine hover:bg-gray-50 rounded-xl transition-all duration-200">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20 focus:outline-none">
                            {{-- Icono de actualizar / guardar --}}
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Actualizar sección
                        </button>

                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>