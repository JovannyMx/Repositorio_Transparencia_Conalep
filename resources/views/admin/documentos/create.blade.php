<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.areas.documentos.index', $area) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-conalep-primary transition-colors mb-1.5 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a los documentos
            </a>
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Cargar documento en: <span class="text-conalep-primary">{{ $area->nombre }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl border-t-4 border-conalep-primary p-6 sm:p-8">
                
                <form action="{{ route('admin.areas.documentos.store', $area) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-2">
                        @include('admin.documentos._form')
                    </div>

                    <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.areas.documentos.index', $area) }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Cargar documento
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>