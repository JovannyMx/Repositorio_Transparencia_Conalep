<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="p-3 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 text-emerald-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                        {{ __('Dashboard y Analíticas') }}
                    </h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Transparencia y métricas de acceso CONALEP Jalisco</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Métricas en tiempo real
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100/60 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Tarjeta de Bienvenida --}}
            <div class="bg-gradient-to-r from-white via-white to-emerald-50/30 rounded-3xl shadow-sm border border-slate-200/70 p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-600"></div>
                <div class="flex items-center gap-5">
                    <div class="p-3.5 bg-emerald-50 rounded-2xl shrink-0 border border-emerald-100 text-emerald-600 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">¡Bienvenido al panel general de transparencia!</h3>
                        <p class="text-slate-500 text-sm font-medium mt-1 leading-relaxed">
                            Resumen consolidado de consultas por área, previsualización directa de documentos en vivo y contabilización de descargas.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Grid de Métricas Clave --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                {{-- Visitas a Áreas --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/70 hover:shadow-md hover:border-emerald-300 hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Visitas a Áreas</p>
                        <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ number_format($totalVisitasAreas ?? 0) }}</h3>
                </div>

                {{-- Documentos Vistos --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/70 hover:shadow-md hover:border-teal-300 hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Visualizaciones PDF</p>
                        <div class="p-2.5 bg-teal-50 rounded-xl text-teal-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ number_format($totalVistasDocs ?? 0) }}</h3>
                </div>

                {{-- Descargas Totales --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/70 hover:shadow-md hover:border-cyan-300 hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Descargas Archivos</p>
                        <div class="p-2.5 bg-cyan-50 rounded-xl text-cyan-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ number_format($totalDescargasDocs ?? 0) }}</h3>
                </div>

                {{-- Áreas Activas --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/70 hover:shadow-md hover:border-indigo-300 hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Áreas Públicas</p>
                        <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ $totalAreas ?? 0 }}</h3>
                </div>

                {{-- Documentos Activos --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/70 hover:shadow-md hover:border-amber-300 hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Archivos Publicados</p>
                        <div class="p-2.5 bg-amber-50 rounded-xl text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 tracking-tight">{{ $totalDocs ?? 0 }}</h3>
                </div>

            </div>

            {{-- Listados Ranking Top 5 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Top Áreas --}}
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-200/80">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                        <h4 class="font-bold text-slate-800 text-base flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                            Áreas Más Consultadas
                        </h4>
                        <span class="text-[11px] font-extrabold text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-wider">Top 5</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($areasPopulares as $index => $area)
                            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100 flex items-center justify-between hover:bg-emerald-50/30 hover:border-emerald-200 transition-all duration-150">
                                <div class="flex items-center gap-3.5">
                                    <span class="w-7 h-7 rounded-xl bg-emerald-100/80 text-emerald-800 font-black text-xs flex items-center justify-center shrink-0 border border-emerald-200/50">
                                        {{ $index + 1 }}º
                                    </span>
                                    <span class="text-sm font-bold text-slate-700">{{ $area->nombre }}</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100/80 text-emerald-800 border border-emerald-200/60 shrink-0">
                                    {{ number_format($area->visitas) }} {{ $area->visitas === 1 ? 'visita' : 'visitas' }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-sm text-slate-400 font-medium">Sin interacciones registradas aún.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Top Documentos --}}
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-200/80">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                        <h4 class="font-bold text-slate-800 text-base flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-teal-500 shadow-sm shadow-teal-500/50"></span>
                            Documentos Más Consultados
                        </h4>
                        <span class="text-[11px] font-extrabold text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-wider">Top 5</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($documentosPopulares as $index => $doc)
                            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100 flex items-center justify-between hover:bg-teal-50/30 hover:border-teal-200 transition-all duration-150">
                                <div class="flex items-center gap-3.5 truncate mr-3">
                                    <span class="w-7 h-7 rounded-xl bg-teal-100/80 text-teal-800 font-black text-xs flex items-center justify-center shrink-0 border border-teal-200/50">
                                        {{ $index + 1 }}º
                                    </span>
                                    <div class="truncate">
                                        <p class="text-sm font-bold text-slate-700 truncate" title="{{ $doc->nombre }}">{{ $doc->nombre }}</p>
                                        @if($doc->area)
                                            <p class="text-[11px] text-slate-400 font-semibold truncate mt-0.5">{{ $doc->area->nombre }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    {{-- Badge Visualizaciones --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-100/80 text-teal-800 border border-teal-200/60" title="Visualizaciones directas">
                                        {{ number_format($doc->vistas ?? 0) }} {{ ($doc->vistas ?? 0) === 1 ? 'vista' : 'vistas' }}
                                    </span>
                                    {{-- Badge Descargas --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-100/80 text-cyan-800 border border-cyan-200/60" title="Archivos descargados">
                                        {{ number_format($doc->descargas ?? 0) }} {{ ($doc->descargas ?? 0) === 1 ? 'descarga' : 'descargas' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-sm text-slate-400 font-medium">Sin interacciones registradas aún.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>