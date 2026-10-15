<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            
            {{-- Enlace de retroceso y Título --}}
            <div>
                <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-conalep-primary transition-colors mb-1.5 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a las áreas
                </a>
                <h2 class="font-garet font-bold text-2xl text-gray-800 leading-tight">
                    Secciones de: <span class="text-conalep-primary">{{ $area->nombre }}</span>
                </h2>
            </div>
            
            {{-- Botón de Acción Principal --}}
            <a href="{{ route('admin.areas.secciones.create', $area) }}"
               class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva sección
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerta de Éxito Estilizada --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-sm animate-fade-in-down" role="alert">
                    <div class="p-1.5 bg-emerald-100 rounded-full shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Tarjeta Principal --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl p-6 lg:p-8 border-t-4 border-conalep-primary">
                
                {{-- Encabezado interno de la tarjeta --}}
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-bold text-gray-800">Estructura de la información</h3>
                    <p class="text-sm text-gray-500">Administra las carpetas, años y categorías para organizar los documentos de esta área.</p>
                </div>

                {{-- Contenedor de Secciones --}}
                <div class="space-y-3">
                    @forelse ($secciones as $seccion)
                        {{-- Componente que renderiza el nodo (Asegúrate de que este componente también tenga un diseño limpio) --}}
                        <x-seccion-nodo :seccion="$seccion" :area="$area" />
                    @empty
                        {{-- Estado Vacío (Empty State) --}}
                        <div class="flex flex-col items-center justify-center py-14 px-4 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-bold text-lg mb-1">Área sin secciones</h3>
                            <p class="text-gray-500 text-sm max-w-sm mx-auto mb-5">
                                Aún no hay carpetas creadas. Agrega la primera sección (por ejemplo, el año en curso) para comenzar a organizar los documentos.
                            </p>
                            <a href="{{ route('admin.areas.secciones.create', $area) }}" class="inline-flex items-center text-sm font-bold text-conalep-primary hover:text-conalep-dark transition-colors group">
                                Crear mi primera sección
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>