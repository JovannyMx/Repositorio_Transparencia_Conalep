<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Aqui estan las rutas para la parte publica :)

Route::get('/', [\App\Http\Controllers\Public\AreaController::class, 'index'])->name('publico.areas.index');
Route::get('/area/{area}', [\App\Http\Controllers\Public\AreaController::class, 'show'])->name('publico.areas.show');
Route::get('/documento/{liga}', [\App\Http\Controllers\Public\DocumentoController::class, 'preview'])->name('publico.documentos.preview');
Route::get('/documento/{liga}/descargar', [\App\Http\Controllers\Public\DocumentoController::class, 'descargar'])->name('publico.documentos.descargar');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('areas', [\App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index');
        Route::get('areas/create', [\App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create')->middleware('role:admin');
        Route::post('areas', [\App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store')->middleware('role:admin');
        Route::get('areas/{area}/edit', [\App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit')->middleware('role:admin');
        Route::put('areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update')->middleware('role:admin');
        Route::delete('areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy')->middleware('role:admin');

        Route::prefix('areas/{area}/secciones')->name('areas.secciones.')->middleware('area.access')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SeccionController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\SeccionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SeccionController::class, 'store'])->name('store');
            Route::get('/{seccion}/editar', [\App\Http\Controllers\Admin\SeccionController::class, 'edit'])->name('edit');
            Route::put('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'update'])->name('update');
            Route::delete('/{seccion}', [\App\Http\Controllers\Admin\SeccionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('areas/{area}/documentos')->name('areas.documentos.')->middleware('area.access')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'index'])->name('index');
            Route::get('/crear', [\App\Http\Controllers\Admin\DocumentoController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DocumentoController::class, 'store'])->name('store');
            Route::get('/{documento}/editar', [\App\Http\Controllers\Admin\DocumentoController::class, 'edit'])->name('edit');
            Route::put('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'update'])->name('update');
            Route::delete('/{documento}', [\App\Http\Controllers\Admin\DocumentoController::class, 'destroy'])->name('destroy');
            Route::get('/{documento}/vista-previa', [\App\Http\Controllers\Admin\DocumentoController::class, 'preview'])->name('preview');
        });

        Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)->middleware('role:admin');
    });
});

require __DIR__.'/auth.php';