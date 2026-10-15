<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\AreaController as PublicAreaController;
use App\Http\Controllers\Public\DocumentoController as PublicDocumentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Portal de Transparencia)
|--------------------------------------------------------------------------
*/

// 1. Portal Principal (Home)
Route::get('/', function () {
    // Si la tabla áreas existe en BD, cargamos los datos reales; si no, usamos el fallback
    if (\Illuminate\Support\Facades\Schema::hasTable('areas')) {
        $areas = \App\Models\Area::where('activo', true)
                    ->withCount('documentos')
                    ->get();
    } else {
        $areas = [];
    }

    return view('welcome', compact('areas'));
})->name('home');

// 2. Vista Detallada por Área
Route::get('/area/{slug}', function ($slug) {
    $area = \App\Models\Area::where('slug', $slug)
                ->with(['documentos', 'secciones'])
                ->firstOrFail();

    $documentos = $area->documentos;

    return view('area-detalle', compact('area', 'documentos'));
})->name('areas.public.show');

// 3. Buscador Global Público
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

// 4. Contacto de Transparencia
Route::get('/contacto-transparencia', function () {
    return view('contacto-transparencia');
})->name('contacto.public');


/*
|--------------------------------------------------------------------------
| Rutas Autenticadas y Panel de Administración
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {

        // Administración de Áreas
        Route::get('areas', [\App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index');
        Route::get('areas/create', [\App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create')->middleware('role:admin');
        Route::post('areas', [\App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store')->middleware('role:admin');
        Route::get('areas/{area}/edit', [\App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit')->middleware('role:admin');
        Route::put('areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update')->middleware('role:admin');
        Route::delete('areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy')->middleware('role:admin');

        // Secciones por Área
        Route::prefix('areas/{area}/secciones')->name('areas.secciones.')->middleware('area.access')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SeccionController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\SeccionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SeccionController::class, 'store'])->name('store');
            Route::get('/{seccion}/editar', [\App\Http\Controllers\Admin\SeccionController::class, 'edit'])->name('edit');
            Route::put('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'update'])->name('update');
            Route::delete('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'destroy'])->name('destroy');
        });

        // Documentos por Área
        Route::prefix('areas/{area}/documentos')->name('areas.documentos.')->middleware('area.access')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\DocumentoController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'store'])->name('store');
            Route::get('/{documento}/editar', [\App\Http\Controllers\Admin\DocumentoController::class, 'edit'])->name('edit');
            Route::put('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'update'])->name('update');
            Route::delete('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'destroy'])->name('destroy');
            Route::get('/{documento}/vista-previa', [\App\Http\Controllers\Admin\DocumentoController::class, 'preview'])->name('preview');
        });

        // Gestión de Usuarios (Solo Administrador)
        Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)->middleware('role:admin');
    });
});

require __DIR__.'/auth.php';