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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Nuevo <span class="text-conalep-primary">usuario</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta Principal --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl p-6 lg:p-8 border-t-4 border-conalep-primary">
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Detalles de la cuenta</h3>
                    <p class="text-sm text-slate-500">Ingresa la información básica y asigna los permisos necesarios para este usuario.</p>
                </div>

                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear usuario
                        </button>

                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>