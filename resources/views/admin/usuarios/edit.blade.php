<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-conalep-primary transition-colors mb-1.5 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a usuarios
            </a>
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar usuario: <span class="text-conalep-primary">{{ $usuario->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta Principal --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl p-6 lg:p-8 border-t-4 border-conalep-primary">
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Actualizar información</h3>
                    <p class="text-sm text-slate-500">Modifica los datos personales o ajusta los permisos asignados a este usuario.</p>
                </div>

                <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Formulario extraído --}}
                    <div class="space-y-6">
                        @include('admin.usuarios._form')
                    </div>

                    {{-- Barra de acciones --}}
                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        
                        <a href="{{ route('admin.usuarios.index') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-conalep-wine hover:bg-slate-50 rounded-xl transition-all duration-200">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20 focus:outline-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Actualizar usuario
                        </button>

                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>