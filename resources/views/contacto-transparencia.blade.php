@extends('layouts.public')

@section('title', 'Unidad de Transparencia - CONALEP Jalisco')

@section('content')

    <!-- Encabezado Principal -->
    <header class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border-l-4 border-l-conalep-green border border-slate-200 mb-8 relative overflow-hidden">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-conalep-dark mb-2">
            Unidad de Transparencia
        </h1>
        <p class="text-slate-600 text-sm leading-relaxed max-w-3xl">
            Información de contacto y atención oficial para solicitudes de acceso a la información pública y ejercicio de derechos ARCO.
        </p>
    </header>

    <!-- Grid Informativo -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        
        <!-- Sección: Datos Institucionales -->
        <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h2 class="text-lg font-bold text-conalep-dark mb-5 border-b border-slate-200 pb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-conalep-green inline-block"></span>
                Datos Institucionales
            </h2>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="block text-xs font-bold text-conalep-green uppercase tracking-wider mb-1">Titular de la Unidad</span>
                    <p class="font-semibold text-slate-800">Unidad de Transparencia e Información Pública</p>
                </div>

                <div>
                    <span class="block text-xs font-bold text-conalep-green uppercase tracking-wider mb-1">Dirección</span>
                    <p class="text-slate-700 leading-relaxed">Av. México 2525, Col. Ladrón de Guevara, Guadalajara, Jalisco.</p>
                </div>

                <div>
                    <span class="block text-xs font-bold text-conalep-green uppercase tracking-wider mb-1">Teléfono de Atención</span>
                    <p class="text-slate-700 font-medium">(33) 3818-4100 Ext. 102</p>
                </div>

                <div>
                    <span class="block text-xs font-bold text-conalep-green uppercase tracking-wider mb-1">Correo Electrónico</span>
                    <a href="mailto:transparencia@jalisco.conalep.edu.mx" class="text-conalep-green font-semibold hover:underline transition">
                        transparencia@jalisco.conalep.edu.mx
                    </a>
                </div>
            </div>
        </section>

        <!-- Sección: Plataforma Externa / Solicitudes -->
        <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-conalep-dark mb-5 border-b border-slate-200 pb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-conalep-green inline-block"></span>
                    Plataformas de Solicitudes
                </h2>
                
                <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                    Puedes ingresar solicitudes de información pública o ejercitar tus derechos ARCO (Acceso, Rectificación, Cancelación y Oposición) directamente en la plataforma nacional.
                </p>
            </div>

            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-center">
                <a 
                    href="https://www.plataformadetransparencia.org.mx/" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 w-full bg-conalep-green hover:bg-conalep-dark text-white font-bold py-3 px-4 rounded-lg transition shadow-sm text-sm"
                >
                    <span>Ir a Plataforma Nacional de Transparencia</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </section>

    </div>

@endsection