<?php

use App\Http\Controllers\ProfileController;
use App\Models\Area;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Administración CONALEP Jalisco - Datos Reales)
|--------------------------------------------------------------------------
*/

// 1. Alias directo para que 'publico.areas.index' no marque error en Blade
Route::get('/', function () {
    if (Schema::hasTable('areas')) {
        $areas = Area::where('activo', true)
            ->withCount(['documentos' => function ($query) {
                $query->where('activo', true)
                      ->where(function ($q) {
                          $q->whereNull('fecha_vencimiento')
                            ->orWhere('fecha_vencimiento', '>=', Carbon::today());
                      });
            }])
            ->orderBy('orden', 'asc')
            ->get();
    } else {
        $areas = collect();
    }

    return view('welcome', compact('areas'));
})->name('publico.areas.index');

// Mantenemos también la ruta nombrada 'home' apuntando a la misma lógica
Route::get('/home', function () {
    return redirect()->route('publico.areas.index');
})->name('home');

// 2. Vista Detallada por Área (Incrementa 'visitas' al ingresar al Área)
Route::get('/area/{slug}', function ($slug) {
    $area = Area::where('slug', $slug)
        ->where('activo', true)
        ->with(['secciones', 'documentos' => function ($q) {
            $q->where('activo', true)
              ->where(function ($query) {
                  $query->whereNull('fecha_vencimiento')
                        ->orWhere('fecha_vencimiento', '>=', Carbon::today());
              })
              ->latest();
        }])
        ->firstOrFail();

    // REGISTRO DE MÉTRICA: Incrementar visitas al área
    $area->increment('visitas');

    $documentos = $area->documentos;

    return view('area-detalle', compact('area', 'documentos'));
})->name('areas.public.show');

// 3. Buscador Global Público (Consulta Real a BD)
Route::get('/buscar', function (Request $request) {
    $query = trim($request->input('q', ''));

    if (!empty($query)) {
        $resultados = Documento::with(['area', 'seccion'])
            ->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('fecha_vencimiento')
                  ->orWhere('fecha_vencimiento', '>=', Carbon::today());
            })
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhereHas('seccion', function ($sq) use ($query) {
                      $sq->where('nombre', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('area', function ($aq) use ($query) {
                      $aq->where('nombre', 'LIKE', "%{$query}%");
                  });
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

// 5. Visualización Pública de Documentos (Botón 'Visualizar' -> Incrementa 'vistas')
Route::get('/documentos/{documento}/ver', function ($id) {
    $documento = Documento::findOrFail($id);

    if (!$documento->activo || ($documento->fecha_vencimiento && $documento->fecha_vencimiento->isPast() && !$documento->fecha_vencimiento->isToday())) {
        abort(404, 'El documento ha vencido o no está disponible.');
    }

    // REGISTRO DE MÉTRICA: Incrementar contador de vistas
    $documento->increment('vistas');

    // 1. Si usa Spatie MediaLibrary
    if (method_exists($documento, 'getFirstMedia')) {
        $media = $documento->getFirstMedia('archivo');
        if ($media && file_exists($media->getPath())) {
            return response()->file($media->getPath(), [
                'Content-Type' => $media->mime_type,
                'Content-Disposition' => 'inline; filename="' . $media->file_name . '"'
            ]);
        }
    }

    // 2. Si se guarda por archivo_path en storage/app/public
    if (!empty($documento->archivo_path) && file_exists(storage_path('app/public/' . $documento->archivo_path))) {
        return response()->file(storage_path('app/public/' . $documento->archivo_path));
    }

    abort(404, 'El archivo solicitado no se encuentra disponible.');
})->name('documentos.public.ver');

// 6. Descarga Pública de Documentos (Botón 'Descargar' -> Incrementa 'descargas')
Route::get('/documentos/{documento}/descargar', function ($id) {
    $documento = Documento::findOrFail($id);

    if (!$documento->activo || ($documento->fecha_vencimiento && $documento->fecha_vencimiento->isPast() && !$documento->fecha_vencimiento->isToday())) {
        abort(404, 'El documento ha vencido o no está disponible.');
    }

    // REGISTRO DE MÉTRICA: Incrementar contador de descargas
    $documento->increment('descargas');

    // 1. Si usa Spatie MediaLibrary
    if (method_exists($documento, 'getFirstMedia')) {
        $media = $documento->getFirstMedia('archivo');
        if ($media && file_exists($media->getPath())) {
            return response()->download($media->getPath(), $media->file_name);
        }
    }

    // 2. Si se guarda por archivo_path en storage public
    if (!empty($documento->archivo_path) && Storage::disk('public')->exists($documento->archivo_path)) {
        $rutaFisica = storage_path('app/public/' . $documento->archivo_path);
        $extension = pathinfo($rutaFisica, PATHINFO_EXTENSION) ?: 'pdf';

        return response()->download($rutaFisica, $documento->nombre . '.' . $extension);
    }

    abort(404, 'El archivo solicitado no se encuentra disponible.');
})->name('documentos.public.descargar');


/*
|--------------------------------------------------------------------------
| Rutas Autenticadas y Panel de Administración
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    // Consultas para el panel de métricas y analíticas
    $totalVisitasAreas = Area::sum('visitas');
    $totalVistasDocs = Documento::sum('vistas');
    $totalDescargasDocs = Documento::sum('descargas');
    $totalAreas = Area::where('activo', true)->count();
    $totalDocs = Documento::where('activo', true)->count();

    // Top 5 áreas más consultadas
    $areasPopulares = Area::where('activo', true)
        ->orderBy('visitas', 'desc')
        ->take(5)
        ->get();

    // Top 5 documentos con más interacción (vistas + descargas)
    $documentosPopulares = Documento::where('activo', true)
        ->with('area')
        ->orderByRaw('(vistas + descargas) DESC')
        ->take(5)
        ->get();

    return view('dashboard', compact(
        'totalVisitasAreas',
        'totalVistasDocs',
        'totalDescargasDocs',
        'totalAreas',
        'totalDocs',
        'areasPopulares',
        'documentosPopulares'
    ));
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
            
            // Ruta para restaurar documentos lógicamente desde la papelera
            Route::patch('/{id}/restore', [\App\Http\Controllers\Admin\DocumentoController::class, 'restore'])->name('restore');
        });

        // Gestión de Usuarios (Solo Administrador)
        Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)->middleware('role:admin');
    });
});

require __DIR__.'/auth.php';