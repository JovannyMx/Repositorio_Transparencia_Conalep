<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-garet font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                <div class="p-2 bg-conalep-primary/10 rounded-lg">
                    <svg class="w-6 h-6 text-conalep-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-conalep-gray/20 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tarjeta de Bienvenida -->
            <div class="bg-white shadow-xl shadow-slate-200/40 rounded-2xl border-t-4 border-conalep-primary p-6 sm:p-8 animate-fade-in-down">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 rounded-full shrink-0 border border-emerald-100">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 mb-1">¡Bienvenido al sistema!</h3>
                        <p class="text-slate-500 text-sm font-medium">
                           
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>