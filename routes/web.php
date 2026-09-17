<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Ruta Pública: Página de Inicio
Route::get('/', function () {
    $areas = [
        [
            'id' => 1,
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Organigramas, nóminas, convocatorias y normatividad laboral.',
            'slug' => 'recursos-humanos',
            'documentos_count' => 12,
        ],
        [
            'id' => 2,
            'nombre' => 'Recursos Materiales',
            'descripcion' => 'Licitaciones, contratos, inventarios y compras directas.',
            'slug' => 'recursos-materiales',
            'documentos_count' => 8,
        ],
        [
            'id' => 3,
            'nombre' => 'Finanzas y Contabilidad',
            'descripcion' => 'Informes financieros, presupuestos anuales y auditorías.',
            'slug' => 'finanzas-contabilidad',
            'documentos_count' => 15,
        ],
        [
            'id' => 4,
            'nombre' => 'Planeación y Evaluación',
            'descripcion' => 'Planes institucionales, estadísticas y reportes de indicadores.',
            'slug' => 'planeacion-evaluacion',
            'documentos_count' => 5,
        ],
    ];

    return view('welcome', compact('areas'));
});

// Ruta Pública: Detalle de Área y sus Documentos (RF-02)
Route::get('/area/{slug}', function ($slug) {
    // Datos simulados del área seleccionada
    $area = [
        'nombre' => 'Recursos Humanos',
        'descripcion' => 'Consulta la información relativa a estructura orgánica, nóminas, convocatorias y normatividad laboral vigente.',
    ];

    // Lista de documentos simulados asociados al área (RF-03, RF-06)
    $documentos = [
        [
            'id' => 1,
            'nombre' => 'Organigrama General de la Dirección 2026',
            'categoria' => 'Estructura Orgánica',
            'fecha_publicacion' => '15/01/2026',
            'formato' => 'PDF',
            'tamaño' => '1.2 MB',
            'url' => '#',
        ],
        [
            'id' => 2,
            'nombre' => 'Tabulador de Sueldos y Salarios 2026',
            'categoria' => 'Remuneraciones',
            'fecha_publicacion' => '01/02/2026',
            'formato' => 'PDF',
            'tamaño' => '850 KB',
            'url' => '#',
        ],
        [
            'id' => 3,
            'nombre' => 'Convocatoria Pública de Personal Técnico',
            'categoria' => 'Convocatorias',
            'fecha_publicacion' => '10/03/2026',
            'formato' => 'PDF',
            'tamaño' => '2.1 MB',
            'url' => '#',
        ],
    ];

    return view('area-detalle', compact('area', 'documentos'));
})->name('areas.public.show');

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