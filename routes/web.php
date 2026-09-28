<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// 1. Portal Principal de Transparencia (RF-01)
Route::get('/', function () {
    $areas = [
        [
            'slug' => 'direccion-general',
            'nombre' => 'Dirección General',
            'descripcion' => 'Normatividad, informes de gestión directiva y acuerdos institucionales.',
            'icono' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'documentos_count' => 12
        ],
        [
            'slug' => 'recursos-humanos',
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Estructura orgánica, directorio, sueldos, vacaciones y convocatorias de personal.',
            'icono' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'documentos_count' => 28
        ],
        [
            'slug' => 'finanzas-contabilidad',
            'nombre' => 'Finanzas y Contabilidad',
            'descripcion' => 'Presupuestos asignados, informes de ejercicio gasto y auditorías financieras.',
            'icono' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'documentos_count' => 18
        ],
        [
            'slug' => 'servicios-educativos',
            'nombre' => 'Servicios Educativos',
            'descripcion' => 'Planes de estudio, becas, convenios académicos y trámites escolares.',
            'icono' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
            'documentos_count' => 15
        ]
    ];

    return view('welcome', compact('areas'));
})->name('home');

// 2. Vista Detallada de Documentos por Área (RF-02, RF-03)
Route::get('/area/{slug}', function ($slug) {
    $areas = [
        'direccion-general' => [
            'nombre' => 'Dirección General',
            'descripcion' => 'Normatividad, informes de gestión directiva y acuerdos institucionales.',
        ],
        'recursos-humanos' => [
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Estructura orgánica, directorio de servidores públicos, remuneraciones y convocatorias.',
        ],
        'finanzas-contabilidad' => [
            'nombre' => 'Finanzas y Contabilidad',
            'descripcion' => 'Presupuestos asignados, estados financieros, informes del ejercicio del gasto y cuentas públicas.',
        ],
        'servicios-educativos' => [
            'nombre' => 'Servicios Educativos',
            'descripcion' => 'Planes de estudio, programas de becas, convenios institucionales y estadísticas educativas.',
        ],
    ];

    if (!array_key_exists($slug, $areas)) {
        abort(404);
    }

    $area = $areas[$slug];

    $documentos = [
        [
            'id' => 1,
            'nombre' => 'Organigrama Institucional CONALEP 2026',
            'categoria' => 'Estructura Orgánica',
            'fecha_publicacion' => '15/01/2026',
            'formato' => 'PDF',
            'tamaño' => '1.2 MB',
            'url' => '#',
        ],
        [
            'id' => 2,
            'nombre' => 'Tabulador de Sueldos y Salarios Vistas 2026',
            'categoria' => 'Remuneraciones',
            'fecha_publicacion' => '01/02/2026',
            'formato' => 'PDF',
            'tamaño' => '850 KB',
            'url' => '#',
        ],
        [
            'id' => 3,
            'nombre' => 'Convocatoria Pública de Contratación Docente 2026-A',
            'categoria' => 'Convocatorias',
            'fecha_publicacion' => '10/02/2026',
            'formato' => 'PDF',
            'tamaño' => '2.1 MB',
            'url' => '#',
        ],
    ];

    return view('area-detalle', compact('area', 'documentos'));
})->name('areas.public.show');

// 3. Buscador Global Público (RF-05)
Route::get('/buscar', function (Request $request) {
    $query = $request->input('q', '');

    $resultados = [
        [
            'id' => 1,
            'nombre' => 'Presupuesto Anual de Egresos 2026',
            'area' => 'Finanzas y Contabilidad',
            'categoria' => 'Presupuestos',
            'fecha_publicacion' => '05/01/2026',
            'formato' => 'PDF',
            'tamaño' => '3.4 MB',
            'url' => '#',
        ],
        [
            'id' => 2,
            'nombre' => 'Tabulador de Sueldos y Salarios 2026',
            'area' => 'Recursos Humanos',
            'categoria' => 'Remuneraciones',
            'fecha_publicacion' => '01/02/2026',
            'formato' => 'PDF',
            'tamaño' => '850 KB',
            'url' => '#',
        ],
    ];

    return view('buscar-resultados', compact('query', 'resultados'));
})->name('buscar.public');

// 4. Formulario / Contacto de Transparencia
Route::get('/contacto-transparencia', function () {
    return view('contacto-transparencia');
})->name('contacto.public');

// Rutas Autenticadas y Panel de Administración
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('areas', \App\Http\Controllers\Admin\AreaController::class);

        Route::prefix('areas/{area}/secciones')->name('areas.secciones.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SeccionController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\SeccionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SeccionController::class, 'store'])->name('store');
            Route::get('/{seccion}/editar', [\App\Http\Controllers\Admin\SeccionController::class, 'edit'])->name('edit');
            Route::put('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'update'])->name('update');
            Route::delete('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('areas/{area}/documentos')->name('areas.documentos.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\DocumentoController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'store'])->name('store');
            Route::get('/{documento}/editar', [\App\Http\Controllers\Admin\DocumentoController::class, 'edit'])->name('edit');
            Route::put('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'update'])->name('update');
            Route::delete('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__.'/auth.php';