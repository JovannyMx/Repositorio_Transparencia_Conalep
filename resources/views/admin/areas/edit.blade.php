<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-conalep-primary transition-colors mb-1.5 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a las áreas
            </a>
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar área: <span class="text-conalep-primary">{{ $area->nombre }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl border-t-4 border-conalep-primary p-6 sm:p-8">
                
                <form action="{{ route('admin.areas.update', $area) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-2">
                        @include('admin.areas._form')
                    </div>

                    <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.areas.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Actualizar área
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>