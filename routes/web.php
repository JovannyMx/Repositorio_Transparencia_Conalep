<?php

use App\Http\Controllers\ProfileController;
use App\Models\Area;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Administración CONALEP Jalisco - Datos Reales)
|--------------------------------------------------------------------------
*/

// 1. Portal Principal (Home)
Route::get('/', function () {
    if (Schema::hasTable('areas')) {
        $areas = Area::where('activo', true)
            ->withCount('documentos')
            ->orderBy('orden', 'asc')
            ->get();
    } else {
        $areas = collect();
    }

    return view('welcome', compact('areas'));
})->name('home');

// 2. Vista Detallada por Área
Route::get('/area/{slug}', function ($slug) {
    $area = Area::where('slug', $slug)
        ->where('activo', true)
        ->with(['secciones', 'documentos' => function ($q) {
            $q->latest();
        }])
        ->firstOrFail();

    $documentos = $area->documentos;

    return view('area-detalle', compact('area', 'documentos'));
})->name('areas.public.show');

// 3. Buscador Global Público (Consulta Real a BD)
Route::get('/buscar', function (Request $request) {
    $query = trim($request->input('q', ''));

    if (!empty($query)) {
        $resultados = Documento::with('area')
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%")
                  ->orWhere('categoria', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->get();
    } else {
        $resultados = collect();
    }

    return view('buscar-resultados', compact('query', 'resultados'));
})->name('buscar.public');

// 4. Contacto de Transparencia
Route::get('/contacto-transparencia', function () {
    return view('contacto-transparencia');
})->name('contacto.public');

// 5. Visualización Pública de Documentos (Sin autenticación para iframe/modal)
Route::get('/documentos/{documento}/ver', function (Documento $documento) {
    // Si utilizas Spatie Media Library
    if (method_exists($documento, 'getFirstMedia')) {
        $media = $documento->getFirstMedia('archivo');
        if ($media) {
            return response()->file($media->getPath(), [
                'Content-Type' => $media->mime_type,
                'Content-Disposition' => 'inline; filename="' . $media->file_name . '"'
            ]);
        }
    }

    // Alternativa en caso de almacenar el path directamente en la base de datos
    if (!empty($documento->archivo_path) && file_exists(storage_path('app/public/' . $documento->archivo_path))) {
        return response()->file(storage_path('app/public/' . $documento->archivo_path));
    }

    abort(404, 'El archivo solicitado no se encuentra disponible.');
})->name('documentos.public.ver');


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