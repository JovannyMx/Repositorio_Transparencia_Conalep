<x-app-layout>
    <x-slot name="header">
        <h2 class="font-garet font-bold text-2xl text-conalep-primary leading-tight">
            Nueva área
        </h2>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta Principal con acento institucional --}}
            <div class="bg-white shadow-xl shadow-conalep-primary/5 rounded-2xl p-6 lg:p-8 border-t-4 border-conalep-primary">
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Detalles del área</h3>
                    <p class="text-sm text-gray-500">Ingresa la información para registrar una nueva área en el sistema.</p>
                </div>

                <form action="{{ route('admin.areas.store') }}" method="POST">
                    @csrf
                    
                    {{-- Formulario extraído --}}
                    <div class="space-y-6">
                        @include('admin.areas._form')
                    </div>

                    {{-- Barra de acciones --}}
                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        
                        <a href="{{ route('admin.areas.index') }}"
                           class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-conalep-wine hover:bg-gray-50 rounded-xl transition-all duration-200">
                            Cancelar
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-conalep-primary hover:bg-conalep-dark text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-conalep-primary/30 transition-all duration-200 hover:-translate-y-0.5 active:scale-95 focus:ring-4 focus:ring-conalep-primary/20 focus:outline-none">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar registro
                        </button>

                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>