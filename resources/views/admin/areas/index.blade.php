<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <div class="p-2 bg-conalep-primary/10 rounded-lg">
                    <svg class="w-6 h-6 text-conalep-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                Áreas de la <span class="text-conalep-primary">administración</span>
            </h2>
            
            @role('admin')
                <a href="{{ route('admin.areas.create') }}"
                   class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva área
                </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje de éxito estilizado -->
            @if (session('success'))
                <div class="mb-8 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-sm animate-fade-in-down" role="alert">
                    <div class="p-1.5 bg-emerald-100 rounded-full shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Contenedor Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @forelse ($areas as $area)
                    <!-- Estructura de la Tarjeta (Card) -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/40 border-t-4 border-conalep-primary p-6 flex flex-col group hover:shadow-xl hover:shadow-conalep-primary/10 transition-all duration-300 hover:-translate-y-1">
                        
                        <!-- Título -->
                        <h3 class="text-lg font-bold text-slate-800 mb-3 group-hover:text-conalep-primary transition-colors leading-tight">
                            {{ $area->nombre }}
                        </h3>

                        <!-- Indicadores (Activo y Orden) -->
                        <div class="flex items-center gap-2 mb-3">
                            @if($area->activo)
                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase bg-red-50 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Inactivo
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200" title="Orden de visualización">
                                <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                                {{ $area->orden }}
                            </span>
                        </div>

                        <!-- Slug -->
                        <p class="text-slate-400 text-[11px] font-mono mb-6 truncate bg-slate-50 px-2 py-1 rounded border border-slate-100" title="Slug: {{ $area->slug }}">
                            /{{ $area->slug }}
                        </p>

                        <!-- Botones Intermedios (Secciones y Documentos) -->
                        <div class="flex gap-3 mb-6">
                            <a href="{{ route('admin.areas.secciones.index', $area) }}" 
                               class="flex-1 flex flex-col items-center justify-center gap-1 bg-slate-50 border border-slate-200 rounded-xl py-2.5 text-xs font-bold text-slate-600 hover:bg-conalep-primary/10 hover:border-conalep-primary/30 hover:text-conalep-primary transition-colors">
                                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Secciones
                            </a>
                            <a href="{{ route('admin.areas.documentos.index', $area) }}" 
                               class="flex-1 flex flex-col items-center justify-center gap-1 bg-slate-50 border border-slate-200 rounded-xl py-2.5 text-xs font-bold text-slate-600 hover:bg-conalep-primary/10 hover:border-conalep-primary/30 hover:text-conalep-primary transition-colors">
                                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Documentos
                            </a>
                        </div>

                        <!-- Acciones de Edición/Eliminación solo para Admins -->
                        @role('admin')
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                            <a href="{{ route('admin.areas.edit', $area) }}" 
                               class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-conalep-primary transition-colors p-1" title="Editar área">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </a>
                            
                            <form action="{{ route('admin.areas.destroy', $area) }}" method="POST" class="inline-block" 
                                  onsubmit="return confirm('¿Eliminar esta área? Esto también eliminará permanentemente sus secciones y documentos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-red-500 transition-colors p-1" title="Eliminar área">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                        @endrole
                        
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="col-span-full bg-white rounded-2xl p-12 text-center border-2 border-dashed border-slate-200 shadow-sm">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 text-slate-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">No hay áreas registradas</h3>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">Aún no se han configurado áreas administrativas en el sistema. Comienza creando la primera para organizar tus documentos.</p>
                        
                        @role('admin')
                            <a href="{{ route('admin.areas.create') }}" class="inline-flex items-center text-sm font-bold text-conalep-primary hover:text-conalep-dark transition-colors group">
                                Crear mi primera área
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endrole
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>